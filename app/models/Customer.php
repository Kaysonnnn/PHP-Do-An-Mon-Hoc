<?php
namespace app\models;

use app\config\Database;

class Customer
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->db->query("SELECT * FROM customers ORDER BY created_at DESC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $stmt = $this->db->prepare("INSERT INTO customers (name, email, phone, address) VALUES (:name, :email, :phone, :address)");
    return $stmt->execute($data);
  }

  public function update($id, $data)
  {
    $stmt = $this->db->prepare("UPDATE customers SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
    $data["id"] = $id;
    return $stmt->execute($data);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM customers WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function getStatistics()
  {
    $stats = [];
    $stats['total_customers'] = $this->db->query("SELECT COUNT(*) AS count FROM customers")->fetch(\PDO::FETCH_ASSOC)['count'];

    $stmt = $this->db->query("
      SELECT customers.name, COUNT(orders.id) AS total_orders
      FROM orders
      JOIN customers ON orders.customer_id = customers.id
      GROUP BY orders.customer_id
      ORDER BY total_orders DESC
      LIMIT 1
    ");
    $topCustomer = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($topCustomer) {
      $stats['top_customer'] = $topCustomer;
    } else {
      $stats['top_customer'] = [
        'name' => 'No Data',
        'total_orders' => 0,
      ];
    }

    return $stats;
  }
}