# DESIGN.md

Panduan desain Kandekande POS. Dokumen ini menjelaskan keputusan visual, token, komponen, dan pola halaman supaya setiap halaman baru terlihat dan terasa seperti satu produk.

Aturan singkatnya: **tenang, rapi, hangat, dan jujur**. Kasir dan dapur memakai aplikasi ini berjam-jam, jadi tampilan harus mudah dibaca, bukan ramai.

## Daftar isi

1. [Prinsip](#1-prinsip)
2. [Yang tidak boleh ada](#2-yang-tidak-boleh-ada)
3. [Warna](#3-warna)
4. [Tipografi](#4-tipografi)
5. [Bentuk, jarak, dan bayangan](#5-bentuk-jarak-dan-bayangan)
6. [Mode siang dan malam](#6-mode-siang-dan-malam)
7. [Kerangka aplikasi](#7-kerangka-aplikasi)
8. [Komponen Blade](#8-komponen-blade)
9. [Tabel, formulir, dan dialog](#9-tabel-formulir-dan-dialog)
10. [Pola halaman](#10-pola-halaman)
11. [Halaman depan (landing)](#11-halaman-depan-landing)
12. [Ilustrasi toko](#12-ilustrasi-toko)
13. [Gerak](#13-gerak)
14. [Responsif](#14-responsif)
15. [Aksesibilitas](#15-aksesibilitas)
16. [Bahasa dan nada tulisan](#16-bahasa-dan-nada-tulisan)
17. [Cara menambah halaman baru](#17-cara-menambah-halaman-baru)
18. [Jebakan yang sudah pernah terjadi](#18-jebakan-yang-sudah-pernah-terjadi)

---

## 1. Prinsip

| Prinsip | Artinya dalam praktik |
| --- | --- |
| Tenang | Satu warna aksen, banyak ruang kosong, tanpa dekorasi yang tidak punya fungsi. |
| Rata dan sejajar | Semua halaman memakai kerangka yang sama. Tepi kiri judul, panel, dan tabel selalu lurus. |
| Penuh lebar | Halaman aplikasi tidak punya margin kiri dan kanan yang besar. Konten memenuhi area di sebelah sidebar. Halaman formulir saja yang dibatasi lebarnya (`narrow`) dan tetap rata kiri. |
| Angka yang jelas | Angka uang dan stok memakai font tampilan, tebal, dan `tabular-nums` agar kolom tidak bergeser. |
| Hangat | Dasar krem, tinta cokelat gelap, aksen oranye roti. Bukan putih dingin dan bukan abu-abu kebiruan. |
| Satu sumber | Warna dan ukuran diambil dari token di bagian 3 sampai 5, bukan angka bebas di tiap halaman. |

## 2. Yang tidak boleh ada

Ini daftar yang sengaja dibuang agar tampilan tidak terlihat generik.

- Emoji sebagai ikon atau hiasan.
- Gradasi warna pada tombol, kartu, atau latar. Gradasi hanya boleh di ilustrasi langit dan saklar siang-malam.
- Label huruf kapital dengan jarak antarhuruf lebar (`uppercase tracking-widest`).
- Kartu di dalam kartu, atau ikon kecil di setiap kartu tanpa alasan.
- Efek kaca (`backdrop-blur`) dan bayangan tebal berwarna.
- Animasi yang berulang tanpa henti dan menarik perhatian (`animate-pulse`, `animate-bounce`).
- Teks kecil di bawah 12 px.
- Warna ungu, biru, dan indigo bawaan Tailwind. Semuanya sudah dipetakan ke palet oranye (lihat bagian 3).

`layouts/app.blade.php` meredam sebagian di atas lewat CSS global (gradasi diratakan, `uppercase` dan `tracking-wid*` dinonaktifkan, blur dimatikan, `animate-pulse` dan `animate-bounce` dihentikan, `text-[10px]` menjadi 11 px). Jangan mengandalkan itu. Tulis kelas yang benar sejak awal.

## 3. Warna

Aplikasi memakai Tailwind. Palet bawaan **dipetakan ulang** di `tailwind.config.js` dan di konfigurasi Tailwind CDN dalam `layouts/app.blade.php`. Artinya `gray-*` sudah berwarna krem-cokelat, dan `indigo-*`, `blue-*`, `brand-*` sudah menjadi oranye.

### 3.1 Palet dasar

| Token Tailwind | Nilai | Dipakai untuk |
| --- | --- | --- |
| `gray-50` | `#FAF9F6` | Latar halaman (siang) |
| `gray-100` | `#F3F1EC` | Latar item aktif di sidebar, hover halus |
| `gray-200` | `#E6E2DA` | Garis dan border (siang) |
| `gray-300` | `#D3CEC4` | Border input |
| `gray-400` | `#A8A097` | Placeholder, teks pendukung paling ringan |
| `gray-500` | `#7D756C` | Teks pendukung |
| `gray-600` | `#5F5851` | Teks isi sekunder |
| `gray-700` | `#433D38` | Label formulir |
| `gray-800` | `#2A2521` | Kartu (malam) |
| `gray-900` | `#1B1612` | Tinta utama (siang), latar halaman (malam) |
| `gray-950` | `#14110F` | Latar terdalam |

### 3.2 Aksen (oranye roti)

`indigo`, `blue`, dan `brand` memakai palet **crust** yang sama.

| Langkah | Nilai | Catatan |
| --- | --- | --- |
| 50 | `#FFF3EC` | |
| 100 | `#FFE3D2` | |
| 200 | `#FFC7A6` | |
| 300 | `#FBA474` | |
| 400 | `#F08A57` | **Aksen di mode malam** |
| 500 | `#E36A33` | |
| 600 | `#D4501E` | **Aksen utama (siang)**, fokus, tautan, harga |
| 700 | `#B03F16` | Hover tombol aksen |
| 800 | `#8A3314` | |
| 900 | `#6B2B14` | |
| 950 | `#3F1809` | |

`purple` dan `violet` dipetakan ke palet **rust** (merah bata: `600` = `#B03F2E`). Pakai seperlunya.

### 3.3 Warna makna

| Makna | Siang | Malam |
| --- | --- | --- |
| Berhasil, tersedia, laba | `emerald-600` | `emerald-400` |
| Bahaya, galat, stok menipis | `red-600` | `red-400` |
| Peringatan (misalnya spanduk "shift belum dibuka") | `amber`, latar pucat dengan teks cokelat tua | `amber` redup di atas latar gelap |

Halaman depan memakai hijau `#1F6B3A` (siang) dan `#8FD6A4` (malam) untuk status "Tersedia di etalase".

Jangan pakai warna makna untuk dekorasi. Merah berarti ada masalah.

### 3.4 Warna tambahan halaman depan

Halaman depan memakai variabel CSS sendiri di `landing.blade.php` (bukan kelas Tailwind).

| Variabel | Siang | Malam | Fungsi |
| --- | --- | --- | --- |
| `--bg` | `#FAF6EF` | `#14110F` | Latar halaman |
| `--ink` | `#1B1612` | `#F3EEE7` | Teks utama |
| `--muted` | `#6F665E` | `#A39A91` | Teks pendukung |
| `--line` | `#E8DFD2` | `#35302B` | Garis dan border |
| `--card` | `#FFFFFF` | `#1F1B18` | Kartu |
| `--accent` | `#D4501E` | `#F08A57` | Aksen |
| `--peach` | `#FBE4D8` | `#3A2418` | Kartu dan ikon hangat |
| `--mint` | `#D3EAD9` | `#1F3A2B` | Kartu dan status tersedia |
| `--sun` | `#F7DC9B` | `#4A3F22` | Kartu jam buka |
| `--rose` | `#F8D9D2` | `#40262A` | Kartu keempat keunggulan |
| `--sand` | `#EEE8DE` | `#2A2521` | Latar foto dan status netral |
| `--band` | `#1B1612` | `#2A2420` | Blok kue kustom |
| `--foot` | `#1B1612` | `#0E0C0A` | Footer |

## 4. Tipografi

| Peran | Font | Bobot |
| --- | --- | --- |
| Judul, angka besar, harga, nama menu | **Bricolage Grotesque** (`font-display`) | 700 dan 800 |
| Teks isi, label, tabel, tombol | **Albert Sans** (`font-sans`) | 400 sampai 700 |

Keduanya dimuat dari `fonts.bunny.net`. Font cadangan: `ui-sans-serif, system-ui, sans-serif`.

### Skala (aplikasi)

| Elemen | Kelas |
| --- | --- |
| Judul halaman (`x-page`) | `font-display text-3xl sm:text-4xl font-extrabold tracking-tight` |
| Judul panel (`x-panel`) | `font-display text-xl font-extrabold tracking-tight` |
| Judul dialog | `font-display text-2xl font-extrabold tracking-tight` |
| Angka statistik (`x-stat`) | `font-display text-2xl sm:text-3xl font-extrabold tabular-nums` |
| Teks isi | `text-sm` sampai `text-base`, Albert Sans |
| Teks pendukung | `text-sm text-gray-500 dark:text-gray-400` |
| Label formulir | `.fld > label`: 14 px, semibold |

`h1`, `h2`, dan `h3` otomatis memakai Bricolage Grotesque dengan `letter-spacing: -.02em`.

### Skala (halaman depan)

| Elemen | Ukuran |
| --- | --- |
| Judul hero | `clamp(52px, 6.7vw, 96px)`, bobot 800, spasi huruf `-.045em` |
| Judul bagian (`.h2`) | `clamp(40px, 4.7vw, 68px)`, bobot 800, `-.04em` |
| Judul kartu menu | 24 px, bobot 700 |
| Harga | 22 px, bobot 700, warna aksen |
| Jam buka besar | `clamp(60px, 9.6vw, 132px)`, bobot 800 |
| Teks isi | 16 px; paragraf pembuka hero 17 sampai 20 px |

Aturan: judul besar selalu rapat (spasi huruf negatif) dan tinggi baris sekitar `.95`. Teks isi tidak pernah di bawah 13 px, dan teks di bawah 14 px hanya untuk keterangan.

## 5. Bentuk, jarak, dan bayangan

### Radius

Skala radius Tailwind dikecilkan di aplikasi supaya terlihat rapi, bukan "gelembung".

| Kelas | Nilai | Dipakai untuk |
| --- | --- | --- |
| `rounded-xl` | `0.5rem` | Panel, tabel, kartu aplikasi |
| `rounded-2xl` | `0.625rem` | Blok besar |
| `rounded-3xl` | `0.75rem` | Jarang dipakai |
| `rounded-full` | pil | Tombol, chip, status, avatar |

Halaman depan lebih membulat: kartu 28 px, blok besar 32 sampai 48 px, tombol dan chip pil penuh.

### Bayangan (aplikasi)

Bayangan tipis dan netral, tidak pernah berwarna.

| Kelas | Nilai |
| --- | --- |
| `shadow-sm` | `0 1px 0 rgba(27,22,18,.04)` |
| `shadow` | `0 1px 2px rgba(27,22,18,.06)` |
| `shadow-md` | `0 2px 8px -2px rgba(27,22,18,.08)` |
| `shadow-lg` | `0 6px 18px -6px rgba(27,22,18,.12)` |
| `shadow-xl` | `0 10px 28px -10px rgba(27,22,18,.16)` |
| `shadow-2xl` | `0 14px 36px -12px rgba(27,22,18,.2)` (dialog) |

Di aplikasi, pemisah utama adalah **garis** (`border-gray-200 dark:border-gray-800`), bukan bayangan.

### Jarak

- Konten halaman: `px-4 sm:px-6 lg:px-8`, antarbagian `space-y-8`, atas bawah `py-8 sm:py-10`.
- Isi panel: `p-5 sm:p-6`.
- Jarak antar kontrol formulir: `space-y-5`.
- Halaman depan: antarbagian `clamp(64px, 8vw, 112px)`, gutter `clamp(20px, 5.6vw, 80px)`, lebar maksimum 1440 px.

## 6. Mode siang dan malam

Kelas `dark` dipasang di `<html>`. Setiap warna harus punya pasangan `dark:`.

| Tempat | Urutan penentuan tema |
| --- | --- |
| Aplikasi (setelah login) | 1) `localStorage` `theme_user_{id}`, 2) kolom `users.theme` di database, 3) pengaturan tema perangkat (`prefers-color-scheme`). |
| Halaman depan dan login | 1) `localStorage` `theme_guest` (atau `theme`), 2) pengaturan tema perangkat. |

- Saklar siang-malam ada di topbar aplikasi, di header halaman depan, dan di halaman login. Saklar di aplikasi mengirim `POST profile.theme` supaya pilihan tersimpan di akun.
- `:root { color-scheme: light }` dan `.dark { color-scheme: dark }` wajib ada agar scrollbar dan kontrol bawaan browser ikut gelap.
- Mode malam bukan sekadar membalik warna. Dasar menjadi `#1B1612` (`gray-900`), kartu `#2A2521` (`gray-800`), garis `gray-700` atau `gray-800`, aksen naik ke `#F08A57`.
- Blok yang gelap di siang (kue kustom, footer) tetap gelap di malam, dengan sedikit pergeseran nilai.

Jangan menulis warna yang hanya benar di satu mode. Kalau menambah elemen, periksa keduanya.

## 7. Kerangka aplikasi

```
┌──────────┬─────────────────────────────────────────┐
│ Sidebar  │ Topbar: tombol sidebar, breadcrumb,       │
│ w-60     │         saklar tema, notifikasi, akun     │
│ (68 px   ├─────────────────────────────────────────┤
│ saat     │ <x-page> judul + aksi                     │
│ diciutkan│   isi halaman (penuh lebar)               │
│ )        │                                           │
└──────────┴─────────────────────────────────────────┘
```

- **Sidebar** (`layouts/navigation.blade.php`): lebar `w-60`, bisa diciutkan jadi 68 px (disimpan di `localStorage` `sb_collapsed`). Di layar kecil menjadi laci. Item menu dikelompokkan: tanpa judul (Dashboard, Kasir, Shift, Dapur), Katalog, Gudang, Analisis atau Transaksi, Tim. Item hanya tampil kalau `hasPermission()` mengizinkan.
- **Item aktif**: latar `gray-100` (siang) dengan garis aksen tipis di kiri.
- **Topbar**: breadcrumb "Kandekande / Halaman", saklar tema, lonceng, nama dan peran akun.
- **Layout `guest`** dipakai halaman masuk dan daftar: dua kolom (formulir dan ilustrasi toko), lihat bagian 12.

Menambah menu baru: tambahkan satu baris ke array `$navAll` di `navigation.blade.php` dengan format `[grup, label, route, pola aktif, ikon, boleh?]`.

## 8. Komponen Blade

Komponen ada di `resources/views/components/`. Pakai komponen ini sebelum menulis kelas sendiri.

### `<x-page>`

Pembungkus semua halaman aplikasi. Mengatur judul, deskripsi, tombol aksi, pesan flash, dan lebar.

```blade
<x-page title="Bahan baku" sub="Pantau stok fisik bahan baku di gudang.">
    <x-slot name="actions">
        <x-btn variant="secondary" :href="route('restock.index')">Riwayat restock</x-btn>
        <x-btn :href="route('bahanbaku.create')">Catat bahan masuk</x-btn>
    </x-slot>

    {{-- isi halaman --}}
</x-page>
```

| Prop | Default | Keterangan |
| --- | --- | --- |
| `title` | wajib | Judul halaman (`h1`) |
| `sub` | `null` | Kalimat penjelas di bawah judul |
| `narrow` | `false` | `true` untuk halaman formulir: dibatasi `max-w-4xl` dan tetap **rata kiri** |
| slot `actions` | kosong | Tombol di kanan judul |

`x-page` sudah memuat `<x-flash />`. Jangan memanggilnya lagi.

### `<x-panel>`

Kotak konten. Border tipis, tanpa bayangan.

```blade
<x-panel title="Catat produksi baru" sub="Sistem per batch dan pesanan kustom">...</x-panel>
<x-panel flush>  {{-- flush: tanpa padding, untuk tabel --}}
    <div class="overflow-x-auto"><table class="tbl">...</table></div>
</x-panel>
```

| Prop | Default | Keterangan |
| --- | --- | --- |
| `title` | `null` | Judul panel (`h2`) |
| `sub` | `null` | Keterangan kecil |
| `flush` | `false` | Hilangkan padding isi, dipakai untuk tabel dan daftar |
| slot `actions` | kosong | Tombol di kanan judul panel |

### `<x-btn>`

Tombol dan tautan bergaya tombol. Kalau `href` diisi, hasilnya `<a>`.

| Prop | Nilai | Keterangan |
| --- | --- | --- |
| `variant` | `primary` (default), `accent`, `secondary`, `danger`, `link` | `primary` gelap, `accent` oranye, `secondary` garis, `danger` garis merah, `link` teks saja |
| `size` | `md` (default, tinggi 42 px), `sm` (36 px) | |
| `type` | `button` (default), `submit` | |
| `href` | `null` | |

Satu halaman cukup punya **satu** tombol `primary`. Sisanya `secondary` atau `link`.

### `<x-stats>` dan `<x-stat>`

Strip statistik satu kotak dengan pemisah garis (bukan deretan kartu terpisah).

```blade
<x-stats>
    <x-stat label="Jenis bahan" value="6" sub="Terdata di gudang" />
    <x-stat label="Stok kritis" value="0" sub="Di bawah batas aman" tone="bad" />
</x-stats>
```

`tone`: `null`, `accent`, `good`, `bad`. Grid 2 kolom di layar kecil, 4 kolom di `lg`.

### `<x-flash>`

Menampilkan `session('success')`, `session('error')`, dan galat validasi. Sudah ada di `x-page`.

### `<x-dialog>`

Dialog modal berbasis Alpine.

```blade
<div x-data="{ modalTambah: false }">
    <x-btn @click="modalTambah = true">Tambah</x-btn>

    <x-dialog show="modalTambah" title="Tambah pelanggan">
        <form>...</form>
    </x-dialog>
</div>
```

| Prop | Keterangan |
| --- | --- |
| `show` | **Nama** variabel Alpine yang mengatur buka-tutup (string, bukan nilai) |
| `title` | Judul dialog |
| `wide` | `true` untuk lebar `max-w-2xl` (default `max-w-md`) |

Esc dan klik di luar menutup dialog. Pakai nama variabel yang unik per halaman agar tidak bentrok dengan `x-data` lain.

## 9. Tabel, formulir, dan dialog

Kelas CSS bersama didefinisikan di `layouts/app.blade.php`.

### Tabel `.tbl`

```blade
<x-panel flush>
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th class="r">Stok</th>
                    <th class="r">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">Tepung terigu</p>
                        <p class="text-xs text-gray-400">#1</p>
                    </td>
                    <td class="r num">49.5 kg</td>
                    <td class="r">...</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-panel>
```

Aturan:

- **Kolom pertama menyerap sisa lebar** (minimal 12 rem; 9 rem di bawah 1280 px). Kolom lain selebar isinya (`width: 1%; white-space: nowrap`) supaya rapat dan sejajar.
- Kolom angka diberi `class="r num"` (rata kanan, `tabular-nums`). Kolom tengah `c`.
- Kolom yang boleh membungkus teks diberi `class="wrap"`.
- Kolom yang kurang penting disembunyikan di layar sempit: `hidden sm:table-cell`, `hidden md:table-cell`, `hidden xl:table-cell`. Isi yang hilang ditaruh di bawah nama pada kolom pertama (`sm:hidden`) bila masih diperlukan.
- Header memakai teks `gray-500`, bobot 500, tanpa huruf kapital.
- Di bawah 1280 px, font tabel 13 px dan padding sel dirapatkan.
- Baris kosong: satu `<tr><td colspan="N" class="py-14 text-center text-gray-400">` dengan kalimat yang menjelaskan apa yang harus dilakukan.
- Aksi per baris berupa teks (`Ubah`, `Hapus`) dengan jarak `gap-3 sm:gap-4`, bukan deretan tombol besar. `Hapus` memakai `text-red-600 dark:text-red-400`.

### Formulir `.fld` dan `.inp`

```blade
<div class="fld">
    <label for="nama_bahan">Nama bahan baku</label>
    <input type="text" id="nama_bahan" name="nama_bahan" class="inp" placeholder="Contoh: Tepung segitiga biru">
    <p class="hint">Dipakai untuk menghitung HPP resep.</p>
</div>
```

- `.inp` tingginya tetap 2,625 rem (sama dengan `x-btn` ukuran `md`), border 1,5 px, radius 0,5 rem, fokus beraksen oranye dengan lingkaran halus. `textarea.inp` tingginya otomatis.
- Label di atas kolom, bukan di dalamnya. Placeholder hanya contoh, bukan pengganti label.
- Formulir tambah dan ubah memakai satu berkas `_form.blade.php` (sudah ada untuk produk, bahan baku, dan karyawan). Pertahankan pola itu.
- Susunan: satu atau dua kolom (`grid sm:grid-cols-2 gap-5`). Tombol simpan di bawah, tombol batal berupa `x-btn variant="link"`.
- Halaman formulir memakai `<x-page narrow>`.

### Dialog

Dipakai untuk tindakan singkat (tambah pelanggan, catat restock, buka dan tutup shift). Untuk formulir panjang, pakai halaman sendiri.

## 10. Pola halaman

### Dashboard

1. Tanggal kecil, lalu sapaan `Halo, {nama}` sebagai `h1`, tombol aksi di kanan.
2. `x-stats` dengan empat angka.
3. Daftar yang butuh tindakan ("Perlu tindakan", "Perlu restock segera") sebagai baris dengan garis pemisah, bukan kartu.
4. Grafik atau ringkasan di bawah, grid `xl:grid-cols-3` dengan kolom utama `xl:col-span-2`.

### Halaman daftar (index)

`x-page` dengan tombol aksi, kolom filter bila perlu (`flex flex-wrap items-end gap-3` dengan `.inp !w-auto`), lalu `x-panel flush` berisi tabel.

### Halaman formulir

`<x-page narrow>` dan `x-panel` berisi `_form`. Rata kiri.

### Layar kasir (POS)

Dua kolom: area produk (toolbar cari, chip kategori, grid produk) dan kolom pesanan di kanan (`lg:w-[340px] xl:w-[400px]`). Spanduk "shift belum dibuka" berwarna amber di atas area produk.

### Layar dapur (KDS)

Kolom kiri: formulir catat produksi (`lg:grid-cols-[340px_1fr]`, menempel saat discroll). Kolom kanan: dua tab ("Pesanan kasir masuk", "Antrean produksi"), kartu pesanan, dan daftar aktivitas produksi. Di ponsel, antrean tampil lebih dulu daripada formulir karena itu yang paling sering dibutuhkan.

### Struk

`kasir/cetak.blade.php` dan `shift/cetak.blade.php` sengaja polos: monospace, lebar 58 mm, tanpa warna dan tanpa tema. **Jangan didesain ulang.**

## 11. Halaman depan (landing)

Berkas: `resources/views/landing.blade.php`, route `/` bernama `landing`. Berdiri sendiri (tidak memakai `layouts/app`). Rujukan visual: file Figma "Kandekande - Landing page" (frame siang dan malam).

Urutan bagian:

| # | Bagian | Isi | Sumber data |
| --- | --- | --- | --- |
| 1 | Pengumuman | Bar tipis: jam buka dan kue kustom | Teks tetap |
| 2 | Header | Logo, menu (Menu, Keunggulan, Kue kustom, Jam buka, FAQ), saklar tema, tombol Masuk staf atau Dashboard | `@auth` |
| 3 | Hero | Status buka, judul, tombol, tiga angka, ilustrasi toko dengan dua lencana mengambang | Harga minimum dan harga kue kustom dari database |
| 4 | Marquee | Pita oranye berisi nama menu yang berjalan | Teks tetap |
| 5 | Menu | Tab kategori dan kartu produk berfoto | `Produk` dan `Kategori` |
| 6 | Keunggulan | Empat kartu pastel berfoto | Teks tetap, harga kustom dari database |
| 7 | Kue kustom | Blok gelap dengan harga, chip, tombol, tiga langkah | Harga dari database |
| 8 | Dari dapur | Galeri tiga foto | Teks tetap |
| 9 | Jam buka | Kartu kuning besar dan jadwal mingguan (hari ini diberi warna aksen) | Jam perangkat |
| 10 | FAQ | Akordeon lima pertanyaan | Harga dari database |
| 11 | Ajakan | Banner oranye dengan foto roti | Teks tetap |
| 12 | Footer | Logo, tiga kolom tautan, wordmark raksasa, hak cipta | Teks tetap |

### Data dan foto

- Route `/` mengirim `$kategori` dan `$menu` (produk dikelompokkan per kategori, tipe `Batch` lebih dulu, `Kustom` terakhir).
- Status kartu: `Kustom` menjadi "Dibuat sesuai pesanan"; `Batch` dengan `stok_etalase > 0` menjadi "Tersedia di etalase"; selain itu "Habis untuk saat ini".
- Foto ada di `public/images/landing/` dan dicocokkan lewat nama produk di bagian atas `landing.blade.php` (`cheesecake`, `donat`, `sobek`, `kue ulang tahun` atau tipe kustom). Produk lain memakai `oven.jpg`. Untuk menambah foto produk, tambah satu cabang pada `match` itu.
- Foto di Figma dan di repo adalah foto contoh. Ganti dengan foto toko sendiri, pertahankan rasio (kartu menu sekitar 276:268, galeri besar 740:500, galeri kecil 520:220, banner 500:440).

### Perilaku (JavaScript ringan)

- Saklar tema, status buka atau tutup (06.00 sampai 21.00 mengikuti jam perangkat), hari ini di jadwal.
- Filter kategori pada kartu menu.
- Akordeon FAQ (satu terbuka sekaligus).
- Kemunculan halus saat digulir (`.rv`, `IntersectionObserver`).
- Wordmark footer menyesuaikan ukuran huruf agar selalu pas selebar kolom.

## 12. Ilustrasi toko

Ilustrasi SVG dua dimensi (bukan 3D), dipakai di halaman login dan hero halaman depan.

- Markup: `resources/views/partials/storefront-scene.blade.php`.
- Warna dan animasi: `resources/views/partials/storefront-style.blade.php`. Berisi variabel warna siang (`:root`) dan malam (`.dark`), serta kelas `.s-*` untuk mewarnai bagian gambar.
- Siang menjadi malam lewat kelas `.dark`: matahari turun dan bulan naik, bintang muncul, jendela dan etalase menyala.
- Gerak ringan: awan melintas, asap cerobong, tanaman bergoyang, lampu hias berkedip, papan "BUKA" berayun.
- Untuk bingkai ilustrasi, beri kelas `.scene` posisi `absolute; inset: 0` dan latar `var(--sky-a)`. Di layar kecil, posisikan ilustrasi dari bawah dengan lebar tetap supaya toko tetap terlihat utuh.
- Jika SVG akan dipindahkan ke alat desain, perhatikan bahwa `viewBox` dan ukuran huruf di dalam `<text>` tidak ikut terskala di beberapa alat.

## 13. Gerak

Gerak harus berfungsi, bukan hiasan.

| Gerak | Durasi dan kurva | Tempat |
| --- | --- | --- |
| Masuk halaman (naik dan muncul) | 0,8 dtk, `cubic-bezier(.2,.7,.2,1)`, berurutan 0,06 dtk | Hero halaman depan, formulir login |
| Muncul saat digulir | 0,8 dtk, geser 24 px | Bagian halaman depan (`.rv`) |
| Hover kartu | naik 4 px, 0,3 dtk | Kartu menu dan keunggulan |
| Hover tombol utama | lingkaran panah berputar `-45°` | Tombol hero dan kue kustom |
| Pergantian tema | 0,6 dtk pada warna latar dan teks | Seluruh halaman |
| Marquee | 38 dtk linear, berhenti saat disorot | Pita oranye |
| Titik status buka | denyut hijau 2,2 dtk | Lencana status toko |

Aturan:

- Hormati `prefers-reduced-motion: reduce`: semua animasi dan transisi dimatikan.
- Tidak ada animasi yang berulang di halaman kerja (kasir, dapur, tabel).
- Jangan menambah pustaka animasi. CSS dan Alpine sudah cukup.

## 14. Responsif

| Lebar | Perilaku |
| --- | --- |
| Di bawah 640 px | Satu kolom, header ringkas, grid menu satu kolom, statistik dua kolom, sidebar jadi laci |
| 640 sampai 1100 px | Grid menu dua kolom, hero bertumpuk, menu navigasi header disembunyikan |
| 1100 px ke atas | Tata letak desktop penuh |
| Aplikasi, `lg` (1024 px) | Sidebar tetap muncul di kiri |
| Tabel, di bawah 1280 px | Font dan padding tabel dirapatkan |

Aturan wajib: **tidak boleh ada scroll horizontal pada halaman**. Tabel boleh di-scroll di dalam `overflow-x-auto`, tetapi kolom Aksi harus tetap terlihat di ponsel (sembunyikan kolom yang kurang penting).

## 15. Aksesibilitas

- Fokus keyboard selalu terlihat: `:focus-visible` memakai garis 2 px beraksen (`#D4501E`, `#F08A57` di mode malam).
- Tombol ikon dan saklar diberi `aria-label` (`role="switch"` untuk saklar tema, `aria-checked`).
- Dialog memakai `role="dialog"` dan `aria-modal`, serta bisa ditutup dengan Esc.
- Pesan flash memakai `role="status"` atau `role="alert"`.
- Akordeon FAQ memakai `aria-expanded` dan `aria-controls`.
- Gambar berisi `alt` yang menjelaskan isinya. Ilustrasi dekoratif diberi `aria-hidden="true"`.
- Warna bukan satu-satunya penanda. Status selalu disertai teks ("Habis untuk saat ini", "Tersedia di etalase").
- Kontras teks pendukung (`gray-500` di atas krem, `#A39A91` di atas gelap) cukup untuk teks 14 px ke atas. Jangan memakai `gray-400` untuk teks penting.

## 16. Bahasa dan nada tulisan

- Bahasa Indonesia. Kalimat pendek, langsung, tanpa jargon.
- **Sentence case** pada judul, tombol, dan label: "Catat bahan masuk", bukan "Catat Bahan Masuk". Nama diri dan singkatan tetap benar (Owner, POS, HPP).
- Tombol berisi kata kerja yang spesifik: "Simpan ke gudang", "Buka shift sekarang". Hindari "OK" dan "Submit".
- Aplikasi (area staf) menyapa dengan **Anda**. Halaman depan (area pelanggan) memakai **kamu**.
- Keadaan kosong menjelaskan langkah berikutnya: "Belum ada pelanggan. Saat kasir mengisi nama pembeli, datanya muncul di sini otomatis."
- Pesan galat menyebut apa yang salah dan cara memperbaikinya. Jangan menyalahkan pengguna.
- Format angka: rupiah `Rp 15.000` (titik ribuan) dan jam `06.00` (titik). Tanggal ditulis dalam bahasa Indonesia, misalnya `Rabu, 28 Oktober 2026`.
- Jangan mengarang fakta di halaman depan (alamat, nomor telepon, klaim). Gunakan data dari database atau teks yang sudah disetujui pemilik.

## 17. Cara menambah halaman baru

1. Buat view di `resources/views/<modul>/` dan bungkus dengan `<x-app-layout>`, lalu `<x-page title="..." sub="...">`.
2. Gunakan `x-panel`, `x-stats`, `x-btn`, tabel `.tbl`, formulir `.fld` dan `.inp`. Jangan membuat gaya baru kalau komponen sudah ada.
3. Tambahkan route dan, bila perlu, satu baris menu di `layouts/navigation.blade.php`.
4. Pasang pasangan `dark:` untuk semua warna.
5. Cek di tiga lebar: 1440 px, 1024 px, dan 390 px. Pastikan tidak ada scroll horizontal dan kolom Aksi terlihat.
6. Cek mode siang dan malam.
7. Kompilasi view untuk menangkap galat Blade: `php artisan view:cache`, lalu `php artisan view:clear`.
8. Ketik teks sesuai bagian 16.

## 18. Jebakan yang sudah pernah terjadi

- **Direktif Blade menempel pada kata.** `kustom@if(...)` tidak dikompilasi dan menyebabkan `ParseError`. Selalu beri spasi sebelum `@if`, `@else`, `@endif`, dan sesudah `endif` bila diikuti teks.
- **Dua deklarasi Alpine dengan nama sama.** Beberapa halaman memakai `x-data` di dalam `x-data`. Beri nama unik per dialog (`modalTambah`, `showRestock`, `modalBukaShift`).
- **Padding tabel di layar kecil tidak berlaku.** Aturan `@media` harus ditulis **setelah** aturan dasar `.tbl th, .tbl td`, kalau tidak akan tertimpa. Urutan itu sudah diperbaiki, jangan dibalik.
- **Tailwind dimuat dua kali.** `layouts/app.blade.php` memuat Tailwind CDN (dengan konfigurasi di tempat) dan juga bundel Vite. Kalau menambah token warna atau radius, ubah **kedua** tempat: `tailwind.config.js` dan blok `tailwind.config` di layout.
- **Alpine dimuat dua kali** (CDN dan bundel). Konsol kadang menampilkan galat parsing Alpine yang sudah ada sejak awal. Penyebabnya belum ditemukan dan fungsi halaman tidak terganggu.
- **Teks di dalam SVG tidak ikut terskala** bila SVG dipindah ke alat desain yang mengabaikan `viewBox`. Skalakan manual.
- **Layar kosong sebentar saat pengujian tangkapan layar.** Animasi masuk halaman membuat tangkapan layar yang diambil terlalu cepat tampak kosong. Tunggu 2 sampai 4 detik.
