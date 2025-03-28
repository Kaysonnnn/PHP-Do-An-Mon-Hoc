<?php
namespace app\controllers\api;

use app\helpers\Csrf;

class SecureApiController
{
  public function getCsrfToken()
  {
    $token = Csrf::generateToken();
    echo json_encode(['csrf_token' => $token]);
  }
}