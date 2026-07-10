# Panduan Pengguna (User Manual) - SisaJejakUang

Dokumen ini berisi petunjuk operasional lengkap penggunaan fitur-fitur aplikasi **SisaJejakUang** bagi pengguna biasa (User) maupun administrator (Admin).

---

## 1. Antarmuka Sesi Masuk (Login & Register)

Saat pertama kali membuka aplikasi, Anda akan dihadapkan pada kartu login.
* **Masuk (Login)**: Masukkan email dan kata sandi Anda, lalu klik **Masuk Aplikasi**.
* **Daftar (Register)**: Klik tab **Daftar** untuk membuat akun baru. Masukkan Nama Lengkap, Email, Password, serta pilih Peran (*Role*) akun Anda (Pengguna Biasa atau Administrator), kemudian klik **Daftar Sekarang**.

---

## 2. Operasional Mode Pengguna (User Mode)

Jika Anda masuk dengan akun ber-peran `user`, Anda akan diarahkan ke antarmuka pengguna biasa yang memiliki 4 tab navigasi utama:

### A. Dashboard Pengguna
* **Skor Kesehatan Finansial (*Financial Health Score*)**: Nilai kesehatan keuangan Anda yang dinilai secara dinamis (skala 0 - 100). Skor dihitung berdasarkan proporsi sisa saldo, kepatuhan batas limit anggaran, serta prioritas pengeluaran.
* **Distribusi Needs vs Wants**: Kartu grafik yang membandingkan persentase dan nilai nominal total pengeluaran untuk **Kebutuhan** (*Needs*) vs **Keinginan** (*Wants*).
* **Metrik Finansial**: Ringkasan total saldo tersedia, pengeluaran bulan ini, dan akumulasi limit anggaran.
* **Aktivitas Terakhir**: Daftar 5 transaksi terbaru. Anda dapat mengklik judul kolom nominal atau waktu untuk mengurutkan daftar.

### B. Tabungan & Anggaran
* **Buat Buku Tabungan**: Klik tombol **Buat Buku Tabungan** di kanan atas untuk membuat wadah rekening baru. Masukkan nama buku tabungan (misal: "Dompet Tunai") dan nominal saldo awal.
* **Manajemen Anggaran**:
  * Klik tombol dropdown pada kartu buku tabungan yang bersangkutan untuk melihat detail anggaran spesifik.
  * Klik **Tambah Anggaran Baru** untuk membuat limit pos pengeluaran.
  * **Aturan Pembatasan**: Nominal limit anggaran tidak boleh melebihi saldo aktif saat ini pada buku tabungan. Jika melebihinya, sistem akan langsung menolak input Anda.

### C. Transaksi & Kategori
* **Pencatatan Transaksi**: Klik tombol **Catat Transaksi** untuk membuka modal input transaksi.
  * Pilih Buku Tabungan sumber dana.
  * Pilih Target Anggaran terkait (atau pilih "Bebas/Tanpa Anggaran" jika di luar budget terencana).
  * Pilih Kategori dan masukkan tanggal, nominal, keterangan, serta sifat prioritas (*Kebutuhan/Keinginan*).
  * Anda juga dapat mengunggah foto struk belanja sebagai bukti pengeluaran.
* **Metode Input OCR (Scan)**: Anda dapat mencatat transaksi dengan melakukan simulasi pembacaan struk belanja digital (*OCR*). Sistem akan otomatis mengisi form berdasarkan data hasil pembacaan.
* **Kategori Kustom**: Tuliskan nama kategori baru di kolom "Kategori Kustom Saya", lalu klik tombol **+** untuk menambahkannya ke pilihan kategori transaksi Anda secara dinamis.

### D. Pengaturan Akun
* Menampilkan informasi profil aktif Anda (Nama, Email, dan Peran).
* **Tombol Keluar (Logout)**: Menghapus sesi aktif Anda dengan aman dan mengembalikan Anda ke layar login.
* **Tombol Hapus Akun Permanen**: Menghapus seluruh data pribadi Anda secara permanen dari server. Tindakan ini memerlukan konfirmasi ganda karena bersifat destruktif dan tidak dapat dibatalkan.

---

## 3. Operasional Mode Administrator (Admin Mode)

Jika Anda masuk dengan akun ber-peran `admin`, Anda akan mendapatkan akses ke panel kontrol admin di sebelah kiri layar (Sidebar Admin) dan dapat beralih ke Mode User kapan saja menggunakan toggle di header.

### A. Dashboard Global
* Menyajikan ringkasan metrik seluruh ekosistem: total transaksi hari ini, total pengguna aktif, akumulasi volume uang beredar di sistem, dan rerata skor kesehatan finansial ekosistem.
* **Rasio Kepatuhan Anggaran**: Menampilkan metrik efisiensi pengelolaan anggaran para pengguna secara global.
* **Tingkat Adopsi OCR**: Metrik pelacak penggunaan fitur entri scan struk belanja untuk melihat efisiensi input digital.

### B. Master Kategori
* Menampilkan daftar seluruh kategori master global yang tersedia untuk semua pengguna.
* Administrator dapat menambahkan kategori master global baru atau menghapus kategori master yang tidak relevan lagi.

### C. Audit Pengguna
* Menampilkan daftar audit pengguna terdaftar dalam sistem.
* Menyajikan statistik jumlah buku tabungan yang dimiliki, rata-rata skor kesehatan keuangan individual, dan total saldo aktif milik tiap pengguna.

### D. Audit Tabel & SQL (Ekspor Database)
* Menyajikan visualisasi skema isi tabel secara langsung dari server.
* **Unduh File SQL**: Klik tombol ini untuk mengekspor database secara instan dalam berkas `.sql` mentah untuk dicadangkan (*backup*) atau dimigrasikan.

### E. Panduan Admin
* Berisi panduan operasional teknis, regulasi reguler, dan langkah pemecahan masalah operasional database sistem SisaJejakUang bagi admin.

### F. Pengaturan Admin
* Serupa dengan menu pengaturan user, bagian ini memuat detail akun administrator dan tombol untuk keluar (logout) atau menghapus akun administrator aktif dari sistem.
