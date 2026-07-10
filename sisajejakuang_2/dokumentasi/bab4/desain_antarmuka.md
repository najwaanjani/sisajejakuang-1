# BAB 4: DESAIN ANTARMUKA

## 4.1 Konsep Desain
Konsep desain antarmuka aplikasi **SisaJejakUang** berpusat pada kegunaan tinggi yaitu tingkat kemudahan penggunaan yang tinggi dan kesederhanaan visual atau gaya minimalis. Kami mengadopsi prinsip desain **Glassmorphism** modern yang memberikan efek kedalaman visual menggunakan warna latar belakang netral abu-abu terang yang dikenal sebagai slate-50, kartu data dengan efek blur semi-transparan, serta tipografi **Plus Jakarta Sans** yang bersih dan modern.

Skema warna fungsional digunakan secara konsisten di seluruh aplikasi:
* **Indigo-600**: Warna aksen utama untuk elemen navigasi aktif, tombol konfirmasi utama, dan branding.
* **Emerald-600**: Menandakan informasi saldo positif, pemasukan, atau indikator keuangan aman.
* **Rose-600**: Digunakan untuk nominal pengeluaran, peringatan anggaran kritis yaitu di bawah sepuluh persen, serta tombol penghapusan akun yang sensitif.

---

## 4.2 Mockup / Wireframe
Tata letak antarmuka diatur secara dinamis untuk menyesuaikan ukuran layar perangkat pengguna atau tata letak responsif:
* **Halaman Autentikasi**: Terdiri dari satu kartu tengah yang ringkas dengan tab pengalih untuk memudahkan pengguna berganti antara form login dan register tanpa berpindah halaman.
* **Header Utama**: Selalu menempel di bagian atas layar. Berisi logo aplikasi, nama pengguna aktif, dan tombol pintas Logout.
* **Layout Dua Kolom pada tampilan desktop**:
  * **Sidebar yang terletak di sisi kiri**: Khusus untuk peran Admin untuk berpindah antar halaman audit dengan cepat.
  * **Konten Utama yang terletak di sisi kanan**: Area rendering tab interaktif di mana komponen visual dimuat secara asinkron.
* **Area Dashboard**: Terdiri dari tiga baris kartu data; baris pertama untuk skor kesehatan keuangan dan rasio prioritas belanja, baris kedua untuk metrik total keuangan yang meliputi jumlah saldo, total pengeluaran bulanan, dan total rencana anggaran, dan baris ketiga untuk tabel aktivitas transaksi terakhir.

---

## 4.3 Deskripsi Tampilan
1. **Halaman Autentikasi yaitu halaman Masuk dan Daftar**:
   * Form Login: Input email, password, dan tombol masuk aplikasi.
   * Form Register: Input nama lengkap, email, password, pilihan peran yaitu antara admin atau user, dan tombol daftar.
2. **Dashboard Keuangan Pribadi atau Mode Pengguna**:
   * Menampilkan kartu skor kesehatan keuangan dinamis dengan skala nilai nol hingga seratus lengkap dengan penjelasan kondisinya.
   * Kartu analisis *Needs vs Wants* yang membagi persentase pengeluaran wajib dan keinginan.
   * Kartu ringkasan total saldo, akumulasi pengeluaran bulanan, dan total budget terencana.
   * Tabel aktivitas transaksi terbaru dengan opsi pengurutan interaktif.
3. **Menu Tabungan & Anggaran**:
   * Memuat grid kartu buku tabungan yang memisahkan saldo di setiap dompet virtual.
   * Setiap kartu memiliki tombol dropdown accordion yang jika diklik akan membuka daftar target anggaran belanja di bawahnya, tombol tambah anggaran baru, serta tombol hapus anggaran.
4. **Menu Transaksi & Kategori**:
   * Formulir input pembuatan kategori kustom pribadi.
   * Jurnal pencatatan transaksi umum dengan tombol **Catat Transaksi** yang berfungsi untuk membuka formulir isian disertai dengan opsi untuk mengunggah foto struk belanja dan tombol **Simulasi OCR atau fitur pindai**.
   * Detail rincian jurnal umum transaksi keuangan.
5. **Dashboard Global atau Mode Administrator**:
   * Menampilkan metrik ekosistem seperti jumlah pengguna aktif, total volume uang beredar dalam sistem, rasio kepatuhan anggaran global, dan tingkat penggunaan scan OCR.
   * Panel manajemen Kategori Master bawaan sistem.
   * Tabel Audit Pengguna yang melacak kepemilikan rekening dan rerata kesehatan finansial setiap anggota.
   * Halaman Audit Database SQL fisik dengan tombol **Unduh File SQL** untuk mencadangkan database.
6. **Halaman Pengaturan Akun**:
   * Menampilkan informasi nama lengkap, email, dan peran akun.
   * Menyediakan tombol Logout untuk mengakhiri sesi.
   * Menyediakan tombol Hapus Akun Permanen untuk menghapus data pribadi dari MySQL secara berantai.
