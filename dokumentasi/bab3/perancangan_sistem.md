# BAB 3: PERANCANGAN SISTEM

## 3.1 Arsitektur Sistem
Sistem aplikasi **SisaJejakUang** menggunakan model arsitektur terpisah sederhana yang memisahkan antara layer presentasi yaitu Frontend dan layer pemrosesan data yaitu Backend dan Database. Hal ini bertujuan agar kode program lebih modular, rapi, dan mudah dirawat.

* **Frontend Layer**: Berupa file tunggal `index.php` yang menggabungkan elemen struktur HTML5, styling Tailwind CSS, dan pustaka ikon Lucide. Klien menangani interaksi pengguna dan memperbarui konten halaman secara dinamis menggunakan Vanilla JavaScript tanpa memuat ulang browser.
* **API Backend Layer**: Terdiri dari skrip-skrip PHP modular di dalam folder `api/`. Skrip ini menerima request AJAX berupa metode GET, POST, dan DELETE dari JavaScript, melakukan validasi aturan bisnis, memproses query SQL, dan mengembalikan respons terstruktur dalam format JSON.
* **Database Layer**: RDBMS MySQL yang diakses menggunakan driver PHP Data Objects yaitu PHP Data Objects untuk memastikan komunikasi basis data yang aman dari celah keamanan seperti SQL Injection.

Berikut adalah diagram aliran data arsitektur sistem secara vertikal menggunakan Graphviz:

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

## 3.2 Workflow Sistem
Alur kerja utama dalam ekosistem sistem digambarkan melalui narasi operasional di bawah ini:
1. **Inisialisasi Sesi**: Pengguna memasukkan email dan password -> Browser mengirim request autentikasi ke `api/auth.php` -> Sistem memeriksa data dan password hash -> Jika cocok, sistem mencatat detail ke dalam session PHP server dan mengembalikan respons sukses -> Browser memuat halaman dashboard utama secara dinamis.
2. **Pembuatan Dompet**: Pengguna menginputkan nama dompet tabungan dan nominal saldo awal -> Data dikirim ke `api/buku_tabungan.php` -> Saldo disimpan di MySQL -> Halaman dashboard memuat ulang visualisasi tabungan secara asinkron.
3. **Pengaturan Anggaran Terkendali**: Pengguna memilih buku tabungan dan memasukkan rencana batas anggaran belanja -> Request dikirim ke `api/anggaran.php` -> Backend melakukan pengecekan saldo aktif dompet tabungan terkait -> Jika limit melebihi saldo tabungan, sistem membatalkan kueri dan mengirim pesan error -> Jika sukses, anggaran dicatat.
4. **Pencatatan Belanja Harian**: Pengguna mencatat transaksi pengeluaran dan mengunggah gambar struk -> Request dikirim ke `api/transaksi.php` -> Backend menyimpan gambar struk ke folder `/uploads/receipts/`, memotong nilai saldo saat ini pada buku tabungan terkait, mencatat transaksi belanja, dan menuliskan audit log ke `tb_system_logs` -> Tampilan grafikanalisis di dashboard otomatis diperbarui.

---

## 3.3 Class Diagram
Struktur pemrograman logika berorientasi objek sederhana yang direpresentasikan dalam modul backend aplikasi ini digambarkan melalui daftar kelas konseptual berikut:
* **Class User**:
  * Atribut: `id` bertipe integer, `name` bertipe string, `email` bertipe string, `password` bertipe string, `role` bertipe enum.
  * Metode: `login` dengan parameter email dan password, `register` dengan parameter name, email, password, dan role, `logout`, `deleteAccount`.
* **Class BukuTabungan**:
  * Atribut: `id` bertipe integer, `userId` bertipe integer, `namaBuku` bertipe string, `saldoAwal` bertipe decimal, `saldoSaatIni` bertipe decimal.
  * Metode: `create` dengan parameter namaBuku dan saldoAwal, `readByUserId` dengan parameter userId, `updateSaldo` dengan parameter amount.
* **Class Anggaran**:
  * Atribut: `id` bertipe integer, `bukuTabunganId` bertipe integer, `namaAnggaran` bertipe string, `batasLimit` bertipe decimal.
  * Metode: `create` dengan parameter bukuTabunganId, namaAnggaran, dan batasLimit, `delete` dengan parameter id, `validateAgainstBalance` dengan parameter limit.
* **Class Transaksi**:
  * Atribut: `id` bertipe integer, `userId` bertipe integer, `bukuTabunganId` bertipe integer, `anggaranId` bertipe integer, `kategoriId` bertipe integer, `tanggal` bertipe date, `keterangan` bertipe string, `nominal` bertipe decimal, `prioritas` bertipe enum, `bukti` bertipe string, `method` bertipe enum.
  * Metode: `create`, `delete` dengan parameter id, `readAllByUserId` dengan parameter userId.
* **Class Kategori**:
  * Atribut: `id` bertipe integer, `userId` bertipe integer, `namaKategori` bertipe string, `jenis` bertipe enum.
  * Metode: `create` dengan parameter namaKategori dan jenis, `delete` dengan parameter id, `read`.

---

## 3.4 Entity Relationship Diagram atau ERD
Struktur fisik relasi tabel pada basis data `db_sisajejakuang` digambarkan secara vertikal dengan detail spesifikasi Graphviz di bawah ini:

```dot
digraph ERD {
    graph [pad="0.5", nodesep="0.5", ranksep="1", rankdir=TB];
    node [shape=plain, fontname="Helvetica"];
    
    tb_user [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#855CF8" align="center"><font color="white"><b>tb_user</b></font></td></tr>
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">name : VARCHAR</td></tr>
            <tr><td align="left">email UK : VARCHAR</td></tr>
            <tr><td align="left">password : VARCHAR</td></tr>
            <tr><td align="left">role : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_buku_tabungan [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#3B82F6" align="center"><font color="white"><b>tb_buku_tabungan</b></font></td></tr>
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">user_id FK : INT</td></tr>
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
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">buku_tabungan_id FK : INT</td></tr>
            <tr><td align="left">nama_anggaran : VARCHAR</td></tr>
            <tr><td align="left">batas_limit : DECIMAL</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_kategori [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#F59E0B" align="center"><font color="white"><b>tb_kategori</b></font></td></tr>
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">user_id FK nullable : INT</td></tr>
            <tr><td align="left">nama_kategori : VARCHAR</td></tr>
            <tr><td align="left">jenis_kategori : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_transaksi [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#EF4444" align="center"><font color="white"><b>tb_transaksi</b></font></td></tr>
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">user_id FK : INT</td></tr>
            <tr><td align="left">buku_tabungan_id FK : INT</td></tr>
            <tr><td align="left">anggaran_id FK nullable : INT</td></tr>
            <tr><td align="left">kategori_id FK : INT</td></tr>
            <tr><td align="left">tanggal_transaksi : DATE</td></tr>
            <tr><td align="left">keterangan : VARCHAR</td></tr>
            <tr><td align="left">nominal : DECIMAL</td></tr>
            <tr><td align="left">prioritas : ENUM</td></tr>
            <tr><td align="left">bukti_pengeluaran nullable : VARCHAR</td></tr>
            <tr><td align="left">input_method : ENUM</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
            <tr><td align="left">updated_at : DATETIME</td></tr>
        </table>
    >];
    
    tb_system_logs [label=<
        <table border="0" cellborder="1" cellspacing="0">
            <tr><td bgcolor="#6B7280" align="center"><font color="white"><b>tb_system_logs</b></font></td></tr>
            <tr><td align="left">id PK : INT</td></tr>
            <tr><td align="left">actor : VARCHAR</td></tr>
            <tr><td align="left">action : VARCHAR</td></tr>
            <tr><td align="left">status : VARCHAR</td></tr>
            <tr><td align="left">created_at : DATETIME</td></tr>
        </table>
    >];

    # Relasi / Kardinalitas
    tb_user -> tb_buku_tabungan [label="1 : N memiliki", arrowhead="crow", arrowtail="none", dir="both"];
    tb_user -> tb_kategori [label="1 : N membuat", arrowhead="crow", arrowtail="none", dir="both"];
    tb_user -> tb_transaksi [label="1 : N memiliki", arrowhead="crow", arrowtail="none", dir="both"];
    tb_buku_tabungan -> tb_anggaran [label="1 : N memiliki", arrowhead="crow", arrowtail="none", dir="both"];
    tb_buku_tabungan -> tb_transaksi [label="1 : N mencatat", arrowhead="crow", arrowtail="none", dir="both"];
    tb_anggaran -> tb_transaksi [label="1 : N mengalokasikan", arrowhead="crow", arrowtail="none", dir="both"];
    tb_kategori -> tb_transaksi [label="1 : N mengelompokkan", arrowhead="crow", arrowtail="none", dir="both"];
}
```
