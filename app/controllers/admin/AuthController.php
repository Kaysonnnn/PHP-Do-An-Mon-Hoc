<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;
use app\helpers\Csrf;
use app\middleware\AuthMiddleware;

class AuthController
{
  private $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function register()
  {
    if (!AuthMiddleware::isLoggedIn()) {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra CSRF Token
        if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
          // die('Invalid CSRF token.');
          die('Yêu cầu gửi đi không hợp lệ!');
        }

        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($this->userModel->register($username, $name, null, $password, 'admin')) {
          Csrf::clearToken(); // Xóa token để tránh xử dụng lại
          header('Location: /doan/admin/login');
        } else {
          echo "Đăng ký thất bại!";
        }
      } else {
        require_once __DIR__ . '/../../views/admin/auth/register.php';
      }
    } else {
      header('Location: /doan/admin/dashboard');
    }
  }

  public function login()
  {
    if (!AuthMiddleware::isLoggedIn()) {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Kiểm tra CSRF Token
        if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
          // die('Invalid CSRF token.');
          die('Yêu cầu gửi đi không hợp lệ!');
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $this->userModel->login($username, $password);
        if ($user) {
          Csrf::clearToken(); // Xóa token sau khi xử lý
          $_SESSION['user_admin'] = $user;
          header('Location: /doan/admin/dashboard');
        } else {
          echo "Đăng nhập thất bại!";
        }
      } else {
        require_once __DIR__ . '/../../views/admin/auth/login.php';
      }
    } else {
      header('Location: /doan/admin/dashboard');
    }
  }

  public function logout()
  {
    if (AuthMiddleware::isLoggedIn()) {
      session_destroy();
      header('Location: /doan/admin/login');
      exit();
    }
  }
}
