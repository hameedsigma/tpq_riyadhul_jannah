<?php
// ============================================================
// admin/logout.php - Logout Admin
// ============================================================
session_start();
require_once '../config.php';

session_unset();
session_destroy();

redirect('../admin/login.php');
