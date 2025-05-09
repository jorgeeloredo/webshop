<?php
// app/controllers/AccountController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Helpers\Auth;
use App\Helpers\Utilities;

class AccountController extends Controller
{
  private User $userModel;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function loginForm()
  {
    // If already logged in, redirect to dashboard
    if (Auth::check()) {
      $this->redirect('/account');
      return;
    }

    $this->view('account/login', [
      'title' => __('account.login')
    ]);
  }

  public function login()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/login');
      return;
    }

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'on';

    // Validate inputs
    $errors = [];

    if (empty($email)) {
      $errors['email'] = __('validation.email_required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = __('validation.email_invalid');
    }

    if (empty($password)) {
      $errors['password'] = __('validation.password_required');
    }

    // If validation fails, return to login form with errors
    if (!empty($errors)) {
      $this->view('account/login', [
        'title' => __('account.login'),
        'errors' => $errors,
        'old' => [
          'email' => $email,
          'remember' => $remember
        ]
      ]);
      return;
    }

    // Attempt authentication
    $user = $this->userModel->authenticate($email, $password);

    if (!$user) {
      $this->view('account/login', [
        'title' => __('account.login'),
        'error' => __('account.login_error'),
        'old' => [
          'email' => $email,
          'remember' => $remember
        ]
      ]);
      return;
    }

    // Login successful
    Auth::login($user);

    // Set remember me cookie if requested
    if ($remember) {
      $token = Utilities::generateRandomString(32);
      setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/', '', true, true);

      // Update user with remember token
      $this->userModel->update($user['id'], [
        'remember_token' => $token
      ]);
    }

    // Redirect to intended page or dashboard
    $intended = $_SESSION['intended_url'] ?? '/account';
    unset($_SESSION['intended_url']);

    $this->redirect($intended);
  }

  public function registerForm()
  {
    // If already logged in, redirect to dashboard
    if (Auth::check()) {
      $this->redirect('/account');
      return;
    }

    $this->view('account/register', [
      'title' => __('account.register')
    ]);
  }

  public function register()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/register');
      return;
    }

    // Get inputs
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validate inputs
    $errors = [];

    if (empty($firstName)) {
      $errors['first_name'] = __('validation.first_name_required');
    }

    if (empty($lastName)) {
      $errors['last_name'] = __('validation.last_name_required');
    }

    if (empty($email)) {
      $errors['email'] = __('validation.email_required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = __('validation.email_invalid');
    } elseif ($this->userModel->findByEmail($email)) {
      $errors['email'] = __('validation.email_already_used');
    }

    if (empty($password)) {
      $errors['password'] = __('validation.password_required');
    } elseif (strlen($password) < 8) {
      $errors['password'] = __('validation.password_min_length');
    }

    if ($password !== $passwordConfirm) {
      $errors['password_confirm'] = __('validation.passwords_dont_match');
    }

    // If validation fails, return to register form with errors
    if (!empty($errors)) {
      $this->view('account/register', [
        'title' => __('account.register'),
        'errors' => $errors,
        'old' => [
          'first_name' => $firstName,
          'last_name' => $lastName,
          'email' => $email
        ]
      ]);
      return;
    }

    // Create user
    $userId = $this->userModel->create([
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $email,
      'password' => $password,
      'role' => 'customer'
    ]);

    // Login the new user
    $user = $this->userModel->find($userId);
    Auth::login($user);

    // Send account creation confirmation email
    $emailService = new \App\Helpers\EmailService();
    $emailService->sendAccountCreationEmail(
      $email,
      $firstName,
      $lastName,
      $password // Send the password in the welcome email
    );

    // Redirect to dashboard
    $this->redirect('/account');
  }

  public function logout()
  {
    Auth::logout();

    // Remove remember me cookie if exists
    if (isset($_COOKIE['remember_token'])) {
      setcookie('remember_token', '', time() - 3600, '/', '', true, true);
    }

    $this->redirect('/');
  }

  public function dashboard()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = '/account';
      $this->redirect('/login');
      return;
    }

    $user = Auth::user();

    // Get user's recent orders
    $recentOrders = $this->userModel->getOrders($user['id']);

    $this->view('account/dashboard', [
      'title' => __('dashboard.my_account'),
      'user' => $user,
      'recentOrders' => $recentOrders
    ]);
  }

  public function profile()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = '/account/profile';
      $this->redirect('/login');
      return;
    }

    $user = Auth::user();

    $this->view('account/profile', [
      'title' => __('profile.my_profile'),
      'user' => $user
    ]);
  }

  public function updateProfile()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $this->redirect('/login');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/account/profile');
      return;
    }

    $user = Auth::user();

    // Get inputs
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

    // Validate inputs
    $errors = [];

    if (empty($firstName)) {
      $errors['first_name'] = __('validation.first_name_required');
    }

    if (empty($lastName)) {
      $errors['last_name'] = __('validation.last_name_required');
    }

    if (empty($email)) {
      $errors['email'] = __('validation.email_required');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = __('validation.email_invalid');
    } elseif ($email !== $user['email'] && $this->userModel->findByEmail($email)) {
      $errors['email'] = __('validation.email_already_used');
    }

    // If password is being updated, validate it
    if (!empty($newPassword)) {
      if (empty($currentPassword)) {
        $errors['current_password'] = __('validation.current_password_required');
      } elseif (!password_verify($currentPassword, $user['password'])) {
        $errors['current_password'] = __('validation.current_password_invalid');
      }

      if (strlen($newPassword) < 8) {
        $errors['new_password'] = __('validation.password_min_length');
      }

      if ($newPassword !== $newPasswordConfirm) {
        $errors['new_password_confirm'] = __('validation.passwords_dont_match');
      }
    }

    // If validation fails, return to profile form with errors
    if (!empty($errors)) {
      $this->view('account/profile', [
        'title' => __('profile.my_profile'),
        'user' => $user,
        'errors' => $errors
      ]);
      return;
    }

    // Update user data
    $userData = [
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $email
    ];

    // Add password if it's being updated
    if (!empty($newPassword)) {
      $userData['password'] = $newPassword;
    }

    $this->userModel->update($user['id'], $userData);

    // Redirect back to profile with success message
    $_SESSION['success'] = __('account.profile_updated');
    $this->redirect('/account/profile');
  }

  public function addresses()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $_SESSION['intended_url'] = '/account/addresses';
      $this->redirect('/login');
      return;
    }

    $user = Auth::user();

    // Normally we would load addresses from the database
    // For now, we'll use placeholder data
    $addresses = [
      [
        'id' => 1,
        'type' => 'shipping',
        'is_default' => true,
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'address' => '123 rue Principale',
        'address2' => 'Appartement 4B',
        'city' => 'Paris',
        'postal_code' => '75001',
        'country' => 'France',
        'phone' => '01 23 45 67 89'
      ],
      [
        'id' => 2,
        'type' => 'billing',
        'is_default' => true,
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'address' => '456 rue Secondaire',
        'address2' => '',
        'city' => 'Lyon',
        'postal_code' => '69001',
        'country' => 'France',
        'phone' => '01 98 76 54 32'
      ]
    ];

    $this->view('account/addresses', [
      'title' => __('addresses.my_addresses'),
      'user' => $user,
      'addresses' => $addresses
    ]);
  }

  public function addAddress()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $this->redirect('/login');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/account/addresses');
      return;
    }

    // Process the form data here
    // For now, we'll just redirect with a success message
    $_SESSION['success'] = __('account.address_added');
    $this->redirect('/account/addresses');
  }

  public function updateAddress()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $this->redirect('/login');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/account/addresses');
      return;
    }

    // Process the form data here
    // For now, we'll just redirect with a success message
    $_SESSION['success'] = __('account.address_updated');
    $this->redirect('/account/addresses');
  }

  public function deleteAddress()
  {
    // Check if user is logged in
    if (!Auth::check()) {
      $this->redirect('/login');
      return;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('/account/addresses');
      return;
    }

    // Process the deletion here
    // For now, we'll just redirect with a success message
    $_SESSION['success'] = __('account.address_deleted');
    $this->redirect('/account/addresses');
  }
}
