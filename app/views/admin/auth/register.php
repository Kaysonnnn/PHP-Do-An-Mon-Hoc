<?php
use app\helpers\Csrf;
?>

<?php include 'app/views/admin/shared/authStart.php'; ?>

<h2>Đăng ký</h2>

<form action="/doan/admin/register" method="POST">
  <input type="hidden" name="csrf_token" value="<?php echo Csrf::generateToken(); ?>"> <!-- Tạo mã bảo mật -->

  <div class="form-group">
    <label for="name">Họ tên</label>
    <input type="text" name="name" id="name" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="username">Tên người dùng</label>
    <input type="text" name="username" id="username" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="password">Mật khẩu</label>
    <input type="password" name="password" id="password" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-primary">Đăng ký</button>
</form>

<?php include 'app/views/admin/shared/authEnd.php'; ?>
