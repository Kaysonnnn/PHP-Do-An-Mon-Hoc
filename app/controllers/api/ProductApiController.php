<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Comment;

class ProductApiController
{
  private $productModel;
  private $commentModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->commentModel = new Comment();
  }

  /**
   * @OA\Get(
   *     path="/products",
   *     summary="Get products by page number",
   *     description="Retrieve products, its associated products with search and pagination.",
   *     tags={"Product"},
   *     @OA\Parameter(
   *         name="page",
   *         in="query",
   *         required=false,
   *         description="The page number for pagination",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
   *     @OA\Parameter(
   *         name="search",
   *         in="query",
   *         required=false,
   *         description="Search the products by (e.g., 'Coca')",
   *         @OA\Schema(
   *             type="string",
   *             example="Coca"
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Success",
   *     )
   * )
   */
  public function index()
  {
    // Get search term and page from query parameters
    $search = $_GET['search'] ?? '';
    $page = $_GET['page'] ?? 1;

    // Fetch products and total pages
    $products = $this->productModel->getProducts($search, $page);
    $totalPages = $this->productModel->getTotalPages($search);

    // Return products and pagination info as JSON
    echo json_encode([
      'products' => $products,
      'totalPages' => $totalPages,
    ]);
  }

  /**
   * @OA\Get(
   *     path="/products/{id}",
   *     summary="Get product's details",
   *     tags={"Product"},
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         description="Product ID",
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
   *             @OA\Property(property="error", type="string", example="Product not found")
   *         )
   *     )
   * )
   */
  public function show($id)
  {
    // Fetch product details by ID
    $product = $this->productModel->getProductById($id);

    // If product not found, return 404
    if (!$product) {
      http_response_code(404);
      echo json_encode(['error' => 'Product not found']);
      return;
    }

    // Fetch associated comments and average rating
    $comments = $this->commentModel->getCommentsByProduct($id);
    $averageRating = $this->commentModel->getAverageRating($id);

    // Return product details, comments, and rating as JSON
    echo json_encode([
      'product' => $product,
      'comments' => $comments,
      'averageRating' => $averageRating,
    ]);
  }
}