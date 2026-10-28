# AGENTS.md

Panduan untuk agen pengodean (dan kontributor baru) yang bekerja di repo ini. Baca sampai habis sebelum mengubah kode. Untuk aturan visual, baca juga [DESIGN.md](DESIGN.md).

## 1. Ringkasan proyek

**Kandekande POS** adalah sistem kasir dan operasional toko roti dan kue. Satu aplikasi Laravel melayani lima kelompok pengguna: Owner, Kasir, Dapur, Admin Stok, dan peran kustom. Ada juga halaman depan publik di `/` untuk pelanggan.

Bahasa produk: **Bahasa Indonesia** (teks antarmuka, pesan galat, komentar kode, dan pesan commit).

## 2. Teknologi

| Lapisan | Pilihan |
| --- | --- |
| Bahasa dan kerangka | PHP 8.2 atau lebih baru, Laravel 12 |
| Autentikasi | Laravel Breeze (Blade) |
| Tampilan | Blade, komponen anonim di `resources/views/components` |
| Interaksi | Alpine.js 3 |
| Gaya | Tailwind CSS 3 dengan `@tailwindcss/forms`, dibangun Vite 7 |
| Database | MySQL untuk pengembangan, SQLite (`:memory:`) untuk tes |
| Tes | PHPUnit 11 |
| Gaya kode PHP | Laravel Pint |

Catatan penting: `layouts/app.blade.php` memuat **Tailwind CDN** dengan konfigurasi sendiri, selain bundel Vite. Token warna dan radius harus diubah di dua tempat (lihat [DESIGN.md](DESIGN.md#18-jebakan-yang-sudah-pernah-terjadi)).

## 3. Perintah yang sering dipakai

```bash
composer setup                  # install, salin .env, key:generate, migrate, npm install, build
composer dev                    # server, antrean, log, dan Vite sekaligus
composer test                   # config:clear lalu php artisan test
vendor/bin/pint                 # rapikan gaya kode PHP
php artisan migrate --seed      # tabel dan empat akun contoh
npm run dev                     # Vite (pengembangan)
npm run build                   # bundel produksi
php artisan view:cache && php artisan view:clear   # kompilasi semua Blade untuk menangkap galat
```

Setelah mengubah `.env`, jalankan `php artisan config:clear`.

## 4. Peta direktori

```
app/
  Http/Controllers/   Satu controller per modul (Kasir, Dapur, Produk, Resep, BahanBaku, ...)
  Http/Middleware/    RoleMiddleware (alias `role`)
  Models/             Eloquent: User, Produk, Kategori, BahanBaku, Resep, ProduksiDapur,
                      Transaksi, DetailTransaksi, DiskonPromo, RestockBahan, Pelanggan, ShiftKasir
bootstrap/app.php     Pendaftaran alias middleware
database/migrations/  Skema (urut berdasarkan tanggal)
database/seeders/     DatabaseSeeder: empat akun contoh
resources/views/
  landing.blade.php   Halaman depan (route `/`)
  layouts/            app, guest, navigation
  components/         page, panel, btn, stat, stats, flash, dialog
  partials/           Ilustrasi toko (dipakai landing dan login)
  auth/ dashboard/ kasir/ dapur/ shift/ produk/ resep/ bahanbaku/ restock/
  laporan/ pelanggan/ karyawan/ kategori/ diskon/ profile/
routes/web.php        Semua route web
public/images/landing Foto halaman depan
docs/screenshots      Gambar untuk README
tests/                Tes Breeze (auth dan profil)
```

## 5. Model data

Kunci utama memakai nama **`id_<tabel>`**, bukan `id`. Contoh: `id_produk`, `id_bahan`, `id_transaksi`, `id_shift`. Selalu tulis `$primaryKey` dan nama kolom yang benar saat membuat relasi atau route model binding.

| Tabel | Isi | Catatan |
| --- | --- | --- |
| `users` | Akun staf | Kolom `role`, `permissions` (array), `theme` |
| `kategoris` | Kategori produk | |
| `produks` | Menu | `tipe_produksi` (`Batch` atau `Kustom`), `jumlah_per_batch`, `harga_dasar`, `stok_etalase`, `stok_minimum` |
| `bahan_bakus` | Bahan baku | `jumlah_stok`, `satuan`, harga beli satuan, batas minimum |
| `reseps` | Takaran bahan per produk | `id_produk`, `id_bahan`, `takaran` |
| `produksi_dapurs` | Catatan produksi | `status_produksi`: `Persiapan`, `Dipanggang`, `Selesai`; ada catatan produksi untuk pesanan kustom |
| `transaksis` dan `detail_transaksis` | Penjualan kasir | `jenis_pesanan`: `Offline` atau `Whatsapp`; ada `status_pesanan`, `catatan_pesanan`, dan relasi pelanggan |
| `pelanggans` | Pelanggan | `tipe_pelanggan`: `Baru`, `Reguler`, `VIP` |
| `diskon_promos` | Promo dan diskon | |
| `restock_bahans` | Riwayat restock | |
| `shift_kasirs` | Shift kasir | `status`: `Open` atau `Closed`, modal awal, rekap tunai dan nontunai, selisih kas |

Aturan bisnis yang perlu diketahui:

- **HPP** produk dihitung dari resep (`Produk::hitungHpp()`): jumlah takaran dikali harga beli bahan. Untuk produk `Batch`, hasilnya dibagi `jumlah_per_batch`.
- **Alur dapur**: kasir membuat transaksi, pesanan muncul di layar dapur (halaman memeriksa pesanan baru sekitar tiap 4 detik lewat `dapur.cek_pesanan`), dapur mengubah status pesanan dan mencatat produksi per batch atau pesanan kustom.
- **Membatalkan produksi** mengembalikan stok bahan baku (lihat teks konfirmasi di `dapur/index.blade.php`).
- Produk `Kustom` tidak punya stok etalase. Statusnya "Dibuat sesuai pesanan".

## 6. Peran dan hak akses

Nilai kolom `role` yang dikenali (lihat `app/Models/User.php`):

| Peran | Nilai `role` | Modul bawaan |
| --- | --- | --- |
| Owner | `Owner` | Semua modul |
| Kasir | `Kasir` atau `Cashier` | `kasir`, `pelanggan`, `laporan` |
| Dapur | `Dapur` atau `Kitchen` | `dapur` |
| Admin Stok | `Stock_Admin` (juga `Admin_Stok`, `Admin Gudang`, `Admin_Gudang`) | `bahanbaku`, `restock` |
| Kustom | `Kustom` atau `Custom` | Daftar `permissions` yang diatur Owner |

Modul hak akses: `kasir`, `pelanggan`, `dapur`, `produk`, `resep`, `diskon`, `bahanbaku`, `laporan`, `karyawan`. Pada peran kustom, `kasir` otomatis membuka `pelanggan`, dan `bahanbaku` membuka `restock`.

- Gunakan `$user->hasPermission('modul')` untuk tampilan (menu, tombol) dan `isOwner()`, `isKasir()`, `isDapur()`, `isStockAdmin()`, `isCustom()` untuk pemeriksaan peran. Jangan membandingkan string `role` langsung.
- Route dilindungi middleware `role:...`, misalnya `role:Owner,Kasir`. `RoleMiddleware` meloloskan Owner dan, untuk peran kustom, mencocokkan nama atau path route dengan modul izin.
- Menambah modul baru berarti memperbarui tiga tempat: `RoleMiddleware` (peta `permissionChecks`), `User::hasPermission` dan label izinnya, serta `layouts/navigation.blade.php`.

Akun contoh dari seeder (hanya untuk lokal, kata sandi `password`): `owner@kandekande.com`, `kasir@kandekande.com`, `dapur@kandekande.com`, `gudang@kandekande.com`.

## 7. Aturan kerja pada tampilan

Pekerjaan tampilan adalah bagian terbesar repo ini. Pegang aturan berikut.

1. **Baca [DESIGN.md](DESIGN.md) dulu.** Warna, huruf, komponen, dan pola halaman ada di sana.
2. **Jangan menyentuh backend untuk tugas tampilan.** Controller, model, migrasi, dan logika route tetap seperti semula, kecuali tugasnya memang meminta perubahan itu.
3. **Pakai komponen yang ada**: `x-page`, `x-panel`, `x-btn`, `x-stats`/`x-stat`, `x-flash`, `x-dialog`, serta kelas `.tbl`, `.fld`, `.inp`. Jangan membuat gaya baru yang sudah ada padanannya.
4. **Setiap warna butuh pasangan `dark:`.** Periksa mode siang dan malam.
5. **Halaman memenuhi lebar layar.** Tidak ada margin kiri dan kanan yang besar. Halaman formulir memakai `<x-page narrow>` dan rata kiri.
6. **Tidak boleh ada scroll horizontal** di halaman. Di ponsel, kolom Aksi tabel harus tetap terlihat.
7. **Jangan menambah**: emoji, gradasi, huruf kapital berjarak, kartu di dalam kartu, atau animasi yang berulang di halaman kerja.
8. **Tombol login cepat jangan dihapus** dulu: tombol "Login cepat (debug)" di halaman masuk dipakai pengembang. Hapus sebelum produksi.
9. **Struk cetak** (`kasir/cetak.blade.php`, `shift/cetak.blade.php`) tidak didesain ulang.
10. **Teks antarmuka berbahasa Indonesia**, sentence case, dengan nada seperti di [DESIGN.md](DESIGN.md#16-bahasa-dan-nada-tulisan).

### Jebakan Blade

- **Beri spasi sebelum direktif.** `kustom@if(...)` tidak dikompilasi dan menimbulkan `ParseError`. Tulis `kustom @if(...)` dan `@endif` yang berdiri sendiri.
- **Nama variabel Alpine harus unik** per dialog agar tidak bentrok antar `x-data`.
- Setelah mengubah banyak view, jalankan `php artisan view:cache` untuk mencari galat kompilasi, lalu `php artisan view:clear`.
- Tema aplikasi disimpan per akun (`users.theme` dan `localStorage`), halaman depan dan login memakai `localStorage` kunci `theme_guest`. Saat menguji tampilan, jangan mengubah `users.theme` kecuali perlu. Kalau terlanjur berubah, kembalikan.

## 8. Menguji perubahan

Tes otomatis yang ada baru mencakup autentikasi dan profil (bawaan Breeze). Modul kasir, dapur, gudang, dan laporan **belum punya tes otomatis**, jadi perubahan di sana perlu diuji manual.

Daftar periksa sebelum menyerahkan perubahan tampilan:

- [ ] `composer test` lulus.
- [ ] `php artisan view:cache` tidak menampilkan galat, lalu `php artisan view:clear`.
- [ ] Halaman dibuka dengan tiap peran yang berhak mengaksesnya (gunakan akun contoh).
- [ ] Mode siang dan malam sama-sama rapi.
- [ ] Lebar 1440 px, 1024 px, dan 390 px: tidak ada scroll horizontal, tombol dan kolom Aksi terlihat.
- [ ] Dialog bisa dibuka, ditutup dengan Esc, dan formulir tidak terkirim ganda.
- [ ] Konsol browser tidak menampilkan galat baru. (Galat parsing Alpine yang sudah ada sejak awal bisa diabaikan.)
- [ ] Pesan flash dan galat validasi tampil.
- [ ] Bila tampilan berubah besar, perbarui gambar di `docs/screenshots` dan, kalau perlu, [README.md](README.md).

Hati-hati: membuka halaman dan menekan tombol aksi dapat **mengubah data** (membuat transaksi, mengubah stok, menghapus). Gunakan database lokal, jangan data produksi.

## 9. Git dan kontribusi

### Branch dan commit

- Kerjakan di branch (`ui-...`, `fix-...`, `docs-...`) lalu ajukan pull request. Jangan force push ke `main`.
- Commit kecil dan fokus. Pesan commit berbahasa Indonesia dengan awalan gaya Conventional Commits:

  ```
  feat(ui): desain ulang halaman masuk dan kerangka aplikasi
  fix: perbaiki hitung kembalian pada pembayaran tunai
  docs: perbarui README dan tangkapan layar
  ```

  Badan commit menjelaskan **mengapa**, bukan mengulang diff.
- **Author commit adalah orang yang menyerahkan pekerjaan.** Jangan menambahkan baris `Co-Authored-By`, nama alat, atau nama agen sebagai author atau co-author. Pastikan `git config user.name` dan `user.email` milik kontributor yang sebenarnya.

### Jangan pernah dikomit

| Jenis | Contoh |
| --- | --- |
| Rahasia dan konfigurasi lokal | `.env`, kunci, token, `auth.json` |
| Dump database | `*.sql`, `*.sqlite` berisi data nyata |
| Hasil build dan dependensi | `vendor/`, `node_modules/`, `public/build/` |
| Alat bantu lokal | Folder konfigurasi agen atau editor yang diawali titik (misalnya `.taste-skill/`, `.idea/`, `.vscode/`) |
| Perubahan acak | `package-lock.json` yang berubah hanya karena `npm install` di mesin lain |

Periksa `git status` dan `git diff --staged` sebelum commit. Tambahkan file dengan nama, bukan `git add .`.

### Pull request

Deskripsi PR memuat: apa yang berubah, mengapa, halaman atau peran yang terpengaruh, cara mengujinya, dan tangkapan layar bila ada perubahan tampilan (siang dan malam).

## 10. Keamanan

- Jangan menaruh kata sandi, token, atau kunci API di kode, view, README, atau tangkapan layar.
- Akun contoh dan tombol login cepat hanya untuk pengembangan lokal. Ganti semua kata sandi dan hapus tombol itu sebelum dipakai di server sungguhan.
- Validasi semua input di controller. Gunakan `$fillable` pada model, jangan `$guarded = []`.
- Semua formulir `POST`, `PATCH`, dan `DELETE` memakai `@csrf` (dan `@method`).
- Keluaran Blade memakai `{{ }}` (otomatis di-escape). Pakai `{!! !!}` hanya untuk isi yang Anda buat sendiri dan aman, misalnya SVG statis.
- Jangan menulis kredensial ke log atau pesan galat.
- Kolom uang di beberapa tabel bertipe `double`. Hati-hati dengan pembulatan saat menambah perhitungan baru dan bulatkan secara eksplisit.

## 11. Bila ragu

- Tanyakan sebelum mengubah skema database, aturan hak akses, atau perhitungan uang dan stok.
- Jangan mengarang data toko (alamat, nomor telepon, harga, jam). Ambil dari database atau tanyakan pemilik.
- Jangan menghapus fitur yang tampak tidak terpakai tanpa memeriksa route, view, dan peran yang memakainya.
- Pertahankan gaya yang sudah ada di sekitar kode yang Anda ubah: penamaan, kepadatan komentar, dan idiom.
