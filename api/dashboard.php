<?php
// api/dashboard.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Silakan login terlebih dahulu.']);
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Ambil parameter mode dari request
$mode = $_GET['mode'] ?? 'user';

if ($mode === 'admin' && $role !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Forbidden. Akses khusus Administrator.']);
    exit;
}

try {
    if ($mode === 'admin') {
        // --- DATA DASHBOARD ADMIN GLOBAL ---

        // 1. Total Transaksi Hari Ini
        $stmt = $pdo->query("SELECT COUNT(id) as total FROM tb_transaksi WHERE DATE(tanggal_transaksi) = CURRENT_DATE()");
        $todayTrxCount = (int) $stmt->fetch()['total'];

        // 2. Jumlah Pengguna Terpantau (Hanya role 'user')
        $stmt = $pdo->query("SELECT COUNT(id) as total FROM tb_user WHERE role = 'user'");
        $totalUsers = (int) $stmt->fetch()['total'];

        // 3. Volume Uang Sistem (Total saldo_saat_ini seluruh buku tabungan)
        $stmt = $pdo->query("SELECT SUM(saldo_saat_ini) as total FROM tb_buku_tabungan");
        $totalEcoBalance = (float) ($stmt->fetch()['total'] ?? 0);

        // 4. Rerata Kesehatan Finansial Ekosistem
        // Ambil data saldo_awal dan pengeluaran per user
        $stmt = $pdo->query("
            SELECT 
                u.id,
                COALESCE(SUM(b.saldo_awal), 0) as total_initial,
                COALESCE((SELECT SUM(nominal) FROM tb_transaksi WHERE user_id = u.id), 0) as total_expense
            FROM tb_user u
            LEFT JOIN tb_buku_tabungan b ON u.id = b.user_id
            WHERE u.role = 'user'
            GROUP BY u.id
        ");
        $usersStats = $stmt->fetchAll();
        $totalScore = 0;
        $userCount = count($usersStats);
        
        foreach ($usersStats as $us) {
            $initial = (float) $us['total_initial'];
            $expense = (float) $us['total_expense'];
            if ($initial > 0) {
                $userScore = max(0, min(100, round((1 - ($expense / $initial)) * 100)));
            } else {
                $userScore = 100; // default sehat jika tidak ada saldo awal
            }
            $totalScore += $userScore;
        }
        $averageHealthScore = $userCount > 0 ? round($totalScore / $userCount) : 100;

        // 5. Rasio Kepatuhan Anggaran (Compliance Rate)
        $stmt = $pdo->query("
            SELECT 
                a.id, 
                a.batas_limit, 
                COALESCE(SUM(t.nominal), 0) as total_spent
            FROM tb_anggaran a
            LEFT JOIN tb_transaksi t ON t.anggaran_id = a.id
            GROUP BY a.id
        ");
        $budgets = $stmt->fetchAll();
        $compliantCount = 0;
        foreach ($budgets as $b) {
            if ((float)$b['total_spent'] <= (float)$b['batas_limit']) {
                $compliantCount++;
            }
        }
        $complianceRate = count($budgets) > 0 ? round(($compliantCount / count($budgets)) * 100) : 100;

        // 6. Penyerapan Fitur OCR (OCR rate)
        $stmt = $pdo->query("SELECT COUNT(id) as total FROM tb_transaksi WHERE input_method = 'ocr'");
        $ocrCount = (int) $stmt->fetch()['total'];
        $stmt = $pdo->query("SELECT COUNT(id) as total FROM tb_transaksi");
        $totalTrx = (int) $stmt->fetch()['total'];
        $ocrRate = $totalTrx > 0 ? round(($ocrCount / $totalTrx) * 100) : 0;

        // 7. Komparasi Pengeluaran Kebutuhan vs Keinginan
        $stmt = $pdo->query("SELECT prioritas, SUM(nominal) as total FROM tb_transaksi GROUP BY prioritas");
        $importanceComparison = ['Needs' => 0, 'Wants' => 0];
        foreach ($stmt->fetchAll() as $row) {
            if ($row['prioritas'] === 'Kebutuhan') {
                $importanceComparison['Needs'] = (float) $row['total'];
            } elseif ($row['prioritas'] === 'Keinginan') {
                $importanceComparison['Wants'] = (float) $row['total'];
            }
        }

        // 8. Tren Pengeluaran 7 Hari Terakhir
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendData[$date] = 0;
        }

        $stmt = $pdo->query("
            SELECT DATE(tanggal_transaksi) as tgl, SUM(nominal) as total 
            FROM tb_transaksi 
            WHERE tanggal_transaksi >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 DAY)
            GROUP BY DATE(tanggal_transaksi)
        ");
        foreach ($stmt->fetchAll() as $row) {
            if (isset($trendData[$row['tgl']])) {
                $trendData[$row['tgl']] = (float) $row['total'];
            }
        }

        echo json_encode([
            'status' => 'success',
            'data' => [
                'today_trx_count' => $todayTrxCount,
                'total_users' => $totalUsers,
                'total_eco_balance' => $totalEcoBalance,
                'average_health_score' => $averageHealthScore,
                'compliance_rate' => $complianceRate,
                'ocr_rate' => $ocrRate,
                'ocr_count' => $ocrCount,
                'total_trx' => $totalTrx,
                'importance_comparison' => $importanceComparison,
                'weekly_trend' => $trendData
            ]
        ]);
        exit;
    } else {
        // --- DATA DASHBOARD USER (Najwa / User Login) ---

        // 1. Total Saldo Tersedia (Sum of saldo_saat_ini for user's books)
        $stmt = $pdo->prepare("SELECT SUM(saldo_saat_ini) as total FROM tb_buku_tabungan WHERE user_id = ?");
        $stmt->execute([$userId]);
        $totalBalance = (float) ($stmt->fetch()['total'] ?? 0);

        // 2. Pengeluaran Bulan Ini
        $stmt = $pdo->prepare("
            SELECT SUM(nominal) as total 
            FROM tb_transaksi 
            WHERE user_id = ? 
              AND MONTH(tanggal_transaksi) = MONTH(CURRENT_DATE()) 
              AND YEAR(tanggal_transaksi) = YEAR(CURRENT_DATE())
        ");
        $stmt->execute([$userId]);
        $monthlyExpense = (float) ($stmt->fetch()['total'] ?? 0);

        // 3. Batas Seluruh Anggaran
        $stmt = $pdo->prepare("
            SELECT SUM(a.batas_limit) as total 
            FROM tb_anggaran a 
            JOIN tb_buku_tabungan b ON a.buku_tabungan_id = b.id 
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        $totalBudgetLimits = (float) ($stmt->fetch()['total'] ?? 0);

        // 4. Saldo Awal Keseluruhan (Untuk Health Score)
        $stmt = $pdo->prepare("SELECT SUM(saldo_awal) as total FROM tb_buku_tabungan WHERE user_id = ?");
        $stmt->execute([$userId]);
        $initialBalance = (float) ($stmt->fetch()['total'] ?? 0);

        // 5. Hitung Skor Kesehatan Finansial
        // Ambil total pengeluaran kumulatif user
        $stmt = $pdo->prepare("SELECT SUM(nominal) as total FROM tb_transaksi WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cumulativeExpense = (float) ($stmt->fetch()['total'] ?? 0);

        $healthScore = $initialBalance > 0 ? max(0, min(100, round((1 - ($cumulativeExpense / $initialBalance)) * 100))) : 0;

        // 6. Distribusi Kebutuhan vs Keinginan
        $stmt = $pdo->prepare("
            SELECT prioritas, SUM(nominal) as total 
            FROM tb_transaksi 
            WHERE user_id = ? 
            GROUP BY prioritas
        ");
        $stmt->execute([$userId]);
        $needs = 0;
        $wants = 0;
        foreach ($stmt->fetchAll() as $row) {
            if ($row['prioritas'] === 'Kebutuhan') {
                $needs = (float) $row['total'];
            } elseif ($row['prioritas'] === 'Keinginan') {
                $wants = (float) $row['total'];
            }
        }

        // 7. Aktivitas Terakhir (10 Transaksi Terakhir)
        $stmt = $pdo->prepare("
            SELECT t.*, k.nama_kategori 
            FROM tb_transaksi t 
            JOIN tb_kategori k ON t.kategori_id = k.id 
            WHERE t.user_id = ? 
            ORDER BY t.tanggal_transaksi DESC, t.id DESC 
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        $recentTransactions = $stmt->fetchAll();

        echo json_encode([
            'status' => 'success',
            'data' => [
                'total_balance' => $totalBalance,
                'monthly_expense' => $monthlyExpense,
                'total_budget_limits' => $totalBudgetLimits,
                'initial_balance' => $initialBalance,
                'health_score' => $healthScore,
                'needs' => $needs,
                'wants' => $wants,
                'recent_transactions' => $recentTransactions
            ]
        ]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage()]);
    exit;
}
