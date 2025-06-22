<?php
// app/controllers/CartController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Cart;
use App\Helpers\Utilities;
use App\Helpers\Shipping;

class CartController extends Controller
{
  private Cart $cartModel;

  public function __construct()
  {
    $this->cartModel = new Cart();
  }

  public function index()
  {
    // Get available shipping methods
    $shippingMethods = Shipping::getMethods();

    $this->view('cart/index', [
      'cart' => $this->cartModel,
      'title' => __('cart.your_cart'),
      'shippingMethods' => $shippingMethods
    ]);
  }

  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/cart');
      return;
    }

    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    // Extract attributes if any
    $attributes = [];
    foreach ($_POST as $key => $value) {
      if (strpos($key, 'attribute_') === 0) {
        $attributeKey = substr($key, 10); // Remove 'attribute_' prefix
        $attributes[$attributeKey] = $value;
      }
    }

    $success = $this->cartModel->add($productId, $quantity, $attributes);

    if (Utilities::isAjaxRequest()) {
      $this->json([
        'success' => $success,
        'totalItems' => $this->cartModel->getTotalQuantity(),
        'totalPrice' => Utilities::formatPrice($this->cartModel->getTotalPrice())
      ]);
    } else {
      // Redirect back to referring page or to cart
      $referer = $_SERVER['HTTP_REFERER'] ?? '/cart';
      $this->redirect($referer);
    }
  }

  public function buyNow()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/cart');
      return;
    }

    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

    // Extract attributes if any
    $attributes = [];
    foreach ($_POST as $key => $value) {
      if (strpos($key, 'attribute_') === 0) {
        $attributeKey = substr($key, 10); // Remove 'attribute_' prefix
        $attributes[$attributeKey] = $value;
      }
    }

    $success = $this->cartModel->add($productId, $quantity, $attributes);

    // Redirect to cart page regardless of AJAX or not
    $this->redirect('/cart');
  }

  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/cart');
      return;
    }

    $itemId = isset($_POST['item_id']) ? $_POST['item_id'] : '';
    $quantity = isset($_POST['quantity']) ? max(0, (int)$_POST['quantity']) : 0;

    $success = $this->cartModel->update($itemId, $quantity);

    if (Utilities::isAjaxRequest()) {
      $this->json([
        'success' => $success,
        'totalItems' => $this->cartModel->getTotalQuantity(),
        'totalPrice' => Utilities::formatPrice($this->cartModel->getTotalPrice()),
        'shippingCost' => $this->cartModel->getFormattedShippingCost(),
        'finalTotal' => $this->cartModel->getFormattedFinalTotal()
      ]);
    } else {
      $this->redirect('/cart');
    }
  }

  public function remove()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/cart');
      return;
    }

    $itemId = isset($_POST['item_id']) ? $_POST['item_id'] : '';

    $success = $this->cartModel->remove($itemId);

    if (Utilities::isAjaxRequest()) {
      $this->json([
        'success' => $success,
        'totalItems' => $this->cartModel->getTotalQuantity(),
        'totalPrice' => Utilities::formatPrice($this->cartModel->getTotalPrice()),
        'shippingCost' => $this->cartModel->getFormattedShippingCost(),
        'finalTotal' => $this->cartModel->getFormattedFinalTotal()
      ]);
    } else {
      $this->redirect('/cart');
    }
  }

  public function updateShipping()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/cart');
      return;
    }

    $methodCode = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : '';

    $success = $this->cartModel->setShippingMethod($methodCode);

    if (Utilities::isAjaxRequest()) {
      $this->json([
        'success' => $success,
        'shippingMethod' => $methodCode,
        'shippingCost' => $this->cartModel->getFormattedShippingCost(),
        'finalTotal' => $this->cartModel->getFormattedFinalTotal()
      ]);
    } else {
      $this->redirect('/cart');
    }
  }
}
