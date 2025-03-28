<?php
namespace app\models;

use app\config\Database;

class User
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function register($username, $name, $email, $password, $role)
  {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $this->db->prepare("INSERT INTO users (username, name, email, password, role) VALUES (:username, :name, :email, :password, :role)");
    return $stmt->execute([
      'username' => $username,
      'name' => $name,
      'email' => $email,
      'password' => $hashedPassword,
      'role' => $role
    ]);
  }

  public function login($username, $password)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin'");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
      return $user;
    }
    return false;
  }

  public function getAllCustomers()
  {
    $stmt = $this->db->query("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getCustomerById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function getUserByEmail($email)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  // Tìm người dùng bằng Google ID
  public function findByGoogleId($googleId)
  {
    $stmt = $this->db->prepare("SELECT * FROM users WHERE google_id = :google_id");
    $stmt->execute([':google_id' => $googleId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  // Tạo người dùng mới với Google ID
  public function createGoogleUser($googleId, $email, $name)
  {
    $stmt = $this->db->prepare("
      INSERT INTO users (google_id, email, name, role)
      VALUES (:google_id, :email, :name, 'customer')
    ");
    $stmt->execute([
      ':google_id' => $googleId,
      ':email' => $email,
      ':name' => $name,
    ]);
  }

  public function updateCustomer($id, $data)
  {
    $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
    $data["id"] = $id;
    return $stmt->execute($data);
  }

  public function updatePassword($customerId, $password)
  {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
    return $stmt->execute([
      ':password' => $hashedPassword,
      ':id' => $customerId,
    ]);
  }

  public function deleteCustomer($id)
  {
    $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function getStatistics()
  {
    $stats = [];
    $stats['total_customers'] = $this->db->query("SELECT COUNT(*) AS count FROM users WHERE role = 'customer'")->fetch(\PDO::FETCH_ASSOC)['count'];

    $stmt = $this->db->query("
      SELECT users.name, COUNT(orders.id) AS total_orders
      FROM orders
      JOIN users ON orders.customer_id = users.id
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
