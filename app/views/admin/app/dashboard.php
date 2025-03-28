<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Chào mừng đến Admin Dashboard</h1>
  <p>Xin chào, <strong><?php echo $_SESSION['user_admin']['username']; ?></strong>!</p>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
