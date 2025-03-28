<?php
namespace app\models;

use app\config\Database;

class Order
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->db->query("
      SELECT orders.*, users.name AS customer_name
      FROM orders
      JOIN users ON orders.customer_id = users.id
      ORDER BY orders.created_at DESC
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->db->prepare("
      SELECT orders.*, users.name AS customer_name, users.email, users.phone, users.address
      FROM orders
      JOIN users ON orders.customer_id = users.id
      WHERE orders.id = :id
    ");
    $stmt->execute(['id' => $id]);
    $order = $stmt->fetch(\PDO::FETCH_ASSOC);

    $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = :id");
    $stmt->execute(['id' => $id]);
    $order['items'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $order;
  }

  public function updateStatus($id, $status)
  {
    $stmt = $this->db->prepare("UPDATE orders SET status = :status WHERE id = :id");
    return $stmt->execute(['id' => $id, 'status' => $status]);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM orders WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function getStatistics()
  {
    $stats = [];
    $stats['total_orders'] = $this->db->query("SELECT COUNT(*) AS count FROM orders")->fetch(\PDO::FETCH_ASSOC)['count'];
    $stats['pending_orders'] = $this->db->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'pending'")->fetch(\PDO::FETCH_ASSOC)['count'];
    $stats['completed_orders'] = $this->db->query("SELECT COUNT(*) AS count FROM orders WHERE status = 'completed'")->fetch(\PDO::FETCH_ASSOC)['count'];
    $stats['total_revenue'] = $this->db->query("SELECT SUM(total_amount) AS revenue FROM orders WHERE status = 'completed'")->fetch(\PDO::FETCH_ASSOC)['revenue'] ?? 0;
    return $stats;
  }

  public function createOrder($customerId, $totalAmount, $paymentMethod, $paymentStatus = 'pending')
  {
    $stmt = $this->db->prepare("
      INSERT INTO orders (customer_id, total_amount, payment_method, payment_status)
      VALUES (:customer_id, :total_amount, :payment_method, :payment_status)
    ");
    $stmt->execute([
      ':customer_id' => $customerId,
      ':total_amount' => $totalAmount,
      ':payment_method' => $paymentMethod,
      ':payment_status' => $paymentStatus,
    ]);
    return $this->db->lastInsertId();
  }

  public function updatePaymentStatus($orderId, $status)
  {
    $stmt = $this->db->prepare("UPDATE orders SET payment_status = :status WHERE id = :order_id");
    return $stmt->execute([
      ':status' => $status,
      ':order_id' => $orderId,
    ]);
  }

  public function addOrderItem($orderId, $productId, $productName, $quantity, $price)
  {
    $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (:order_id, :product_id, :product_name, :quantity, :price)");
    $stmt->execute([
      ':order_id' => $orderId,
      ':product_id' => $productId,
      ':product_name' => $productName,
      ':quantity' => $quantity,
      ':price' => $price,
    ]);
  }

  public function getOrdersByUser($customerId)
  {
    $stmt = $this->db->prepare("SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY created_at DESC");
    $stmt->execute([':customer_id' => $customerId]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getOrderDetails($orderId, $customerId)
  {
    $stmt = $this->db->prepare("
      SELECT o.*, oi.*, p.image
      FROM orders o
      JOIN order_items oi ON o.id = oi.order_id
      JOIN products p on oi.product_id = p.id
      WHERE o.id = :order_id AND o.customer_id = :customer_id
    ");
    $stmt->execute([
      ':order_id' => $orderId,
      ':customer_id' => $customerId,
    ]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getOrder($orderId, $customerId)
  {
    $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = :order_id AND customer_id = :customer_id");
    $stmt->execute([
      ':order_id' => $orderId,
      ':customer_id' => $customerId,
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}
