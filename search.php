<?php
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if (mb_strlen($q) < 2) {
    echo json_encode(['results' => []]);
    exit;
}

$url = 'https://api.deezer.com/search/track?limit=5&q=' . urlencode($q);
$ctx = stream_context_create(['http' => [
    'timeout' => 6,
    'header'  => "User-Agent: CafeRequest/1.0\r\n",
]]);
$raw = @file_get_contents($url, false, $ctx);

if ($raw === false) {
    $err = error_get_last();
    echo json_encode(['results' => [], 'error' => $err['message'] ?? 'Gagal menghubungi Deezer']);
    exit;
}

$data = json_decode($raw, true);
$out  = [];
foreach (($data['data'] ?? []) as $t) {
    $out[] = [
        'title'  => $t['title'] ?? '',
        'artist' => $t['artist']['name'] ?? '',
        'thumb'  => $t['album']['cover_small'] ?? '',
        'cover'  => $t['album']['cover_big'] ?? '',
    ];
}
echo json_encode(['results' => $out]);