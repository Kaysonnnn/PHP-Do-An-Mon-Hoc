<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\User;
use app\helpers\Csrf;
use app\helpers\JWTHandler;

class AuthApiController
{
  private $userModel;
  private $jwtHandler;

  public function __construct()
  {
    $this->userModel = new User();
    $this->jwtHandler = new JWTHandler();
  }

  /**
   * @OA\Get(
   *     path="/auth/test",
   *     summary="Test API",
   *     description="A simple API to test the connection.",
   *     tags={"Auth"},
   *     @OA\Response(
   *         response=200,
   *         description="Successful response",
   *         @OA\JsonContent(
   *             type="string",
   *             example="Hello World!"
   *         )
   *     )
   * )
   */
  public function test()
  {
    echo 'Hello World!';
  }

  /**
   * @OA\Post(
   *     path="/auth/register",
   *     summary="Register an account",
   *     tags={"Auth"},
   *     @OA\Parameter(
   *         name="X-Csrf-Token",
   *         in="header",
   *         required=false,
   *         description="CSRF Token (invalid now)",
   *         @OA\Schema(
   *             type="string",
   *             example="abcd123456"
   *         )
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="name", type="string", description="Full name"),
   *             @OA\Property(property="email", type="string", description="Email address"),
   *             @OA\Property(property="password", type="string", description="Password")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Registration successfully!",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Error when register.",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function register()
  {
    header('Content-Type: application/json');

//    $headers = getallheaders();
//    $csrfToken = $headers['X-CSRF-TOKEN'] ?? '';
//
//    if (!isset($csrfToken) || !Csrf::validateToken($csrfToken)) {
//      die('Invalid request');
//    }

    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];
    $role = 'customer';

    if ($this->userModel->register(null, $name, $email, $password, $role)) {
      http_response_code(200);
      echo json_encode(['message' => 'Registration successfully!']);
    } else {
      http_response_code(401);
      echo json_encode(['message' => 'Error when register.']);
    }
  }

  /**
   * @OA\Post(
   *     path="/auth/login",
   *     summary="Login user",
   *     tags={"Auth"},
   *     @OA\Parameter(
   *         name="X-Csrf-Token",
   *         in="header",
   *         required=false,
   *         description="CSRF Token (invalid now)",
   *         @OA\Schema(
   *             type="string",
   *             example="abcd123456"
   *         )
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="email", type="string", description="Email address"),
   *             @OA\Property(property="password", type="string", description="Password")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Login successfully!",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="token", type="string")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Invalid credentials",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string")
   *         )
   *     )
   * )
   */
  public function login()
  {
    header('Content-Type: application/json');

//    $headers = getallheaders();
//    $csrfToken = $headers['X-Csrf-Token'] ?? '';
//
//    if (!isset($csrfToken) || !Csrf::validateToken($csrfToken)) {
//       http_response_code(401);
//      die('Invalid request ' . $csrfToken);
//    }

    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $user = $this->userModel->getUserByEmail($email);

    if ($user && password_verify($password, $user['password']) && $user['role'] == 'customer') {
      $token = $this->jwtHandler->encode([
        'user_customer' => $user,
      ]);
      echo json_encode(['token' => $token]);
    } else {
      http_response_code(401);
      echo json_encode(['message' => 'Invalid credentials']);
    }
  }

  /**
   * @OA\Get(
   *     path="/auth/get-user-info",
   *     summary="Get customer information",
   *     description="Retrieve the authenticated customer's information using a JWT token.",
   *     tags={"Auth"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Response(
   *         response=200,
   *         description="Customer information retrieved successfully",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="decoded", type="object", description="Decoded customer information", example={
   *                 "id": 123,
   *                 "name": "John Doe",
   *                 "email": "johndoe@example.com"
   *             })
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Invalid token")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Invalid request",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Invalid request")
   *         )
   *     )
   * )
   */
  public function getCustomerInfo()
  {
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
      $authHeader = $headers['Authorization'];
      $arr = explode(" ", $authHeader);
      $jwt = $arr[1] ?? null;

      if ($jwt) {
        $decoded = $this->jwtHandler->decode($jwt);
        echo json_encode(['decoded' => $decoded['user_customer']]);
      } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid token']);
      }
    } else {
      http_response_code(401);
      echo json_encode(['message' => 'Invalid request']);
    }
  }
}
