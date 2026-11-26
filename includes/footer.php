<footer class="text-center text-sm text-mocha/70 py-8">
  &copy; <?= date('Y') ?> Café Song Request · Dibuat dengan ☕
  <?php if (empty($hideAdminLink)): ?>
    <br>
    <a href="/Cafe-request/admin/login.php" class="underline hover:text-caramel">Login Admin</a>
  <?php endif; ?>
</footer>
</body>
</html>