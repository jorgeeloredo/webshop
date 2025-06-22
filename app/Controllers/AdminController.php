<?php
// app/Controllers/AdminController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Order;
use App\Models\User;
use App\Helpers\Auth;

class AdminController extends Controller
{
  private Order $orderModel;
  private User $userModel;

  public function __construct()
  {
    $this->orderModel = new Order();
    $this->userModel = new User();
  }

  public function index()
  {
    // Check if user is logged in and has admin privileges
    if (!$this->isAdmin()) {
      $this->redirect('/login');
      return;
    }

    // Redirect to orders page
    $this->redirect('/admin/orders');
  }

  public function orders()
  {
    // Check if user is logged in and has admin privileges
    if (!$this->isAdmin()) {
      $this->redirect('/login');
      return;
    }

    // Get all orders
    $orders = $this->orderModel->getAll();

    $this->view('admin/orders', [
      'title' => __('admin.admin') . ' - ' . __('admin.manage_orders'),
      'orders' => $orders,
      'orderStatuses' => $this->orderModel->getAllStatuses()
    ]);
  }

  public function orderDetails($id)
  {
    // Check if user is logged in and has admin privileges
    if (!$this->isAdmin()) {
      $this->redirect('/login');
      return;
    }

    // Get order by ID
    $order = $this->orderModel->find($id);

    if (!$order) {
      $this->view('error/404', [
        'message' => __('error.order_not_found'),
        'title' => '404 - ' . __('error.order_not_found')
      ]);
      return;
    }

    $this->view('admin/order_details', [
      'title' => __('admin.admin') . ' - ' . __('admin.order_details', ['id' => $id]),
      'order' => $order,
      'orderStatuses' => $this->orderModel->getAllStatuses()
    ]);
  }

  public function updateOrderStatus()
  {
    // Check if user is logged in and has admin privileges
    if (!$this->isAdmin()) {
      $this->redirect('/login');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/admin/orders');
      return;
    }

    $orderId = $_POST['order_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!$orderId || !$status) {
      $_SESSION['error'] = __('admin.invalid_order_or_status');
      $this->redirect('/admin/orders');
      return;
    }

    // Update order status
    $success = $this->orderModel->updateStatus($orderId, $status);

    if ($success) {
      $_SESSION['success'] = __('admin.status_updated');
    } else {
      $_SESSION['error'] = __('admin.status_update_failed');
    }

    $this->redirect('/admin/orders');
  }

  private function isAdmin()
  {
    if (!Auth::check()) {
      return false;
    }

    $user = Auth::user();
    return isset($user['role']) && $user['role'] === 'admin';
  }
}
