<?php
// ============================================================
// admin/beranda.php - Pengaturan Tampilan Hero / Beranda
// ============================================================
require_once 'includes/auth.php';

$db = getDB();

// ── Pastikan tabel ada (auto-install jika baru pertama pakai) ──
$db->exec("CREATE TABLE IF NOT EXISTS `hero_settings` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kunci`      VARCHAR(80)  NOT NULL UNIQUE,
  `nilai`      TEXT         NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// ── Default values (dipakai jika baris di DB belum ada) ──────
$defaults = [
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

// Sisipkan default jika baris belum ada
foreach ($defaults as $k => $v) {
    $db->prepare("INSERT IGNORE INTO hero_settings (kunci, nilai) VALUES (?, ?)")
       ->execute([$k, $v]);
}

// ── Handle POST: simpan perubahan ────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // --- Simpan teks & warna ---
    if ($action === 'simpan_pengaturan') {
        $fields = [
            'bg_tipe', 'bg_warna_utama', 'bg_warna_kedua', 'bg_arah_gradient',
            'overlay_opasitas', 'overlay_warna',
            'hero_judul', 'hero_judul_sub', 'hero_deskripsi',
            'tombol_utama_label', 'tombol_utama_link',
            'tombol_dua_label', 'tombol_dua_wa',
        ];
        $stmt = $db->prepare("UPDATE hero_settings SET nilai=? WHERE kunci=?");
        foreach ($fields as $f) {
            if (isset($_POST[$f])) {
                $stmt->execute([trim($_POST[$f]), $f]);
            }
        }
        setFlash('success', 'Pengaturan hero berhasil disimpan.');
        redirect('beranda.php');
    }

    // --- Upload gambar background ---
    if ($action === 'upload_bg') {
        if (isset($_FILES['bg_file']) && $_FILES['bg_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['bg_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowed)) {
                setFlash('error', 'Format tidak diizinkan. Gunakan JPG, PNG, atau WEBP.');
                redirect('beranda.php');
            }

            // Validasi ukuran maksimal 5 MB
            if ($_FILES['bg_file']['size'] > 5 * 1024 * 1024) {
                setFlash('error', 'Ukuran file terlalu besar. Maksimal 5 MB.');
                redirect('beranda.php');
            }

            $namaFile = 'hero-bg.' . $ext;
            $tujuan   = '../assets/images/' . $namaFile;

            if (move_uploaded_file($_FILES['bg_file']['tmp_name'], $tujuan)) {
                // Simpan nama file ke database
                $db->prepare("UPDATE hero_settings SET nilai=? WHERE kunci='bg_gambar'")
                   ->execute([$namaFile]);
                // Otomatis aktifkan mode gambar
                $db->prepare("UPDATE hero_settings SET nilai='gambar' WHERE kunci='bg_tipe'")
                   ->execute();
                setFlash('success', 'Gambar background berhasil diunggah dan diaktifkan.');
            } else {
                setFlash('error', 'Gagal menyimpan file. Periksa izin folder assets/images/.');
            }
        } else {
            setFlash('error', 'Tidak ada file yang dipilih atau terjadi error upload.');
        }
        redirect('beranda.php');
    }

    // --- Hapus gambar background ---
    if ($action === 'hapus_bg') {
        $namaFile = $db->query("SELECT nilai FROM hero_settings WHERE kunci='bg_gambar'")->fetchColumn();
        if ($namaFile && file_exists('../assets/images/' . $namaFile)) {
            unlink('../assets/images/' . $namaFile);
        }
        $db->prepare("UPDATE hero_settings SET nilai='' WHERE kunci='bg_gambar'")->execute();
        $db->prepare("UPDATE hero_settings SET nilai='warna' WHERE kunci='bg_tipe'")->execute();
        setFlash('success', 'Gambar background berhasil dihapus. Mode beralih ke warna gradient.');
        redirect('beranda.php');
    }
}

// ── Ambil semua pengaturan dari DB ───────────────────────────
$rows = $db->query("SELECT kunci, nilai FROM hero_settings")->fetchAll();
$h    = [];
foreach ($rows as $r) $h[$r['kunci']] = $r['nilai'];

// Merge dengan defaults untuk field yang mungkin belum ada
$h = array_merge($defaults, $h);

$pageTitle = 'Pengaturan Beranda';
require_once 'includes/sidebar.php';

// ── Helper: buat CSS background untuk preview ────────────────
function buildPreviewBg(array $h): string {
    if ($h['bg_tipe'] === 'gambar' && !empty($h['bg_gambar'])) {
        $r   = hexdec(substr($h['overlay_warna'], 1, 2));
        $g   = hexdec(substr($h['overlay_warna'], 3, 2));
        $b   = hexdec(substr($h['overlay_warna'], 5, 2));
        $op  = (float)$h['overlay_opasitas'];
        return "background: linear-gradient(rgba({$r},{$g},{$b},{$op}), rgba({$r},{$g},{$b},{$op})),
                url('../assets/images/{$h['bg_gambar']}?v=" . time() . "') center/cover no-repeat;";
    }
    $arah = htmlspecialchars($h['bg_arah_gradient']);
    $c1   = htmlspecialchars($h['bg_warna_utama']);
    $c2   = htmlspecialchars($h['bg_warna_kedua']);
    return "background: linear-gradient({$arah}, {$c1}, {$c2});";
}
?>

<!-- ============================================================
     PREVIEW HERO SECTION
============================================================ -->
<div class="mb-4">
  <h6 class="fw-700 mb-3 text-secondary">
    <i class="bi bi-eye me-2"></i>Preview Tampilan Hero (Perkiraan)
  </h6>
  <div id="heroPreviewer"
       class="rounded-4 overflow-hidden position-relative d-flex align-items-center"
       style="min-height:220px; <?= buildPreviewBg($h) ?> transition: background .4s;">
    <div class="container-fluid px-5 py-4">
      <p style="font-family:'serif';font-size:1.2rem;color:#fef3c7;margin-bottom:.5rem" id="prevArabic">
        بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ
      </p>
      <h2 style="color:#fff;font-weight:700;font-size:1.8rem;line-height:1.3;" class="mb-2">
        <span id="prevJudul"><?= e($h['hero_judul']) ?></span><br>
        <span id="prevJudulSub" style="color:#fef3c7;"><?= e($h['hero_judul_sub']) ?></span>
      </h2>
      <p id="prevDesc" style="color:rgba(255,255,255,.88);font-size:.95rem;margin-bottom:1rem">
        <?= e($h['hero_deskripsi']) ?>
      </p>
      <div class="d-flex gap-2 flex-wrap">
        <span class="badge px-3 py-2 rounded-pill" style="background:#d97706;font-size:.8rem">
          <i class="bi bi-book me-1"></i>
          <span id="prevBtn1"><?= e($h['tombol_utama_label']) ?></span>
        </span>
        <span class="badge px-3 py-2 rounded-pill" style="background:rgba(255,255,255,.25);font-size:.8rem;border:1px solid #fff">
          <i class="bi bi-whatsapp me-1"></i>
          <span id="prevBtn2"><?= e($h['tombol_dua_label']) ?></span>
        </span>
      </div>
    </div>
  </div>
  <p class="text-muted small mt-2">
    <i class="bi bi-info-circle me-1"></i>
    Preview diperbarui otomatis saat Anda mengubah pengaturan di bawah.
  </p>
</div>

<!-- ============================================================
     CARD 1: BACKGROUND
============================================================ -->
<div class="card form-card mb-4">
  <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
    <div style="width:32px;height:32px;background:#7c3aed;border-radius:8px;display:flex;align-items:center;justify-content:center;">
      <i class="bi bi-image text-white small"></i>
    </div>
    <h6 class="mb-0 fw-700">Background Hero</h6>
    <!-- Badge status background saat ini -->
    <span class="badge ms-auto <?= $h['bg_tipe'] === 'gambar' ? 'bg-success' : 'bg-secondary' ?>">
      Mode: <?= $h['bg_tipe'] === 'gambar' ? 'Gambar Foto' : 'Warna Gradient' ?>
    </span>
  </div>
  <div class="card-body p-4">

    <!-- Pilih Tipe Background -->
    <div class="mb-4">
      <label class="form-label fw-600 small">Tipe Background</label>
      <div class="d-flex gap-3 flex-wrap">

        <!-- Opsi: Warna Gradient -->
        <div class="flex-fill" style="min-width:200px">
          <input type="radio" class="btn-check" name="_bg_tipe_ui" id="tipeWarna"
                 <?= $h['bg_tipe'] !== 'gambar' ? 'checked' : '' ?> onchange="switchBgMode('warna')">
          <label class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1" for="tipeWarna">
            <i class="bi bi-palette2 fs-4"></i>
            <span class="fw-600">Warna Gradient</span>
            <small class="text-muted">Pilih dua warna & arah</small>
          </label>
        </div>

        <!-- Opsi: Gambar Upload -->
        <div class="flex-fill" style="min-width:200px">
          <input type="radio" class="btn-check" name="_bg_tipe_ui" id="tipeGambar"
                 <?= $h['bg_tipe'] === 'gambar' ? 'checked' : '' ?> onchange="switchBgMode('gambar')">
          <label class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center gap-1" for="tipeGambar">
            <i class="bi bi-card-image fs-4"></i>
            <span class="fw-600">Gambar / Foto</span>
            <small class="text-muted">Upload foto sendiri</small>
          </label>
        </div>

      </div>
    </div>

    <!-- ── Panel Warna Gradient ── -->
    <div id="panelWarna" class="<?= $h['bg_tipe'] === 'gambar' ? 'd-none' : '' ?>">
      <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0">
        <div class="row g-3 align-items-end">
          <div class="col-sm-4">
            <label class="form-label fw-500 small">Warna Utama</label>
            <div class="d-flex gap-2 align-items-center">
              <input type="color" id="colorPicker1" value="<?= e($h['bg_warna_utama']) ?>"
                     class="form-control form-control-color"
                     oninput="document.getElementById('inputWarna1').value=this.value; updatePreview()">
              <input type="text" id="inputWarna1" value="<?= e($h['bg_warna_utama']) ?>"
                     class="form-control form-control-sm font-monospace"
                     placeholder="#059669" maxlength="7"
                     oninput="document.getElementById('colorPicker1').value=this.value; updatePreview()">
            </div>
          </div>
          <div class="col-sm-4">
            <label class="form-label fw-500 small">Warna Kedua</label>
            <div class="d-flex gap-2 align-items-center">
              <input type="color" id="colorPicker2" value="<?= e($h['bg_warna_kedua']) ?>"
                     class="form-control form-control-color"
                     oninput="document.getElementById('inputWarna2').value=this.value; updatePreview()">
              <input type="text" id="inputWarna2" value="<?= e($h['bg_warna_kedua']) ?>"
                     class="form-control form-control-sm font-monospace"
                     placeholder="#d97706" maxlength="7"
                     oninput="document.getElementById('colorPicker2').value=this.value; updatePreview()">
            </div>
          </div>
          <div class="col-sm-4">
            <label class="form-label fw-500 small">Arah Gradient</label>
            <select id="selectArah" class="form-select form-select-sm" onchange="updatePreview()">
              <?php
              $arahOptions = [
                '135deg'    => '↘ Diagonal (135°) — Default',
                'to right'  => '→ Kanan',
                'to bottom' => '↓ Bawah',
                'to left'   => '← Kiri',
                '45deg'     => '↗ Diagonal (45°)',
                '180deg'    => '↓ Bawah (180°)',
              ];
              foreach ($arahOptions as $val => $label):
              ?>
              <option value="<?= e($val) ?>" <?= $h['bg_arah_gradient'] === $val ? 'selected' : '' ?>>
                <?= e($label) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- Swatch preset warna -->
        <div class="mt-3">
          <small class="text-muted d-block mb-2">Preset Cepat:</small>
          <div class="d-flex gap-2 flex-wrap">
            <?php
            $presets = [
              ['Hijau Emerald',  '#059669', '#047857'],
              ['Hijau - Emas',   '#059669', '#d97706'],
              ['Biru - Hijau',   '#0ea5e9', '#059669'],
              ['Ungu - Biru',    '#7c3aed', '#2563eb'],
              ['Biru Tua',       '#1e3a5f', '#0ea5e9'],
              ['Coklat Islami',  '#92400e', '#d97706'],
            ];
            foreach ($presets as [$nama, $c1, $c2]):
            ?>
            <button type="button" class="btn btn-sm border-0 p-0"
                    title="<?= e($nama) ?>"
                    onclick="applyPreset('<?= $c1 ?>', '<?= $c2 ?>')"
                    style="width:32px;height:32px;border-radius:6px;
                           background:linear-gradient(135deg, <?= $c1 ?>, <?= $c2 ?>);
                           cursor:pointer">
            </button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Panel Gambar Upload ── -->
    <div id="panelGambar" class="<?= $h['bg_tipe'] !== 'gambar' ? 'd-none' : '' ?>">

      <!-- Gambar saat ini -->
      <?php if (!empty($h['bg_gambar']) && file_exists('../assets/images/' . $h['bg_gambar'])): ?>
      <div class="mb-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0">
        <div class="d-flex align-items-center gap-3">
          <img src="../assets/images/<?= e($h['bg_gambar']) ?>?v=<?= time() ?>"
               alt="Background Saat Ini"
               class="rounded-3 shadow-sm"
               style="width:140px;height:80px;object-fit:cover;">
          <div>
            <p class="mb-1 small fw-600 text-success">
              <i class="bi bi-check-circle-fill me-1"></i>Gambar aktif
            </p>
            <p class="mb-2 small text-muted"><?= e($h['bg_gambar']) ?></p>
            <form method="POST" onsubmit="return confirm('Hapus gambar background ini?')">
              <input type="hidden" name="action" value="hapus_bg">
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Hapus Gambar
              </button>
            </form>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Form Upload -->
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="upload_bg">
        <div class="mb-3">
          <label class="form-label fw-500 small">Unggah Gambar Background Baru</label>
          <input type="file" name="bg_file" id="bg_file" class="form-control"
                 accept="image/jpeg,image/png,image/webp" required
                 onchange="previewUpload(this)">
          <div class="form-text">Format: JPG, PNG, WEBP. Maksimal 5 MB. Rekomendasi: 1920×1080px atau lebih.</div>
        </div>
        <!-- Preview sebelum upload -->
        <div id="uploadPreviewWrap" class="d-none mb-3">
          <p class="small text-muted fw-500 mb-1">Preview gambar yang dipilih:</p>
          <img id="uploadPreviewImg" src="" alt=""
               class="rounded-3 shadow-sm"
               style="max-height:160px;max-width:100%;object-fit:cover;">
        </div>

        <!-- Pengaturan Overlay (hanya tampil di mode gambar) -->
        <div class="p-3 rounded-3 mb-3" style="background:#f8fafc;border:1px solid #e2e8f0">
          <p class="small fw-600 mb-3">Pengaturan Overlay (lapisan warna di atas gambar)</p>
          <div class="row g-3 align-items-end">
            <div class="col-sm-5">
              <label class="form-label fw-500 small">Warna Overlay</label>
              <div class="d-flex gap-2 align-items-center">
                <input type="color" id="overlayColorPicker" value="<?= e($h['overlay_warna']) ?>"
                       class="form-control form-control-color"
                       oninput="document.getElementById('overlayColorText').value=this.value; updatePreview()">
                <input type="text" id="overlayColorText" value="<?= e($h['overlay_warna']) ?>"
                       class="form-control form-control-sm font-monospace"
                       maxlength="7"
                       oninput="document.getElementById('overlayColorPicker').value=this.value; updatePreview()">
              </div>
            </div>
            <div class="col-sm-7">
              <label class="form-label fw-500 small d-flex justify-content-between">
                <span>Opasitas Overlay</span>
                <span id="overlayOpLabel" class="fw-700" style="color:var(--emerald)">
                  <?= round($h['overlay_opasitas'] * 100) ?>%
                </span>
              </label>
              <input type="range" id="overlayRange" class="form-range"
                     min="0" max="1" step="0.05"
                     value="<?= e($h['overlay_opasitas']) ?>"
                     oninput="document.getElementById('overlayOpLabel').textContent=Math.round(this.value*100)+'%'; updatePreview()">
              <div class="d-flex justify-content-between">
                <small class="text-muted">Transparan (foto jelas)</small>
                <small class="text-muted">Gelap (teks jelas)</small>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-emerald">
          <i class="bi bi-cloud-upload me-2"></i>Upload & Aktifkan Gambar
        </button>
      </form>
    </div>

  </div>
</div>

<!-- ============================================================
     CARD 2: TEKS HERO
============================================================ -->
<form method="POST" id="formPengaturan">
  <input type="hidden" name="action" value="simpan_pengaturan">
  <!-- Field tersembunyi untuk mengirim nilai dari color picker & select -->
  <input type="hidden" name="bg_tipe"          id="input_bg_tipe"          value="<?= e($h['bg_tipe']) ?>">
  <input type="hidden" name="bg_warna_utama"   id="input_bg_warna_utama"   value="<?= e($h['bg_warna_utama']) ?>">
  <input type="hidden" name="bg_warna_kedua"   id="input_bg_warna_kedua"   value="<?= e($h['bg_warna_kedua']) ?>">
  <input type="hidden" name="bg_arah_gradient" id="input_bg_arah_gradient" value="<?= e($h['bg_arah_gradient']) ?>">
  <input type="hidden" name="overlay_warna"    id="input_overlay_warna"    value="<?= e($h['overlay_warna']) ?>">
  <input type="hidden" name="overlay_opasitas" id="input_overlay_opasitas" value="<?= e($h['overlay_opasitas']) ?>">

  <div class="card form-card mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
      <div style="width:32px;height:32px;background:#0891b2;border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-type text-white small"></i>
      </div>
      <h6 class="mb-0 fw-700">Teks Hero</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-500 small">Judul Baris 1</label>
          <input type="text" name="hero_judul" class="form-control"
                 value="<?= e($h['hero_judul']) ?>" maxlength="80"
                 oninput="document.getElementById('prevJudul').textContent=this.value"
                 placeholder="Selamat Datang di">
          <div class="form-text">Teks biasa, baris pertama judul hero.</div>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-500 small">Judul Baris 2 <span class="badge" style="background:var(--gold);font-size:.6rem">Warna Emas</span></label>
          <input type="text" name="hero_judul_sub" class="form-control"
                 value="<?= e($h['hero_judul_sub']) ?>" maxlength="80"
                 oninput="document.getElementById('prevJudulSub').textContent=this.value"
                 placeholder="TPQ Riyadhul Jannah">
          <div class="form-text">Ditampilkan dengan warna emas (highlight).</div>
        </div>
        <div class="col-12">
          <label class="form-label fw-500 small">Teks Deskripsi / Slogan</label>
          <input type="text" name="hero_deskripsi" class="form-control"
                 value="<?= e($h['hero_deskripsi']) ?>" maxlength="200"
                 oninput="document.getElementById('prevDesc').textContent=this.value"
                 placeholder="Mencetak Generasi Qurani yang Berakhlak Mulia">
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================================
       CARD 3: TOMBOL HERO
  ============================================================ -->
  <div class="card form-card mb-4">
    <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
      <div style="width:32px;height:32px;background:#d97706;border-radius:8px;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-cursor text-white small"></i>
      </div>
      <h6 class="mb-0 fw-700">Tombol Aksi (CTA)</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-4">
        <!-- Tombol 1 -->
        <div class="col-md-6">
          <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0">
            <p class="fw-600 small mb-2">
              <span class="badge me-1" style="background:var(--gold)">Tombol 1</span>
              Tombol Utama (Kuning)
            </p>
            <div class="mb-2">
              <label class="form-label fw-500 small">Label</label>
              <input type="text" name="tombol_utama_label" class="form-control form-control-sm"
                     value="<?= e($h['tombol_utama_label']) ?>"
                     oninput="document.getElementById('prevBtn1').textContent=this.value"
                     placeholder="Lihat Program">
            </div>
            <div>
              <label class="form-label fw-500 small">Link Tujuan</label>
              <input type="text" name="tombol_utama_link" class="form-control form-control-sm"
                     value="<?= e($h['tombol_utama_link']) ?>"
                     placeholder="#program atau https://...">
              <div class="form-text">Gunakan #program untuk scroll, atau URL lengkap.</div>
            </div>
          </div>
        </div>
        <!-- Tombol 2 -->
        <div class="col-md-6">
          <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0">
            <p class="fw-600 small mb-2">
              <span class="badge bg-secondary me-1">Tombol 2</span>
              Tombol Outline (Putih)
            </p>
            <div class="mb-2">
              <label class="form-label fw-500 small">Label</label>
              <input type="text" name="tombol_dua_label" class="form-control form-control-sm"
                     value="<?= e($h['tombol_dua_label']) ?>"
                     oninput="document.getElementById('prevBtn2').textContent=this.value"
                     placeholder="Daftar Sekarang">
            </div>
            <div>
              <label class="form-label fw-500 small">Tautkan ke WhatsApp?</label>
              <select name="tombol_dua_wa" class="form-select form-select-sm">
                <option value="1" <?= $h['tombol_dua_wa'] === '1' ? 'selected' : '' ?>>
                  Ya – Buka WhatsApp (nomor dari pengaturan Kontak)
                </option>
                <option value="0" <?= $h['tombol_dua_wa'] === '0' ? 'selected' : '' ?>>
                  Tidak – Link #kontak biasa
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tombol Simpan -->
  <div class="d-flex gap-3 align-items-center">
    <button type="submit" class="btn btn-emerald px-4 py-2">
      <i class="bi bi-save2 me-2"></i>Simpan Semua Perubahan
    </button>
    <a href="../index.php" target="_blank" class="btn btn-outline-primary">
      <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Website
    </a>
    <small class="text-muted ms-auto">
      <i class="bi bi-clock me-1"></i>
      Terakhir diupdate: <?= !empty($rows) ? date('d M Y H:i', strtotime($rows[0]['updated_at'] ?? 'now')) : '-' ?>
    </small>
  </div>
</form>

<?php require_once 'includes/sidebar_end.php'; ?>

<!-- ============================================================
     JAVASCRIPT: Preview real-time & logika UI
============================================================ -->
<script>
const prevEl = document.getElementById('heroPreviewer');

// ── Mode switch: warna ↔ gambar ──────────────────────────────
function switchBgMode(mode) {
    document.getElementById('panelWarna').classList.toggle('d-none', mode !== 'warna');
    document.getElementById('panelGambar').classList.toggle('d-none', mode !== 'gambar');
    document.getElementById('input_bg_tipe').value = mode;
    updatePreview();
}

// ── Apply preset warna ───────────────────────────────────────
function applyPreset(c1, c2) {
    document.getElementById('colorPicker1').value   = c1;
    document.getElementById('inputWarna1').value    = c1;
    document.getElementById('colorPicker2').value   = c2;
    document.getElementById('inputWarna2').value    = c2;
    updatePreview();
}

// ── Preview upload sebelum submit ────────────────────────────
function previewUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('uploadPreviewImg').src = e.target.result;
            document.getElementById('uploadPreviewWrap').classList.remove('d-none');
            // Update previewer langsung dengan DataURL
            prevEl.style.background = `
                linear-gradient(rgba(5,120,87,0.75), rgba(5,120,87,0.75)),
                url('${e.target.result}') center/cover no-repeat`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Update preview real-time ─────────────────────────────────
function updatePreview() {
    const bgTipe = document.getElementById('input_bg_tipe').value;

    // Sinkronkan hidden inputs
    document.getElementById('input_bg_warna_utama').value   = document.getElementById('inputWarna1').value;
    document.getElementById('input_bg_warna_kedua').value   = document.getElementById('inputWarna2').value;
    document.getElementById('input_bg_arah_gradient').value = document.getElementById('selectArah').value;
    document.getElementById('input_overlay_warna').value    = document.getElementById('overlayColorText').value;
    document.getElementById('input_overlay_opasitas').value = document.getElementById('overlayRange').value;

    if (bgTipe === 'warna') {
        const c1   = document.getElementById('inputWarna1').value || '#059669';
        const c2   = document.getElementById('inputWarna2').value || '#d97706';
        const arah = document.getElementById('selectArah').value  || '135deg';
        prevEl.style.background = `linear-gradient(${arah}, ${c1}, ${c2})`;
    } else {
        // Mode gambar: update overlay saja
        const overlayWarna = document.getElementById('overlayColorText').value || '#059669';
        const opasitas     = document.getElementById('overlayRange').value || '0.75';
        // Coba parse warna hex ke rgb
        let r=5, g=120, b=87;
        const hex = overlayWarna.replace('#','');
        if (hex.length === 6) {
            r = parseInt(hex.substring(0,2), 16);
            g = parseInt(hex.substring(2,4), 16);
            b = parseInt(hex.substring(4,6), 16);
        }
        // Pertahankan gambar background yang sudah ada, hanya update overlay
        const currentBg = getComputedStyle(prevEl).backgroundImage;
        const imgMatch  = currentBg.match(/url\(['"]?([^'"]+)['"]?\)/g);
        const imgUrl    = imgMatch ? imgMatch[imgMatch.length - 1] : '';
        if (imgUrl) {
            prevEl.style.background = `
                linear-gradient(rgba(${r},${g},${b},${opasitas}), rgba(${r},${g},${b},${opasitas})),
                ${imgUrl} center/cover no-repeat`;
        }
    }
}

// Jalankan sekali saat halaman dimuat untuk sinkronisasi
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
