<?php
// ============================================================
// admin/includes/auth.php - Proteksi Halaman Admin
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();
require_once dirname(__DIR__, 2) . '/config.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('../admin/login.php');
}
