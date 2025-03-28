<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Đăng nhập</h2>
  <form action="/doan/customer/login" method="POST">
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="password">Mật khẩu</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
  </form>

  <hr>

  <a href="/doan/customer/register">
    Đăng ký tài khoản mới
  </a>

  <hr>

  <!-- Nút Google Sign-In -->
  <a href="/doan/customer/google-login" class="btn btn-danger">
    Đăng nhập với Google
  </a>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
