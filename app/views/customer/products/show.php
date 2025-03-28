<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <img src="/doan/uploads/<?= $product['image'] ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="col-md-6">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p>$<?= number_format($product['price'], 2) ?></p>
      <p><?= htmlspecialchars($product['description']) ?></p>
      <a href="/doan/customer/cart/add/<?= $product['id'] ?>" class="btn btn-success">Thêm vào giỏ</a>
    </div>
  </div>

  <hr>

  <!-- Bình luận -->
  <h3>Bình luận</h3>
  <p><i>(chỉ các đánh giá đã phê duyệt sẽ được hiển thị)</i></p>
  <?php foreach ($comments as $comment): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($comment['customer_name']) ?></h5>
        <p class="card-text"><?= htmlspecialchars($comment['comment']) ?></p>
        <p class="card-text"><small class="text-muted">Đánh giá: <?= $comment['rating'] ?> / 5 - <?= $comment['created_at'] ?></small></p>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Form thêm bình luận -->
  <?php if (isset($_SESSION['user_customer'])): ?>
    <form action="/doan/customer/comments/add/<?= $product['id'] ?>" method="POST">
      <div class="form-group">
        <label for="comment">Bình luận của bạn</label>
        <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
      </div>
      <div class="form-group">
        <label for="rating">Đánh giá</label>
        <select name="rating" id="rating" class="form-control" required>
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Gửi</button>
    </form>
  <?php else: ?>
    <p>Vui lòng <a href="/doan/customer/login">đăng nhập</a> để bình luận.</p>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
