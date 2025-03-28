<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Đơn hàng của tôi</h2>
  <table class="table">
    <thead>
    <tr>
      <th>Mã đơn hàng</th>
      <th>Tổng</th>
      <th>Trạng thái đơn</th>
      <th>Hình thức thanh toán</th>
      <th>Trạng thái thanh toán</th>
      <th>Ngày đặt</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td>$<?= number_format($order['total_amount'], 2) ?></td>
        <td><?= ucfirst($order['status']) ?></td>
        <td><?= ucfirst($order['payment_method']) ?></td>
        <td><?= ucfirst($order['payment_status']) ?></td>
        <td><?= $order['created_at'] ?></td>
        <td><a href="/doan/customer/orders/<?= $order['id'] ?>" class="btn btn-primary">Xem chi tiết</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
