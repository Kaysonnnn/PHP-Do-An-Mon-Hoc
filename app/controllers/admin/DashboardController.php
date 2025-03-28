<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\User;
use app\models\Order;
use app\middleware\AuthMiddleware;

class DashboardController
{
  private $productModel;
  private $userModel;
  private $orderModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->userModel = new User();
    $this->orderModel = new Order();
  }

  public function index()
  {
    AuthMiddleware::checkAdminAuth(); // Kiểm tra đăng nhập
    require_once __DIR__ . '/../../views/admin/app/dashboard.php';
  }

  public function index2()
  {
    AuthMiddleware::checkAdminAuth();

    $productStats = $this->productModel->getStatistics();
    $customerStats = $this->userModel->getStatistics();
    $orderStats = $this->orderModel->getStatistics();

    require_once __DIR__ . '/../../views/admin/dashboard/index.php';
  }
}
