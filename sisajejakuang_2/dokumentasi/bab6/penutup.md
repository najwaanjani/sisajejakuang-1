# BAB 6: PENUTUP

## 6.1 Kesimpulan
Pengembangan aplikasi manajemen keuangan **SisaJejakUang** telah berhasil diselesaikan dan menghasilkan sistem yang fungsional, kaya akan fitur, serta memberikan manfaat nyata bagi pengelolaan keuangan pribadi. Berdasarkan hasil evaluasi terhadap sistem, dapat ditarik beberapa kesimpulan utama mengenai aspek aplikasi sebagai berikut:

1. **Fungsionalitas Sistem yang Efisien**:
   * Sistem beroperasi sebagai aplikasi satu halaman yang responsif, memungkinkan seluruh proses navigasi, pembaruan saldo buku tabungan, dan kalkulasi grafik keuangan terjadi secara instan tanpa memerlukan pemuatan ulang halaman browser.
   * Hubungan antar-tabel dalam database dirancang secara aman dengan menerapkan integritas penghapusan data secara berantai. Ketika pengguna memilih untuk menghapus akun, sistem secara otomatis membersihkan seluruh data tabungan, anggaran, kategori, dan transaksi tanpa menyisakan data yatim.

2. **Fitur Pengendali Keuangan yang Kuat**:
   * **Buku Tabungan**: Menyediakan kemampuan untuk membagi dana ke dalam beberapa dompet virtual seperti rekening bank atau uang tunai demi pencatatan saldo yang lebih teratur.
   * **Manajemen Anggaran Ketat**: Menerapkan validasi keamanan di mana batas nominal anggaran dilarang melebihi sisa saldo tabungan aktif, berfungsi sebagai pengaman utama untuk mencegah alokasi anggaran belanja yang fiktif atau berlebihan.
   * **Pencatatan Transaksi Dinamis**: Memungkinkan pencatatan pemasukan dan pengeluaran secara rinci, dilengkapi dengan prioritas kepentingan serta opsi simulasi pembacaan struk belanja otomatis untuk mempercepat entri data pengeluaran.

3. **Manfaat Nyata bagi Pengguna**:
   * **Skor Kesehatan Finansial**: Membantu pengguna memantau kesehatan dompet mereka melalui perhitungan skor numerik dinamis yang memberikan umpan balik langsung atas pola belanja mereka.
   * **Analisis Kebutuhan versus Keinginan**: Membiasakan pengguna untuk lebih bijak dalam membelanjakan uang dengan menyajikan persentase perbandingan antara belanja pokok yang bersifat wajib dan belanja tambahan yang bersifat konsumtif.
   * **Perlindungan Privasi Mandiri**: Memberikan otoritas penuh kepada pengguna untuk menghapus akun mereka secara permanen jika sudah tidak ingin menggunakan sistem lagi.

---

## 6.2 Saran Pengembangan
Meskipun aplikasi saat ini sudah berfungsi dengan baik untuk memenuhi kebutuhan dasar pencatatan keuangan pribadi dan audit admin, terdapat beberapa area yang direkomendasikan untuk pengembangan lanjutan di masa mendatang:
1. **Peningkatan Fitur OCR yaitu Optical Character Recognition Asli**:
   * *Saran*: Mengganti modul OCR simulasi dengan mengintegrasikan pustaka OCR riil, seperti menggunakan Tesseract.js di sisi frontend atau Cloud Vision API di sisi backend, agar aplikasi dapat memindai struk belanja asli secara langsung dan mengekstrak data nominal belanja secara otomatis.
2. **Sistem Notifikasi Finansial Aktif**:
   * *Saran*: Mengintegrasikan sistem dengan bot API Telegram atau WhatsApp Business API untuk memberikan pesan peringatan otomatis secara instan kepada pengguna ketika saldo tabungan mereka kritis atau pengeluaran harian melampaui limit anggaran belanja.
3. **Ekspor Laporan Keuangan Terenkripsi**:
   * *Saran*: Menyediakan modul ekspor data transaksi ke dalam berkas laporan berformat PDF terenkripsi password atau spreadsheet Excel yang berekstensi xlsx untuk memudahkan dokumentasi eksternal atau pelaporan pajak mandiri.
4. **Keamanan Akun Ganda atau Two-Factor Authentication**:
   * *Saran*: Menerapkan otentikasi dua faktor menggunakan kode verifikasi sekali pakai yaitu One-Time Password yang dikirim ke nomor telepon pengguna atau aplikasi authenticator seperti Google Authenticator saat login.
