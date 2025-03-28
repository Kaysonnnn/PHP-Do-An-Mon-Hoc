<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Comment;

class ProductController
{
  private $productModel;
  private $commentModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->commentModel = new Comment();
  }

  public function index()
  {
    $search = $_GET['search'] ?? '';
    $page = $_GET['page'] ?? 1;
    $products = $this->productModel->getProducts($search, $page);
    $totalPages = $this->productModel->getTotalPages($search);
    require_once __DIR__ . '/../../views/customer/products/index.php';
  }

  public function show($id)
  {
    $product = $this->productModel->getProductById($id);
    $comments = $this->commentModel->getCommentsByProduct($id);
    $averageRating = $this->commentModel->getAverageRating($id);
    require_once __DIR__ . '/../../views/customer/products/show.php';
  }
}