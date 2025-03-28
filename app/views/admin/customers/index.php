<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1 class="mb-3">Quản lý khách hàng</h1>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Mã KH</th>
      <th>Tên</th>
      <th>Email</th>
      <th>Số điện thoại</th>
      <th>Địa chỉ</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($customers as $customer): ?>
      <tr>
        <td><?php echo $customer['id']; ?></td>
        <td><?php echo $customer['name']; ?></td>
        <td><?php echo $customer['email']; ?></td>
        <td><?php echo $customer['phone']; ?></td>
        <td><?php echo $customer['address']; ?></td>
        <td>
          <a href="/doan/admin/customers/edit?id=<?php echo $customer['id']; ?>" class="btn btn-warning">Sửa</a>
          <a href="/doan/admin/customers/delete?id=<?php echo $customer['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này không?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
