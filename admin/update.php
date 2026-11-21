<?php
require __DIR__ . '/../config/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
csrf_check();

$id     = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($id <= 0 || !in_array($status, ['Antrean', 'Diputar', 'Selesai'], true)) {
    http_response_code(400);
    die('Data tidak valid.');
}

$pdo->beginTransaction();
try {
    // Hanya satu lagu yang boleh berstatus Diputar
    if ($status === 'Diputar') {
        $pdo->prepare("UPDATE song_requests SET status='Selesai' WHERE status='Diputar' AND id <> ?")
            ->execute([$id]);
    }
    $pdo->prepare('UPDATE song_requests SET status = ? WHERE id = ?')
        ->execute([$status, $id]);
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    die('Gagal memperbarui status.');
}

header('Location: index.php');
exit;