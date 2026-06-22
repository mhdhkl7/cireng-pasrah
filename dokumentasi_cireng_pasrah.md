# Dokumentasi Komprehensif: Cireng Pasrah

Dokumen ini berisi penjelasan sangat rinci mengenai arsitektur, alur data (data flow), logika bisnis, dan seluruh fitur yang ada di dalam aplikasi **Cireng Pasrah**. Aplikasi ini dibangun menggunakan framework **Laravel (PHP)** dengan konsep MVC (Model-View-Controller) dan menggunakan **MySQL** sebagai databasenya. UI/UX dibangun menggunakan murni **HTML5 & CSS3 Native** tanpa framework eksternal seperti Tailwind atau Bootstrap.

---

## 1. Arsitektur Umum & Database (Model)

Aplikasi Cireng Pasrah sangat bergantung pada pemisahan tabel relasional di database. Berikut adalah entitas utama (tabel) yang bekerja di balik layar:

1. **`users`**: Menyimpan semua data pengguna.
   - Kolom `role` membedakan hak akses: `admin`, `customer`, `driver`.
   - Menggunakan hashing untuk password.
2. **`produks`**: Menyimpan katalog cireng (Nama, Harga, Deskripsi, Gambar).
3. **`inventoris`**: Tabel *single-row* (hanya ada 1 baris) yang menyimpan total stok "Cireng Mentah" secara global.
4. **`pesanans`**: Jantung dari aplikasi. Menyimpan data transaksi lengkap (kode pesanan, total harga, ongkir, metode pengiriman, metode pembayaran, bukti transfer, dan status yang sangat dinamis).
   - Memiliki relasi `user_id` (Customer yang memesan) dan `driver_id` (Driver yang mengambil pesanan).
5. **`detail_pesanans`**: Menyimpan item apa saja yang dibeli di dalam satu pesanan (relasi ke `pesanans` dan `produks`).

---

## 2. Sistem Autentikasi, Keamanan & Middleware

Aplikasi ini memiliki 3 "pintu" keamanan agar peran tidak saling tumpang tindih:

- **Middleware Khusus:** 
  Terdapat 3 middleware kustom (`AdminMiddleware`, `CustomerMiddleware`, `DriverMiddleware`). Saat user login, jika user mencoba mengakses rute `admin/*` tapi role-nya adalah `customer`, middleware akan memblokirnya dan melemparnya kembali dengan error 403 (Unauthorized).
- **Session Timeout (Idle Timer):**
  Untuk keamanan, terdapat middleware dan JavaScript yang mendeteksi jika user tidak melakukan aktivitas selama 15 menit. Jika idle, user akan otomatis di-logout (*session destroyed*).
- **Toggle Password:**
  Di halaman Login dan Register, ada script JavaScript native murni untuk mengubah atribut input dari `type="password"` ke `type="text"` agar pengguna bisa melihat password yang mereka ketik.

---

## 3. Alur Logika: Sisi Customer

### A. Katalog & Keranjang (Session)
- Saat Customer login, mereka dilempar ke halaman `/katalog`.
- **Logic Keranjang:** Keranjang belanja TIDAK disimpan di database, melainkan di **PHP Session**.
- Saat menekan "Tambah ke Keranjang", `KeranjangController` akan membuat *array* di session. Jika produk yang sama ditambahkan lagi, logic akan menambahkan parameter `qty` (kuantitas), bukan membuat baris baru.

### B. Checkout & Perhitungan Ongkir
- Di keranjang, Customer bisa menekan Checkout.
- **Form Checkout:** Customer memilih:
  - Pengiriman: *Delivery* atau *Take Away*.
  - Jarak: Diinput manual (dalam meter). Jika Take Away, input jarak disembunyikan pakai JavaScript native.
  - Pembayaran: *Transfer* atau *Cash On Delivery (COD)*.
- **Logic Ongkir:** Di `CheckoutController`, jika Delivery, sistem menghitung `Ongkir = (Jarak / 1000) * Tarif per KM`. Misalnya tarif adalah Rp 5.000 / KM.
- **Clear Cart:** Segera setelah pesanan berhasil tersimpan di tabel `pesanans` dan `detail_pesanans`, controller mengeksekusi `session()->forget('keranjang')` untuk mengosongkan keranjang.

### C. Pembayaran & Pesanan Saya
- Jika memilih *Transfer*, status pembayaran adalah `belum_dibayar`. Customer wajib mengunggah foto bukti transfer dari halaman Detail Pesanan. Gambar tersebut disimpan menggunakan fitur `Storage` Laravel (terintegrasi dengan path `/storage/`).
- Jika memilih *COD*, status pembayaran otomatis tertahan di `belum_dibayar` sampai nanti uang diserahkan ke driver.

---

## 4. Alur Logika: Sisi Admin (Back-Office)

### A. Manajemen Stok Inventori (Automasi)
- Stok Cireng Pasrah dikonsepkan berupa "Cireng Mentah" global.
- Saat pesanan baru masuk, statusnya adalah `pending` (stok belum dipotong).
- **Logic Stok Otomatis:** Saat Admin mengubah status pesanan dari `pending` ke `diproses` (artinya mulai dimasak), `PesananController` milik Admin akan secara otomatis mengurangi tabel `inventoris` sebanyak total *qty* pesanan tersebut. Jika admin mengembalikan status ke `pending` atau `dibatalkan`, stok akan otomatis **di-refund** ke inventori.

### B. Validasi Pembayaran & Alert Refund
- Jika ada pelanggan yang batal pesanan (misalnya warung tutup) tapi mereka *sudah mentransfer*, aplikasi akan memunculkan *Alert Merah* "⚠️ Perlu Refund" di dashboard admin. Logic ini ada di `Pesanan Model` yang mendeteksi: `jika status dibatalkan DAN status_pembayaran lunas`.
- Admin bisa melakukan klik "Verifikasi Lunas" jika melihat gambar bukti transfer valid.

### C. Manajemen Pesanan (Status Trigger)
Admin mengubah status pesanan. Ada *trigger* krusial di sini:
- Saat pesanan *Delivery* diubah statusnya menjadi **"Siap"**, Controller Admin akan memotong status tersebut dan mem-bypass nya menjadi status `mencari_driver`.
- Logic Batal: Jika pesanan "Take Away" berhari-hari tidak diambil, admin bisa menekan "Selesai (Tidak Diambil)".

### D. User Management
Admin bisa melihat daftar Customer dan Driver, serta menghapus (menendang) mereka dari database jika melakukan pelanggaran.

---

## 5. Alur Logika: Sisi Driver (Sistem Pool & Pengantaran)

Sistem Driver adalah fitur paling kompleks di aplikasi ini yang menggunakan **Sistem Pool (Open Bidding / Rebutan Cepat)**.

### A. Dashboard Pool & Algoritma
Saat Driver login, `DashboardController` melakukan *query* membelah pesanan menjadi 3 bagian:
1. **Pool Terbuka:** Mengambil semua pesanan dengan status `mencari_driver` di mana `driver_id` masih kosong (`NULL`). Ini dilihat oleh *semua* driver.
2. **Aktif Saya:** Mengambil pesanan yang statusnya masih berjalan dan `driver_id` nya sama dengan id driver yang sedang login.
3. **Riwayat:** Pesanan berstatus `selesai` milik driver tersebut.

### B. Proses Pengambilan (Take Order)
- Saat Driver A menekan tombol "Ambil Pesanan", `DriverPesananController` segera mengisi kolom `driver_id` di database dengan ID Driver A, lalu mengubah statusnya ke `driver_menuju_resto`.
- Karena `driver_id` sudah tidak `NULL`, pesanan ini langsung **hilang dari layar driver lain**.

### C. Siklus Status Driver (State Machine)
Driver harus menekan tombol secara berurutan:
1. **Status:** `driver_menuju_resto` 
   - *Logic Khusus:* Ini adalah SATU-SATUNYA fase di mana driver boleh menekan tombol **"Batal"**. Jika dibatalkan, `driver_id` di-reset ke `NULL`, status kembali ke `mencari_driver`, dan pesanan muncul lagi di Pool Terbuka.
2. **Status:** `tiba_di_resto` (Mengambil cireng dari admin). Tombol batal hilang.
3. **Status:** `sedang_mengantar` (Di jalan).
   - *Logic Khusus COD:* Jika metode bayar adalah COD dan belum bayar, tombol "Selesai" biasa digantikan menjadi warna hijau "Terima Pembayaran Tunai & Selesai". Saat ditekan, controller tidak hanya menset status ke `selesai`, tapi sekaligus merubah `status_pembayaran` ke `lunas`.
4. **Status:** `selesai`.

### D. Fitur Keamanan Privasi Pelanggan (Data Masking)
- Di bagian "Riwayat Selesai" driver, ada potensi penyalahgunaan data alamat pelanggan di masa depan.
- Solusinya ada di Model `Pesanan.php` melalui **Accessor (Mutator)**: `getMaskedAlamatAttribute()`.
- Algoritmanya: Mengambil string alamat (misal: "Jalan Merdeka No 10 Bandung"), memecahnya dengan spasi (*explode*). Kata pertama dan kedua dibiarkan, sisa kata lainnya dipotong dan diganti dengan asteris (`***`).
- Hasil di layar driver (khusus pesanan selesai): `"Jalan Merdeka No*** Ba***"`.

---

## 6. CSS Architecture & Desain
Tidak ada penggunaan Tailwind. Semua styling dilakukan dengan teknik:
- **CSS Variables (:root):** Disimpan di dalam file layout `admin.blade.php`, `customer.blade.php`, dan `driver.blade.php`.
  - Admin & Customer: Menggunakan `--primary: #2563eb;` (Biru Solid Profesional).
  - Driver: Menggunakan `--primary: #d97706;` (Amber/Oranye Solid yang kontras tapi tidak bikin sakit mata).
- Desain menghindari efek `linear-gradient` untuk menuruti standar UI datar (*flat design*) yang bersih.
- Komponen *Card*, *Badge*, dan tabel dideklarasikan dengan `<style>` secara langsung namun di-modularisasi agar ringan tanpa harus me-load library CSS berukuran besar (seperti Bootstrap).

---

## Kesimpulan Siklus Hidup Pesanan (Life Cycle)
1. **Customer:** Klik Beli -> Keranjang -> Checkout (Hitung Jarak). Database Terisi.
2. **Admin:** Cek Pesanan -> Ubah "Diproses" (Stok Gudang Berkurang) -> Ubah "Siap" (Langsung terlempar ke `mencari_driver`).
3. **Driver:** Lihat Pool Terbuka -> Klik Ambil -> Ke Resto -> Mengantar -> Terima Uang (Jika COD) -> Selesai.
4. **Alamat:** Setelah selesai, alamat di HP Driver langsung di-masking untuk privasi. 

Sistem ini memastikan tidak ada bentrokan antara pesanan, menjaga alur stok tetap sinkron, memberikan batasan hak (privilege) yang solid antar user, serta menjamin keamanan data.
