<?php
require __DIR__ . '/config/config.php';
$pageTitle = 'Antrean Lagu';

$nowPlaying = $pdo->query("SELECT * FROM song_requests WHERE status='Diputar' ORDER BY id DESC LIMIT 1")->fetch();
$queue      = $pdo->query("SELECT * FROM song_requests WHERE status='Antrean' ORDER BY created_at ASC, id ASC")->fetchAll();
$done       = $pdo->query("SELECT * FROM song_requests WHERE status='Selesai' ORDER BY id DESC LIMIT 5")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<meta http-equiv="refresh" content="10">

<main class="max-w-6xl mx-auto px-5 py-10">
  <header class="flex items-center justify-between mb-8">
    <h1 class="font-serif text-4xl font-bold">☕ Now Playing</h1>
    <a href="index.php" class="bg-mocha text-white text-sm rounded-full px-5 py-2 hover:bg-espresso transition">+ Request Lagu</a>
  </header>

  <!-- Sedang diputar -->
  <section class="rounded-3xl bg-espresso text-cream p-8 mb-8 shadow-xl">
    <?php if ($nowPlaying): ?>
      <p class="text-caramel text-xs tracking-widest uppercase mb-2">Sedang Diputar</p>
      <h2 class="font-serif text-4xl md:text-5xl font-bold"><?= e($nowPlaying['song_title']) ?></h2>
      <p class="text-xl text-latte mt-2"><?= e($nowPlaying['artist_name']) ?></p>
      <?php if ($nowPlaying['message']): ?>
        <p class="mt-4 italic text-latte/80">“<?= e($nowPlaying['message']) ?>”</p>
      <?php endif; ?>
    <?php else: ?>
      <p class="text-latte">Belum ada lagu yang diputar. Yuk, request satu! 🎶</p>
    <?php endif; ?>
  </section>

  <div class="grid md:grid-cols-3 gap-6">
    <!-- Antrean -->
    <section class="md:col-span-2">
      <h3 class="font-serif text-2xl font-semibold mb-4">Antrean (<?= count($queue) ?>)</h3>
      <div class="space-y-3">
        <?php foreach ($queue as $i => $s): ?>
          <article class="flex gap-4 bg-white border border-latte rounded-2xl p-4 shadow-sm">
            <div class="w-10 h-10 shrink-0 rounded-full bg-latte text-mocha font-semibold flex items-center justify-center"><?= $i + 1 ?></div>
            <div class="min-w-0">
              <p class="font-semibold truncate"><?= e($s['song_title']) ?></p>
              <p class="text-sm text-mocha"><?= e($s['artist_name']) ?></p>
              <?php if ($s['message']): ?>
                <p class="text-sm italic text-mocha/70 mt-1">“<?= e($s['message']) ?>”</p>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
        <?php if (!$queue): ?>
          <p class="text-mocha/70">Antrean kosong.</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Selesai -->
    <section>
      <h3 class="font-serif text-2xl font-semibold mb-4">Baru Selesai</h3>
      <ul class="space-y-2 text-sm">
        <?php foreach ($done as $s): ?>
          <li class="bg-latte/50 rounded-xl px-4 py-3">
            <p class="font-medium"><?= e($s['song_title']) ?></p>
            <p class="text-mocha"><?= e($s['artist_name']) ?></p>
          </li>
        <?php endforeach; ?>
        <?php if (!$done): ?><li class="text-mocha/70">Belum ada.</li><?php endif; ?>
      </ul>
    </section>
  </div>
  <p class="text-xs text-mocha/60 mt-8 text-center">Halaman diperbarui otomatis setiap 10 detik.</p>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>