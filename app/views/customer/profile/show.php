<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Hồ sơ cá nhân</h2>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
    <?php unset($_SESSION['error_message']); ?>
  <?php endif; ?>

  <form action="/doan/customer/profile/update" method="POST">
    <div class="form-group">
      <label for="name">Họ tên:</label>
      <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="form-group">
      <label for="phone">Số điện thoại:</label>
      <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="form-group">
      <label for="address">Địa chỉ:</label>
      <input type="text" name="address" id="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
  </form>

  <hr>

  <h3>Đổi mật khẩu</h3>
  <form action="/doan/customer/profile/password" method="POST">
    <div class="form-group">
      <label for="current_password">Mật khẩu hiện tại:</label>
      <input type="password" name="current_password" id="current_password" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="new_password">Mật khẩu mới:</label>
      <input type="password" name="new_password" id="new_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
