# UAS Pemrograman Web - Website Perumahan "Summarecon Mutiara Makassar"

Proyek ini adalah implementasi sistem informasi profil perumahan dinamis berbasis **PHP Native** dan **MySQL** (menggunakan driver **MySQLi Prosedural**). Dibuat untuk memenuhi kriteria tugas UAS Pemrograman Web dengan menggabungkan estetika desain premium Bootstrap 5, CSS kustom, serta validasi sisi klien menggunakan JavaScript.

## Fitur Utama

1. **Landing Page (`index.php`):**
   - Menampilkan koleksi tipe unit perumahan secara dinamis langsung dari database.
   - Menampilkan sisa kapasitas stok unit (misalnya: "Tersedia: 10 Unit" atau "Habis") dan harga terkini.
   - Form kontak customer (`send_message.php`) terproteksi validasi JavaScript dan langsung menyimpan pesan ke database.
2. **Otentikasi Staf (`login.php` & `logout.php`):**
   - Halaman masuk staf administrasi dengan verifikasi session aman dan password ter-hash bcrypt.
3. **Dasbor Admin (`admin/dashboard.php`):**
   - Panel visual ringkasan statistik stok unit perumahan.
   - Pengelolaan pesan masuk dari customer (`admin/messages.php`).
4. **CRUD Unit Properti (`admin/add.php`, `admin/edit.php`, `admin/delete.php`):**
   - Menambah, mengedit, dan menghapus unit properti dengan input kapasitas stok manual.
   - Penentuan status ketersediaan otomatis (`stok > 0` -> Tersedia, `stok = 0` -> Habis).
   - Pengunggahan gambar aman (maksimal 2MB, ekstensi JPG/JPEG/PNG) lengkap dengan auto-clean gambar lama di server saat dihapus atau diubah.

---

## Spesifikasi Teknologi

- **Backend**: PHP 8.x Native (Sintaks Prosedural MySQLi dengan Prepared Statements untuk proteksi SQL Injection).
- **Database**: MySQL / MariaDB (`db_summarecon`).
- **Frontend**: Bootstrap 5 (tata letak responsif, tabel, formulir, alert) & Custom CSS (`css/style.css` untuk micro-interactions, kustom tombol, hover zoom kartu).
- **Validasi**: JavaScript Vanilla (`js/main.js`) untuk validasi form real-time sisi klien (Bootstrap error state styling).

---

## Struktur Folder Proyek

```text
uas-pemograman-web/
├── admin/
│   ├── add.php          # Form tambah tipe unit baru
│   ├── dashboard.php    # Dasbor utama admin & daftar properti
│   ├── delete.php       # Aksi penghapusan unit properti
│   ├── edit.php         # Form edit data properti & stok
│   └── messages.php     # Panel log pesan masuk dari customer
├── css/
│   └── style.css        # Gaya kustom & override Bootstrap
├── img/                 # Folder penyimpanan gambar unit properti
├── includes/
│   ├── auth.php         # Middleware pengaman session admin
│   ├── db.php           # Inisialisasi koneksi database MySQLi
│   ├── footer.php       # Template footer & pemuatan script JS
│   └── header.php       # Template navbar responsif & penanganan path
├── js/
│   └── main.js          # Skrip validasi form dan konfirmasi modal
├── database.sql         # Struktur database & data bawaan (seeds)
├── index.php            # Halaman utama / landing page publik
├── login.php            # Antarmuka masuk staf admin
├── logout.php           # Menghapus session staf admin
├── send_message.php     # Aksi pengiriman pesan kontak customer
└── README.md            # Dokumentasi proyek
```

---

## Cara Menjalankan Proyek Secara Lokal

### 1. Persiapan Database
1. Buka MySQL admin (phpMyAdmin / Laragon / XAMPP).
2. Buat database baru bernama `db_summarecon`.
3. Impor berkas [database.sql](database.sql) ke dalam database tersebut.

### 2. Konfigurasi Koneksi
Buka berkas [includes/db.php](includes/db.php), sesuaikan kredensial server MySQL Anda:
```php
$host = 'localhost';
$db   = 'db_summarecon';
$user = 'root'; // Sesuaikan user Anda
$pass = '';     // Sesuaikan password Anda
```

### 3. Menjalankan Server
Jalankan server PHP lokal (melalui terminal di folder proyek):
```bash
php -S localhost:8000
```
Buka browser dan akses alamat `http://localhost:8000`.

### 4. Detail Kredensial Login Admin
Akses halaman login staf melalui browser di `http://localhost:8000/login.php` dengan akun default berikut:
- **Username**: `admin`
- **Password**: `admin123`
