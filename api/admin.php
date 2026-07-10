<?php
// api/admin.php
session_start();
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Forbidden. Akses khusus Administrator.']);
    exit;
}

$action = $_GET['action'] ?? 'logs';

try {
    if ($action === 'logs') {
        // Ambil Log Audit Sistem
        $stmt = $pdo->query("SELECT * FROM tb_system_logs ORDER BY id DESC LIMIT 100");
        $logs = $stmt->fetchAll();

        echo json_encode(['status' => 'success', 'data' => $logs]);
        exit;
    }

    elseif ($action === 'users') {
        // Ambil Daftar Pengguna & Status Kesehatan Finansial
        $stmt = $pdo->query("
            SELECT 
                u.id, 
                u.name, 
                u.email, 
                COALESCE(SUM(b.saldo_saat_ini), 0) as balance, 
                COALESCE(SUM(b.saldo_awal), 0) as initial,
                (SELECT COALESCE(SUM(nominal), 0) FROM tb_transaksi WHERE user_id = u.id) as expense
            FROM tb_user u 
            LEFT JOIN tb_buku_tabungan b ON u.id = b.user_id 
            WHERE u.role = 'user' 
            GROUP BY u.id 
            ORDER BY u.name ASC
        ");
        $users = $stmt->fetchAll();

        $result = [];
        foreach ($users as $u) {
            $initial = (float) $u['initial'];
            $expense = (float) $u['expense'];
            $healthScore = $initial > 0 ? max(0, min(100, round((1 - ($expense / $initial)) * 100))) : 100;
            
            $result[] = [
                'id' => $u['id'],
                'name' => $u['name'],
                'email' => $u['email'],
                'balance' => (float) $u['balance'],
                'health_score' => $healthScore
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $result]);
        exit;
    }

    elseif ($action === 'dump_sql') {
        // Menghasilkan dynamic backup file SQL database
        $tables = ['tb_user', 'tb_buku_tabungan', 'tb_anggaran', 'tb_kategori', 'tb_transaksi', 'tb_system_logs'];
        
        $sqlDump = "-- SisaJejakUang Database Dump\n";
        $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sqlDump .= "-- Host: 127.0.0.1\n\n";
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Dapatkan struktur tabel
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $row = $stmt->fetch();
            $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
            $sqlDump .= $row['Create Table'] . ";\n\n";

            // Dapatkan data tabel
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll();

            if (count($rows) > 0) {
                $sqlDump .= "INSERT INTO `$table` (";
                
                // Ambil daftar nama kolom
                $columns = array_keys($rows[0]);
                $sqlDump .= implode(', ', array_map(function($c) { return "`$c`"; }, $columns));
                $sqlDump .= ") VALUES\n";

                $valArr = [];
                foreach ($rows as $r) {
                    $escapedVals = [];
                    foreach ($columns as $col) {
                        $val = $r[$col];
                        if ($val === null) {
                            $escapedVals[] = "NULL";
                        } else {
                            // Escape data
                            $escapedVals[] = $pdo->quote($val);
                        }
                    }
                    $valArr[] = "(" . implode(', ', $escapedVals) . ")";
                }
                $sqlDump .= implode(",\n", $valArr) . ";\n\n";
            }
        }
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Kembalikan file SQL sebagai unduhan attachment
        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="dump_sisajejakuang_' . date('Ymd_His') . '.sql"');
        header('Content-Length: ' . strlen($sqlDump));
        
        echo $sqlDump;
        exit;
    }

    else {
        echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memproses data admin: ' . $e->getMessage()]);
    exit;
}
