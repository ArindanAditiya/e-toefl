<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- SEO & Identitas Resmi Ma'had Aly Hasyim Asy'ari Tebuireng -->
  <title>{{ $appName }} - Pondok Pesantren Tebuireng Jombang</title>
  <meta name="description" content="Platform resmi Ujian e-TOEFL & Prediksi Kemampuan Bahasa Inggris Mahasantri Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang. Uji kompetensi bahasa terstandar untuk studi akademik dan dakwah internasional.">
  <meta name="keywords" content="e-toefl, toefl prediction, toeic, mahad aly, hasyim asy'ari, tebuireng, jombang, ujian toefl santri, pesantren, sertifikat toefl">
  <meta name="author" content="Ma'had Aly Hasyim Asy'ari Tebuireng">
  <meta name="theme-color" content="#0A3D24">

  <!-- Open Graph Protocol -->
  <meta property="og:title" content="{{ $appName }} - Ma'had Aly Tebuireng">
  <meta property="og:description" content="Portal Resmi Uji Kompetensi e-TOEFL & TOEIC Prediction Test Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:site_name" content="Ma'had Aly Hasyim Asy'ari Tebuireng">

  <!-- Favicon Resmi -->
  <link rel="icon" type="image/png" href="{{ asset('id/assets/images/favicon.png') }}?v=2">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
  <link rel="apple-touch-icon" href="{{ asset('id/assets/images/favicon.png') }}?v=2">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS & Font Awesome -->
  <link href="{{ asset('id/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('id/assets/css/fontawesome.css') }}">

  <style>
    :root {
      --mahad-dark: #0A3D24;
      --mahad-primary: #007A1E;
      --mahad-gold: #C69C2B;
      --mahad-gold-light: #E0BA55;
      --mahad-white: #FFFFFF;
      --mahad-bg: #F8FAFC;
      --mahad-soft: #EBF5EE;
      --mahad-text: #2D3748;
      --mahad-muted: #64748B;
      --mahad-border: #E2E8F0;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--mahad-text);
      background-color: var(--mahad-white);
      line-height: 1.6;
      overflow-x: hidden;
    }

    h1, h2, h3, h4, h5, h6 {
      font-weight: 700;
      color: var(--mahad-dark);
      letter-spacing: -0.3px;
    }

    a {
      text-decoration: none;
      transition: all 0.25s ease;
    }

    /* ==========================================================================
       HEADER & NAVIGATION BAR (100% Flexbox Murni, Responsif & Anti-Truncation)
       ========================================================================== */
    .site-header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background-color: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(8px);
      box-shadow: 0 2px 14px rgba(10, 61, 36, 0.08);
      border-bottom: 3px solid var(--mahad-gold);
      z-index: 99999;
      height: 72px;
      display: flex;
      align-items: center;
      transition: all 0.3s ease;
    }

    .header-container {
      width: 100%;
      max-width: 1240px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    /* Logo Brand */
    .header-brand {
      display: flex;
      align-items: center;
      flex-shrink: 0;
    }

    .header-logo-img {
      height: 46px;
      width: auto;
      max-width: 260px;
      object-fit: contain;
      display: block;
      transition: all 0.3s ease;
    }

    /* Navigasi Desktop */
    .header-nav-list {
      display: flex;
      align-items: center;
      gap: 6px;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .header-nav-link {
      display: inline-block;
      padding: 8px 13px;
      font-size: 14.5px;
      font-weight: 600;
      color: var(--mahad-dark);
      border-radius: 6px;
      white-space: nowrap;
    }

    .header-nav-link:hover,
    .header-nav-link.active {
      color: var(--mahad-primary);
      background-color: var(--mahad-soft);
    }

    /* Tombol Aksi Desktop */
    .header-actions-desktop {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-shrink: 0;
    }

    .btn-login-header {
      padding: 8px 18px;
      font-size: 14px;
      font-weight: 700;
      color: var(--mahad-primary);
      border: 1.5px solid var(--mahad-primary);
      border-radius: 6px;
      background: transparent;
    }

    .btn-login-header:hover {
      background-color: var(--mahad-soft);
      color: var(--mahad-dark);
    }

    .btn-register-header {
      padding: 8px 20px;
      font-size: 14px;
      font-weight: 700;
      color: #FFFFFF;
      background-color: var(--mahad-primary);
      border: 1.5px solid var(--mahad-primary);
      border-radius: 6px;
      box-shadow: 0 4px 10px rgba(0, 122, 30, 0.25);
    }

    .btn-register-header:hover {
      background-color: var(--mahad-dark);
      border-color: var(--mahad-dark);
      color: #FFFFFF;
    }

    /* Tombol Hamburger Menu Mobile */
    .mobile-hamburger-btn {
      display: none;
      background: var(--mahad-soft);
      border: 1px solid #C2E2CC;
      width: 42px;
      height: 42px;
      border-radius: 8px;
      cursor: pointer;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 5px;
      padding: 0;
      z-index: 100000;
    }

    .hamburger-line {
      width: 22px;
      height: 2.5px;
      background-color: var(--mahad-dark);
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .mobile-hamburger-btn.active .hamburger-line:nth-child(1) {
      transform: translateY(7.5px) rotate(45deg);
    }

    .mobile-hamburger-btn.active .hamburger-line:nth-child(2) {
      opacity: 0;
    }

    .mobile-hamburger-btn.active .hamburger-line:nth-child(3) {
      transform: translateY(-7.5px) rotate(-45deg);
    }

    /* Mobile Drawer Menu */
    .mobile-nav-drawer {
      display: none;
      position: fixed;
      top: 64px;
      left: 0;
      right: 0;
      background-color: #FFFFFF;
      box-shadow: 0 16px 32px rgba(10, 61, 36, 0.16);
      border-top: 1px solid var(--mahad-border);
      border-bottom: 4px solid var(--mahad-gold);
      padding: 16px 20px 24px 20px;
      flex-direction: column;
      gap: 6px;
      z-index: 99998;
      max-height: calc(100vh - 64px);
      overflow-y: auto;
    }

    .mobile-nav-drawer.open {
      display: flex;
      animation: slideDown 0.25s ease-out forwards;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .mobile-nav-item {
      display: block;
      padding: 11px 16px;
      font-size: 15px;
      font-weight: 600;
      color: var(--mahad-dark);
      border-radius: 8px;
    }

    .mobile-nav-item:hover,
    .mobile-nav-item.active {
      background-color: var(--mahad-soft);
      color: var(--mahad-primary);
    }

    .mobile-nav-actions {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px dashed var(--mahad-border);
    }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS (HP / iPhone 13 Pro < 992px)
       ========================================================================== */
    @media (max-width: 991px) {
      .site-header {
        height: 64px;
        padding: 0;
      }
      .header-container {
        padding: 0 16px;
      }
      .header-logo-img {
        height: 36px;
        max-width: 200px;
      }
      .header-nav-list,
      .header-actions-desktop {
        display: none !important;
      }
      .mobile-hamburger-btn {
        display: flex !important;
      }
    }

    /* ==========================================================================
       HERO BANNER SECTION
       ========================================================================== */
    .hero-section {
      padding-top: 124px;
      padding-bottom: 60px;
      background: linear-gradient(180deg, #F0F6F2 0%, #FFFFFF 100%);
      position: relative;
    }

    @media (max-width: 991px) {
      .hero-section {
        padding-top: 86px;
        padding-bottom: 40px;
      }
    }

    .badge-academic-hero {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background-color: #E6F3EA;
      color: var(--mahad-dark);
      border: 1px solid #C2E2CC;
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 16px;
    }

    .badge-academic-hero i {
      color: var(--mahad-gold);
    }

    .hero-title {
      font-size: 42px;
      line-height: 1.22;
      color: var(--mahad-dark);
      margin-bottom: 16px;
    }

    .hero-title em {
      font-style: normal;
      color: var(--mahad-primary);
    }

    .hero-title span {
      color: var(--mahad-gold);
    }

    @media (max-width: 767px) {
      .hero-title {
        font-size: 27px;
        line-height: 1.28;
      }
    }

    .hero-desc {
      font-size: 16px;
      line-height: 1.7;
      color: #4A5568;
      margin-bottom: 24px;
    }

    /* Hero Academic Visual Card */
    .academic-hero-card {
      background: var(--mahad-white);
      border-radius: 16px;
      box-shadow: 0 16px 36px rgba(10, 61, 36, 0.1);
      border: 1px solid #E2EDE4;
      overflow: hidden;
    }

    .academic-hero-header {
      background: linear-gradient(135deg, var(--mahad-dark) 0%, var(--mahad-primary) 100%);
      padding: 22px 24px;
      color: var(--mahad-white);
      border-bottom: 4px solid var(--mahad-gold);
    }

    .academic-hero-header .inst-tag {
      font-size: 11.5px;
      font-weight: 700;
      color: var(--mahad-gold);
      letter-spacing: 1.2px;
      text-transform: uppercase;
    }

    .academic-hero-header h3 {
      color: #FFFFFF !important;
      font-size: 19px;
      margin: 4px 0 0;
    }

    .academic-hero-body {
      padding: 22px 24px;
    }

    .feature-pillar {
      display: flex;
      align-items: flex-start;
      padding: 11px 14px;
      background: #F8FAF9;
      border-radius: 8px;
      margin-bottom: 10px;
      border-left: 3px solid var(--mahad-primary);
    }

    .feature-pillar .pillar-icon {
      width: 34px;
      height: 34px;
      border-radius: 6px;
      background: #E8F5EB;
      color: var(--mahad-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
      margin-right: 12px;
      flex-shrink: 0;
    }

    .feature-pillar h5 {
      font-size: 13.5px;
      margin: 0 0 2px;
      color: var(--mahad-dark);
    }

    .feature-pillar p {
      font-size: 12px;
      margin: 0;
      color: #718096;
      line-height: 1.35;
    }

    /* ==========================================================================
       SECTION HEADINGS & GENERAL PADDING (Rapat, Padat Berisi)
       ========================================================================== */
    .section-wrap {
      padding: 55px 0;
    }

    @media (max-width: 767px) {
      .section-wrap {
        padding: 38px 0;
      }
    }

    .section-header {
      text-align: center;
      margin-bottom: 36px;
    }

    .section-tag {
      display: inline-block;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: var(--mahad-primary);
      background-color: var(--mahad-soft);
      padding: 4px 12px;
      border-radius: 50px;
      margin-bottom: 8px;
    }

    .section-title {
      font-size: 30px;
      color: var(--mahad-dark);
      margin-bottom: 8px;
    }

    .section-title em {
      font-style: normal;
      color: var(--mahad-primary);
    }

    .section-desc {
      font-size: 15px;
      color: var(--mahad-muted);
      max-width: 680px;
      margin: 0 auto;
    }

    @media (max-width: 767px) {
      .section-title {
        font-size: 24px;
      }
      .section-desc {
        font-size: 14px;
      }
    }

    /* ==========================================================================
       ALUR UJIAN CARDS (4 Langkah)
       ========================================================================== */
    .step-card {
      background: #FFFFFF;
      border: 1px solid var(--mahad-border);
      border-radius: 12px;
      padding: 24px 20px;
      text-align: center;
      height: 100%;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
      transition: all 0.3s ease;
      position: relative;
    }

    .step-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 24px rgba(10, 61, 36, 0.08);
      border-color: var(--mahad-primary);
    }

    .step-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 0;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--mahad-soft);
      color: var(--mahad-primary);
      font-weight: 800;
      font-size: 15px;
      margin-bottom: 14px;
      border: 2px solid #C2E2CC;
      flex-shrink: 0;
      text-align: center;
    }

    .step-badge i {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 0;
      font-size: 18px;
    }

    .step-badge.badge-whatsapp {
      background-color: #25D366;
      color: #FFFFFF;
      border-color: #1EBE5D;
      box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
    }

    .floating-wa-btn {
      position: fixed;
      bottom: 24px;
      right: 24px;
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background-color: #25D366;
      color: #FFFFFF;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 0;
      font-size: 28px;
      box-shadow: 0 4px 16px rgba(37, 211, 102, 0.45);
      z-index: 99990;
      transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .floating-wa-btn:hover {
      background-color: #1EBE5D;
      color: #FFFFFF;
      transform: translateY(-3px) scale(1.08);
      box-shadow: 0 8px 22px rgba(37, 211, 102, 0.55);
    }

    .floating-wa-btn i {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 0;
    }

    .step-card h4 {
      font-size: 16px;
      margin-bottom: 8px;
      color: var(--mahad-dark);
    }

    .step-card p {
      font-size: 13.5px;
      color: var(--mahad-muted);
      margin: 0;
      line-height: 1.5;
    }

    /* ==========================================================================
       FITUR 1: INTERACTIVE MINI SAMPLE TEST WIDGET
       ========================================================================== */
    .sample-test-box {
      background: #FFFFFF;
      border-radius: 16px;
      border: 1px solid #D1E5D8;
      box-shadow: 0 12px 32px rgba(10, 61, 36, 0.08);
      overflow: hidden;
    }

    .sample-nav-tabs {
      display: flex;
      background-color: #F1F7F3;
      border-bottom: 1px solid #D1E5D8;
      padding: 8px 12px 0 12px;
      gap: 6px;
      overflow-x: auto;
    }

    .sample-tab-btn {
      padding: 10px 18px;
      font-size: 13.5px;
      font-weight: 700;
      color: var(--mahad-muted);
      background: transparent;
      border: none;
      border-radius: 8px 8px 0 0;
      cursor: pointer;
      white-space: nowrap;
    }

    .sample-tab-btn.active {
      color: var(--mahad-dark);
      background-color: #FFFFFF;
      border: 1px solid #D1E5D8;
      border-bottom: 1px solid #FFFFFF;
    }

    .sample-content {
      padding: 26px 28px;
    }

    @media (max-width: 767px) {
      .sample-content {
        padding: 18px 16px;
      }
    }

    .question-stem {
      font-size: 16px;
      font-weight: 600;
      color: var(--mahad-dark);
      margin-bottom: 18px;
      line-height: 1.5;
    }

    .options-grid {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 20px;
    }

    .option-btn {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1.5px solid var(--mahad-border);
      background-color: #FFFFFF;
      cursor: pointer;
      text-align: left;
      font-size: 14.5px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .option-btn:hover {
      border-color: var(--mahad-primary);
      background-color: var(--mahad-soft);
    }

    .option-btn.selected-correct {
      border-color: #007A1E !important;
      background-color: #EBF5EE !important;
      color: #0A3D24 !important;
      font-weight: 700;
    }

    .option-btn.selected-wrong {
      border-color: #DC2626 !important;
      background-color: #FEF2F2 !important;
      color: #991B1B !important;
      font-weight: 700;
    }

    .option-letter {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: #F1F5F9;
      font-weight: 700;
      margin-right: 12px;
      font-size: 13px;
      flex-shrink: 0;
    }

    .option-btn.selected-correct .option-letter {
      background: #007A1E;
      color: #FFFFFF;
    }

    .option-btn.selected-wrong .option-letter {
      background: #DC2626;
      color: #FFFFFF;
    }

    .explanation-card {
      display: none;
      padding: 14px 18px;
      border-radius: 8px;
      background-color: #F8FAF9;
      border-left: 4px solid var(--mahad-gold);
      font-size: 13.5px;
      line-height: 1.5;
    }

    /* ==========================================================================
       FITUR 2: DETAIL PAKET (TOEFL VS TOEIC) TAB SWITCHER
       ========================================================================== */
    .package-card {
      background: #FFFFFF;
      border: 1.5px solid var(--mahad-border);
      border-radius: 16px;
      padding: 30px 24px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
      height: 100%;
      transition: all 0.3s ease;
      position: relative;
    }

    .package-card.featured {
      border-color: var(--mahad-primary);
      box-shadow: 0 12px 32px rgba(0, 122, 30, 0.12);
    }

    .package-ribbon {
      position: absolute;
      top: -12px;
      right: 20px;
      background-color: var(--mahad-gold);
      color: #FFFFFF;
      font-size: 11px;
      font-weight: 800;
      padding: 4px 12px;
      border-radius: 50px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .package-card h3 {
      font-size: 22px;
      color: var(--mahad-dark);
      margin-bottom: 8px;
    }

    .package-features {
      list-style: none;
      padding: 0;
      margin: 20px 0 24px 0;
    }

    .package-features li {
      font-size: 14px;
      color: #4A5568;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .package-features li i {
      color: var(--mahad-primary);
      font-size: 14px;
    }

    /* ==========================================================================
       FITUR 3: SCORE CALCULATOR & MATRIX
       ========================================================================== */
    .calculator-card {
      background: #FFFFFF;
      border: 1px solid #D2E5DA;
      border-radius: 16px;
      padding: 28px 24px;
      box-shadow: 0 10px 28px rgba(10, 61, 36, 0.06);
    }

    .slider-group {
      margin-bottom: 20px;
    }

    .slider-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 6px;
    }

    .slider-label {
      font-size: 14px;
      font-weight: 600;
      color: var(--mahad-dark);
    }

    .slider-val-badge {
      background-color: var(--mahad-soft);
      color: var(--mahad-primary);
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 13px;
    }

    .form-range {
      width: 100%;
      accent-color: var(--mahad-primary);
    }

    .result-score-box {
      background: linear-gradient(135deg, var(--mahad-dark) 0%, var(--mahad-primary) 100%);
      border-radius: 12px;
      padding: 24px 20px;
      color: #FFFFFF;
      text-align: center;
      border: 2px solid var(--mahad-gold);
    }

    .score-number {
      font-size: 46px;
      font-weight: 800;
      line-height: 1;
      color: #FFFFFF;
      margin: 8px 0;
    }

    .score-predicate {
      display: inline-block;
      background: var(--mahad-gold);
      color: #FFFFFF;
      padding: 4px 14px;
      border-radius: 50px;
      font-size: 12.5px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* ==========================================================================
       FITUR 4: FAQ ACCORDION INTERAKTIF
       ========================================================================== */
    .faq-item {
      background: #FFFFFF;
      border: 1px solid var(--mahad-border);
      border-radius: 10px;
      margin-bottom: 12px;
      overflow: hidden;
      transition: all 0.2s ease;
    }

    .faq-header {
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-weight: 700;
      font-size: 15px;
      color: var(--mahad-dark);
      background-color: #FFFFFF;
    }

    .faq-header:hover {
      background-color: var(--mahad-soft);
      color: var(--mahad-primary);
    }

    .faq-icon {
      font-size: 13px;
      transition: transform 0.3s ease;
      color: var(--mahad-muted);
    }

    .faq-item.active .faq-icon {
      transform: rotate(180deg);
      color: var(--mahad-primary);
    }

    .faq-body {
      display: none;
      padding: 14px 20px 18px 20px;
      font-size: 14px;
      color: #4A5568;
      line-height: 1.6;
      border-top: 1px solid #F1F5F9;
      background-color: #FAFCFA;
    }

    /* ==========================================================================
       FOOTER
       ========================================================================== */
    footer.site-footer {
      background-color: var(--mahad-dark);
      color: #E2E8F0;
      padding: 48px 0 24px 0;
      border-top: 4px solid var(--mahad-gold);
    }

    footer a {
      color: var(--mahad-gold-light);
    }

    footer a:hover {
      color: #FFFFFF;
      text-decoration: underline;
    }
  </style>
</head>

<body>

  <!-- ==========================================================================
       NAVBAR / HEADER (Desktop 3-Column & Mobile Single-Row Fixed 64px)
       ========================================================================== -->
  <header class="site-header">
    <div class="header-container">
      
      <!-- Logo e-TOEFL Ma'had Aly di Kiri -->
      <a href="{{ url('/') }}" class="header-brand">
        <img src="{{ asset('id/assets/images/logo.png') }}?v=2" alt="e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng" class="header-logo-img">
      </a>

      <!-- Menu Navigasi Tengah (Desktop) -->
      <ul class="header-nav-list">
        <li><a href="#top" class="header-nav-link active">Beranda</a></li>
        <li><a href="#alur" class="header-nav-link">Alur Ujian</a></li>
        <li><a href="#simulasi-mini" class="header-nav-link">Simulasi Mini</a></li>
        <li><a href="#paket" class="header-nav-link">Paket Ujian</a></li>
        <li><a href="#kalkulator" class="header-nav-link">Konversi Skor</a></li>
        <li><a href="#sertifikat" class="header-nav-link">Sertifikat</a></li>
        <li><a href="#faq" class="header-nav-link">FAQ</a></li>
        <li><a href="#kontak" class="header-nav-link">Kontak</a></li>
      </ul>

      <!-- Tombol Aksi Kanan (Desktop) -->
      <div class="header-actions-desktop">
        <a href="{{ route('login') }}" class="btn-login-header">Masuk</a>
        <a href="{{ route('register') }}" class="btn-register-header">Daftar</a>
      </div>

      <!-- Tombol Hamburger Mobile (Sejajar di Kanan pada Layar HP) -->
      <button type="button" class="mobile-hamburger-btn" id="hamburgerBtn" aria-label="Menu Utama">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

    </div>

    <!-- Mobile Drawer Menu (Slide-Down di Bawah Baris Header) -->
    <div class="mobile-nav-drawer" id="mobileDrawer">
      <a href="#top" class="mobile-nav-item active">Beranda</a>
      <a href="#alur" class="mobile-nav-item">Alur Ujian</a>
      <a href="#simulasi-mini" class="mobile-nav-item">Simulasi Mini</a>
      <a href="#paket" class="mobile-nav-item">Paket Ujian (TOEFL & TOEIC)</a>
      <a href="#kalkulator" class="mobile-nav-item">Konversi Skor</a>
      <a href="#sertifikat" class="mobile-nav-item">Sertifikat Resmi</a>
      <a href="#faq" class="mobile-nav-item">FAQ Mahasantri</a>
      <a href="#kontak" class="mobile-nav-item">Hubungi Kami</a>
      <div class="mobile-nav-actions">
        <a href="{{ route('login') }}" class="btn btn-outline-success w-100 py-2" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">Masuk Peserta</a>
        <a href="{{ route('register') }}" class="btn btn-success w-100 py-2" style="background-color: #007A1E; font-weight: 700;">Registrasi Akun Baru</a>
      </div>
    </div>
  </header>

  <!-- ==========================================================================
       HERO BANNER SECTION
       ========================================================================== -->
  <section class="hero-section" id="top">
    <div class="container" style="max-width: 1240px;">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="badge-academic-hero">
            <i class="fa fa-certificate"></i> Pusat Bahasa Ma'had Aly Hasyim Asy'ari
          </div>
          <h1 class="hero-title">
            e-TOEFL <em>Ma'had Aly</em><br><span>Hasyim Asy'ari</span>
          </h1>
          <p class="hero-desc">
            Platform simulasi dan prediksi kemahiran bahasa Inggris (TOEFL & TOEIC Prediction Test) terstandar untuk Mahasantri Ma'had Aly Hasyim Asy'ari Pondok Pesantren Tebuireng Jombang. Uji kesiapan bahasa global Anda dan raih sertifikat resmi ber-barcode untuk mendukung studi akademik serta dakwah internasional.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4 py-3" style="background-color: #007A1E; font-weight: 700;">
              <i class="fa fa-user-plus me-1"></i> Registrasi Akun
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-4 py-3" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
              <i class="fa fa-sign-in me-1"></i> Masuk Ujian
            </a>
          </div>
        </div>

        <div class="col-lg-6 mt-4 mt-lg-0">
          <div class="academic-hero-card">
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
                  <p>Skor terhitung instan dengan penerbitan sertifikat digital ber-barcode resmi.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 1: ALUR UJIAN (4 Langkah Terstruktur)
       ========================================================================== -->
  <section class="section-wrap" id="alur" style="background-color: var(--mahad-bg);">
    <div class="container" style="max-width: 1240px;">
      <div class="section-header">
        <span class="section-tag">Tahapan Pelaksanaan</span>
        <h2 class="section-title">Alur Ujian <em>e-TOEFL</em></h2>
        <p class="section-desc">Langkah terstruktur mulai dari pendaftaran akun hingga penerbitan sertifikat resmi</p>
      </div>

      <div class="row g-3">
        <div class="col-lg-3 col-md-6">
          <div class="step-card">
            <div class="step-badge">1</div>
            <h4>Buat Akun Peserta</h4>
            <p>Daftar secara instan menggunakan alamat email aktif untuk menerima tautan aktivasi akun.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="step-card">
            <div class="step-badge">2</div>
            <h4>Pilih Paket Ujian</h4>
            <p>Pilih paket try out TOEFL atau TOEIC sesuai jadwal yang disediakan oleh Ma'had Aly.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="step-card">
            <div class="step-badge">3</div>
            <h4>Kerjakan Ujian Online</h4>
            <p>Selesaikan seluruh seksi soal sesuai batas waktu sistematis yang ditentukan pengawas.</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="step-card">
            <div class="step-badge">4</div>
            <h4>Unduh Sertifikat</h4>
            <p>Dapatkan sertifikat digital instan dengan skor terkonversi dan QR-Code verifikasi resmi.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FITUR INTERAKTIF 1: SIMULASI CONTOH SOAL MINI (Interactive Mini Widget)
       ========================================================================== -->
  <section class="section-wrap" id="simulasi-mini">
    <div class="container" style="max-width: 1000px;">
      <div class="section-header">
        <span class="section-tag">Uji Coba Cepat</span>
        <h2 class="section-title">Simulasi <em>Mini Sample Test</em></h2>
        <p class="section-desc">Rasakan pengalaman mengerjakan contoh soal standar e-TOEFL Ma'had Aly dengan umpan balik dan pembahasan instan.</p>
      </div>

      <div class="sample-test-box">
        <!-- Tabs Pilihan Soal -->
        <div class="sample-nav-tabs">
          <button type="button" class="sample-tab-btn active" onclick="switchSample(0)">Soal 1: Structure</button>
          <button type="button" class="sample-tab-btn" onclick="switchSample(1)">Soal 2: Written Expression</button>
          <button type="button" class="sample-tab-btn" onclick="switchSample(2)">Soal 3: Reading Vocabulary</button>
        </div>

        <div class="sample-content">
          <!-- Question 1 -->
          <div class="sample-item" id="sample-0">
            <div class="question-stem">
              <strong>1.</strong> Not only __________ the highest academic honors, but he also dedicated his life to teaching classical Islamic jurisprudence.
            </div>
            <div class="options-grid">
              <button class="option-btn" onclick="checkAnswer(this, 0, false)">
                <span class="option-letter">A</span> he achieved
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 0, true)">
                <span class="option-letter">B</span> did he achieve
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 0, false)">
                <span class="option-letter">C</span> he did achieve
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 0, false)">
                <span class="option-letter">D</span> achieving
              </button>
            </div>
            <div class="explanation-card" id="exp-0">
              <strong>Pembahasan Kaidah Grammar:</strong> Pola kalimat diawali oleh frasa negatif <em>"Not only"</em> di awal klausa mengharuskan terjadinya <strong>inversi</strong> (pembalikan susunan kata kerja bantu sebelum subjek: <em>Auxiliary + Subject + Verb</em>). Bentuk yang benar adalah <code>did he achieve</code>.
            </div>
          </div>

          <!-- Question 2 -->
          <div class="sample-item" id="sample-1" style="display: none;">
            <div class="question-stem">
              <strong>2.</strong> Identify the incorrect grammatical part in the sentence:<br>
              <span class="text-decoration-underline">The mahasantri</span> (A) in the advanced program <span class="text-decoration-underline">is</span> (B) required to submit their research papers <span class="text-decoration-underline">before</span> (C) the graduation <span class="text-decoration-underline">ceremony</span> (D).
            </div>
            <div class="options-grid">
              <button class="option-btn" onclick="checkAnswer(this, 1, false)">
                <span class="option-letter">A</span> The mahasantri
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 1, true)">
                <span class="option-letter">B</span> is
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 1, false)">
                <span class="option-letter">C</span> before
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 1, false)">
                <span class="option-letter">D</span> ceremony
              </button>
            </div>
            <div class="explanation-card" id="exp-1">
              <strong>Pembahasan Kaidah Grammar:</strong> Rujukan kata ganti jamak <em>"their research papers"</em> menandakan bahwa subjek <em>"The mahasantri"</em> merujuk pada bentuk jamak (plural). Oleh karena itu, kata kerja bantu (to be) pada opsi (B) seharusnya <code>are</code>, bukan <code>is</code>.
            </div>
          </div>

          <!-- Question 3 -->
          <div class="sample-item" id="sample-2" style="display: none;">
            <div class="question-stem">
              <strong>3.</strong> "The classical manuscripts preserve <strong>profound</strong> insights into ethical principles."<br>
              The word <strong>profound</strong> in the sentence is closest in meaning to:
            </div>
            <div class="options-grid">
              <button class="option-btn" onclick="checkAnswer(this, 2, false)">
                <span class="option-letter">A</span> superficial
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 2, true)">
                <span class="option-letter">B</span> deep and meaningful
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 2, false)">
                <span class="option-letter">C</span> temporary
              </button>
              <button class="option-btn" onclick="checkAnswer(this, 2, false)">
                <span class="option-letter">D</span> conventional
              </button>
            </div>
            <div class="explanation-card" id="exp-2">
              <strong>Pembahasan Kosakata:</strong> Kata <em>"profound"</em> dalam konteks wacana ilmiah atau pemikiran bermakna <code>deep, penetrating, or having great insight</code> (sangat mendalam/bermakna tinggi). Antonimnya adalah <em>superficial</em>.
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 2: DETAIL PAKET UJIAN (TOEFL VS TOEIC)
       ========================================================================== -->
  <section class="section-wrap" id="paket" style="background-color: var(--mahad-bg);">
    <div class="container" style="max-width: 1240px;">
      <div class="section-header">
        <span class="section-tag">Program Tersedia</span>
        <h2 class="section-title">Paket Ujian <em>Prediksi Standar</em></h2>
        <p class="section-desc">Pilihan simulasi terstandarisasi untuk mengukur kesiapan kemahiran bahasa asing mahasantri.</p>
      </div>

      <div class="row g-4 justify-content-center">
        <!-- Paket TOEFL -->
        <div class="col-lg-5 col-md-6">
          <div class="package-card featured">
            <div class="package-ribbon">Rekomendasi Akademik</div>
            <h3>e-TOEFL Prediction</h3>
            <p class="text-muted small">Simulasi TOEFL ITP Paper-Based Test format online.</p>
            <hr class="my-3">
            <ul class="package-features">
              <li><i class="fa fa-check-circle"></i> <strong>Section 1:</strong> Listening Comprehension (50 Soal)</li>
              <li><i class="fa fa-check-circle"></i> <strong>Section 2:</strong> Structure & Written Expression (40 Soal)</li>
              <li><i class="fa fa-check-circle"></i> <strong>Section 3:</strong> Reading Comprehension (50 Soal)</li>
              <li><i class="fa fa-clock-o"></i> Durasi Ujian: <strong>115 Menit</strong> Total</li>
              <li><i class="fa fa-certificate"></i> Rentang Skor: <strong>310 - 677</strong> (ETS Conversion)</li>
              <li><i class="fa fa-qrcode"></i> Sertifikat Digital Resmi ber-Barcode</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-success w-100 py-2" style="background-color: #007A1E; font-weight: 700;">
              Mulai Ujian TOEFL
            </a>
          </div>
        </div>

        <!-- Paket TOEIC -->
        <div class="col-lg-5 col-md-6">
          <div class="package-card">
            <h3>e-TOEIC Prediction</h3>
            <p class="text-muted small">Simulasi kemahiran bahasa Inggris komunikasi internasional & profesional.</p>
            <hr class="my-3">
            <ul class="package-features">
              <li><i class="fa fa-check-circle"></i> <strong>Section 1:</strong> Listening Section (100 Soal Audio)</li>
              <li><i class="fa fa-check-circle"></i> <strong>Section 2:</strong> Reading Section (100 Soal Teks)</li>
              <li><i class="fa fa-clock-o"></i> Durasi Ujian: <strong>120 Menit</strong> Total</li>
              <li><i class="fa fa-certificate"></i> Rentang Skor: <strong>10 - 990</strong> (TOEIC Standard)</li>
              <li><i class="fa fa-line-chart"></i> Analisis Tingkat Kemahiran Kerja Internasional</li>
              <li><i class="fa fa-qrcode"></i> Sertifikat Digital Instan Resmi</li>
            </ul>
            <a href="{{ route('register') }}" class="btn btn-outline-success w-100 py-2" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
              Mulai Ujian TOEIC
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FITUR INTERAKTIF 2: KALKULATOR & KONVERSI SKOR RESMI
       ========================================================================== -->
  <section class="section-wrap" id="kalkulator">
    <div class="container" style="max-width: 1100px;">
      <div class="section-header">
        <span class="section-tag">Kalkulator Simulasi</span>
        <h2 class="section-title">Konversi Skor & <em>Standar Kelulusan</em></h2>
        <p class="section-desc">Geser nilai pada masing-masing seksi untuk melihat simulasi kalkulasi skor akhir TOEFL ITP dan status predikat kelulusan Ma'had Aly.</p>
      </div>

      <div class="row g-4 align-items-center">
        <!-- Sliders -->
        <div class="col-lg-7">
          <div class="calculator-card">
            <!-- Listening -->
            <div class="slider-group">
              <div class="slider-header">
                <span class="slider-label">1. Listening Comprehension (Skor Terkonversi)</span>
                <span class="slider-val-badge" id="valListening">52</span>
              </div>
              <input type="range" class="form-range" id="rangeListening" min="31" max="68" value="52" oninput="calculateScore()">
              <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                <span>Min: 31</span>
                <span>Maks: 68</span>
              </div>
            </div>

            <!-- Structure -->
            <div class="slider-group">
              <div class="slider-header">
                <span class="slider-label">2. Structure & Written Expression</span>
                <span class="slider-val-badge" id="valStructure">50</span>
              </div>
              <input type="range" class="form-range" id="rangeStructure" min="31" max="68" value="50" oninput="calculateScore()">
              <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                <span>Min: 31</span>
                <span>Maks: 68</span>
              </div>
            </div>

            <!-- Reading -->
            <div class="slider-group" style="margin-bottom: 0;">
              <div class="slider-header">
                <span class="slider-label">3. Reading Comprehension</span>
                <span class="slider-val-badge" id="valReading">54</span>
              </div>
              <input type="range" class="form-range" id="rangeReading" min="31" max="67" value="54" oninput="calculateScore()">
              <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                <span>Min: 31</span>
                <span>Maks: 67</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Result Box -->
        <div class="col-lg-5">
          <div class="result-score-box">
            <div style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--mahad-gold-light); font-weight: 700;">
              PREDIKSI SKOR AKHIR TOEFL
            </div>
            <div class="score-number" id="totalScoreDisplay">520</div>
            <div class="score-predicate" id="scorePredicateDisplay">High Intermediate</div>
            <p class="mt-3 mb-0 small" style="opacity: 0.85; line-height: 1.4;">
              Rumus Standar ETS: <code>((Listening + Structure + Reading) &times; 10) / 3</code>. Memenuhi syarat kelulusan minimal mahasantri (450).
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 3: SERTIFIKAT DIGITAL & LIGHTBOX PREVIEW
       ========================================================================== -->
  <section class="section-wrap" id="sertifikat" style="background-color: var(--mahad-bg);">
    <div class="container" style="max-width: 1100px;">
      <div class="section-header">
        <span class="section-tag">Legalitas & Bukti Ujian</span>
        <h2 class="section-title">Sertifikat <em>Digital Resmi</em></h2>
        <p class="section-desc">Dilengkapi dengan QR-Code verifikasi instan untuk membuktikan keaslian hasil ujian mahasantri.</p>
      </div>

      <div class="row align-items-center g-4">
        <div class="col-lg-6 text-center">
          <div style="background: #FFFFFF; padding: 12px; border-radius: 12px; border: 1px solid var(--mahad-border); box-shadow: 0 10px 25px rgba(0,0,0,0.06); cursor: pointer;" onclick="openCertModal()">
            <img src="{{ asset('id/assets/images/portfolio-01.jpg') }}" alt="Contoh Sertifikat e-TOEFL Ma'had Aly" class="img-fluid rounded shadow-sm" style="max-height: 320px; object-fit: cover;">
            <div class="mt-2 text-muted small"><i class="fa fa-search-plus me-1"></i> Klik untuk memperbesar preview sertifikat</div>
          </div>
        </div>

        <div class="col-lg-6">
          <h3 class="mb-3">Fitur Keamanan Sertifikat Digital</h3>
          <ul class="package-features">
            <li><i class="fa fa-check-circle"></i> <strong>QR Code Terintegrasi:</strong> Pemindaian instan mengarah ke database portal resmi Ma'had Aly.</li>
            <li><i class="fa fa-check-circle"></i> <strong>Nomor Seri Unik:</strong> Dilengkapi nomor sertifikat terregistrasi anti-pemalsuan.</li>
            <li><i class="fa fa-check-circle"></i> <strong>Penerbitan Otomatis:</strong> Langsung dapat diunduh dalam format PDF siap cetak setelah ujian selesai.</li>
            <li><i class="fa fa-check-circle"></i> <strong>Legalitas Institusi:</strong> Diakui untuk pemenuhan syarat kelulusan, sidang munaqasyah, dan beasiswa.</li>
          </ul>
          <button type="button" class="btn btn-outline-success px-4 py-2 mt-2" onclick="openCertModal()" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
            <i class="fa fa-eye me-1"></i> Lihat Sampel Sertifikat
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FITUR INTERAKTIF 4: FAQ ACCORDION
       ========================================================================== -->
  <section class="section-wrap" id="faq">
    <div class="container" style="max-width: 900px;">
      <div class="section-header">
        <span class="section-tag">Tanya Jawab</span>
        <h2 class="section-title">Pertanyaan <em>Sering Diajukan</em></h2>
        <p class="section-desc">Informasi lengkap seputar teknis ujian, perangkat, dan penerbitan hasil.</p>
      </div>

      <div class="faq-container">
        <!-- FAQ 1 -->
        <div class="faq-item">
          <div class="faq-header" onclick="toggleFaq(this)">
            <span>Kapan mahasantri dapat mengikuti ujian e-TOEFL?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
          </div>
          <div class="faq-body">
            Ujian e-TOEFL dapat diikuti secara fleksibel sesuai jadwal sesi yang telah ditentukan oleh Pusat Bahasa Ma'had Aly Hasyim Asy'ari. Mahasantri cukup melakukan registrasi akun, mengaktifkan via email, dan memilih sesi paket ujian yang tersedia di dashboard.
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-item">
          <div class="faq-header" onclick="toggleFaq(this)">
            <span>Perangkat apa saja yang didukung untuk pengerjaan ujian?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
          </div>
          <div class="faq-body">
            Aplikasi didesain responsif untuk Laptop, Komputer PC, Tablet, maupun Smartphone. Namun demi kenyamanan maksimal pada seksi <em>Listening Comprehension</em> dan teks bacaan panjang pada <em>Reading</em>, mahasantri sangat disarankan menggunakan Laptop/PC dengan koneksi internet stabil serta earphone/headphone berkualitas baik.
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-item">
          <div class="faq-header" onclick="toggleFaq(this)">
            <span>Berapa batas nilai kelulusan minimal di Ma'had Aly Tebuireng?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
          </div>
          <div class="faq-body">
            Standar skor kelulusan minimal e-TOEFL Prediction bagi mahasantri jenjang Marhalah Ula (M1) Ma'had Aly Hasyim Asy'ari Tebuireng adalah skor <strong>450</strong> (predikat <em>Working Proficiency</em>). Mahasantri yang belum mencapai nilai standar diberikan kesempatan untuk mengikuti simulasi remedial pada periode berikutnya.
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-item">
          <div class="faq-header" onclick="toggleFaq(this)">
            <span>Bagaimana jika koneksi internet terputus saat pengerjaan soal?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
          </div>
          <div class="faq-body">
            Sistem e-TOEFL dilengkapi fitur <em>auto-save</em> jawaban secara berkala. Jika koneksi terputus sesaat, mahasantri dapat memuat ulang (refresh) halaman ujian dan melanjutkan pengerjaan selama batas waktu pengerjaan sesi belum berakhir.
          </div>
        </div>

        <!-- FAQ 5 -->
        <div class="faq-item">
          <div class="faq-header" onclick="toggleFaq(this)">
            <span>Bagaimana cara memverifikasi keabsahan sertifikat?</span>
            <i class="fa fa-chevron-down faq-icon"></i>
          </div>
          <div class="faq-body">
            Setiap lembar sertifikat digital memuat kode QR unik. Pihak ketiga atau penguji cukup memindai kode QR tersebut menggunakan kamera smartphone untuk membuka tautan verifikasi resmi yang menampilkan nama peserta, tanggal ujian, serta rincian skor per seksi.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       SECTION 4: KONTAK & KONSULTASI
       ========================================================================== -->
  <section class="section-wrap" id="kontak" style="background-color: var(--mahad-bg);">
    <div class="container" style="max-width: 1100px;">
      <div class="section-header">
        <span class="section-tag">Layanan Bantuan</span>
        <h2 class="section-title">Hubungi <em>Pusat Bahasa</em></h2>
        <p class="section-desc">Ada pertanyaan seputar akun atau jadwal ujian? Tim admin Ma'had Aly siap membantu.</p>
      </div>

      <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
          <div class="step-card text-start">
            <div class="d-flex align-items-center mb-3">
              <div class="step-badge badge-whatsapp m-0 me-3"><i class="fa fa-whatsapp"></i></div>
              <div>
                <h5 class="mb-0">WhatsApp Resmi</h5>
                <span class="text-muted small">Layanan Helpdesk</span>
              </div>
            </div>
            <p class="mb-3">Hubungi admin resmi Pusat Bahasa untuk konfirmasi akun atau kendala teknis.</p>
            <a href="{{ $adminWaLink }}" target="_blank" rel="noopener" class="btn btn-outline-success w-100 btn-sm py-2 d-inline-flex align-items-center justify-content-center gap-2" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
              <i class="fa fa-whatsapp" style="font-size: 16px; line-height: 0;"></i> Chat {{ $adminWa }}
            </a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="step-card text-start">
            <div class="d-flex align-items-center mb-3">
              <div class="step-badge m-0 me-3"><i class="fa fa-envelope"></i></div>
              <div>
                <h5 class="mb-0">Email Resmi</h5>
                <span class="text-muted small">Korespondensi Surat</span>
              </div>
            </div>
            <p class="mb-3">Pengiriman permohonan sertifikat cetak atau validasi kelembagaan formal.</p>
            <a href="mailto:{{ $adminEmail }}" class="btn btn-outline-success w-100 btn-sm py-2" style="border-color: #007A1E; color: #007A1E; font-weight: 700;">
              {{ $adminEmail }}
            </a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="step-card text-start">
            <div class="d-flex align-items-center mb-3">
              <div class="step-badge m-0 me-3"><i class="fa fa-map-marker"></i></div>
              <div>
                <h5 class="mb-0">Kampus Ma'had Aly</h5>
                <span class="text-muted small">Sekretariat Akademik</span>
              </div>
            </div>
            <p class="small text-muted mb-0">
              {{ $adminAddress }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ==========================================================================
       FOOTER
       ========================================================================== -->
  <footer class="site-footer">
    <div class="container text-center" style="max-width: 900px;">
      <h5 class="text-white fw-bold mb-2">MA'HAD ALY HASYIM ASY'ARI</h5>
      <p class="mb-2" style="color: var(--mahad-gold-light); font-weight: 600;">PONDOK PESANTREN TEBUIRENG JOMBANG</p>
      <p class="small text-muted mb-3">{{ $adminAddress }}</p>
      <p class="small mb-0">
        &copy; {{ date('Y') }} {{ $appName }}. Hak Cipta Dilindungi. | 
        <a href="https://mahatbi.or.id" target="_blank" rel="noopener">mahatbi.or.id</a>
      </p>
    </div>
  </footer>

  <!-- Modal Lightbox Sertifikat -->
  <div class="modal fade" id="certModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="modal-header" style="background-color: var(--mahad-dark); color: #FFFFFF; border-bottom: 3px solid var(--mahad-gold);">
          <h5 class="modal-title fs-6">Sampel Sertifikat e-TOEFL Prediction Ma'had Aly</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0 text-center bg-dark">
          <img src="{{ asset('id/assets/images/portfolio-01.jpg') }}" alt="Sertifikat e-TOEFL Preview" class="img-fluid">
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts: Bootstrap Bundle -->
  <script src="{{ asset('id/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Interactive Scripts (Vanilla JS Ringan, Bersih & Cepat) -->
  <script>
    // 1. Mobile Hamburger Menu Toggle
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');

    hamburgerBtn.addEventListener('click', function(e) {
      e.preventDefault();
      this.classList.toggle('active');
      mobileDrawer.classList.toggle('open');
    });

    // Tutup drawer saat salah satu tautan di-klik
    document.querySelectorAll('.mobile-nav-item').forEach(item => {
      item.addEventListener('click', () => {
        hamburgerBtn.classList.remove('active');
        mobileDrawer.classList.remove('open');
      });
    });

    // 2. Sample Mini Test Logic
    function switchSample(index) {
      document.querySelectorAll('.sample-tab-btn').forEach((btn, idx) => {
        btn.classList.toggle('active', idx === index);
      });
      document.querySelectorAll('.sample-item').forEach((item, idx) => {
        item.style.display = (idx === index) ? 'block' : 'none';
      });
    }

    function checkAnswer(button, questionIdx, isCorrect) {
      const parent = button.parentElement;
      const buttons = parent.querySelectorAll('.option-btn');
      buttons.forEach(btn => btn.disabled = true);

      if (isCorrect) {
        button.classList.add('selected-correct');
      } else {
        button.classList.add('selected-wrong');
      }

      const exp = document.getElementById('exp-' + questionIdx);
      if (exp) {
        exp.style.display = 'block';
      }
    }

    // 3. Live Score Calculator Logic
    function calculateScore() {
      const l = parseInt(document.getElementById('rangeListening').value);
      const s = parseInt(document.getElementById('rangeStructure').value);
      const r = parseInt(document.getElementById('rangeReading').value);

      document.getElementById('valListening').innerText = l;
      document.getElementById('valStructure').innerText = s;
      document.getElementById('valReading').innerText = r;

      const total = Math.round(((l + s + r) * 10) / 3);
      document.getElementById('totalScoreDisplay').innerText = total;

      const predicateEl = document.getElementById('scorePredicateDisplay');
      if (total < 400) {
        predicateEl.innerText = 'Novice';
        predicateEl.style.backgroundColor = '#94A3B8';
      } else if (total < 450) {
        predicateEl.innerText = 'Elementary';
        predicateEl.style.backgroundColor = '#3B82F6';
      } else if (total < 500) {
        predicateEl.innerText = 'Intermediate (Standar Kelulusan)';
        predicateEl.style.backgroundColor = '#007A1E';
      } else if (total < 550) {
        predicateEl.innerText = 'High Intermediate';
        predicateEl.style.backgroundColor = '#C69C2B';
      } else {
        predicateEl.innerText = 'Advanced (Studi Lanjut)';
        predicateEl.style.backgroundColor = '#0A3D24';
      }
    }

    // 4. FAQ Accordion Toggle
    function toggleFaq(header) {
      const item = header.parentElement;
      const body = item.querySelector('.faq-body');
      const isActive = item.classList.contains('active');

      // Tutup semua faq lainnya
      document.querySelectorAll('.faq-item').forEach(other => {
        other.classList.remove('active');
        other.querySelector('.faq-body').style.display = 'none';
      });

      if (!isActive) {
        item.classList.add('active');
        body.style.display = 'block';
      }
    }

    // 5. Modal Lightbox Trigger
    function openCertModal() {
      const modalEl = document.getElementById('certModal');
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  </script>

  <!-- Floating WhatsApp Action Button (Flexbox Centered) -->
  <a href="{{ $adminWaLink }}" target="_blank" rel="noopener" class="floating-wa-btn" aria-label="Bantuan WhatsApp Resmi">
    <i class="fa fa-whatsapp"></i>
  </a>

</body>
</html>
