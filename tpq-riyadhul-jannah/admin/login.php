<?php
// ============================================================
// admin/login.php - Halaman Login Admin
// ============================================================
session_start();
require_once '../config.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_nama'] = $user['nama'];
            $_SESSION['admin_role'] = $user['role'];
            redirect('dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | <?= e(SITE_NAME) ?></title>
  <link rel="icon" href="../assets/images/logo.png?v=<?= time() ?>" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root { --emerald:#059669; --gold:#d97706; }
    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
      display: flex; align-items: center; justify-content: center;
    }
    .login-card {
      background: #fff; border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,.2);
      overflow: hidden; width: 100%; max-width: 420px;
    }
    .login-header {
      background: linear-gradient(135deg, var(--emerald), #047857);
      padding: 2rem; text-align: center; color: #fff;
    }
    .login-header .logo-circle {
      width: 72px; height: 72px; background: rgba(255,255,255,.2);
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1rem; font-size: 2rem;
    }
    .login-body { padding: 2rem; }
    .form-control:focus { border-color: var(--emerald); box-shadow: 0 0 0 .2rem rgba(5,150,105,.2); }
    .btn-login {
      background: linear-gradient(135deg, var(--emerald), #047857);
      border: none; color: #fff; font-weight: 600;
      padding: .75rem; border-radius: 10px; width: 100%;
      transition: all .3s;
    }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(5,150,105,.4); color: #fff; }
    .input-group-text { background: #f8fafc; border-right: none; }
    .form-control { border-left: none; }
    .toggle-pw { cursor: pointer; background: #f8fafc; border-left: none; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <div class="logo-circle"><i class="bi bi-shield-lock-fill"></i></div>
      <h5 class="mb-0 fw-700">Panel Admin</h5>
      <small class="opacity-75"><?= e(SITE_NAME) ?></small>
    </div>
    <div class="login-body">
      <?php if ($error): ?>
      <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <small><?= e($error) ?></small>
      </div>
      <?php endif; ?>

      <form method="POST" novalidate>
        <div class="mb-3">
          <label class="form-label fw-500 small">Username / Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
            <input type="text" name="username" class="form-control"
                   placeholder="Masukkan username atau email"
                   value="<?= e($_POST['username'] ?? '') ?>" required autofocus>
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label fw-500 small">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
            <input type="password" name="password" id="pwInput" class="form-control"
                   placeholder="Masukkan password" required>
            <button type="button" class="btn toggle-pw border" onclick="togglePw()">
              <i class="bi bi-eye" id="pwIcon"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn-login">
          <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
        </button>
      </form>

      <div class="text-center mt-3">
        <a href="../index.php" class="text-muted small text-decoration-none">
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Website
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePw() {
      const inp  = document.getElementById('pwInput');
      const icon = document.getElementById('pwIcon');
      if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash';
      } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye';
      }
    }
  </script>
</body>
</html>
