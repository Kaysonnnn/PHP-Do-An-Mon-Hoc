<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="padding: 1.5rem 0">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="/doan/admin/dashboard">Admin Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="/doan/admin/dashboard">Trang chủ</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/products">Sản phẩm</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/categories">Danh mục</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/orders">Đơn đặt hàng</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/customers">Khách hàng</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/comments">Đánh giá</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/admin/dashboard2">Thống kê</a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="#"><?= htmlspecialchars($_SESSION['user_admin']['name']) ?></a>
      </li>
      <li class="nav-item">
        <a class="btn btn-danger" href="/doan/admin/logout">Đăng xuất</a>
      </li>
    </ul>
  </div>
</nav>
