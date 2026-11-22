<?php
// 301 Permanent Redirect ke Clean Root URL
header("HTTP/1.1 301 Moved Permanently");
header("Location: /");
exit();

$candidatePaths = [
    __DIR__ . '/../../.env',          // Struktur lokal standard (toefl/public/id/index.php)
    __DIR__ . '/../../toefl/.env',     // Struktur Hostinger (public_html/id/index.php -> toefl/.env)
    __DIR__ . '/../../../toefl/.env',  // Variasi struktur subfolder hosting
    __DIR__ . '/../.env',             // Jika ada .env di root public_html
    dirname(dirname(__DIR__)) . '/toefl/.env',
];

$envFile = null;
foreach ($candidatePaths as $path) {
    if (file_exists($path)) {
        $envFile = $path;
        break;
    }
}

$adminWa = '@mahadalytbi';
$adminEmail = 'mahadaly@tebuireng.ac.id';
$adminAddress = 'Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471';
$appName = "e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng";
$appUrl = '';

if ($envFile && file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim(trim($val), '"\'');
            if ($key === 'ADMIN_WA' && !empty($val)) {
                $adminWa = $val;
            } elseif ($key === 'ADMIN_EMAIL' && !empty($val)) {
                $adminEmail = $val;
            } elseif ($key === 'ADMIN_ADDRESS' && !empty($val)) {
                $adminAddress = $val;
            } elseif ($key === 'APP_NAME' && !empty($val)) {
                $appName = $val;
            } elseif ($key === 'APP_URL' && !empty($val)) {
                $appUrl = rtrim($val, '/');
            }
        }
    }
}

// Format tautan WhatsApp resmi (Mendukung format @username, link wa.me, dan nomor internasional)
$adminWaDisplay = $adminWa;
$adminWaLink = 'https://wa.me/';
if (strpos($adminWa, 'http://') === 0 || strpos($adminWa, 'https://') === 0) {
    $adminWaLink = $adminWa;
} elseif (strpos($adminWa, '@') === 0) {
    $adminWaLink = 'https://wa.me/' . substr($adminWa, 1);
} else {
    $adminWaClean = preg_replace('/[^0-9]/', '', $adminWa);
    if (!empty($adminWaClean)) {
        $adminWaIntl = (substr($adminWaClean, 0, 1) === '0') ? ('62' . substr($adminWaClean, 1)) : $adminWaClean;
        $adminWaLink = 'https://wa.me/' . $adminWaIntl;
    }
}

$loginUrl = '/login';
$registerUrl = '/register';
$homeUrl = '/';
?>
<!DOCTYPE html>
<html lang="id">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="/id/">
    
    <!-- Favicon Resmi Ma'had Aly Hasyim Asy'ari Tebuireng -->
    <link rel="icon" type="image/png" href="assets/images/favicon.png?v=2">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <link rel="apple-touch-icon" href="assets/images/favicon.png?v=2">
    
    <!-- SEO & Metadata Resmi Ma'had Aly Hasyim Asy'ari Tebuireng -->
    <title><?= htmlspecialchars($appName) ?> - Pondok Pesantren Tebuireng Jombang</title>
    <meta name="description" content="Platform resmi Ujian e-TOEFL & Prediksi Kemampuan Bahasa Inggris Mahasantri Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang. Uji kompetensi bahasa asing terstandar untuk memperluas dakwah global dan literasi turats.">
    <meta name="keywords" content="e-toefl, toefl prediction, toeic, mahad aly, hasyim asy'ari, tebuireng, jombang, ujian toefl santri, pesantren, sertifikat toefl">
    <meta name="author" content="Ma'had Aly Hasyim Asy'ari Tebuireng">
    
    <!-- Open Graph Protocol -->
    <meta property="og:title" content="<?= htmlspecialchars($appName) ?> - Ma'had Aly Tebuireng">
    <meta property="og:description" content="Portal Resmi Uji Kompetensi e-TOEFL & TOEIC Prediction Test Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://etoefl.mahatbi.or.id/id/index.php">
    <meta property="og:site_name" content="Ma'had Aly Hasyim Asy'ari Tebuireng">
    <meta name="theme-color" content="#0A3D24">

    <!-- Fonts Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-space-dynamic.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <link rel="stylesheet" href="assets/css/owl.css">

    <!-- Custom Academic Stylesheet (Palet Ma'had Aly Tebuireng: #0A3D24, #007A1E, #FFFFFF, #C69C2B) -->
    <style>
      :root {
        --mahad-dark: #0A3D24;
        --mahad-primary: #007A1E;
        --mahad-gold: #C69C2B;
        --mahad-gold-light: #E0BA55;
        --mahad-white: #FFFFFF;
        --mahad-bg: #F8FAF9;
        --mahad-text: #2D3748;
      }

      body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
        color: var(--mahad-text);
        background-color: var(--mahad-white);
      }

      h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-weight: 700;
        color: var(--mahad-dark);
      }

      /* ===== Navbar & Header Styling (Desktop & Mobile-First Ma'had Aly Standard) ===== */
      header.header-area {
        background-color: #FFFFFF !important;
        box-shadow: 0 2px 14px rgba(10, 61, 36, 0.08) !important;
        border-bottom: 3px solid var(--mahad-gold) !important;
        z-index: 9999 !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }

      /* Desktop Viewport (>= 992px) */
      @media (min-width: 992px) {
        header.header-area {
          position: absolute !important;
          top: 0 !important;
          left: 0 !important;
          right: 0 !important;
          height: auto !important;
          min-height: 82px !important;
        }
        header.header-area .main-nav {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          min-height: 82px !important;
          padding: 8px 0 !important;
          background: transparent !important;
          overflow: visible !important;
        }
        header.header-area .main-nav .logo {
          display: flex !important;
          align-items: center !important;
          line-height: 1 !important;
          float: none !important;
          margin-right: 25px !important;
          text-decoration: none !important;
        }
        .header-logo-img {
          height: 52px !important;
          width: auto !important;
          max-width: 270px !important;
          object-fit: contain !important;
          display: block !important;
          transition: all 0.3s ease !important;
        }
        /* Sticky Header saat Halaman Di-scroll */
        header.header-area.background-header {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          right: 0 !important;
          min-height: 72px !important;
          height: auto !important;
          background-color: rgba(255, 255, 255, 0.98) !important;
          box-shadow: 0 4px 18px rgba(10, 61, 36, 0.12) !important;
          border-bottom: 3px solid var(--mahad-gold) !important;
          z-index: 99999 !important;
          padding: 0 !important;
        }
        header.header-area.background-header .main-nav {
          min-height: 72px !important;
          padding: 5px 0 !important;
        }
        header.header-area.background-header .header-logo-img {
          height: 46px !important;
          max-width: 240px !important;
        }
        /* Spacing dan Tampilan Menu Navigasi Desktop */
        header.header-area .main-nav .nav {
          display: flex !important;
          align-items: center !important;
          gap: 6px !important;
          margin: 0 !important;
          padding: 0 !important;
          float: none !important;
          list-style: none !important;
        }
        header.header-area .main-nav .nav li {
          padding: 0 !important;
          margin: 0 !important;
        }
        header.header-area .main-nav .nav li a {
          display: inline-block !important;
          padding: 8px 14px !important;
          height: auto !important;
          line-height: normal !important;
          color: var(--mahad-dark) !important;
          font-weight: 600 !important;
          font-size: 14.5px !important;
          border-radius: 6px !important;
          transition: all 0.2s ease !important;
        }
        header.header-area .main-nav .nav li a:hover,
        header.header-area .main-nav .nav li a.active {
          color: var(--mahad-primary) !important;
          background-color: #EBF5EE !important;
        }
        header.header-area .main-nav .nav li .main-red-button a,
        .main-button a,
        .main-button button,
        .btn-mahad-primary {
          background-color: var(--mahad-primary) !important;
          border: none !important;
          color: var(--mahad-white) !important;
          font-weight: 700 !important;
          border-radius: 6px !important;
          box-shadow: 0 4px 10px rgba(0, 122, 30, 0.25) !important;
          padding: 9px 18px !important;
          transition: all 0.3s ease !important;
        }
        header.header-area .main-nav .nav li .main-red-button a:hover,
        .main-button a:hover,
        .main-button button:hover,
        .btn-mahad-primary:hover {
          background-color: var(--mahad-dark) !important;
          box-shadow: 0 4px 14px rgba(10, 61, 36, 0.35) !important;
          color: #FFFFFF !important;
        }
        header.header-area .menu-trigger {
          display: none !important;
        }
      }

      /* Mobile Viewport (< 992px) - iPhone / Android Phone Standard */
      @media (max-width: 991px) {
        header.header-area {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          right: 0 !important;
          height: 64px !important;
          min-height: 64px !important;
          background-color: #FFFFFF !important;
          box-shadow: 0 2px 10px rgba(10, 61, 36, 0.1) !important;
          border-bottom: 3px solid var(--mahad-gold) !important;
          padding: 0 15px !important;
          z-index: 99999 !important;
        }
        header.header-area .container {
          padding: 0 !important;
          max-width: 100% !important;
        }
        header.header-area .main-nav {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          min-height: 64px !important;
          height: 64px !important;
          padding: 0 !important;
          position: relative !important;
          background: transparent !important;
          overflow: visible !important;
        }
        header.header-area .main-nav .logo {
          margin: 0 !important;
          display: flex !important;
          align-items: center !important;
        }
        .header-logo-img {
          height: 38px !important;
          max-width: 210px !important;
          width: auto !important;
          object-fit: contain !important;
          display: block !important;
        }
        /* Hamburger Button */
        header.header-area .menu-trigger {
          display: flex !important;
          flex-direction: column !important;
          justify-content: center !important;
          align-items: center !important;
          width: 42px !important;
          height: 42px !important;
          border-radius: 8px !important;
          background-color: #EBF5EE !important;
          border: 1px solid #C2E2CC !important;
          cursor: pointer !important;
          position: relative !important;
          z-index: 100000 !important;
          text-decoration: none !important;
        }
        header.header-area .menu-trigger span {
          display: block !important;
          position: relative !important;
          width: 22px !important;
          height: 2.5px !important;
          background-color: #0A3D24 !important;
          border-radius: 2px !important;
          transition: all 0.3s ease !important;
          text-indent: -9999px;
        }
        header.header-area .menu-trigger span:before,
        header.header-area .menu-trigger span:after {
          content: '' !important;
          position: absolute !important;
          left: 0 !important;
          width: 22px !important;
          height: 2.5px !important;
          background-color: #0A3D24 !important;
          border-radius: 2px !important;
          transition: all 0.3s ease !important;
        }
        header.header-area .menu-trigger span:before {
          top: -7px !important;
        }
        header.header-area .menu-trigger span:after {
          top: 7px !important;
        }
        header.header-area .menu-trigger.active span {
          background-color: transparent !important;
        }
        header.header-area .menu-trigger.active span:before {
          top: 0 !important;
          transform: rotate(45deg) !important;
        }
        header.header-area .menu-trigger.active span:after {
          top: 0 !important;
          transform: rotate(-45deg) !important;
        }
        /* Mobile Menu Drawer */
        header.header-area .main-nav .nav {
          display: none;
          position: fixed !important;
          top: 64px !important;
          left: 0 !important;
          right: 0 !important;
          width: 100% !important;
          background-color: #FFFFFF !important;
          box-shadow: 0 16px 32px rgba(10, 61, 36, 0.18) !important;
          border-top: 2px solid #007A1E !important;
          border-bottom: 4px solid var(--mahad-gold) !important;
          padding: 16px 20px 24px 20px !important;
          flex-direction: column !important;
          gap: 6px !important;
          z-index: 99999 !important;
          list-style: none !important;
          margin: 0 !important;
          max-height: calc(100vh - 64px) !important;
          overflow-y: auto !important;
        }
        header.header-area .main-nav .nav.mobile-active {
          display: flex !important;
        }
        header.header-area .main-nav .nav li {
          width: 100% !important;
          margin: 0 !important;
          padding: 0 !important;
        }
        header.header-area .main-nav .nav li a {
          display: block !important;
          width: 100% !important;
          padding: 11px 16px !important;
          font-size: 15px !important;
          font-weight: 600 !important;
          color: #0A3D24 !important;
          border-radius: 8px !important;
          text-align: left !important;
          background: transparent !important;
        }
        header.header-area .main-nav .nav li a:hover,
        header.header-area .main-nav .nav li a.active {
          background-color: #EBF5EE !important;
          color: #007A1E !important;
        }
        header.header-area .main-nav .nav li .main-red-button {
          margin-top: 10px !important;
          width: 100% !important;
        }
        header.header-area .main-nav .nav li .main-red-button a {
          display: block !important;
          width: 100% !important;
          text-align: center !important;
          padding: 12px !important;
          background-color: #007A1E !important;
          color: #FFFFFF !important;
          border-radius: 8px !important;
          font-weight: 700 !important;
        }
        /* Menghilangkan penutupan Judul Hero di Layar HP */
        .main-banner {
          padding-top: 96px !important;
          padding-bottom: 50px !important;
        }
        .main-banner .left-content h1 {
          font-size: 27px !important;
          line-height: 1.3 !important;
        }
      }

      /* Alur Ujian Background Gradient Tanpa Gelombang Merah */
      .about-us {
        background-image: none !important;
        background: linear-gradient(180deg, #F4FAF5 0%, #FFFFFF 100%) !important;
      }

      /* Hero Section */
      .main-banner {
        padding: 160px 0 100px 0 !important;
        background: linear-gradient(180deg, #F0F6F2 0%, #FFFFFF 100%);
        position: relative;
      }
      .main-banner .left-content h6 {
        color: var(--mahad-primary);
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 12px;
      }
      .main-banner .left-content h1 {
        font-size: 40px;
        line-height: 1.25;
        color: var(--mahad-dark);
        margin-bottom: 20px;
      }
      .main-banner .left-content h1 em {
        font-style: normal;
        color: var(--mahad-primary);
      }
      .main-banner .left-content h1 span {
        color: var(--mahad-gold);
      }
      .main-banner .left-content p {
        font-size: 16px;
        line-height: 1.7;
        color: #4A5568;
        margin-bottom: 28px;
      }
      .badge-academic {
        display: inline-flex;
        align-items: center;
        background-color: #E6F3EA;
        color: var(--mahad-dark);
        border: 1px solid #C2E2CC;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 18px;
      }
      .badge-academic i {
        color: var(--mahad-gold);
        margin-right: 8px;
      }

      /* Hero Academic Visual Card (Pengganti ilustrasi perkantoran tanpa AI Slop) */
      .academic-hero-card {
        background: var(--mahad-white);
        border-radius: 16px;
        box-shadow: 0 20px 45px rgba(10, 61, 36, 0.12);
        border: 1px solid #E2EDE4;
        overflow: hidden;
        position: relative;
      }
      .academic-hero-header {
        background: linear-gradient(135deg, var(--mahad-dark) 0%, var(--mahad-primary) 100%);
        padding: 24px 28px;
        color: var(--mahad-white);
        border-bottom: 4px solid var(--mahad-gold);
      }
      .academic-hero-header .inst-tag {
        font-size: 12px;
        font-weight: 700;
        color: var(--mahad-gold);
        letter-spacing: 1.5px;
        text-transform: uppercase;
      }
      .academic-hero-header h3 {
        color: #FFFFFF !important;
        font-size: 20px;
        margin: 4px 0 0;
      }
      .academic-hero-body {
        padding: 26px 28px;
      }
      .feature-pillar {
        display: flex;
        align-items: flex-start;
        padding: 12px 14px;
        background: #F8FAF9;
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 3px solid var(--mahad-primary);
      }
      .feature-pillar .pillar-icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background: #E8F5EB;
        color: var(--mahad-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 14px;
        flex-shrink: 0;
      }
      .feature-pillar h5 {
        font-size: 14px;
        margin: 0 0 2px;
        color: var(--mahad-dark);
      }
      .feature-pillar p {
        font-size: 12px;
        margin: 0;
        color: #718096;
        line-height: 1.4;
      }

      /* Section Headings */
      .section-heading h2 {
        color: var(--mahad-dark);
        font-size: 32px;
        position: relative;
        padding-bottom: 14px;
      }
      .section-heading h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--mahad-gold);
      }
      .section-heading.text-center h2:after {
        left: 50%;
        transform: translateX(-50%);
      }
      .section-heading h2 em {
        font-style: normal;
        color: var(--mahad-primary);
      }
      .section-heading h2 span {
        color: var(--mahad-gold);
      }

      /* Cara Kerja Cards */
      .about-us .services .item {
        background: #FFFFFF;
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(10, 61, 36, 0.06);
        border: 1px solid #EDF4EE;
        border-top: 3px solid var(--mahad-primary);
        transition: all 0.3s ease;
      }
      .about-us .services .item:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(10, 61, 36, 0.12);
        border-top-color: var(--mahad-gold);
      }
      .about-us .services .item h4 {
        color: var(--mahad-dark);
        font-size: 17px;
      }

      /* Progress Bars */
      .progress-skill-bar .filled-bar {
        background: linear-gradient(90deg, var(--mahad-primary) 0%, var(--mahad-gold) 100%) !important;
      }

      /* Contact Section */
      .contact-us {
        background: #F8FAF9;
        border-top: 1px solid #E2EDE4;
        padding: 90px 0;
      }
      .contact-info-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(10, 61, 36, 0.06);
        border: 1px solid #E2EDE4;
      }
      .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 22px;
      }
      .contact-item .c-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #EAF4EC;
        color: var(--mahad-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-right: 16px;
        flex-shrink: 0;
      }
      .contact-item h5 {
        font-size: 14px;
        margin: 0 0 4px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      .contact-item p {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        color: var(--mahad-dark);
      }
      .contact-item a {
        color: var(--mahad-primary);
        text-decoration: none;
      }
      .contact-item a:hover {
        text-decoration: underline;
      }

      /* Form Styling */
      .contact-us form {
        background: #FFFFFF;
        padding: 36px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(10, 61, 36, 0.06);
        border: 1px solid #E2EDE4;
      }
      .contact-us form input,
      .contact-us form textarea {
        border: 1px solid #D1E3D4 !important;
        border-radius: 6px !important;
        font-size: 14px;
      }
      .contact-us form input:focus,
      .contact-us form textarea:focus {
        border-color: var(--mahad-primary) !important;
        box-shadow: 0 0 0 3px rgba(0, 122, 30, 0.15) !important;
      }

      /* Footer */
      footer {
        background: var(--mahad-dark) !important;
        color: #E2EDE4 !important;
        padding: 40px 0 30px 0 !important;
        border-top: 4px solid var(--mahad-gold);
      }
      footer p {
        color: #D1E3D4 !important;
        font-size: 14px;
      }
      footer a {
        color: var(--mahad-gold) !important;
        font-weight: 600;
        text-decoration: none;
      }
      footer a:hover {
        text-decoration: underline;
      }
    </style>
  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav d-flex align-items-center justify-content-between">
            <!-- ***** Logo Start ***** -->
            <a href="<?= htmlspecialchars($homeUrl) ?>" class="logo d-flex align-items-center text-decoration-none">
              <img src="assets/images/logo.png?v=2" alt="e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng" class="header-logo-img">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="scroll-to-section"><a href="#top" class="active">Beranda</a></li>
              <li class="scroll-to-section"><a href="#about">Alur Ujian</a></li>
              <li class="scroll-to-section"><a href="#services">TOEFL</a></li>
              <li class="scroll-to-section"><a href="#toeic">TOEIC</a></li>
              <li class="scroll-to-section"><a href="#portfolio">Sertifikat</a></li> 
              <li class="scroll-to-section"><a href="#contact">Hubungi Kami</a></li> 
              <li class="scroll-to-section"><div class="main-red-button"><a href="<?= htmlspecialchars($loginUrl) ?>">Masuk Peserta</a></div></li> 
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->

  <!-- ***** Hero Banner Start ***** -->
  <div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.6s">
            <div class="badge-academic">
              <i class="fa fa-certificate"></i> Pusat Bahasa Ma'had Aly Hasyim Asy'ari
            </div>
            <h6>Portal Resmi Uji Kompetensi Bahasa Inggris</h6>
            <h1>e-TOEFL <em>Ma'had Aly</em><br><span>Hasyim Asy'ari</span></h1>
            <p>
              Platform simulasi dan prediksi kemahiran bahasa Inggris (TOEFL & TOEIC Prediction Test) terstandar untuk Mahasantri Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang. Uji kesiapan bahasa global Anda dan raih sertifikat resmi untuk mendukung studi akademik serta dakwah internasional.
            </p>
            <div class="d-flex flex-wrap gap-2">
              <a href="<?= htmlspecialchars($registerUrl) ?>" class="btn btn-mahad-primary btn-lg px-4 py-3 me-2">
                <i class="fa fa-user-plus me-1"></i> Registrasi Akun
              </a>
              <a href="<?= htmlspecialchars($loginUrl) ?>" class="btn btn-outline-success btn-lg px-4 py-3" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
                <i class="fa fa-sign-in me-1"></i> Masuk Ujian
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
          <div class="academic-hero-card wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.7s">
            <div class="academic-hero-header">
              <div class="inst-tag">STANDARISASI AKADEMIK PESANTREN</div>
              <h3>Simulasi e-TOEFL Terpadu</h3>
            </div>
            <div class="academic-hero-body">
              <div class="feature-pillar">
                <div class="pillar-icon"><i class="fa fa-headphones"></i></div>
                <div>
                  <h5>1. Listening Comprehension</h5>
                  <p>Audio playback interaktif dengan pembatasan pemutaran terstandar ETS.</p>
                </div>
              </div>
              <div class="feature-pillar">
                <div class="pillar-icon"><i class="fa fa-pencil-square-o"></i></div>
                <div>
                  <h5>2. Structure & Written Expression</h5>
                  <p>Pengujian tata bahasa, struktur kalimat formal, dan error analysis terukur.</p>
                </div>
              </div>
              <div class="feature-pillar">
                <div class="pillar-icon"><i class="fa fa-book"></i></div>
                <div>
                  <h5>3. Reading Comprehension</h5>
                  <p>Evaluasi pemahaman teks bacaan akademik, inferensi, dan kosakata ilmiah.</p>
                </div>
              </div>
              <div class="feature-pillar" style="margin-bottom: 0; border-left-color: var(--mahad-gold);">
                <div class="pillar-icon" style="color: var(--mahad-gold); background: #FCF8EA;"><i class="fa fa-check-circle"></i></div>
                <div>
                  <h5>Sertifikat Digital Otomatis</h5>
                  <p>Skor terhitung langsung dengan penerbitan sertifikat digital instan ber-barcode.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ***** Hero Banner End ***** -->

  <!-- ***** Alur Ujian (Cara Kerja) Start ***** -->
  <div id="about" class="about-us section" style="padding: 90px 0;">
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
          <div class="section-heading text-center">
            <h2>Alur Pelaksanaan <em>Ujian e-TOEFL</em></h2>
            <p class="mt-3">Langkah terstruktur mulai dari pendaftaran akun hingga penerbitan sertifikat resmi</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="services">
            <div class="row">
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="item p-4 text-center h-100 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                  <div class="mb-3">
                    <span class="badge rounded-pill bg-success px-3 py-2" style="background-color: var(--mahad-primary) !important; font-size: 14px;">Langkah 1</span>
                  </div>
                  <h4>Buat Akun Peserta</h4>
                  <p class="mt-2 text-muted">Daftar secara instan menggunakan alamat email aktif untuk menerima tautan aktivasi akun.</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="item p-4 text-center h-100 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.4s">
                  <div class="mb-3">
                    <span class="badge rounded-pill bg-success px-3 py-2" style="background-color: var(--mahad-primary) !important; font-size: 14px;">Langkah 2</span>
                  </div>
                  <h4>Pilih Paket Ujian</h4>
                  <p class="mt-2 text-muted">Pilih paket try out TOEFL atau TOEIC sesuai jadwal yang disediakan oleh Ma'had Aly.</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="item p-4 text-center h-100 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.6s">
                  <div class="mb-3">
                    <span class="badge rounded-pill bg-success px-3 py-2" style="background-color: var(--mahad-primary) !important; font-size: 14px;">Langkah 3</span>
                  </div>
                  <h4>Kerjakan Ujian Online</h4>
                  <p class="mt-2 text-muted">Kerjakan soal secara mandiri dengan alokasi waktu dan sistem pemutaran audio listening terpadu.</p>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="item p-4 text-center h-100 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                  <div class="mb-3">
                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2" style="background-color: var(--mahad-gold) !important; color: #FFFFFF !important; font-size: 14px;">Langkah 4</span>
                  </div>
                  <h4>Unduh Sertifikat</h4>
                  <p class="mt-2 text-muted">Skor langsung dikalkulasi dan sertifikat digital prediksi dapat diunduh untuk kebutuhan akademik.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ***** Alur Ujian End ***** -->

  <!-- ***** TOEFL Info Section ***** -->
  <div id="services" class="our-services section" style="padding: 90px 0; background-color: #F8FAF9;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
          <div class="p-3 text-center">
            <img src="assets/images/toefl.png" alt="TOEFL Prediction" class="img-fluid rounded shadow-sm" style="max-height: 380px;">
          </div>
        </div>
        <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
          <div class="section-heading">
            <h2>Ujian <em>TOEFL Prediction</em></h2>
            <p class="mt-3">
              <em>Test of English as a Foreign Language</em> (TOEFL) Paper-Based Test (PBT) simulasi dirancang untuk mengukur kemahiran bahasa Inggris non-penutur asli dalam ranah akademik. Di Ma'had Aly Hasyim Asy'ari, hasil ujian ini menjadi tolok ukur kesiapan mahasantri dalam menelaah literatur internasional dan melanjutkan studi ke jenjang yang lebih tinggi.
            </p>
          </div>
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="first-bar progress-skill-bar">
                <h4>Listening Comprehension</h4>
                <span>Skor Maks. 68</span>
                <div class="filled-bar" style="width: 100%;"></div>
                <div class="full-bar"></div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="second-bar progress-skill-bar">
                <h4>Structure and Written Expression</h4>
                <span>Skor Maks. 68</span>
                <div class="filled-bar" style="width: 100%;"></div>
                <div class="full-bar"></div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="third-bar progress-skill-bar">
                <h4>Reading Comprehension</h4>
                <span>Skor Maks. 67</span>
                <div class="filled-bar" style="width: 100%;"></div>
                <div class="full-bar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ***** TOEIC Info Section ***** -->
  <div id="toeic" class="our-services section" style="padding: 90px 0;">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
          <div class="p-3 text-center">
            <img src="assets/images/toeic.png" alt="TOEIC Prediction" class="img-fluid rounded shadow-sm" style="max-height: 380px;">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
          <div class="section-heading">
            <h2>Ujian <em>TOEIC</em> <span>Prediction</span></h2>
            <p class="mt-3">
              <em>Test of English for International Communication</em> (TOEIC) mengukur kemampuan bahasa Inggris praktis dalam komunikasi kerja dan lingkungan internasional. Cocok untuk menguji kemampuan reseptif (Listening & Reading) dalam konteks profesional.
            </p>
          </div>
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="first-bar progress-skill-bar">
                <h4>Listening Comprehension</h4>
                <span>Skor Maks. 495</span>
                <div class="filled-bar" style="width: 100%;"></div>
                <div class="full-bar"></div>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="second-bar progress-skill-bar">
                <h4>Reading Comprehension</h4>
                <span>Skor Maks. 495</span>
                <div class="filled-bar" style="width: 100%;"></div>
                <div class="full-bar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ***** Sertifikat Section ***** -->
  <div id="portfolio" class="our-portfolio section" style="padding: 90px 0; background-color: #F8FAF9;">
    <div class="container">
      <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
          <div class="section-heading text-center">
            <h2>Penerbitan <em>Sertifikat</em> <span>Digital</span></h2>
            <p class="mt-3">Setiap peserta yang menyelesaikan ujian akan menerima lembar sertifikat resmi yang mencantumkan skor per sub-tes serta skor total terstandarisasi.</p>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="card border-0 shadow-sm rounded-3 overflow-hidden text-center h-100">
            <img src="assets/images/sertifikat-toefl.jpg" class="card-img-top" alt="Contoh Sertifikat TOEFL">
            <div class="card-body p-4">
              <h5 class="fw-bold" style="color: var(--mahad-dark);">Sertifikat e-TOEFL Prediction</h5>
              <p class="text-muted small mt-2">Mencantumkan nilai Listening, Structure, Reading, dan Total Skor Prediksi (Skala 310 - 677).</p>
            </div>
          </div>
        </div>
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="card border-0 shadow-sm rounded-3 overflow-hidden text-center h-100">
            <img src="assets/images/sertifikat-toeic.jpg" class="card-img-top" alt="Contoh Sertifikat TOEIC">
            <div class="card-body p-4">
              <h5 class="fw-bold" style="color: var(--mahad-dark);">Sertifikat TOEIC Prediction</h5>
              <p class="text-muted small mt-2">Mencantumkan nilai Listening, Reading, dan Total Skor Prediksi (Skala 10 - 990).</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ***** Kontak Admin & Hubungi Kami ***** -->
  <div id="contact" class="contact-us section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.25s">
          <div class="contact-info-card">
            <div class="section-heading mb-4">
              <h2>Hubungi <em>Layanan</em> Kami</h2>
              <p>Pusat Layanan Bahasa & Pengelola Ujian e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng</p>
            </div>
            
            <div class="contact-item">
              <div class="c-icon"><i class="fa fa-map-marker"></i></div>
              <div>
                <h5>Alamat Institusi</h5>
                <p><?= htmlspecialchars($adminAddress) ?></p>
              </div>
            </div>

            <div class="contact-item">
              <div class="c-icon"><i class="fa fa-envelope"></i></div>
              <div>
                <h5>Email Resmi</h5>
                <p><a href="mailto:<?= htmlspecialchars($adminEmail) ?>"><?= htmlspecialchars($adminEmail) ?></a></p>
              </div>
            </div>

            <div class="contact-item">
              <div class="c-icon"><i class="fa fa-whatsapp"></i></div>
              <div>
                <h5>WhatsApp Helpdesk</h5>
                <p><a href="<?= htmlspecialchars($adminWaLink) ?>" target="_blank"><?= htmlspecialchars($adminWaDisplay) ?></a></p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-7 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.25s">
          <form id="contact-form" action="" method="post">
            <h4 class="mb-3 fw-bold" style="color: var(--mahad-dark);">Kirim Pesan ke Helpdesk</h4>
            <p class="text-muted small mb-4">Silakan lengkapi formulir di bawah ini untuk terhubung langsung dengan admin bantuan kami via WhatsApp resmi.</p>
            <div class="row">
              <div class="col-lg-6 mb-3">
                <input type="text" name="name" id="name" class="form-control py-2" placeholder="Nama Depan" required>
              </div>
              <div class="col-lg-6 mb-3">
                <input type="text" name="surname" id="surname" class="form-control py-2" placeholder="Nama Belakang" required>
              </div>
              <div class="col-lg-12 mb-3">
                <input type="email" name="email" id="email" class="form-control py-2" placeholder="Alamat Email Anda" required>
              </div>
              <div class="col-lg-12 mb-3">
                <textarea name="message" class="form-control" id="message" rows="4" placeholder="Tuliskan kendala atau pertanyaan Anda..." required></textarea>  
              </div>
              <div class="col-lg-12">
                <button type="submit" class="btn btn-mahad-primary w-100 py-3">
                  <i class="fa fa-whatsapp me-2"></i> Kirim Pesan via WhatsApp
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ***** Footer ***** -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h5 class="text-white fw-bold mb-2">MA'HAD ALY HASYIM ASY'ARI</h5>
          <p class="mb-2">PONDOK PESANTREN TEBUIRENG JOMBANG</p>
          <p class="small text-muted mb-3"><?= htmlspecialchars($adminAddress) ?></p>
          <p class="small mb-0">
            © <?= date('Y') ?> e-TOEFL Ma'had Aly Hasyim Asy'ari. Hak Cipta Dilindungi. | 
            <a href="https://mahatbi.or.id" target="_blank" rel="noopener">mahatbi.or.id</a>
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/templatemo-custom.js"></script>

  <script type="text/javascript">
    document.getElementById("contact-form").addEventListener("submit", function (event) {
        event.preventDefault();

        var name = document.getElementById("name").value.trim();
        var surname = document.getElementById("surname").value.trim();
        var email = document.getElementById("email").value.trim();
        var message = document.getElementById("message").value.trim();
        var waTarget = '<?= addslashes($adminWaLink) ?>';

        var encodedText = encodeURIComponent(
            "Assalamu'alaikum Admin e-TOEFL Ma'had Aly Tebuireng,\n\n" +
            "Nama: " + name + " " + surname + "\n" +
            "Email: " + email + "\n" +
            "Pertanyaan / Kendala:\n" + message
        );

        var finalLink;
        if (waTarget.indexOf('?') !== -1) {
            finalLink = waTarget + '&text=' + encodedText;
        } else {
            finalLink = waTarget + '?text=' + encodedText;
        }

        window.open(finalLink, '_blank');
    });

    // Mobile Hamburger Menu Interactive Handler
    $(document).ready(function () {
        $('.menu-trigger').off('click').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('.header-area .main-nav .nav').toggleClass('mobile-active');
        });

        // Tutup menu drawer otomatis saat salah satu tautan menu di-klik
        $('.header-area .main-nav .nav li a').on('click', function () {
            if ($(window).width() < 992) {
                $('.menu-trigger').removeClass('active');
                $('.header-area .main-nav .nav').removeClass('mobile-active');
            }
        });
    });
  </script>

</body>
</html>
