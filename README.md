# Kandekande POS

Sistem kasir dan operasional untuk toko roti dan kue Kandekande. Satu aplikasi untuk kasir, dapur, gudang, dan pemilik, ditambah halaman depan untuk pelanggan.

Dibangun dengan Laravel 12, Blade, Alpine.js, dan Tailwind CSS.

<p align="center">
  <img src="docs/screenshots/landing-hero-siang.png" alt="Halaman depan Kandekande, mode siang" width="49%">
  <img src="docs/screenshots/landing-hero-malam.png" alt="Halaman depan Kandekande, mode malam" width="49%">
</p>

## Isi

- [Fitur](#fitur)
- [Tangkapan layar](#tangkapan-layar)
- [Desain](#desain)
- [Menjalankan di komputer sendiri](#menjalankan-di-komputer-sendiri)
- [Akun contoh](#akun-contoh)
- [Struktur tampilan](#struktur-tampilan)
- [Dokumen untuk kontributor](#dokumen-untuk-kontributor)

## Fitur

**Halaman depan (`/`)**
Halaman untuk pelanggan: menu hari ini, keunggulan toko, pesanan kue kustom, galeri dapur, jadwal buka, dan tanya jawab. Daftar menu, harga, dan status stok (tersedia, habis, dibuat sesuai pesanan) dibaca langsung dari database. Status buka atau tutup mengikuti jam perangkat, dan ada saklar mode siang dan malam.

**Per peran**

| Peran | Yang bisa dilakukan |
| --- | --- |
| Owner | Dashboard penjualan, produk, kategori, resep, promo, bahan baku, restock, laporan dan ekspor CSV, pelanggan, karyawan beserta hak akses, serta semua fitur kasir dan dapur |
| Kasir | Layar kasir (POS), buka dan tutup shift dengan rekap kas, riwayat dan cetak ulang struk, pelanggan |
| Dapur | Layar dapur: antrean pesanan dari kasir, catat produksi per batch atau pesanan kustom, kalkulator hasil panggang, notifikasi pesanan baru |
| Admin Stok | Bahan baku, restock, dan peringatan stok menipis |
| Peran kustom | Hak akses disusun sendiri oleh Owner per menu |

## Tangkapan layar

### Halaman depan dan login

<p align="center">
  <img src="docs/screenshots/login-siang.png" alt="Halaman masuk, mode siang" width="49%">
  <img src="docs/screenshots/login-malam.png" alt="Halaman masuk, mode malam" width="49%">
</p>

Halaman depan lengkap (dari atas sampai footer): [docs/screenshots/landing-halaman-penuh.jpg](docs/screenshots/landing-halaman-penuh.jpg)

### Owner

<p align="center">
  <img src="docs/screenshots/owner-dashboard.png" alt="Dashboard Owner" width="49%">
  <img src="docs/screenshots/owner-laporan.png" alt="Laporan penjualan" width="49%">
</p>
<p align="center">
  <img src="docs/screenshots/owner-produk.png" alt="Daftar produk" width="49%">
  <img src="docs/screenshots/owner-resep.png" alt="Daftar resep" width="49%">
</p>

### Kasir

<p align="center">
  <img src="docs/screenshots/kasir-pos.png" alt="Layar kasir, mode siang" width="49%">
  <img src="docs/screenshots/kasir-pos-malam.png" alt="Layar kasir, mode malam" width="49%">
</p>
<p align="center">
  <img src="docs/screenshots/kasir-dashboard.png" alt="Dashboard Kasir" width="49%">
  <img src="docs/screenshots/kasir-shift.png" alt="Shift dan kas" width="49%">
</p>

### Dapur

<p align="center">
  <img src="docs/screenshots/dapur-layar.png" alt="Layar dapur" width="49%">
  <img src="docs/screenshots/dapur-dashboard.png" alt="Dashboard Dapur" width="49%">
</p>

### Admin Stok

<p align="center">
  <img src="docs/screenshots/stok-dashboard.png" alt="Dashboard Admin Stok" width="49%">
  <img src="docs/screenshots/stok-bahan-baku.png" alt="Daftar bahan baku" width="49%">
</p>
<p align="center">
  <img src="docs/screenshots/stok-restock.png" alt="Riwayat restock" width="49%">
</p>

## Desain

Tampilannya sengaja dibuat tenang dan rapi, supaya kasir dan dapur bisa dipakai berjam-jam tanpa lelah.

- **Huruf**: Bricolage Grotesque untuk judul dan angka, Albert Sans untuk teks.
- **Warna**: krem hangat sebagai dasar, tinta cokelat gelap, satu warna aksen oranye. Tidak ada gradasi, bayangan tebal, atau emoji.
- **Mode siang dan malam**: ikut tema perangkat, bisa diganti lewat saklar di kanan atas. Di aplikasi, pilihan tersimpan per pengguna.
- **Tata letak**: sidebar dan topbar yang sama untuk semua peran, halaman memenuhi lebar layar, tabel dengan kolom pertama melebar dan kolom lain rapat.
- **Ukuran layar**: halaman depan dan layar aplikasi responsif dari ponsel sampai desktop. Struk cetak sengaja dibiarkan polos untuk printer 58 mm.

## Menjalankan di komputer sendiri

Kebutuhan: PHP 8.2 atau lebih baru, Composer, Node.js 18 atau lebih baru, dan database (MySQL atau SQLite).

```bash
git clone https://github.com/BardClutch/New-Kandekande-POS.git
cd New-Kandekande-POS

composer install
cp .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`, lalu jalankan:

```bash
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Buka `http://localhost:8000`. Untuk pengembangan tampilan, jalankan `npm run dev` di terminal terpisah.

## Akun contoh

`php artisan migrate --seed` membuat empat akun untuk pengembangan lokal. Kata sandinya `password`.

| Peran | Email |
| --- | --- |
| Owner | `owner@kandekande.com` |
| Kasir | `kasir@kandekande.com` |
| Dapur | `dapur@kandekande.com` |
| Admin Stok | `gudang@kandekande.com` |

Halaman masuk punya tombol "Login cepat (debug)" yang mengisi akun di atas. Hapus tombol itu dan ganti semua kata sandi sebelum aplikasi dipakai di server sungguhan.

## Struktur tampilan

```
resources/views
├── landing.blade.php          Halaman depan untuk pelanggan
├── partials/                  Ilustrasi toko (SVG) dan gayanya, dipakai halaman depan dan login
├── layouts/                   app (sidebar, topbar), guest (login), navigation
├── components/                page, panel, btn, stat, stats, flash, dialog
├── auth/                      Masuk, daftar, lupa dan atur ulang kata sandi
├── dashboard/                 Dashboard per peran
├── kasir/  dapur/  shift/     Layar kasir, layar dapur, shift dan kas
└── produk/ resep/ bahanbaku/ restock/ laporan/ pelanggan/ karyawan/ ...

public/images/landing          Foto untuk halaman depan (ganti dengan foto toko sendiri)
docs/screenshots               Gambar yang dipakai di README ini
```

Menu di halaman depan dicocokkan dengan foto lewat nama produk (cheesecake, donat, roti sobek, kue ulang tahun). Produk lain memakai foto cadangan. Untuk foto sendiri, ganti berkas di `public/images/landing` dengan nama yang sama, atau ubah pencocokan di bagian atas `landing.blade.php`.

## Dokumen untuk kontributor

- [DESIGN.md](DESIGN.md): panduan desain lengkap (warna, huruf, komponen, pola halaman, mode siang dan malam, halaman depan).
- [AGENTS.md](AGENTS.md): panduan kerja untuk kontributor dan agen pengodean (perintah, struktur, model data, hak akses, aturan tampilan, pengujian, Git, dan keamanan).

## Lisensi

Proyek ini memakai kerangka [Laravel](https://laravel.com) yang berlisensi [MIT](https://opensource.org/licenses/MIT).
