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

    <div class="relative">
      <label class="block text-sm font-medium mb-1.5" for="song_title">Judul Lagu</label>
      <input type="text" id="song_title" name="song_title" maxlength="150" required autocomplete="off"
             value="<?= e($old['song_title'] ?? '') ?>"
             class="w-full rounded-xl border border-latte bg-cream/50 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel">
      <input type="hidden" id="cover_url" name="cover_url" value="">

      <div id="suggestions"
           class="hidden absolute left-0 right-0 top-full mt-1 z-20 bg-white border border-latte rounded-xl shadow-lg overflow-hidden"></div>
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

<script>
const titleEl  = document.getElementById('song_title');
const artistEl = document.getElementById('artist_name');
const coverEl  = document.getElementById('cover_url');
const box      = document.getElementById('suggestions');
let timer;

titleEl.addEventListener('input', () => {
  coverEl.value = '';
  clearTimeout(timer);
  const q = titleEl.value.trim();
  if (q.length < 2) { box.classList.add('hidden'); return; }

  timer = setTimeout(async () => {
    try {
      const res  = await fetch('search.php?q=' + encodeURIComponent(q));
      const data = await res.json();
      box.innerHTML = '';

      if (!data.results || !data.results.length) { box.classList.add('hidden'); return; }

      data.results.forEach(song => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'w-full flex items-center gap-3 px-3 py-2 text-left hover:bg-latte/50 transition';

        const img = document.createElement('img');
        img.src = song.thumb;
        img.alt = '';
        img.className = 'w-10 h-10 rounded-lg shrink-0';

        const text = document.createElement('div');
        text.className = 'min-w-0';
        const t = document.createElement('p');
        t.className = 'text-sm font-medium truncate';
        t.textContent = song.title;
        const a = document.createElement('p');
        a.className = 'text-xs text-mocha truncate';
        a.textContent = song.artist;
        text.append(t, a);

        item.append(img, text);
        item.addEventListener('click', () => {
          titleEl.value  = song.title;
          artistEl.value = song.artist;
          coverEl.value  = song.cover;
          box.classList.add('hidden');
        });
        box.appendChild(item);
      });
      box.classList.remove('hidden');
    } catch (e) {
      console.error('Autocomplete gagal:', e);
      box.classList.add('hidden');
    }
  }, 300);
});

document.addEventListener('click', e => {
  if (!box.contains(e.target) && e.target !== titleEl) box.classList.add('hidden');
});
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>