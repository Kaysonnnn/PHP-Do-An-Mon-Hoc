<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h2>Thêm sản phẩm mới</h2>
  <form action="/doan/admin/products/create" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="category_id">Danh mục</label>
      <select name="category_id" id="category_id" class="form-control" required>
        <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="name">Tên sản phẩm</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea name="description" id="description" class="form-control"></textarea>
    </div>
    <div class="form-group">
      <label for="price">Giá</label>
      <input type="number" step="0.01" name="price" id="price" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="stock">Số lượng tồn kho</label>
      <input type="number" name="stock" id="stock" class="form-control" required>
    </div>
    <div class="form-group">
      <label for="image">Hình ảnh</label>
      <input type="file" name="image" id="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
