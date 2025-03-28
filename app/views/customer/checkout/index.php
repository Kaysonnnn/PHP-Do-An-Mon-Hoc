<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<div class="container mt-5">
  <h2>Thanh toán</h2>
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
        <td><?= $item['quantity'] ?></td>
        <td>$<?= number_format($total, 2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <h4>Tổng cộng: $<?= number_format($grandTotal, 2) ?></h4>
  <form id="payment-form" action="/doan/customer/checkout/store" method="POST">
    <div class="mb-3">
      <label for="payment-method">Chọn hình thức thanh toán:</label>
      <select id="payment-method" name="payment_method" class="form-control">
        <option value="cod">Thanh toán khi nhận hàng</option>
        <option value="stripe">Thanh toán với Stripe</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Tiếp tục</button>
  </form>
</div>

<script>
  document.getElementById('payment-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const paymentMethod = document.getElementById('payment-method').value;

    if (paymentMethod === 'cod') {
      // Nếu chọn COD, gửi form như bình thường
      this.submit();
    } else if (paymentMethod === 'stripe') {
      window.location.href = "/doan/customer/checkout/stripe";
    }
  });
</script>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
