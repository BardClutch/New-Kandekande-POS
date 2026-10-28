<?php

namespace App\Http\Controllers;

use App\Models\ShiftKasir;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShiftKasirController extends Controller
{
    /**
     * Tampilkan daftar riwayat semua shift kasir (untuk Owner & Kasir)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ShiftKasir::with(['user', 'transaksis'])
            ->orderBy('id_shift', 'desc');

        // Jika bukan Owner, hanya bisa melihat shift miliknya sendiri
        if (!$user->isOwner()) {
            $query->where('id_user', $user->id);
        }

        // Filter tanggal jika ada
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_buka', $request->tanggal);
        }

        $shifts = $query->paginate(15);

        // Statistik Ringkasan Hari Ini
        $today = Carbon::today();
        $baseStatQuery = ShiftKasir::whereDate('waktu_buka', $today);
        if (!$user->isOwner()) {
            $baseStatQuery->where('id_user', $user->id);
        }

        $activeShiftCount = (clone $baseStatQuery)->where('status', 'Open')->count();
        $totalKasHariIni = (clone $baseStatQuery)->sum('total_penjualan_tunai');
        $totalNonTunaiHariIni = (clone $baseStatQuery)->sum('total_penjualan_nontunai');
        $totalSelisihHariIni = (clone $baseStatQuery)->where('status', 'Closed')->sum('selisih');

        $currentActiveShift = $user->activeShift();

        return view('shift.index', compact(
            'shifts',
            'activeShiftCount',
            'totalKasHariIni',
            'totalNonTunaiHariIni',
            'totalSelisihHariIni',
            'currentActiveShift'
        ));
    }

    /**
     * Buka Shift Kasir Baru (Input Modal Awal Uang Kembalian)
     */
    public function buka(Request $request)
    {
        $user = Auth::user();

        // Periksa apakah user sudah memiliki shift yang masih berjalan
        $activeShift = $user->activeShift();
        if ($activeShift) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda masih memiliki shift yang sedang aktif (Shift #' . $activeShift->id_shift . '). Harap tutup shift sebelumnya terlebih dahulu.'
                ], 422);
            }
            return redirect()->back()->with('error', 'Anda masih memiliki shift yang sedang aktif!');
        }

        $request->validate([
            'modal_awal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $modalAwal = (float) $request->input('modal_awal', 0);

        $shift = ShiftKasir::create([
            'id_user' => $user->id,
            'waktu_buka' => now(),
            'modal_awal' => $modalAwal,
            'kas_akhir_sistem' => $modalAwal,
            'status' => 'Open',
            'catatan' => $request->input('catatan'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift kasir #' . $shift->id_shift . ' berhasil dibuka dengan modal awal Rp ' . number_format($modalAwal, 0, ',', '.'),
                'shift' => $shift
            ]);
        }

        return redirect()->route('kasir.index')->with('success', 'Shift kasir berhasil dibuka! Selamat bertugas.');
    }

    /**
     * Tutup Shift Kasir (Input Uang Fisik Kas, Hitung Selisih & Selesai)
     */
    public function tutup(Request $request, ShiftKasir $shift)
    {
        $user = Auth::user();

        // Otorisasi: hanya kasir pemilik shift atau Owner yang bisa menutup
        if ($shift->id_user !== $user->id && !$user->isOwner()) {
            abort(403, 'Anda tidak berhak menutup shift kasir pengguna lain.');
        }

        if ($shift->status !== 'Open') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Shift ini sudah ditutup sebelumnya.'], 422);
            }
            return redirect()->back()->with('error', 'Shift ini sudah ditutup sebelumnya.');
        }

        $request->validate([
            'kas_akhir_fisik' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        // Hitung ulang transaksi agar akurat
        $shift->hitungUlangKas();

        $kasFisik = (float) $request->input('kas_akhir_fisik', 0);
        $selisih = $kasFisik - (float) $shift->kas_akhir_sistem;

        $catatanTambahan = $request->input('catatan');
        if (!empty($catatanTambahan)) {
            $shift->catatan = $shift->catatan 
                ? $shift->catatan . " | Penutupan: " . $catatanTambahan 
                : $catatanTambahan;
        }

        $shift->update([
            'waktu_tutup' => now(),
            'kas_akhir_fisik' => $kasFisik,
            'selisih' => $selisih,
            'status' => 'Closed',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Shift #' . $shift->id_shift . ' berhasil ditutup. Selisih kas: Rp ' . number_format($selisih, 0, ',', '.'),
                'cetak_url' => route('shift.cetak', $shift->id_shift)
            ]);
        }

        return redirect()->route('shift.index')
            ->with('success', 'Shift kasir #' . $shift->id_shift . ' berhasil ditutup!')
            ->with('cetak_shift_id', $shift->id_shift);
    }

    /**
     * API Status Shift Kasir Saat Ini
     */
    public function status(Request $request)
    {
        $user = Auth::user();
        $activeShift = $user->activeShift();

        if ($activeShift) {
            $activeShift->hitungUlangKas();
            return response()->json([
                'has_active_shift' => true,
                'shift' => [
                    'id_shift' => $activeShift->id_shift,
                    'waktu_buka' => $activeShift->waktu_buka->format('d/m/Y H:i'),
                    'modal_awal' => (float)$activeShift->modal_awal,
                    'total_penjualan_tunai' => (float)$activeShift->total_penjualan_tunai,
                    'total_penjualan_nontunai' => (float)$activeShift->total_penjualan_nontunai,
                    'total_transaksi' => $activeShift->total_transaksi,
                    'kas_akhir_sistem' => (float)$activeShift->kas_akhir_sistem,
                ]
            ]);
        }

        return response()->json([
            'has_active_shift' => false,
            'shift' => null,
        ]);
    }

    /**
     * Cetak Struk Rekap Shift Kasir (Thermal Paper 58mm/80mm)
     */
    public function cetak(ShiftKasir $shift)
    {
        $shift->load(['user', 'transaksis.detailTransaksis.produk']);
        $shift->hitungUlangKas();

        return view('shift.cetak', compact('shift'));
    }
}
