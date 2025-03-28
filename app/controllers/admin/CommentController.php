<?php
namespace app\controllers\admin;

require_once __DIR__ . '/../../../vendor/autoload.php';

use app\models\Comment;
use app\middleware\AuthMiddleware;

class CommentController
{
  private $commentModel;

  public function __construct()
  {
    $this->commentModel = new Comment();
  }

  public function index()
  {
    AuthMiddleware::checkAdminAuth();
    $comments = $this->commentModel->getAll();
    require_once __DIR__ . '/../../views/admin/comments/index.php';
  }

  public function approve($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->commentModel->updateStatus($id, 'approved')) {
      header('Location: /doan/admin/comments');
    } else {
      echo "Lỗi duyệt bình luận.";
    }
  }

  public function reject($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->commentModel->updateStatus($id, 'rejected')) {
      header('Location: /doan/admin/comments');
    } else {
      echo "Lỗi từ chối bình luận.";
    }
  }

  public function delete($id)
  {
    AuthMiddleware::checkAdminAuth();
    if ($this->commentModel->delete($id)) {
      header('Location: /doan/admin/comments');
    } else {
      echo "Lỗi xóa bình luận.";
    }
  }
}