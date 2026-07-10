<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$current_user = $is_logged_in ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'role' => $_SESSION['role']
] : null;

if (!$is_logged_in):
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisaJejakUang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-gradient-to-tr from-indigo-150 via-slate-50 to-rose-150 text-slate-900 min-h-screen flex items-center justify-center p-4">
    <!-- Login Card -->
    <div class="glass-card w-full max-w-md p-8 rounded-3xl shadow-xl border border-slate-200">
        <div class="flex flex-col items-center mb-8">
            <div class="bg-indigo-600 p-3 rounded-2xl shadow-lg shadow-indigo-200 mb-3 text-white">
                <i data-lucide="wallet" class="w-8 h-8"></i>
            </div>
            <h1 class="font-extrabold text-2xl tracking-tight text-indigo-900 leading-none">SisaJejakUang</h1>
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-2">Sistem Manajemen Keuangan</p>
        </div>

        <!-- Tabs -->
        <div class="flex bg-slate-100 p-1 rounded-xl mb-6 shadow-inner">
            <button onclick="switchTab('login')" id="tab-login" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all bg-white text-indigo-900 shadow-sm">Masuk</button>
            <button onclick="switchTab('register')" id="tab-register" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all text-slate-500">Daftar</button>
        </div>

        <!-- Form Login -->
        <form id="form-login" onsubmit="handleAuthSubmit(event, 'login')" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Email</label>
                <input type="email" id="login-email" placeholder="contoh@mail.com" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold outline-none focus:border-indigo-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Password</label>
                <input type="password" id="login-password" placeholder="••••••••" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold outline-none focus:border-indigo-500 transition-colors">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-750 text-white font-extrabold py-3.5 rounded-xl uppercase text-xs tracking-wider shadow-lg shadow-indigo-150 transition-all">Masuk Aplikasi</button>
        </form>

        <!-- Form Register -->
        <form id="form-register" onsubmit="handleAuthSubmit(event, 'register')" class="space-y-4 hidden">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Nama Lengkap</label>
                <input type="text" id="reg-name" placeholder="Nama Lengkap Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold outline-none focus:border-indigo-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Email</label>
                <input type="email" id="reg-email" placeholder="contoh@mail.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold outline-none focus:border-indigo-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Password</label>
                <input type="password" id="reg-password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-semibold outline-none focus:border-indigo-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5 px-1">Peran (Role)</label>
                <select id="reg-role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold outline-none focus:border-indigo-500 transition-colors">
                    <option value="user">Pengguna Biasa (User)</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-750 text-white font-extrabold py-3.5 rounded-xl uppercase text-xs tracking-wider shadow-lg shadow-indigo-150 transition-all">Daftar Sekarang</button>
        </form>

        <div class="mt-8 pt-4 border-t border-slate-100 text-center">
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Sistem Manajemen Keuangan</p>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

    <script>
        function switchTab(tab) {
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');

            if (tab === 'login') {
                tabLogin.className = 'flex-1 py-2 rounded-lg text-xs font-bold transition-all bg-white text-indigo-900 shadow-sm';
                tabRegister.className = 'flex-1 py-2 rounded-lg text-xs font-bold transition-all text-slate-500';
                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');
            } else {
                tabRegister.className = 'flex-1 py-2 rounded-lg text-xs font-bold transition-all bg-white text-indigo-900 shadow-sm';
                tabLogin.className = 'flex-1 py-2 rounded-lg text-xs font-bold transition-all text-slate-500';
                formRegister.classList.remove('hidden');
                formLogin.classList.add('hidden');
            }
        }

        async function handleAuthSubmit(e, type) {
            e.preventDefault();
            const url = `api/auth.php?action=${type}`;
            let payload = {};

            if (type === 'login') {
                payload = {
                    email: document.getElementById('login-email').value,
                    password: document.getElementById('login-password').value
                };
            } else {
                payload = {
                    name: document.getElementById('reg-name').value,
                    email: document.getElementById('reg-email').value,
                    password: document.getElementById('reg-password').value,
                    role: document.getElementById('reg-role').value
                };
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch(parseErr) {
                    console.error("Non-JSON Response:", text);
                    showToast('Respons server tidak valid: ' + text.substring(0, 100), 'error');
                    return;
                }

                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    showToast(data.message || 'Operasi gagal.', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Koneksi server gagal: ' + err.message, 'error');
            }
        }

        function showToast(msg, type) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `p-4 rounded-xl shadow-lg text-xs font-bold text-white transition-all transform translate-y-2 opacity-0 flex items-center gap-2 ${
                type === 'success' ? 'bg-emerald-600' : type === 'error' ? 'bg-rose-500' : 'bg-indigo-650'
            }`;
            
            let icon = 'info';
            if (type === 'success') icon = 'check-circle';
            if (type === 'error') icon = 'alert-triangle';

            toast.innerHTML = `<i data-lucide="${icon}" class="w-4 h-4"></i> ${msg}`;
            container.appendChild(toast);
            lucide.createIcons();

            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        lucide.createIcons();
    </script>
</body>
</html>
<?php
exit;
endif;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SisaJejakUang - Sistem Manajemen Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Library untuk Ekspor PDF dari DOM HTML -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 1);
        }
        .page-content { transition: all 0.3s ease; }
        .category-tag {
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">

    <!-- NAVIGASI UTAMA -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="wallet" class="text-white w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-xl tracking-tight text-indigo-900 leading-none">SisaJejakUang</h1>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-800"><?= htmlspecialchars($current_user['name']) ?></p>
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider"><?= htmlspecialchars($current_user['role']) ?></p>
                </div>
                <?php if ($current_user['role'] === 'admin'): ?>
                <div class="bg-slate-100 p-1 rounded-xl flex gap-1 shadow-inner">
                    <button id="btn-mode-user" onclick="switchRole('user')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">User Mode</button>
                    <button id="btn-mode-admin" onclick="switchRole('admin')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Admin Mode</button>
                </div>
                <?php endif; ?>
                <button onclick="handleLogout()" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" title="Keluar (Logout)">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- LAYOUT UTAMA -->
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row min-h-[calc(100vh-80px)]">
        
        <!-- SIDEBAR KHUSUS ADMIN -->
        <aside id="admin-sidebar" class="hidden w-full md:w-64 bg-white border-r border-slate-200 p-6 space-y-2">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Admin Control</p>
            <button onclick="showAdminPage('dashboard')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="dashboard">
                <i data-lucide="pie-chart" class="w-4 h-4"></i> Dashboard Global
            </button>
            <button onclick="showAdminPage('categories')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="categories">
                <i data-lucide="tags" class="w-4 h-4"></i> Master Kategori
            </button>
            <button onclick="showAdminPage('users')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="users">
                <i data-lucide="users" class="w-4 h-4"></i> Audit Pengguna
            </button>
            <button onclick="showAdminPage('database')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="database">
                <i data-lucide="database" class="w-4 h-4"></i> Audit Tabel & SQL
            </button>
            <hr class="my-4 border-slate-100">
            <button onclick="showAdminPage('guide')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="guide">
                <i data-lucide="book-open" class="w-4 h-4"></i> Panduan Admin
            </button>
            <button onclick="showAdminPage('settings')" class="admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all" data-admin-page="settings">
                <i data-lucide="settings" class="w-4 h-4"></i> Pengaturan Akun
            </button>
        </aside>

        <!-- KONTEN AREA -->
        <main id="main-content" class="flex-1 p-4 md:p-8">
            
            <!-- VIEW MODE PENGGUNA (USER) -->
            <div id="view-user" class="space-y-8">

            <!-- SIDEBAR -->
            <!-- <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
                <nav class="flex-1 px-4 py-6 space-y-1">
                    <button onclick="showUserPage('dashboard')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="dashboard">Dashboard</button>
                    <button onclick="showUserPage('accounts')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="accounts">Tabungan & Anggaran</button>
                    <button onclick="showUserPage('transactions')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="transactions">Transaksi & Kategori</button>
                    <button onclick="showUserPage('settings')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="settings">Pengaturan Akun</button>
                </nav>
            </aside> -->
                <!-- Navigasi Internal User -->
                <div class="flex justify-center">
                    <div class="flex bg-slate-100 p-1 rounded-2xl w-fit shadow-inner">
                        <button onclick="showUserPage('dashboard')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="dashboard">Dashboard</button>
                        <button onclick="showUserPage('accounts')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="accounts">Tabungan & Anggaran</button>
                        <button onclick="showUserPage('transactions')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="transactions">Transaksi & Kategori</button>
                        <button onclick="showUserPage('settings')" class="user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all" data-user-page="settings">Pengaturan Akun</button>
                    </div>
                </div>

                <!-- Halaman User: Dashboard -->
                <div id="u-page-dashboard" class="u-page space-y-6">
                    <!-- AREA ALERT DINAMIS -->
                    <div id="u-dashboard-alerts" class="hidden space-y-3"></div>

                <!-- HERO / WELCOME CARD -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-400 p-8 mb-8 text-white">
                    <!-- Dekorasi bulatan blur, mirip efek di gambar 1 -->
                    <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-56 h-56 bg-white/10 rounded-full"></div>

                    <div class="relative z-10">
                        <p class="font-semibold text-xl tracking-tight text-white leading-none">Selamat datang kembali,</p>
                        <h1 class="text-3xl font-bold mt-1"><?= htmlspecialchars($current_user['name']) ?> 👋</h1>
                    </div>
                        <br> 
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Indikator Skor Kesehatan Finansial -->
                        <div class="lg:col-span-1 glass-card p-6 rounded-3xl border-indigo-500 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 opacity-10 text-indigo-600 rotate-12">
                                <i data-lucide="activity" class="w-24 h-24"></i>
                            </div>
                            <p class="text-[10px] font-black text-slate-400 uppercase mb-4 tracking-widest">Financial Health Score</p>
                            <div class="flex items-end gap-2 mb-2">
                                <h3 id="u-health-score" class="text-5xl font-black text-indigo-600">0</h3>
                                <span class="text-slate-400 font-bold mb-1">/ 100</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mb-4">
                                <div id="u-health-bar" class="health-bar bg-indigo-500 h-full w-0"></div>
                            </div>
                            <p id="u-health-desc" class="text-xs text-slate-500 font-medium">Memuat skor...</p>
                        </div>
                        
                        <!-- Distribusi Needs vs Wants -->
                        <!-- <div class="lg:col-span-1 glass-card p-6 rounded-3xl border-indigo-500 shadow-sm relative overflow-hidden">
                            <div class="justify-center">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1">Total Kebutuhan (Needs)</p>
                                <h4 id="u-stat-needs" class="text-xl font-black text-indigo-700">Rp 0</h4>
                            </div>
                        </div>
                        
                        <div class="lg:col-span-1 glass-card p-6 rounded-3xl border-indigo-500 shadow-sm relative overflow-hidden">
                            <div class="justify-center">
                                <p class="text-[10px] font-bold text-rose-400 uppercase mb-1">Total Keinginan (Wants)</p>
                                <h4 id="u-stat-wants" class="text-xl font-black text-rose-700">Rp 0</h4>
                            </div>
                        </div> -->

                        <!-- Distribusi Needs vs Wants -->
                        <div class="lg:col-span-2 glass-card p-6 rounded-3xl shadow-sm grid grid-cols-2 gap-4">
                            <div class="bg-indigo-100 p-5 rounded-2xl flex flex-col justify-center">
                                <p class="text-[10px] font-bold text-indigo-400 uppercase mb-1">Total Kebutuhan (Needs)</p>
                                <h4 id="u-stat-needs" class="text-xl font-black text-indigo-700">Rp 0</h4>
                            </div>
                            <div class="bg-rose-50 p-5 rounded-2xl flex flex-col justify-center">
                                <p class="text-[10px] font-bold text-rose-400 uppercase mb-1">Total Keinginan (Wants)</p>
                                <h4 id="u-stat-wants" class="text-xl font-black text-rose-700">Rp 0</h4>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="glass-card p-6 rounded-3xl border-b-4 border-yellow-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Saldo Tersedia</p>
                            <h3 id="u-stat-balance" class="text-2xl font-black text-slate-800 tracking-tight">Rp 0</h3>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-b-4 border-rose-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Pengeluaran Bulan Ini</p>
                            <h3 id="u-stat-expense" class="text-2xl font-black text-rose-600 tracking-tight">Rp 0</h3>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-b-4 border-emerald-600 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Batas Seluruh Anggaran</p>
                            <h3 id="u-stat-budget-used" class="text-2xl font-black text-emerald-600 tracking-tight">Rp 0</h3>
                        </div>
                    </div>
                </div>
                    <div class="glass-card rounded-3xl overflow-hidden p-6 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-indigo-500"></i> Aktivitas Terakhir
                            </h4>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                Klik Kolom Waktu / Nominal Untuk Urutkan
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider select-none">
                                    <tr>
                                        <th class="px-6 py-3 cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleTrxSort('date')">
                                            Keterangan <span id="sort-recent-date" class="text-indigo-600">▼</span>
                                        </th>
                                        <th class="px-6 py-3 text-right cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleTrxSort('amount')">
                                            Nominal <span id="sort-recent-amount" class="text-indigo-600"></span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="u-recent-list" class="divide-y divide-slate-100"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Halaman User: Accounts & Budgets -->
                <div id="u-page-accounts" class="u-page hidden space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800">Buku Tabungan & Anggaran Spesifik</h2>
                            <p class="text-xs text-slate-400 font-medium">Buat dompet tabungan dan tambahkan berbagai target pengeluaran di dalamnya (misal: Makan, Main).</p>
                        </div>
                        <button onclick="openModal('modal-account')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100">Buat Buku Tabungan</button>
                    </div>
                    <div id="u-accounts-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
                </div>

                <!-- Halaman User: Transactions & Custom Categories -->
                <div id="u-page-transactions" class="u-page hidden space-y-8">
                    
                    <!-- GRID ATAS: Kategori Custom (Kiri) & Neraca Buku Besar (Kanan) - Height Sama -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                        
                        <!-- Card Kategori Pengguna Mandiri -->
                        <div class="glass-card p-6 rounded-3xl flex flex-col justify-between h-full space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Kategori Kustom Saya</h3>
                                    <span class="text-xs text-indigo-600 font-bold">tb_kategori</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mb-3">Buat klasifikasi pengeluaran pribadi Anda sendiri secara dinamis.</p>
                                <form id="form-user-category" class="flex gap-2">
                                    <input type="text" id="user-cat-name" placeholder="Nama Kategori..." required class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-semibold outline-none focus:border-indigo-500">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all">+</button>
                                </form>
                            </div>
                            <div id="user-categories-list" class="flex flex-wrap gap-2 pt-2 overflow-y-auto max-h-[140px]"></div>
                        </div>

                        <!-- NERACA BUKU BESAR (Sisi Kanan Kategori Custom) -->
                        <div class="glass-card p-6 rounded-3xl shadow-sm flex flex-col justify-between h-full space-y-4">
                            <div>
                                <div class="border-b border-slate-100 pb-3 flex justify-between items-start">
                                    <div>
                                        <h3 class="font-extrabold text-indigo-950 text-sm flex items-center gap-1.5">
                                            <i data-lucide="book-marked" class="text-indigo-600 w-4.5 h-4.5"></i> Neraca Buku Besar
                                        </h3>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1 leading-normal">
                                            Memantau debit (pembukaan), kredit (pengeluaran), dan saldo penutupan bersih.
                                        </p>
                                    </div>
                                    <button onclick="previewLedgerPDF()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all flex items-center justify-center gap-1 shadow-md shadow-indigo-100">
                                        <i data-lucide="printer" class="w-3 h-3"></i> Cetak PDF
                                    </button>
                                </div>

                                <!-- Pengendali Filter & Rentang Waktu -->
                                <div class="grid grid-cols-2 gap-2 bg-slate-50 p-2 rounded-xl border border-slate-200 mt-3">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] font-black text-slate-400 uppercase px-1">Dari</span>
                                        <input type="date" id="ledger-start" class="bg-transparent border-0 text-xs font-bold text-slate-700 outline-none p-0.5">
                                    </div>
                                    <div class="flex flex-col border-l border-slate-200 pl-2">
                                        <span class="text-[8px] font-black text-slate-400 uppercase px-1">Sampai</span>
                                        <input type="date" id="ledger-end" class="bg-transparent border-0 text-xs font-bold text-slate-700 outline-none p-0.5">
                                    </div>
                                </div>
                                
                                <div class="flex gap-1 mt-2">
                                    <button onclick="setLedgerRange('month')" class="flex-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 py-1 rounded-lg text-[10px] font-bold transition-all text-center">Bulan Ini</button>
                                    <button onclick="setLedgerRange('all')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-1 rounded-lg text-[10px] font-bold transition-all text-center">Semua</button>
                                </div>
                            </div>

                            <!-- Tabel Neraca Saku -->
                            <div class="overflow-y-auto max-h-[140px]">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[9px] tracking-wider select-none sticky top-0">
                                        <tr>
                                            <th class="py-2 px-1">Buku</th>
                                            <th class="py-2 text-right">Debit</th>
                                            <th class="py-2 text-right text-rose-500">Kredit</th>
                                            <th class="py-2 text-right text-indigo-600">Saldo</th>
                                            <th class="py-2 text-center">Jurnal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="u-ledger-tbody" class="divide-y divide-slate-100 font-medium">
                                        <!-- Dinamis dari javascript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN BAWAH: Daftar Pengeluaran Tercatat & Jurnal Penjelas -->
                    <div class="space-y-6">
                        
                        <!-- Daftar Transaksi -->
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                <div class="flex flex-col">
                                    <h2 class="text-xl font-black text-slate-800">Pengeluaran Tercatat</h2>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Tabel kumpulan transaksi yang sudah dicatat.</span>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button onclick="simulateOCR()" class="bg-slate-800 text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-black transition-all">
                                        <i data-lucide="camera" class="w-4 h-4"></i> Scan Struk
                                    </button>
                                    <button onclick="openNewTransactionModal()" class="bg-rose-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-rose-100">Catat Baru</button>
                                </div>
                            </div>
                            <div class="glass-card rounded-3xl overflow-hidden shadow-sm">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm min-w-[900px]">
                                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider select-none">
                                            <tr>
                                                <th class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleTrxSort('date')">
                                                    Waktu <span id="sort-trx-date" class="text-indigo-600">▼</span>
                                                </th>
                                                <th class="px-6 py-4">Keterangan</th>
                                                <th class="px-6 py-4">Buku Tabungan</th>
                                                <th class="px-6 py-4">Alokasi Anggaran</th>
                                                <th class="px-6 py-4">Prioritas</th>
                                                <th class="px-6 py-4">Kategori</th>
                                                <th class="px-6 py-4">Bukti</th>
                                                <th class="px-6 py-4 text-right cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleTrxSort('amount')">
                                                    Nominal <span id="sort-trx-amount" class="text-indigo-600"></span>
                                                </th>
                                                <th class="px-6 py-4 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="u-all-list" class="divide-y divide-slate-100"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Jurnal Penjelas Tambahan -->
                        <div id="u-ledger-details-container" class="hidden bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-3">
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                                <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="scroll-text" class="w-4 h-4 text-indigo-600"></i> Jurnal Umum Penjelas: <span id="ledger-detail-title" class="text-indigo-600"></span>
                                </h4>
                                <button onclick="closeLedgerDetails()" class="text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs font-medium">
                                    <thead class="text-slate-400 uppercase text-[9px] font-extrabold border-b border-slate-100">
                                        <tr>
                                            <th class="py-2">Waktu Jurnal</th>
                                            <th class="py-2">Keterangan / Transaksi</th>
                                            <th class="py-2">Kategori</th>
                                            <th class="py-2">Sifat Prioritas</th>
                                            <th class="py-2 text-right">Nominal Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="u-ledger-details-tbody" class="divide-y divide-slate-100 text-slate-600">
                                        <!-- Dinamis rincian transaksi -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Halaman User: Settings -->
                <div class="justify-center items-center">
                    <div id="u-page-settings" class="u-page hidden space-y-6">
                        <div class="max-w-md glass-card p-8 rounded-3xl shadow-sm border border-slate-200 mx-auto">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-indigo-50 p-2.5 rounded-2xl text-indigo-600">
                                    <i data-lucide="settings" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-800 leading-none">Pengaturan Akun</h3>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola Sesi & Keamanan</p>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mb-6">Informasi akun pengguna.</p>
                            
                            <div class="space-y-4">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Pengguna</p>
                                    <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($current_user['name']) ?></p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Email</p>
                                    <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($current_user['email']) ?></p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Peran (Role)</p>
                                    <p class="text-sm font-bold text-indigo-600 uppercase font-extrabold"><?= htmlspecialchars($current_user['role']) ?></p>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 space-y-3">
                                <button onclick="handleLogout()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar (Logout)
                                </button>
                                <button onclick="handleDeleteAccount()" class="w-full bg-rose-50 hover:bg-rose-150 hover:text-rose-700 text-rose-600 font-bold py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 border border-rose-200">
                                    <i data-lucide="user-x" class="w-4 h-4"></i> Hapus Akun Permanen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW MODE ADMINISTRATOR (ADMIN) -->
            <div id="view-admin" class="hidden space-y-8">
                
                <!-- Admin Dashboard Overview -->
                <div id="a-page-dashboard" class="a-page space-y-6">
                    <h2 class="text-3xl font-black text-slate-800">Admin Dashboard Overview</h2>
                    <p class="text-sm text-slate-500">Pemantauan volume finansial ekosistem dan kepatuhan anggaran.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 pt-2">
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-indigo-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Total Transaksi Hari Ini</p>
                            <h3 id="a-stat-today-trx" class="text-3xl font-black text-slate-800">0</h3>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-emerald-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Pengguna Terpantau</p>
                            <h3 id="a-stat-users" class="text-3xl font-black text-slate-800">0</h3>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-amber-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Volume Uang Sistem</p>
                            <h3 id="a-stat-balance" class="text-2xl font-black text-slate-800">Rp 0</h3>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-rose-500 shadow-sm">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1">Rerata Kesehatan Finansial</p>
                            <h3 id="a-stat-health-avg" class="text-3xl font-black text-rose-600">0%</h3>
                        </div>
                    </div>

                    <!-- BARIS GRAFIK & VISUALISASI KESEHATAN FINANSIAL & COMPLIANCE & OCR STATS -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Rerata Kesehatan Gauge -->
                        <div class="glass-card p-6 rounded-3xl flex flex-col items-center justify-center text-center">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Rata-rata Kesehatan Finansial</h4>
                            <div class="relative w-40 h-40 flex items-center justify-center">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="80" cy="80" r="70" class="text-slate-100" stroke-width="12" stroke="currentColor" fill="transparent" />
                                    <circle id="admin-health-circle" cx="80" cy="80" r="70" class="text-indigo-600 transition-all duration-500" stroke-width="12" stroke-linecap="round" stroke="currentColor" fill="transparent" stroke-dasharray="439.8" stroke-dashoffset="439.8" />
                                </svg>
                                <div class="absolute flex flex-col items-center justify-center">
                                    <span id="admin-health-percentage" class="text-3xl font-black text-indigo-900">0%</span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Skor Ekosistem</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 font-semibold mt-4">Kombinasi kumulatif kepatuhan anggaran seluruh pengguna aktif.</p>
                        </div>

                        <!-- Kepatuhan Anggaran & Penggunaan OCR (FITUR REKOMENDASI TERBARU) -->
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Card Kepatuhan Anggaran -->
                            <div class="glass-card p-6 rounded-3xl flex flex-col justify-between">
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rasio Kepatuhan Anggaran</h4>
                                    <p class="text-[10px] text-slate-400 leading-normal mb-4">Persentase pos anggaran yang sukses dikelola di bawah batas limit.</p>
                                    <div class="flex items-baseline gap-2 mb-2">
                                        <span id="admin-compliance-rate" class="text-4xl font-black text-emerald-600">0%</span>
                                        <span class="text-[10px] font-bold text-slate-400">Compliance Rate</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div id="admin-compliance-bar" class="bg-emerald-500 h-full w-0 transition-all duration-500"></div>
                                    </div>
                                </div>
                                <div class="text-[9px] font-semibold text-slate-400 mt-2">Target standar industri: &gt; 80%</div>
                            </div>

                            <!-- Card Efisiensi Fitur OCR -->
                            <div class="glass-card p-6 rounded-3xl flex flex-col justify-between">
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Efisiensi Fitur OCR (Scan)</h4>
                                    <p class="text-[10px] text-slate-400 leading-normal mb-4">Rasio adopsi pemindaian otomatis struk belanja digital dibanding manual.</p>
                                    <div class="flex items-baseline gap-2 mb-2">
                                        <span id="admin-ocr-rate" class="text-4xl font-black text-indigo-600">0%</span>
                                        <span class="text-[10px] font-bold text-slate-400">OCR Utilization</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        <div id="admin-ocr-bar" class="bg-indigo-500 h-full w-0 transition-all duration-500"></div>
                                    </div>
                                </div>
                                <div class="text-[9px] font-semibold text-slate-400 mt-2" id="admin-ocr-ratio-text">0 dari 0 Transaksi</div>
                            </div>
                        </div>
                    </div>

                    <!-- BARIS GRAFIK TREN & COMPARISON -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-1 glass-card p-6 rounded-3xl flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Komparasi Pengeluaran Kebutuhan & Keinginan</h4>
                                <div class="space-y-4" id="admin-needs-wants-bars"></div>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex justify-between text-[10px] text-slate-400 font-bold">
                                <span>STATUS: REKOMENDASI TERPANTAU</span>
                                <span>TOTAL: 2 KATEGORI PRIORITAS</span>
                            </div>
                        </div>

                        <div class="lg:col-span-2 glass-card p-6 rounded-3xl shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 mb-4">
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Tren Pengeluaran 7 Hari Terakhir</h4>
                                    <p class="text-xs text-slate-500 font-medium">Grafik mingguan akumulasi nominal pengeluaran ekosistem.</p>
                                </div>
                                <span class="text-[10px] bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full font-bold uppercase tracking-wider">Transaksi Perminggu</span>
                            </div>
                            <div class="w-full overflow-x-auto">
                                <div id="admin-weekly-chart" class="min-w-[550px] h-48 flex items-center justify-center">
                                    <!-- Dynamic SVG Line Chart goes here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- USER ACTIVITY LOGS (FITUR REKOMENDASI TERBARU) -->
                    <div class="glass-card p-6 rounded-3xl shadow-sm space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-150 pb-3">
                            <div>
                                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-1.5">
                                    <i data-lucide="shield-alert" class="text-rose-500 w-4.5 h-4.5"></i> Log Audit Aktivitas Sistem
                                </h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-1">
                                    Rekam aktivitas operasional real-time pengguna simulan di dalam ekosistem SisaJejakUang.
                                </p>
                            </div>
                            <span class="text-[10px] bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-bold uppercase tracking-wider">Keamanan & Audit</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[9px] tracking-wider select-none">
                                    <tr>
                                        <th class="px-4 py-2.5">Waktu Operasional</th>
                                        <th class="px-4 py-2.5">Aktor / ID</th>
                                        <th class="px-4 py-2.5">Deskripsi Aktivitas</th>
                                        <th class="px-4 py-2.5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-activity-tbody" class="divide-y divide-slate-100 font-semibold text-slate-600">
                                    <!-- Dinamis dari javascript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PREVIEW SINGKAT KESELURUHAN DATA SISTEM -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i data-lucide="eye" class="text-indigo-600"></i> Preview Singkat Keseluruhan Data Sistem
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Buku Tabungan Preview -->
                            <div class="glass-card p-5 rounded-3xl shadow-sm space-y-3">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <h5 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="wallet" class="w-3.5 h-3.5 text-indigo-600"></i> Buku Tabungan
                                    </h5>
                                    <span id="preview-count-books" class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                                </div>
                                <div id="preview-list-books" class="space-y-2 text-xs max-h-48 overflow-y-auto"></div>
                            </div>

                            <!-- Anggaran Preview -->
                            <div class="glass-card p-5 rounded-3xl shadow-sm space-y-3">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <h5 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="pie-chart" class="w-3.5 h-3.5 text-emerald-600"></i> Alokasi Anggaran
                                    </h5>
                                    <span id="preview-count-budgets" class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                                </div>
                                <div id="preview-list-budgets" class="space-y-2 text-xs max-h-48 overflow-y-auto"></div>
                            </div>

                            <!-- Kategori Preview -->
                            <div class="glass-card p-5 rounded-3xl shadow-sm space-y-3">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <h5 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="tags" class="w-3.5 h-3.5 text-amber-600"></i> Kategori Aktif
                                    </h5>
                                    <span id="preview-count-categories" class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                                </div>
                                <div id="preview-list-categories" class="space-y-2 text-xs max-h-48 overflow-y-auto"></div>
                            </div>

                            <!-- Transaksi Preview -->
                            <div class="glass-card p-5 rounded-3xl shadow-sm space-y-3">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                                    <h5 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center gap-1.5">
                                        <i data-lucide="arrow-left-right" class="w-3.5 h-3.5 text-rose-600"></i> Transaksi Terakhir
                                    </h5>
                                    <span id="preview-count-transactions" class="bg-rose-50 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded-full">0</span>
                                </div>
                                <div id="preview-list-transactions" class="space-y-2 text-xs max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Master Categories Page -->
                <div id="a-page-categories" class="a-page hidden space-y-6">
                    <h2 class="text-2xl font-black text-slate-800">Kelola Kategori Master (Rekomendasi Global)</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-1 glass-card p-6 rounded-3xl h-fit">
                            <h4 class="font-bold text-slate-800 mb-4">Tambah Kategori Master</h4>
                            <form id="form-admin-category" class="space-y-4">
                                <input type="text" id="admin-cat-name" placeholder="Nama Kategori Master" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold outline-none text-sm">
                                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl uppercase text-xs tracking-widest shadow-lg">Simpan</button>
                            </form>
                        </div>
                        <div class="lg:col-span-2 glass-card rounded-3xl overflow-hidden shadow-sm">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px]">
                                    <tr>
                                        <th class="px-6 py-4">Nama Kategori Master</th>
                                        <th class="px-6 py-4">Waktu Dibuat</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="a-categories-list" class="divide-y divide-slate-100"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Admin Users Audit Page (TERINTEGRASI STATUS FINANCIAL KRITIS/SEHAT) -->
                <div id="a-page-users" class="a-page hidden space-y-6">
                    <div class="flex flex-col mb-4">
                        <h2 class="text-2xl font-black text-slate-800">Database & Audit Finansial Pengguna</h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">Klik kolom Nama atau Saldo untuk mengurutkan daftar pengguna</span>
                    </div>
                    <div class="glass-card rounded-3xl overflow-hidden shadow-sm">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] select-none">
                                <tr>
                                    <th class="px-6 py-4 cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleUserSort('name')">
                                        Nama Pengguna <span id="sort-user-name" class="text-indigo-600"></span>
                                    </th>
                                    <th class="px-6 py-4 text-center">Status Kesehatan Finansial</th>
                                    <th class="px-6 py-4 text-right cursor-pointer hover:bg-slate-100 transition-colors" onclick="handleUserSort('balance')">
                                        Total Saldo Terbaca <span id="sort-user-balance" class="text-indigo-600">▼</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="a-users-list" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ADMIN DATABASE AUDIT & SQL GENERATOR PAGE (TERINTEGRASI TOMBOL DOWNLOAD SQL) -->
                <div id="a-page-database" class="a-page hidden space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800">Database & SQL Generator Audit</h2>
                            <p class="text-xs text-slate-400 font-medium">Bandingkan skema fisik database SQL dengan state objek data JSON yang aktif saat ini.</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="downloadSQLScript()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-lg shadow-indigo-150">
                                <i data-lucide="download" class="w-4 h-4"></i> Unduh File SQL
                            </button>
                            <button onclick="generateSQLInserts()" class="bg-slate-800 hover:bg-black text-white px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-lg">
                                <i data-lucide="terminal" class="w-4 h-4"></i> Muat Ulang Skrip SQL
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- JSON State Inspector -->
                        <div class="glass-card p-6 rounded-3xl space-y-4">
                            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <i data-lucide="braces" class="text-emerald-500"></i> JSON Real-Time State Inspector
                            </h4>
                            <div class="bg-slate-900 rounded-2xl p-4 text-xs font-mono text-emerald-400 overflow-y-auto max-h-[450px]">
                                <pre id="db-json-preview">Loading...</pre>
                            </div>
                        </div>

                        <!-- DDL & INSERT Generator Panel -->
                        <div class="glass-card p-6 rounded-3xl space-y-4">
                            <div class="flex justify-between items-center">
                                <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <i data-lucide="file-code" class="text-indigo-500"></i> SQL INSERT Script Generator
                                </h4>
                                <button onclick="copySQLScript()" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold flex items-center gap-1">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i> Salin SQL
                                </button>
                            </div>
                            <textarea id="db-sql-preview" readonly class="w-full h-[450px] bg-slate-900 text-amber-300 font-mono text-xs rounded-2xl p-4 resize-none outline-none focus:border-indigo-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Admin Guide Page (PANDUAN ADMIN DIUPDATE) -->
                <div id="a-page-guide" class="a-page hidden space-y-8">
                    <header>
                        <h2 class="text-3xl font-black text-slate-800">Panduan Penggunaan Administrator</h2>
                        <p class="text-slate-500 font-medium italic">Instruksi operasional manajemen ekosistem dan kepatuhan finansial SisaJejakUang.</p>
                    </header>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-indigo-600 space-y-3">
                            <h4 class="font-bold flex items-center gap-2"><i data-lucide="pie-chart" class="w-5 h-5 text-indigo-600"></i> Pemantauan Kepatuhan Anggaran</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Kartu **Rasio Kepatuhan Anggaran** menghitung persentase sub-anggaran pengguna yang sukses bertahan di bawah limit. Jika metrik menurun drastis di bawah 80%, pertimbangkan untuk merilis panduan penghematan global.
                            </p>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-emerald-600 space-y-3">
                            <h4 class="font-bold flex items-center gap-2"><i data-lucide="camera" class="w-5 h-5 text-emerald-600"></i> Metrik Penyerapan OCR (Scan)</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Bagian **OCR Utilization** melacak tingkat kenyamanan adopsi fitur scan struk belanja digital. Evaluasi efisiensi pemicu scan untuk memicu peningkatan produktivitas entri transaksi bagi pengguna.
                            </p>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-amber-600 space-y-3">
                            <h4 class="font-bold flex items-center gap-2"><i data-lucide="scroll-text" class="w-5 h-5 text-amber-600"></i> Log Aktivitas Sistem (Activity Log)</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Manfaatkan tabel **Log Audit Aktivitas** untuk melacak urutan peristiwa mencurigakan, perubahan alokasi finansial mendadak, atau untuk mempermudah deteksi anomali (*debugging*) operasional database simulan.
                            </p>
                        </div>
                        <div class="glass-card p-6 rounded-3xl border-l-4 border-rose-600 space-y-3">
                            <h4 class="font-bold flex items-center gap-2"><i data-lucide="database" class="w-5 h-5 text-rose-600"></i> Ekspor SQL Fisik</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Di halaman database, Administrator dapat mengunduh berkas `.sql` secara instan menggunakan tombol **Unduh File SQL**. Berkas ini dapat langsung diimpor ke sistem manajemen RDBMS seperti MySQL secara langsung.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Halaman Admin: Settings -->
                <div id="a-page-settings" class="a-page hidden space-y-6">
                    <div class="max-w-md glass-card p-8 rounded-3xl shadow-sm border border-slate-200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-indigo-50 p-2.5 rounded-2xl text-indigo-600">
                                <i data-lucide="settings" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-800 leading-none">Pengaturan Admin</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Kelola Sesi & Keamanan</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mb-6">Informasi akun administrator saat ini dan opsi penghapusan akun permanen.</p>
                        
                        <div class="space-y-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Administrator</p>
                                <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($current_user['name']) ?></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Email</p>
                                <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($current_user['email']) ?></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Peran (Role)</p>
                                <p class="text-sm font-bold text-indigo-600 uppercase font-extrabold"><?= htmlspecialchars($current_user['role']) ?></p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 space-y-3">
                            <button onclick="handleLogout()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Keluar (Logout)
                            </button>
                            <button onclick="handleDeleteAccount()" class="w-full bg-rose-50 hover:bg-rose-150 hover:text-rose-700 text-rose-600 font-bold py-3.5 px-4 rounded-2xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 border border-rose-200">
                                <i data-lucide="user-x" class="w-4 h-4"></i> Hapus Akun Permanen
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL USER: BUAT BUKU TABUNGAN -->
    <div id="modal-account" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-800">Buku Tabungan Baru</h3>
                <button onclick="closeModal('modal-account')" class="p-2 text-slate-400"><i data-lucide="x"></i></button>
            </div>
            <form id="form-account" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Buku Tabungan</label>
                    <input type="text" id="acc-name" placeholder="Contoh: Dompet Utama, Gopay, Bank BNI" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 font-semibold outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Saldo Awal (Rp)</label>
                    <input type="text" id="acc-balance" placeholder="Masukkan nominal saldo awal" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 font-bold outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl uppercase tracking-widest shadow-xl">Simpan Akun</button>
            </form>
        </div>
    </div>

    <!-- MODAL USER: TAMBAH ANGGARAN SPESIFIK -->
    <div id="modal-add-budget" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-800">Tambah Anggaran Baru</h3>
                <button onclick="closeModal('modal-add-budget')" class="p-2 text-slate-400"><i data-lucide="x"></i></button>
            </div>
            <form id="form-add-budget" class="space-y-4">
                <input type="hidden" id="add-budget-account-id">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nama Anggaran</label>
                    <input type="text" id="add-budget-name" placeholder="Contoh: Anggaran Makan, Anggaran Main" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 font-semibold outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Batas Maksimal Pengeluaran (Rp)</label>
                    <input type="text" id="add-budget-limit" placeholder="Masukkan nominal batas anggaran" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 font-bold outline-none focus:border-indigo-500">
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl uppercase tracking-widest shadow-xl">Tambahkan Anggaran</button>
            </form>
        </div>
    </div>

    <!-- MODAL USER: CATAT TRANSAKSI (Sederhana Tanpa Opsi Simpan Foto Manual) -->
    <div id="modal-transaction" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-lg shadow-2xl p-8 space-y-6">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-800">Catat Pengeluaran</h3>
                <button onclick="closeModal('modal-transaction')" class="p-2 text-slate-400"><i data-lucide="x"></i></button>
            </div>
            <form id="form-transaction" class="space-y-4">
                <input type="text" id="trx-note" placeholder="Keterangan Pengeluaran" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-rose-500">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Nominal (Rp)</label>
                        <input type="text" id="trx-amount" placeholder="Nominal" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Kategori</label>
                        <select id="trx-category" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none"></select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Buku Tabungan</label>
                        <select id="trx-account" onchange="updateBudgetDropdown()" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none"></select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Target Anggaran</label>
                        <select id="trx-budget" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none"></select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Prioritas Pengeluaran</label>
                        <select id="trx-importance" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none">
                            <option value="Kebutuhan">Kebutuhan (Need)</option>
                            <option value="Keinginan">Keinginan (Want)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Tanggal Transaksi</label>
                        <input type="date" id="trx-date" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-bold outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Bukti Struk Belanja (Foto)</label>
                    <input type="file" id="trx-receipt" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 font-semibold text-xs outline-none focus:border-rose-500">
                    <div id="trx-receipt-preview-container" class="hidden mt-2 p-2 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                        <img id="trx-receipt-preview-img" src="" alt="Preview Struk" class="w-16 h-16 object-contain rounded-lg border">
                        <button type="button" onclick="removeSelectedReceipt()" class="text-xs text-rose-500 font-bold hover:underline">Hapus Foto</button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-rose-500 text-white font-black py-4 rounded-2xl uppercase shadow-xl">Simpan</button>
            </form>
        </div>
    </div>

    <!-- MODAL USER: LIHAT BUKU STRUK -->
    <div id="modal-view-receipt" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl p-6 space-y-4">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                    <i data-lucide="receipt" class="text-indigo-600"></i> Bukti Struk Belanja
                </h3>
                <button onclick="closeModal('modal-view-receipt')" class="p-2 text-slate-400 hover:text-slate-600"><i data-lucide="x"></i></button>
            </div>
            <div class="flex justify-center bg-slate-50 p-4 rounded-2xl border border-slate-150 overflow-hidden min-h-[300px] items-center">
                <img id="receipt-viewer-img" src="" alt="Bukti Struk" class="max-w-full max-h-[450px] object-contain rounded-lg shadow-sm">
            </div>
            <button onclick="closeModal('modal-view-receipt')" class="w-full bg-slate-800 text-white font-black py-3 rounded-xl uppercase text-xs tracking-wider hover:bg-slate-950 transition-colors">Tutup Jendela</button>
        </div>
    </div>

    <!-- MODAL USER: PRATINJAU / PREVIEW PDF SEBELUM DI-DOWNLOAD -->
    <div id="modal-pdf-preview" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
        <div class="bg-white rounded-[2rem] w-full max-w-2xl shadow-2xl p-8 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-black text-slate-800">Pratinjau Laporan PDF</h3>
                    <p class="text-xs text-slate-400">Silakan periksa lembar pelaporan resmi Anda sebelum disimpan.</p>
                </div>
                <button onclick="closeModal('modal-pdf-preview')" class="p-2 text-slate-400 hover:text-slate-600"><i data-lucide="x"></i></button>
            </div>
            
            <!-- AREA CETAK FISIK (A4/Letter Layout Simulator) -->
            <div id="pdf-print-area" class="bg-white p-8 border border-slate-200 rounded-2xl shadow-inner text-slate-800 space-y-6 text-sm">
                <!-- Header Laporan Finansial Resmi -->
                <div class="flex justify-between items-start border-b-2 border-slate-300 pb-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-indigo-950 uppercase tracking-tight">SisaJejakUang</h2>
                        <p class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest">Sistem Manajemen Keuangan Digital</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-indigo-100 text-indigo-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">Laporan Resmi</span>
                        <p class="text-[10px] text-slate-400 font-bold mt-2">Dicetak: <span id="pdf-report-print-date"></span></p>
                    </div>
                </div>

                <!-- Judul Dokumen -->
                <div class="text-center space-y-1 py-2">
                    <h3 class="text-base font-black uppercase text-slate-800 tracking-wide">LAPORAN NERACA LAJUR BUKU BESAR</h3>
                    <p class="text-xs text-slate-500 font-bold">Periode Filter: <span id="pdf-report-range-date" class="text-indigo-600"></span></p>
                </div>

                <!-- Metadata Profil Pengguna -->
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                    <div>
                        <p class="text-[9px] text-slate-400 font-black uppercase">Nama Pengguna / Nasabah</p>
                        <p class="font-extrabold text-slate-800 text-sm">Najwa Anjani (Demo User)</p>
                        <p class="text-slate-400">ID Pengguna: #01-DEMO</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-slate-400 font-black uppercase">Validasi Database</p>
                        <p class="font-extrabold text-emerald-600 text-sm">Terverifikasi Sistem</p>
                        <p class="text-slate-400">Schema Match: tb_buku_tabungan</p>
                    </div>
                </div>

                <!-- Tabel Neraca Lajur Buku Besar -->
                <div class="space-y-2">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-600 font-black uppercase text-[9px] tracking-wider border-b border-slate-300">
                                <th class="py-2 px-3">Buku Tabungan / Akun</th>
                                <th class="py-2 px-3 text-right">Debit (Saldo Awal)</th>
                                <th class="py-2 px-3 text-right text-rose-600">Kredit (Pengeluaran)</th>
                                <th class="py-2 px-3 text-right text-indigo-700">Saldo Akhir</th>
                            </tr>
                        </thead>
                        <tbody id="pdf-report-tbody" class="divide-y divide-slate-200">
                            <!-- Diisi dinamis -->
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 font-black text-slate-800 border-t-2 border-slate-300">
                                <td class="py-3 px-3 uppercase text-[10px]">TOTAL KUMULATIF</td>
                                <td id="pdf-report-total-debit" class="py-3 px-3 text-right">Rp 0</td>
                                <td id="pdf-report-total-kredit" class="py-3 px-3 text-right text-rose-600">-Rp 0</td>
                                <td id="pdf-report-total-saldo" class="py-3 px-3 text-right text-indigo-700">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Panel Tombol Final -->
            <div class="flex gap-4">
                <button onclick="closeModal('modal-pdf-preview')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 rounded-2xl uppercase text-xs tracking-wider transition-all">Batal</button>
                <button onclick="downloadLedgerPDF()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3.5 rounded-2xl uppercase text-xs tracking-wider shadow-lg shadow-indigo-100 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="file-down" class="w-4 h-4"></i> Unduh PDF Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="fixed bottom-10 right-1/2 translate-x-1/2 md:translate-x-0 md:right-10 z-[100] transition-all duration-500 translate-y-32">
        <div class="bg-slate-800 text-white px-8 py-4 rounded-full shadow-2xl flex items-center gap-4">
            <span id="toast-icon"></span>
            <span id="toast-message" class="font-bold text-sm"></span>
        </div>
    </div>

    <script>
        // --- DATA STATE (STRUKTUR DATABASE REVISI TERBARU) ---
        let accounts = [];
        let transactions = [];
        let categories = []; // Menyimpan objek kategori (jenis_kategori & id_user)
        let currentRole = 'user';

        // --- DISMISSED ALERTS STATE ---
        let dismissedAlerts = [];

        // --- GENERAL LEDGER ACTIVE ACCOUNT ---
        let activeLedgerAccountId = null;

        // --- EDIT TRANSACTION STATE ---
        let editingTrxId = null;

        // --- SESSION USER ID ---
        const DEMO_USER_ID = <?= $_SESSION['user_id'] ?>;  // ID dinamis dari session PHP
        const ADMIN_USER_ID = 1;

        // --- SORTING STATE ---
        let trxSortKey = 'date'; // 'date' atau 'amount'
        let trxSortOrder = 'desc'; // 'asc' atau 'desc'

        let userSortKey = 'balance'; // 'name' atau 'balance'
        let userSortOrder = 'desc'; // 'asc' atau 'desc'

        // --- STRUK STATE ---
        let selectedReceiptBase64 = '';

        // --- ACCORDION STATE (BUKU TABUNGAN) ---
        let openBudgetsDropdowns = [];

        const MOCK_ADMIN_USERS = [
            { name: 'Najwa Anjani', balance: 4500000 },
            { name: 'Meisa Zahrah', balance: 3800000 }
        ];

        // --- SYSTEM LOGGER HELPER (FITUR REKOMENDASI TERBARU) ---
        function sysLog(actor, action, status = 'Sukses') {
            const logs = JSON.parse(localStorage.getItem('ssju_v6_system_logs')) || [];
            logs.unshift({
                timestamp: new Date().toISOString(),
                actor: actor,
                action: action,
                status: status
            });
            if (logs.length > 30) logs.pop(); // Batasi 30 log teranyar
            localStorage.setItem('ssju_v6_system_logs', JSON.stringify(logs));
        }

        // --- INIT APP ---
        document.addEventListener('DOMContentLoaded', async () => {
            await loadData();
            
            setupReceiptUploadListener();
            setupLedgerListeners();
            setupCurrencyInputListeners();
            
            // Set role awal berdasarkan session PHP
            const userRole = "<?= $_SESSION['role'] ?>";
            switchRole(userRole === 'admin' ? 'admin' : 'user');
            
            updateAllUI();
            lucide.createIcons();
        });

        // Seed data kategori awal dengan relasi dan ENUM non-nullable
        function seedDefaultCategories() {
            categories = [
                { id: 1, id_user: ADMIN_USER_ID, name: 'Makanan', type: 'master', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() },
                { id: 2, id_user: ADMIN_USER_ID, name: 'Transportasi', type: 'master', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() },
                { id: 3, id_user: ADMIN_USER_ID, name: 'Belanja Utama', type: 'master', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() },
                { id: 4, id_user: ADMIN_USER_ID, name: 'Hiburan', type: 'master', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() }
            ];
            sysLog('Sistem', 'Melakukan seeding data kategori bawaan standar.');
            saveData();
        }

        function seedDefaultUserFiles() {
            accounts = [
                { 
                    id: 101, 
                    id_user: DEMO_USER_ID,
                    name: 'Rekening Bank BNI', 
                    initial: 2500000, 
                    current: 2000000, 
                    budgets: [
                        { id: 1, id_buku: 101, name: 'Anggaran Makan', limit: 800000, createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() },
                        { id: 2, id_buku: 101, name: 'Anggaran Main', limit: 400000, createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() }
                    ],
                    createdAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString()
                },
                { 
                    id: 102, 
                    id_user: DEMO_USER_ID,
                    name: 'Dompet Tunai', 
                    initial: 500000, 
                    current: 450000, 
                    budgets: [
                        { id: 3, id_buku: 102, name: 'Anggaran Jajan', limit: 150000, createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() }
                    ],
                    createdAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString()
                }
            ];
            transactions = [
                { id: 201, id_user: DEMO_USER_ID, date: new Date().toISOString(), note: 'Beli Buku Kuliah', amount: 500000, categoryId: 3, accountId: 101, accountName: 'Rekening Bank BNI', budgetId: 'free', budgetName: 'Bebas (Tanpa Anggaran)', importance: 'Kebutuhan', receipt: '', inputMethod: 'manual', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() },
                { id: 202, id_user: DEMO_USER_ID, date: new Date().toISOString(), note: 'Makan Malam', amount: 50000, categoryId: 1, accountId: 102, accountName: 'Dompet Tunai', budgetId: 3, budgetName: 'Anggaran Jajan', importance: 'Kebutuhan', receipt: '', inputMethod: 'manual', createdAt: new Date().toISOString(), updatedAt: new Date().toISOString() }
            ];
            sysLog('Sistem', 'Melakukan seeding buku tabungan dan transaksi simulan.');
            saveData();
        }

        // --- INPUT CURRENCY FORMATTER (Ribuan dengan Titik) ---
        function setupCurrencyInputListeners() {
            const inputsToFormat = ['trx-amount', 'acc-balance', 'add-budget-limit'];
            inputsToFormat.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', function() {
                        formatNumberInput(this);
                    });
                }
            });
        }

        function formatNumberInput(input) {
            let value = input.value.replace(/\D/g, "");
            if (value) {
                input.value = Number(value).toLocaleString('id-ID');
            } else {
                input.value = "";
            }
        }

        function getRawNumberValue(id) {
            const el = document.getElementById(id);
            if (!el) return 0;
            return parseFloat(el.value.replace(/\./g, '')) || 0;
        }

        // --- NAVIGATION ---
        function switchRole(role) {
            currentRole = role;
            document.getElementById('view-user').classList.toggle('hidden', role !== 'user');
            document.getElementById('view-admin').classList.toggle('hidden', role !== 'admin');
            document.getElementById('admin-sidebar').classList.toggle('hidden', role !== 'admin');
            
            const btnUser = document.getElementById('btn-mode-user');
            const btnAdmin = document.getElementById('btn-mode-admin');
            if (btnUser) {
                btnUser.className = `px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${role === 'user' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'}`;
            }
            if (btnAdmin) {
                btnAdmin.className = `px-3 py-1.5 rounded-lg text-xs font-bold transition-all ${role === 'admin' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400'}`;
            }

            if (role === 'admin') {
                showAdminPage('dashboard');
                sysLog('Admin', 'Masuk ke dalam Dashboard Administrator.');
            } else {
                showUserPage('dashboard');
            }
            lucide.createIcons();
        }

        // --- DIRECT SINGLE TRX RECEIPT UPLOAD ---
        function triggerDirectUpload(trxId) {
            const tempInput = document.createElement('input');
            tempInput.type = 'file';
            tempInput.accept = 'image/*';
            tempInput.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        showToast('Ukuran file struk maksimal adalah 2MB.', 'error');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        const base64Data = evt.target.result;
                        const trxIdx = transactions.findIndex(t => t.id === parseFloat(trxId) || t.id == trxId);
                        if (trxIdx !== -1) {
                            transactions[trxIdx].receipt = base64Data;
                            transactions[trxIdx].updatedAt = new Date().toISOString();
                            sysLog('User', `Melampirkan foto struk belanja untuk ID Transaksi #${trxId}`);
                            saveAndRefresh();
                            showToast('Foto struk berhasil ditambahkan!', 'success');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            };
            tempInput.click();
        }

        function showUserPage(pid) {
            document.querySelectorAll('.u-page').forEach(p => p.classList.add('hidden'));
            document.getElementById(`u-page-${pid}`).classList.remove('hidden');
            document.querySelectorAll('.user-nav').forEach(btn => {
                const isActive = btn.dataset.userPage === pid;
                btn.className = `user-nav px-6 py-2 rounded-xl text-sm font-bold transition-all ${isActive ? 'bg-white shadow-md text-indigo-600' : 'text-slate-400'}`;
            });
            if (pid === 'transactions') {
                renderUserCategoriesList();
                initLedgerDates();
                renderUserLedger();
            }
        }

        function showAdminPage(pid) {
            document.querySelectorAll('.a-page').forEach(p => p.classList.add('hidden'));
            const activePage = document.getElementById(`a-page-${pid}`);
            if(activePage) activePage.classList.remove('hidden');
            
            document.querySelectorAll('.admin-nav').forEach(btn => {
                const isActive = btn.dataset.adminPage === pid;
                btn.className = `admin-nav w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all ${isActive ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-50'}`;
            });

            if (pid === 'categories') renderAdminMasterCategories();
            if (pid === 'database') renderDatabaseInspector();
            updateAdminData();
            lucide.createIcons();
        }

        // --- DATABASE INSPECTOR & SQL GENERATOR ---
        function renderDatabaseInspector() {
            const fullState = {
                tb_user: [
                    { id_user: DEMO_USER_ID, nama_lengkap: 'Najwa Anjani (User Demo)', email: 'najwa@email.ac.id', role: 'user' },
                    { id_user: ADMIN_USER_ID, nama_lengkap: 'Admin', email: 'admin@email.com', role: 'admin' }
                ],
                tb_buku_tabungan: accounts.map(a => ({
                    id_buku: a.id, id_user: a.id_user, nama_buku: a.name, saldo_awal: a.initial, saldo_saat_ini: a.current, createdAt: a.createdAt, updatedAt: a.updatedAt
                })),
                tb_anggaran: accounts.flatMap(a => (a.budgets || []).map(b => ({
                    id_anggaran: b.id, id_buku: b.id_buku, nama_anggaran: b.name, batas_limit: b.limit, createdAt: b.createdAt, updatedAt: b.updatedAt
                }))),
                tb_kategori: categories.map(c => ({
                    id_kategori: c.id, id_user: c.id_user, nama_kategori: c.name, jenis_kategori: c.type, createdAt: c.createdAt, updatedAt: c.updatedAt
                })),
                tb_transaksi: transactions.map(t => ({
                    id_transaksi: t.id, id_user: t.id_user, id_buku: t.accountId, id_anggaran: t.budgetId, id_kategori: t.categoryId, tanggal_transaksi: t.date, keterangan: t.note, nominal: t.amount, prioritas: t.importance, bukti_pengeluaran: t.receipt ? '[Base64 Image String]' : 'NULL'
                }))
            };
            
            const jsonView = document.getElementById('db-json-preview');
            if (jsonView) {
                jsonView.innerText = JSON.stringify(fullState, null, 2);
            }
            generateSQLInserts();
        }

        function generateSQLInserts() {
            let sql = `-- ===============================================\n`;
            sql += `-- SISAJEJAKUANG SQL DATA INSERT EXPORT GENERATOR\n`;
            sql += `-- Digenerasikan secara otomatis berdasarkan data aktif\n`;
            sql += `-- ===============================================\n\n`;

            // tb_user
            sql += `INSERT INTO tb_user (id_user, nama_lengkap, email, password, role) VALUES\n`;
            sql += `(${DEMO_USER_ID}, 'Najwa Anjani', 'najwa@email.com', '$2y$10$xyz...', 'user'),\n`;
            sql += `(${ADMIN_USER_ID}, 'Admin', 'admin@email.com', '$2y$10$abc...', 'admin');\n\n`;

            // tb_buku_tabungan
            if (accounts.length > 0) {
                sql += `INSERT INTO tb_buku_tabungan (id_buku, id_user, nama_buku, saldo_awal, saldo_saat_ini, created_at, updated_at) VALUES\n`;
                const accRows = accounts.map(a => `(${a.id}, ${a.id_user}, '${a.name}', ${a.initial}, ${a.current}, '${a.createdAt}', '${a.updatedAt}')`).join(',\n');
                sql += accRows + ';\n\n';
            }

            // tb_anggaran
            const flatBudgets = accounts.flatMap(a => a.budgets || []);
            if (flatBudgets.length > 0) {
                sql += `INSERT INTO tb_anggaran (id_anggaran, id_buku, nama_anggaran, batas_limit, created_at, updated_at) VALUES\n`;
                const budgetRows = flatBudgets.map(b => `(${b.id}, ${b.id_buku}, '${b.name}', ${b.limit}, '${b.createdAt}', '${b.updatedAt}')`).join(',\n');
                sql += budgetRows + ';\n\n';
            }

            // tb_kategori
            if (categories.length > 0) {
                sql += `INSERT INTO tb_kategori (id_kategori, id_user, nama_kategori, jenis_kategori, created_at, updated_at) VALUES\n`;
                const catRows = categories.map(c => `(${c.id}, ${c.id_user}, '${c.name}', '${c.type}', '${c.createdAt}', '${c.updatedAt}')`).join(',\n');
                sql += catRows + ';\n\n';
            }

            // tb_transaksi
            if (transactions.length > 0) {
                sql += `INSERT INTO tb_transaksi (id_transaksi, id_user, id_buku, id_anggaran, id_kategori, tanggal_transaksi, keterangan, nominal, prioritas, bukti_pengeluaran) VALUES\n`;
                const trxRows = transactions.map(t => {
                    const budgetIdVal = t.budgetId === 'free' ? 'NULL' : t.budgetId;
                    const receiptVal = t.receipt ? `'[BASE64_RECEIPT_IMAGE]'` : 'NULL';
                    return `(${t.id}, ${t.id_user}, ${t.accountId}, ${budgetIdVal}, ${t.categoryId}, '${t.date}', '${t.note.replace(/'/g, "''")}', ${t.amount}, '${t.importance}', ${receiptVal})`;
                }).join(',\n');
                sql += trxRows + ';\n';
            }

            const sqlPreview = document.getElementById('db-sql-preview');
            if (sqlPreview) {
                sqlPreview.value = sql;
            }
        }

        function copySQLScript() {
            const sqlPreview = document.getElementById('db-sql-preview');
            if (sqlPreview) {
                sqlPreview.select();
                document.execCommand('copy');
                sysLog('Admin', 'Menyalin skrip dump SQL otomatis.');
                showToast('Skrip SQL berhasil disalin ke clipboard!', 'success');
            }
        }

        // --- DOWNLOAD FILE SQL (FITUR REKOMENDASI TERBARU) ---
        function downloadSQLScript() {
            generateSQLInserts();
            const sqlText = document.getElementById('db-sql-preview').value;
            const blob = new Blob([sqlText], { type: 'text/plain;charset=utf-8' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'SisaJejakUang_Database_Dump.sql';
            sysLog('Admin', 'Melakukan ekspor dump SQL sistem ke berkas fisik (.sql).');
            link.click();
            showToast('Berkas SQL berhasil diunduh!', 'success');
        }

        // --- MODAL UTILITIES ---
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('hidden');
            if (id === 'modal-transaction') {
                resetTransactionFormUpload();
                editingTrxId = null;
            }
        }

        function openNewTransactionModal() {
            editingTrxId = null;
            document.querySelector('#modal-transaction h3').innerText = 'Catat Pengeluaran';
            document.querySelector('#form-transaction button[type="submit"]').innerText = 'Simpan';
            
            document.getElementById('form-transaction').reset();
            resetTransactionFormUpload();
            
            // Set default date to today
            const dateInput = document.getElementById('trx-date');
            if (dateInput) {
                dateInput.value = new Date().toISOString().split('T')[0];
            }
            
            openModal('modal-transaction');
        }

        function openAddBudgetModal(accountId) {
            document.getElementById('add-budget-account-id').value = accountId;
            openModal('modal-add-budget');
        }

        // --- ACCORDION BUKU TABUNGAN ---
        function toggleBudgetsDropdown(accId) {
            if (openBudgetsDropdowns.includes(accId)) {
                openBudgetsDropdowns = [];
            } else {
                openBudgetsDropdowns = [accId]; 
            }
            renderUserAccounts();
        }

        // --- DISMISS DASHBOARD ALERT ---
        function dismissAlert(key) {
            dismissedAlerts.push(key);
            renderUserDashboard();
            showToast('Peringatan disembunyikan.', 'info');
        }

        // --- SORTING MECHANISMS ---
        function handleTrxSort(key) {
            if (trxSortKey === key) {
                trxSortOrder = trxSortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                trxSortKey = key;
                trxSortOrder = 'desc';
            }
            saveAndRefresh();
            showToast(`Transaksi diurutkan berdasarkan ${key === 'date' ? 'Waktu' : 'Nominal'} (${trxSortOrder === 'asc' ? 'Naik' : 'Turun'})`, 'info');
        }

        function handleUserSort(key) {
            if (userSortKey === key) {
                userSortOrder = userSortOrder === 'asc' ? 'desc' : 'asc';
            } else {
                userSortKey = key;
                userSortOrder = 'desc';
            }
            saveAndRefresh();
            showToast(`Pengguna diurutkan berdasarkan ${key === 'name' ? 'Nama' : 'Saldo'} (${userSortOrder === 'asc' ? 'Naik' : 'Turun'})`, 'info');
        }

        function getSortedTransactions() {
            return [...transactions].sort((a, b) => {
                let valA, valB;
                if (trxSortKey === 'date') {
                    valA = new Date(a.date).getTime();
                    valB = new Date(b.date).getTime();
                } else if (trxSortKey === 'amount') {
                    valA = a.amount;
                    valB = b.amount;
                }
                
                if (trxSortOrder === 'asc') {
                    return valA - valB;
                } else {
                    return valB - valA;
                }
            });
        }

        function updateSortIndicators() {
            const arrow = trxSortOrder === 'asc' ? ' ▲' : ' ▼';
            safeSetText('sort-trx-date', trxSortKey === 'date' ? arrow : '');
            safeSetText('sort-trx-amount', trxSortKey === 'amount' ? arrow : '');
            safeSetText('sort-recent-date', trxSortKey === 'date' ? arrow : '');
            safeSetText('sort-recent-amount', trxSortKey === 'amount' ? arrow : '');

            const userArrow = userSortOrder === 'asc' ? ' ▲' : ' ▼';
            safeSetText('sort-user-name', userSortKey === 'name' ? userArrow : '');
            safeSetText('sort-user-balance', userSortKey === 'balance' ? userArrow : '');
        }

        // --- FITUR UPLOAD STRUK ---
        function setupReceiptUploadListener() {
            selectedReceiptBase64 = '';
            const receiptInput = document.getElementById('trx-receipt');
            if (receiptInput) {
                receiptInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.size > 2 * 1024 * 1024) {
                            showToast('Ukuran file struk maksimal adalah 2MB.', 'error');
                            this.value = '';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            selectedReceiptBase64 = evt.target.result;
                            const previewContainer = document.getElementById('trx-receipt-preview-container');
                            const previewImg = document.getElementById('trx-receipt-preview-img');
                            if (previewContainer && previewImg) {
                                previewImg.src = selectedReceiptBase64;
                                previewContainer.classList.remove('hidden');
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        function removeSelectedReceipt() {
            const receiptInput = document.getElementById('trx-receipt');
            if (receiptInput) receiptInput.value = '';
            selectedReceiptBase64 = '';
            const previewContainer = document.getElementById('trx-receipt-preview-container');
            const previewImg = document.getElementById('trx-receipt-preview-img');
            if (previewContainer) previewContainer.classList.add('hidden');
            if (previewImg) previewImg.src = '';
        }

        function resetTransactionFormUpload() {
            removeSelectedReceipt();
        }

        function viewReceipt(trxId) {
            const trx = transactions.find(t => t.id === parseFloat(trxId) || t.id == trxId);
            if (trx && trx.receipt) {
                const viewerImg = document.getElementById('receipt-viewer-img');
                if (viewerImg) {
                    viewerImg.src = trx.receipt;
                    openModal('modal-view-receipt');
                }
            } else {
                showToast('Transaksi ini tidak memiliki bukti struk.', 'error');
            }
        }

        // --- USER KATEGORI (KUSTOM) ---
        function renderUserCategoriesList() {
            const container = document.getElementById('user-categories-list');
            if(!container) return;
            
            const userCustomCats = categories.filter(c => c.type === 'kustom' && c.id_user === DEMO_USER_ID);
            container.innerHTML = userCustomCats.map(cat => `
                <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-[11px] font-bold px-3 py-1.5 rounded-xl">
                    ${cat.name}
                    <button onclick="deleteUserCategory(${cat.id})" class="text-indigo-400 hover:text-rose-600 font-bold ml-1 text-[10px]">✕</button>
                </span>
            `).join('');
        }

        document.getElementById('form-user-category').onsubmit = async (e) => {
            e.preventDefault();
            const input = document.getElementById('user-cat-name');
            const name = input.value.trim();

            if (!name) return;

            try {
                const res = await fetch('api/kategori.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama_kategori: name })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast(`Kategori kustom "${name}" berhasil ditambahkan!`, 'success');
                    input.value = '';
                    await saveAndRefresh();
                    renderUserCategoriesList();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Gagal terhubung ke server.', 'error');
            }
        };

        async function deleteUserCategory(id) {
            if (confirm('Hapus kategori kustom ini?')) {
                try {
                    const res = await fetch('api/kategori.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        showToast('Kategori berhasil dihapus.', 'info');
                        await saveAndRefresh();
                        renderUserCategoriesList();
                    } else {
                        showToast(data.message, 'error');
                    }
                } catch (err) {
                    showToast('Gagal terhubung ke server.', 'error');
                }
            }
        }

        // --- ADMIN MASTER KATEGORI ---
        function renderAdminMasterCategories() {
            const list = document.getElementById('a-categories-list');
            if(!list) return;

            const masterCats = categories.filter(c => c.type === 'master');
            list.innerHTML = masterCats.map(cat => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-bold text-slate-800">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                            ${cat.name}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                        Dibuat: ${formatDate(cat.createdAt)}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="deleteAdminCategory(${cat.id})" class="text-slate-200 hover:text-rose-500"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                </tr>
            `).join('');
            lucide.createIcons();
        }

        document.getElementById('form-admin-category').onsubmit = async (e) => {
            e.preventDefault();
            const input = document.getElementById('admin-cat-name');
            const name = input.value.trim();

            if (!name) return;

            try {
                const res = await fetch('api/kategori.php?action=create_master', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama_kategori: name })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Kategori Master Global berhasil ditambahkan!', 'success');
                    input.value = '';
                    await saveAndRefresh();
                    renderAdminMasterCategories();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Gagal terhubung ke server.', 'error');
            }
        };

        async function deleteAdminCategory(id) {
            if (confirm('Hapus kategori master global ini?')) {
                try {
                    const res = await fetch('api/kategori.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        showToast('Kategori Master berhasil dihapus.', 'info');
                        await saveAndRefresh();
                        renderAdminMasterCategories();
                    } else {
                        showToast(data.message, 'error');
                    }
                } catch (err) {
                    showToast('Gagal terhubung ke server.', 'error');
                }
            }
        }

        // --- MANAGE ANGGARAN MANDIRI BARU ---
        document.getElementById('form-add-budget').onsubmit = async (e) => {
            e.preventDefault();
            const accountId = parseInt(document.getElementById('add-budget-account-id').value);
            const name = document.getElementById('add-budget-name').value.trim();
            const limit = getRawNumberValue('add-budget-limit');

            if (!name || limit <= 0) return;

            // Validasi client-side: anggaran tidak boleh melebihi saldo saat ini dari buku tabungan
            const targetAcc = accounts.find(a => a.id === accountId);
            if (targetAcc && limit > targetAcc.current) {
                showToast(`Gagal: Batas anggaran (${formatIDR(limit)}) melebihi saldo saat ini (${formatIDR(targetAcc.current)}) dari buku tabungan.`, 'error');
                return;
            }

            try {
                const res = await fetch('api/anggaran.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        buku_tabungan_id: accountId,
                        nama_anggaran: name,
                        batas_limit: limit
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast(`Anggaran "${name}" berhasil ditambahkan!`, 'success');
                    closeModal('modal-add-budget');
                    e.target.reset();
                    await saveAndRefresh();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Gagal terhubung ke server.', 'error');
            }
        };

        async function deleteBudget(accountId, budgetId) {
            if (confirm('Hapus anggaran ini? Transaksi yang sudah dialokasikan akan dipindahkan ke kategori Bebas.')) {
                try {
                    const res = await fetch('api/anggaran.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: budgetId })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        showToast('Anggaran telah dihapus.', 'info');
                        await saveAndRefresh();
                    } else {
                        showToast(data.message, 'error');
                    }
                } catch (err) {
                    showToast('Gagal terhubung ke server.', 'error');
                }
            }
        }

        // --- ACCOUNT & TRANSAKSI ---
        document.getElementById('form-account').onsubmit = async (e) => {
            e.preventDefault();
            const name = document.getElementById('acc-name').value;
            const balance = getRawNumberValue('acc-balance');
            
            if (!name || balance < 0) return;

            try {
                const res = await fetch('api/buku_tabungan.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        nama_buku: name,
                        saldo_awal: balance
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Buku tabungan berhasil disimpan!', 'success');
                    closeModal('modal-account');
                    e.target.reset();
                    await saveAndRefresh();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Gagal terhubung ke server.', 'error');
            }
        };

        async function deleteTransaction(id) {
            if (confirm('Apakah Anda yakin ingin menghapus transaksi ini? Saldo tabungan akan dikembalikan.')) {
                try {
                    const res = await fetch('api/transaksi.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });
                    const data = await res.json();
                    if (data.status === 'success') {
                        showToast(data.message, 'info');
                        await saveAndRefresh();
                    } else {
                        showToast(data.message, 'error');
                    }
                } catch (err) {
                    showToast('Gagal menghapus transaksi dari server.', 'error');
                }
            }
        }

        // --- FITUR EDIT TRANSAKSI ---
        function editTransaction(id) {
            const trx = transactions.find(t => t.id === id);
            if (!trx) return;

            editingTrxId = id;
            document.getElementById('trx-note').value = trx.note;
            
            const amountInput = document.getElementById('trx-amount');
            amountInput.value = Number(trx.amount).toLocaleString('id-ID');

            document.getElementById('trx-category').value = trx.category || categories.find(c => c.id === trx.categoryId)?.name || '';
            document.getElementById('trx-importance').value = trx.importance;
            document.getElementById('trx-account').value = trx.accountId;
            updateBudgetDropdown();
            document.getElementById('trx-budget').value = trx.budgetId;
            
            // Set date input value
            document.getElementById('trx-date').value = trx.date || new Date().toISOString().split('T')[0];

            // Set receipt preview
            const previewContainer = document.getElementById('trx-receipt-preview-container');
            const previewImg = document.getElementById('trx-receipt-preview-img');
            document.getElementById('trx-receipt').value = '';
            if (trx.receipt) {
                selectedReceiptBase64 = trx.receipt;
                if (previewContainer && previewImg) {
                    previewImg.src = trx.receipt;
                    previewContainer.classList.remove('hidden');
                }
            } else {
                selectedReceiptBase64 = '';
                if (previewContainer) previewContainer.classList.add('hidden');
            }

            document.querySelector('#modal-transaction h3').innerText = 'Edit Pengeluaran';
            document.querySelector('#form-transaction button[type="submit"]').innerText = 'Update Pengeluaran';

            openModal('modal-transaction');
        }

        document.getElementById('form-transaction').onsubmit = async (e) => {
            e.preventDefault();
            const note = document.getElementById('trx-note').value;
            const amount = getRawNumberValue('trx-amount');
            const categoryName = document.getElementById('trx-category').value;
            const importance = document.getElementById('trx-importance').value;
            const accountId = parseInt(document.getElementById('trx-account').value);
            const budgetIdVal = document.getElementById('trx-budget').value;
            const trxDate = document.getElementById('trx-date').value || new Date().toISOString().split('T')[0];

            const selectedCat = categories.find(c => c.name === categoryName) || categories[0];
            const targetBudgetId = budgetIdVal === 'free' ? null : parseInt(budgetIdVal);

            try {
                if (editingTrxId !== null) {
                    // Hapus dulu transaksi lama
                    const resDel = await fetch('api/transaksi.php?action=delete', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: editingTrxId })
                    });
                    const jsonDel = await resDel.json();
                    if (jsonDel.status !== 'success') {
                        showToast(jsonDel.message, 'error');
                        return;
                    }
                    editingTrxId = null;
                }

                // Kirim transaksi baru ke database
                const payload = {
                    buku_tabungan_id: accountId,
                    anggaran_id: targetBudgetId,
                    kategori_id: selectedCat.id,
                    tanggal_transaksi: trxDate,
                    keterangan: note,
                    nominal: amount,
                    prioritas: importance,
                    input_method: selectedReceiptBase64 ? 'ocr' : 'manual',
                    bukti_pengeluaran: selectedReceiptBase64
                };

                const res = await fetch('api/transaksi.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.status === 'success') {
                    showToast(data.message, 'success');
                    if (data.budget_warning) {
                        alert(data.budget_warning_message);
                    }
                    selectedReceiptBase64 = '';
                    closeModal('modal-transaction');
                    e.target.reset();
                    await saveAndRefresh();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (err) {
                showToast('Gagal mencatat transaksi ke server.', 'error');
            }
        };

        function simulateOCR() {
            showToast('Memindai struk belanja...', 'info');
            setTimeout(() => {
                const mockReceipt = `data:image/svg+xml;utf8,` + encodeURIComponent(`
                <svg xmlns="http://www.w3.org/2000/svg" width="300" height="420" viewBox="0 0 300 420">
                    <rect width="300" height="420" fill="#fff" rx="10" stroke="#cbd5e1" stroke-width="2"/>
                    <path d="M 0 405 L 15 420 L 30 405 L 45 420 L 60 405 L 75 420 L 90 405 L 105 420 L 120 405 L 135 420 L 150 405 L 165 420 L 180 405 L 195 420 L 210 405 L 225 420 L 240 405 L 255 420 L 270 405 L 285 420 L 300 405 L 300 0 L 0 0 Z" fill="#ffffff" />
                    <text x="150" y="50" font-family="Courier New, monospace" font-size="18" font-weight="bold" text-anchor="middle" fill="#1e293b">STRUK BELANJA OCR</text>
                    <text x="150" y="75" font-family="Courier New, monospace" font-size="12" text-anchor="middle" fill="#64748b">SisaJejakUang Smart Scanner</text>
                    <line x1="20" y1="100" x2="280" y2="100" stroke="#cbd5e1" stroke-dasharray="5,5" />
                    <text x="20" y="130" font-family="Courier New, monospace" font-size="13" fill="#334155">Detail Item: Makan Siang OCR</text>
                    <text x="20" y="160" font-family="Courier New, monospace" font-size="13" fill="#334155">Kategori: Makanan</text>
                    <text x="20" y="190" font-family="Courier New, monospace" font-size="13" fill="#334155">Prioritas: Kebutuhan</text>
                    <text x="20" y="220" font-family="Courier New, monospace" font-size="13" fill="#334155">Status Struk: TERVERIFIKASI</text>
                    <line x1="20" y1="240" x2="280" y2="240" stroke="#cbd5e1" stroke-dasharray="5,5" />
                    <text x="20" y="280" font-family="Courier New, monospace" font-size="15" font-weight="bold" fill="#0f172a">TOTAL BELANJA</text>
                    <text x="280" y="280" font-family="Courier New, monospace" font-size="15" font-weight="bold" text-anchor="end" fill="#b91c1c">Rp 45.000</text>
                    <text x="150" y="360" font-family="Courier New, monospace" font-size="11" text-anchor="middle" fill="#94a3b8">-- Bukti Transaksi Digital --</text>
                </svg>`);

                document.getElementById('trx-note').value = 'Makan Siang OCR';
                
                const amountInput = document.getElementById('trx-amount');
                amountInput.value = Number(45000).toLocaleString('id-ID');
                
                document.getElementById('trx-importance').value = 'Kebutuhan';
                
                // Set default date to today
                document.getElementById('trx-date').value = new Date().toISOString().split('T')[0];
                
                selectedReceiptBase64 = mockReceipt;
                
                // Show OCR receipt preview
                const previewContainer = document.getElementById('trx-receipt-preview-container');
                const previewImg = document.getElementById('trx-receipt-preview-img');
                if (previewContainer && previewImg) {
                    previewImg.src = mockReceipt;
                    previewContainer.classList.remove('hidden');
                }

                openModal('modal-transaction');
                sysLog('User', 'Memicu simulasi scan struk OCR belanja untuk "Makan Siang OCR" senilai Rp 45.000');
                showToast('OCR Berhasil membaca data dan menyusun struk!', 'success');
            }, 1000);
        }

        // --- SAFE DOM MANIPULATION HELPERS ---
        function safeSetText(id, val) {
            const el = document.getElementById(id);
            if (el) el.innerText = val;
        }

        function safeSetHTML(id, val) {
            const el = document.getElementById(id);
            if (el) el.innerHTML = val;
        }

        // --- REFRESH DISPLAY ---
        function updateAllUI() {
            renderUserDashboard();
            renderUserAccounts();
            renderUserTransactions();
            renderUserLedger(); 
            populateSelectOptions();
            updateSortIndicators();
        }

        function renderUserDashboard() {
            const currentTotalBal = accounts.reduce((s, a) => s + a.current, 0);
            const totalExpense = transactions.reduce((s, t) => s + t.amount, 0);
            const totalBudgetLimits = accounts.reduce((sum, a) => sum + (a.budgets ? a.budgets.reduce((s, b) => s + b.limit, 0) : 0), 0);
            const initial = accounts.reduce((s, a) => s + a.initial, 0);
            
            const score = initial > 0 ? Math.round((1 - (totalExpense / initial)) * 100) : 0;
            const hScore = document.getElementById('u-health-score');
            const hBar = document.getElementById('u-health-bar');
            const hDesc = document.getElementById('u-health-desc');
            
            if(hScore) hScore.innerText = score;
            if(hBar) hBar.style.width = score + '%';
            if(hDesc) hDesc.innerText = score > 60 ? 'Keuangan Anda sangat sehat!' : 'Hati-hati dengan pengeluaran Anda.';

            safeSetText('u-stat-balance', formatIDR(currentTotalBal));
            safeSetText('u-stat-expense', formatIDR(totalExpense));
            safeSetText('u-stat-budget-used', `${formatIDR(totalBudgetLimits)}`);

            const needs = transactions.filter(t => t.importance === 'Kebutuhan').reduce((s, t) => s + t.amount, 0);
            const wants = transactions.filter(t => t.importance === 'Keinginan').reduce((s, t) => s + t.amount, 0);
            safeSetText('u-stat-needs', formatIDR(needs));
            safeSetText('u-stat-wants', formatIDR(wants));

            const alertContainer = document.getElementById('u-dashboard-alerts');
            if (alertContainer) {
                let alertsHtml = '';

                if (totalBudgetLimits > 0 && totalExpense > totalBudgetLimits && !dismissedAlerts.includes('limit-exceeded')) {
                    alertsHtml += `
                        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-2xl flex items-start justify-between gap-3 shadow-sm mb-2">
                            <div class="flex items-start gap-3">
                                <div class="text-rose-500 mt-0.5"><i data-lucide="alert-triangle" class="w-5 h-5"></i></div>
                                <div>
                                    <h4 class="font-bold text-rose-800 text-sm">Pengeluaran Bulan Ini Berlebih!</h4>
                                    <p class="text-xs text-rose-600 mt-0.5">Total pengeluaran Anda (${formatIDR(totalExpense)}) saat ini telah melampaui total batas anggaran yang direncanakan (${formatIDR(totalBudgetLimits)}).</p>
                                </div>
                            </div>
                            <button onclick="dismissAlert('limit-exceeded')" class="text-rose-400 hover:text-rose-800 transition-colors p-1" title="Sembunyikan Peringatan">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    `;
                }

                if (initial > 0 && currentTotalBal < (0.1 * initial) && !dismissedAlerts.includes('low-balance')) {
                    alertsHtml += `
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-2xl flex items-start justify-between gap-3 shadow-sm mb-2">
                            <div class="flex items-start gap-3">
                                <div class="text-amber-500 mt-0.5"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
                                <div>
                                    <h4 class="font-bold text-amber-800 text-sm">Total Saldo Kritis (&lt; 10%)!</h4>
                                    <p class="text-xs text-amber-600 mt-0.5">Sisa dana Anda (${formatIDR(currentTotalBal)}) kurang dari 10% dari saldo awal keseluruhan (${formatIDR(initial)}). Mohon kurangi pengeluaran non-primer.</p>
                                </div>
                            </div>
                            <button onclick="dismissAlert('low-balance')" class="text-amber-400 hover:text-amber-800 transition-colors p-1" title="Sembunyikan Peringatan">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    `;
                }

                let lowBudgets = [];
                accounts.forEach(acc => {
                    if (acc.budgets && acc.budgets.length > 0) {
                        acc.budgets.forEach(b => {
                            const spentOnB = transactions
                                .filter(t => t.accountId === acc.id && t.budgetId === b.id)
                                .reduce((s, t) => s + t.amount, 0);
                            const remaining = b.limit - spentOnB;
                            const alertKey = `low-budget-${acc.id}-${b.id}`;
                            if (remaining < (0.1 * b.limit) && !dismissedAlerts.includes(alertKey)) {
                                lowBudgets.push({ accName: acc.name, bName: b.name, remaining, key: alertKey });
                            }
                        });
                    }
                });

                if (lowBudgets.length > 0) {
                    lowBudgets.forEach(lb => {
                        alertsHtml += `
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl flex items-start justify-between gap-3 shadow-sm mb-2">
                                <div class="flex items-start gap-3">
                                    <div class="text-red-500 mt-0.5"><i data-lucide="shield-alert" class="w-5 h-5"></i></div>
                                    <div>
                                        <h4 class="font-bold text-red-800 text-sm">Batas Anggaran Menipis (&lt; 10%)!</h4>
                                        <p class="text-xs text-red-600 mt-0.5">Alokasi dana untuk anggaran <b>${lb.bName}</b> (${lb.accName}) tersisa kurang dari 10% (Sisa saku: ${formatIDR(lb.remaining)}).</p>
                                    </div>
                                </div>
                                <button onclick="dismissAlert('${lb.key}')" class="text-red-400 hover:text-red-800 transition-colors p-1" title="Sembunyikan Peringatan">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        `;
                    });
                }

                if (alertsHtml) {
                    alertContainer.innerHTML = alertsHtml;
                    alertContainer.classList.remove('hidden');
                } else {
                    alertContainer.innerHTML = '';
                    alertContainer.classList.add('hidden');
                }
            }

            const list = document.getElementById('u-recent-list');
            if(list) {
                const sortedTrx = getSortedTransactions();
                list.innerHTML = sortedTrx.slice(0, 5).map(t => `
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <b>${t.note}</b>
                                ${t.receipt ? `<button onclick="viewReceipt('${t.id}')" class="text-indigo-600 hover:text-indigo-800 focus:outline-none" title="Lihat Struk"><i data-lucide="image" class="w-4 h-4 inline"></i></button>` : ''}
                            </div>
                            <small class="text-slate-400 font-semibold">${t.accountName}</small>
                            <span class="text-[9px] text-indigo-500 font-bold ml-1">${formatDate(t.date)}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-indigo-50 text-indigo-700 font-bold px-2 py-1 rounded-lg text-[10px] mr-2">${t.category}</span>
                            <span class="font-black text-rose-500">-${formatIDR(t.amount)}</span>
                        </td>
                    </tr>
                `).join('');
            }
            lucide.createIcons();
        }

        function renderUserAccounts() {
            const grid = document.getElementById('u-accounts-grid');
            if(!grid) return;
            grid.innerHTML = accounts.map(acc => {
                const isOpen = openBudgetsDropdowns.includes(acc.id);
                let budgetsListHtml = '';

                if(acc.budgets && acc.budgets.length > 0) {
                    budgetsListHtml = acc.budgets.map(b => {
                        const spent = transactions
                            .filter(t => t.accountId === acc.id && t.budgetId === b.id)
                            .reduce((s, t) => s + t.amount, 0);
                        const remaining = b.limit - spent;
                        const isLowBudget = remaining < (0.1 * b.limit); 
                        const usagePerc = Math.min(100, Math.round((spent / b.limit) * 100));

                        const cardBgClass = isLowBudget ? 'bg-rose-50/75 border-rose-200 text-rose-800' : 'bg-slate-50 border-slate-100 text-slate-700';
                        const progressBgClass = isLowBudget ? 'bg-rose-600' : 'bg-indigo-600';
                        const textBadgeClass = isLowBudget ? 'text-rose-600' : 'text-slate-500';

                        return `
                            <div class="${cardBgClass} p-3 rounded-2xl border space-y-2 relative group/item">
                                <div class="flex justify-between items-center text-[10px] font-bold">
                                    <span class="truncate flex items-center gap-1">
                                        ${isLowBudget ? '⚠️' : ''} ${b.name}
                                    </span>
                                    <button onclick="deleteBudget(${acc.id}, ${b.id})" class="text-slate-300 hover:text-rose-500 text-[9px] font-black transition-colors">Hapus</button>
                                </div>
                                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full ${progressBgClass}" style="width: ${usagePerc}%"></div>
                                </div>
                                <div class="flex justify-between text-[9px] font-bold ${textBadgeClass}">
                                    <span>Pakai: ${formatIDR(spent)}</span>
                                    <span>Limit: ${formatIDR(b.limit)}</span>
                                </div>
                                <div class="flex justify-between items-center text-[8px] text-slate-400">
                                    <span>Dibuat: ${formatDate(b.createdAt)}</span>
                                </div>
                                ${isLowBudget ? `<div class="text-[8px] text-rose-600 font-extrabold uppercase tracking-wide">Sisa saku tipis (&lt;10%)!</div>` : ''}
                            </div>
                        `;
                    }).join('');
                } else {
                    budgetsListHtml = `<p class="text-xs text-slate-400 italic py-2">Belum ada anggaran ditambahkan.</p>`;
                }

                return `
                    <div class="glass-card p-6 rounded-3xl border-b-4 border-indigo-500 shadow-sm space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-slate-400 text-[10px] uppercase tracking-wider mb-1">${acc.name}</h5>
                                <p class="text-2xl font-black text-slate-800">${formatIDR(acc.current)}</p>
                                <span class="text-[8px] text-slate-400 block font-bold mt-1">
                                    Dibuat: ${formatDate(acc.createdAt)} 
                                    ${acc.updatedAt !== acc.createdAt ? `| Diubah: ${formatDate(acc.updatedAt)}` : ''}
                                </span>
                            </div>
                            <div class="flex gap-1.5">
                                <button onclick="openAddBudgetModal(${acc.id})" class="bg-indigo-50 text-indigo-700 p-1.5 rounded-xl hover:bg-indigo-100 transition-colors" title="Tambah Anggaran Baru">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                                <button onclick="toggleBudgetsDropdown(${acc.id})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-xl transition-all flex items-center gap-1">
                                    <span>Anggaran</span>
                                    <i data-lucide="${isOpen ? 'chevron-up' : 'chevron-down'}" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <div id="budgets-dropdown-${acc.id}" class="${isOpen ? '' : 'hidden'} pt-3 border-t border-slate-100 space-y-3">
                            <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex justify-between">
                                <span>Alokasi Anggaran</span>
                                <span class="text-indigo-600 lowercase font-normal italic">sisa &lt; 10% merah</span>
                            </h6>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                ${budgetsListHtml}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            lucide.createIcons();
        }

        function renderUserTransactions() {
            const list = document.getElementById('u-all-list');
            if(!list) return;
            const sortedTrx = getSortedTransactions();
            list.innerHTML = sortedTrx.map(t => {
                const catObj = categories.find(c => c.id === t.categoryId);
                const catLabel = catObj ? catObj.name : t.category;

                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400">${formatDate(t.date)}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <span>${t.note}</span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">${t.accountName}</td>
                        <td class="px-6 py-4"><span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-lg text-[10px] font-bold">${t.budgetName || 'Bebas'}</span></td>
                        <td class="px-6 py-4"><span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">${t.importance || 'Kebutuhan'}</span></td>
                        <td class="px-6 py-4 text-xs">
                            <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-lg font-bold">${catLabel}</span>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            ${t.receipt ? 
                                `<button onclick="viewReceipt('${t.id}')" class="text-indigo-600 hover:text-indigo-800 font-bold underline flex items-center gap-1 focus:outline-none">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> lihat foto
                                 </button>` : 
                                `<button onclick="triggerDirectUpload('${t.id}')" class="text-slate-400 hover:text-rose-500 font-bold underline flex items-center gap-1 focus:outline-none">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> upload bukti
                                 </button>`
                            }
                        </td>
                        <td class="px-6 py-4 text-right font-black text-rose-500">-${formatIDR(t.amount)}</td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="editTransaction(${t.id})" class="inline-flex items-center gap-1 text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg font-extrabold transition-all focus:outline-none">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                            </button><br>
                            <button onclick="deleteTransaction(${t.id})" class="inline-flex items-center gap-1 text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 px-3 py-1.5 rounded-lg font-extrabold transition-all focus:outline-none ml-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            lucide.createIcons();
        }

        // --- NERACA BUKU BESAR GENERATOR & RANGE FILTER ---
        function initLedgerDates() {
            const startInput = document.getElementById('ledger-start');
            const endInput = document.getElementById('ledger-end');
            if (startInput && endInput && !startInput.value) {
                const today = new Date();
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                
                startInput.value = startOfMonth.toISOString().split('T')[0];
                endInput.value = today.toISOString().split('T')[0];
            }
        }

        function setupLedgerListeners() {
            const startInput = document.getElementById('ledger-start');
            const endInput = document.getElementById('ledger-end');
            if (startInput && endInput) {
                startInput.addEventListener('change', () => {
                    renderUserLedger();
                    if (activeLedgerAccountId) {
                        viewLedgerDetails(activeLedgerAccountId, startInput.value, endInput.value);
                    }
                });
                endInput.addEventListener('change', () => {
                    renderUserLedger();
                    if (activeLedgerAccountId) {
                        viewLedgerDetails(activeLedgerAccountId, startInput.value, endInput.value);
                    }
                });
            }
        }

        // --- NERACA BUKU BESAR ENGINE ---
        function renderUserLedger() {
            initLedgerDates();
            const startVal = document.getElementById('ledger-start').value;
            const endVal = document.getElementById('ledger-end').value;
            const tbody = document.getElementById('u-ledger-tbody');
            if (!tbody) return;

            if (!startVal || !endVal) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-slate-400 italic">Tanggal tidak valid.</td></tr>`;
                return;
            }

            const startTime = new Date(startVal + 'T00:00:00').getTime();
            const endTime = new Date(endVal + 'T23:59:59').getTime();

            if (accounts.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-slate-400 italic">Belum ada Buku Tabungan.</td></tr>`;
                return;
            }

            tbody.innerHTML = accounts.map(acc => {
                const trxsBefore = transactions.filter(t => t.accountId === acc.id && new Date(t.date).getTime() < startTime);
                const totalBefore = trxsBefore.reduce((sum, t) => sum + t.amount, 0);
                const saldoAwal = acc.initial - totalBefore;

                const trxsDuring = transactions.filter(t => {
                    const tTime = new Date(t.date).getTime();
                    return t.accountId === acc.id && tTime >= startTime && tTime <= endTime;
                });
                const totalKredit = trxsDuring.reduce((sum, t) => sum + t.amount, 0);
                const saldoAkhir = saldoAwal - totalKredit;

                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 font-bold text-slate-800 truncate max-w-[80px]" title="${acc.name}">${acc.name}</td>
                        <td class="py-3 text-right font-bold text-slate-600">${formatIDR(saldoAwal)}</td>
                        <td class="py-3 text-right font-bold text-rose-500">-${formatIDR(totalKredit)}</td>
                        <td class="py-3 text-right font-bold text-indigo-600">${formatIDR(saldoAkhir)}</td>
                        <td class="py-3 text-center">
                            <button onclick="viewLedgerDetails(${acc.id}, '${startVal}', '${endVal}')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 p-1.5 rounded-lg inline-flex items-center justify-center focus:outline-none" title="Lihat Jurnal">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            lucide.createIcons();
        }

        function viewLedgerDetails(accId, startVal, endVal) {
            const acc = accounts.find(a => a.id === accId);
            if (!acc) return;

            activeLedgerAccountId = accId;
            const startTime = new Date(startVal + 'T00:00:00').getTime();
            const endTime = new Date(endVal + 'T23:59:59').getTime();

            const trxsDuring = transactions.filter(t => {
                const tTime = new Date(t.date).getTime();
                return t.accountId === accId && tTime >= startTime && tTime <= endTime;
            });

            const container = document.getElementById('u-ledger-details-container');
            const titleSpan = document.getElementById('ledger-detail-title');
            const tbody = document.getElementById('u-ledger-details-tbody');

            if (!container || !titleSpan || !tbody) return;

            titleSpan.innerText = `${acc.name} (Rentang: ${formatDate(startVal).split(',')[0]} s.d. ${formatDate(endVal).split(',')[0]})`;

            if (trxsDuring.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="py-4 text-center text-slate-400 italic">Tidak ada rincian jurnal pengeluaran dalam rentang periode ini.</td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = trxsDuring.map(t => `
                    <tr class="hover:bg-slate-100/80 transition-colors">
                        <td class="py-3 text-slate-500 font-bold">${formatDate(t.date)}</td>
                        <td class="py-3 font-semibold text-slate-800">${t.note}</td>
                        <td class="py-3"><span class="bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded text-[9px] font-bold">${t.category || 'Lainnya'}</span></td>
                        <td class="py-3"><span class="bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">${t.importance}</span></td>
                        <td class="py-3 text-right font-black text-rose-500">-${formatIDR(t.amount)}</td>
                    </tr>
                `).join('');
            }

            container.classList.remove('hidden');
            container.scrollIntoView({ behavior: 'smooth' });
            lucide.createIcons();
        }

        function closeLedgerDetails() {
            const container = document.getElementById('u-ledger-details-container');
            if (container) container.classList.add('hidden');
            activeLedgerAccountId = null;
        }

        function setLedgerRange(type) {
            const startInput = document.getElementById('ledger-start');
            const endInput = document.getElementById('ledger-end');
            if (!startInput || !endInput) return;

            const today = new Date();
            if (type === 'month') {
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                startInput.value = startOfMonth.toISOString().split('T')[0];
                endInput.value = today.toISOString().split('T')[0];
            } else if (type === 'all') {
                let earliestDate = new Date(today.getFullYear() - 1, today.getMonth(), today.getDate());
                if (transactions.length > 0) {
                    const sortedTrxs = [...transactions].sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime());
                    earliestDate = new Date(sortedTrxs[0].date);
                }
                startInput.value = earliestDate.toISOString().split('T')[0];
                endInput.value = today.toISOString().split('T')[0];
            }
            renderUserLedger();
            if (activeLedgerAccountId) {
                viewLedgerDetails(activeLedgerAccountId, startInput.value, endInput.value);
            }
        }

        // --- FITUR PRATINJAU SEBELUM DOWNLOAD PDF ---
        function previewLedgerPDF() {
            initLedgerDates();
            const startVal = document.getElementById('ledger-start').value;
            const endVal = document.getElementById('ledger-end').value;
            
            if (!startVal || !endVal) {
                showToast('Harap tentukan tanggal rentang laporan.', 'error');
                return;
            }

            const startTime = new Date(startVal + 'T00:00:00').getTime();
            const endTime = new Date(endVal + 'T23:59:59').getTime();

            const tbody = document.getElementById('pdf-report-tbody');
            if (!tbody) return;

            let totalDebitAll = 0;
            let totalKreditAll = 0;
            let totalSaldoAll = 0;

            let tbodyHtml = '';
            accounts.forEach(acc => {
                const trxsBefore = transactions.filter(t => t.accountId === acc.id && new Date(t.date).getTime() < startTime);
                const totalBefore = trxsBefore.reduce((sum, t) => sum + t.amount, 0);
                const saldoAwal = acc.initial - totalBefore;

                const trxsDuring = transactions.filter(t => {
                    const tTime = new Date(t.date).getTime();
                    return t.accountId === acc.id && tTime >= startTime && tTime <= endTime;
                });
                const totalKredit = trxsDuring.reduce((sum, t) => sum + t.amount, 0);
                const saldoAkhir = saldoAwal - totalKredit;

                totalDebitAll += saldoAwal;
                totalKreditAll += totalKredit;
                totalSaldoAll += saldoAkhir;

                tbodyHtml += `
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 px-3 font-bold text-slate-800">${acc.name}</td>
                        <td class="py-2.5 px-3 text-right font-medium text-slate-600">${formatIDR(saldoAwal)}</td>
                        <td class="py-2.5 px-3 text-right font-medium text-rose-500">-${formatIDR(totalKredit)}</td>
                        <td class="py-2.5 px-3 text-right font-bold text-indigo-950">${formatIDR(saldoAkhir)}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = tbodyHtml;

            safeSetText('pdf-report-total-debit', formatIDR(totalDebitAll));
            safeSetText('pdf-report-total-kredit', `-${formatIDR(totalKreditAll)}`);
            safeSetText('pdf-report-total-saldo', formatIDR(totalSaldoAll));

            const printDateStr = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            safeSetText('pdf-report-print-date', printDateStr);

            const rangeDateStr = `${new Date(startVal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })} s.d. ${new Date(endVal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}`;
            safeSetText('pdf-report-range-date', rangeDateStr);

            openModal('modal-pdf-preview');
            lucide.createIcons();
        }

        function downloadLedgerPDF() {
            const printArea = document.getElementById('pdf-print-area');
            if (!printArea) return;

            showToast('Sedang merangkum laporan PDF...', 'info');

            const options = {
                margin:       0.4,
                filename:     `SisaJejakUang_Buku_Besar.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(options).from(printArea).save().then(() => {
                sysLog('User', 'Sukses mengunduh Dokumen Laporan Neraca Buku Besar (PDF).');
                showToast('Laporan PDF Neraca Buku Besar berhasil diunduh!', 'success');
                closeModal('modal-pdf-preview');
            }).catch(err => {
                console.error("Gagal cetak PDF:", err);
                showToast('Terjadi kesalahan saat mengunduh laporan.', 'error');
            });
        }

        // --- ADMIN VIEW DATA MANAGEMENT (METRIK & LOGS DIUPDATE) ---
        function updateAdminData() {
            const totalEcoBalance = MOCK_ADMIN_USERS.reduce((s, u) => s + u.balance, 0) + accounts.reduce((s, a) => s + a.current, 0);
            
            const todayStr = new Date().toISOString().split('T')[0];
            const todayTrxs = transactions.filter(t => t.date.split('T')[0] === todayStr);
            safeSetText('a-stat-today-trx', todayTrxs.length);

            safeSetText('a-stat-users', MOCK_ADMIN_USERS.length + 1);
            safeSetText('a-stat-balance', formatIDR(totalEcoBalance));

            const totalExpense = transactions.reduce((s, t) => s + t.amount, 0);
            const initialBalance = accounts.reduce((s, a) => s + a.initial, 0);
            const demoScore = initialBalance > 0 ? Math.round((1 - (totalExpense / initialBalance)) * 100) : 0;
            
            const mockNajwaScore = 88;
            const mockMeisaScore = 75;
            const averageScore = Math.round((mockNajwaScore + mockMeisaScore + demoScore) / 3);
            
            safeSetText('a-stat-health-avg', averageScore + '%');

            const healthCircle = document.getElementById('admin-health-circle');
            const healthPercentage = document.getElementById('admin-health-percentage');
            if (healthCircle && healthPercentage) {
                const circumference = 439.8;
                const offset = circumference - (averageScore / 100) * circumference;
                healthCircle.style.strokeDashoffset = offset;
                healthPercentage.innerText = averageScore + '%';
            }

            // A. METRIK KEPATUHAN ANGGARAN (FITUR REKOMENDASI)
            const flatAllBudgets = accounts.flatMap(a => a.budgets || []);
            let compliantCount = 0;
            flatAllBudgets.forEach(b => {
                const spentOnB = transactions
                    .filter(t => t.accountId === b.id_buku && t.budgetId === b.id)
                    .reduce((s, t) => s + t.amount, 0);
                if (spentOnB <= b.limit) {
                    compliantCount++;
                }
            });
            const complianceRate = flatAllBudgets.length > 0 ? Math.round((compliantCount / flatAllBudgets.length) * 100) : 100;
            safeSetText('admin-compliance-rate', complianceRate + '%');
            const compBar = document.getElementById('admin-compliance-bar');
            if (compBar) compBar.style.width = complianceRate + '%';

            // B. STATISTIK PENYERAPAN FITUR OCR (FITUR REKOMENDASI)
            const ocrTransactionsCount = transactions.filter(t => t.inputMethod === 'ocr').length;
            const totalTransactionsCount = transactions.length;
            const ocrRate = totalTransactionsCount > 0 ? Math.round((ocrTransactionsCount / totalTransactionsCount) * 100) : 0;
            safeSetText('admin-ocr-rate', ocrRate + '%');
            const ocrBar = document.getElementById('admin-ocr-bar');
            if (ocrBar) ocrBar.style.width = ocrRate + '%';
            safeSetText('admin-ocr-ratio-text', `${ocrTransactionsCount} dari ${totalTransactionsCount} Transaksi`);

            // C. RENDER SYSTEM ACTIVITY LOGS (FITUR REKOMENDASI)
            const activityTbody = document.getElementById('admin-activity-tbody');
            if (activityTbody) {
                const logs = JSON.parse(localStorage.getItem('ssju_v6_system_logs')) || [];
                if (logs.length === 0) {
                    activityTbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-slate-400 italic">Belum ada log terekam.</td></tr>`;
                } else {
                    activityTbody.innerHTML = logs.map(l => `
                        <tr>
                            <td class="px-4 py-3 text-[11px] font-bold text-slate-400">${formatDate(l.timestamp)}</td>
                            <td class="px-4 py-3"><span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded text-[10px] font-black">${l.actor}</span></td>
                            <td class="px-4 py-3 font-semibold text-slate-800 text-[11px]">${l.action}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="bg-emerald-50 text-emerald-700 text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">${l.status}</span>
                            </td>
                        </tr>
                    `).join('');
                }
            }

            // Komparasi Needs vs Wants
            const compContainer = document.getElementById('admin-needs-wants-bars');
            if (compContainer) {
                const needsTotal = transactions.filter(t => t.importance === 'Kebutuhan').reduce((s, t) => s + t.amount, 0);
                const wantsTotal = transactions.filter(t => t.importance === 'Keinginan').reduce((s, t) => s + t.amount, 0);
                const overallSpent = needsTotal + wantsTotal;
                
                const needsPerc = overallSpent > 0 ? Math.round((needsTotal / overallSpent) * 100) : 0;
                const wantsPerc = overallSpent > 0 ? Math.round((wantsTotal / overallSpent) * 100) : 0;

                compContainer.innerHTML = `
                    <div class="space-y-6 py-2">
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-xs font-black text-indigo-900 uppercase tracking-wider block">Kebutuhan (Needs)</span>
                                    <span class="text-xs text-slate-400 font-bold">${formatIDR(needsTotal)}</span>
                                </div>
                                <span class="text-lg font-black text-indigo-600">${needsPerc}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden shadow-inner">
                                <div class="bg-indigo-600 h-full rounded-full transition-all duration-500" style="width: ${needsPerc}%"></div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="text-xs font-black text-rose-900 uppercase tracking-wider block">Keinginan (Wants)</span>
                                    <span class="text-xs text-slate-400 font-bold">${formatIDR(wantsTotal)}</span>
                                </div>
                                <span class="text-lg font-black text-rose-600">${wantsPerc}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-4 rounded-full overflow-hidden shadow-inner">
                                <div class="bg-rose-500 h-full rounded-full transition-all duration-500" style="width: ${wantsPerc}%"></div>
                            </div>
                        </div>
                    </div>
                `;
            }

            const weeklyChartContainer = document.getElementById('admin-weekly-chart');
            if (weeklyChartContainer) {
                const last7DaysData = [];
                for (let i = 6; i >= 0; i--) {
                    const d = new Date();
                    d.setDate(d.getDate() - i);
                    const yyyymmdd = d.toISOString().split('T')[0];
                    const label = `${d.getDate()} ${d.toLocaleDateString('id-ID', { month: 'short' })}`;
                    const dayAmount = transactions
                        .filter(t => t.date.split('T')[0] === yyyymmdd)
                        .reduce((sum, t) => sum + t.amount, 0);
                    last7DaysData.push({ dateStr: yyyymmdd, label: label, amount: dayAmount });
                }

                const maxVal = Math.max(...last7DaysData.map(d => d.amount), 50000);
                const svgW = 600;
                const svgH = 160;
                const padL = 60;
                const padR = 40;
                const padT = 20;
                const padB = 30;
                const chartW = svgW - padL - padR;
                const chartH = svgH - padT - padB;
                
                let points = [];
                let pointsStr = "";
                let dotsHtml = "";
                let labelsHtml = "";
                
                last7DaysData.forEach((day, index) => {
                    const x = padL + (index * (chartW / 6));
                    const y = padT + chartH - ((day.amount / maxVal) * chartH);
                    points.push({ x, y, label: day.label, amount: day.amount });
                    pointsStr += `${x},${y} `;
                    
                    dotsHtml += `
                        <g class="group/dot cursor-pointer">
                            <circle cx="${x}" cy="${y}" r="5" fill="#4f46e5" stroke="#ffffff" stroke-width="2" class="transition-all hover:r-7" />
                            <circle cx="${x}" cy="${y}" r="12" fill="transparent" class="cursor-pointer" />
                            <rect x="${x - 50}" y="${y - 32}" width="100" height="22" rx="6" fill="#1e293b" class="opacity-0 group-hover/dot:opacity-100 transition-opacity duration-200 pointer-events-none" />
                            <text x="${x}" y="${y - 17}" fill="#ffffff" font-size="9" font-weight="bold" text-anchor="middle" class="opacity-0 group-hover/dot:opacity-100 transition-opacity duration-200 pointer-events-none">${formatIDR(day.amount)}</text>
                        </g>
                    `;
                    labelsHtml += `<text x="${x}" y="${svgH - 8}" fill="#94a3b8" font-size="9" font-weight="bold" text-anchor="middle">${day.label}</text>`;
                });
                
                const svgHtml = `
                    <svg viewBox="0 0 ${svgW} ${svgH}" class="w-full h-full font-sans">
                        <line x1="${padL}" y1="${padT}" x2="${svgW - padR}" y2="${padT}" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="${padL}" y1="${padT + chartH/2}" x2="${svgW - padR}" y2="${padT + chartH/2}" stroke="#f1f5f9" stroke-width="1" stroke-dasharray="4" />
                        <line x1="${padL}" y1="${padT + chartH}" x2="${svgW - padR}" y2="${padT + chartH}" stroke="#e2e8f0" stroke-width="1" />
                        <text x="${padL - 10}" y="${padT + 4}" fill="#94a3b8" font-size="8" font-weight="bold" text-anchor="end">${formatIDR(maxVal)}</text>
                        <text x="${padL - 10}" y="${padT + chartH/2 + 3}" fill="#94a3b8" font-size="8" font-weight="bold" text-anchor="end">${formatIDR(maxVal/2)}</text>
                        <text x="${padL - 10}" y="${padT + chartH + 3}" fill="#94a3b8" font-size="8" font-weight="bold" text-anchor="end">Rp 0</text>
                        <defs>
                            <linearGradient id="weeklyChartGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#4f46e5" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#4f46e5" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        <path d="M ${points[0].x} ${padT + chartH} ${points.map(p => `L ${p.x} ${p.y}`).join(' ')} L ${points[points.length-1].x} ${padT + chartH} Z" fill="url(#weeklyChartGrad)" />
                        <polyline points="${pointsStr.trim()}" fill="none" stroke="#4f46e5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        ${dotsHtml}
                        ${labelsHtml}
                    </svg>
                `;
                weeklyChartContainer.innerHTML = svgHtml;
            }

            // PREVIEW SINGKAT KESELURUHAN DATA SISTEM
            // 1. Buku Tabungan Preview
            const booksList = document.getElementById('preview-list-books');
            const booksCount = document.getElementById('preview-count-books');
            if (booksList && booksCount) {
                booksCount.innerText = accounts.length;
                if (accounts.length === 0) {
                    booksList.innerHTML = `<div class="text-slate-400 italic text-[11px] py-1">Tidak ada data.</div>`;
                } else {
                    booksList.innerHTML = accounts.map(a => `
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 flex flex-col">
                            <span class="font-bold text-slate-800 truncate">${a.name}</span>
                            <span class="text-[9px] text-slate-500 font-extrabold mt-0.5">${formatIDR(a.current)}</span>
                        </div>
                    `).join('');
                }
            }

            // 2. Alokasi Anggaran Preview
            const budgetsList = document.getElementById('preview-list-budgets');
            const budgetsCount = document.getElementById('preview-count-budgets');
            if (budgetsList && budgetsCount) {
                budgetsCount.innerText = flatAllBudgets.length;
                if (flatAllBudgets.length === 0) {
                    budgetsList.innerHTML = `<div class="text-slate-400 italic text-[11px] py-1">Tidak ada data.</div>`;
                } else {
                    budgetsList.innerHTML = flatAllBudgets.map(b => `
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 flex flex-col">
                            <span class="font-bold text-slate-800 truncate">${b.name}</span>
                            <span class="text-[9px] text-slate-500 font-extrabold mt-0.5">Limit: ${formatIDR(b.limit)}</span>
                        </div>
                    `).join('');
                }
            }

            // 3. Kategori Preview
            const categoriesList = document.getElementById('preview-list-categories');
            const categoriesCount = document.getElementById('preview-count-categories');
            if (categoriesList && categoriesCount) {
                categoriesCount.innerText = categories.length;
                if (categories.length === 0) {
                    categoriesList.innerHTML = `<div class="text-slate-400 italic text-[11px] py-1">Tidak ada data.</div>`;
                } else {
                    categoriesList.innerHTML = categories.map(c => `
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 flex justify-between items-center gap-1">
                            <span class="font-bold text-slate-800 truncate">${c.name}</span>
                            <span class="text-[8px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded ${c.type === 'master' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700'}">${c.type}</span>
                        </div>
                    `).join('');
                }
            }

            // 4. Transaksi Preview
            const transList = document.getElementById('preview-list-transactions');
            const transCount = document.getElementById('preview-count-transactions');
            if (transList && transCount) {
                transCount.innerText = transactions.length;
                if (transactions.length === 0) {
                    transList.innerHTML = `<div class="text-slate-400 italic text-[11px] py-1">Tidak ada data.</div>`;
                } else {
                    transList.innerHTML = transactions.slice(0, 5).map(t => `
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 flex flex-col">
                            <span class="font-bold text-slate-800 truncate">${t.note}</span>
                            <div class="flex justify-between items-center text-[9px] mt-1">
                                <span class="text-rose-500 font-extrabold">-${formatIDR(t.amount)}</span>
                                <span class="text-slate-400 font-semibold">${formatDate(t.date).split(',')[0]}</span>
                            </div>
                        </div>
                    `).join('');
                }
            }

            // Render Audit Tabel Pengguna (FITUR STATUS KESEHATAN FINANSIAL INDIKATOR KEPATUHAN)
            const uList = document.getElementById('a-users-list');
            if(uList) {
                const allUsers = [...MOCK_ADMIN_USERS, { name: 'User Demo (Anda)', balance: accounts.reduce((s, a) => s + a.current, 0) }];
                allUsers.sort((a, b) => {
                    let valA = userSortKey === 'name' ? a.name.toLowerCase() : a.balance;
                    let valB = userSortKey === 'name' ? b.name.toLowerCase() : b.balance;
                    if (userSortKey === 'name') {
                        return userSortOrder === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
                    } else {
                        return userSortOrder === 'asc' ? valA - valB : valB - valA;
                    }
                });
                uList.innerHTML = allUsers.map(u => {
                    // Cek status finansial berdasarkan saldo kritis (< Rp 1.000.000 kritis)
                    const isKritis = u.balance < 1000000;
                    const financialStatusBadge = isKritis 
                        ? `<span class="bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-black px-3 py-1 rounded-full uppercase">Kritis</span>`
                        : `<span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black px-3 py-1 rounded-full uppercase">Sehat</span>`;
                        
                    return `
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-bold text-slate-800">${u.name}</td>
                            <td class="px-6 py-4 text-center">${financialStatusBadge}</td>
                            <td class="px-6 py-4 text-right font-black text-slate-600">${formatIDR(u.balance)}</td>
                        </tr>
                    `;
                }).join('');
            }
        }

        function updateBudgetDropdown() {
            const accountId = parseInt(document.getElementById('trx-account').value);
            const budgetSelect = document.getElementById('trx-budget');
            if(!budgetSelect) return;

            const targetAcc = accounts.find(a => a.id === accountId);
            if(targetAcc && targetAcc.budgets && targetAcc.budgets.length > 0) {
                const optionsHtml = `<option value="free">Bebas (Tanpa Anggaran)</option>` + 
                    targetAcc.budgets.map(b => `<option value="${b.id}">${b.name} (Limit: ${formatIDR(b.limit)})</option>`).join('');
                budgetSelect.innerHTML = optionsHtml;
            } else {
                budgetSelect.innerHTML = `<option value="free">Bebas (Tanpa Anggaran)</option>`;
            }
        }

        // --- SYSTEM HELPERS ---
        function populateSelectOptions() {
            const accEl = document.getElementById('trx-account');
            const catEl = document.getElementById('trx-category');
            if (accEl) {
                accEl.innerHTML = accounts.map(a => `<option value="${a.id}">${a.name} (${formatIDR(a.current)})</option>`).join('');
                updateBudgetDropdown(); 
            }
            if (catEl) {
                const allowedCats = categories.filter(c => c.type === 'master' || (c.type === 'kustom' && c.id_user === DEMO_USER_ID));
                catEl.innerHTML = allowedCats.map(c => `<option value="${c.name}">${c.name} [${c.type.toUpperCase()}]</option>`).join('');
            }
        }
        
        async function saveAndRefresh() {
            await loadData();
            updateAllUI();
            if (currentRole === 'admin') {
                updateAdminData();
            }
        }
        
        function saveData() {
            // Data secara otomatis disimpan di database MySQL via API Call
        }
        
        async function loadData() {
            try {
                // 1. Fetch Categories
                let resCat = await fetch('api/kategori.php');
                let jsonCat = await resCat.json();
                if (jsonCat.status === 'success') {
                    categories = jsonCat.data.map(c => {
                        return {
                            id: parseInt(c.id),
                            id_user: c.user_id ? parseInt(c.user_id) : null,
                            name: c.nama_kategori,
                            type: c.jenis_kategori,
                            createdAt: c.created_at,
                            updatedAt: c.updated_at
                        };
                    });
                }

                // 2. Fetch Accounts
                let resAcc = await fetch('api/buku_tabungan.php');
                let jsonAcc = await resAcc.json();
                if (jsonAcc.status === 'success') {
                    accounts = jsonAcc.data.map(a => {
                        return {
                            id: parseInt(a.id),
                            id_user: parseInt(a.user_id),
                            name: a.nama_buku,
                            initial: parseFloat(a.saldo_awal),
                            current: parseFloat(a.saldo_saat_ini),
                            budgets: a.budgets ? a.budgets.map(b => {
                                return {
                                    id: parseInt(b.id),
                                    id_buku: parseInt(b.buku_tabungan_id),
                                    name: b.nama_anggaran,
                                    limit: parseFloat(b.batas_limit),
                                    spent: parseFloat(b.spent || 0),
                                    createdAt: b.created_at,
                                    updatedAt: b.updated_at
                                };
                            }) : [],
                            createdAt: a.created_at,
                            updatedAt: a.updated_at
                        };
                    });
                }

                // 3. Fetch Transactions
                let resTrx = await fetch('api/transaksi.php');
                let jsonTrx = await resTrx.json();
                if (jsonTrx.status === 'success') {
                    transactions = jsonTrx.data.map(t => {
                        return {
                            id: parseInt(t.id),
                            accountId: parseInt(t.buku_tabungan_id),
                            accountName: t.nama_buku,
                            budgetId: t.anggaran_id ? parseInt(t.anggaran_id) : 'free',
                            budgetName: t.nama_anggaran || '',
                            categoryId: parseInt(t.kategori_id),
                            category: t.nama_kategori,
                            date: t.tanggal_transaksi,
                            note: t.keterangan,
                            amount: parseFloat(t.nominal),
                            importance: t.prioritas,
                            receipt: t.bukti_pengeluaran,
                            inputMethod: t.input_method,
                            createdAt: t.created_at,
                            updatedAt: t.updated_at
                        };
                    });
                }
            } catch (err) {
                console.error('Gagal mengambil data dari server:', err);
                showToast('Koneksi server terganggu.', 'error');
            }
        }
        
        function formatIDR(n) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n); }
        function formatDate(iso) { return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }); }
        
        async function handleLogout() { 
            if (confirm('Apakah Anda yakin ingin logout dari sistem?')) {
                try {
                    let res = await fetch('api/auth.php?action=logout');
                    let json = await res.json();
                    if (json.status === 'success') {
                        // location.reload();
                        window.location.replace('http://localhost/sisajejakuang_2');
                    } else {
                        showToast(json.message, 'error');
                    }
                } catch (err) {
                    showToast('Gagal logout.', 'error');
                }
            }
        }

        async function resetData() {
            await handleLogout();
        }

        async function handleDeleteAccount() {
            if (confirm('Apakah Anda yakin ingin MENGHAPUS AKUN secara permanen?\nTindakan ini TIDAK DAPAT DIBATALKAN dan seluruh data buku tabungan, anggaran, serta riwayat transaksi Anda akan dihapus selamanya.')) {
                if (confirm('Konfirmasi terakhir: Anda benar-benar yakin ingin menghapus akun ini?')) {
                    try {
                        let res = await fetch('api/auth.php?action=delete_account', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' }
                        });
                        let json = await res.json();
                        if (json.status === 'success') {
                            alert('Akun Anda berhasil dihapus.');
                            // location.reload();
                            window.location.replace('http://localhost/sisajejakuang_2');
                        } else {
                            showToast(json.message, 'error');
                        }
                    } catch (err) {
                        showToast('Gagal menghapus akun.', 'error');
                    }
                }
            }
        }
        
        function showToast(msg, type) {
            const t = document.getElementById('toast');
            if (t) {
                const msgEl = document.getElementById('toast-message');
                if (msgEl) msgEl.innerText = msg;
                t.classList.remove('translate-y-32');
                setTimeout(() => t.classList.add('translate-y-32'), 3000);
            }
        }
    </script>
</body>
</html> 