<?php
// ============================================================
// config.php - Konfigurasi Database & Konstanta Aplikasi
// TPQ RIYADHUL JANNAH
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tpq_riyadhul_jannah');
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'TPQ Riyadhul Jannah');
define('SITE_SLOGAN', 'Mencetak Generasi Qurani yang Berakhlak Mulia');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', 'uploads/');

// Koneksi PDO
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:20px;background:#fee;border:1px solid #f00;border-radius:8px;">
                <h3>Koneksi Database Gagal</h3>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <p>Pastikan MySQL berjalan dan database <strong>' . DB_NAME . '</strong> sudah dibuat.</p>
            </div>');
        }
    }
    return $pdo;
}

// Helper: sanitize output
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Helper: redirect
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

// Helper: flash message
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
