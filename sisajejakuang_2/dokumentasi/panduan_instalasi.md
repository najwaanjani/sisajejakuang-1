# Panduan Instalasi & Konfigurasi Lokal - SisaJejakUang

Dokumen ini menjelaskan langkah-langkah instalasi, konfigurasi, dan cara menjalankan aplikasi **SisaJejakUang** di lingkungan lokal menggunakan XAMPP.

---

## Prasyarat Sistem

Sebelum memulai instalasi, pastikan sistem Anda memiliki komponen berikut:
1. **XAMPP** (disarankan versi dengan PHP 8.x atau yang terbaru).
2. **Web Browser** (Google Chrome, Firefox, Microsoft Edge, dsb).
3. **Database Client** (phpMyAdmin bawaan XAMPP atau alat visual seperti DBeaver/HeidiSQL).

---

## Langkah-langkah Instalasi

### 1. Salin Folder Proyek
Salin folder proyek `sisajejakuang_2` ke dalam direktori dokumen root server web lokal Anda:
* **Windows**: `C:\xampp\htdocs\sisajejakuang_2`
* **macOS**: `/Applications/XAMPP/xamppfiles/htdocs/sisajejakuang_2`
* **Linux**: `/opt/lampp/htdocs/sisajejakuang_2`

### 2. Jalankan Apache & MySQL Server
1. Buka **XAMPP Control Panel**.
2. Klik tombol **Start** pada baris **Apache** dan **MySQL**.
3. Pastikan indikator status keduanya berwarna hijau, menandakan server telah aktif secara normal.

### 3. Konfigurasi Database (MySQL & phpMyAdmin)
1. Buka browser Anda dan akses alamat `http://localhost/phpmyadmin/`.
2. Buat database baru bernama `db_sisajejakuang`:
   * Klik menu **New** di sebelah kiri.
   * Masukkan nama database: `db_sisajejakuang`.
   * Pilih kolasi default (`utf8mb4_general_ci` atau `utf8mb4_unicode_ci`).
   * Klik tombol **Create**.
3. Impor skema tabel dan data sampel:
   * Klik nama database `db_sisajejakuang` yang baru dibuat.
   * Klik tab **Import** di bagian atas menu phpMyAdmin.
   * Klik tombol **Choose File** (Pilih File) dan cari berkas SQL di dalam folder proyek Anda: `C:\xampp\htdocs\sisajejakuang_2\database\db_sisajejakuang.sql`.
   * Scroll ke bawah halaman, lalu klik tombol **Import** (atau **Go**).
   * Pastikan semua tabel dan data sampel berhasil diimpor tanpa pesan kesalahan.

### 4. Sesuaikan Kredensial Database
Buka berkas konfigurasi database proyek Anda pada path:
[config/db.php](file:///c:/xampp/htdocs/sisajejakuang_2/config/db.php)

Sesuaikan variabel-variabel berikut berdasarkan konfigurasi MySQL pada komputer lokal Anda:

```php
$host = '127.0.0.1:3306'; // Sesuaikan port MySQL Anda (default: 3306 atau 3307)
$db   = 'db_sisajejakuang';
$user = 'root';            // Default user MySQL di XAMPP
$pass = '';                // Default password MySQL di XAMPP (kosongkan jika tidak memakai password)
```

> [!NOTE]  
> Pastikan variabel `$host` mencantumkan port MySQL yang benar (misalnya `localhost:3307` jika Anda menggunakan port kustom 3307, atau cukup `127.0.0.1` jika menggunakan port default 3306).

---

## Mengakses Aplikasi

Setelah instalasi selesai dan server berjalan:
1. Buka web browser Anda.
2. Akses URL aplikasi di localhost:
   `http://localhost/sisajejakuang_2/`
3. Anda akan diarahkan ke halaman masuk (login).

### Akun Demo untuk Pengujian:

Anda dapat menggunakan salah satu akun di bawah ini untuk mencoba aplikasi:

1. **Akun Pengguna Biasa (User Mode)**:
   * **Email**: `najwa@sisajejakuang.com`
   * **Password**: `najwa123`
2. **Akun Administrator (Admin Mode)**:
   * **Email**: `admin@sisajejakuang.com`
   * **Password**: `admin123`

---

## Troubleshooting (Penyelesaian Masalah)

* **Error: Koneksi database gagal**:  
  Buka kembali file [config/db.php](file:///c:/xampp/htdocs/sisajejakuang_2/config/db.php) dan verifikasi bahwa port (`host`), nama database (`db`), nama pengguna (`user`), dan kata sandi (`pass`) sudah sesuai dengan setelan MySQL aktif di XAMPP Control Panel Anda.
* **Error: Upload Struk Gagal / Folder Uploads Tidak Ditemukan**:  
  Pastikan folder `uploads/receipts` ada pada direktori proyek dan memiliki hak izin menulis (*write permissions*) agar server web PHP dapat menyimpan file gambar bukti struk transaksi yang diunggah.
