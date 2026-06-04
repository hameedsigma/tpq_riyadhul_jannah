<?php
// ============================================================
// admin/profil.php - Manajemen Profil (Sejarah, Visi, Misi, Sambutan)
// ============================================================
require_once 'includes/auth.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle upload logo
    if (isset($_POST['upload_logo'])) {
        if (isset($_FILES['logo_website']) && $_FILES['logo_website']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['logo_website']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['logo_website']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                move_uploaded_file($tmp, '../assets/images/logo.png');
                setFlash('success', 'Logo Website berhasil diperbarui.');
            } else {
                setFlash('error', 'Format gambar tidak diizinkan.');
            }
        }
        redirect('profil.php');
    }

    $kunci  = $_POST['kunci']  ?? '';
    $judul  = trim($_POST['judul']  ?? '');
    $konten = trim($_POST['konten'] ?? '');

    $allowed = ['sejarah', 'visi', 'misi', 'sambutan'];
    if (in_array($kunci, $allowed) && $judul !== '' && $konten !== '') {
        $stmt = $db->prepare("UPDATE profil SET judul=?, konten=? WHERE kunci=?");
        $stmt->execute([$judul, $konten, $kunci]);
        
        // Handle upload foto sejarah
        if ($kunci === 'sejarah' && isset($_FILES['foto_sejarah']) && $_FILES['foto_sejarah']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['foto_sejarah']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['foto_sejarah']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                move_uploaded_file($tmp, '../assets/images/sejarah.jpg');
            }
        }
        
        // Handle upload foto kepala
        if ($kunci === 'sambutan' && isset($_FILES['foto_kepala']) && $_FILES['foto_kepala']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['foto_kepala']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['foto_kepala']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                move_uploaded_file($tmp, '../assets/images/kepala.jpg');
            }
        }
        
        setFlash('success', 'Konten "' . ucfirst($kunci) . '" berhasil diperbarui.');
    } else {
        setFlash('error', 'Data tidak valid atau field kosong.');
    }
    redirect('profil.php');
}

// Ambil semua profil
$rows   = $db->query("SELECT * FROM profil ORDER BY id ASC")->fetchAll();
$profil = [];
foreach ($rows as $r) $profil[$r['kunci']] = $r;

$pageTitle = 'Profil & Visi Misi';
require_once 'includes/sidebar.php';
?>

<div class="row g-4 mb-4">
  <div class="col-12">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
        <div style="width:32px;height:32px;background:#3b82f6;border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i class="bi bi-image text-white small"></i>
        </div>
        <h6 class="mb-0 fw-700">Logo Website & Favicon</h6>
      </div>
      <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="upload_logo" value="1">
          <div class="mb-3">
            <label class="form-label fw-500 small">Unggah Logo (Rekomendasi rasio 1:1 / Persegi)</label>
            <input type="file" name="logo_website" class="form-control" accept="image/png, image/jpeg, image/webp" required>
            <div class="form-text">Format: PNG, JPG, WEBP. Gambar ini akan digunakan di navigasi utama dan sebagai ikon tab browser (favicon).</div>
            <div class="mt-2">
               <img src="../assets/images/logo.png?v=<?= time() ?>" alt="Logo Saat Ini" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: contain; background: #f8fafc;" onerror="this.style.display='none'">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-upload me-2"></i>Simpan Logo
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <?php
  $items = [
    ['kunci' => 'sejarah',  'label' => 'Sejarah TPQ',    'icon' => 'bi-clock-history', 'color' => '#059669'],
    ['kunci' => 'visi',     'label' => 'Visi',            'icon' => 'bi-eye',           'color' => '#7c3aed'],
    ['kunci' => 'misi',     'label' => 'Misi',            'icon' => 'bi-bullseye',      'color' => '#d97706'],
    ['kunci' => 'sambutan', 'label' => 'Sambutan Kepala', 'icon' => 'bi-person-badge',  'color' => '#0891b2'],
  ];
  foreach ($items as $item):
    $data = $profil[$item['kunci']] ?? ['judul' => $item['label'], 'konten' => ''];
  ?>
  <div class="col-12">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
        <div style="width:32px;height:32px;background:<?= $item['color'] ?>;border-radius:8px;display:flex;align-items:center;justify-content:center;">
          <i class="bi <?= $item['icon'] ?> text-white small"></i>
        </div>
        <h6 class="mb-0 fw-700"><?= $item['label'] ?></h6>
      </div>
      <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="kunci" value="<?= $item['kunci'] ?>">
          <div class="mb-3">
            <label class="form-label fw-500 small">Judul</label>
            <input type="text" name="judul" class="form-control"
                   value="<?= e($data['judul']) ?>" required>
          </div>
          
          <?php if ($item['kunci'] === 'sejarah'): ?>
          <div class="mb-3">
            <label class="form-label fw-500 small">Ganti Foto Profil / Sejarah (Opsional)</label>
            <input type="file" name="foto_sejarah" class="form-control" accept="image/jpeg, image/png, image/webp">
            <div class="form-text">Format yang diizinkan: JPG, PNG, WEBP. Biarkan kosong jika tidak ingin mengubah foto.</div>
            <div class="mt-2">
               <img src="../assets/images/sejarah.jpg?v=<?= time() ?>" alt="Foto saat ini" class="img-thumbnail" style="max-height: 120px;" onerror="this.style.display='none'">
            </div>
          </div>
          <?php endif; ?>
          
          <?php if ($item['kunci'] === 'sambutan'): ?>
          <div class="mb-3">
            <label class="form-label fw-500 small">Foto Kepala TPQ (Opsional)</label>
            <input type="file" name="foto_kepala" class="form-control" accept="image/jpeg, image/png, image/webp">
            <div class="form-text">Format yang diizinkan: JPG, PNG, WEBP. Foto idealnya berwajah jelas dengan rasio kotak (1:1).</div>
            <div class="mt-2">
               <img src="../assets/images/kepala.jpg?v=<?= time() ?>" alt="Foto Kepala" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.style.display='none'">
            </div>
          </div>
          <?php endif; ?>
          <div class="mb-3">
            <label class="form-label fw-500 small">Konten</label>
            <textarea name="konten" class="form-control" rows="<?= $item['kunci'] === 'misi' ? 8 : 6 ?>"
                      required placeholder="Tulis konten di sini..."><?= e($data['konten']) ?></textarea>
            <div class="form-text">Gunakan baris baru (Enter) untuk membuat paragraf atau daftar.</div>
          </div>
          <button type="submit" class="btn btn-emerald">
            <i class="bi bi-save me-2"></i>Simpan Perubahan
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php require_once 'includes/sidebar_end.php'; ?>
