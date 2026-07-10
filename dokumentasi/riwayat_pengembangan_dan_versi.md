# Riwayat Pengembangan & Versi - SisaJejakUang

Dokumen ini mencatat kronologi pengembangan, log perubahan (*changelog*), rilis versi, dan rencana pengembangan masa depan untuk aplikasi **SisaJejakUang**.

---

## 1. Kronologi Rilis Versi

### Versi 1.0.0 (Tahap Mockup Statis)
* **Status**: Selesai
* **Deskripsi**: Aplikasi dikembangkan sebagai purwarupa (*mockup*) statis menggunakan satu file `index.html`. Penyimpanan data simulasi sepenuhnya mengandalkan `localStorage` pada browser klien. Fitur pembacaan struk (OCR) disimulasikan menggunakan data lokal statis.

### Versi 2.0.0 (Tahap Migrasi ke Native PHP & MySQL)
* **Status**: Selesai
* **Deskripsi**: Migrasi arsitektur dari mockup statis menjadi dinamis. Kode backend ditulis ulang menggunakan Native PHP murni tanpa framework, dan penyimpanan data dipindahkan ke RDBMS MySQL. Relasi antar tabel diatur menggunakan Foreign Key dan integritas cascading. Komunikasi data menggunakan AJAX Fetch API dengan format JSON.

### Versi 2.1.0 (Tahap Penguatan Validasi & Manajemen Sesi)
* **Status**: Aktif (Versi Saat Ini)
* **Deskripsi**: Rilis pembaruan keamanan, pembatasan anggaran, dan integrasi fitur manajemen akun pengguna/admin.

---

## 2. Detail Pembaruan Terbaru (Changelog v2.1.0)

Berikut adalah daftar perubahan spesifik yang baru saja diimplementasikan pada versi v2.1.0:

### A. Validasi Aturan Anggaran (Budget Limits Constraint)
* **Deskripsi**: Mencegah pengguna menetapkan batas limit anggaran yang melebihi jumlah saldo pada buku tabungan induknya.
* **Perubahan Backend**: Mengubah penangan pada [api/anggaran.php](file:///c:/xampp/htdocs/sisajejakuang_2/api/anggaran.php). Query pembuatan anggaran diubah untuk memeriksa saldo saat ini (`saldo_saat_ini`) dari buku tabungan. Jika nilai input batas limit lebih besar, request dihentikan dengan pesan JSON error `400 Bad Request`.
* **Perubahan Frontend**: Menambahkan validasi *client-side* di file [index.php](file:///c:/xampp/htdocs/sisajejakuang_2/index.php) pada penangan form submit anggaran. Melakukan pencarian instan pada memori `accounts.find()` dan memblokir submit jika limit melebihi saldo saat ini, serta memicu peringatan berupa *toast notification*.

### B. Opsi Logout
* **Deskripsi**: Mengganti fitur "Reset Semua Data" (yang sebelumnya membingungkan dan menggunakan ikon refresh) menjadi tombol **Keluar (Logout)** yang eksplisit.
* **Perubahan Antarmuka**: 
  * Mengubah tombol navigasi pojok kanan atas di [index.php](file:///c:/xampp/htdocs/sisajejakuang_2/index.php) menjadi ikon `log-out` dengan keterangan tooltip "Keluar (Logout)".
  * Menambahkan tombol **Keluar (Logout)** di dalam halaman Pengaturan Akun (User & Admin Mode).
  * Menghubungkan tombol ke endpoint `api/auth.php?action=logout`.

### C. Opsi Hapus Akun Permanen (Self-Account Deletion)
* **Deskripsi**: Memberikan kebebasan bagi pengguna (User & Admin) untuk menghapus seluruh data mereka secara mandiri dari sistem.
* **Perubahan Backend**: Menambahkan aksi `delete_account` di dalam file [api/auth.php](file:///c:/xampp/htdocs/sisajejakuang_2/api/auth.php). Menjalankan query `DELETE FROM tb_user WHERE id = ?` yang secara otomatis memicu pembersihan (*cascade delete*) seluruh data tabungan, anggaran, kategori, dan transaksi milik pengguna dari database MySQL secara bersih.
* **Perubahan Antarmuka**: Menambahkan tombol **Hapus Akun Permanen** dengan icon `user-x` di dalam tab/halaman Pengaturan Akun (baik pada Mode User maupun Mode Admin). Dilengkapi perlindungan konfirmasi ganda (*double confirm modal*) sebelum memicu penghapusan permanen ke backend.

---

## 3. Rencana Pengembangan Selanjutnya (Roadmap)

Untuk pengembangan lebih lanjut, berikut adalah rekomendasi fitur selanjutnya:
1. **Sistem Keamanan Kata Sandi**: Fitur "Lupa Password" menggunakan pengiriman token reset lewat email atau verifikasi nomor OTP.
2. **Integrasi OCR Asli**: Mengganti modul OCR simulasi dengan pustaka OCR berbasis cloud (misalnya Google Cloud Vision API atau Tesseract.js) agar dapat membaca struk belanja asli secara *real-time*.
3. **Ekspor Laporan Bulanan**: Fitur unduh laporan keuangan bulanan terenkripsi dalam format PDF atau Excel (.xlsx) untuk mempermudah audit mandiri pengguna.
