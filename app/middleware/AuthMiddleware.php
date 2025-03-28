<?php
namespace app\middleware;

class AuthMiddleware
{
  public static function checkAdminAuth()
  {
    if (!isset($_SESSION['user_admin'])) {
      header('Location: /doan/admin/login');
      exit();
    }
  }

  public static function checkCustomerAuth()
  {
    if (!isset($_SESSION['user_customer'])) {
      header('Location: /doan/customer/login');
      exit();
    }
  }

  public static function checkCustomerRole()
  {
    self::checkCustomerAuth();
    if ($_SESSION['user_customer']['role'] !== 'customer') {
      echo "Access Denied!";
      exit();
    }
  }

  public static function isAdmin()
  {
    return isset($_SESSION['user_admin']) && $_SESSION['user_admin']['role'] === 'admin';
  }

  public static function isCustomer()
  {
    return isset($_SESSION['user_customer']) && $_SESSION['user_customer']['role'] === 'customer';
  }

  public static function isLoggedIn()
  {
    return !empty($_SESSION['user_customer']) || !empty($_SESSION['user_admin']);
  }
}
