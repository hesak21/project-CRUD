<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'cafe_request');
define('DB_USER', 'root');
define('DB_PASS', '');            // Laragon/XAMPP default: kosong

// Kredensial admin (GANTI sebelum dipakai sungguhan)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'kopi123');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
}

// Helper escape output (cegah XSS)
function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_check(): void {
    $ok = isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
    if (!$ok) { http_response_code(419); die('Token tidak valid.'); }
}

// Wajib login admin
function require_admin(): void {
    if (empty($_SESSION['is_admin'])) {
        header('Location: login.php');
        exit;
    }
}

// Label status untuk tampilan
function status_label(string $s): string {
    return $s === 'Diputar' ? 'Sedang Diputar' : $s;
}