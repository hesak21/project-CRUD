<?php
require __DIR__ . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
csrf_check();

$title   = trim($_POST['song_title'] ?? '');
$artist  = trim($_POST['artist_name'] ?? '');
$message = trim($_POST['message'] ?? '');

$errors = [];
if ($title === '' || mb_strlen($title) > 150)   $errors[] = 'Judul lagu wajib diisi (maks 150 karakter).';
if ($artist === '' || mb_strlen($artist) > 150) $errors[] = 'Nama musisi wajib diisi (maks 150 karakter).';
if (mb_strlen($message) > 255)                  $errors[] = 'Pesan maksimal 255 karakter.';

// Anti-spam sederhana: jeda 30 detik per pengunjung
if (!$errors && isset($_SESSION['last_request']) && time() - $_SESSION['last_request'] < 30) {
    $errors[] = 'Tunggu sebentar sebelum mengirim request lagi.';
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['song_title' => $title, 'artist_name' => $artist, 'message' => $message];
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO song_requests (song_title, artist_name, message) VALUES (?, ?, ?)'
);
$stmt->execute([$title, $artist, $message !== '' ? $message : null]);

$_SESSION['last_request'] = time();
$_SESSION['flash'] = 'Request lagumu sudah masuk antrean. Terima kasih! 🎧';
header('Location: index.php');
exit;