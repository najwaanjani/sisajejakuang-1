# Dokumentasi API Endpoints - SisaJejakUang

Dokumen ini menjelaskan spesifikasi antarmuka API backend PHP pada proyek **SisaJejakUang** yang melayani request AJAX dalam format JSON.

---

## Aturan Komunikasi API

* **Content-Type**: Semua request dan response API menggunakan tipe data JSON (`application/json`), kecuali beberapa endpoint yang menerima parameter default query string (`GET` / `POST` form-data).
* **Autentikasi**: Endpoint dilindungi oleh session PHP (`session_start()`). Pengguna harus login terlebih dahulu agar dapat memproses data, kecuali pada endpoint registrasi dan login.

---

## 1. API Autentikasi (`api/auth.php`)

Mengelola pendaftaran akun, sesi masuk, keluar, dan penghapusan data akun pengguna.

### A. Registrasi Akun Baru
* **Metode**: `POST`
* **Query Parameter**: `action=register`
* **Request Body (JSON)**:
  ```json
  {
    "name": "Nama Pengguna",
    "email": "user@example.com",
    "password": "password123",
    "role": "user"
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Pendaftaran berhasil. Silakan login."
  }
  ```

### B. Login (Masuk Sesi)
* **Metode**: `POST`
* **Query Parameter**: `action=login`
* **Request Body (JSON)**:
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Login berhasil",
    "user": {
      "id": 2,
      "name": "Nama Pengguna",
      "email": "user@example.com",
      "role": "user"
    }
  }
  ```

### C. Logout (Keluar Sesi)
* **Metode**: `GET` / `POST`
* **Query Parameter**: `action=logout`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Logout berhasil."
  }
  ```

### D. Delete Account (Hapus Akun Permanen)
* **Metode**: `POST`
* **Query Parameter**: `action=delete_account`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Akun berhasil dihapus."
  }
  ```

---

## 2. API Buku Tabungan (`api/buku_tabungan.php`)

Mengelola pembuatan dan penampilan rekening tabungan personal.

### A. Mendapatkan Semua Buku Tabungan
* **Metode**: `GET`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": "1",
        "user_id": "2",
        "nama_buku": "Dompet Utama",
        "saldo_awal": "5000000.00",
        "saldo_saat_ini": "4500000.00",
        "created_at": "2026-06-08 00:00:00",
        "updated_at": "2026-06-24 10:00:00",
        "budgets": [
          {
            "id": "1",
            "buku_tabungan_id": "1",
            "nama_anggaran": "Makan Harian",
            "batas_limit": "1000000.00",
            "spent": 175000
          }
        ]
      }
    ]
  }
  ```

### B. Membuat Buku Tabungan Baru
* **Metode**: `POST`
* **Request Body (JSON)**:
  ```json
  {
    "nama_buku": "Gopay",
    "saldo_awal": 200000
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Buku tabungan berhasil ditambahkan."
  }
  ```

---

## 3. API Anggaran (`api/anggaran.php`)

Mengelola pembuatan pos anggaran per tabungan dengan validasi batas saldo.

### A. Membuat Anggaran Baru
* **Metode**: `POST`
* **Query Parameter**: `action=create`
* **Request Body (JSON)**:
  ```json
  {
    "buku_tabungan_id": 1,
    "nama_anggaran": "Jajan Kopi",
    "batas_limit": 150000
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Anggaran berhasil ditambahkan."
  }
  ```
* **Response Gagal (Saldo Tabungan Tidak Mencukupi) (JSON)**:
  ```json
  {
    "status": "error",
    "message": "Gagal: Batas anggaran (Rp 150.000) tidak boleh melebihi saldo buku tabungan saat ini (Rp 50.000)."
  }
  ```

### B. Menghapus Anggaran
* **Metode**: `POST` / `DELETE`
* **Query Parameter**: `action=delete`
* **Request Body (JSON)**:
  ```json
  {
    "id": 3
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Anggaran berhasil dihapus."
  }
  ```

---

## 4. API Transaksi (`api/transaksi.php`)

Mengelola input transaksi manual/OCR dan lampiran foto struk pengeluaran.

### A. Mendapatkan Riwayat Transaksi
* **Metode**: `GET`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": "1",
        "buku_tabungan_id": "1",
        "nama_buku": "Dompet Utama",
        "anggaran_id": "3",
        "nama_anggaran": "Hangout Akhir Pekan",
        "kategori_id": "9",
        "nama_kategori": "Kopi & Nongkrong",
        "tanggal_transaksi": "2026-06-08",
        "keterangan": "Kopi Susu Senja",
        "nominal": "35000.00",
        "prioritas": "Keinginan",
        "bukti_pengeluaran": null,
        "input_method": "manual"
      }
    ]
  }
  ```

### B. Membuat Transaksi Baru
* **Metode**: `POST`
* **Query Parameter**: `action=create`
* **Request Body (JSON)**:
  ```json
  {
    "buku_tabungan_id": 1,
    "anggaran_id": 3,
    "kategori_id": 9,
    "tanggal_transaksi": "2026-06-24",
    "keterangan": "Beli Kopi Sore",
    "nominal": 35000,
    "prioritas": "Keinginan",
    "bukti_pengeluaran_base64": "data:image/jpeg;base64,...",
    "input_method": "manual"
  }
  ```
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "message": "Transaksi berhasil disimpan."
  }
  ```

---

## 5. API Kategori (`api/kategori.php`)

Mengelola kategori master global (hanya dikelola oleh admin) dan kategori kustom (dikelola oleh user).

### A. Mendapatkan Daftar Kategori
* **Metode**: `GET`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": "1",
        "user_id": null,
        "nama_kategori": "Makanan & Minuman",
        "jenis_kategori": "master"
      },
      {
        "id": "9",
        "user_id": "2",
        "nama_kategori": "Skincare",
        "jenis_kategori": "kustom"
      }
    ]
  }
  ```

---

## 6. API Admin (`api/admin.php`)

Menyediakan log aktivitas sistem global bagi pengguna dengan peran administrator.

### A. Mendapatkan Log Aktivitas Sistem
* **Metode**: `GET`
* **Query Parameter**: `action=get_logs`
* **Response Sukses (JSON)**:
  ```json
  {
    "status": "success",
    "data": [
      {
        "id": "1",
        "actor": "najwa@sisajejakuang.com",
        "action": "Login berhasil",
        "status": "success",
        "created_at": "2026-06-24 10:05:00"
      }
    ]
  }
  ```
