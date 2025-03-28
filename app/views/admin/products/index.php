<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Quản lý sản phẩm</h1>
  <a href="/doan/admin/products/create" class="btn btn-primary mb-3">Thêm sản phẩm mới</a>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Mã SP</th>
      <th>Danh mục</th>
      <th>Tên SP</th>
      <th>Đơn giá</th>
      <th>Tồn kho</th>
      <th>Hình ảnh</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
      <tr>
        <td><?php echo $product['id']; ?></td>
        <td><?php echo $product['category_name']; ?></td>
        <td><?php echo $product['name']; ?></td>
        <td><?php echo $product['price']; ?></td>
        <td><?php echo $product['stock']; ?></td>
        <td>
          <?php if ($product['image']): ?>
            <img src="/doan/uploads/<?php echo $product['image']; ?>" width="50" height="50" alt="Image">
          <?php else: ?>
            N/A
          <?php endif; ?>
        </td>
        <td>
          <a href="/doan/admin/products/edit?id=<?php echo $product['id']; ?>" class="btn btn-warning">Sửa</a>
          <a href="/doan/admin/products/delete?id=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
