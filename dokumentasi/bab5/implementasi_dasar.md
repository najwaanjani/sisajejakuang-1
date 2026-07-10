# BAB 5: IMPLEMENTASI DASAR

## 5.1 Tools dan Teknologi
Pengembangan dan deployment aplikasi **SisaJejakUang** menggunakan tools dan teknologi berikut:
1. **Bahasa Pemrograman**:
   * PHP 8.x yaitu backend: Pemrosesan API endpoint dinamis, kontrol sesi, hashing enkripsi, dan komunikasi database.
   * JavaScript ES6+ yaitu frontend: Penanganan interaksi UI dinamis, pengiriman request asinkron Fetch API, modifikasi DOM, dan visualisasi bagan.
2. **Styling & Ikon**: Tailwind CSS yang dimuat menggunakan Content Delivery Network dan Lucide Icons.
3. **Database Management**: MySQL RDBMS dan phpMyAdmin untuk administrasi skema database visual.
4. **Local Web Server**: Apache Server yang dibundel di dalam aplikasi **XAMPP Control Panel**.
5. **Driver Database**: PHP Data Objects atau PHP Data Objects menggunakan parameter binding praterbaca yaitu prepared statements untuk mencegah celah keamanan SQL injection.

---

## 5.2 Struktur Folder
Struktur direktori dan file utama dalam folder kerja server lokal adalah sebagai berikut:
* **`/config/db.php`**: File konfigurasi koneksi database menggunakan driver PDO PHP.
* **`/database/db_sisajejakuang.sql`**: Berkas SQL mentah berisi DDL struktur tabel database dan data seeds awal untuk pengujian.
* **`/api/`**:
  * `auth.php`: Penangan AJAX login, pendaftaran, keluar sesi, dan penghapusan akun permanen.
  * `buku_tabungan.php`: CRUD dompet tabungan.
  * `anggaran.php`: CRUD pos anggaran terencana dengan pengaman validasi batas limit.
  * `transaksi.php`: CRUD pencatatan jurnal transaksi masuk/keluar dan unggah berkas struk.
  * `kategori.php`: CRUD klasifikasi kategori master global dan kategori kustom pribadi.
  * `dashboard.php`: Penyedia data agregat analisis kesehatan keuangan untuk dashboard.
  * `admin.php`: Penyedia data log audit sistem global dan daftar audit statistik pengguna untuk administrator.
* **`/assets/`**: Folder penyimpan seluruh aset statis berupa CSS, Fonts, Images, dan JS untuk menunjang antarmuka halaman pendarat.
* **`/uploads/receipts/`**: Tempat penyimpanan file fisik gambar struk belanja yang diunggah pengguna.
* **`/dokumentasi/`**: Folder penyimpanan kumpulan berkas dokumentasi proyek yang berekstensi md.
* **`/index.php`**: Berkas halaman pendarat utama yang memperkenalkan sistem dan menyediakan tautan navigasi ke halaman masuk atau daftar.
* **`/app.php`**: Berkas aplikasi utama yaitu Single Page Application yang memuat seluruh dasbor interaktif bagi pengguna biasa maupun administrator.

---

## 5.3 Petunjuk Penggunaan Website
Berikut adalah panduan langkah demi langkah bagi pengguna untuk mengoperasikan sistem dari tahap masuk hingga melakukan pencatatan transaksi:
1. **Mengakses Halaman Utama**: Buka alamat website di browser Anda untuk masuk ke halaman pendarat utama. Di bagian pojok kanan atas, klik tombol Masuk untuk berpindah ke halaman autentikasi.
2. **Melakukan Autentikasi**:
   * **Masuk**: Masukkan alamat email dan kata sandi Anda yang terdaftar, lalu klik tombol Masuk Aplikasi.
   * **Daftar Akun Baru**: Jika belum memiliki akun, klik tombol Daftar di sebelah kanan tombol Masuk pada tab menu formulir. Isi nama lengkap, alamat email, kata sandi, tentukan peran Anda sebagai user biasa atau administrator, lalu klik tombol Daftar Sekarang.
3. **Membuat Buku Tabungan**:
   * Buka menu Buku Tabungan di dashboard Anda.
   * Klik tombol Tambah Buku Tabungan baru yang tersedia.
   * Masukkan nama tabungan seperti Rekening Bank atau Dompet Tunai, isi nominal saldo awal Anda, lalu klik tombol Simpan.
4. **Menetapkan Rencana Anggaran Belanja**:
   * Pada menu Buku Tabungan, klik ikon dropdown pada kartu tabungan yang telah dibuat untuk menampilkan detailnya.
   * Klik tombol Tambah Anggaran.
   * Isi nama pos anggaran seperti Anggaran Makanan atau Anggaran Transportasi, masukkan batas nominal anggaran belanja Anda, lalu klik tombol Simpan. Perlu diperhatikan bahwa batas nominal anggaran belanja dilarang melebihi jumlah saldo aktif saat ini pada buku tabungan terkait.
5. **Mencatat Transaksi Keuangan**:
   * Buka menu Transaksi dari menu navigasi dashboard.
   * Klik tombol Catat Transaksi Baru untuk membuka formulir.
   * Tentukan jenis transaksi apakah pemasukan atau pengeluaran.
   * Pilih Buku Tabungan yang bersangkutan.
   * Pilih pos Anggaran terkait jika transaksi tersebut merupakan pengeluaran.
   * Tentukan Kategori transaksi, isi nominal uang, tanggal, keterangan transaksi, tentukan skala prioritas apakah kebutuhan atau keinginan, serta lampirkan foto struk belanja jika ada. Klik tombol Simpan Transaksi.
   * **Menggunakan Fitur Simulasi Scan OCR**: Sebagai alternatif cepat, Anda dapat menggunakan tombol Simulasi OCR Scan. Unggah gambar struk belanja Anda, dan sistem akan mendeteksi isi nominal belanja serta keterangan transaksi secara otomatis tanpa perlu mengetik manual.
6. **Mengevaluasi Kesehatan Finansial**:
   * Kembali ke menu Dashboard untuk memantau skor kesehatan finansial Anda secara langsung, memantau persentase perbandingan kebutuhan versus keinginan, dan melihat riwayat mutasi transaksi keuangan Anda yang terperinci.
