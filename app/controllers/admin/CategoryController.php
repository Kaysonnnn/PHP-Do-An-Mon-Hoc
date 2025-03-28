<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Category;
use app\middleware\AuthMiddleware;

class CategoryController
{
  private $categoryModel;

  public function __construct()
  {
    $this->categoryModel = new Category();
  }

  public function index()
  {
    AuthMiddleware::checkAdminAuth();
    $categories = $this->categoryModel->getAll();
    require_once __DIR__ . '/../../views/admin/categories/index.php';
  }

  public function create()
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
      ];
      if ($this->categoryModel->create($data)) {
        header('Location: /doan/admin/categories');
      } else {
        echo "Lỗi tạo danh mục.";
      }
    } else {
      require_once __DIR__ . '/../../views/admin/categories/create.php';
    }
  }

  public function edit($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
      ];
      if ($this->categoryModel->update($id, $data)) {
        header('Location: /doan/admin/categories');
      } else {
        echo "Lỗi cập nhật danh mục.";
      }
    } else {
      $category = $this->categoryModel->getById($id);
      require_once __DIR__ . '/../../views/admin/categories/edit.php';
    }
  }

  public function delete($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->categoryModel->delete($id)) {
      header('Location: /doan/admin/categories');
    } else {
      echo "Lỗi xóa danh mục.";
    }
  }
}