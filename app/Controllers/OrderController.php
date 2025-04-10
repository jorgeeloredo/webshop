<?php
// app/controllers/OrderController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Helpers\Auth;
use App\Helpers\Utilities;

class OrderController extends Controller
{
  private Order $orderModel;
  private Cart $cartModel;

  public function __construct()
  {
    $this->orderModel = new Order();
    $this->cartModel = new Cart();
  }

  public function index()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = '/account/orders';
      $this->redirect('/login');
      return;
    }

    $userId = Auth::id();
    $orders = $this->orderModel->findByUser($userId);

    $this->view('orders/index', [
      'title' => 'Mes commandes',
      'orders' => $orders
    ]);
  }

  public function show($id)
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = "/account/orders/{$id}";
      $this->redirect('/login');
      return;
    }

    $userId = Auth::id();
    $order = $this->orderModel->find($id);

    // Check if order exists and belongs to the user
    if (!$order || $order['user_id'] != $userId) {
      $this->view('error/404', [
        'message' => 'Commande non trouvée',
        'title' => '404 - Commande non trouvée'
      ]);
      return;
    }

    $this->view('orders/details', [
      'title' => 'Détails de la commande #' . $order['id'],
      'order' => $order
    ]);
  }

  public function checkout()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = '/checkout';
      $this->redirect('/login');
      return;
    }

    // Check if cart is empty
    if ($this->cartModel->getTotalQuantity() === 0) {
      $this->redirect('/cart');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->view('orders/checkout', [
        'title' => 'Finaliser la commande',
        'cart' => $this->cartModel  // Make sure this is initialized
      ]);
      return;
    }

    // Process checkout form
    $userId = Auth::id();
    $cartItems = $this->cartModel->getItems();

    // Get shipping and billing information from the form
    $shippingAddress = [
      'first_name' => $_POST['shipping_first_name'] ?? '',
      'last_name' => $_POST['shipping_last_name'] ?? '',
      'address' => $_POST['shipping_address'] ?? '',
      'address2' => $_POST['shipping_address2'] ?? '',
      'city' => $_POST['shipping_city'] ?? '',
      'postal_code' => $_POST['shipping_postal_code'] ?? '',
      'country' => $_POST['shipping_country'] ?? '',
      'phone' => $_POST['shipping_phone'] ?? ''
    ];

    // Check if billing address is the same as shipping
    $sameAsBilling = isset($_POST['same_as_billing']) && $_POST['same_as_billing'] === 'on';

    if ($sameAsBilling) {
      $billingAddress = $shippingAddress;
    } else {
      $billingAddress = [
        'first_name' => $_POST['billing_first_name'] ?? '',
        'last_name' => $_POST['billing_last_name'] ?? '',
        'address' => $_POST['billing_address'] ?? '',
        'address2' => $_POST['billing_address2'] ?? '',
        'city' => $_POST['billing_city'] ?? '',
        'postal_code' => $_POST['billing_postal_code'] ?? '',
        'country' => $_POST['billing_country'] ?? '',
        'phone' => $_POST['billing_phone'] ?? ''
      ];
    }

    // Get payment information
    $paymentMethod = $_POST['payment_method'] ?? 'card';

    // Basic validation
    $errors = [];

    // Check required shipping fields
    if (empty($shippingAddress['first_name'])) {
      $errors['shipping_first_name'] = 'Le prénom est requis';
    }

    if (empty($shippingAddress['last_name'])) {
      $errors['shipping_last_name'] = 'Le nom est requis';
    }

    if (empty($shippingAddress['address'])) {
      $errors['shipping_address'] = 'L\'adresse est requise';
    }

    if (empty($shippingAddress['city'])) {
      $errors['shipping_city'] = 'La ville est requise';
    }

    if (empty($shippingAddress['postal_code'])) {
      $errors['shipping_postal_code'] = 'Le code postal est requis';
    }

    if (empty($shippingAddress['country'])) {
      $errors['shipping_country'] = 'Le pays est requis';
    }

    if (empty($shippingAddress['phone'])) {
      $errors['shipping_phone'] = 'Le téléphone est requis';
    }

    // Check billing fields if not same as shipping
    if (!$sameAsBilling) {
      if (empty($billingAddress['first_name'])) {
        $errors['billing_first_name'] = 'Le prénom est requis';
      }

      if (empty($billingAddress['last_name'])) {
        $errors['billing_last_name'] = 'Le nom est requis';
      }

      if (empty($billingAddress['address'])) {
        $errors['billing_address'] = 'L\'adresse est requise';
      }

      if (empty($billingAddress['city'])) {
        $errors['billing_city'] = 'La ville est requise';
      }

      if (empty($billingAddress['postal_code'])) {
        $errors['billing_postal_code'] = 'Le code postal est requis';
      }

      if (empty($billingAddress['country'])) {
        $errors['billing_country'] = 'Le pays est requis';
      }

      if (empty($billingAddress['phone'])) {
        $errors['billing_phone'] = 'Le téléphone est requis';
      }
    }

    // If validation fails, return to checkout form with errors
    if (!empty($errors)) {
      $this->view('orders/checkout', [
        'title' => 'Finaliser la commande',
        'cart' => $this->cartModel,
        'errors' => $errors,
        'old' => $_POST
      ]);
      return;
    }

    // Create the order
    $orderData = [
      'user_id' => $userId,
      'total' => $this->cartModel->getTotalPrice(),
      'shipping_address' => json_encode($shippingAddress),
      'billing_address' => json_encode($billingAddress),
      'payment_method' => $paymentMethod,
      'status' => 'pending' // Set initial status to pending
    ];

    // Create order in database and get order ID
    $orderId = $this->orderModel->create($orderData, $cartItems);

    // Clear the cart
    $this->cartModel->clear();

    // Skip payment processing and redirect directly to success page
    $this->redirect("/checkout/success?order_id={$orderId}");
  }

  public function success()
  {
    $orderId = $_GET['order_id'] ?? 0;

    // Check if order exists and belongs to the user
    if (!Auth::check()) {
      $this->redirect('/login');
      return;
    }

    $userId = Auth::id();
    $order = $this->orderModel->find($orderId);

    if (!$order || $order['user_id'] != $userId) {
      $this->view('error/404', [
        'message' => 'Commande non trouvée',
        'title' => '404 - Commande non trouvée'
      ]);
      return;
    }

    $this->view('orders/success', [
      'title' => 'Commande confirmée',
      'order' => $order
    ]);
  }
}
