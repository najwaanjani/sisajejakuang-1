# Arsitektur & Desain Sistem - SisaJejakUang

Dokumen ini menjelaskan arsitektur perangkat lunak, struktur direktori, dan perancangan basis data relasional untuk aplikasi **SisaJejakUang**.

---

## 1. Arsitektur Perangkat Lunak

Aplikasi **SisaJejakUang** menggunakan arsitektur **Single Page Application (SPA)** sederhana berbasis **Native PHP** di sisi backend dan kombinasi **Tailwind CSS + Vanilla JS** di sisi frontend.

* **Frontend**: Antarmuka web disajikan melalui satu file utama, yaitu `index.php`. Navigasi antar halaman disimulasikan menggunakan JavaScript dengan memanipulasi visibilitas (show/hide) elemen HTML menggunakan class Tailwind CSS (`hidden`). Kueri dan pembaruan data dilakukan secara asinkron menggunakan `fetch()` API ke backend PHP.
* **Backend**: Kumpulan skrip PHP modular di dalam folder `api/` bertindak sebagai API Endpoints. backend ini bertugas memproses data input dari frontend, memvalidasi aturan bisnis (misalnya pembatasan limit anggaran), mengelola sesi aktif (`session_start`), dan berkomunikasi dengan MySQL RDBMS melalui driver **PDO (PHP Data Objects)**.
* **Database**: Database relasional **MySQL** menyimpan seluruh data persisten (User, Tabungan, Anggaran, Kategori, Transaksi, dan Audit Log).

```dot
digraph G {
    rankdir=TB;
    node [shape=box, style=rounded, fontname="Helvetica"];
    
    Client [label="Browser Frontend / index.php"];
    AuthAPI [label="API Autentikasi / api/auth.php"];
    TrxAPI [label="API Transaksi / api/transaksi.php"];
    AccAPI [label="API Buku Tabungan / api/buku_tabungan.php"];
    BudAPI [label="API Anggaran / api/anggaran.php"];
    CatAPI [label="API Kategori / api/kategori.php"];
    AdmAPI [label="API Admin & Log / api/admin.php"];
    
    DB [label="MySQL Database", shape=cylinder];
    
    Client -> AuthAPI [label="AJAX Fetch JSON"];
    Client -> TrxAPI [label="AJAX Fetch JSON"];
    Client -> AccAPI [label="AJAX Fetch JSON"];
    Client -> BudAPI [label="AJAX Fetch JSON"];
    Client -> CatAPI [label="AJAX Fetch JSON"];
    Client -> AdmAPI [label="AJAX Fetch JSON"];
    
    AuthAPI -> DB [label="PDO SQL"];
    TrxAPI -> DB [label="PDO SQL"];
    AccAPI -> DB [label="PDO SQL"];
    BudAPI -> DB [label="PDO SQL"];
    CatAPI -> DB [label="PDO SQL"];
    AdmAPI -> DB [label="PDO SQL"];
}
```

---

## 2. Struktur Direktori Proyek

Berikut adalah struktur direktori aplikasi **SisaJejakUang**:

```text
sisajejakuang_2/
│
├── config/
│   └── db.php                  # Konfigurasi & inisialisasi koneksi PDO ke MySQL
│
├── database/
│   └── db_sisajejakuang.sql    # Skema DDL dan data awal (seeds) untuk database
│
├── dokumentasi/
│   ├── arsitektur_dan_desain.md # Dokumen arsitektur dan database (Dokumen Ini)
│   ├── panduan_instalasi.md     # Langkah-langkah menjalankan aplikasi di lokal
│   ├── dokumentasi_api.md       # Spesifikasi request/response API endpoint
│   ├── panduan_pengguna.md      # Manual operasional user dan admin
│   └── riwayat_pengembangan.md # Log perubahan dan catatan rilis pengembangan
│
├── api/                        # API Endpoints untuk melayani request AJAX JSON
│   ├── auth.php                # Autentikasi (login, register, logout, hapus akun)
│   ├── dashboard.php           # Data agregat dashboard user dan admin
│   ├── transaksi.php           # CRUD Transaksi dan upload bukti transaksi
│   ├── buku_tabungan.php       # CRUD Buku Tabungan
│   ├── anggaran.php            # CRUD Anggaran dengan validasi limit saldo
│   ├── kategori.php            # CRUD Kategori master dan kustom
│   └── admin.php               # Halaman log audit & manajemen sistem admin
│
├── uploads/
│   └── receipts/               # Folder untuk menyimpan bukti upload struk transaksi
│
├── index.php                   # Halaman utama aplikasi (Frontend SPA & Router)
└── implementation_plan.md      # Rencana kerja pengembangan awal
```

---

## 3. Desain Basis Data (MySQL)

### Diagram Hubungan Entitas (ERD)

Aplikasi ini menggunakan skema relasional dengan dependensi *Foreign Key* dan aksi *Cascading* (`ON DELETE CASCADE`) untuk memastikan integritas data.

```dot
digraph ERD {
    graph [pad="0.5", nodesep="0.5", ranksep="1", rankdir=TB];
    node [shape=plain, fontname="Helvetica"];
    
    tb_user [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#855CF8" align="center"><font color="white"><b>tb_user</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">name : VARCHAR</td></tr>
            <tr><td align="left">email (UK) : VARCHAR</td></tr>
            <tr><td align="left">password : VARCHAR</td></tr>
            <tr><td align="left">role : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_buku_tabungan [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#3B82F6" align="center"><font color="white"><b>tb_buku_tabungan</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">user_id (FK) : INT</td></tr>
            <tr><td align="left">nama_buku : VARCHAR</td></tr>
            <tr><td align="left">saldo_awal : DECIMAL</td></tr>
            <tr><td align="left">saldo_saat_ini : DECIMAL</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_anggaran [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#10B981" align="center"><font color="white"><b>tb_anggaran</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">buku_tabungan_id (FK) : INT</td></tr>
            <tr><td align="left">nama_anggaran : VARCHAR</td></tr>
            <tr><td align="left">batas_limit : DECIMAL</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_kategori [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#F59E0B" align="center"><font color="white"><b>tb_kategori</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">user_id (FK, nullable) : INT</td></tr>
            <tr><td align="left">nama_kategori : VARCHAR</td></tr>
            <tr><td align="left">jenis_kategori : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_transaksi [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#EF4444" align="center"><font color="white"><b>tb_transaksi</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">user_id (FK) : INT</td></tr>
            <tr><td align="left">buku_tabungan_id (FK) : INT</td></tr>
            <tr><td align="left">anggaran_id (FK, nullable) : INT</td></tr>
            <tr><td align="left">kategori_id (FK) : INT</td></tr>
            <tr><td align="left">tanggal_transaksi : DATE</td></tr>
            <tr><td align="left">keterangan : VARCHAR</td></tr>
            <tr><td align="left">nominal : DECIMAL</td></tr>
            <tr><td align="left">prioritas : ENUM</td></tr>
            <tr><td align="left">bukti_pengeluaran (nullable) : VARCHAR</td></tr>
            <tr><td align="left">input_method : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_system_logs [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#6B7280" align="center"><font color="white"><b>tb_system_logs</b></font></td></tr>
            <tr><td align="left">id (PK) : INT</td></tr>
            <tr><td align="left">actor : VARCHAR</td></tr>
            <tr><td align="left">action : VARCHAR</td></tr>
            <tr><td align="left">status : VARCHAR</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
        </table>
    >];

    # Relasi / Kardinalitas
    tb_user -> tb_buku_tabungan [label="1 : N (memiliki)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_user -> tb_kategori [label="1 : N (membuat)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_user -> tb_transaksi [label="1 : N (memiliki)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_buku_tabungan -> tb_anggaran [label="1 : N (memiliki)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_buku_tabungan -> tb_transaksi [label="1 : N (mencatat)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_anggaran -> tb_transaksi [label="1 : N (mengalokasikan)", arrowhead="crow", arrowtail="none", dir="both"];
    tb_kategori -> tb_transaksi [label="1 : N (mengelompokkan)", arrowhead="crow", arrowtail="none", dir="both"];
}
```

### Kamus Data Tabel Utama

1. **`tb_user`**: Menyimpan data identitas kredensial pengguna dan perannya.
   * `id`: Primary key pengguna.
   * `role`: Menentukan hak akses pengguna (`admin` untuk panel global, `user` untuk personal).
2. **`tb_buku_tabungan`**: Wadah rekening/dompet keuangan mandiri milik user.
   * `saldo_saat_ini`: Saldo aktif yang disesuaikan secara dinamis berdasarkan kalkulasi transaksi kredit/debet.
3. **`tb_anggaran`**: Pos pengeluaran terencana yang diikat pada buku tabungan.
   * `batas_limit`: Nilai limit anggaran. Aturan bisnis menetapkan nilai ini tidak boleh melebihi `saldo_saat_ini` pada buku tabungan terkait.
4. **`tb_kategori`**: Klasifikasi transaksi. Kategori berskala master memiliki `user_id` bernilai `NULL` (dapat digunakan oleh semua user).
5. **`tb_transaksi`**: Catatan jurnal transaksi keuangan debet atau kredit.
   * `prioritas`: Sifat prioritas anggaran (`Kebutuhan` atau `Keinginan`).
   * `bukti_pengeluaran`: Path penyimpanan file struk belanja.
6. **`tb_system_logs`**: Log audit jejak sistem untuk merekam aksi login, manipulasi data keuangan, dan perubahan penting lainnya.
