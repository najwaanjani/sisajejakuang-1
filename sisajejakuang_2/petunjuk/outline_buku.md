# OUTLINE DAN PROMPT PENYUSUNAN LAPORAN AKHIR PROYEK 1
> **Sumber Konteks Aplikasi:** C:\xampp\htdocs\sisajejakuang_2

---

## BAB 1 PENDAHULUAN

### 1.1 Latar Belakang
**Prompt:**
```text
Tuliskan latar belakang untuk aplikasi berbasis web/mobile dari proyek 'sisajejakuang_2'. Jelaskan masalah nyata terkait pelacakan atau pengelolaan keuangan/sisa uang yang ingin diselesaikan, kondisi saat ini yang masih manual atau kurang efisien, serta alasan kuat mengapa sistem ini penting untuk dibuat.

1.2 Nama Aplikasi dan Dasar Ide
Prompt:
Jelaskan arti dan filosofi di balik nama aplikasi dari proyek 'sisajejakuang_2' (atau tentukan nama komersialnya jika ada). Uraikan dari mana dasar ide aplikasi ini berasal, misalnya dari masalah sehari-hari mahasiswa atau masyarakat dalam mengelola sisa anggaran belanja/uang saku.

1.3 Tujuan Pengembangan
Prompt:
Buatlah poin-poin tujuan pengembangan aplikasi 'sisajejakuang_2'. Pisahkan menjadi:
1. Tujuan Umum (dampak luas dari aplikasi).
2. Tujuan Khusus (fitur atau target spesifik yang ingin dicapai oleh sistem).

1.4 Ruang Lingkup
Prompt:
Batasi ruang lingkup pengembangan aplikasi 'sisajejakuang_2' agar fokus dan tidak terlalu luas. Sebutkan platform yang digunakan (misalnya: berbasis Web dengan PHP/XAMPP), batasan pengguna (single-user atau multi-user), serta fitur utama apa saja yang dicakup dan yang tidak dicakup.

BAB 2 DESKRIPSI SISTEM
2.1 Gambaran Umum Aplikasi
Prompt:
Berikan penjelasan ringkas dan komprehensif mengenai cara kerja aplikasi 'sisajejakuang_2'. Gambarkan alur bagaimana pengguna berinteraksi dengan sistem ini dari awal masuk hingga mendapatkan informasi sisa keuangan mereka.

2.2 Stakeholder dan User
Prompt:
Identifikasi siapa saja aktor atau user yang akan menggunakan aplikasi 'sisajejakuang_2'. Jelaskan hak akses masing-masing user secara spesifik (misalnya: Admin dapat mengelola kategori, User/Mahasiswa hanya dapat mencatat pengeluaran).

2.3 Kebutuhan Fungsional
Prompt:
Susun daftar kebutuhan fungsional (functional requirements) berupa fitur-fitur utama yang wajib ada di dalam sistem 'sisajejakuang_2' (contoh: Pencatatan saldo masuk, pencatatan pengeluaran harian, penentuan sisa uang otomatis, dan laporan grafik).

2.4 Kebutuhan Non-Fungsional
Prompt:
Tuliskan kebutuhan non-fungsional untuk aplikasi 'sisajejakuang_2'. Fokuskan pada aspek kesederhanaan penggunaan (usability), kecepatan respons sistem saat dijalankan di lokal (XAMPP), dan keamanan dasar (seperti enkripsi password atau session login).

BAB 3 PERANCANGAN SISTEM
3.1 Arsitektur Sistem
Prompt:
Gambarkan arsitektur sistem sederhana untuk proyek 'sisajejakuang_2'. Jelaskan bagaimana hubungan dan aliran data antara Frontend (antarmuka pengguna), Backend (logika PHP), dan Database (MySQL di XAMPP).

3.2 Workflow Sistem
Prompt:
Buatlah narasi penjelasan alur kerja (workflow) sistem dari awal hingga akhir berdasarkan struktur kode dan fungsionalitas di 'sisajejakuang_2'. Berikan slot/placeholder di atas narasi ini untuk penempatan diagram flowchart/activity diagram nantinya.

3.3 Class Diagram
Prompt:
Rancanglah struktur class sederhana yang merepresentasikan objek-objek di dalam sistem 'sisajejakuang_2' (misalnya Class User, Class Transaksi, Class Dompet/Kategori) beserta atribut dan metodenya untuk dijadikan visualisasi Class Diagram.

3.4 Entity Relationship Diagram (ERD)
Prompt:
Petakan struktur database MySQL dari proyek 'sisajejakuang_2'. Sebutkan tabel-tabel yang dibutuhkan (seperti tabel user, transaksi, kategori), atribut/kolomnya, primary key, serta relasi antar tabelnya (one-to-many atau lainnya).

BAB 4 DESAIN ANTARMUKA
4.1 Konsep Desain
Prompt:
Jelaskan konsep dan tujuan dari desain antarmuka aplikasi 'sisajejakuang_2'. Deskripsikan pemilihan tema warna atau layout yang menjamin kemudahan pengguna (user-friendly) dalam mencatat keuangan dengan cepat.

4.2 Mockup / Wireframe
Prompt:
Buat panduan visual atau deskripsi sketsa halaman (wireframe) untuk halaman utama aplikasi 'sisajejakuang_2'. Tentukan letak penempatan ringkasan sisa uang, tombol tambah transaksi, dan tabel riwayat pengeluaran.

4.3 Deskripsi Tampilan
Prompt:
Uraikan secara detail fungsi dari setiap halaman yang ada pada aplikasi 'sisajejakuang_2', mulai dari Halaman Login, Dashboard Utama, Halaman Form Input Transaksi, hingga Halaman Laporan Keuangan.

BAB 5 IMPLEMENTASI DASAR
5.1 Tools dan Teknologi
Prompt:
Sebutkan daftar teknologi, bahasa pemrograman, dan tools yang nyata digunakan dalam pengembangan proyek 'sisajejakuang_2' berdasarkan path lokal tersebut (misalnya: PHP asli/framework, HTML5, CSS3/Bootstrap, JavaScript, phpMyAdmin, dan web server XAMPP).

5.2 Struktur Folder
Prompt:
Petakan susunan folder dan file utama yang ada di dalam direktori `C:\xampp\htdocs\sisajejakuang_2`. Jelaskan secara singkat fungsi dari folder/file penting tersebut (misalnya folder `/config` untuk koneksi database, folder `/assets` untuk CSS/JS, dll).

5.3 Petunjuk Menjalankan Aplikasi
Prompt:
Tuliskan langkah-langkah berurutan dan mudah dipahami bagi pengguna awam untuk menjalankan aplikasi 'sisajejakuang_2' di komputer lokal. Mulai dari mengaktifkan Apache & MySQL di XAMPP Control Panel, melakukan import database `.sql`, hingga mengaksesnya via browser di `http://localhost/sisajejakuang_2`.

BAB 6 PENUTUP
6.1 Kesimpulan
Prompt:
Susun kesimpulan akhir dari pembuatan proyek aplikasi 'sisajejakuang_2'. Evaluasi apakah aplikasi ini berhasil menjawab masalah pelacakan sisa keuangan yang diangkat pada Bab 1 serta apakah semua target tujuan pengembangan telah terpenuhi.

6.2 Saran Pengembangan
Prompt:
Berikan beberapa ide konkret dan saran pengembangan lanjutan untuk aplikasi 'sisajejakuang_2' di masa mendatang, seperti integrasi dengan bot notifikasi (WhatsApp/Telegram), fitur multi-rekening, atau pembacaan struk belanja otomatis menggunakan OCR.