<?php
// ============================================================
// admin/kontak.php - Manajemen Informasi Kontak
// ============================================================
require_once 'includes/auth.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'alamat', 'whatsapp', 'whatsapp_text', 'email',
        'jam_belajar', 'maps_embed', 'facebook', 'instagram', 'youtube'
    ];

    $stmt = $db->prepare("UPDATE kontak SET nilai=? WHERE kunci=?");
    foreach ($fields as $field) {
        $nilai = trim($_POST[$field] ?? '');
        $stmt->execute([$nilai, $field]);
    }
    setFlash('success', 'Informasi kontak berhasil diperbarui.');
    redirect('kontak.php');
}

// Ambil semua kontak
$rows   = $db->query("SELECT kunci, nilai FROM kontak")->fetchAll();
$kontak = [];
foreach ($rows as $r) $kontak[$r['kunci']] = $r['nilai'];

$pageTitle = 'Informasi Kontak';
require_once 'includes/sidebar.php';
?>

<div class="card form-card">
  <div class="card-header bg-white border-bottom py-3 px-4">
    <h6 class="mb-0 fw-700"><i class="bi bi-telephone me-2"></i>Edit Informasi Kontak</h6>
  </div>
  <div class="card-body p-4">
    <form method="POST">
      <div class="row g-4">

        <!-- Alamat -->
        <div class="col-12">
          <h6 class="fw-700 text-muted small text-uppercase mb-3">
            <i class="bi bi-geo-alt me-1"></i>Lokasi
          </h6>
          <div class="mb-3">
            <label class="form-label fw-500 small">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="3"><?= e($kontak['alamat'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500 small">Embed URL Google Maps</label>
            <input type="url" name="maps_embed" class="form-control"
                   value="<?= e($kontak['maps_embed'] ?? '') ?>"
                   placeholder="https://www.google.com/maps/embed?pb=...">
            <div class="form-text">
              Cara mendapatkan: Buka Google Maps → Cari lokasi → Klik "Bagikan" → "Sematkan peta" → Salin URL dari atribut <code>src</code>
            </div>
          </div>
        </div>

        <div class="col-12"><hr class="my-0"></div>

        <!-- Kontak -->
        <div class="col-12">
          <h6 class="fw-700 text-muted small text-uppercase mb-3">
            <i class="bi bi-chat me-1"></i>Kontak
          </h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-500 small">Nomor WhatsApp (tanpa +, cth: 6281234567890)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                <input type="text" name="whatsapp" class="form-control"
                       value="<?= e($kontak['whatsapp'] ?? '') ?>"
                       placeholder="6281234567890">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-500 small">Tampilan Nomor WA (cth: 0812-3456-7890)</label>
              <input type="text" name="whatsapp_text" class="form-control"
                     value="<?= e($kontak['whatsapp_text'] ?? '') ?>"
                     placeholder="0812-3456-7890">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-500 small">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" style="color:var(--gold)"></i></span>
                <input type="email" name="email" class="form-control"
                       value="<?= e($kontak['email'] ?? '') ?>"
                       placeholder="info@tpq.id">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-500 small">Jam Belajar</label>
              <textarea name="jam_belajar" class="form-control" rows="2"
                        placeholder="Senin - Jumat: 15.30 - 17.30 WIB"><?= e($kontak['jam_belajar'] ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <div class="col-12"><hr class="my-0"></div>

        <!-- Media Sosial -->
        <div class="col-12">
          <h6 class="fw-700 text-muted small text-uppercase mb-3">
            <i class="bi bi-share me-1"></i>Media Sosial
          </h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-500 small">Facebook</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-facebook" style="color:#1877f2"></i></span>
                <input type="url" name="facebook" class="form-control"
                       value="<?= e($kontak['facebook'] ?? '') ?>"
                       placeholder="https://facebook.com/...">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-500 small">Instagram</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-instagram" style="color:#e1306c"></i></span>
                <input type="url" name="instagram" class="form-control"
                       value="<?= e($kontak['instagram'] ?? '') ?>"
                       placeholder="https://instagram.com/...">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-500 small">YouTube</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-youtube" style="color:#ff0000"></i></span>
                <input type="url" name="youtube" class="form-control"
                       value="<?= e($kontak['youtube'] ?? '') ?>"
                       placeholder="https://youtube.com/...">
              </div>
            </div>
          </div>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-emerald">
            <i class="bi bi-save me-2"></i>Simpan Semua Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php require_once 'includes/sidebar_end.php'; ?>
