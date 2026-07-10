<?php
// api/transaksi.php
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
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $bukuTabunganId = $_GET['buku_tabungan_id'] ?? null;

        $query = "
            SELECT t.*, b.nama_buku, a.nama_anggaran, k.nama_kategori 
            FROM tb_transaksi t 
            JOIN tb_buku_tabungan b ON t.buku_tabungan_id = b.id 
            LEFT JOIN tb_anggaran a ON t.anggaran_id = a.id 
            JOIN tb_kategori k ON t.kategori_id = k.id 
            WHERE t.user_id = ?
        ";
        $params = [$userId];

        if ($startDate && $endDate) {
            $query .= " AND t.tanggal_transaksi BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }

        if ($bukuTabunganId) {
            $query .= " AND t.buku_tabungan_id = ?";
            $params[] = (int) $bukuTabunganId;
        }

        $query .= " ORDER BY t.tanggal_transaksi DESC, t.id DESC";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll();

        echo json_encode(['status' => 'success', 'data' => $transactions]);
        exit;
    }

    elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $action = $_GET['action'] ?? $input['action'] ?? 'create';

        if ($action === 'create') {
            $buku_tabungan_id = (int) ($input['buku_tabungan_id'] ?? 0);
            $anggaran_id = !empty($input['anggaran_id']) ? (int) $input['anggaran_id'] : null;
            $kategori_id = (int) ($input['kategori_id'] ?? 0);
            $tanggal_transaksi = trim($input['tanggal_transaksi'] ?? date('Y-m-d'));
            $keterangan = trim($input['keterangan'] ?? '');
            $nominal = (float) ($input['nominal'] ?? 0);
            $prioritas = trim($input['prioritas'] ?? 'Kebutuhan');
            $input_method = trim($input['input_method'] ?? 'manual');
            
            if ($buku_tabungan_id <= 0 || $kategori_id <= 0 || empty($keterangan) || $nominal <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Harap isi seluruh field transaksi dengan benar.']);
                exit;
            }

            // Upload bukti pengeluaran jika ada
            $bukti_path = null;
            if (isset($_FILES['bukti_pengeluaran']) && $_FILES['bukti_pengeluaran']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/receipts/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileTmpPath = $_FILES['bukti_pengeluaran']['tmp_name'];
                $fileName = $_FILES['bukti_pengeluaran']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $destPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $bukti_path = 'uploads/receipts/' . $newFileName;
                    }
                }
            } elseif (!empty($input['bukti_pengeluaran'])) {
                // Jika input berupa data base64 dari simulasi OCR
                $bukti_raw = trim($input['bukti_pengeluaran']);
                if (preg_match('/^data:([^;]+);base64,(.*)$/', $bukti_raw, $matches)) {
                    $uploadDir = __DIR__ . '/../uploads/receipts/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $data = base64_decode($matches[2]);
                    $ext = 'png';
                    if (strpos($matches[1], 'svg') !== false) {
                        $ext = 'svg';
                    } elseif (strpos($matches[1], 'jpeg') !== false || strpos($matches[1], 'jpg') !== false) {
                        $ext = 'jpg';
                    }
                    $newFileName = md5(time() . rand()) . '.' . $ext;
                    if (file_put_contents($uploadDir . $newFileName, $data) !== false) {
                        $bukti_path = 'uploads/receipts/' . $newFileName;
                    }
                } else {
                    $bukti_path = $bukti_raw;
                }
            }

            // PROSES TRANSAKSI MYSQL (MENGGUNAKAN TRANSACTION LOCK)
            $pdo->beginTransaction();

            try {
                // 1. Ambil & Kunci saldo buku tabungan
                $stmt = $pdo->prepare("SELECT nama_buku, saldo_saat_ini FROM tb_buku_tabungan WHERE id = ? AND user_id = ? FOR UPDATE");
                $stmt->execute([$buku_tabungan_id, $userId]);
                $book = $stmt->fetch();

                if (!$book) {
                    $pdo->rollBack();
                    echo json_encode(['status' => 'error', 'message' => 'Buku tabungan tidak ditemukan.']);
                    exit;
                }

                $saldo_saat_ini = (float) $book['saldo_saat_ini'];

                // 2. Validasi kecukupan saldo
                if ($nominal > $saldo_saat_ini) {
                    $pdo->rollBack();
                    writeLog($pdo, $_SESSION['email'], "Transaksi ditolak: Saldo tidak cukup untuk mencatat '$keterangan'", 'failed');
                    echo json_encode(['status' => 'error', 'message' => 'Saldo buku tabungan tidak mencukupi untuk transaksi ini.']);
                    exit;
                }

                // 3. Simpan transaksi
                $stmt = $pdo->prepare("
                    INSERT INTO tb_transaksi 
                    (user_id, buku_tabungan_id, anggaran_id, kategori_id, tanggal_transaksi, keterangan, nominal, prioritas, bukti_pengeluaran, input_method) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $userId, $buku_tabungan_id, $anggaran_id, $kategori_id, 
                    $tanggal_transaksi, $keterangan, $nominal, $prioritas, 
                    $bukti_path, $input_method
                ]);
                $newTrxId = $pdo->lastInsertId();

                // 4. Update saldo buku tabungan
                $stmt = $pdo->prepare("UPDATE tb_buku_tabungan SET saldo_saat_ini = saldo_saat_ini - ? WHERE id = ?");
                $stmt->execute([$nominal, $buku_tabungan_id]);

                // Commit database transaction
                $pdo->commit();

                // Cek anggaran jika transaksi terikat ke anggaran
                $budgetWarning = false;
                $budgetMessage = '';
                if ($anggaran_id) {
                    $stmt = $pdo->prepare("
                        SELECT a.nama_anggaran, a.batas_limit, COALESCE(SUM(t.nominal), 0) as total_spent
                        FROM tb_anggaran a
                        LEFT JOIN tb_transaksi t ON t.anggaran_id = a.id
                        WHERE a.id = ?
                        GROUP BY a.id
                    ");
                    $stmt->execute([$anggaran_id]);
                    $budgetStats = $stmt->fetch();

                    if ($budgetStats) {
                        $limit = (float) $budgetStats['batas_limit'];
                        $spent = (float) $budgetStats['total_spent'];
                        if ($spent > $limit) {
                            $budgetWarning = true;
                            $budgetMessage = "Perhatian! Nominal transaksi telah melebihi batas limit anggaran '" . $budgetStats['nama_anggaran'] . "'!";
                        }
                    }
                }

                writeLog($pdo, $_SESSION['email'], "Mencatat transaksi: $keterangan ($input_method)");

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil dicatat.',
                    'budget_warning' => $budgetWarning,
                    'budget_warning_message' => $budgetMessage,
                    'data' => [
                        'id' => $newTrxId,
                        'buku_tabungan_id' => $buku_tabungan_id,
                        'nominal' => $nominal
                    ]
                ]);
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Gagal mencatat transaksi: ' . $e->getMessage()]);
                exit;
            }
        }

        elseif ($action === 'delete') {
            // Hapus Transaksi & Kembalikan Saldo Buku Tabungan
            $id = (int) ($input['id'] ?? 0);

            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID transaksi tidak valid.']);
                exit;
            }

            $pdo->beginTransaction();

            try {
                // 1. Dapatkan info transaksi & kunci baris transaksi untuk integritas
                $stmt = $pdo->prepare("SELECT * FROM tb_transaksi WHERE id = ? AND user_id = ? FOR UPDATE");
                $stmt->execute([$id, $userId]);
                $trx = $stmt->fetch();

                if (!$trx) {
                    $pdo->rollBack();
                    echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan atau bukan milik Anda.']);
                    exit;
                }

                $nominal = (float) $trx['nominal'];
                $buku_tabungan_id = (int) $trx['buku_tabungan_id'];
                $keterangan = $trx['keterangan'];

                // 2. Kembalikan saldo buku tabungan terkait
                $stmt = $pdo->prepare("UPDATE tb_buku_tabungan SET saldo_saat_ini = saldo_saat_ini + ? WHERE id = ?");
                $stmt->execute([$nominal, $buku_tabungan_id]);

                // 3. Hapus transaksi dari database
                $stmt = $pdo->prepare("DELETE FROM tb_transaksi WHERE id = ?");
                $stmt->execute([$id]);

                $pdo->commit();

                writeLog($pdo, $_SESSION['email'], "Menghapus transaksi: $keterangan (Reversal Saldo Rp $nominal)");

                echo json_encode(['status' => 'success', 'message' => 'Transaksi berhasil dihapus dan saldo tabungan telah dipulihkan.']);
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
                exit;
            }
        }
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses transaksi: ' . $e->getMessage()]);
    exit;
}
