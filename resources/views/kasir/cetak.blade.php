<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk #{{ $transaksi->id_transaksi }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
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
        .font-bold {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
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
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
        }
        .header p {
            margin: 2px 0;
        }
        .footer {
            margin-top: 10px;
            font-size: 10px;
        }
        
        /* Hilangkan elemen lain saat diprint, pastikan background putih */
        @media print {
            body {
                background-color: white;
            }
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h2>KANDE-KANDE POS</h2>
        <p>Jl. Skripsi Rehan No. 123</p>
        <p>Telp: 08123456789</p>
    </div>

    <div class="divider"></div>

    <table style="font-size: 11px;">
        <tr>
            <td>No</td>
            <td>: #{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Tgl</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal_waktu)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $transaksi->user->name ?? 'Kasir' }}</td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td>: {{ $transaksi->jenis_pesanan }} ({{ $transaksi->metode_pembayaran }})</td>
        </tr>
        @if($transaksi->nama_pelanggan)
        <tr>
            <td>Plg</td>
            <td>: {{ $transaksi->nama_pelanggan }} {{ $transaksi->no_hp_pelanggan ? '('.$transaksi->no_hp_pelanggan.')' : '' }}</td>
        </tr>
        @endif
        @if($transaksi->catatan_pesanan)
        <tr>
            <td>Note</td>
            <td>: {{ $transaksi->catatan_pesanan }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table>
        @foreach($transaksi->details as $detail)
            <tr>
                <td colspan="3">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</td>
            </tr>
            <tr>
                <td>{{ $detail->qty_beli }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">
                    @if($detail->potongan_diskon > 0)
                        <br><small>- (Disc: {{ number_format($detail->potongan_diskon, 0, ',', '.') }})</small>
                    @endif
                </td>
                <td class="text-right font-bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table>
        <tr>
            <td class="font-bold">TOTAL:</td>
            <td class="text-right font-bold" style="font-size: 14px;">Rp {{ number_format($transaksi->total_belanja, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
    </div>

    <script>
        // Otomatis memicu dialog print saat halaman dimuat
        window.onload = function() {
            window.print();
            // Opsional: Tutup tab setelah print selesai (hanya berlaku jika dibuka dari script)
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</body>
</html>
