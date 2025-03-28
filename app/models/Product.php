<?php
namespace app\models;

use app\config\Database;

class Product
{
  private $db;

  public function __construct()
  {
    $this->db = Database::getInstance()->getConnection();
  }

  public function getAll()
  {
    $stmt = $this->db->query("
      SELECT products.*, categories.name AS category_name
      FROM products
      JOIN categories ON products.category_id = categories.id
      ORDER BY products.created_at DESC
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create($data)
  {
    $stmt = $this->db->prepare("
      INSERT INTO products (category_id, name, description, price, stock, image)
      VALUES (:category_id, :name, :description, :price, :stock, :image)
    ");
    return $stmt->execute($data);
  }

  public function update($id, $data)
  {
    $stmt = $this->db->prepare("
      UPDATE products
      SET category_id = :category_id, name = :name, description = :description, price = :price, stock = :stock, image = :image
      WHERE id = :id
    ");
    $data['id'] = $id;
    return $stmt->execute($data);
  }

  public function delete($id)
  {
    $stmt = $this->db->prepare("DELETE FROM  products WHERE id = :id");
    return $stmt->execute(['id' => $id]);
  }

  public function getStatistics()
  {
    $stats = [];
    $stats['total_products'] = $this->db->query("SELECT COUNT(*) AS count FROM products")->fetch(\PDO::FETCH_ASSOC)['count'];
    $stats['low_stock_products'] = $this->db->query("SELECT COUNT(*)AS count FROM products WHERE stock < 10")->fetch(\PDO::FETCH_ASSOC)['count'];

    $stmt = $this->db->query("
      SELECT products.name, SUM(order_items.quantity) AS total_sold
      FROM order_items
      JOIN products ON order_items.product_id = products.id
      GROUP BY order_items.product_id
      ORDER BY total_sold DESC
      LIMIT 1
    ");
    $topSellingProduct = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($topSellingProduct) {
      $stats['top_selling_product'] = $topSellingProduct;
    } else {
      $stats['top_selling_product'] = [
        'name' => 'No Data',
        'total_sold' => 0,
      ];
    }

    return $stats;
  }

  public function getFeaturedProducts()
  {
    $stmt = $this->db->query("
      SELECT *
      FROM products
      WHERE is_featured = 1
      ORDER BY created_at DESC
      LIMIT 8
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getNewProducts()
  {
    $stmt = $this->db->query("
      SELECT *
      FROM products
      ORDER BY created_at DESC
      LIMIT 8
    ");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getProducts($search = '', $page = 1, $limit = 10)
  {
    $offset = ($page - 1) * $limit;
    $stmt = $this->db->prepare("SELECT * FROM products WHERE name LIKE :search LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':search', '%' . $search . '%', \PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function getTotalPages($search = '', $limit = 10)
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM products WHERE name LIKE :search");
    $stmt->bindValue(':search', '%' . $search . '%', \PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetch(\PDO::FETCH_ASSOC)['count'];
    return ceil($count / $limit);
  }

  public function getProductById($id)
  {
    $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function getProductsByCategory($categoryId, $offset = 0, $limit = 10, $sort = 'name', $order = 'ASC')
  {
    $stmt = $this->db->prepare("
      SELECT *
      FROM products
      WHERE category_id = :category_id
      ORDER BY $sort $order
      LIMIT :offset, :limit
    ");
    $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function countProductsByCategory($categoryId)
  {
    $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id = :category_id");
    $stmt->execute([':category_id' => $categoryId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC)['total'];
  }
}