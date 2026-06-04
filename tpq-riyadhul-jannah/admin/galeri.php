<?php
// ============================================================
// admin/galeri.php - Manajemen Galeri Foto (CRUD + Upload)
// ============================================================
require_once 'includes/auth.php';

$db     = getDB();
$action = $_GET['action'] ?? 'list';

// ── PROSES POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';

    if ($act === 'tambah') {
        $judul    = trim($_POST['judul'] ?? '');
        $deskripsi= trim($_POST['deskripsi'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Kegiatan');

        if (empty($judul)) {
            setFlash('error', 'Judul foto wajib diisi.');
            redirect('galeri.php?action=tambah');
        }

        // Upload foto
        if (!isset($_FILES['foto']) || empty($_FILES['foto']['name'][0])) {
            setFlash('error', 'Minimal satu foto wajib diunggah.');
            redirect('galeri.php?action=tambah');
        }

        $files = $_FILES['foto'];
        $uploadDir = dirname(__DIR__) . '/uploads/galeri/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowed  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $berhasil = 0;

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed) && $files['size'][$i] <= 5 * 1024 * 1024) {
                    $namaFile = 'galeri_' . time() . '_' . bin2hex(random_bytes(2)) . '_' . $i . '.' . $ext;
                    $dest     = $uploadDir . $namaFile;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
                        $stmt = $db->prepare("INSERT INTO galeri (judul,deskripsi,foto,kategori) VALUES (?,?,?,?)");
                        $stmt->execute([$judul, $deskripsi, 'galeri/' . $namaFile, $kategori]);
                        $berhasil++;
                    }
                }
            }
        }
        
        if ($berhasil > 0) {
            setFlash('success', 'Berhasil menambahkan ' . $berhasil . ' foto ke album "' . $judul . '".');
        } else {
            setFlash('error', 'Gagal mengunggah foto. Pastikan format dan ukuran (maks 5MB) sesuai.');
        }
        redirect('galeri.php');

    } elseif ($act === 'hapus') {
        $gid = (int)($_POST['id'] ?? 0);
        if ($gid) {
            $stmt = $db->prepare("SELECT foto FROM galeri WHERE id=?");
            $stmt->execute([$gid]);
            $row = $stmt->fetch();
            if ($row) {
                $filePath = dirname(__DIR__) . '/uploads/' . $row['foto'];
                if (file_exists($filePath)) unlink($filePath);
                $db->prepare("DELETE FROM galeri WHERE id=?")->execute([$gid]);
                setFlash('success', 'Foto berhasil dihapus.');
            }
        }
        redirect('galeri.php');
    }
}

$galeri = $db->query("SELECT * FROM galeri ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Galeri Kegiatan';
require_once 'includes/sidebar.php';
?>

<div class="row g-4">
  <!-- Form Tambah (jika action=tambah) -->
  <?php if ($action === 'tambah'): ?>
  <div class="col-12">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-700"><i class="bi bi-plus-circle me-2"></i>Tambah Foto Galeri</h6>
        <a href="galeri.php" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
      </div>
      <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="act" value="tambah">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-500 small">Judul Foto <span class="text-danger">*</span></label>
              <input type="text" name="judul" class="form-control"
                     placeholder="cth: Wisuda Santri 2024" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-500 small">Kategori</label>
              <select name="kategori" class="form-select">
                <option>Kegiatan</option>
                <option>Wisuda</option>
                <option>Lomba</option>
                <option>Belajar</option>
                <option>Lainnya</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-500 small">Deskripsi (opsional)</label>
              <input type="text" name="deskripsi" class="form-control"
                     placeholder="Keterangan singkat foto...">
            </div>
            <div class="col-12">
              <label class="form-label fw-500 small">Upload Foto (Bisa pilih lebih dari 1) <span class="text-danger">*</span></label>
              <input type="file" name="foto[]" id="fotoInput" class="form-control"
                     accept="image/jpeg,image/png,image/webp,image/gif" required multiple
                     onchange="previewFoto(this)">
              <div class="form-text">Format: JPG, PNG, WEBP. Maks: 5MB per foto. Tahan tombol CTRL (atau seret kursor) untuk memilih banyak foto.</div>
              <div id="fotoPreview" class="mt-2 d-none d-flex flex-wrap gap-2"></div>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-emerald">
                <i class="bi bi-cloud-upload me-2"></i>Upload & Simpan
              </button>
              <a href="galeri.php" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Daftar Galeri -->
  <div class="col-12">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-700">Daftar Foto (<?= count($galeri) ?>)</h6>
        <?php if ($action !== 'tambah'): ?>
        <a href="galeri.php?action=tambah" class="btn btn-emerald btn-sm">
          <i class="bi bi-plus-circle me-1"></i>Tambah Foto
        </a>
        <?php endif; ?>
      </div>
      <div class="card-body p-4">
        <?php if (empty($galeri)): ?>
        <div class="text-center py-5 text-muted">
          <i class="bi bi-images fs-1 opacity-25"></i>
          <p class="mt-2">Belum ada foto. <a href="galeri.php?action=tambah">Tambah sekarang</a></p>
        </div>
        <?php else: ?>
        <div class="row g-3">
          <?php foreach ($galeri as $foto): ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
              <div style="height:160px;overflow:hidden">
                <img src="../<?= e(UPLOAD_URL . $foto['foto']) ?>"
                     alt="<?= e($foto['judul']) ?>"
                     class="w-100 h-100" style="object-fit:cover"
                     onerror="this.src='https://placehold.co/300x160/059669/white?text=Foto'">
              </div>
              <div class="p-2">
                <div class="fw-600 small text-truncate"><?= e($foto['judul']) ?></div>
                <div class="d-flex align-items-center justify-content-between mt-1">
                  <span class="badge bg-success-subtle text-success" style="font-size:.65rem">
                    <?= e($foto['kategori']) ?>
                  </span>
                  <form method="POST" onsubmit="return confirm('Hapus foto ini?')">
                    <input type="hidden" name="act" value="hapus">
                    <input type="hidden" name="id" value="<?= $foto['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Hapus">
                      <i class="bi bi-trash" style="font-size:.75rem"></i>
                    </button>
                  </form>
                </div>
                <div class="text-muted mt-1" style="font-size:.65rem">
                  <?= date('d M Y', strtotime($foto['created_at'])) ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
function previewFoto(input) {
  const container = document.getElementById('fotoPreview');
  container.innerHTML = '';
  if (input.files && input.files.length > 0) {
    container.classList.remove('d-none');
    Array.from(input.files).forEach(file => {
      const reader = new FileReader();
      reader.onload = e => {
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'rounded-3 shadow-sm border border-secondary border-opacity-25';
        img.style.height = '100px';
        img.style.width = '120px';
        img.style.objectFit = 'cover';
        container.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  }
}
</script>

<?php require_once 'includes/sidebar_end.php'; ?>
