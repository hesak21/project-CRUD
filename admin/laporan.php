
<?php
require __DIR__ . '/../config/config.php';
require_admin();

$rows = $pdo->query(
    "SELECT * FROM song_requests
     ORDER BY FIELD(status,'Diputar','Antrean','Selesai'), created_at ASC"
)->fetchAll();

$total = count($rows);
$antrean = 0;
$diputar = 0;
$selesai = 0;

foreach ($rows as $r) {
    if ($r['status'] === 'Antrean') {
        $antrean++;
    } elseif ($r['status'] === 'Diputar') {
        $diputar++;
    } elseif ($r['status'] === 'Selesai') {
        $selesai++;
    }
}

$tanggal = date('d-m-Y H:i:s');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Request Lagu Café</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 25px;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        .summary {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
        }

        .summary-card {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .summary-card h3 {
            font-size: 13px;
            margin: 0 0 8px;
            color: #666;
        }

        .summary-card p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #eee5d5;
            color: #5b4030;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 9px;
            vertical-align: top;
        }

        tr {
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 11px;
            color: #777;
        }

        .toolbar {
            text-align: center;
            margin-bottom: 25px;
        }

        .toolbar button {
            border: none;
            border-radius: 8px;
            background: #765039;
            color: white;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 14px;
        }

        .toolbar button:hover {
            background: #503522;
        }

        @media print {
            body {
                margin: 10mm;
            }

            .toolbar {
                display: none;
            }

            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button onclick="window.print()">Download / Simpan PDF</button>
    </div>

    <h1>Laporan Request Lagu</h1>
    <div class="subtitle">
        Café Request System<br>
        Dicetak pada: <?= htmlspecialchars($tanggal) ?>
    </div>

    <!-- Ringkasan -->
    <div class="summary">

        <div class="summary-card">
            <h3>Total Request</h3>
            <p><?= $total ?></p>
        </div>

        <div class="summary-card">
            <h3>Antrean</h3>
            <p><?= $antrean ?></p>
        </div>

        <div class="summary-card">
            <h3>Sedang Diputar</h3>
            <p><?= $diputar ?></p>
        </div>

        <div class="summary-card">
            <h3>Selesai</h3>
            <p><?= $selesai ?></p>
        </div>

    </div>

    <!-- Tabel Laporan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Lagu</th>
                <th>Penyanyi</th>
                <th>Pesan</th>
                <th>Waktu Request</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($rows): ?>
                <?php foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>

                        <td>
                            <?= htmlspecialchars($r['song_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($r['artist_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($r['message'] ?? '—', ENT_QUOTES, 'UTF-8') ?: '—' ?>
                        </td>

                        <td>
                            <?= date('d M Y H:i', strtotime($r['created_at'])) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(status_label($r['status']), ENT_QUOTES, 'UTF-8') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">
                        Belum ada data request lagu.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Laporan dibuat otomatis oleh sistem Café Request.
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>

</body>
</html>
