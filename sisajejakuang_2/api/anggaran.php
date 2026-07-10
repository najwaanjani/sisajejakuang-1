<?php
// api/anggaran.php
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
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $action = $_GET['action'] ?? $input['action'] ?? 'create';

        if ($action === 'create') {
            // Buat Anggaran Baru
            $buku_tabungan_id = (int) ($input['buku_tabungan_id'] ?? 0);
            $nama_anggaran = trim($input['nama_anggaran'] ?? '');
            $batas_limit = (float) ($input['batas_limit'] ?? 0);

            if ($buku_tabungan_id <= 0 || empty($nama_anggaran) || $batas_limit <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Semua kolom anggaran wajib diisi dengan valid.']);
                exit;
            }

            // Validasi kepemilikan buku tabungan dan saldo
            $stmt = $pdo->prepare("SELECT nama_buku, saldo_saat_ini FROM tb_buku_tabungan WHERE id = ? AND user_id = ?");
            $stmt->execute([$buku_tabungan_id, $userId]);
            $book = $stmt->fetch();

            if (!$book) {
                echo json_encode(['status' => 'error', 'message' => 'Buku tabungan tidak ditemukan.']);
                exit;
            }

            if ($batas_limit > (float) $book['saldo_saat_ini']) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Gagal: Batas anggaran (Rp ' . number_format($batas_limit, 0, ',', '.') . ') tidak boleh melebihi saldo buku tabungan saat ini (Rp ' . number_format($book['saldo_saat_ini'], 0, ',', '.') . ').'
                ]);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO tb_anggaran (buku_tabungan_id, nama_anggaran, batas_limit) VALUES (?, ?, ?)");
            $stmt->execute([$buku_tabungan_id, $nama_anggaran, $batas_limit]);
            $newBudgetId = $pdo->lastInsertId();

            writeLog($pdo, $_SESSION['email'], "Membuat Anggaran: $nama_anggaran di buku " . $book['nama_buku']);

            echo json_encode([
                'status' => 'success',
                'message' => 'Anggaran berhasil ditambahkan.',
                'data' => [
                    'id' => $newBudgetId,
                    'buku_tabungan_id' => $buku_tabungan_id,
                    'nama_anggaran' => $nama_anggaran,
                    'batas_limit' => $batas_limit
                ]
            ]);
            exit;
        }

        elseif ($action === 'delete') {
            // Hapus Anggaran
            $id = (int) ($input['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID anggaran tidak valid.']);
                exit;
            }

            // Validasi kepemilikan anggaran via buku tabungan
            $stmt = $pdo->prepare("
                SELECT a.nama_anggaran, b.nama_buku 
                FROM tb_anggaran a 
                JOIN tb_buku_tabungan b ON a.buku_tabungan_id = b.id 
                WHERE a.id = ? AND b.user_id = ?
            ");
            $stmt->execute([$id, $userId]);
            $budget = $stmt->fetch();

            if (!$budget) {
                echo json_encode(['status' => 'error', 'message' => 'Anggaran tidak ditemukan atau bukan milik Anda.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM tb_anggaran WHERE id = ?");
            $stmt->execute([$id]);

            writeLog($pdo, $_SESSION['email'], "Menghapus Anggaran: " . $budget['nama_anggaran'] . " dari " . $budget['nama_buku']);

            echo json_encode(['status' => 'success', 'message' => 'Anggaran berhasil dihapus.']);
            exit;
        }
    }

    elseif ($method === 'DELETE') {
        // Alternatif jika client mengirim request DELETE langsung
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID anggaran tidak valid.']);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT a.nama_anggaran, b.nama_buku 
            FROM tb_anggaran a 
            JOIN tb_buku_tabungan b ON a.buku_tabungan_id = b.id 
            WHERE a.id = ? AND b.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        $budget = $stmt->fetch();

        if (!$budget) {
            echo json_encode(['status' => 'error', 'message' => 'Anggaran tidak ditemukan atau bukan milik Anda.']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM tb_anggaran WHERE id = ?");
        $stmt->execute([$id]);

        writeLog($pdo, $_SESSION['email'], "Menghapus Anggaran: " . $budget['nama_anggaran'] . " dari " . $budget['nama_buku']);

        echo json_encode(['status' => 'success', 'message' => 'Anggaran berhasil dihapus.']);
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses anggaran: ' . $e->getMessage()]);
    exit;
}
