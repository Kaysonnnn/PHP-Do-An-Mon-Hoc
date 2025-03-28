<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;

class CartController
{
  public function index()
  {
    $cart = $_SESSION['cart'] ?? [];
    require_once __DIR__ . '/../../views/customer/cart/index.php';
  }

  public function add($productId)
  {
    require_once __DIR__ . '/../../models/Product.php';
    $productModel = new Product();
    $product = $productModel->getProductById($productId);

    if (!$product) {
      header('Location: /doan/customer');
      exit();
    }

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
      $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
      $_SESSION['cart'][$productId] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'],
        'quantity' => 1,
      ];
    }

    header('Location: /doan/customer/cart');
    exit();
  }

  public function update()
  {
    foreach ($_POST['quantities'] as $productId => $quantity) {
      if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = max(1, (int) $quantity);
      }
    }
    header('Location: /doan/customer/cart');
    exit();
  }

  public function remove($productId)
  {
    if (isset($_SESSION['cart'][$productId])) {
      unset($_SESSION['cart'][$productId]);
    }
    header('Location: /doan/customer/cart');
    exit();
  }

  public function clear()
  {
    unset($_SESSION['cart']);
    header('Location: /doan/customer/cart');
    exit();
  }
}