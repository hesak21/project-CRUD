<?php
require __DIR__ . '/config/config.php';
$pageTitle = 'Request Lagu';
$flash = $_SESSION['flash'] ?? null;
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['flash'], $_SESSION['errors'], $_SESSION['old']);
include __DIR__ . '/includes/header.php';
?>
<main class="max-w-xl mx-auto px-5 py-10">
  <div class="text-center mb-8">
    <p class="text-caramel tracking-widest text-xs uppercase mb-2">Café Playlist</p>
    <h1 class="font-serif text-4xl font-bold">Request Lagu Favoritmu</h1>
    <p class="text-mocha mt-3">Nikmati kopimu, kami putarkan lagunya 🎶</p>
  </div>

  <?php if ($flash): ?>
    <div class="mb-5 rounded-xl bg-green-100 text-green-800 px-4 py-3 text-sm"><?= e($flash) ?></div>
  <?php endif; ?>
  <?php if ($errors): ?>
    <div class="mb-5 rounded-xl bg-red-100 text-red-800 px-4 py-3 text-sm">
      <ul class="list-disc pl-5"><?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form action="store.php" method="POST" class="bg-white rounded-3xl shadow-lg shadow-mocha/10 p-7 space-y-5 border border-latte">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <div>
      <label class="block text-sm font-medium mb-1.5" for="song_title">Judul Lagu </label>
      <input type="text" id="song_title" name="song_title" maxlength="150" required
             value="<?= e($old['song_title'] ?? '') ?>"
             class="w-full rounded-xl border border-latte bg-cream/50 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1.5" for="artist_name">Nama Musisi </label>
      <input type="text" id="artist_name" name="artist_name" maxlength="150" required
             value="<?= e($old['artist_name'] ?? '') ?>"
             class="w-full rounded-xl border border-latte bg-cream/50 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1.5" for="message">Pesan Singkat (opsional)</label>
      <textarea id="message" name="message" rows="3" maxlength="255"
                class="w-full rounded-xl border border-latte bg-cream/50 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel"
                placeholder="Contoh: Buat yang lagi ulang tahun hari ini"><?= e($old['message'] ?? '') ?></textarea>
    </div>

    <button type="submit"
            class="w-full bg-mocha hover:bg-espresso text-white font-medium rounded-xl py-3 transition">
      Kirim Request ☕
    </button>
  </form>

  <p class="text-center mt-6 text-sm">
    <a href="monitor.php" class="text-mocha underline hover:text-caramel">Lihat antrean lagu →</a>
  </p>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>