<?php
// ============================================================
// admin/dashboard.php - Dashboard Admin
// ============================================================
require_once 'includes/auth.php';

$db = getDB();
$jumlahProgram = $db->query("SELECT COUNT(*) FROM program WHERE aktif=1")->fetchColumn();
$jumlahGaleri  = $db->query("SELECT COUNT(*) FROM galeri")->fetchColumn();
$jumlahProfil  = $db->query("SELECT COUNT(*) FROM profil")->fetchColumn();
$jumlahKontak  = $db->query("SELECT COUNT(*) FROM kontak")->fetchColumn();

// Statistik Pengunjung
$hariIni = date('Y-m-d');
$bulanIni = date('Y-m');

$stmtHariIni = $db->prepare("SELECT COUNT(*) FROM pengunjung WHERE tanggal = ?");
$stmtHariIni->execute([$hariIni]);
$pengunjungHariIni = $stmtHariIni->fetchColumn();

$stmtBulanIni = $db->prepare("SELECT COUNT(*) FROM pengunjung WHERE DATE_FORMAT(tanggal, '%Y-%m') = ?");
$stmtBulanIni->execute([$bulanIni]);
$pengunjungBulanIni = $stmtBulanIni->fetchColumn();

$totalPengunjung = $db->query("SELECT COUNT(*) FROM pengunjung")->fetchColumn();

// Foto terbaru
$fotoTerbaru = $db->query("SELECT * FROM galeri ORDER BY created_at DESC LIMIT 4")->fetchAll();

$pageTitle = 'Dashboard';
require_once 'includes/sidebar.php';
?>

<!-- Visitor Stats -->
<h6 class="fw-700 mb-3 text-secondary"><i class="bi bi-bar-chart-fill me-2"></i>Statistik Pengunjung</h6>
<div class="row g-4 mb-5">
  <div class="col-sm-4">
    <div class="card stat-card p-4" style="border-left: 4px solid #3b82f6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Pengunjung Hari Ini</p>
          <h3 class="fw-700 mb-0 text-primary"><?= number_format($pengunjungHariIni) ?></h3>
        </div>
        <div class="icon-box" style="background:#dbeafe;color:#3b82f6">
          <i class="bi bi-person-fill"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card stat-card p-4" style="border-left: 4px solid #8b5cf6;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Pengunjung Bulan Ini</p>
          <h3 class="fw-700 mb-0" style="color: #8b5cf6;"><?= number_format($pengunjungBulanIni) ?></h3>
        </div>
        <div class="icon-box" style="background:#ede9fe;color:#8b5cf6">
          <i class="bi bi-people-fill"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card stat-card p-4" style="border-left: 4px solid #10b981;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Total Pengunjung</p>
          <h3 class="fw-700 mb-0 text-success"><?= number_format($totalPengunjung) ?></h3>
        </div>
        <div class="icon-box" style="background:#d1fae5;color:#10b981">
          <i class="bi bi-globe"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Stat Cards -->
<h6 class="fw-700 mb-3 text-secondary"><i class="bi bi-folder-fill me-2"></i>Statistik Konten</h6>
<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Program Aktif</p>
          <h3 class="fw-700 mb-0"><?= $jumlahProgram ?></h3>
        </div>
        <div class="icon-box" style="background:#d1fae5;color:#059669">
          <i class="bi bi-journal-bookmark"></i>
        </div>
      </div>
      <a href="program.php" class="small text-muted mt-2 d-block text-decoration-none">
        Kelola Program <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Foto Galeri</p>
          <h3 class="fw-700 mb-0"><?= $jumlahGaleri ?></h3>
        </div>
        <div class="icon-box" style="background:#fef3c7;color:#d97706">
          <i class="bi bi-images"></i>
        </div>
      </div>
      <a href="galeri.php" class="small text-muted mt-2 d-block text-decoration-none">
        Kelola Galeri <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Konten Profil</p>
          <h3 class="fw-700 mb-0"><?= $jumlahProfil ?></h3>
        </div>
        <div class="icon-box" style="background:#ede9fe;color:#7c3aed">
          <i class="bi bi-file-person"></i>
        </div>
      </div>
      <a href="profil.php" class="small text-muted mt-2 d-block text-decoration-none">
        Edit Profil <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card p-4">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted small mb-1">Info Kontak</p>
          <h3 class="fw-700 mb-0"><?= $jumlahKontak ?></h3>
        </div>
        <div class="icon-box" style="background:#fee2e2;color:#dc2626">
          <i class="bi bi-telephone"></i>
        </div>
      </div>
      <a href="kontak.php" class="small text-muted mt-2 d-block text-decoration-none">
        Edit Kontak <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</div>

<!-- Quick Actions + Recent Photos -->
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card form-card p-4 h-100">
      <h6 class="fw-700 mb-3">Aksi Cepat</h6>
      <div class="d-grid gap-2">
        <a href="beranda.php" class="btn btn-emerald">
          <i class="bi bi-house-gear me-2"></i>Edit Tampilan Beranda
        </a>
        <a href="galeri.php?action=tambah" class="btn btn-outline-success">
          <i class="bi bi-plus-circle me-2"></i>Tambah Foto Galeri
        </a>
        <a href="program.php?action=tambah" class="btn btn-outline-success">
          <i class="bi bi-plus-circle me-2"></i>Tambah Program
        </a>
        <a href="profil.php" class="btn btn-outline-secondary">
          <i class="bi bi-pencil me-2"></i>Edit Visi & Misi
        </a>
        <a href="kontak.php" class="btn btn-outline-secondary">
          <i class="bi bi-pencil me-2"></i>Edit Info Kontak
        </a>
        <a href="../index.php" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-globe me-2"></i>Lihat Website
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card form-card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-700 mb-0">Foto Terbaru</h6>
        <a href="galeri.php" class="btn btn-sm btn-outline-success">Lihat Semua</a>
      </div>
      <?php if (empty($fotoTerbaru)): ?>
      <div class="text-center py-4 text-muted">
        <i class="bi bi-images fs-1 opacity-25"></i>
        <p class="mt-2 small">Belum ada foto. <a href="galeri.php?action=tambah">Tambah sekarang</a></p>
      </div>
      <?php else: ?>
      <div class="row g-2">
        <?php foreach ($fotoTerbaru as $foto): ?>
        <div class="col-6 col-md-3">
          <div class="position-relative rounded-3 overflow-hidden" style="height:100px">
            <img src="../<?= e(UPLOAD_URL . $foto['foto']) ?>"
                 alt="<?= e($foto['judul']) ?>"
                 class="w-100 h-100" style="object-fit:cover"
                 onerror="this.src='https://placehold.co/200x100/059669/white?text=Foto'">
            <div class="position-absolute bottom-0 start-0 end-0 p-1"
                 style="background:linear-gradient(transparent,rgba(0,0,0,.6))">
              <small class="text-white" style="font-size:.65rem"><?= e($foto['judul']) ?></small>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once 'includes/sidebar_end.php'; ?>
