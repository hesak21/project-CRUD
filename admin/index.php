
<?php
require __DIR__ . '/../config/config.php';
require_admin();
$pageTitle = 'Dashboard Admin';

$rows = $pdo->query(
    "SELECT * FROM song_requests
     ORDER BY FIELD(status,'Diputar','Antrean','Selesai'), created_at ASC"
)->fetchAll();

$badge = [
    'Antrean' => 'bg-amber-100 text-amber-800',
    'Diputar' => 'bg-green-100 text-green-800',
    'Selesai' => 'bg-stone-200 text-stone-600',
];

include __DIR__ . '/../includes/header.php';
?>

<main class="max-w-6xl mx-auto px-5 py-10">

  <header class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="font-serif text-3xl font-bold">
      Dashboard Operator
    </h1>

    <div class="flex flex-wrap gap-2 text-sm">

      <!-- Buka Monitor -->
      <a href="../monitor.php"
         target="_blank"
         class="bg-latte rounded-full px-4 py-2 hover:bg-caramel hover:text-white transition">
        Buka Monitor
      </a>

      <!-- Laporan PDF -->
      <a href="laporan.php"
         target="_blank"
         class="bg-green-600 text-white rounded-full px-4 py-2 hover:bg-green-700 transition">
        ↓ Laporan PDF
      </a>

      <!-- Keluar -->
      <a href="logout.php"
         class="bg-mocha text-white rounded-full px-4 py-2 hover:bg-espresso transition">
        Keluar
      </a>

    </div>
  </header>

  <div class="bg-white border border-latte rounded-3xl shadow-sm overflow-x-auto">

    <table class="w-full text-sm text-left">

      <thead class="bg-latte/60 text-mocha">
        <tr>
          <th class="px-4 py-3">Lagu</th>
          <th class="px-4 py-3">Pesan</th>
          <th class="px-4 py-3">Waktu</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-latte/60">

      <?php foreach ($rows as $r): ?>
        <tr>

          <!-- Lagu & Artis -->
          <td class="px-4 py-3">
            <p class="font-semibold">
              <?= e($r['song_title']) ?>
            </p>
            <p class="text-mocha">
              <?= e($r['artist_name']) ?>
            </p>
          </td>

          <!-- Pesan -->
          <td class="px-4 py-3 max-w-xs text-mocha/80">
            <?= e($r['message']) ?: '—' ?>
          </td>

          <!-- Waktu -->
          <td class="px-4 py-3 whitespace-nowrap">
            <?= date('d M H:i', strtotime($r['created_at'])) ?>
          </td>

          <!-- Status -->
          <td class="px-4 py-3">
            <span class="px-3 py-1 rounded-full text-xs font-medium <?= $badge[$r['status']] ?? 'bg-gray-100 text-gray-700' ?>">
              <?= e(status_label($r['status'])) ?>
            </span>
          </td>

          <!-- Aksi -->
          <td class="px-4 py-3">
            <div class="flex justify-end gap-2">

              <?php if ($r['status'] !== 'Diputar'): ?>
                <form action="update.php" method="POST">
                  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <input type="hidden" name="status" value="Diputar">

                  <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-3 py-1.5">
                    ▶ Putar
                  </button>
                </form>
              <?php endif; ?>

              <?php if ($r['status'] !== 'Selesai'): ?>
                <form action="update.php" method="POST">
                  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                  <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                  <input type="hidden" name="status" value="Selesai">

                  <button type="submit"
                    class="bg-stone-600 hover:bg-stone-700 text-white rounded-lg px-3 py-1.5">
                    ✔ Selesai
                  </button>
                </form>
              <?php endif; ?>

              <form action="delete.php" method="POST"
                    onsubmit="return confirm('Hapus request ini?')">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">

                <button type="submit"
                  class="bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-1.5">
                  🗑 Hapus
                </button>
              </form>

            </div>
          </td>

        </tr>
      <?php endforeach; ?>

      <?php if (!$rows): ?>
        <tr>
          <td colspan="5" class="px-4 py-8 text-center text-mocha/70">
            Belum ada request lagu.
          </td>
        </tr>
      <?php endif; ?>

      </tbody>
    </table>

  </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
