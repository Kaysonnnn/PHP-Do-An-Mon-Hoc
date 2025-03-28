<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Order;
use app\middleware\AuthMiddleware;

class OrderController
{
  private $orderModel;

  public function __construct()
  {
    $this->orderModel = new Order();
  }

  public function index()
  {
    AuthMiddleware::checkAdminAuth();
    $orders = $this->orderModel->getAll();
    require_once __DIR__ . '/../../views/admin/orders/index.php';
  }

  public function view($id)
  {
    AuthMiddleware::checkAdminAuth();
    $order = $this->orderModel->getById($id);
    require_once __DIR__ . '/../../views/admin/orders/view.php';
  }

  public function updateStatus($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $status = $_POST['status'];
      if ($this->orderModel->updateStatus($id, $status)) {
        header("Location: /doan/admin/orders/view?id=$id");
      } else {
        echo "Lỗi cập nhật trạng thái đơn hàng.";
      }
    }
  }

  public function updatePaymentStatus($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $status = $_POST['status'];
      if ($this->orderModel->updatePaymentStatus($id, $status)) {
        header("Location: /doan/admin/orders/view?id=$id");
      } else {
        echo "Lỗi cập nhật trạng thái đơn hàng.";
      }
    }
  }

  public function delete($id) {
    AuthMiddleware::checkAdminAuth();
    if ($this->orderModel->delete($id)) {
      header('Location: /doan/admin/orders');
    } else {
      echo "Lỗi xóa đơn hàng.";
    }
  }
}