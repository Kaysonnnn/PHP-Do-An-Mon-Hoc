<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;
use Google\Client;
use Google\Service\Oauth2;

class GoogleAuthController
{
  private $client;
  private $userModel;

  public function __construct()
  {
    $this->client = new Client();
    $this->userModel = new User();

    // Cấu hình Google API Client
    $this->client->setClientId('54931681281-sn0pu3sccspg2hvnton1c24ed0ni8ich.apps.googleusercontent.com');
    $this->client->setClientSecret('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');
    $this->client->setRedirectUri('http://localhost:81/doan/customer/google-callback');
    $this->client->addScope('email');
    $this->client->addScope('profile');
  }

  // Chuyển hướng người dùng tới Google để đăng nhập
  public function redirectToGoogle()
  {
    $authUrl = $this->client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
  }

  public function handleGoogleCallback()
  {
    if(!isset($_GET['code'])) {
      echo "Authorization failed!";
      exit();
    }

    $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
      echo "Error fetching token: " . $token['error'];
      exit();
    }

    $this->client->setAccessToken($token);

    $googleService = new Oauth2($this->client);
    $googleUser = $googleService->userinfo->get();

    // Lấy thông tin người dùng
    $googleId = $googleUser->id;
    $email = $googleUser->email;
    $name = $googleUser->name;

    // Kiểm tra xem người dùng đã tồn tại chưa
    $user = $this->userModel->findByGoogleId($googleId);
    if (!$user) {
      // Thêm người dùng mới vào CSDL
      $this->userModel->createGoogleUser($googleId, $email, $name);
      $user = $this->userModel->findByGoogleId($googleId);
    }

    // Đăng nhập người dùng
    $_SESSION['user_customer'] = $user;
    header('Location: /doan/customer/');
    exit();
  }
}
