<?php
namespace app\controllers\api;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Category;

class HomeApiController
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
   *     path="/home",
   *     summary="Get all categories, featured products, and (8) new products",
   *     tags={"Home"},
   *     @OA\Response(
   *         response=200,
   *         description="Success"
   *     )
   * )
   */
  public function index()
  {
    // Fetch categories, featured products, and new products
    $categories = $this->categoryModel->getAll();
    $featuredProducts = $this->productModel->getFeaturedProducts();
    $newProducts = $this->productModel->getNewProducts();

    // Return the data as JSON
    echo json_encode([
      'categories' => $categories,
      'featuredProducts' => $featuredProducts,
      'newProducts' => $newProducts,
    ]);
  }
}