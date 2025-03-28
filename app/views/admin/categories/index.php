<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Quản lý danh mục</h1>
  <a href="/doan/admin/categories/create" class="btn btn-primary mb-3">Thêm danh mục mới</a>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Mã DM</th>
      <th>Tên danh mục</th>
      <th>Mô tả</th>
      <th>Ngày tạo</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $category): ?>
      <tr>
        <td><?php echo $category['id']; ?></td>
        <td><?php echo $category['name']; ?></td>
        <td><?php echo $category['description']; ?></td>
        <td><?php echo $category['created_at']; ?></td>
        <td>
          <a href="/doan/admin/categories/edit?id=<?php echo $category['id']; ?>" class="btn btn-warning">Sửa</a>
          <a href="/doan/admin/categories/delete?id=<?php echo $category['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
