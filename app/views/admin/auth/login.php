<?php
use app\helpers\Csrf;
?>

<?php include 'app/views/admin/shared/authStart.php'; ?>

<h2>Đăng nhập</h2>

<form action="/doan/admin/login" method="POST">
  <input type="hidden" name="csrf_token" value="<?php echo Csrf::generateToken(); ?>"> <!-- Tạo mã bảo mật -->

  <div class="form-group">
    <label for="username">Tên người dùng</label>
    <input type="text" name="username" id="username" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="password">Mật khẩu</label>
    <input type="password" name="password" id="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>

<?php include 'app/views/admin/shared/authEnd.php'; ?>
