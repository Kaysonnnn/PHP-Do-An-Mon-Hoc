<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Chi tiết đơn hàng</h2>
  <h5>Mã đơn hàng: <?= htmlspecialchars($order['id']) ?></h5>
  <p>Trạng thái đơn: <strong><?= ucfirst(htmlspecialchars($order['status'])) ?></strong></p>
  <p>Hình thức thanh toán: <strong><?= ucfirst(htmlspecialchars($order['payment_method'])) ?></strong></p>
  <p>Trạng thái thanh toán: <strong><?= ucfirst(htmlspecialchars($order['payment_status'])) ?></strong></p>
  <p>Ngày đặt hàng: <?= htmlspecialchars($order['created_at']) ?></p>
  <p>Tổng cộng: <strong>$<?= number_format($order['total_amount'], 2) ?></strong></p>
  <hr>

  <h4>Sản phẩm</h4>
  <table class="table">
    <thead>
    <tr>
      <th>Hình</th>
      <th>Tên</th>
      <th>Đơn giá</th>
      <th>Số lượng</th>
      <th>Tổng</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orderDetails as $item): ?>
      <tr>
        <td>
          <img src="/doan/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px;">
        </td>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td>$<?= number_format($item['price'], 2) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <a href="/doan/customer/orders" class="btn btn-secondary">Quay lại Lịch sử đặt hàng</a>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
