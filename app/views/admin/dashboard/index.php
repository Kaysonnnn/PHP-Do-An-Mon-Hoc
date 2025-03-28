<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Admin Dashboard</h1>
  <div class="row mt-5">
    <!-- Product Stats -->
    <div class="col-md-4">
      <h3>Sản phẩm</h3>
      <ul class="list-group">
        <li class="list-group-item">Tổng sản phẩm: <?php echo $productStats['total_products']; ?></li>
        <!--<li class="list-group-item">Tồn kho ít: <?php /*echo $productStats['low_stock_products']; */?></li>-->
        <li class="list-group-item">
          Bán chạy: <?php echo $productStats['top_selling_product']['name']; ?>
          (đã bán <?php echo $productStats['top_selling_product']['total_sold']; ?>)
        </li>
      </ul>
    </div>

    <!-- Customer Stats -->
    <div class="col-md-4">
      <h3>Khách hàng</h3>
      <ul class="list-group">
        <li class="list-group-item">Tổng khách hàng: <?php echo $customerStats['total_customers']; ?></li>
        <li class="list-group-item">
          Khách hàng hàng đầu: <?php echo $customerStats['top_customer']['name']; ?>
          (<?php echo $customerStats['top_customer']['total_orders']; ?> đơn hàng)
        </li>
      </ul>
    </div>

    <!-- Order Stats -->
    <div class="col-md-4">
      <h3>Đơn hàng</h3>
      <ul class="list-group">
        <li class="list-group-item">Tổng đơn hàng: <?php echo $orderStats['total_orders']; ?></li>
        <li class="list-group-item">Đang chờ xử lý: <?php echo $orderStats['pending_orders']; ?></li>
        <li class="list-group-item">Hoàn thành: <?php echo $orderStats['completed_orders']; ?></li>
        <li class="list-group-item">Tổng thu nhập: $<?php echo number_format($orderStats['total_revenue'], 2); ?></li>
      </ul>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
