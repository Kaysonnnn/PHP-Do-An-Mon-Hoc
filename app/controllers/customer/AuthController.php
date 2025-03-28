<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;

class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function showRegister()
  {
    require_once __DIR__ . '/../../views/customer/auth/register.php';
  }

  public function register()
  {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'customer';

    if ($this->userModel->register(null, $name, $email, $password, $role)) {
      header('Location: /doan/customer/login');
    } else {
      echo "Lỗi xảy ra trong quá trình đăng ký!";
    }
  }

  public function showLogin()
  {
    require_once __DIR__ . '/../../views/customer/auth/login.php';
  }

  public function login()
  {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $this->userModel->getUserByEmail($email);

    if ($user && password_verify($password, $user['password']) && $user['role'] == 'customer') {
      $_SESSION['user_customer'] = $user;
      header('Location: /doan/customer/home');
    } else {
      echo "Thông tin xác thực không hợp lệ!";
    }
  }

  public function logout()
  {
    session_destroy();
    header('Location: /doan/customer/login');
  }
}