<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h2>Sửa khách hàng</h2>
  <form action="/doan/admin/customers/edit?id=<?php echo $customer['id']; ?>" method="POST">
    <div class="form-group">
      <label for="name">Họ tên khách hàng</label>
      <input type="text" name="name" id="name" value="<?php echo $customer['name']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?php echo $customer['email']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="phone">Số điện thoại</label>
      <input type="text" name="phone" id="phone" value="<?php echo $customer['phone']; ?>" class="form-control">
    </div>
    <div class="form-group">
      <label for="address">Địa chỉ</label>
      <textarea name="address" id="address" class="form-control"><?php echo $customer['address']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật khách hàng</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
