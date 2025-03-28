<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;
use app\middleware\AuthMiddleware;

class CustomerController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function index()
  {
    AuthMiddleware::checkAdminAuth();
    $customers = $this->userModel->getAllCustomers();
    require_once __DIR__ . '/../../views/admin/customers/index.php';
  }

  public function edit($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address']
      ];
      if ($this->userModel->updateCustomer($id, $data)) {
        header('Location: /doan/admin/customers');
      } else {
        echo "Lỗi cập nhật khách hàng";
      }
    } else {
      $customer = $this->userModel->getCustomerById($id);
      require_once __DIR__ . '/../../views/admin/customers/edit.php';
    }
  }

  public function delete($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->userModel->deleteCustomer($id)) {
      header('Location: /doan/admin/customers');
    } else {
      echo "Lỗi xóa khách hàng";
    }
  }
}