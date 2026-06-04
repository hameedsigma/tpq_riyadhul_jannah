<!-- ── Admin Sidebar & Topbar Layout ── -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? e($pageTitle) . ' | ' : '' ?>Admin <?= e(SITE_NAME) ?></title>
  <link rel="icon" href="../assets/images/logo.png?v=<?= time() ?>" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --emerald:#059669; --emerald-dark:#047857; --gold:#d97706; --sidebar-w:260px; }
    body { font-family:'Poppins',sans-serif; background:#f1f5f9; }

    /* Sidebar */
    .sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: var(--sidebar-w); background: linear-gradient(180deg,#065f46 0%,#047857 100%);
      z-index: 1040; overflow-y: auto; transition: transform .3s;
    }
    .sidebar-brand {
      padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,.1);
      display: flex; align-items: center; gap: .75rem;
    }
    .sidebar-brand .brand-icon {
      width: 40px; height: 40px; background: rgba(255,255,255,.2);
      border-radius: 10px; display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; color: #fff; flex-shrink: 0;
    }
    .sidebar-brand .brand-name { color: #fff; font-weight: 700; font-size: .9rem; line-height: 1.2; }
    .sidebar-brand .brand-sub  { color: rgba(255,255,255,.6); font-size: .7rem; }

    .sidebar-menu { padding: 1rem 0; }
    .sidebar-label { color: rgba(255,255,255,.4); font-size: .65rem; font-weight: 600;
                     letter-spacing: .1em; text-transform: uppercase; padding: .75rem 1.5rem .25rem; }
    .sidebar-link {
      display: flex; align-items: center; gap: .75rem;
      padding: .65rem 1.5rem; color: rgba(255,255,255,.75);
      text-decoration: none; font-size: .875rem; font-weight: 500;
      transition: all .2s; border-left: 3px solid transparent;
    }
    .sidebar-link:hover { color: #fff; background: rgba(255,255,255,.1); }
    .sidebar-link.active { color: #fff; background: rgba(255,255,255,.15); border-left-color: var(--gold); }
    .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

    /* Main content */
    .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }

    /* Topbar */
    .topbar {
      background: #fff; padding: .75rem 1.5rem;
      box-shadow: 0 1px 10px rgba(0,0,0,.08);
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .topbar .page-title { font-weight: 600; font-size: 1.1rem; color: #1e293b; margin: 0; }
    .topbar .admin-info { display: flex; align-items: center; gap: .75rem; }
    .topbar .avatar {
      width: 36px; height: 36px; background: var(--emerald);
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 700; font-size: .9rem;
    }

    /* Cards */
    .stat-card {
      border: none; border-radius: 16px;
      box-shadow: 0 2px 15px rgba(0,0,0,.07);
      transition: transform .2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card .icon-box {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
    }

    /* Tables */
    .table-card { border: none; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,.07); overflow: hidden; }
    .table thead th { background: #f8fafc; font-weight: 600; font-size: .8rem;
                      text-transform: uppercase; letter-spacing: .05em; color: #64748b; border: none; }
    .table tbody td { vertical-align: middle; font-size: .875rem; }

    /* Forms */
    .form-card { border: none; border-radius: 16px; box-shadow: 0 2px 15px rgba(0,0,0,.07); }
    .form-control:focus, .form-select:focus {
      border-color: var(--emerald); box-shadow: 0 0 0 .2rem rgba(5,150,105,.15);
    }
    .btn-emerald { background: var(--emerald); border: none; color: #fff; font-weight: 600; }
    .btn-emerald:hover { background: var(--emerald-dark); color: #fff; }

    /* Flash messages */
    .flash-alert { border-radius: 12px; border: none; }

    /* Responsive */
    @media (max-width: 991px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 1039; }
      .sidebar-overlay.show { display: block; }
    }
  </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- ── Sidebar ── -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="bi bi-book-half"></i></div>
    <div>
      <div class="brand-name"><?= e(SITE_NAME) ?></div>
      <div class="brand-sub">Panel Admin</div>
    </div>
  </div>

  <nav class="sidebar-menu">
    <div class="sidebar-label">Utama</div>
    <a href="dashboard.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="sidebar-label">Manajemen Konten</div>
    <a href="beranda.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'beranda.php' ? 'active' : '' ?>">
      <i class="bi bi-house-gear"></i> Tampilan Beranda
    </a>
    <a href="profil.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'profil.php' ? 'active' : '' ?>">
      <i class="bi bi-file-person"></i> Profil & Visi Misi
    </a>
    <a href="program.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'program.php' ? 'active' : '' ?>">
      <i class="bi bi-journal-bookmark"></i> Program Unggulan
    </a>
    <a href="galeri.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'galeri.php' ? 'active' : '' ?>">
      <i class="bi bi-images"></i> Galeri Kegiatan
    </a>
    <a href="kontak.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'kontak.php' ? 'active' : '' ?>">
      <i class="bi bi-telephone"></i> Informasi Kontak
    </a>

    <div class="sidebar-label">Akun</div>
    <a href="ganti-password.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF']) === 'ganti-password.php' ? 'active' : '' ?>">
      <i class="bi bi-key"></i> Ganti Password
    </a>
    <a href="logout.php" class="sidebar-link text-danger-emphasis">
      <i class="bi bi-box-arrow-left"></i> Keluar
    </a>

    <div class="sidebar-label">Website</div>
    <a href="../index.php" target="_blank" class="sidebar-link">
      <i class="bi bi-globe"></i> Lihat Website
    </a>
  </nav>
</aside>

<!-- ── Main Content ── -->
<div class="main-content">
  <!-- Topbar -->
  <div class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()" style="border:none">
        <i class="bi bi-list fs-4"></i>
      </button>
      <h6 class="page-title"><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h6>
    </div>
    <div class="admin-info">
      <div class="d-none d-sm-block text-end">
        <div class="small fw-600"><?= e($_SESSION['admin_nama'] ?? 'Admin') ?></div>
        <div class="text-muted" style="font-size:.7rem"><?= e(ucfirst($_SESSION['admin_role'] ?? 'admin')) ?></div>
      </div>
      <div class="avatar"><?= strtoupper(substr($_SESSION['admin_nama'] ?? 'A', 0, 1)) ?></div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="p-4">
    <?php
    $flash = getFlash();
    if ($flash):
    ?>
    <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> flash-alert d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-<?= $flash['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?>"></i>
      <?= e($flash['message']) ?>
    </div>
    <?php endif; ?>
