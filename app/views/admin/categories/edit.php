<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h2>Sửa danh mục</h2>
  <form action="/doan/admin/categories/edit?id=<?php echo $category['id']; ?>" method="POST">
    <div class="form-group">
      <label for="name">Tên danh mục</label>
      <input type="text" name="name" id="name" value="<?php echo $category['name']; ?>" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea name="description" id="description" class="form-control"><?php echo $category['description']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật danh mục</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
