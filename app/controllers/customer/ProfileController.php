<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;

class ProfileController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function showProfile()
  {
    if (!isset($_SESSION['user_customer'])) {
      header('Location: /doan/customer/login');
      exit();
    }

    $customerId = $_SESSION['user_customer']['id'];
    $user = $this->userModel->getCustomerById($customerId);

    require_once __DIR__ . '/../../views/customer/profile/show.php';
  }

  public function updateProfile()
  {
    if (!isset($_SESSION['user_customer'])) {
      header('Location: /doan/customer/login');
      exit();
    }

    $customerId = $_SESSION['user_customer']['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
      ];

      $this->userModel->updateCustomer($customerId, $data);

      $_SESSION['success_message'] = 'Đã cập nhật hồ sơ cá nhân thành công!';
      header('Location: /doan/customer/profile');
      exit();
    }
  }

  public function updatePassword()
  {
    if (!isset($_SESSION['user_customer'])) {
      header('Location: /doan/customer/login');
      exit();
    }

    $customerId = $_SESSION['user_customer']['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $currentPassword = $_POST['current_password'];
      $newPassword = $_POST['new_password'];

      $user = $this->userModel->getCustomerById($customerId);

      if (!password_verify($currentPassword, $user['password'])) {
        $_SESSION['error_message'] = 'Mật khẩu hiện tại không chính xác.';
        header('Location: /doan/customer/profile');
        exit();
      }

      $this->userModel->updatePassword($customerId, $newPassword);

      $_SESSION['success_message'] = 'Cập nhật mật khẩu thành công!';
      header('Location: /doan/customer/profile');
      exit();
    }
  }
}