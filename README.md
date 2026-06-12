# Cireng Pasrah 🍟

Aplikasi e-commerce sederhana dan modern berbasis **Laravel 13** yang dirancang khusus untuk manajemen penjualan, pemesanan, dan inventori produk olahan "Cireng Pasrah".

Dibangun dengan antarmuka yang bersih, modern, dan sangat memanjakan mata menggunakan kombinasi gradasi biru-hijau (teal & cyan) yang nyaman untuk digunakan berlama-lama tanpa membuat mata lelah.

---

## 🚀 Fitur Utama

### 1. Panel Admin (Manajemen Toko)
- **Dashboard Analitik**: Ringkasan total pendapatan, jumlah pesanan, dan tren penjualan.
- **Manajemen Produk (Katalog)**: Tambah, edit, hapus varian cireng beserta harga dan deskripsinya.
- **Manajemen Inventori**: Melacak stok bahan baku seperti Cireng Mentah, Tepung Tapioka, dan Minyak Goreng. 
- **Manajemen Pesanan**: Memantau status pemesanan pelanggan, dari "Menunggu Konfirmasi" hingga "Selesai".
- **Manajemen Pelanggan**: Melihat daftar pelanggan yang terdaftar di dalam sistem.

### 2. Panel Customer (Pelanggan)
- **Katalog Produk**: Tampilan daftar cireng yang modern, dengan deskripsi lengkap dan harga.
- **Keranjang Belanja**: Menambahkan produk ke keranjang sebelum melakukan *checkout*.
- **Checkout & Pemesanan**: Proses penyelesaian pesanan yang sederhana.
- **Riwayat Pesanan**: Pelanggan dapat melacak status pesanan mereka secara langsung.

### 3. Autentikasi Terintegrasi
- Login dan Register yang dilengkapi dengan validasi *server-side*.
- Pemisahan hak akses otomatis (RBAC) antara `admin` dan `customer`.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 13.x](https://laravel.com/) (PHP 8.4)
- **Database**: MySQL (dijalankan menggunakan Laragon)
- **Frontend**: Blade Templating Engine + Vanilla CSS (Custom Design System, Flexbox/Grid, Glassmorphism UI)
- **Fonts**: Google Fonts (Inter)
- **Icons**: Emoji & Custom SVG

---

## ⚙️ Cara Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di komputer lokal Anda:

### 1. Persyaratan Sistem
- PHP >= 8.2 (Disarankan PHP 8.4)
- Composer 2.x
- Node.js & npm (Opsional, jika menggunakan Vite secara penuh)
- Laragon / XAMPP / Database MySQL apa saja

### 2. Langkah Instalasi

Clone repositori ini, lalu masuk ke dalam folder proyek:
```bash
git clone https://github.com/username-anda/cireng-pasrah.git
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
- **Nama**: Pak Haikal
- **Email**: `admin@cireng.shop`
- **Password**: `password123`

### Pelanggan (Customer)
Anda dapat menggunakan akun salah satu pelanggan di bawah ini:
- `prasetyoaditama@cireng.shop`
- `aditiarach@cireng.shop`
- `rehanpratamapasaribu@cireng.shop`
- `steephenparnaehansitumeang@cireng.shop`
- `richardpangihutansimanjuntak@cireng.shop`
- `walriansihombing@cireng.shop`

**Password untuk semua pelanggan**: `password123`

*(Alamat default semua pelanggan diatur di kota **Medan**).*

---

## 📂 Struktur Database Utama

1. `users` - Menyimpan data admin dan pelanggan.
2. `produks` - Menyimpan daftar menu cireng (Cireng Original, Keju, dll).
3. `inventoris` - Menyimpan stok barang (Cireng mentah, tepung, minyak).
4. `pesanans` - Data *header* pesanan yang dilakukan pelanggan.
5. `detail_pesanans` - Rincian item produk di dalam satu pesanan.

---

## 📄 Lisensi

Aplikasi Cireng Pasrah ini adalah perangkat lunak *open-source* yang dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

*(Dibuat dengan ❤️ untuk kemajuan bisnis Cireng Pasrah)*
