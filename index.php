<?php
require_once __DIR__ . '/vendor/autoload.php';

// Admin
use app\controllers\admin\AuthController as AdminAuthController;
use app\controllers\admin\CategoryController as AdminCategoryController;
use app\controllers\admin\CommentController as AdminCommentController;
use app\controllers\admin\CustomerController as AdminCustomerController;
use app\controllers\admin\DashboardController as AdminDashboardController;
use app\controllers\admin\OrderController as AdminOrderController;
use app\controllers\admin\ProductController as AdminProductController;

// Customer
use app\controllers\customer\HomeController as CustomerHomeController;
use app\controllers\customer\AuthController as CustomerAuthController;
use app\controllers\customer\GoogleAuthController as CustomerGoogleAuthController;
use app\controllers\customer\ProductController as CustomerProductController;
use app\controllers\customer\CategoryController as CustomerCategoryController;
use app\controllers\customer\CommentController as CustomerCommentController;
use app\controllers\customer\CartController as CustomerCartController;
use app\controllers\customer\CheckoutController as CustomerCheckoutController;
use app\controllers\customer\ProfileController as CustomerProfileController;

// API
use app\routes\ApiRouter;
use app\controllers\SwaggerController;
use app\controllers\api\AuthApiController;
use app\controllers\api\GoogleAuthApiController;
use app\controllers\api\ProductApiController;
use app\controllers\api\CategoryApiController;
use app\controllers\api\CommentApiController;
use app\controllers\api\HomeApiController;
use app\controllers\api\CheckoutApiController;
use app\controllers\api\OrderApiController;
use app\controllers\api\SecureApiController;
use app\controllers\api\ProfileApiController;

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Admin
$adminAuthController = new AdminAuthController();
$adminDashboardController = new AdminDashboardController();
$adminCustomerController = new AdminCustomerController();
$adminOrderController = new AdminOrderController();
$adminCategoryController = new AdminCategoryController();
$adminProductController = new AdminProductController();
$adminCommentController = new AdminCommentController();
// Customer
$customerHomeController = new CustomerHomeController();
$customerAuthController = new CustomerAuthController();
$customerGoogleAuthController = new CustomerGoogleAuthController();
$customerProductController = new CustomerProductController();
$customerCategoryController = new CustomerCategoryController();
$customerCommentController = new CustomerCommentController();
$customerCartController = new CustomerCartController();
$customerCheckoutController = new CustomerCheckoutController();
$customerProfileController = new CustomerProfileController();

$baseApiUrl = "/doan/api";
$baseAdminUrl = "/doan/admin";
$baseCustomerUrl = "/doan/customer";
$baseCustomerUrlReg = "\/doan\/customer";

############################## API ##############################

if (str_contains($uri, $baseApiUrl)) {
//  $allowedOrigins = [
//    'jigra://localhost',
//    'family://localhost',
//    'http://localhost',
//    'https://localhost',
//    'http://localhost:8100',
//    'https://localhost:8100',
//    'https://app.php.khanhduy1407.live',
//  ];
//
////  if (isset($_SERVER['HTTP_ORIGIN'])) {
//  if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
//    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//    header('Access-Control-Allow-Credentials: true');
//    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
//    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Cookie');
//  }
//
//  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
//    http_response_code(200);
//    exit;
//  }

  $apiRouter = new ApiRouter();
  $method = $_SERVER['REQUEST_METHOD'];

  $apiRouter->get("$baseApiUrl/docs", [SwaggerController::class, 'docs']);

  // Auth
  $apiRouter->get("$baseApiUrl/auth/test", [AuthApiController::class, 'test']);
  $apiRouter->post("$baseApiUrl/auth/login", [AuthApiController::class, 'login']);
  $apiRouter->post("$baseApiUrl/auth/register", [AuthApiController::class, 'register']);
  $apiRouter->get("$baseApiUrl/auth/get-user-info", [AuthApiController::class, 'getCustomerInfo']);
  $apiRouter->get("$baseApiUrl/auth/google-auth-url", [GoogleAuthApiController::class, 'getGoogleAuthUrl']);
  $apiRouter->get("$baseApiUrl/auth/google-callback", [GoogleAuthApiController::class, 'handleGoogleCallback']);

  // Product
  $apiRouter->get("$baseApiUrl/products", [ProductApiController::class, 'index']);
  $apiRouter->get("$baseApiUrl/products/{id}", [ProductApiController::class, 'show']);

  // Category
  $apiRouter->get("$baseApiUrl/categories/{categoryId}", [CategoryApiController::class, 'show']);

  // Comment
  $apiRouter->post("$baseApiUrl/comments/{productId}", [CommentApiController::class, 'store']);
  $apiRouter->get("$baseApiUrl/comments/{productId}", [CommentApiController::class, 'index']);
  $apiRouter->get("$baseApiUrl/comments/{productId}/averageRating", [CommentApiController::class, 'getAverageRating']);

  // Home
  $apiRouter->get("$baseApiUrl/home", [HomeApiController::class, 'index']);

  // Checkout
  $apiRouter->post("$baseApiUrl/checkout/cod", [CheckoutApiController::class, 'storeCod']);
  $apiRouter->post("$baseApiUrl/checkout/create-stripe-checkout", [CheckoutApiController::class, 'createCheckoutWithStripe']);
  $apiRouter->post("$baseApiUrl/checkout/stripe", [CheckoutApiController::class, 'storeStripe']);

  // Order
  $apiRouter->get("$baseApiUrl/orders", [OrderApiController::class, 'getOrders']);
  $apiRouter->get("$baseApiUrl/orders/{orderId}", [OrderApiController::class, 'getOrderDetail']);

  // Secure
  $apiRouter->get("$baseApiUrl/secure/csrf", [SecureApiController::class, 'getCsrfToken']);

  // Profile API
  $apiRouter->get("$baseApiUrl/profile", [ProfileApiController::class, 'getProfile']);
  $apiRouter->post("$baseApiUrl/profile/update", [ProfileApiController::class, 'updateProfile']);
  $apiRouter->post("$baseApiUrl/profile/update-password", [ProfileApiController::class, 'updatePassword']);

  $apiRouter->resolve($uri, $method);
}


############################## ADMIN ##############################

// Auth
elseif ($uri === "$baseAdminUrl/register") $adminAuthController->register();
elseif ($uri === "$baseAdminUrl/login") $adminAuthController->login();
elseif ($uri === "$baseAdminUrl/logout") $adminAuthController->logout();

// Dashboard
elseif ($uri === "$baseAdminUrl/" || $uri === "$baseAdminUrl/dashboard") $adminDashboardController->index();
elseif ($uri === "$baseAdminUrl/dashboard2") $adminDashboardController->index2();

// Customer
elseif ($uri === "$baseAdminUrl/customers") $adminCustomerController->index();
elseif ($uri === "$baseAdminUrl/customers/edit") $adminCustomerController->edit($_GET['id']);
elseif ($uri === "$baseAdminUrl/customers/delete") $adminCustomerController->delete($_GET['id']);

// Order
elseif ($uri === "$baseAdminUrl/orders") $adminOrderController->index();
elseif ($uri === "$baseAdminUrl/orders/view") $adminOrderController->view($_GET['id']);
elseif ($uri === "$baseAdminUrl/orders/update_status") $adminOrderController->updateStatus($_GET['id']);
elseif ($uri === "$baseAdminUrl/orders/update_payment_status") $adminOrderController->updatePaymentStatus($_GET['id']);
elseif ($uri === "$baseAdminUrl/orders/delete") $adminOrderController->delete($_GET['id']);

// Category
elseif ($uri === "$baseAdminUrl/categories") $adminCategoryController->index();
elseif ($uri === "$baseAdminUrl/categories/create") $adminCategoryController->create();
elseif ($uri === "$baseAdminUrl/categories/edit") $adminCategoryController->edit($_GET['id']);
elseif ($uri === "$baseAdminUrl/categories/delete") $adminCategoryController->delete($_GET['id']);

// Product
elseif ($uri === "$baseAdminUrl/products") $adminProductController->index();
elseif ($uri === "$baseAdminUrl/products/create") $adminProductController->create();
elseif ($uri === "$baseAdminUrl/products/edit") $adminProductController->edit($_GET['id']);
elseif ($uri === "$baseAdminUrl/products/delete") $adminProductController->delete($_GET['id']);

// Comment
elseif ($uri === "$baseAdminUrl/comments") $adminCommentController->index();
elseif ($uri === "$baseAdminUrl/comments/approve") $adminCommentController->approve($_GET['id']);
elseif ($uri === "$baseAdminUrl/comments/reject") $adminCommentController->reject($_GET['id']);
elseif ($uri === "$baseAdminUrl/comments/delete") $adminCommentController->delete($_GET['id']);


############################## CUSTOMER ##############################

// Home
elseif ($uri === "$baseCustomerUrl/" || $uri === "$baseCustomerUrl/home") $customerHomeController->index();

// Auth
elseif ($uri === "$baseCustomerUrl/register") {
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customerAuthController->showRegister();
  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerAuthController->register();
  }
}
elseif ($uri === "$baseCustomerUrl/login") {
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customerAuthController->showLogin();
  } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerAuthController->login();
  }
}
elseif ($uri === "$baseCustomerUrl/logout") $customerAuthController->logout();
elseif ($uri === "$baseCustomerUrl/google-login") $customerGoogleAuthController->redirectToGoogle();
elseif ($uri === "$baseCustomerUrl/google-callback") $customerGoogleAuthController->handleGoogleCallback();

// Product
elseif ($uri === "$baseCustomerUrl/products") $customerProductController->index();
elseif (preg_match("/^$baseCustomerUrlReg\/products\/(\d+)$/", $uri, $matches)) $customerProductController->show($matches[1]);

// Category
elseif (preg_match("/^$baseCustomerUrlReg\/category\/(\d+)$/", $uri, $matches)) {
  $categoryId = (int)$matches[1];
  $customerCategoryController->show($categoryId);
}

// Comment
elseif (preg_match("/^$baseCustomerUrlReg\/comments\/add\/(\d+)$/", $uri, $matches)) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerCommentController->store($matches[1]);
  }
}

// Cart
elseif ($uri === "$baseCustomerUrl/cart") $customerCartController->index();
elseif (preg_match("/^$baseCustomerUrlReg\/cart\/add\/(\d+)$/", $uri, $matches)) $customerCartController->add($matches[1]);
elseif ($uri === "$baseCustomerUrl/cart/update" && $_SERVER['REQUEST_METHOD'] === 'POST') $customerCartController->update();
elseif (preg_match("/^$baseCustomerUrlReg\/cart\/remove\/(\d+)$/", $uri, $matches)) $customerCartController->remove($matches[1]);
elseif ($uri === "$baseCustomerUrl/cart/clear") $customerCartController->clear();

// Checkout
elseif ($uri === "$baseCustomerUrl/checkout") $customerCheckoutController->index();
elseif ($uri === "$baseCustomerUrl/checkout/store" && $_SERVER['REQUEST_METHOD'] === 'POST') $customerCheckoutController->store();
elseif ($uri === "$baseCustomerUrl/checkout/stripe") $customerCheckoutController->storeStripe();
elseif ($uri === "$baseCustomerUrl/checkout/confirm-stripe") $customerCheckoutController->confirmStripe();
elseif ($uri === "$baseCustomerUrl/orders") $customerCheckoutController->orders();
elseif (preg_match("/^$baseCustomerUrlReg\/orders\/(\d+)$/", $uri, $matches)) {
  $orderId = (int)$matches[1];
  $customerCheckoutController->details($orderId);
}

// Profile
elseif ($uri === "$baseCustomerUrl/profile" && $_SERVER['REQUEST_METHOD'] === 'GET') $customerProfileController->showProfile();
elseif ($uri === "$baseCustomerUrl/profile/update" && $_SERVER['REQUEST_METHOD'] === 'POST') $customerProfileController->updateProfile();
elseif ($uri === "$baseCustomerUrl/profile/password" && $_SERVER['REQUEST_METHOD'] === 'POST') $customerProfileController->updatePassword();


############################## ERROR PAGE ##############################

else echo "404 Not Found";
