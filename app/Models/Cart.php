<?php
// app/models/Cart.php
namespace App\Models;

use App\Helpers\Shipping;

class Cart
{
  private $items = [];
  private $totalPrice = 0;
  private $totalQuantity = 0;
  private $shippingMethod = null;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $this->items = $_SESSION['cart'] ?? [];

    // Initialize shipping method from session or default
    $this->shippingMethod = $_SESSION['shipping_method'] ?? Shipping::getDefaultMethod()['code'];

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

  public function getShippingMethod()
  {
    return $this->shippingMethod;
  }

  public function setShippingMethod($methodCode)
  {
    $method = Shipping::getMethod($methodCode);

    if (!$method) {
      return false;
    }

    $this->shippingMethod = $methodCode;
    $_SESSION['shipping_method'] = $methodCode;

    return true;
  }

  public function getShippingCost()
  {
    return Shipping::calculateCost($this->shippingMethod, $this->totalPrice);
  }

  public function getFormattedShippingCost()
  {
    return Shipping::formatCost($this->getShippingCost());
  }

  public function getFinalTotal()
  {
    return $this->totalPrice + $this->getShippingCost();
  }

  public function getFormattedFinalTotal()
  {
    return number_format($this->getFinalTotal(), 2, ',', ' ') . ' €';
  }

  public function getRemainingForFreeShipping()
  {
    return Shipping::getRemainingForFreeShipping($this->totalPrice, $this->shippingMethod);
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
        // Store the first image from the product's images array
        'image' => isset($product['images']) && !empty($product['images']) ? $product['images'][0] : null,
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
