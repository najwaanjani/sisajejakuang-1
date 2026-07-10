<?php
// api/kategori.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Silakan login terlebih dahulu.']);
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];
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
        // Ambil kategori master (global) + kategori kustom (milik user saat ini)
        $stmt = $pdo->prepare("
            SELECT * FROM tb_kategori 
            WHERE user_id IS NULL OR user_id = ? 
            ORDER BY jenis_kategori DESC, nama_kategori ASC
        ");
        $stmt->execute([$userId]);
        $categories = $stmt->fetchAll();

        echo json_encode(['status' => 'success', 'data' => $categories]);
        exit;
    }

    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $action = $_GET['action'] ?? $input['action'] ?? 'create';

        if ($action === 'create') {
            // Membuat Kategori Kustom User
            $nama_kategori = trim($input['nama_kategori'] ?? '');

            if (empty($nama_kategori)) {
                echo json_encode(['status' => 'error', 'message' => 'Nama kategori tidak boleh kosong.']);
                exit;
            }

            // Cek duplikasi untuk user ini (atau di kategori master)
            $stmt = $pdo->prepare("
                SELECT id FROM tb_kategori 
                WHERE nama_kategori = ? AND (user_id = ? OR user_id IS NULL)
            ");
            $stmt->execute([$nama_kategori, $userId]);
            if ($stmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Nama kategori sudah terdaftar.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO tb_kategori (user_id, nama_kategori, jenis_kategori) VALUES (?, ?, 'kustom')");
            $stmt->execute([$userId, $nama_kategori]);
            $newCatId = $pdo->lastInsertId();

            writeLog($pdo, $_SESSION['email'], "Membuat Kategori Kustom: $nama_kategori");

            echo json_encode([
                'status' => 'success',
                'message' => 'Kategori kustom berhasil ditambahkan.',
                'data' => [
                    'id' => $newCatId,
                    'user_id' => $userId,
                    'nama_kategori' => $nama_kategori,
                    'jenis_kategori' => 'kustom'
                ]
            ]);
            exit;
        }

        elseif ($action === 'create_master') {
            // Membuat Kategori Master (Khusus Admin)
            if ($role !== 'admin') {
                echo json_encode(['status' => 'error', 'message' => 'Forbidden. Hanya admin yang bisa membuat kategori master.']);
                exit;
            }

            $nama_kategori = trim($input['nama_kategori'] ?? '');

            if (empty($nama_kategori)) {
                echo json_encode(['status' => 'error', 'message' => 'Nama kategori master tidak boleh kosong.']);
                exit;
            }

            // Cek duplikasi kategori master
            $stmt = $pdo->prepare("SELECT id FROM tb_kategori WHERE nama_kategori = ? AND user_id IS NULL");
            $stmt->execute([$nama_kategori]);
            if ($stmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Kategori master sudah terdaftar.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO tb_kategori (user_id, nama_kategori, jenis_kategori) VALUES (NULL, ?, 'master')");
            $stmt->execute([$nama_kategori]);
            $newCatId = $pdo->lastInsertId();

            writeLog($pdo, $_SESSION['email'], "Membuat Kategori Master: $nama_kategori");

            echo json_encode([
                'status' => 'success',
                'message' => 'Kategori master berhasil ditambahkan.',
                'data' => [
                    'id' => $newCatId,
                    'user_id' => null,
                    'nama_kategori' => $nama_kategori,
                    'jenis_kategori' => 'master'
                ]
            ]);
            exit;
        }

        elseif ($action === 'delete') {
            // Hapus Kategori
            $id = (int) ($input['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID kategori tidak valid.']);
                exit;
            }

            // Cek hak akses hapus
            if ($role === 'admin') {
                // Admin bisa menghapus apapun
                $stmt = $pdo->prepare("SELECT nama_kategori FROM tb_kategori WHERE id = ?");
                $stmt->execute([$id]);
            } else {
                // User biasa hanya bisa menghapus kategori kustom miliknya sendiri
                $stmt = $pdo->prepare("SELECT nama_kategori FROM tb_kategori WHERE id = ? AND user_id = ? AND jenis_kategori = 'kustom'");
                $stmt->execute([$id, $userId]);
            }
            $category = $stmt->fetch();

            if (!$category) {
                echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan atau Anda tidak memiliki akses untuk menghapusnya.']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM tb_kategori WHERE id = ?");
            $stmt->execute([$id]);

            writeLog($pdo, $_SESSION['email'], "Menghapus Kategori: " . $category['nama_kategori']);

            echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus.']);
            exit;
        }
    }

    elseif ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'ID kategori tidak valid.']);
            exit;
        }

        if ($role === 'admin') {
            $stmt = $pdo->prepare("SELECT nama_kategori FROM tb_kategori WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $pdo->prepare("SELECT nama_kategori FROM tb_kategori WHERE id = ? AND user_id = ? AND jenis_kategori = 'kustom'");
            $stmt->execute([$id, $userId]);
        }
        $category = $stmt->fetch();

        if (!$category) {
            echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan atau Anda tidak memiliki akses untuk menghapusnya.']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM tb_kategori WHERE id = ?");
        $stmt->execute([$id]);

        writeLog($pdo, $_SESSION['email'], "Menghapus Kategori: " . $category['nama_kategori']);

        echo json_encode(['status' => 'success', 'message' => 'Kategori berhasil dihapus.']);
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses kategori: ' . $e->getMessage()]);
    exit;
}
