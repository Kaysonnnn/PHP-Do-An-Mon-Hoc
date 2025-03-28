<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Giỏ hàng của bạn</h2>
  <form action="/doan/customer/cart/update" method="POST">
    <table class="table">
      <thead>
      <tr>
        <th>Hình</th>
        <th>Tên</th>
        <th>Đơn giá</th>
        <th>Số lượng</th>
        <th>Tổng</th>
        <th>Hành động</th>
      </tr>
      </thead>
      <tbody>
      <?php
      $grandTotal = 0;
      foreach ($cart as $item):
        $total = $item['price'] * $item['quantity'];
        $grandTotal += $total;
        ?>
        <tr>
          <td><img src="/doan/uploads/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 50px;"></td>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td>$<?= number_format($item['price'], 2) ?></td>
          <td>
            <input type="number" name="quantities[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1" class="form-control" style="width: 80px;">
          </td>
          <td>$<?= number_format($total, 2) ?></td>
          <td>
            <a href="/doan/customer/cart/remove/<?= $item['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-between">
      <h4>Tổng cộng: $<?= number_format($grandTotal, 2) ?></h4>
      <?php if (!empty($_SESSION['cart'])) : ?>
        <div>
          <button type="submit" class="btn btn-primary">Cập nhật</button>
          <a href="/doan/customer/cart/clear" class="btn btn-warning">Xóa giỏ</a>
          <a href="/doan/customer/checkout" class="btn btn-success">Tiến hành thanh toán</a>
        </div>
      <?php else : ?>
        <div>
          <p>
            Giỏ hàng đang trống
            <a href="/doan/customer/products" class="btn btn-primary">Mua hàng</a>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
