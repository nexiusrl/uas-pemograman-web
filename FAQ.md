# Tanya Jawab (FAQ) - Pengembangan Website Perumahan

Berikut adalah jawaban dari pertanyaan terkait implementasi proyek website perumahan "Summarecon Mutiara Makassar":

### 1. Apakah proyek anda menggunakan session atau cookie untuk menyimpan data login? Sebutkan data apa saja yang disimpan!
Proyek ini menggunakan **session** untuk menyimpan status login staf administrator. Data session didefinisikan saat otentikasi berhasil pada `login.php` dan diverifikasi di setiap modul admin melalui `includes/auth.php`.
Data yang disimpan dalam session adalah:
*   `$_SESSION['admin_logged_in']` (boolean): Bernilai `true` untuk menandakan bahwa pengguna telah berhasil masuk sebagai administrator.
*   `$_SESSION['username']` (string): Nama pengguna admin yang sedang aktif (misalnya: `admin`).

---

### 2. Berapa jumlah tabel pada database yang digunakan dalam aplikasi web Anda? Jelaskan secara singkat fungsi dari masing-masing tabel tersebut!
Aplikasi menggunakan **3 tabel** di dalam database `db_summarecon`:
*   `users`: Menyimpan kredensial staf admin (id, username, password ter-hash bcrypt) untuk otentikasi masuk ke panel admin.
*   `housing_units`: Menyimpan data inventaris tipe/model perumahan yang terdaftar (id, nama unit, tipe, harga, stok, status, nama file gambar, deskripsi).
*   `contact_messages`: Menyimpan kiriman pesan kontak dari customer (id, nama, email, telepon, isi pesan, tanggal kirim).

---

### 3. Jelaskan satu proses dalam proyek anda yang melibatkan pengolahan data pada tabel database dan tuliskan letak (nama file dan baris kode) dari proses tersebut!
*   **Proses**: Penambahan (Insert) data unit perumahan baru ke dalam tabel `housing_units`. 
*   **Penjelasan**: Staf memasukkan data unit baru beserta stoknya lewat form. Sistem memproses unggahan file gambar, memvalidasi ukuran dan ekstensinya, lalu menghitung status ketersediaan secara otomatis (`stok > 0` -> 'Tersedia', `stok = 0` -> 'Habis'). Data disimpan dengan aman menggunakan prepared statements MySQLi untuk menghindari SQL Injection.
*   **Letak**: [admin/add.php](file:///D:/Dzul/Kuliah/Semester%204/Pemograman%20Web/uas-pemograman-web/admin/add.php) baris 51–81.

---

### 4. Apa kendala yang anda hadapi selama pengerjaan proyek ini?
*   **Ketiadaan Modul `fileinfo` PHP**: Server pengujian lokal tidak mengaktifkan extension `fileinfo` (menyebabkan fungsi bawaan `mime_content_type()` tidak terdefinisi dan memicu fatal error). Kendala diselesaikan dengan mengganti deteksi jenis file menggunakan ekstensi nama file `pathinfo()` dikombinasikan dengan pemeriksaan keabsahan dimensi gambar melalui `@getimagesize()`.
*   **Logika Ketersediaan Otomatis**: Mensinkronisasikan ketersediaan stok angka dengan status tekstual ('Tersedia' / 'Habis') secara otomatis di backend ketika admin menyimpan data, sehingga tidak merepotkan admin untuk menginput dua data berulang.

---

### 5. Jelaskan secara singkat satu proses dalam proyek anda yang menggunakan JavaScript, dan tuliskan letak (nama file dan baris kode) dari proses tersebut!
*   **Proses**: Validasi form kontak sisi klien (Client-side validation).
*   **Penjelasan**: Sebelum data form dikirimkan ke server PHP di halaman utama, JavaScript mencegah submit form jika ada kolom input (nama, email, telepon, pesan) yang kosong atau format email tidak sesuai dengan standar regex. Jika input tidak valid, kelas Bootstrap `.is-invalid` ditambahkan untuk memberi visualisasi error merah kepada pengguna.
*   **Letak**: [js/main.js](file:///D:/Dzul/Kuliah/Semester%204/Pemograman%20Web/uas-pemograman-web/js/main.js) baris 2–50.

---

### 6. Tuliskan link github projek anda!
Link repositori GitHub proyek:
`https://github.com/nexiusrl/uas-pemograman-web`
