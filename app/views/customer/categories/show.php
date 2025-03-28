<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Danh mục: <?= htmlspecialchars($category['name']) ?></h2>
  <p><?= htmlspecialchars($category['description']) ?></p>
  <hr>

  <!-- Bộ lọc -->
  <div class="mb-4">
    <form method="GET" class="form-inline">
      <label for="sort" class="mr-2">Sắp xếp theo:</label>
      <select name="sort" id="sort" class="form-control mr-2">
        <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Tên</option>
        <option value="price" <?= $sort === 'price' ? 'selected' : '' ?>>Giá</option>
      </select>
      <select name="order" id="order" class="form-control mr-2">
        <option value="ASC" <?= $order === 'ASC' ? 'selected' : '' ?>>Tăng dần</option>
        <option value="DESC" <?= $order === 'DESC' ? 'selected' : '' ?>>Giảm dần</option>
      </select>
      <button type="submit" class="btn btn-primary">Áp dụng</button>
    </form>
  </div>

  <!-- Danh sách sản phẩm -->
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="/doan/uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
            <a href="/doan/customer/products/<?= $product['id'] ?>" class="btn btn-primary">Xem chi tiết</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Phân trang -->
  <nav>
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&sort=<?= $sort ?>&order=<?= $order ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
