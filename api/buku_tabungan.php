<?php
// api/buku_tabungan.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Silakan login terlebih dahulu.']);
    exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// Helper untuk log audit
function writeLog($pdo, $actor, $action, $status = 'success') {
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_system_logs (actor, action, status) VALUES (?, ?, ?)");
        $stmt->execute([$actor, $action, $status]);
    } catch (Exception $e) {}
}

try {
    if ($method === 'GET') {
        // Ambil daftar Buku Tabungan beserta anggaran di dalamnya
        $stmt = $pdo->prepare("SELECT * FROM tb_buku_tabungan WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        $books = $stmt->fetchAll();

        $result = [];
        foreach ($books as $book) {
            // Ambil anggaran untuk buku tabungan ini
            $stmtB = $pdo->prepare("SELECT * FROM tb_anggaran WHERE buku_tabungan_id = ?");
            $stmtB->execute([$book['id']]);
            $budgets = $stmtB->fetchAll();
            
            // Hitung akumulasi terpakai untuk setiap anggaran
            foreach ($budgets as &$b) {
                $stmtSpent = $pdo->prepare("SELECT SUM(nominal) as spent FROM tb_transaksi WHERE buku_tabungan_id = ? AND anggaran_id = ?");
                $stmtSpent->execute([$book['id'], $b['id']]);
                $b['spent'] = (float) ($stmtSpent->fetch()['spent'] ?? 0);
            }

            $book['budgets'] = $budgets;
            $result[] = $book;
        }

        echo json_encode(['status' => 'success', 'data' => $result]);
        exit;
    }

    elseif ($method === 'POST') {
        // Buat Buku Tabungan baru
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $nama_buku = trim($input['nama_buku'] ?? '');
        $saldo_awal = (float) ($input['saldo_awal'] ?? 0);

        if (empty($nama_buku)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama buku tabungan tidak boleh kosong.']);
            exit;
        }

        if ($saldo_awal < 0) {
            echo json_encode(['status' => 'error', 'message' => 'Saldo awal tidak boleh negatif.']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO tb_buku_tabungan (user_id, nama_buku, saldo_awal, saldo_saat_ini) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $nama_buku, $saldo_awal, $saldo_awal]);
        $newBookId = $pdo->lastInsertId();

        writeLog($pdo, $_SESSION['email'], "Membuat Buku Tabungan: $nama_buku");

        echo json_encode([
            'status' => 'success',
            'message' => 'Buku Tabungan berhasil dibuat.',
            'data' => [
                'id' => $newBookId,
                'nama_buku' => $nama_buku,
                'saldo_awal' => $saldo_awal,
                'saldo_saat_ini' => $saldo_awal
            ]
        ]);
        exit;
    }

    elseif ($method === 'DELETE' || ($method === 'POST' && ($_GET['action'] ?? '') === 'delete')) {
        // Hapus Buku Tabungan
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST ?? $_GET;
        $id = (int) ($input['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID buku tabungan tidak valid.']);
            exit;
        }

        // Cek kepemilikan
        $stmt = $pdo->prepare("SELECT nama_buku FROM tb_buku_tabungan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        $book = $stmt->fetch();

        if (!$book) {
            echo json_encode(['status' => 'error', 'message' => 'Buku tabungan tidak ditemukan atau bukan milik Anda.']);
            exit;
        }

        // Hapus buku tabungan (Cascade foreign key akan otomatis menghapus anggaran dan transaksi terkait di MySQL)
        $stmt = $pdo->prepare("DELETE FROM tb_buku_tabungan WHERE id = ?");
        $stmt->execute([$id]);

        writeLog($pdo, $_SESSION['email'], "Menghapus Buku Tabungan: " . $book['nama_buku']);

        echo json_encode(['status' => 'success', 'message' => 'Buku Tabungan berhasil dihapus.']);
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses buku tabungan: ' . $e->getMessage()]);
    exit;
}
