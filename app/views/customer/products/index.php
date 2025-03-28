<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Sản phẩm</h2>
  <form method="GET" action="/doan/customer/products" class="mb-4">
    <input type="text" name="search" placeholder="🔎 Tìm kiếm sản phẩm..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
  </form>
  <div class="row">
    <?php foreach ($products as $product): ?>
      <div class="col-md-4 mb-4">
        <div class="card">
          <img src="/doan/uploads/<?= $product['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text">$<?= number_format($product['price'], 2) ?></p>
            <a href="/doan/customer/products/<?= $product['id'] ?>" class="btn btn-primary">Xem chi tiết</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <nav>
    <ul class="pagination">
      <?php
      $currentPage = $_GET['page'] ?? 1; // Đặt mặc định là trang 1 nếu không có tham số page
      for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
          <a class="page-link" href="/doan/customer/products?page=<?= $i ?>&search=<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <?= $i ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
