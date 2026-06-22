# Cireng Pasrah 🍟

Aplikasi e-commerce sederhana dan modern berbasis **Laravel 13** yang dirancang khusus untuk manajemen penjualan, pemesanan, dan pengiriman produk olahan "Cireng Pasrah".

Dibangun dengan antarmuka yang bersih, modern, dan sangat memanjakan mata dengan desain *flat* tanpa gradasi yang nyaman untuk digunakan berlama-lama tanpa membuat mata lelah.

---

## 🚀 Fitur Utama & Roles

Aplikasi ini memiliki 3 hak akses (roles) dengan fungsinya masing-masing:

### 1. Panel Admin (Manajemen Toko)
- **Dashboard Analitik**: Ringkasan total pendapatan, jumlah pesanan, dan status pesanan (termasuk pesanan yang perlu refund / terlambat diambil).
- **Manajemen Produk (Katalog)**: Tambah, edit, hapus varian cireng beserta harga dan deskripsinya.
- **Manajemen Inventori (Automasi)**: Stok "Cireng Mentah" yang berkurang secara otomatis ketika pesanan mulai "Diproses" dan bertambah otomatis jika pesanan dibatalkan.
- **Manajemen Pesanan**: Memantau status pemesanan pelanggan. Jika pesanan *Delivery* diubah menjadi "Siap", pesanan otomatis dilempar ke **Pool Driver**.
- **Validasi Pembayaran**: Verifikasi bukti transfer dan sistem peringatan jika pesanan dibatalkan padahal sudah lunas (Refund Alert).
- **Manajemen Pengguna**: Mengelola data pelanggan dan driver, termasuk fitur menghapus akun.

### 2. Panel Customer (Pelanggan)
- **Katalog Produk**: Tampilan daftar cireng yang modern dengan keranjang berbasis *Session*.
- **Checkout Dinamis**: Memilih opsi pengiriman (Take Away / Delivery) dan pembayaran (Transfer / COD). Untuk *Delivery*, pelanggan memasukkan perkiraan jarak dan ongkir dihitung otomatis (Rp 5 per meter).
- **Clear Cart**: Keranjang otomatis dikosongkan segera setelah pemesanan berhasil.
- **Riwayat Pesanan**: Pelanggan dapat melacak status pesanan secara real-time dan mengunggah bukti transfer jika memilih metode Transfer.
- **Profil & Autentikasi**: Edit profil dan fitur "Lihat Password" murni dengan Vanilla JS. Fitur idle-timeout 15 menit.

### 3. Panel Driver (Sistem Pool)
- **Pool Terbuka**: Saat Admin mengubah pesanan delivery menjadi "Siap", pesanan masuk ke *Pool* yang bisa dilihat semua driver.
- **Sistem Rebutan (Ambil Pesanan)**: Driver dapat mengambil pesanan dari pool. Begitu diambil, pesanan hilang dari layar driver lain.
- **Status Bertahap**: Driver memproses status secara real-time: `Menuju Resto` → `Di Resto` → `Mengantar` → `Selesai`.
- **Batal Khusus**: Driver hanya boleh membatalkan pesanan saat masih "Menuju Resto". Pesanan yang batal akan dikembalikan ke *Pool*.
- **Penerimaan COD**: Jika metode pembayaran adalah COD, saat pesanan berstatus "Mengantar", muncul tombol khusus "Terima Pembayaran Tunai & Selesai" yang otomatis merubah pesanan menjadi Lunas.
- **Privasi Alamat**: Di menu Riwayat Driver, pesanan yang sudah *Selesai* akan disamarkan alamatnya (contoh: "Jl. Merdeka..." menjadi "Jl. Merd***") untuk melindungi privasi pelanggan.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 13.x](https://laravel.com/) (PHP 8.4)
- **Database**: MySQL
- **Frontend**: Blade Templating Engine + Vanilla CSS (Custom Design System, Flexbox/Grid, Solid Colors UI)
- **Fonts**: Google Fonts (Inter)
- **Icons**: Emoji & Custom SVG

---

## ⚙️ Cara Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di komputer lokal Anda:

### 1. Persyaratan Sistem
- PHP >= 8.2 (Disarankan PHP 8.4)
- Composer 2.x
- XAMPP / Laragon / MySQL Server

### 2. Langkah Instalasi

Clone repositori ini, lalu masuk ke dalam folder proyek:
```bash
git clone https://github.com/mhdhkl7/cireng-pasrah.git
cd cireng-pasrah
```

Instal dependensi PHP melalui Composer:
```bash
composer install
```

Salin file `.env.example` menjadi `.env` dan atur konfigurasi database Anda:
```bash
cp .env.example .env
```
*Buka file `.env` dan pastikan konfigurasi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sudah sesuai dengan lokal Anda.*

Generate *Application Key* Laravel:
```bash
php artisan key:generate
```

Jalankan Migrasi Database beserta Seeder (Data Awal):
```bash
php artisan migrate:fresh --seed
```

### 3. Menjalankan Server Lokal
Jalankan perintah ini untuk menyalakan *development server*:
```bash
php artisan serve
```
Aplikasi bisa diakses melalui browser di alamat: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Default (Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat menggunakan akun di bawah ini untuk masuk:

### Administrator
- **Email**: `admin@cireng.shop`
- **Password**: `password123`

### Driver
- **Email**: `driver@cireng.shop`
- **Password**: `password123`

### Pelanggan (Customer)
Anda dapat menggunakan akun salah satu pelanggan di bawah ini:
- `prasetyoaditama@cireng.shop`
- `aditiarach@cireng.shop`
- `rehanpratamapasaribu@cireng.shop`
- `steephenparnaehansitumeang@cireng.shop`

**Password untuk semua pelanggan**: `password123`

---

## 📂 Struktur Database Utama

1. `users` - Menyimpan data admin, pelanggan, dan driver.
2. `produks` - Menyimpan daftar menu cireng.
3. `inventoris` - Menyimpan stok barang otomatis.
4. `pesanans` - Data pesanan, total harga, ongkir, jarak, catatan pembatalan, dan *foreign key* `driver_id`.
5. `detail_pesanans` - Rincian item produk di dalam satu pesanan.

---

## 📄 Lisensi

Aplikasi Cireng Pasrah ini adalah perangkat lunak *open-source* yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

*(Dibuat dengan ❤️ untuk kemajuan bisnis Cireng Pasrah)*
