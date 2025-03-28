<?php
namespace app\models;

use app\config\Database;

class Category
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->db->query("SELECT * FROM categories ORDER BY created_at DESC");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
    return $stmt->execute($data);
  }

  public function update($id, $data)
  {
    $stmt = $this->db->prepare("UPDATE categories SET name = :name, description = :description WHERE id = :id");
    $data['id'] = $id;
    return $stmt->execute($data);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }
}
