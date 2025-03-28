<?php
use app\middleware\AuthMiddleware;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Store</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="padding: 1.5rem 0">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <a class="navbar-brand" href="/doan/customer/home">My Store</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="/doan/customer/home">Trang chủ</a></li>
      <li class="nav-item"><a class="nav-link" href="/doan/customer/products">Sản phẩm</a></li>
      <?php if (AuthMiddleware::isCustomer()): ?>
        <li class="nav-item"><a class="nav-link" href="/doan/customer/cart">Giỏ hàng</a></li>
        <li class="nav-item"><a class="nav-link" href="/doan/customer/orders">Lịch sử đặt hàng</a></li>
      <?php endif; ?>
    </ul>
    <ul class="navbar-nav">
      <?php if (isset($_SESSION['user_customer'])): ?>
        <li class="nav-item"><a class="nav-link" href="/doan/customer/profile"><?= htmlspecialchars($_SESSION['user_customer']['name']) ?></a></li>
        <li class="nav-item"><a class="btn btn-danger" href="/doan/customer/logout">Đăng xuất</a></li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="/doan/customer/login">Đăng nhập</a></li>
        <li class="nav-item"><a class="btn btn-primary" href="/doan/customer/register">Đăng ký</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
