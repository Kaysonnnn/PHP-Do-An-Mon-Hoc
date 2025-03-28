<?php
namespace app\controllers\customer;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Category;

class HomeController
{
  private $productModel;
  private $categoryModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
  }

  public function index()
  {
    $categories = $this->categoryModel->getAll();
    $featuredProducts = $this->productModel->getFeaturedProducts();
    $newProducts = $this->productModel->getNewProducts();

    require_once __DIR__ . '/../../views/customer/home/index.php';
  }
}
