<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e(SITE_NAME) ?> - <?= e(SITE_SLOGAN) ?>">
  <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?><?= e(SITE_NAME) ?></title>
  
  <!-- Favicon -->
  <link rel="icon" href="assets/images/logo.png?v=<?= time() ?>" type="image/png">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --emerald:     #059669;
      --emerald-dark:#047857;
      --emerald-light:#d1fae5;
      --gold:        #d97706;
      --gold-light:  #fef3c7;
      --white:       #ffffff;
      --gray-soft:   #f8fafc;
      --text-dark:   #1e293b;
      --text-muted:  #64748b;
    }

    * { scroll-behavior: smooth; }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-dark);
      background: var(--white);
    }

    /* ── Navbar ── */
    .navbar-brand .brand-text { font-weight: 700; color: var(--emerald); font-size: 1.1rem; }
    .navbar-brand .brand-sub  { font-size: .7rem; color: var(--gold); letter-spacing: .05em; }
    .navbar { background: rgba(255,255,255,.97) !important; box-shadow: 0 2px 20px rgba(0,0,0,.08); }
    .nav-link { font-weight: 500; color: var(--text-dark) !important; transition: color .2s; }
    .nav-link:hover, .nav-link.active { color: var(--emerald) !important; }
    .navbar-toggler { border-color: var(--emerald); }
    .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); }

    /* ── Hero ── */
    .hero-section {
      min-height: 100vh;
      /* Background diatur dinamis via inline style dari database (admin/beranda.php) */
      display: flex; align-items: center;
    }
    .hero-section .arabic-text { font-family: 'Amiri', serif; font-size: 2rem; color: var(--gold-light); }
    .hero-section h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; }
    .hero-section p  { font-size: 1.15rem; color: rgba(255,255,255,.9); }
    .btn-hero-primary   { background: var(--gold); border: none; color: #fff; font-weight: 600; padding: .75rem 2rem; border-radius: 50px; transition: all .3s; }
    .btn-hero-primary:hover { background: #b45309; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(217,119,6,.4); }
    .btn-hero-outline   { border: 2px solid #fff; color: #fff; font-weight: 600; padding: .75rem 2rem; border-radius: 50px; transition: all .3s; }
    .btn-hero-outline:hover { background: #fff; color: var(--emerald); }

    /* ── Section Titles ── */
    .section-title { font-weight: 700; color: var(--text-dark); }
    .section-title span { color: var(--emerald); }
    .section-divider { width: 60px; height: 4px; background: linear-gradient(90deg, var(--emerald), var(--gold)); border-radius: 2px; margin: .75rem auto 1.5rem; }

    /* ── Cards ── */
    .card-program {
      border: none; border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,.07);
      transition: transform .3s, box-shadow .3s;
      overflow: hidden;
    }
    .card-program:hover { transform: translateY(-6px); box-shadow: 0 12px 35px rgba(5,150,105,.15); }
    .card-program .icon-wrap {
      width: 64px; height: 64px; border-radius: 16px;
      background: var(--emerald-light); display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; color: var(--emerald); margin-bottom: 1rem;
    }
    .card-program h5 { font-weight: 600; color: var(--text-dark); }

    /* ── Profil Section ── */
    .profil-section { background: var(--gray-soft); }
    .visi-misi-card {
      border-left: 4px solid var(--emerald); border-radius: 0 12px 12px 0;
      background: #fff; padding: 1.5rem; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .visi-misi-card.gold { border-left-color: var(--gold); }

    /* ── Galeri ── */
    .galeri-item { position: relative; overflow: hidden; border-radius: 12px; cursor: pointer; }
    .galeri-item img { width: 100%; height: 220px; object-fit: cover; transition: transform .4s; }
    .galeri-item:hover img { transform: scale(1.08); }
    .galeri-overlay {
      position: absolute; inset: 0; background: rgba(5,150,105,.75);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      opacity: 0; transition: opacity .3s; color: #fff; text-align: center; padding: 1rem;
    }
    .galeri-item:hover .galeri-overlay { opacity: 1; }

    /* ── Kontak ── */
    .kontak-section { background: linear-gradient(135deg, var(--emerald-dark) 0%, #065f46 100%); }
    .kontak-card { background: rgba(255,255,255,.1); border-radius: 16px; padding: 1.5rem; color: #fff; border: 1px solid rgba(255,255,255,.15); }
    .kontak-icon { width: 48px; height: 48px; background: var(--gold); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; flex-shrink: 0; }
    .btn-wa { background: #25d366; color: #fff; border: none; border-radius: 50px; padding: .75rem 2rem; font-weight: 600; transition: all .3s; }
    .btn-wa:hover { background: #128c7e; color: #fff; transform: translateY(-2px); }

    /* ── Footer ── */
    footer { background: #0f172a; color: rgba(255,255,255,.7); }
    footer a { color: rgba(255,255,255,.6); text-decoration: none; transition: color .2s; }
    footer a:hover { color: var(--gold); }
    footer .footer-brand { font-weight: 700; color: #fff; font-size: 1.2rem; }

    /* ── Stats Bar ── */
    .stats-bar { background: var(--emerald); }
    .stat-item h3 { font-size: 2rem; font-weight: 700; color: #fff; margin: 0; }
    .stat-item p  { color: rgba(255,255,255,.85); margin: 0; font-size: .9rem; }

    /* ── Scroll to top ── */
    #scrollTop {
      position: fixed; bottom: 2rem; right: 2rem; z-index: 999;
      width: 44px; height: 44px; border-radius: 50%;
      background: var(--emerald); color: #fff; border: none;
      display: none; align-items: center; justify-content: center;
      box-shadow: 0 4px 15px rgba(5,150,105,.4); transition: all .3s;
    }
    #scrollTop:hover { background: var(--emerald-dark); transform: translateY(-3px); }

    /* ── Lightbox ── */
    .lightbox-overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,.9); z-index: 9999;
      display: none; align-items: center; justify-content: center;
    }
    .lightbox-overlay.active { display: flex; }
    .lightbox-overlay img { max-width: 90vw; max-height: 85vh; border-radius: 8px; }
    .lightbox-close { position: absolute; top: 1rem; right: 1.5rem; color: #fff; font-size: 2rem; cursor: pointer; }

    @media (max-width: 768px) {
      .hero-section { min-height: 90vh; text-align: center; }
      .hero-section .arabic-text { font-size: 1.4rem; }
    }
  </style>
</head>
<body>

<!-- ── Navbar ── -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="assets/images/logo.png?v=<?= time() ?>" alt="Logo TPQ" width="44" height="44"
           onerror="this.style.display='none'"
           style="border-radius:8px; object-fit: cover;">
      <div>
        <div class="brand-text"><?= e(SITE_NAME) ?></div>
        <div class="brand-sub">Lembaga Pendidikan Al-Quran</div>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="index.php#beranda">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#profil">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#program">Program</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#galeri">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#kontak">Kontak</a></li>
      </ul>
    </div>
  </div>
</nav>
