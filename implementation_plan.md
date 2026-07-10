# Blueprint Migrasi Aplikasi SisaJejakUang ke Native PHP & MySQL (phpMyAdmin)

Dokumen ini berisi cetak biru (blueprint) arsitektur, skema database, dan panduan langkah demi langkah untuk melakukan migrasi aplikasi **SisaJejakUang** dari mockup statis (`index.html` dengan `localStorage`) menjadi aplikasi web dinamis berbasis **Native PHP (Pure PHP)** dan basis data **MySQL** yang dikelola menggunakan **phpMyAdmin** pada localhost (XAMPP).

---

## 1. Arsitektur Database (MySQL)

Berikut adalah struktur tabel fisik yang akan dibuat di MySQL melalui phpMyAdmin. Relasi antar tabel tetap dipertahankan dengan foreign key untuk menjaga integritas data:

```mermaid
erDiagram
    tb_user ||--o{ tb_buku_tabungan : "memiliki"
    tb_user ||--o{ tb_kategori : "membuat"
    tb_user ||--o{ tb_transaksi : "memiliki"
    tb_buku_tabungan ||--o{ tb_anggaran : "memiliki"
    tb_buku_tabungan ||--o{ tb_transaksi : "mencatat"
    tb_anggaran ||--o{ tb_transaksi : "mengalokasikan"
    tb_kategori ||--o{ tb_transaksi : "mengelompokkan"

    tb_user {
        int id PK AUTO_INCREMENT
        string name
        string email UNIQUE
        string password
        enum role "admin, user"
        datetime created_at
        datetime updated_at
    }
    tb_buku_tabungan {
        int id PK AUTO_INCREMENT
        int user_id FK
        string nama_buku
        decimal saldo_awal
        decimal saldo_saat_ini
        datetime created_at
        datetime updated_at
    }
    tb_anggaran {
        int id PK AUTO_INCREMENT
        int buku_tabungan_id FK
        string nama_anggaran
        decimal batas_limit
        datetime created_at
        datetime updated_at
    }
    tb_kategori {
        int id PK AUTO_INCREMENT
        int user_id FK "nullable (null jika kategori master)"
        string nama_kategori
        enum jenis_kategori "master, kustom"
        datetime created_at
        datetime updated_at
    }
    tb_transaksi {
        int id PK AUTO_INCREMENT
        int user_id FK
        int buku_tabungan_id FK
        int anggaran_id FK "nullable (null jika anggaran bebas)"
        int kategori_id FK
        date tanggal_transaksi
        string keterangan
        decimal nominal
        enum prioritas "Kebutuhan, Keinginan"
        string bukti_pengeluaran "nullable (path file gambar)"
        enum input_method "manual, ocr"
        datetime created_at
        datetime updated_at
    }
    tb_system_logs {
        int id PK AUTO_INCREMENT
        string actor
        string action
        string status
        datetime created_at
    }
```

---

## 2. Struktur Direktori Proyek (Native PHP)

Untuk menjaga kerapian kode tanpa menggunakan framework, kita akan mengadopsi struktur modular sederhana di mana bagian frontend (`index.php`) berkomunikasi dengan backend melalui **AJAX API endpoints** (mengirim dan menerima format JSON).

```
sisajejakuang_1/
│
├── config/
│   └── db.php                  # Koneksi database menggunakan PHP PDO
│
├── database/
│   └── db_sisajejakuang.sql    # File SQL skema dan seed data untuk phpMyAdmin
│
├── uploads/
│   └── receipts/               # Folder untuk menyimpan bukti upload struk transaksi
│
├── api/                        # API Endpoint untuk melayani request JSON dari Frontend
│   ├── auth.php                # Registrasi, Login, dan Logout
│   ├── dashboard.php           # Data Ringkasan Dashboard (User & Admin)
│   ├── buku_tabungan.php       # CRUD Buku Tabungan
│   ├── anggaran.php            # CRUD Anggaran
│   ├── kategori.php            # CRUD Kategori (Master & Kustom)
│   ├── transaksi.php           # CRUD Transaksi & Simulasi OCR
│   └── admin.php               # Operasi Admin (Audit Log, List Pengguna, Dump SQL)
│
├── index.php                   # Frontend Antarmuka Utama (Migrasi dari index.html)
└── README.md
```

---

## User Review Required

> [!IMPORTANT]
> **Metode Komunikasi Data (SPA AJAX)**
> Kita akan tetap mempertahankan antarmuka bawaan yang sangat responsif (SPA) dengan mengubah penyimpanan dari `localStorage` menjadi panggilan `Fetch API` ke berkas PHP di folder `api/`. Ini berarti halaman tidak akan melakukan reload penuh saat menambahkan transaksi atau berganti menu.
> 
> **Penyimpanan Upload Bukti Transaksi**
> Bukti transaksi yang diunggah atau disimulasikan melalui OCR akan disimpan secara fisik di folder lokal `uploads/receipts/`. Pastikan folder ini memiliki hak akses menulis (writable).

---

## Open Questions

> [!NOTE]
> **Akun Default untuk Login Pertama Kali**
> Kami akan menyediakan seed data akun bawaan di dalam berkas SQL:
> - **Akun Admin**: `admin@sisajejakuang.com` (password: `admin123`)
> - **Akun User Demo**: `najwa@sisajejakuang.com` (password: `user123`)
> 
> *Apakah Anda memerlukan fitur pendaftaran akun baru (Register) secara visual di antarmuka, atau cukup form Login saja dengan akun-akun demo tersebut?*

---

## Proposed Changes

### Database & Configuration

#### [NEW] [db_sisajejakuang.sql](file:///c:/xampp/htdocs/sisajejakuang_1/database/db_sisajejakuang.sql)
Membuat file database SQL mentah yang mendefinisikan seluruh tabel, foreign key constraints, dan memasukkan data default (Admin Kelompok 31, Kategori Master default, dan beberapa buku tabungan awal).

#### [NEW] [db.php](file:///c:/xampp/htdocs/sisajejakuang_1/config/db.php)
Menginisialisasi koneksi PDO MySQL dengan penanganan error try-catch yang aman.

```php
<?php
// config/db.php
$host = '127.0.0.1';
$db   = 'db_sisajejakuang';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     header('Content-Type: application/json');
     echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $e->getMessage()]);
     exit;
}
```

---

### API Backend (JSON Endpoints)

#### [NEW] [auth.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/auth.php)
Mengelola session login pengguna. Menerima request POST untuk login/logout/register dan mengembalikan JSON status keberhasilan beserta peran (role) user.

#### [NEW] [dashboard.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/dashboard.php)
Mengembalikan statistik finansial agregat untuk halaman dashboard user (total saldo, total pengeluaran, batas anggaran, rasio prioritas) dan admin (rasio kepatuhan anggaran, efisiensi OCR, tren pengeluaran mingguan).

#### [NEW] [buku_tabungan.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/buku_tabungan.php)
Menerima request GET untuk mengambil daftar buku tabungan, POST untuk membuat buku tabungan baru, dan DELETE untuk menghapusnya.

#### [NEW] [anggaran.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/anggaran.php)
Mengelola anggaran spesifik pada buku tabungan tertentu.

#### [NEW] [kategori.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/kategori.php)
Mengelola kategori master (oleh admin) dan kategori kustom (oleh pengguna).

#### [NEW] [transaksi.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/transaksi.php)
Menyimpan transaksi pengeluaran.
- Melakukan validasi saldo: menolak transaksi jika nominal melebihi `saldo_saat_ini` di buku tabungan terkait.
- Mengurangi `saldo_saat_ini` di tabel `tb_buku_tabungan` secara atomik (menggunakan Database Transactions).
- Mendukung simulasi input file struk belanja (OCR) dan menyimpannya di folder `uploads/receipts/`.

#### [NEW] [admin.php](file:///c:/xampp/htdocs/sisajejakuang_1/api/admin.php)
Menyediakan daftar log audit aktivitas sistem dari tabel `tb_system_logs`, daftar audit seluruh pengguna beserta saldo mereka, serta mengembalikan data dump SQL secara dinamis untuk fitur backup database admin.

---

### Frontend Integration

#### [NEW] [index.php](file:///c:/xampp/htdocs/sisajejakuang_1/index.php)
- Menyertakan `session_start()` di baris paling atas untuk mendeteksi sesi login pengguna. Jika sesi tidak aktif, menampilkan form login premium yang terintegrasi dengan backend.
- Menggantikan fungsi manipulasi array lokal JavaScript (yang sebelumnya membaca dari `localStorage`) dengan panggilan `fetch()` menuju endpoints `api/*.php`.
- Mengimplementasikan UI responsif dan interaktif yang persis seperti desain statis asli menggunakan Tailwind CSS dan Lucide icons.

#### [DELETE] [index.html](file:///c:/xampp/htdocs/sisajejakuang_1/index.html)
Dihapus karena struktur HTML dan JS-nya sudah digabungkan secara utuh ke dalam file dinamis PHP `index.php`.

---

## 3. Langkah-Langkah Uji Coba di Localhost (Step-by-Step)

Untuk menjalankan dan menguji aplikasi ini secara lokal menggunakan XAMPP:

### Langkah 1: Persiapan Server Lokal (XAMPP)
1. Buka **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache** dan **MySQL**. Pastikan indikator keduanya berwarna hijau.

### Langkah 2: Penempatan Berkas Proyek
1. Pastikan folder proyek bernama `sisajejakuang_1` berada di dalam direktori `htdocs` XAMPP Anda.
   * Path lengkap: `C:\xampp\htdocs\sisajejakuang_1\`

### Langkah 3: Membuat & Mengimpor Database via phpMyAdmin
1. Buka browser Anda dan akses halaman admin database: `http://localhost/phpmyadmin/`
2. Klik menu **New** di kolom kiri.
3. Masukkan nama database: **`db_sisajejakuang`**, lalu klik tombol **Create**.
4. Pilih database `db_sisajejakuang` yang baru dibuat di kolom kiri.
5. Klik tab **Import** di bagian atas.
6. Klik tombol **Choose File** (Pilih Berkas) dan cari file SQL di dalam proyek: `C:\xampp\htdocs\sisajejakuang_1\database\db_sisajejakuang.sql`
7. Gulir ke bawah dan klik tombol **Import** (atau **Go**). Tunggu hingga muncul pesan keberhasilan berwarna hijau.

### Langkah 4: Uji Coba Aplikasi di Browser
1. Buka browser baru atau tab baru.
2. Akses alamat: **`http://localhost/sisajejakuang_1/`**
3. Anda akan disambut oleh halaman Login. Masukkan kredensial demo berikut:
   * **Sebagai User**: Email `najwa@sisajejakuang.com` | Password `user123`
   * **Sebagai Admin**: Email `admin@sisajejakuang.com` | Password `admin123`
4. Selamat mencoba seluruh fitur!

---

## 4. Rencana Verifikasi

### Manual Verification
- **Verifikasi Autentikasi**: Mencoba login menggunakan email/password salah, mencoba mendaftar akun baru, dan melakukan logout untuk memastikan sesi terhapus.
- **Verifikasi Transaksi**: Membuat transaksi baru dan memverifikasi bahwa saldo di buku tabungan yang bersangkutan otomatis berkurang di database MySQL.
- **Pengujian Batas Anggaran**: Memasukkan transaksi melebihi limit anggaran dan memastikan peringatan muncul.
- **Verifikasi Halaman Admin**: Masuk ke Admin Mode dan memastikan Log Audit mencatat aktivitas login/transaksi secara akurat, serta mencoba mengunduh dump SQL database.
