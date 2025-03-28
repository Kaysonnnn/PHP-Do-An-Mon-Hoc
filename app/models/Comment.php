<?php
namespace app\models;

use app\config\Database;

class Comment
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->db->query("
      SELECT comments.*, products.name AS product_name, users.name AS customer_name
      FROM comments
      JOIN products ON comments.product_id = products.id
      JOIN users ON comments.customer_id = users.id
      ORDER BY comments.created_at DESC
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getCommentsByProduct($productId)
  {
    $stmt = $this->db->prepare("
      SELECT c.comment, c.rating, c.created_at, u.name AS customer_name
      FROM comments c
      JOIN users u ON c.customer_id = u.id
      WHERE c.product_id = :product_id AND status = 'approved'
      ORDER BY c.created_at DESC
    ");
    $stmt->execute([':product_id' => $productId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function updateStatus($id, $status)
  {
    $stmt = $this->db->prepare("UPDATE comments SET status = :status WHERE id = :id");
    return $stmt->execute(['status' => $status, 'id' => $id]);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function addComment($productId, $customerId, $comment, $rating)
  {
    $stmt = $this->db->prepare("INSERT INTO comments (product_id, customer_id, comment, rating) VALUES (:product_id, :customer_id, :comment, :rating)");
    $stmt->execute([
      ':product_id' => $productId,
      ':customer_id' => $customerId,
      ':comment' => $comment,
      ':rating' => $rating,
    ]);
  }

  public function getAverageRating($productId)
  {
    $stmt = $this->db->prepare("SELECT AVG(rating) AS average_rating FROM comments WHERE product_id = :product_id");
    $stmt->execute([':product_id' => $productId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC)['average_rating'];
  }
}