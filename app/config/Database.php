<?php
namespace app\config;

class Database
{
  private static $instance = null;
  private $pdo;

  public function __construct()
  {
    $config = include 'config.php';
    $db = $config['db'];
    $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
    $this->pdo = new \PDO($dsn, $db['username'], $db['password']);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new Database();
    }
    return self::$instance;
  }

  public function getConnection()
  {
    return $this->pdo;
  }
}
