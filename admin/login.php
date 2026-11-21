<?php
require __DIR__ . '/../config/config.php';
$pageTitle = 'Login Admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if (hash_equals(ADMIN_USER, $u) && hash_equals(ADMIN_PASS, $p)) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
include __DIR__ . '/../includes/header.php';
?>
<main class="max-w-sm mx-auto px-5 py-20">
  <h1 class="font-serif text-3xl font-bold text-center mb-6">Admin Café</h1>
  <?php if ($error): ?>
    <div class="mb-4 rounded-xl bg-red-100 text-red-800 px-4 py-3 text-sm"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="POST" class="bg-white border border-latte rounded-3xl shadow-lg p-6 space-y-4">
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
    <input type="text" name="username" placeholder="Username" required
           class="w-full rounded-xl border border-latte px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel">
    <input type="password" name="password" placeholder="Password" required
           class="w-full rounded-xl border border-latte px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-caramel">
    <button class="w-full bg-mocha hover:bg-espresso text-white rounded-xl py-2.5 transition">Masuk</button>
  </form>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>