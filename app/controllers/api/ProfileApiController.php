<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\helpers\JWTHandler;
use app\models\User;

class ProfileApiController
{
  private $userModel;
  private $jwtHandler;

  public function __construct()
  {
    $this->userModel = new User();
    $this->jwtHandler = new JWTHandler();
  }

  private function authenticate(): ?array
  {
    $headers = apache_request_headers();

    if (isset($headers['Authorization'])) {
      $authHeader = $headers['Authorization'];
      $arr = explode(" ", $authHeader);
      $jwt = $arr[1] ?? null;

      if ($jwt) {
        $decoded = $this->jwtHandler->decode($jwt);
        return json_decode(json_encode($decoded), true);
      }
    }
    return null;
  }

  /**
   * @OA\Get(
   *     path="/profile",
   *     summary="Get personal profile",
   *     tags={"Profile"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Response(
   *         response=200,
   *         description="Success",
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Customer not found",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="error", type="string", example="Customer not found")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to continue!")
   *         )
   *     )
   * )
   */
  public function getProfile()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      $customerId = $userCustomer['user_customer']['id'];
      $user = $this->userModel->getCustomerById($customerId);

      if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'Customer not found']);
        exit();
      }

      echo json_encode(['info' => $user]);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to continue!']);
      return;
    }
  }

  /**
   * @OA\Post(
   *     path="/profile/update",
   *     summary="Update personal profile",
   *     tags={"Profile"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="name", type="string", description="Full name"),
   *             @OA\Property(property="email", type="string", description="Email address"),
   *             @OA\Property(property="phone", type="string", description="Phone number"),
   *             @OA\Property(property="address", type="string", description="Address")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Success",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Profile updated successfully")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to continue!")
   *         )
   *     )
   * )
   */
  public function updateProfile()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $customerId = $userCustomer['user_customer']['id'];

      $input = json_decode(file_get_contents('php://input'), true);
      $data = [
        'name' => $input['name'] ?? null,
        'email' => $input['email'] ?? null,
        'phone' => $input['phone'] ?? null,
        'address' => $input['address'] ?? null,
      ];

      $this->userModel->updateCustomer($customerId, $data);

      echo json_encode(['message' => 'Profile updated successfully']);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to continue!']);
      return;
    }
  }

  /**
   * @OA\Post(
   *     path="/profile/update-password",
   *     summary="Update customer's password",
   *     tags={"Profile"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="current_password", type="string", description="Current password"),
   *             @OA\Property(property="new_password", type="string", description="New password")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Success",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Password updated successfully")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Error",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="error", type="string", example="Current password is incorrect")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to continue!")
   *         )
   *     )
   * )
   */
  public function updatePassword()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $customerId = $userCustomer['user_customer']['id'];

      $input = json_decode(file_get_contents('php://input'), true);
      $currentPassword = $input['current_password'] ?? null;
      $newPassword = $input['new_password'] ?? null;

      $user = $this->userModel->getCustomerById($customerId);

      if (!$user || !password_verify($currentPassword, $user['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Current password is incorrect']);
        exit();
      }

      $this->userModel->updatePassword($customerId, $newPassword);

      echo json_encode(['message' => 'Password updated successfully']);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to continue!']);
      return;
    }
  }
}
