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
      'title' => 'Admin - Commandes',
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
        'message' => 'Commande non trouvée',
        'title' => '404 - Commande non trouvée'
      ]);
      return;
    }

    $this->view('admin/order_details', [
      'title' => 'Admin - Détails de la commande #' . $id,
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
      $_SESSION['error'] = 'Identifiant de commande ou statut invalide.';
      $this->redirect('/admin/orders');
      return;
    }

    // Update order status
    $success = $this->orderModel->updateStatus($orderId, $status);

    if ($success) {
      $_SESSION['success'] = 'Statut de la commande mis à jour avec succès.';
    } else {
      $_SESSION['error'] = 'Échec de la mise à jour du statut de la commande.';
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
