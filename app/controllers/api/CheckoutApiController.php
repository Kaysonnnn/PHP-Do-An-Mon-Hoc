<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\helpers\JWTHandler;
use app\models\Order;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutApiController
{
  private $orderModel;
  private $jwtHandler;
  private $stripeConfig;

  public function __construct()
  {
    $this->orderModel = new Order();
    $this->jwtHandler = new JWTHandler();
    $config = require __DIR__ . '/../../config/config.php';
    $this->stripeConfig = $config['stripe'];
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
   * @OA\Post(
   *     path="/checkout/cod",
   *     summary="Checkout with Cash on Delivery (COD)",
   *     description="Process a checkout request using Cash on Delivery (COD) payment method.",
   *     tags={"Checkout"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         description="Cart information for checkout",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(
   *                 property="cart",
   *                 type="array",
   *                 description="List of products in the cart",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="id", type="integer", description="Product ID", example=101),
   *                     @OA\Property(property="name", type="string", description="Product name", example="Laptop"),
   *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
   *                     @OA\Property(property="price", type="number", format="float", description="Price per unit", example=599.99)
   *                 )
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Checkout successful",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Checkout successfully with COD!"),
   *             @OA\Property(property="order_id", type="integer", example=12345)
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to comment")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Invalid request",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Add product to cart, please!")
   *         )
   *     )
   * )
   */
  public function storeCod()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $data = json_decode(file_get_contents("php://input"), true);

      $cart = $data['cart'] ?? [];
      if (empty($cart)) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Add product to cart, please!']);
        return;
      }

      $customerId = $userCustomer['user_customer']['id'];
      $totalAmount = array_reduce($cart, function ($sum, $item) {
        return $sum + $item['price'] * $item['quantity'];
      }, 0);

      $orderId = $this->orderModel->createOrder($customerId, $totalAmount, 'cod', 'pending');

      foreach ($cart as $item) {
        $this->orderModel->addOrderItem($orderId, $item['id'], $item['name'], $item['quantity'], $item['price']);
      }

      http_response_code(200);
      echo json_encode([
        'message' => 'Checkout successfully with COD!',
        'order_id' => $orderId,
      ]);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to comment']);
      return;
    }
  }

  /**
   * @OA\Post(
   *     path="/checkout/create-stripe-checkout",
   *     summary="Create checkout with Stripe",
   *     description="Process create a checkout with Stripe info.",
   *     tags={"Checkout"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         description="Cart information for checkout",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(
   *                 property="cart",
   *                 type="array",
   *                 description="List of products in the cart",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="id", type="integer", description="Product ID", example=101),
   *                     @OA\Property(property="name", type="string", description="Product name", example="Laptop"),
   *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
   *                     @OA\Property(property="price", type="number", format="float", description="Price per unit", example=599.99)
   *                 )
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Checkout successful",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Checkout successfully with Stripe!"),
   *             @OA\Property(property="order_id", type="integer", example=12345)
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="publishableKey", type="string", description="Publishable key", example="pk_test_abcd1234"),
   *             @OA\Property(property="clientSecret", type="string", description="Client secret", example="sk_test_abcd1234")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Invalid request",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Error when create checkout info")
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Cart is empty",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Add product to cart, please!")
   *         )
   *     )
   * )
   */
  public function createCheckoutWithStripe()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $data = json_decode(file_get_contents("php://input"), true);

      $cart = $data['cart'] ?? [];
      if (empty($cart)) {
        http_response_code(404);
        echo json_encode(['message' => 'Add product to cart, please!']);
        return;
      }

      Stripe::setApiKey($this->stripeConfig['secret_key']);

      $totalAmount = array_reduce($cart, function ($sum, $item) {
        return $sum + $item['price'] * $item['quantity'];
      }, 0);

      try {
        $paymentIntent = PaymentIntent::create([
          'amount' => $totalAmount * 100, // Stripe expects the amount in cents
          'currency' => 'usd',
        ]);

        $publishableKey = $this->stripeConfig['publishable_key'];
        $clientSecret = $paymentIntent->client_secret;

        http_response_code(200);
        echo json_encode([
          'publishableKey' => $publishableKey,
          'clientSecret' => $clientSecret,
        ]);
      } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(['message' => 'Error when create checkout info: ' . $e->getMessage()]);
      }
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to comment']);
      return;
    }
  }

  /**
   * @OA\Post(
   *     path="/checkout/stripe",
   *     summary="Checkout with Stripe",
   *     description="Process a checkout request using Stripe payment method.",
   *     tags={"Checkout"},
   *     security={{"bearerAuth": {}}},
   *     @OA\RequestBody(
   *         required=true,
   *         description="Cart information for checkout",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(
   *                 property="cart",
   *                 type="array",
   *                 description="List of products in the cart",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="id", type="integer", description="Product ID", example=101),
   *                     @OA\Property(property="name", type="string", description="Product name", example="Laptop"),
   *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product", example=2),
   *                     @OA\Property(property="price", type="number", format="float", description="Price per unit", example=599.99)
   *                 )
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Checkout successful",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Checkout successfully with Stripe!"),
   *             @OA\Property(property="order_id", type="integer", example=12345)
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="You must be logged in to comment")
   *         )
   *     ),
   *     @OA\Response(
   *         response=400,
   *         description="Invalid request",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Add product to cart, please!")
   *         )
   *     )
   * )
   */
  public function storeStripe()
  {
    $userCustomer = $this->authenticate();
    if ($userCustomer != null) {
      header('Content-Type: application/json');
      $data = json_decode(file_get_contents("php://input"), true);

      $cart = $data['cart'] ?? [];
      if (empty($cart)) {
        http_response_code(401); // Unauthorized
        echo json_encode(['message' => 'Add product to cart, please!']);
        return;
      }

      $customerId = $userCustomer['user_customer']['id'];
      $totalAmount = array_reduce($cart, function ($sum, $item) {
        return $sum + $item['price'] * $item['quantity'];
      }, 0);

      $orderId = $this->orderModel->createOrder($customerId, $totalAmount, 'stripe', 'paid');

      // Lưu chi tiết sản phẩm
      foreach ($cart as $item) {
        $this->orderModel->addOrderItem($orderId, $item['id'], $item['name'], $item['quantity'], $item['price']);
      }

      http_response_code(200);
      echo json_encode([
        'message' => 'Checkout successfully with Stripe!',
        'order_id' => $orderId,
      ]);
    } else {
      http_response_code(401); // Unauthorized
      echo json_encode(['message' => 'You must be logged in to comment']);
      return;
    }
  }
}
