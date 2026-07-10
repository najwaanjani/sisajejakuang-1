<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$current_user = $is_logged_in ? [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'role' => $_SESSION['role']
] : null;
?>
<!DOCTYPE html>
<html class="no-js" lang="id">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>SisaJejakUang - Kelola Keuangan Pribadi Anda Secara Cerdas</title>
    <meta name="description" content="SisaJejakUang adalah aplikasi pencatatan keuangan dinamis yang membantu Anda mengelola saldo, batas anggaran belanja, dan melacak prioritas kebutuhan vs keinginan." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-beta1.min.css" />
    <link rel="stylesheet" href="assets/css/LineIcons.2.0.css"/>
    <link rel="stylesheet" href="assets/css/tiny-slider.css"/>
    <link rel="stylesheet" href="assets/css/animate.css"/>
    <link rel="stylesheet" href="assets/css/lindy-uikit.css"/>
    
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700;800&display=swap');
      body {
        font-family: 'Plus Jakarta Sans', sans-serif;
      }
      .navbar-brand span {
        font-family: 'Plus Jakarta Sans', sans-serif;
      }
      .logo-accent {
        color: #4f46e5; /* indigo-600 */
      }
      .button-primary {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #fff !important;
      }
      .button-primary:hover {
        background-color: #4338ca !important;
        border-color: #4338ca !important;
      }
      .button-outline {
        border: 2px solid #4f46e5 !important;
        color: #4f46e5 !important;
        background: transparent !important;
      }
      .button-outline:hover {
        background-color: #4f46e5 !important;
        color: #fff !important;
      }
    </style>
  </head>
  <body>
    <!-- ========================= preloader start ========================= -->
    <div class="preloader">
      <div class="loader">
        <div class="spinner">
          <div class="spinner-container">
            <div class="spinner-rotator">
              <div class="spinner-left">
                <div class="spinner-circle"></div>
              </div>
              <div class="spinner-right">
                <div class="spinner-circle"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ========================= preloader end ========================= -->

    <!-- ========================= hero-section-wrapper-5 start ========================= -->
    <section id="home" class="hero-section-wrapper-5">

      <!-- ========================= header-6 start ========================= -->
      <header class="header header-6">
        <div class="navbar-area">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                  <a class="navbar-brand d-flex align-items-center" href="http://localhost/sisajejakuang_2">
                    <span class="fs-3 fw-bold text-dark" style="letter-spacing: -0.5px;">SisaJejak<span class="logo-accent">Uang</span></span>
                  </a>
  
                  <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent6">
                    <ul id="nav6" class="navbar-nav ms-auto">
                      <li class="nav-item">
                        <a class="page-scroll active" href="#home">Beranda</a>
                      </li>
                      <li class="nav-item">
                        <a class="page-scroll" href="#feature">Fitur</a>
                      </li>
                      <li class="nav-item">
                        <a class="page-scroll" href="#about">Tentang Kami</a>
                      </li>
                    </ul>
                  </div>
                  
                  <div class="header-action d-flex align-items-center gap-2">
                    <?php if ($is_logged_in): ?>
                      <span class="me-3 text-muted d-none d-xl-inline">Halo, <b><?= htmlspecialchars($current_user['name']) ?></b></span>
                      <a href="app.php" class="button button-sm radius-30 button-primary px-4 py-2">Ke Dashboard</a>
                    <?php else: ?>
                      <a href="app.php" class="button button-sm radius-30 button-outline px-4 py-2 me-2">Masuk</a>
                      <a href="app.php?tab=register" class="button button-sm radius-30 button-primary px-4 py-2">Daftar</a>
                    <?php endif; ?>
                  </div>
                  <!-- navbar collapse -->
                </nav>
                <!-- navbar -->
              </div>
            </div>
            <!-- row -->
          </div>
          <!-- container -->
        </div>
        <!-- navbar area -->
      </header>
      <!-- ========================= header-6 end ========================= -->

      <!-- ========================= hero-5 start ========================= -->
      <div class="hero-section hero-style-5 img-bg" style="background-image: url('assets/img/hero/hero-5/hero-bg.svg')">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="hero-content-wrapper">
                <h2 class="mb-30 wow fadeInUp" data-wow-delay=".2s">Kelola Sisa Jejak Keuangan Anda Secara Cerdas</h2>
                <p class="mb-30 wow fadeInUp" data-wow-delay=".4s">Pantau saldo dari berbagai rekening/dompet, tetapkan anggaran terkendali yang aman, catat pengeluaran harian instan, dan evaluasi kepatuhan prioritas belanja Anda.</p>
                <div class="wow fadeInUp" data-wow-delay=".6s">
                  <?php if ($is_logged_in): ?>
                    <a href="app.php" class="button button-lg radius-50 button-primary">Buka Dashboard Saya <i class="lni lni-chevron-right"></i></a>
                  <?php else: ?>
                    <a href="app.php?tab=register" class="button button-lg radius-50 button-primary me-3">Mulai Sekarang <i class="lni lni-chevron-right"></i></a>
                    <a href="app.php" class="button button-lg radius-50 button-outline">Masuk Akun</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-lg-6 align-self-end">
              <div class="hero-image wow fadeInUp" data-wow-delay=".5s">
                <img src="assets/img/hero/hero-5/hero-img.svg" alt="Financial Tracking Dashboard">
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ========================= hero-5 end ========================= -->

    </section>
    <!-- ========================= hero-section-wrapper-6 end ========================= -->

    <!-- ========================= feature style-5 start ========================= -->
    <section id="feature" class="feature-section feature-style-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10">
            <div class="section-title text-center mb-60">
              <h3 class="mb-15 wow fadeInUp" data-wow-delay=".2s">Fitur Unggulan SisaJejakUang</h3>
              <p class="wow fadeInUp" data-wow-delay=".4s">Kami menyediakan modul keuangan esensial yang dirancang secara khusus untuk menjaga stabilitas saku Anda dari risiko pengeluaran berlebih.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
              <div class="icon">
                <i class="lni lni-wallet"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg>                  
              </div>
              <div class="content">
                <h5>Multi-Buku Tabungan</h5>
                <p>Pisahkan pencatatan saldo Anda ke beberapa dompet digital, rekening bank, maupun uang tunai secara terstruktur.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".4s">
              <div class="icon">
                <i class="lni lni-target"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg> 
              </div>
              <div class="content">
                <h5>Validasi Saldo & Anggaran</h5>
                <p>Aturan sistem ketat memastikan batas limit anggaran yang Anda buat tidak boleh melebih saldo aktif tabungan.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
              <div class="icon">
                <i class="lni lni-stats-up"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg> 
              </div>
              <div class="content">
                <h5>Financial Health Score</h5>
                <p>Pantau kualitas keuangan Anda secara langsung lewat kalkulasi rasio Kebutuhan (Needs) vs Keinginan (Wants).</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".2s">
              <div class="icon">
                <i class="lni lni-camera"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg> 
              </div>
              <div class="content">
                <h5>Simulasi Pindai Struk (OCR)</h5>
                <p>Catat pengeluaran Anda secara kilat melalui sistem simulasi pembaca teks struk digital secara otomatis.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".4s">
              <div class="icon">
                <i class="lni lni-lock"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg> 
              </div>
              <div class="content">
                <h5>Log Audit Aktivitas</h5>
                <p>Sistem audit log bawaan yang merekam semua aktivitas finansial krusial demi menjamin transparansi perubahan data.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-feature wow fadeInUp" data-wow-delay=".6s">
              <div class="icon">
                <i class="lni lni-trash"></i>
                <svg width="110" height="72" viewBox="0 0 110 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M110 54.7589C110 85.0014 85.3757 66.2583 55 66.2583C24.6243 66.2583 0 85.0014 0 54.7589C0 24.5164 24.6243 0 55 0C85.3757 0 110 24.5164 110 54.7589Z" fill="#EBF4FF"/>
                </svg> 
              </div>
              <div class="content">
                <h5>Privasi Akun Mandiri</h5>
                <p>Kontrol privasi penuh dengan fitur hapus akun mandiri berantai yang membersihkan semua data Anda dari sistem database.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- ========================= feature style-5 end ========================= -->

    <!-- ========================= about style-4 start ========================= -->
    <section id="about" class="about-section about-style-4">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-5 col-lg-6">
            <div class="about-content-wrapper">
              <div class="section-title mb-30">
                <h3 class="mb-25 wow fadeInUp" data-wow-delay=".2s">Masa Depan Manajemen Keuangan di Tangan Anda</h3>
                <p class="wow fadeInUp" data-wow-delay=".3s">Berhentilah menebak ke mana uang Anda pergi. Dapatkan kendali penuh terhadap saku bulanan Anda dengan instan.</p>
              </div>
              <ul>
                <li class="wow fadeInUp" data-wow-delay=".35s">
                  <i class="lni lni-checkmark-circle text-primary"></i>
                  Pantau saldo riil yang diperbarui secara otomatis per transaksi.
                </li>
                <li class="wow fadeInUp" data-wow-delay=".4s">
                  <i class="lni lni-checkmark-circle text-primary"></i>
                  Notifikasi visual instan saat budget pos belanja tersisa kurang dari 10%.
                </li>
                <li class="wow fadeInUp" data-wow-delay=".45s">
                  <i class="lni lni-checkmark-circle text-primary"></i>
                  Analisis audit logs yang transparan untuk pemantauan keamanan database.
                </li>
              </ul>
              <a href="app.php" class="button button-lg radius-10 button-primary wow fadeInUp" data-wow-delay=".5s">Coba Sekarang</a>
            </div>
          </div>
          <div class="col-xl-7 col-lg-6">
            <div class="about-image text-lg-right wow fadeInUp" data-wow-delay=".5s">
              <img src="assets/img/about/about-4/about-img.svg" alt="SisaJejakUang Analytical charts">
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ========================= about style-4 end ========================= -->

    <!-- ========================= footer style-4 start ========================= -->
    <footer class="footer footer-style-4">
      <div class="container">
        <div class="widget-wrapper">
          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
                <div class="logo mb-4">
                  <a href="index.php" class="d-flex align-items-center">
                    <span class="fs-4 fw-bold text-dark" style="letter-spacing: -0.5px;">SisaJejak<span class="logo-accent">Uang</span></span>
                  </a>
                </div>
                <p class="desc">Aplikasi pelacakan finansial dinamis dan sistem anggaran saldo</p>
                <ul class="socials">
                  <li> <a href="#0"> <i class="lni lni-facebook-filled"></i> </a> </li>
                  <li> <a href="#0"> <i class="lni lni-twitter-filled"></i> </a> </li>
                  <li> <a href="#0"> <i class="lni lni-instagram-filled"></i> </a> </li>
                  <li> <a href="#0"> <i class="lni lni-linkedin-original"></i> </a> </li>
                </ul>
              </div>
            </div>
            <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6 col-sm-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".3s">
                <h6>Menu Navigasi</h6>
                <ul class="links">
                  <li> <a href="#home">Beranda</a> </li>
                  <li> <a href="#feature">Fitur Utama</a> </li>
                  <li> <a href="#about">Tentang Kami</a> </li>
                  <li> <a href="#pricing">Paket</a> </li>
                  <li> <a href="#contact">Kontak</a> </li>
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".4s">
                <h6>Fitur Sistem</h6>
                <ul class="links">
                  <li> <a href="app.php">Buku Tabungan</a> </li>
                  <li> <a href="app.php">Manajemen Anggaran</a> </li>
                  <li> <a href="app.php">Mutasi Transaksi</a> </li>
                  <li> <a href="app.php">Scan Struk (OCR)</a> </li>
                </ul>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
              <div class="footer-widget wow fadeInUp" data-wow-delay=".5s">
                <h6>Tersedia Untuk</h6>
                <ul class="download-app">
                  <li>
                    <a href="#0">
                      <span class="icon"><i class="lni lni-chrome"></i></span>
                      <span class="text">Buka di browser <b>Google Chrome</b> </span>
                    </a>
                  </li>
                  <li>
                    <a href="#0">
                      <span class="icon"><i class="lni lni-firefox"></i></span>
                      <span class="text">Kompatibel di <b>Mozilla Firefox</b> </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="copyright-wrapper wow fadeInUp" data-wow-delay=".2s">
          <p>Design and Developed by <a href="https://uideck.com" rel="nofollow" target="_blank">UIdeck</a></p>
        </div>
      </div>
    </footer>
    <!-- ========================= footer style-4 end ========================= -->

    <!-- ========================= scroll-top start ========================= -->
    <a href="#" class="scroll-top"> <i class="lni lni-chevron-up"></i> </a>
    <!-- ========================= scroll-top end ========================= -->
		

    <!-- ========================= JS here ========================= -->
    <script src="assets/js/bootstrap-5.0.0-beta1.min.js"></script>
    <script src="assets/js/tiny-slider.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/main.js"></script>
  </body>
</html>
