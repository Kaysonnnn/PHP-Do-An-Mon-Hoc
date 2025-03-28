<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h2>Thêm danh mục mới</h2>
  <form action="/doan/admin/categories/create" method="POST">
    <div class="form-group">
      <label for="name">Tên danh mục</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea name="description" id="description" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
