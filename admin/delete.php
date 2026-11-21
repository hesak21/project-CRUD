<?php
require __DIR__ . '/../config/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
csrf_check();

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $pdo->prepare('DELETE FROM song_requests WHERE id = ?')->execute([$id]);
}

header('Location: index.php');
exit;