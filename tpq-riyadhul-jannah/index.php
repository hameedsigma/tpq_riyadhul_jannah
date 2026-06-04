<?php
// ============================================================
// index.php - Halaman Utama (Public)
// TPQ RIYADHUL JANNAH
// ============================================================
session_start();
require_once 'config.php';

$db = getDB();

// Pencatatan Statistik Pengunjung
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
$tanggal = date('Y-m-d');
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Cek apakah IP ini sudah tercatat hari ini
$stmtCek = $db->prepare("SELECT id FROM pengunjung WHERE ip_address = ? AND tanggal = ?");
$stmtCek->execute([$ipAddress, $tanggal]);
if (!$stmtCek->fetch()) {
    $stmtInsert = $db->prepare("INSERT INTO pengunjung (ip_address, tanggal, user_agent) VALUES (?, ?, ?)");
    $stmtInsert->execute([$ipAddress, $tanggal, $userAgent]);
}

// Ambil data dari database
$profil  = [];
$rows    = $db->query("SELECT kunci, konten FROM profil")->fetchAll();
foreach ($rows as $r) $profil[$r['kunci']] = $r['konten'];

$programs = $db->query("SELECT * FROM program WHERE aktif=1 ORDER BY urutan ASC")->fetchAll();
$galeri = $db->query("
    SELECT judul, kategori, 
           (SELECT foto FROM galeri g2 WHERE g2.judul = g1.judul ORDER BY id ASC LIMIT 1) as cover,
           COUNT(*) as total,
           GROUP_CONCAT(foto ORDER BY id ASC SEPARATOR ',') as foto_list
    FROM galeri g1
    GROUP BY judul, kategori
    ORDER BY MAX(created_at) DESC
    LIMIT 12
")->fetchAll();

$kontak = [];
$krows  = $db->query("SELECT kunci, nilai FROM kontak")->fetchAll();
foreach ($krows as $r) $kontak[$r['kunci']] = $r['nilai'];

// ── Ambil pengaturan Hero dari database ──────────────────────
$heroDefaults = [
    'bg_tipe'            => 'warna',
    'bg_gambar'          => '',
    'bg_warna_utama'     => '#059669',
    'bg_warna_kedua'     => '#d97706',
    'bg_arah_gradient'   => '135deg',
    'overlay_opasitas'   => '0.75',
    'overlay_warna'      => '#059669',
    'hero_judul'         => 'Selamat Datang di',
    'hero_judul_sub'     => 'TPQ Riyadhul Jannah',
    'hero_deskripsi'     => 'Mencetak Generasi Qurani yang Berakhlak Mulia',
    'tombol_utama_label' => 'Lihat Program',
    'tombol_utama_link'  => '#program',
    'tombol_dua_label'   => 'Daftar Sekarang',
    'tombol_dua_wa'      => '1',
];

// Cek apakah tabel hero_settings sudah ada sebelum query
$heroRows = [];
try {
    $heroRows = $db->query("SELECT kunci, nilai FROM hero_settings")->fetchAll();
} catch (Exception $e) {
    // Tabel belum ada, pakai defaults saja
}
$hero = $heroDefaults;
foreach ($heroRows as $r) $hero[$r['kunci']] = $r['nilai'];

// ── Bangun CSS background hero ────────────────────────────────
if ($hero['bg_tipe'] === 'gambar' && !empty($hero['bg_gambar']) && file_exists('assets/images/' . $hero['bg_gambar'])) {
    // Parse warna overlay hex ke rgb
    $hexOv = ltrim($hero['overlay_warna'], '#');
    $r_ov  = hexdec(substr($hexOv, 0, 2));
    $g_ov  = hexdec(substr($hexOv, 2, 2));
    $b_ov  = hexdec(substr($hexOv, 4, 2));
    $op    = (float)$hero['overlay_opasitas'];
    $heroBgCss = "background: linear-gradient(rgba({$r_ov},{$g_ov},{$b_ov},{$op}), rgba({$r_ov},{$g_ov},{$b_ov},{$op})),
                  url('assets/images/{$hero['bg_gambar']}?v=" . time() . "') center/cover no-repeat;";
} else {
    $arah = $hero['bg_arah_gradient'];
    $c1   = $hero['bg_warna_utama'];
    $c2   = $hero['bg_warna_kedua'];
    $heroBgCss = "background: linear-gradient({$arah}, {$c1}, {$c2});";
}

$jumlahSantri  = 350;
$jumlahHafidz  = 48;
$jumlahUstadz  = 15;
$tahunBerdiri  = 2005;

$pageTitle = 'Beranda';
require_once 'includes/header.php';
?>

<!-- ══════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════ -->
<section class="hero-section" id="beranda" style="<?= $heroBgCss ?>">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7" data-aos="fade-right">
        <p class="arabic-text mb-2">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</p>
        <h1 class="mb-3">
          <?= e($hero['hero_judul']) ?><br>
          <span style="color:var(--gold-light)"><?= e($hero['hero_judul_sub']) ?></span>
        </h1>
        <p class="mb-4"><?= e($hero['hero_deskripsi']) ?></p>
        <div class="d-flex flex-wrap gap-3">
          <a href="<?= e($hero['tombol_utama_link']) ?>" class="btn btn-hero-primary">
            <i class="bi bi-book me-2"></i><?= e($hero['tombol_utama_label']) ?>
          </a>
          <?php if ($hero['tombol_dua_wa'] === '1'): ?>
          <a href="https://wa.me/<?= e($kontak['whatsapp'] ?? '') ?>" target="_blank"
             class="btn btn-hero-outline">
            <i class="bi bi-whatsapp me-2"></i><?= e($hero['tombol_dua_label']) ?>
          </a>
          <?php else: ?>
          <a href="#kontak" class="btn btn-hero-outline">
            <i class="bi bi-envelope me-2"></i><?= e($hero['tombol_dua_label']) ?>
          </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-flex justify-content-center mt-4 mt-lg-0">
        <div class="text-center text-white">
          <div style="background:rgba(255,255,255,.15);border-radius:20px;padding:2rem;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2)">
            <i class="bi bi-book-half" style="font-size:5rem;color:var(--gold-light)"></i>
            <h4 class="mt-3 mb-1">Pendaftaran Santri Baru</h4>
            <p class="small mb-3">Tahun Ajaran <?= date('Y') ?>/<?= date('Y')+1 ?></p>
            <a href="https://wa.me/<?= e($kontak['whatsapp'] ?? '') ?>?text=Assalamu'alaikum, saya ingin mendaftarkan anak saya di TPQ Riyadhul Jannah"
               target="_blank" class="btn btn-wa w-100">
              <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ══════════════════════════════════════════
     PROFIL SECTION
══════════════════════════════════════════ -->
<section class="profil-section py-5" id="profil">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Profil <span>TPQ Kami</span></h2>
      <div class="section-divider"></div>
      <p class="text-muted">Mengenal lebih dekat TPQ Riyadhul Jannah</p>
    </div>

    <!-- Sejarah -->
    <div class="row align-items-center g-4 mb-5">
      <div class="col-lg-6">
        <img src="assets/images/sejarah.jpg?v=<?= time() ?>" alt="Sejarah TPQ"
             class="img-fluid rounded-4 shadow"
             style="max-height:380px;width:100%;object-fit:cover;"
             onerror="this.src='https://placehold.co/600x380/059669/white?text=TPQ+Riyadhul+Jannah'">
      </div>
      <div class="col-lg-6">
        <h3 class="fw-700 mb-3" style="color:var(--emerald)">
          <i class="bi bi-clock-history me-2"></i>Sejarah Kami
        </h3>
        <p class="text-muted lh-lg"><?= nl2br(e($profil['sejarah'] ?? '')) ?></p>
        <div class="d-flex gap-3 mt-3">
          <div class="text-center p-3 rounded-3" style="background:var(--emerald-light)">
            <div class="fw-700 fs-4" style="color:var(--emerald)"><?= $tahunBerdiri ?></div>
            <small class="text-muted">Tahun Berdiri</small>
          </div>
          <div class="text-center p-3 rounded-3" style="background:var(--gold-light)">
            <div class="fw-700 fs-4" style="color:var(--gold)"><?= date('Y') - $tahunBerdiri ?>+</div>
            <small class="text-muted">Tahun Berpengalaman</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Visi & Misi -->
    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="visi-misi-card h-100">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div style="width:36px;height:36px;background:var(--emerald);border-radius:8px;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-eye text-white"></i>
            </div>
            <h5 class="mb-0 fw-700">Visi</h5>
          </div>
          <p class="text-muted mb-0 lh-lg"><?= nl2br(e($profil['visi'] ?? '')) ?></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="visi-misi-card gold h-100">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div style="width:36px;height:36px;background:var(--gold);border-radius:8px;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-bullseye text-white"></i>
            </div>
            <h5 class="mb-0 fw-700">Misi</h5>
          </div>
          <div class="text-muted lh-lg" style="white-space:pre-line"><?= e($profil['misi'] ?? '') ?></div>
        </div>
      </div>
    </div>

    <!-- Sambutan -->
    <?php if (!empty($profil['sambutan'])): ?>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="p-4 rounded-4 shadow-sm" style="background:#fff;border:1px solid var(--emerald-light)">
          <div class="d-flex align-items-center gap-3 mb-3">
            <?php if (file_exists('assets/images/kepala.jpg')): ?>
              <img src="assets/images/kepala.jpg?v=<?= time() ?>" alt="Kepala TPQ" class="rounded-circle shadow-sm" style="width:64px;height:64px;object-fit:cover;border:2px solid var(--emerald-light);">
            <?php else: ?>
              <div style="width:56px;height:56px;background:var(--emerald);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-person-fill text-white fs-4"></i>
              </div>
            <?php endif; ?>
            <div>
              <h5 class="mb-0 fw-700">Sambutan Kepala TPQ</h5>
              <small class="text-muted">Pesan untuk Orang Tua & Santri</small>
            </div>
          </div>
          <div class="text-muted lh-lg fst-italic" style="white-space:pre-line"><?= e($profil['sambutan']) ?></div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ══════════════════════════════════════════
     PROGRAM UNGGULAN
══════════════════════════════════════════ -->
<section class="py-5 bg-white" id="program">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Program <span>Unggulan</span></h2>
      <div class="section-divider"></div>
      <p class="text-muted">Berbagai program pembelajaran Al-Quran yang kami tawarkan</p>
    </div>
    <div class="row g-4">
      <?php foreach ($programs as $prog): ?>
      <div class="col-sm-6 col-lg-4">
        <div class="card-program p-4 h-100">
          <div class="icon-wrap">
            <i class="bi <?= e($prog['ikon']) ?>"></i>
          </div>
          <h5><?= e($prog['nama']) ?></h5>
          <p class="text-muted small mb-0"><?= e($prog['deskripsi']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- CTA Daftar -->
    <div class="text-center mt-5">
      <div class="p-4 rounded-4" style="background:var(--emerald-light)">
        <h4 class="fw-700 mb-2" style="color:var(--emerald)">Tertarik Mendaftarkan Putra/Putri Anda?</h4>
        <p class="text-muted mb-3">Hubungi kami sekarang untuk informasi pendaftaran dan jadwal belajar</p>
        <a href="https://wa.me/<?= e($kontak['whatsapp'] ?? '') ?>?text=Assalamu'alaikum, saya ingin bertanya tentang program di TPQ Riyadhul Jannah"
           target="_blank" class="btn btn-wa">
          <i class="bi bi-whatsapp me-2"></i>Tanya via WhatsApp
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════
     GALERI KEGIATAN
══════════════════════════════════════════ -->
<section class="py-5" style="background:var(--gray-soft)" id="galeri">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Galeri <span>Kegiatan</span></h2>
      <div class="section-divider"></div>
      <p class="text-muted">Momen berharga kegiatan santri TPQ Riyadhul Jannah</p>
    </div>

    <?php if (empty($galeri)): ?>
    <div class="text-center py-5">
      <i class="bi bi-images" style="font-size:4rem;color:var(--emerald);opacity:.4"></i>
      <p class="text-muted mt-3">Foto kegiatan akan segera ditambahkan</p>
    </div>
    <?php else: ?>
    <div class="row g-3">
      <?php foreach ($galeri as $album): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="galeri-item"
             onclick="openAlbum(this)" 
             data-title="<?= e($album['judul']) ?>" 
             data-photos="<?= e($album['foto_list']) ?>">
          <img src="<?= e(UPLOAD_URL . $album['cover']) ?>"
               alt="<?= e($album['judul']) ?>"
               onerror="this.src='https://placehold.co/400x300/059669/white?text=Foto+Kegiatan'">
          <div class="galeri-overlay">
            <i class="bi bi-collection fs-3 mb-2"></i>
            <small class="fw-600"><?= e($album['judul']) ?></small>
            <?php if ($album['kategori']): ?>
            <div class="d-flex gap-2 mt-1 justify-content-center">
              <span class="badge" style="background:var(--gold)"><?= e($album['kategori']) ?></span>
              <span class="badge bg-light text-dark"><?= $album['total'] ?> Foto</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ══════════════════════════════════════════
     KONTAK
══════════════════════════════════════════ -->
<section class="kontak-section py-5" id="kontak">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title" style="color:#fff">Hubungi <span style="color:var(--gold-light)">Kami</span></h2>
      <div class="section-divider" style="background:linear-gradient(90deg,#fff,var(--gold))"></div>
      <p style="color:rgba(255,255,255,.8)">Kami siap membantu Anda</p>
    </div>

    <div class="row g-4">
      <!-- Info Kontak -->
      <div class="col-lg-5">
        <div class="row g-3">
          <div class="col-12">
            <div class="kontak-card d-flex gap-3 align-items-start">
              <div class="kontak-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <h6 class="fw-600 mb-1">Alamat</h6>
                <p class="small mb-0 opacity-75"><?= nl2br(e($kontak['alamat'] ?? '')) ?></p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="kontak-card d-flex gap-3 align-items-start">
              <div class="kontak-icon" style="background:#25d366"><i class="bi bi-whatsapp"></i></div>
              <div>
                <h6 class="fw-600 mb-1">WhatsApp</h6>
                <a href="https://wa.me/<?= e($kontak['whatsapp'] ?? '') ?>"
                   target="_blank" class="text-white small"><?= e($kontak['whatsapp_text'] ?? '') ?></a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="kontak-card d-flex gap-3 align-items-start">
              <div class="kontak-icon" style="background:var(--gold)"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <h6 class="fw-600 mb-1">Email</h6>
                <a href="mailto:<?= e($kontak['email'] ?? '') ?>"
                   class="text-white small"><?= e($kontak['email'] ?? '') ?></a>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="kontak-card d-flex gap-3 align-items-start">
              <div class="kontak-icon" style="background:#6366f1"><i class="bi bi-clock-fill"></i></div>
              <div>
                <h6 class="fw-600 mb-1">Jam Belajar</h6>
                <p class="small mb-0 opacity-75" style="white-space:pre-line"><?= e($kontak['jam_belajar'] ?? '') ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Google Maps -->
      <div class="col-lg-7">
        <div class="rounded-4 overflow-hidden shadow" style="height:380px">
          <?php if (!empty($kontak['maps_embed'])): ?>
          <iframe
            src="<?= e($kontak['maps_embed']) ?>"
            width="100%" height="100%" style="border:0"
            allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Lokasi TPQ Riyadhul Jannah">
          </iframe>
          <?php else: ?>
          <div class="d-flex align-items-center justify-content-center h-100"
               style="background:rgba(255,255,255,.1)">
            <div class="text-center text-white">
              <i class="bi bi-map fs-1 mb-2"></i>
              <p>Peta belum dikonfigurasi</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
