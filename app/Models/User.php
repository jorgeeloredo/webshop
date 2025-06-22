<?php
// app/models/User.php
namespace App\Models;

use App\Core\App;
use App\Core\Database;

class User
{
  private Database $db;

  public function __construct()
  {
    $this->db = App::get('database');
  }

  public function find($id)
  {
    return $this->db->find('users', $id);
  }

  public function findByEmail($email)
  {
    return $this->db->whereFirst('users', 'email', $email);
  }

  public function create($data)
  {
    // Hash password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    // Add created timestamp
    $data['created_at'] = date('Y-m-d H:i:s');

    return $this->db->create('users', $data);
  }

  /**
   * Check if a user has admin privileges
   * 
   * @param int $userId
   * @return bool
   */
  public function isAdmin($userId)
  {
    $user = $this->find($userId);
    return isset($user['role']) && $user['role'] === 'admin';
  }

  public function update($id, $data)
  {
    // Hash password if it's being updated
    if (isset($data['password'])) {
      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    // Add updated timestamp
    $data['updated_at'] = date('Y-m-d H:i:s');

    return $this->db->update('users', $id, $data);
  }

  public function authenticate($email, $password)
  {
    $user = $this->findByEmail($email);

    if (!$user) {
      return false;
    }

    return password_verify($password, $user['password']) ? $user : false;
  }

  public function getOrders($userId)
  {
    $this->db->query("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
    return $this->db->get();
  }
}
