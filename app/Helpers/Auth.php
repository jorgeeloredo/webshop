<?php
// app/helpers/Auth.php
namespace App\Helpers;

use App\Models\User;

class Auth
{
  private static $user = null;

  public static function initialize()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }

  public static function login($user)
  {
    self::initialize();

    $_SESSION['user_id'] = $user['id'];
    self::$user = $user;

    return true;
  }

  public static function logout()
  {
    self::initialize();

    unset($_SESSION['user_id']);
    self::$user = null;

    return true;
  }

  public static function check()
  {
    self::initialize();

    return isset($_SESSION['user_id']);
  }

  public static function user()
  {
    if (self::check() && self::$user === null) {
      $userModel = new User();
      self::$user = $userModel->find($_SESSION['user_id']);
    }

    return self::$user;
  }

  public static function id()
  {
    self::initialize();

    return $_SESSION['user_id'] ?? null;
  }

  public static function guest()
  {
    return !self::check();
  }
}
