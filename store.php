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
$cover   = trim($_POST['cover_url'] ?? '');

$errors = [];
if ($title === '' || mb_strlen($title) > 150)   $errors[] = 'Judul lagu wajib diisi (maks 150 karakter).';
if ($artist === '' || mb_strlen($artist) > 150) $errors[] = 'Nama musisi wajib diisi (maks 150 karakter).';
if (mb_strlen($message) > 255)                  $errors[] = 'Pesan maksimal 255 karakter.';

// Cover hanya boleh dari server gambar Apple/Deezer (jangan percaya input form mentah)
if ($cover !== '') {
    $parts = parse_url($cover);
    $host  = $parts['host'] ?? '';
    $valid = ($parts['scheme'] ?? '') === 'https'
          && preg_match('/(^|\.)(mzstatic\.com|dzcdn\.net)$/i', $host)
          && mb_strlen($cover) <= 255;
    if (!$valid) $cover = '';
}

if (!$errors && isset($_SESSION['last_request']) && time() - $_SESSION['last_request'] < 30) {
    $errors[] = 'Tunggu sebentar sebelum mengirim request lagi.';
}

if (!$errors) {
    $cek = $pdo->prepare(
        "SELECT COUNT(*) FROM song_requests
         WHERE LOWER(song_title) = LOWER(?) AND LOWER(artist_name) = LOWER(?)
           AND status IN ('Antrean', 'Diputar')"
    );
    $cek->execute([$title, $artist]);
    if ($cek->fetchColumn() > 0) {
        $errors[] = 'Lagu ini sudah ada di antrean. Coba lagu lain ya!';
    }
}

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = ['song_title' => $title, 'artist_name' => $artist, 'message' => $message];
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare(
    'INSERT INTO song_requests (song_title, artist_name, message, cover_url) VALUES (?, ?, ?, ?)'
);
$stmt->execute([
    $title,
    $artist,
    $message !== '' ? $message : null,
    $cover !== '' ? $cover : null,
]);

$_SESSION['last_request'] = time();
$_SESSION['flash'] = 'Request lagumu sudah masuk antrean. Terima kasih! 🎧';
header('Location: index.php');
exit;