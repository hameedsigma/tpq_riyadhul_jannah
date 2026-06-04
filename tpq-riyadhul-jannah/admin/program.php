<?php
// ============================================================
// admin/program.php - Manajemen Program Unggulan (CRUD)
// ============================================================
require_once 'includes/auth.php';

$db     = getDB();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// ── PROSES POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act  = $_POST['act'] ?? '';
    $nama = trim($_POST['nama'] ?? '');
    $desk = trim($_POST['deskripsi'] ?? '');
    $ikon = trim($_POST['ikon'] ?? 'bi-book');
    $urut = (int)($_POST['urutan'] ?? 0);
    $aktif= isset($_POST['aktif']) ? 1 : 0;

    if ($act === 'tambah') {
        if ($nama && $desk) {
            $stmt = $db->prepare("INSERT INTO program (nama,deskripsi,ikon,urutan,aktif) VALUES (?,?,?,?,?)");
            $stmt->execute([$nama, $desk, $ikon, $urut, $aktif]);
            setFlash('success', 'Program "' . $nama . '" berhasil ditambahkan.');
        } else {
            setFlash('error', 'Nama dan deskripsi wajib diisi.');
        }
    } elseif ($act === 'edit') {
        $pid = (int)($_POST['id'] ?? 0);
        if ($pid && $nama && $desk) {
            $stmt = $db->prepare("UPDATE program SET nama=?,deskripsi=?,ikon=?,urutan=?,aktif=? WHERE id=?");
            $stmt->execute([$nama, $desk, $ikon, $urut, $aktif, $pid]);
            setFlash('success', 'Program berhasil diperbarui.');
        }
    } elseif ($act === 'hapus') {
        $pid = (int)($_POST['id'] ?? 0);
        if ($pid) {
            $db->prepare("DELETE FROM program WHERE id=?")->execute([$pid]);
            setFlash('success', 'Program berhasil dihapus.');
        }
    }
    redirect('program.php');
}

// ── DATA ──
$programs = $db->query("SELECT * FROM program ORDER BY urutan ASC, id ASC")->fetchAll();
$editData = null;
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM program WHERE id=?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
    if (!$editData) redirect('program.php');
}

$ikonOptions = [
    'bi-book-half'        => 'Buku',
    'bi-journal-bookmark' => 'Jurnal',
    'bi-mic'              => 'Mikrofon',
    'bi-heart'            => 'Hati',
    'bi-pen'              => 'Pena',
    'bi-translate'        => 'Terjemah',
    'bi-star'             => 'Bintang',
    'bi-mortarboard'      => 'Wisuda',
    'bi-people'           => 'Orang',
    'bi-award'            => 'Penghargaan',
];

$pageTitle = 'Program Unggulan';
require_once 'includes/sidebar.php';
?>

<div class="row g-4">
  <!-- Form Tambah/Edit -->
  <div class="col-lg-4">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="mb-0 fw-700">
          <?= $action === 'edit' ? '<i class="bi bi-pencil me-2"></i>Edit Program' : '<i class="bi bi-plus-circle me-2"></i>Tambah Program' ?>
        </h6>
      </div>
      <div class="card-body p-4">
        <form method="POST">
          <input type="hidden" name="act" value="<?= $action === 'edit' ? 'edit' : 'tambah' ?>">
          <?php if ($action === 'edit'): ?>
          <input type="hidden" name="id" value="<?= $editData['id'] ?>">
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label fw-500 small">Nama Program <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control"
                   value="<?= e($editData['nama'] ?? '') ?>"
                   placeholder="cth: Tahfidz Al-Quran" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500 small">Deskripsi <span class="text-danger">*</span></label>
            <textarea name="deskripsi" class="form-control" rows="4"
                      placeholder="Deskripsi singkat program..." required><?= e($editData['deskripsi'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500 small">Ikon</label>
            <select name="ikon" class="form-select">
              <?php foreach ($ikonOptions as $val => $label): ?>
              <option value="<?= $val ?>" <?= ($editData['ikon'] ?? 'bi-book-half') === $val ? 'selected' : '' ?>>
                <?= $label ?> (<?= $val ?>)
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500 small">Urutan Tampil</label>
            <input type="number" name="urutan" class="form-control" min="0" max="99"
                   value="<?= $editData['urutan'] ?? 0 ?>">
          </div>
          <div class="mb-4">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="aktif" id="aktifSwitch"
                     <?= ($editData['aktif'] ?? 1) ? 'checked' : '' ?>>
              <label class="form-check-label small" for="aktifSwitch">Tampilkan di website</label>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-emerald flex-fill">
              <i class="bi bi-save me-1"></i><?= $action === 'edit' ? 'Simpan' : 'Tambah' ?>
            </button>
            <?php if ($action === 'edit'): ?>
            <a href="program.php" class="btn btn-outline-secondary">Batal</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Daftar Program -->
  <div class="col-lg-8">
    <div class="card table-card">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="mb-0 fw-700">Daftar Program (<?= count($programs) ?>)</h6>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Program</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($programs)): ?>
            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada program</td></tr>
            <?php else: ?>
            <?php foreach ($programs as $i => $prog): ?>
            <tr>
              <td class="text-muted"><?= $i + 1 ?></td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div style="width:32px;height:32px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi <?= e($prog['ikon']) ?>" style="color:#059669"></i>
                  </div>
                  <div>
                    <div class="fw-600 small"><?= e($prog['nama']) ?></div>
                    <div class="text-muted" style="font-size:.7rem"><?= e(mb_substr($prog['deskripsi'], 0, 50)) ?>...</div>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge <?= $prog['aktif'] ? 'bg-success' : 'bg-secondary' ?>">
                  <?= $prog['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                </span>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="program.php?action=edit&id=<?= $prog['id'] ?>"
                     class="btn btn-sm btn-outline-primary" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form method="POST" onsubmit="return confirm('Hapus program ini?')">
                    <input type="hidden" name="act" value="hapus">
                    <input type="hidden" name="id" value="<?= $prog['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/sidebar_end.php'; ?>
