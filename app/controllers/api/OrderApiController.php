<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\helpers\JWTHandler;
use app\models\Order;

class OrderApiController
{
  private $orderModel;
  private $jwtHandler;

  public function __construct()
  {
    $this->orderModel = new Order();
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
   *     path="/orders",
   *     summary="Get all orders of customer",
   *     tags={"Order"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Response(
   *         response=200,
   *         description="Success"
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to see your orders")
   *         )
   *     )
   * )
   */
  public function getOrders()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      $customerId = $userCustomer['user_customer']['id'];

      $orders = $this->orderModel->getOrdersByUser($customerId);

      http_response_code(200);
      echo json_encode(['orders' => $orders]);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to see your orders']);
      return;
    }
  }

  /**
   * @OA\Get(
   *     path="/orders/{orderId}",
   *     summary="Get order's details of customer",
   *     tags={"Order"},
   *     security={{"bearerAuth": {}}},
   *     @OA\Parameter(
   *         name="orderId",
   *         in="path",
   *         required=true,
   *         description="Order ID",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Success"
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Not found",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Order not found")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to see your orders")
   *         )
   *     )
   * )
   */
  public function getOrderDetail($orderId)
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      $customerId = $userCustomer['user_customer']['id'];

      $order = $this->orderModel->getOrder($orderId, $customerId);
      if ($order) {
        $orderDetails = $this->orderModel->getOrderDetails($orderId, $customerId);

        http_response_code(200);
        echo json_encode([
          'order' => $order,
          'order_details' => $orderDetails,
        ]);
      } else {
        http_response_code(404);
        echo json_encode(['message' => 'Order not found']);
      }
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to see your orders']);
      return;
    }
  }
}
