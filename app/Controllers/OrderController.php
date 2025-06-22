<?php
// app/controllers/OrderController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Helpers\Auth;
use App\Helpers\Utilities;
use App\Helpers\EmailService;
use App\Helpers\Shipping;

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
      'title' => __('order.my_orders'),
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
        'message' => __('error.order_not_found'),
        'title' => '404 - ' . __('error.order_not_found')
      ]);
      return;
    }

    $this->view('orders/details', [
      'title' => __('order.order_details', ['id' => $order['id']]),
      'order' => $order
    ]);
  }

  public function checkout()
  {
    // Check if cart is empty
    if ($this->cartModel->getTotalQuantity() === 0) {
      $this->redirect('/cart');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      // Get the shipping methods
      $shippingMethods = Shipping::getMethods();

      // Just display the checkout page without requiring login
      $this->view('orders/checkout', [
        'title' => __('checkout.finalize_order'),
        'cart' => $this->cartModel,
        'shippingMethods' => $shippingMethods
      ]);
      return;
    }

    // Process checkout form
    $cartItems = $this->cartModel->getItems();
    $isLoggedIn = \App\Helpers\Auth::check();
    $userId = null;

    // Guest checkout with option to create account
    if (!$isLoggedIn) {
      $email = $_POST['email'] ?? '';
      $createAccount = isset($_POST['create_account']) && $_POST['create_account'] === 'on';
      $password = $_POST['password'] ?? '';
      $passwordConfirm = $_POST['password_confirm'] ?? '';
      $firstName = $_POST['first_name'] ?? '';
      $lastName = $_POST['last_name'] ?? '';

      // Validate email
      $errors = [];
      if (empty($email)) {
        $errors['email'] = __('validation.email_required');
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = __('validation.email_invalid');
      }

      // If creating account, validate password
      if ($createAccount) {
        if (empty($password)) {
          $errors['password'] = __('validation.account_creation_password_required');
        } elseif (strlen($password) < 8) {
          $errors['password'] = __('validation.password_min_length');
        }

        if ($password !== $passwordConfirm) {
          $errors['password_confirm'] = __('validation.passwords_dont_match');
        }

        // Check if email is already registered
        $userModel = new \App\Models\User();
        if ($userModel->findByEmail($email)) {
          $errors['email'] = __('validation.email_login_required');
        }
      }

      // If no errors and creating account, create user
      if (empty($errors) && $createAccount) {
        $userModel = new \App\Models\User();
        $userId = $userModel->create([
          'first_name' => $firstName,
          'last_name' => $lastName,
          'email' => $email,
          'password' => $password,
          'role' => 'customer'
        ]);

        // Log the user in
        $user = $userModel->find($userId);
        \App\Helpers\Auth::login($user);
      }
    } else {
      // User is already logged in
      $userId = \App\Helpers\Auth::id();
    }

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

    // Get shipping method
    $shippingMethod = $_POST['shipping_method'] ?? $this->cartModel->getShippingMethod();

    // Calculate shipping cost based on cart total and selected shipping method
    $shippingCost = Shipping::calculateCost($shippingMethod, $this->cartModel->getTotalPrice());

    // Basic validation for shipping address
    if (empty($shippingAddress['first_name'])) {
      $errors['shipping_first_name'] = __('validation.shipping_first_name_required');
    }

    if (empty($shippingAddress['last_name'])) {
      $errors['shipping_last_name'] = __('validation.shipping_last_name_required');
    }

    if (empty($shippingAddress['address'])) {
      $errors['shipping_address'] = __('validation.shipping_address_required');
    }

    if (empty($shippingAddress['city'])) {
      $errors['shipping_city'] = __('validation.shipping_city_required');
    }

    if (empty($shippingAddress['postal_code'])) {
      $errors['shipping_postal_code'] = __('validation.shipping_postal_code_required');
    }

    if (empty($shippingAddress['country'])) {
      $errors['shipping_country'] = __('validation.shipping_country_required');
    }

    if (empty($shippingAddress['phone'])) {
      $errors['shipping_phone'] = __('validation.shipping_phone_required');
    }

    // Check billing fields if not same as shipping
    if (!$sameAsBilling) {
      if (empty($billingAddress['first_name'])) {
        $errors['billing_first_name'] = __('validation.billing_first_name_required');
      }

      if (empty($billingAddress['last_name'])) {
        $errors['billing_last_name'] = __('validation.billing_last_name_required');
      }

      if (empty($billingAddress['address'])) {
        $errors['billing_address'] = __('validation.billing_address_required');
      }

      if (empty($billingAddress['city'])) {
        $errors['billing_city'] = __('validation.billing_city_required');
      }

      if (empty($billingAddress['postal_code'])) {
        $errors['billing_postal_code'] = __('validation.billing_postal_code_required');
      }

      if (empty($billingAddress['country'])) {
        $errors['billing_country'] = __('validation.billing_country_required');
      }

      if (empty($billingAddress['phone'])) {
        $errors['billing_phone'] = __('validation.billing_phone_required');
      }
    }

    // Validate shipping method
    if (empty($shippingMethod) || !Shipping::getMethod($shippingMethod)) {
      $errors['shipping_method'] = __('validation.shipping_method_invalid');
    }

    // If validation fails, return to checkout form with errors
    if (!empty($errors)) {
      $this->view('orders/checkout', [
        'title' => __('checkout.finalize_order'),
        'cart' => $this->cartModel,
        'errors' => $errors,
        'old' => $_POST,
        'shippingMethods' => Shipping::getMethods()
      ]);
      return;
    }

    // Calculate and store the final total (including shipping cost)
    $totalPrice = $this->cartModel->getTotalPrice();
    $finalTotal = $totalPrice + $shippingCost;

    // Create the order (works for both guest and logged-in users)
    $orderData = [
      'user_id' => $userId, // Can be null for guest checkout
      'total' => $finalTotal,
      'shipping_address' => json_encode($shippingAddress),
      'billing_address' => json_encode($billingAddress),
      'payment_method' => $paymentMethod,
      'status' => 'pending', // Set initial status to pending
      'email' => $isLoggedIn ? \App\Helpers\Auth::user()['email'] : ($_POST['email'] ?? ''),
      'guest_checkout' => !$isLoggedIn && !$createAccount ? 1 : 0,  // Ensure this is an integer, not a string
      'shipping_method' => $shippingMethod,
      'shipping_cost' => $shippingCost
    ];

    // Create order in database and get order ID
    $orderId = $this->orderModel->create($orderData, $cartItems);

    // Get the created order with its items
    $order = $this->orderModel->find($orderId);
    $orderItems = isset($order['items']) ? $order['items'] : [];

    // Get user data (if logged in)
    $user = [];
    if ($userId) {
      $userModel = new \App\Models\User();
      $user = $userModel->find($userId);
    }

    // Send order confirmation email
    $emailService = new EmailService();
    $emailService->sendOrderConfirmationEmail($order, $orderItems, $user);

    // Clear the cart
    $this->cartModel->clear();

    // Skip payment processing and redirect directly to success page
    $this->redirect("/checkout/success?order_id={$orderId}");
  }

  public function success()
  {
    $orderId = $_GET['order_id'] ?? 0;
    $order = $this->orderModel->find($orderId);

    // Check if order exists
    if (!$order) {
      $this->view('error/404', [
        'message' => __('error.order_not_found'),
        'title' => '404 - ' . __('error.order_not_found')
      ]);
      return;
    }

    // For logged-in users, check if the order belongs to them
    if (Auth::check()) {
      $userId = Auth::id();
      if ($order['user_id'] && $order['user_id'] != $userId) {
        $this->view('error/404', [
          'message' => __('error.order_not_found'),
          'title' => '404 - ' . __('error.order_not_found')
        ]);
        return;
      }
    } else {
      // For guests, store the order ID in session to allow them to view it
      if (!isset($_SESSION['guest_orders'])) {
        $_SESSION['guest_orders'] = [];
      }

      // Add the order to the guest orders if it's not already there
      if (!in_array($orderId, $_SESSION['guest_orders'])) {
        $_SESSION['guest_orders'][] = $orderId;
      }
    }

    $this->view('orders/success', [
      'title' => __('success.thank_you'),
      'order' => $order
    ]);
  }
}
