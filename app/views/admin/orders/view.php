<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <a href="/doan/admin/orders" class="btn btn-secondary mb-3">Quay lại Đơn hàng</a>

  <h2>Chi tiết đơn hàng</h2>
  <h4>Mã đơn hàng: <?php echo $order['id']; ?></h4>
  <p>Khách hàng: <?php echo $order['customer_name']; ?></p>
  <p>Email: <?php echo $order['email']; ?></p>
  <p>Số điện thoại: <?php echo $order['phone']; ?></p>
  <p>Địa chỉ: <?php echo $order['address']; ?></p>
  <p>Tổng cộng: <?php echo $order['total_amount']; ?></p>
  <p>Trạng thái đơn hàng: <?php echo $order['status']; ?></p>
  <p>Hình thức thanh toán: <?php echo $order['payment_method']; ?></p>
  <p>Trạng thái thanh toán: <?php echo $order['payment_status']; ?></p>

  <h4>Sản phẩm:</h4>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Tên sản phẩm</th>
      <th>Số lượng</th>
      <th>Đơn giá</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($order['items'] as $item): ?>
      <tr>
        <td><?php echo $item['product_name']; ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo $item['price']; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="row">
    <form action="/doan/admin/orders/update_status?id=<?php echo $order['id']; ?>" method="POST" class="col-6">
      <div class="form-group">
        <label class="form-label">Trạng thái đơn hàng:</label>
        <select name="status" class="form-control">
          <option value="Pending" <?php if ($order['status'] === 'Pending') echo 'selected'; ?>>Đang chờ xử lý</option>
          <option value="Processing" <?php if ($order['status'] === 'Processing') echo 'selected'; ?>>Đang giao</option>
          <option value="Completed" <?php if ($order['status'] === 'Completed') echo 'selected'; ?>>Hoàn thành</option>
          <option value="Cancelled" <?php if ($order['status'] === 'Cancelled') echo 'selected'; ?>>Hủy đặt</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Cập nhật trạng thái</button>
    </form>
    <?php if ($order['payment_method'] === 'cod') : ?>
      <form action="/doan/admin/orders/update_payment_status?id=<?php echo $order['id']; ?>" method="POST" class="col-6">
        <div class="form-group">
          <label class="form-label">Trạng thái thanh toán:</label>
          <select name="status" class="form-control">
            <option value="pending" <?php if ($order['payment_status'] === 'pending') echo 'selected'; ?>>Chưa thanh toán</option>
            <option value="paid" <?php if ($order['payment_status'] === 'paid') echo 'selected'; ?>>Đã thanh toán</option>
            <option value="failed" <?php if ($order['payment_status'] === 'failed') echo 'selected'; ?>>Lỗi thanh toán</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật trạng thái</button>
      </form>
    <?php else : ?>
      <div class="form-group col-6">
        <label class="form-label">Trạng thái thanh toán:</label>
        <select name="status" class="form-control" disabled>
          <option value="pending" <?php if ($order['payment_status'] === 'pending') echo 'selected'; ?>>Chưa thanh toán</option>
          <option value="paid" <?php if ($order['payment_status'] === 'paid') echo 'selected'; ?>>Đã thanh toán</option>
          <option value="failed" <?php if ($order['payment_status'] === 'failed') echo 'selected'; ?>>Lỗi thanh toán</option>
        </select>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
