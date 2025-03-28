<?php
namespace app\helpers;

require_once __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler
{
  private $secret_key;

  public function __construct()
  {
    $this->secret_key = "HUTECH";
  }

  public function encode($data)
  {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // 1h
    $payload = array(
      'iat' => $issuedAt,
      'exp' => $expirationTime,
      'data' => $data,
    );

    return JWT::encode($payload, $this->secret_key, 'HS256');
  }

  public function decode($jwt)
  {
    try {
      $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
      return (array) $decoded->data;
    } catch (Exception $e) {
      return null;
    }
  }
}
