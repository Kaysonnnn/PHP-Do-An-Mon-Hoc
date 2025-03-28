<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Product;
use app\models\Category;
use app\middleware\AuthMiddleware;

class ProductController
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
    AuthMiddleware::checkAdminAuth();
    $products = $this->productModel->getAll();
    require_once __DIR__ . '/../../views/admin/products/index.php';
  }

  public function create()
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'stock' => $_POST['stock'],
        'image' => $_FILES['image']['name'] ?? null,
      ];
      if ($_FILES['image']['name']) {
        $uploadDirectory = __DIR__ . '/../../../uploads/';
        if (!is_dir($uploadDirectory)) {
          mkdir($uploadDirectory, 0777, true);
        }
        $targetFile = $uploadDirectory . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
          $data['image'] = $_FILES['image']['name'];
        } else {
          echo "Lỗi: Lỗi upload hình ảnh.";
        }
      }
      if ($this->productModel->create($data)) {
        header('Location: /doan/admin/products');
      } else {
        echo "Lỗi tạo sản phẩm.";
      }
    } else {
      $categories = $this->categoryModel->getAll();
      require_once __DIR__ . '/../../views/admin/products/create.php';
    }
  }

  public function edit($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'category_id' => $_POST['category_id'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'stock' => $_POST['stock'],
        'image' => $_FILES['image']['name'] ?? $_POST['existing_image'],
      ];
      if ($_FILES['image']['name']) {
        $uploadDirectory = __DIR__ . '/../../../uploads/';
        if (!is_dir($uploadDirectory)) {
          mkdir($uploadDirectory, 0777, true);
        }
        $targetFile = $uploadDirectory . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
          $data['image'] = $_FILES['image']['name'];
        } else {
          echo "Lỗi: Lỗi upload hình ảnh.";
        }
      }
      if ($this->productModel->update($id, $data)) {
        header('Location: /doan/admin/products');
      } else {
        echo "Lỗi cập nhật sản phẩm.";
      }
    } else {
      $product = $this->productModel->getById($id);
      $categories = $this->categoryModel->getAll();
      require_once __DIR__ . '/../../views/admin/products/edit.php';
    }
  }

  public function delete($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->productModel->delete($id)) {
      header('Location: /doan/admin/products');
    } else {
      echo "Lỗi xóa sản phẩm.";
    }
  }
}