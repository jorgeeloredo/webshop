<?php
// app/core/Database.php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
  private static $instance = null;
  private $connection;
  private $statement;

  private function __construct()
  {
    // Get database configuration from config file
    $config = require __DIR__ . '/../config/config.php';
    $dbConfig = $config['database'];

    $host = $dbConfig['host'];
    $db = $dbConfig['name'];
    $user = $dbConfig['user'];
    $password = $dbConfig['password'];
    $charset = 'utf8mb4';

    $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
      $this->connection = new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
      throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function query($sql, $params = [])
  {
    $this->statement = $this->connection->prepare($sql);
    $this->statement->execute($params);
    return $this;
  }

  public function find($table, $id)
  {
    $this->query("SELECT * FROM {$table} WHERE id = ?", [$id]);
    return $this->statement->fetch();
  }

  public function findAll($table)
  {
    $this->query("SELECT * FROM {$table}");
    return $this->statement->fetchAll();
  }

  public function create($table, $data)
  {
    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

    $this->query($sql, array_values($data));

    return $this->connection->lastInsertId();
  }

  public function update($table, $id, $data)
  {
    $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';
    $sql = "UPDATE {$table} SET {$setClause} WHERE id = ?";

    $values = array_values($data);
    $values[] = $id;

    $this->query($sql, $values);

    return $this->statement->rowCount();
  }

  public function delete($table, $id)
  {
    $this->query("DELETE FROM {$table} WHERE id = ?", [$id]);
    return $this->statement->rowCount();
  }

  public function where($table, $column, $value, $operator = '=')
  {
    $sql = "SELECT * FROM {$table} WHERE {$column} {$operator} ?";
    $this->query($sql, [$value]);
    return $this->statement->fetchAll();
  }

  public function whereFirst($table, $column, $value, $operator = '=')
  {
    $sql = "SELECT * FROM {$table} WHERE {$column} {$operator} ? LIMIT 1";
    $this->query($sql, [$value]);
    return $this->statement->fetch();
  }

  public function get()
  {
    return $this->statement->fetchAll();
  }

  public function first()
  {
    return $this->statement->fetch();
  }

  public function count()
  {
    return $this->statement->rowCount();
  }

  public function beginTransaction()
  {
    $this->connection->beginTransaction();
  }

  public function commit()
  {
    $this->connection->commit();
  }

  public function rollback()
  {
    $this->connection->rollBack();
  }
}
