<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Category;

class CategoryApiController
{
  private $productModel;
  private $categoryModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
  }

  /**
   * @OA\Get(
   *     path="/categories/{categoryId}",
   *     summary="Get category details with products",
   *     description="Retrieve a category by ID and its associated products with pagination and sorting.",
   *     tags={"Category"},
   *     @OA\Parameter(
   *         name="categoryId",
   *         in="path",
   *         required=true,
   *         description="The ID of the category",
   *         @OA\Schema(
   *             type="integer",
   *             example=1
   *         )
   *     ),
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
   *         name="sort",
   *         in="query",
   *         required=false,
   *         description="Field to sort the products by (e.g., 'name', 'price')",
   *         @OA\Schema(
   *             type="string",
   *             example="name"
   *         )
   *     ),
   *     @OA\Parameter(
   *         name="order",
   *         in="query",
   *         required=false,
   *         description="Sorting order (e.g., 'ASC' or 'DESC')",
   *         @OA\Schema(
   *             type="string",
   *             example="ASC"
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Category details with paginated products",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="sort", type="string", example="name"),
   *             @OA\Property(property="order", type="string", example="ASC"),
   *             @OA\Property(
   *                 property="category",
   *                 type="object",
   *                 description="Category details",
   *                 @OA\Property(property="id", type="integer", example=1),
   *                 @OA\Property(property="name", type="string", example="Electronics")
   *             ),
   *             @OA\Property(
   *                 property="products",
   *                 type="array",
   *                 description="List of products in the category",
   *                 @OA\Items(
   *                     type="object",
   *                     @OA\Property(property="id", type="integer", example=101),
   *                     @OA\Property(property="name", type="string", example="Smartphone"),
   *                     @OA\Property(property="price", type="number", format="float", example=699.99),
   *                     @OA\Property(property="quantity", type="integer", example=50)
   *                 )
   *             ),
   *             @OA\Property(
   *                 property="pagination",
   *                 type="object",
   *                 description="Pagination details",
   *                 @OA\Property(property="currentPage", type="integer", example=1),
   *                 @OA\Property(property="totalPages", type="integer", example=5),
   *                 @OA\Property(property="totalProducts", type="integer", example=45)
   *             )
   *         )
   *     ),
   *     @OA\Response(
   *         response=404,
   *         description="Category not found",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="Category not found!")
   *         )
   *     )
   * )
   */
  public function show($categoryId)
  {
    header('Content-Type: application/json');

    $category = $this->categoryModel->getById($categoryId);
    if (!$category) {
      http_response_code(404);
      echo json_encode(['message' => 'Category not found!']);
      return;
    }
    // Xử lý phân trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Xử lý sắp xếp
    $sort = $_GET['sort'] ?? 'name';
    $order = $_GET['order'] ?? 'ASC';

    // Lấy sản phẩm
    $products = $this->productModel->getProductsByCategory($categoryId, $offset, $limit, $sort, $order);
    $totalProducts = $this->productModel->countProductsByCategory($categoryId);
    $totalPages = ceil($totalProducts / $limit);

    http_response_code(200);
    echo json_encode([
      'sort' => $sort,
      'order' => $order,
      'category' => $category,
      'products' => $products,
      'pagination' => [
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'totalProducts' => $totalProducts,
      ],
    ]);
  }
}