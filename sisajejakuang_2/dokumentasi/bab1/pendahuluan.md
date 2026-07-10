# BAB 1: PENDAHULUAN

## 1.1 Latar Belakang
Di era modern yang didominasi oleh perkembangan teknologi finansial dan digitalisasi ekonomi, pola konsumsi masyarakat—khususnya kalangan mahasiswa dan pekerja muda—telah mengalami pergeseran yang sangat signifikan. Kemudahan transaksi nontunai melalui berbagai platform seperti dompet digital, layanan perbankan digital, hingga fasilitas pembiayaan instan telah menciptakan budaya belanja yang sangat dinamis namun juga impulsif. Proses transaksi yang semakin mulus sering kali mengaburkan kesadaran individu terhadap jumlah uang yang telah mereka belanjakan. Akibatnya, banyak orang menghadapi masalah finansial klasik: kehabisan dana sebelum akhir bulan tanpa mengetahui secara pasti ke mana uang tersebut mengalir.

Metode pencatatan keuangan konvensional, seperti mencatat di buku saku fisik atau memasukkan data secara manual ke dalam lembar kerja spreadsheet, kini dinilai kurang efektif dan melelahkan. Kelemahan utama metode manual ini adalah rendahnya tingkat kedisiplinan pengguna untuk secara konsisten mencatat setiap transaksi kecil, serta tidak adanya umpan balik analitik instan yang dapat membantu pengguna mengukur kesehatan finansial mereka secara langsung. Di sisi lain, aplikasi keuangan komersial yang beredar di pasar saat ini sering kali terlalu rumit, dipenuhi iklan yang mengganggu kenyamanan pengguna, atau mengabaikan perlindungan privasi data pribadi. 

Berdasarkan permasalahan nyata tersebut, dikembangkanlah aplikasi SisaJejakUang. Aplikasi ini hadir sebagai solusi manajemen keuangan mandiri berbasis web yang menawarkan antarmuka ramah pengguna, pencatatan instan, visualisasi analitik kesehatan keuangan, serta penegakan aturan bisnis yang ketat—seperti mencegah pembuatan pos anggaran belanja yang melebihi kapasitas saldo aktual dompet pengguna. Dengan adanya sistem terintegrasi ini, diharapkan pengguna dapat kembali memegang kendali penuh atas keputusan finansial mereka sehari-hari.

---

## 1.2 Nama Aplikasi dan Dasar Ide
Aplikasi ini diberi nama SisaJejakUang. Pemilihan nama ini didasari oleh tiga pilar filosofis penting:
1. Sisa: Mewakili fokus utama aplikasi untuk membantu pengguna mengamankan sisa saku atau pendapatan bersih mereka agar tidak habis secara percuma, melainkan dialokasikan ke pos-pos penting atau tabungan masa depan.
2. Jejak: Melambangkan sistem pencatatan jurnal keuangan yang rapi, transparan, dan dapat ditelusuri kembali kapan saja.
3. Uang: Merupakan objek utama yang dikelola dalam ekosistem aplikasi ini.

Dasar ide dari proyek SisaJejakUang berawal dari pengamatan langsung terhadap kehidupan mahasiswa yang tinggal di kost. Mahasiswa sering kali mengalami kesulitan mengelola kiriman uang saku bulanan dari orang tua. Banyak yang mengalami kesulitan finansial di tanggal tua akibat tidak adanya kontrol ketat terhadap pengeluaran konsumtif dibandingkan dengan pengeluaran wajib. Ide ini kemudian dikembangkan menjadi sebuah sistem web dinamis terpusat yang membagi uang saku ke dalam berbagai sub-dompet virtual dan anggaran belanja yang dibatasi secara sistematis.

---

## 1.3 Tujuan Pengembangan
Pengembangan aplikasi SisaJejakUang memiliki beberapa tujuan utama yang dibagi menjadi dua kategori:

### 1.3.1 Tujuan Umum
* Membantu masyarakat luas, terutama generasi muda dan mahasiswa, dalam meningkatkan literasi keuangan atau pemahaman finansial melalui pembiasaan pencatatan arus kas secara digital.
* Mendorong terwujudnya kebiasaan belanja yang bijak, hemat, dan terencana guna membangun stabilitas keuangan jangka panjang.

### 1.3.2 Tujuan Khusus
* Menyediakan platform web terpusat yang mampu mengelola banyak dompet keuangan pribadi yang dikenal sebagai Buku Tabungan secara mandiri dalam satu akun.
* Menerapkan validasi keamanan data dan aturan batas anggaran finansial yang dinamis untuk meminimalisasi risiko defisit atau pengeluaran berlebih.
* Menyajikan analisis ringkas perbandingan pengeluaran berdasarkan kategori prioritas **Kebutuhan** yaitu pengeluaran pokok dan **Keinginan** yaitu pengeluaran tambahan.
* Menyediakan fitur simulasi pemindaian struk belanja fisik berbasis teknologi pengenalan karakter optik atau Optical Character Recognition untuk mempermudah dan mempercepat entri data transaksi.
* Memberikan hak penuh atas privasi data pengguna dengan menyediakan fitur penghapusan seluruh riwayat akun secara mandiri dan permanen.

---

## 1.4 Ruang Lingkup
Untuk memastikan proses pengembangan berjalan terarah dan efisien, ruang lingkup aplikasi SisaJejakUang dibatasi pada aspek-aspek berikut:
1. Teknologi: Aplikasi dibangun menggunakan arsitektur Single Page Application atau aplikasi satu halaman sederhana dengan bahasa pemrograman Native PHP di sisi server yaitu backend, basis data MySQL yang dijalankan di XAMPP Control Panel, dan styling menggunakan Tailwind CSS serta manipulasi DOM menggunakan Vanilla JavaScript di sisi klien yaitu frontend.
2. Pengguna & Otorisasi: Aplikasi mendukung otorisasi akses menggunakan session PHP yang memisahkan hak akses antara Pengguna Biasa yaitu User untuk pengelolaan akun pribadi dan Administrator yaitu Admin untuk memantau ekosistem.
3. Fitur yang Dicakup:
   * CRUD Buku Tabungan pribadi dengan inisialisasi saldo awal.
   * CRUD Anggaran yang dibatasi agar tidak boleh melebihi saldo tabungan saat ini.
   * CRUD Catatan Transaksi pemasukan/pengeluaran disertai unggah file gambar struk belanja.
   * CRUD Kategori kustom mandiri.
   * Dashboard interaktif dengan grafik perbandingan pengeluaran kebutuhan dan keinginan serta metrik skor kesehatan keuangan.
   * Panel admin yang memuat System Audit Logs global dan fitur unduh backup SQL database.
4. Fitur di Luar Cakupan: Aplikasi ini tidak menyediakan integrasi langsung dengan API bank komersial asli, transaksi keuangan riil seperti transfer uang, atau pengiriman notifikasi via Short Message Service dan Electronic Mail pihak ketiga.
