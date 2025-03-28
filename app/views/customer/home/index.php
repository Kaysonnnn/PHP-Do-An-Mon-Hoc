<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <!-- Categories -->
  <div class="mb-4">
    <h3>Danh mục</h3>
    <div class="row">
      <?php foreach ($categories as $category): ?>
        <div class="col-md-3">
          <a href="/doan/customer/category/<?php echo $category['id']; ?>" class="text-decoration-none">
            <div class="card">
              <div class="card-body text-center">
                <?php echo $category['name']; ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Featured Products -->
  <div class="mb-4">
    <h3>Sản phẩm nổi bật</h3>
    <div class="row">
      <?php foreach ($featuredProducts as $product): ?>
        <div class="col-md-3">
          <div class="card">
            <img src="/doan/uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $product['name']; ?></h5>
              <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
              <a href="/doan/customer/products/<?php echo $product['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- New Products -->
  <div class="mb-4">
    <h3>Sản phẩm mới</h3>
    <div class="row">
      <?php foreach ($newProducts as $product): ?>
        <div class="col-md-3">
          <div class="card">
            <img src="/doan/uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?php echo $product['name']; ?></h5>
              <p class="card-text">$<?php echo number_format($product['price'], 2); ?></p>
              <a href="/doan/customer/products/<?php echo $product['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="mb-4">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.4184519722626!2d106.78303187476726!3d10.855743457726295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175276e7ea103df%3A0xb6cf10bb7d719327!2sHUTECH%20University%20-%20Thu%20Duc%20Campus!5e0!3m2!1sen!2s!4v1734237032107!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
