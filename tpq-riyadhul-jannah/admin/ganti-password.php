<?php
// ============================================================
// admin/ganti-password.php - Ganti Password Admin
// ============================================================
require_once 'includes/auth.php';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lama  = $_POST['password_lama']  ?? '';
    $baru  = $_POST['password_baru']  ?? '';
    $ulang = $_POST['password_ulang'] ?? '';

    $stmt = $db->prepare("SELECT password FROM users WHERE id=?");
    $stmt->execute([$_SESSION['admin_id']]);
    $user = $stmt->fetch();

    if (!password_verify($lama, $user['password'])) {
        setFlash('error', 'Password lama tidak sesuai.');
    } elseif (strlen($baru) < 8) {
        setFlash('error', 'Password baru minimal 8 karakter.');
    } elseif ($baru !== $ulang) {
        setFlash('error', 'Konfirmasi password tidak cocok.');
    } else {
        $hash = password_hash($baru, PASSWORD_BCRYPT, ['cost' => 12]);
        $db->prepare("UPDATE users SET password=? WHERE id=?")->execute([$hash, $_SESSION['admin_id']]);
        setFlash('success', 'Password berhasil diubah.');
    }
    redirect('ganti-password.php');
}

$pageTitle = 'Ganti Password';
require_once 'includes/sidebar.php';
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card form-card">
      <div class="card-header bg-white border-bottom py-3 px-4">
        <h6 class="mb-0 fw-700"><i class="bi bi-key me-2"></i>Ganti Password</h6>
      </div>
      <div class="card-body p-4">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label fw-500 small">Password Lama <span class="text-danger">*</span></label>
            <input type="password" name="password_lama" class="form-control"
                   placeholder="Masukkan password saat ini" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-500 small">Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="password_baru" class="form-control"
                   placeholder="Minimal 8 karakter" required minlength="8">
          </div>
          <div class="mb-4">
            <label class="form-label fw-500 small">Konfirmasi Password Baru <span class="text-danger">*</span></label>
            <input type="password" name="password_ulang" class="form-control"
                   placeholder="Ulangi password baru" required>
          </div>
          <button type="submit" class="btn btn-emerald w-100">
            <i class="bi bi-shield-check me-2"></i>Ubah Password
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/sidebar_end.php'; ?>
