<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Đăng ký</h2>
  <form action="/doan/customer/register" method="POST">
    <div class="form-group">
      <label for="name">Họ tên</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="password">Mật khẩu</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <!-- TODO: Không cần thiết phải thêm chọn quyền ở đăng ký tài khoản khách hàng -->
    <!--<div class="form-group">
      <label for="role">Role</label>
      <select name="role" id="role" class="form-control">
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
      </select>
    </div>-->
    <button type="submit" class="btn btn-primary">Đăng ký</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
