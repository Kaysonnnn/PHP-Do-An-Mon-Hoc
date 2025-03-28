<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Order;
use app\middleware\AuthMiddleware;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController
{
  private $orderModel;
  private $stripeConfig;

  public function __construct()
  {
    $this->orderModel = new Order();
    $config = require __DIR__ . '/../../config/config.php';
    $this->stripeConfig = $config['stripe'];
  }

  public function index()
  {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
      echo "Giỏ hàng của bạn đang trống!";
      exit();
    }

    require_once __DIR__ . '/../../views/customer/checkout/index.php';
  }

  public function store()
  {
    AuthMiddleware::checkCustomerAuth();

    $paymentMethod = $_POST['payment_method'];

    if ($paymentMethod === 'cod') {
      $this->storeCod();
    } elseif ($paymentMethod === 'stripe') {
      $this->storeStripe();
    }
  }

  public function storeCod()
  {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
      header('Location: /doan/customer/cart');
      exit();
    }

    $customerId = $_SESSION['user_customer']['id'];
    $totalAmount = array_reduce($cart, function ($sum, $item) {
      return $sum + $item['price'] * $item['quantity'];
    }, 0);

    // Tạo đơn hàng
    $orderId = $this->orderModel->createOrder($customerId, $totalAmount, 'cod', 'pending');

    // Lưu chi tiết sản phẩm
    foreach ($cart as $item) {
      $this->orderModel->addOrderItem($orderId, $item['id'], $item['name'], $item['quantity'], $item['price']);
    }

    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);

    echo "Đặt hàng thành công với COD!";
    echo "<br><a href='/doan/customer/orders/$orderId'>Xem đơn hàng</a>";
  }

  public function storeStripe()
  {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
      header('Location: /doan/customer/cart');
      exit();
    }

    Stripe::setApiKey($this->stripeConfig['secret_key']);

    $customerId = $_SESSION['user_customer']['id'];
    $totalAmount = array_reduce($cart, function ($sum, $item) {
      return $sum + $item['price'] * $item['quantity'];
    }, 0);

    try {
      $paymentIntent = PaymentIntent::create([
        'amount' => $totalAmount * 100, // Stripe expects the amount in cents
        'currency' => 'usd',
      ]);

      $publishableKey = $this->stripeConfig['publishable_key'];
      $clientSecret = $paymentIntent->client_secret;

      require_once __DIR__ . '/../../views/customer/checkout/stripe_payment.php';
    } catch (\Exception $e) {
      echo "Lỗi tạo thanh toán: " . $e->getMessage();
    }
  }

  public function confirmStripe()
  {
    AuthMiddleware::checkCustomerAuth();

    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
      header('Location /doan/customer/cart');
      exit();
    }

    $customerId = $_SESSION['user_customer']['id'];
    $totalAmount = array_reduce($cart, function ($sum, $item) {
      return $sum + $item['price'] * $item['quantity'];
    }, 0);

    // Tạo đơn hàng
    $orderId = $this->orderModel->createOrder($customerId, $totalAmount, 'stripe', 'paid');

    // Lưu chi tiết sản phẩm
    foreach ($cart as $item) {
      $this->orderModel->addOrderItem($orderId, $item['id'], $item['name'], $item['quantity'], $item['price']);
    }

    unset($_SESSION['cart']);
    echo "Đặt hàng thành công với Stripe!";
    echo "<br><a href='/doan/customer/orders/$orderId'>Xem đơn hàng</a>";
  }

  public function orders()
  {
    AuthMiddleware::checkCustomerAuth();
    $customerId = $_SESSION['user_customer']['id'];
    $orders = $this->orderModel->getOrdersByUser($customerId);
    require_once __DIR__ . '/../../views/customer/orders/index.php';
  }

  public function details($orderId)
  {
    AuthMiddleware::checkCustomerAuth();
    $customerId = $_SESSION['user_customer']['id'];

    $order = $this->orderModel->getOrder($orderId, $customerId);
    if (!$order) {
      echo "Không tìm thấy đơn hàng hoặc bị từ chối truy cập.";
      exit();
    }

    $orderDetails = $this->orderModel->getOrderDetails($orderId, $customerId);
    require_once __DIR__ . '/../../views/customer/orders/details.php';
  }
}