<?php
// app/models/Cart.php
namespace App\Models;

class Cart
{
  private $items = [];
  private $totalPrice = 0;
  private $totalQuantity = 0;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $this->items = $_SESSION['cart'] ?? [];
    $this->calculateTotals();
  }

  public function getItems()
  {
    return $this->items;
  }

  public function getTotalPrice()
  {
    return $this->totalPrice;
  }

  public function getTotalQuantity()
  {
    return $this->totalQuantity;
  }

  public function add($productId, $quantity = 1, $attributes = [])
  {
    $productModel = new Product();
    $product = $productModel->find($productId);

    if (!$product) {
      return false;
    }

    // Create a unique item ID that includes selected attributes
    $itemId = $this->generateItemId($productId, $attributes);

    // If item exists, increase quantity
    if (isset($this->items[$itemId])) {
      $this->items[$itemId]['quantity'] += $quantity;
    } else {
      // Otherwise add new item
      $this->items[$itemId] = [
        'id' => $productId,
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'attributes' => $attributes,
        'image' => $product['image'] ?? null,
        'slug' => $product['slug'] ?? null,
      ];
    }

    $this->save();
    $this->calculateTotals();

    return true;
  }

  public function update($itemId, $quantity)
  {
    if (!isset($this->items[$itemId])) {
      return false;
    }

    if ($quantity <= 0) {
      return $this->remove($itemId);
    }

    $this->items[$itemId]['quantity'] = $quantity;
    $this->save();
    $this->calculateTotals();

    return true;
  }

  public function remove($itemId)
  {
    if (!isset($this->items[$itemId])) {
      return false;
    }

    unset($this->items[$itemId]);
    $this->save();
    $this->calculateTotals();

    return true;
  }

  public function clear()
  {
    $this->items = [];
    $this->save();
    $this->calculateTotals();

    return true;
  }

  private function calculateTotals()
  {
    $this->totalPrice = 0;
    $this->totalQuantity = 0;

    foreach ($this->items as $item) {
      $this->totalPrice += $item['price'] * $item['quantity'];
      $this->totalQuantity += $item['quantity'];
    }
  }

  private function save()
  {
    $_SESSION['cart'] = $this->items;
  }

  private function generateItemId($productId, $attributes)
  {
    if (empty($attributes)) {
      return $productId;
    }

    // Sort attributes to ensure consistent order
    ksort($attributes);

    // Create a string representation of attributes
    $attributeString = '';
    foreach ($attributes as $key => $value) {
      $attributeString .= "$key:$value;";
    }

    return $productId . '-' . md5($attributeString);
  }
}
