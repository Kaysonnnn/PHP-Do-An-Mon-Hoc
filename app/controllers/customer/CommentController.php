<?php
namespace app\controllers\customer;

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

  public function store($productId)
  {
    AuthMiddleware::checkCustomerAuth();
    $customerId = $_SESSION['user_customer']['id'];
    $comment = $_POST['comment'] ?? '';
    $rating = $_POST['rating'] ?? 5;

    if (!empty($comment)) {
      $this->commentModel->addComment($productId, $customerId, $comment, $rating);
      header("Location: /doan/customer/products/$productId");
      exit();
    } else {
      echo "Nội dung bình luận đánh giá không được để trống";
    }
  }

  public function index($productId)
  {
    return $this->commentModel->getCommentsByProduct($productId);
  }

  public function getAverageRating($productId)
  {
    return $this->commentModel->getAverageRating($productId);
  }
}