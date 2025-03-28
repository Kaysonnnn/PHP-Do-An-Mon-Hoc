<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\helpers\JWTHandler;
use app\models\User;
use Google\Client;
use Google\Service\Oauth2;

class GoogleAuthApiController
{
  private $client;
  private $userModel;
  private $jwtHandler;

  public function __construct()
  {
    $this->client = new Client();
    $this->userModel = new User();
    $this->jwtHandler = new JWTHandler();

    // Cấu hình Google API Client
    $this->client->setClientId('54931681281-sn0pu3sccspg2hvnton1c24ed0ni8ich.apps.googleusercontent.com');
    $this->client->setClientSecret('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');
    $this->client->setRedirectUri('https://app.php.khanhduy1407.live/auth/google-callback');
    $this->client->addScope('email');
    $this->client->addScope('profile');
  }

  /**
   * @OA\Get(
   *     path="/auth/google-auth-url",
   *     summary="Get Google auth url",
   *     tags={"Google Auth"},
   *     @OA\Response(
   *         response=200,
   *         description="Get url successfully"
   *     )
   * )
   */
  public function getGoogleAuthUrl()
  {
    $authUrl = $this->client->createAuthUrl();
    echo json_encode(['url' => $authUrl]);
    exit();
  }

  /**
   * @OA\Get(
   *     path="/auth/google-callback",
   *     summary="Google auth callback",
   *     tags={"Google Auth"},
   *     @OA\Parameter(
   *         name="code",
   *         in="path",
   *         required=true,
   *         description="Auth code",
   *         @OA\Schema(
   *             type="string",
   *             example="abcd1234"
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Create user if not exists and return token",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="token", type="string", example="abcd1234")
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="'code' Not Found."
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Error access token with auth code"
   *     )
   * )
   */
  public function handleGoogleCallback()
  {
    if (!isset($_GET['code'])) {
      http_response_code(404);
      echo json_encode(["error" => "Authorization failed!"]);
      exit();
    }

    $token = $this->client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
      http_response_code(400);
      echo json_encode(["error" => $token['error']]);
      exit();
    }

    $this->client->setAccessToken($token);

    $googleService = new Oauth2($this->client);
    $googleUser = $googleService->userinfo->get();

    $googleId = $googleUser->id;
    $email = $googleUser->email;
    $name = $googleUser->name;

    // Kiểm tra hoặc tạo người dùng trong DB
    $user = $this->userModel->findByGoogleId($googleId);
    if (!$user) {
      $this->userModel->createGoogleUser($googleId, $email, $name);
      $user = $this->userModel->findByGoogleId($googleId);
    }

    // Tạo JWT hoặc session cho API
    $token = $this->jwtHandler->encode([
      'user_customer' => $user,
    ]);
    echo json_encode(['token' => $token]);
    exit();
  }
}
