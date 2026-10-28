<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Tutup Shift #{{ $shift->id_shift }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            width: 58mm; /* Ukuran standar printer thermal */
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .font-bold {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .double-divider {
            border-top: 2px solid #000;
            margin: 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 2px 0;
            vertical-align: top;
        }
        .header {
            margin-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 15px;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .footer {
            margin-top: 15px;
            font-size: 9px;
        }
        @media print {
            body {
                background-color: white;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            display: block;
            width: 100%;
            background: #4f46e5;
            color: #fff;
            padding: 8px;
            text-align: center;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 12px;
            font-family: sans-serif;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="javascript:window.print()" class="btn-print">Cetak Struk Shift Ini</a>
    </div>

    <div class="header text-center">
        <h2>KANDE-KANDE POS</h2>
        <p>Jl. Skripsi Rehan No. 123</p>
        <p>Telp: 08123456789</p>
        <div class="double-divider"></div>
        <p class="font-bold" style="font-size: 12px; margin-top: 4px;">LAPORAN TUTUP SHIFT KASIR</p>
    </div>

    <table style="font-size: 10px;">
        <tr>
            <td>ID Shift</td>
            <td>: #{{ str_pad($shift->id_shift, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $shift->user->name ?? 'Kasir' }}</td>
        </tr>
        <tr>
            <td>Buka</td>
            <td>: {{ \Carbon\Carbon::parse($shift->waktu_buka)->format('d/m/y H:i') }}</td>
        </tr>
        <tr>
            <td>Tutup</td>
            <td>: {{ $shift->waktu_tutup ? \Carbon\Carbon::parse($shift->waktu_tutup)->format('d/m/y H:i') : 'Masih Buka' }}</td>
        </tr>
        @if($shift->waktu_tutup)
        <tr>
            <td>Durasi</td>
            <td>: {{ \Carbon\Carbon::parse($shift->waktu_buka)->diffForHumans(\Carbon\Carbon::parse($shift->waktu_tutup), true) }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table style="font-size: 10px;">
        <tr>
            <td colspan="2" class="font-bold">RINGKASAN PENJUALAN:</td>
        </tr>
        <tr>
            <td>Total Nota Trx</td>
            <td class="text-right">{{ $shift->total_transaksi }} transaksi</td>
        </tr>
        <tr>
            <td>Penjualan Tunai</td>
            <td class="text-right">Rp {{ number_format($shift->total_penjualan_tunai, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Penjualan Non-Tunai</td>
            <td class="text-right">Rp {{ number_format($shift->total_penjualan_nontunai, 0, ',', '.') }}</td>
        </tr>
        <tr class="font-bold">
            <td>Total Omset Shift</td>
            <td class="text-right">Rp {{ number_format($shift->total_penjualan_tunai + $shift->total_penjualan_nontunai, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table style="font-size: 10px;">
        <tr>
            <td colspan="2" class="font-bold">REKAPITULASI KAS LACI:</td>
        </tr>
        <tr>
            <td>Modal Awal Kasir</td>
            <td class="text-right">Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>(+) Penerimaan Tunai</td>
            <td class="text-right">Rp {{ number_format($shift->total_penjualan_tunai, 0, ',', '.') }}</td>
        </tr>
        <tr class="font-bold">
            <td>(=) Kas Sistem di Laci</td>
            <td class="text-right">Rp {{ number_format($shift->kas_akhir_sistem, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kas Fisik Dihitung</td>
            <td class="text-right font-bold">
                Rp {{ number_format($shift->kas_akhir_fisik ?? 0, 0, ',', '.') }}
            </td>
        </tr>
        <tr class="font-bold" style="font-size: 11px;">
            <td>Selisih Kas</td>
            <td class="text-right">
                @if(is_null($shift->selisih))
                    -
                @elseif($shift->selisih == 0)
                    Rp 0 (PAS)
                @elseif($shift->selisih > 0)
                    +Rp {{ number_format($shift->selisih, 0, ',', '.') }} (SURPLUS)
                @else
                    -Rp {{ number_format(abs($shift->selisih), 0, ',', '.') }} (MINUS)
                @endif
            </td>
        </tr>
    </table>

    @if($shift->catatan)
    <div class="divider"></div>
    <div style="font-size: 10px;">
        <span class="font-bold">Catatan:</span><br>
        {{ $shift->catatan }}
    </div>
    @endif

    <div class="divider"></div>

    <!-- Tanda Tangan Kasir & Supervisor -->
    <table style="width: 100%; margin-top: 15px; font-size: 9px; text-align: center;">
        <tr>
            <td style="width: 50%;">Kasir Bertugas,</td>
            <td style="width: 50%;">Supervisor/Owner,</td>
        </tr>
        <tr>
            <td style="height: 35px;"></td>
            <td style="height: 35px;"></td>
        </tr>
        <tr>
            <td class="font-bold">({{ $shift->user->name ?? 'Kasir' }})</td>
            <td class="font-bold">( .................... )</td>
        </tr>
    </table>

    <div class="footer text-center">
        <p>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>*** DOKUMEN REKAP SHIFT RESMI ***</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
