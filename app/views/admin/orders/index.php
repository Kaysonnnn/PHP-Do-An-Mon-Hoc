<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Quản lý đơn hàng</h1>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Mã ĐH</th>
      <th>Khách hàng</th>
      <th>Tổng tiền</th>
      <th>Trạng thái đơn hàng</th>
      <th>Hình thức thanh toán</th>
      <th>Trạng thái thanh toán</th>
      <th>Ngày đặt</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?php echo $order['id']; ?></td>
        <td><?php echo $order['customer_name']; ?></td>
        <td><?php echo $order['total_amount']; ?></td>
        <td><?php echo $order['status']; ?></td>
        <td><?php echo $order['payment_method']; ?></td>
        <td><?php echo $order['payment_status']; ?></td>
        <td><?php echo $order['created_at']; ?></td>
        <td>
          <a href="/doan/admin/orders/view?id=<?php echo $order['id']; ?>" class="btn btn-info">Xem</a>
          <form action="/doan/admin/orders/delete?id=<?php echo $order['id']; ?>" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa đơn hàng này không?')">
            <button type="submit" class="btn btn-danger">Xóa</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
