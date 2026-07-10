# BAB 2: DESKRIPSI SISTEM

## 2.1 Gambaran Umum Aplikasi
**SisaJejakUang** dirancang sebagai sistem pencatatan keuangan dinamis satu halaman yaitu Single Page Application. Aplikasi ini memberikan antarmuka interaktif yang menghubungkan pengguna dengan data keuangan mereka melalui internet. 

Alur kerja aplikasi dimulai dari gerbang autentikasi di mana pengguna terdaftar dapat masuk menggunakan kredensial berupa email dan kata sandi yang aman. Setelah masuk ke sistem, halaman utama akan langsung merender dashboard yang berisi akumulasi keuangan pengguna. Pengguna dapat melacak total uang mereka di berbagai wadah tabungan yang dikenal sebagai Buku Tabungan. Untuk mengendalikan sifat boros, pengguna dapat menetapkan target pengeluaran terencana yang disebut Anggaran pada dompet-dompet tersebut. Setiap transaksi pengeluaran yang dicatat oleh pengguna akan langsung memotong saldo buku tabungan secara otomatis dan memperbarui bar perkembangan konsumsi anggaran, serta menghitung ulang skor kesehatan finansial pengguna secara dinamis.

---

## 2.2 Stakeholder dan User
Sistem ini memfasilitasi dua peran pengguna dengan hak akses yang terisolasi secara ketat demi menjaga privasi dan keamanan data:

### 2.2.1 Pengguna Biasa atau Mode Pengguna
* **Hak Akses & Otoritas**:
  * Mengisi profil pengguna dan mengelola keamanan sesi masuk.
  * Membuat, memperbarui, dan menghapus rekening/dompet keuangan pribadi yaitu Buku Tabungan.
  * Membuat dan menghapus pos anggaran belanja khusus yaitu Anggaran yang terikat pada buku tabungan.
  * Mencatat transaksi debet/kredit berupa pemasukan atau pengeluaran secara manual atau menggunakan fitur simulasi pindai struk yang dinamakan Optical Character Recognition.
  * Menambahkan dan menghapus kategori transaksi pribadi yang disebut Kategori Kustom.
  * Menghapus seluruh informasi data akun miliknya secara mandiri dari sistem yang dikenal sebagai Delete Account.

### 2.2.2 Administrator atau Mode Administrator
* **Hak Akses & Otoritas**:
  * Memantau dashboard analisis kinerja ekosistem secara global, seperti memantau rasio kepatuhan anggaran seluruh pengguna serta tingkat kenyamanan penggunaan fitur scan Optical Character Recognition secara global.
  * Mengelola daftar kategori transaksi default yang tersedia bagi seluruh pengguna yang disebut Kategori Master.
  * Melihat ringkasan statistik keuangan dan kesehatan keuangan masing-masing pengguna aktif tanpa melanggar privasi transaksi individu yaitu halaman Audit Pengguna.
  * Memantau riwayat log audit aktivitas penting sistem yang dikenal sebagai System Logs.
  * Mengunduh berkas fisik cadangan basis data SQL secara langsung.
  * Menghapus akun administrator yang sedang aktif dari sistem.

---

## 2.3 Kebutuhan Fungsional atau Kebutuhan Fungsional
Fitur-fitur utama yang wajib disediakan oleh sistem **SisaJejakUang** meliputi:
1. **Sistem Autentikasi Sesi**: Registrasi akun baru, login sesi, logout aman, dan pembersihan akun secara permanen dari basis data.
2. **Manajemen Buku Tabungan**: CRUD tabungan pengguna yang mendata saldo awal dan menghitung saldo aktif secara otomatis berdasarkan riwayat pemasukan atau pengeluaran.
3. **Manajemen Anggaran Terkendali**: CRUD anggaran belanja terencana dengan pengaman validasi yang mencegah batas limit melebihi sisa saldo tabungan saat ini.
4. **Pencatatan Jurnal Transaksi**: CRUD transaksi keuangan yang mendata nominal, tanggal, keterangan, sifat prioritas yaitu kebutuhan atau keinginan, lampiran gambar struk, dan penanda metode entri yaitu manual atau Optical Character Recognition.
5. **Pengelompokan Kategori**: CRUD kategori transaksi dengan pembagian tipe kategori bawaan global yaitu master dan kategori buatan pengguna yaitu kustom.
6. **Dashboard Analitik Dinamis**: Visualisasi perhitungan matematis skor kesehatan keuangan, persentase rasio pengeluaran berdasarkan sifat prioritas yaitu perbandingan Kebutuhan dan Keinginan, dan grafik sisa batas anggaran belanja yang menipis yang tersisa kurang dari sepuluh persen.
7. **Pencatatan Audit Log**: Sistem mencatat setiap aktivitas modifikasi data keuangan penting ke dalam tabel audit log sebagai jejak digital yang transparan.

---

## 2.4 Kebutuhan Non-Fungsional atau Kebutuhan Non-Fungsional
* **Usability yang berfokus pada kemudahan penggunaan**: Desain antarmuka responsif yang dapat diakses dengan baik melalui perangkat desktop maupun mobile, didukung oleh transisi perpindahan halaman yang halus menggunakan JavaScript.
* **Performance yang berfokus pada kinerja sistem**: Respons server web lokal untuk request API backend rata-rata di bawah 100 milidetik, menjamin kelancaran entri data tanpa jeda loading yang lama.
* **Security yang berfokus pada aspek keamanan**:
  * Keamanan password menggunakan hashing searah yang kuat dengan algoritma bawaan PHP fungsi password hash menggunakan salt acak bawaan.
  * Pengamanan transaksi database menggunakan integritas kunci asing yaitu Foreign Key Constraints dengan opsi penghapusan berantai dengan opsi ON DELETE CASCADE sehingga saat akun dihapus, tidak menyisakan sampah data yatim atau data yatim pada basis data.
  * Sistem mendeteksi hak akses session PHP pada setiap request API untuk mencegah akses data ilegal yang merupakan proses authorization check.
