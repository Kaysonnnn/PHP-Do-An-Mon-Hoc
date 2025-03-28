<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h2>Sửa sản phẩm</h2>
  <form action="/doan/admin/products/edit?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="category_id">Danh mục</label>
      <select name="category_id" id="category_id" class="form-control" required>
        <?php foreach ($categories as $category): ?>
          <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
            <?php echo $category['name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="name">Tên sản phẩm</label>
      <input type="text" name="name" id="name" class="form-control" value="<?php echo $product['name']; ?>" required>
    </div>
    <div class="form-group">
      <label for="description">Mô tả</label>
      <textarea name="description" id="description" class="form-control"><?php echo $product['description']; ?></textarea>
    </div>
    <div class="form-group">
      <label for="price">Giá</label>
      <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?php echo $product['price']; ?>" required>
    </div>
    <div class="form-group">
      <label for="stock">Số lượng tồn kho</label>
      <input type="number" name="stock" id="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
    </div>
    <div class="form-group">
      <label for="image">Hình ảnh</label>
      <input type="file" name="image" id="image" class="form-control">
      <?php if ($product['image']): ?>
        <p>Hình ảnh hiện tại: <img src="/doan/uploads/<?php echo $product['image']; ?>" width="100" alt="Image"></p>
        <input type="hidden" name="existing_image" value="<?php echo $product['image']; ?>">
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
