<?php
// api/auth.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

// Helper untuk log audit
function writeLog($pdo, $actor, $action, $status = 'success') {
    try {
        $stmt = $pdo->prepare("INSERT INTO tb_system_logs (actor, action, status) VALUES (?, ?, ?)");
        $stmt->execute([$actor, $action, $status]);
    } catch (Exception $e) {
        // Abaikan error log agar tidak mengganggu response utama
    }
}

// Ambil input JSON jika ada
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = $_GET['action'] ?? $input['action'] ?? 'check';

try {
    if ($action === 'login') {
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Email dan Password wajib diisi.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM tb_user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            writeLog($pdo, $email, 'Login berhasil', 'success');

            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            writeLog($pdo, $email ?: 'Guest', 'Percobaan login gagal', 'failed');
            echo json_encode(['status' => 'error', 'message' => 'Email atau Password salah.']);
        }
        exit;
    }

    elseif ($action === 'register') {
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $role = $input['role'] ?? 'user'; // default role

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom pendaftaran wajib diisi.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
            exit;
        }

        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT id FROM tb_user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Email sudah terdaftar.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO tb_user (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $role]);
        $newUserId = $pdo->lastInsertId();

        // Buat kategori kustom default otomatis untuk user baru
        $stmtKategori = $pdo->prepare("INSERT INTO tb_kategori (user_id, nama_kategori, jenis_kategori) VALUES (?, ?, 'kustom')");
        $stmtKategori->execute([$newUserId, 'Kopi & Nongkrong']);
        $stmtKategori->execute([$newUserId, 'Skincare']);

        writeLog($pdo, $email, 'Registrasi akun baru berhasil', 'success');

        echo json_encode(['status' => 'success', 'message' => 'Pendaftaran berhasil. Silakan login.']);
        exit;
    }

    elseif ($action === 'logout') {
        $actor = $_SESSION['email'] ?? 'Guest';
        writeLog($pdo, $actor, 'Logout berhasil', 'success');

        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logout berhasil.']);
        exit;
    }

    elseif ($action === 'delete_account') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized.']);
            exit;
        }
        $userId = $_SESSION['user_id'];
        $email = $_SESSION['email'];

        // Hapus user dari database (akan cascade delete tb_buku_tabungan, tb_kategori, tb_transaksi)
        $stmt = $pdo->prepare("DELETE FROM tb_user WHERE id = ?");
        $stmt->execute([$userId]);

        writeLog($pdo, $email, 'Akun dihapus oleh pengguna', 'success');

        // Hancurkan session
        session_destroy();

        echo json_encode(['status' => 'success', 'message' => 'Akun berhasil dihapus.']);
        exit;
    }

    elseif ($action === 'check') {
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'status' => 'success',
                'logged_in' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['name'],
                    'email' => $_SESSION['email'],
                    'role' => $_SESSION['role']
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'logged_in' => false
            ]);
        }
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal sistem autentikasi: ' . $e->getMessage()]);
    exit;
}
