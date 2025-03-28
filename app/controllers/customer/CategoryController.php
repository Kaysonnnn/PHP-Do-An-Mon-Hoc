<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Category;

class CategoryController
{
  private $productModel;
  private $categoryModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
  }

  // Hiển thị sản phẩm theo danh mục
  public function show($categoryId)
  {
    $category = $this->categoryModel->getById($categoryId);
    if (!$category) {
      echo "Không tìm thấy danh mục này!";
      exit();
    }

    // Xử lý phân trang
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Xử lý sắp xếp
    $sort = $_GET['sort'] ?? 'name';
    $order = $_GET['order'] ?? 'ASC';

    // Lấy sản phẩm
    $products = $this->productModel->getProductsByCategory($categoryId, $offset, $limit, $sort, $order);
    $totalProducts = $this->productModel->countProductsByCategory($categoryId);
    $totalPages = ceil($totalProducts / $limit);

    require_once __DIR__ . '/../../views/customer/categories/show.php';
  }
}