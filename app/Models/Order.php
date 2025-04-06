<?php
// app/models/Order.php
namespace App\Models;

use App\Core\App;
use App\Core\Database;

class Order
{
  private Database $db;

  private $statusLabels = [
    'pending' => 'En attente',
    'processing' => 'En cours de traitement',
    'shipped' => 'Expédié',
    'delivered' => 'Livré',
    'cancelled' => 'Annulé'
  ];

  public function __construct()
  {
    $this->db = App::get('database');
  }

  public function find($id)
  {
    $order = $this->db->find('orders', $id);

    if ($order) {
      // Get order items
      $this->db->query(
        "SELECT * FROM order_items WHERE order_id = ?",
        [$id]
      );
      $order['items'] = $this->db->get();
    }

    return $order;
  }

  public function findByUser($userId)
  {
    $this->db->query(
      "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC",
      [$userId]
    );

    return $this->db->get();
  }

  public function create($data, $items)
  {
    $this->db->beginTransaction();

    try {
      // Add created timestamp
      $data['created_at'] = date('Y-m-d H:i:s');

      // Set initial status if not set
      if (!isset($data['status'])) {
        $data['status'] = 'pending';
      }

      // Insert order
      $orderId = $this->db->create('orders', $data);

      // Insert order items
      foreach ($items as $item) {
        $this->db->create('order_items', [
          'order_id' => $orderId,
          'product_id' => $item['id'],
          'name' => $item['name'],
          'price' => $item['price'],
          'quantity' => $item['quantity'],
          'attributes' => json_encode($item['attributes'] ?? []),
        ]);
      }

      $this->db->commit();
      return $orderId;
    } catch (\Exception $e) {
      $this->db->rollback();
      throw $e;
    }
  }

  public function updateStatus($id, $status)
  {
    return $this->db->update('orders', $id, ['status' => $status]);
  }

  public function getStatusLabel($status)
  {
    return $this->statusLabels[$status] ?? $status;
  }

  public function getAllStatuses()
  {
    return $this->statusLabels;
  }
}
