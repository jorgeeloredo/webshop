<?php
// app/helpers/Utilities.php
namespace App\Helpers;

class Utilities
{
  public static function formatPrice($price, $currencySymbol = '€')
  {
    return number_format($price, 2, ',', ' ') . ' ' . $currencySymbol;
  }

  public static function formatDate($date, $format = 'd/m/Y')
  {
    return date($format, strtotime($date));
  }

  public static function sanitizeInput($input)
  {
    if (is_array($input)) {
      foreach ($input as $key => $value) {
        $input[$key] = self::sanitizeInput($value);
      }
      return $input;
    }

    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
  }

  public static function truncate($string, $length = 100, $append = '...')
  {
    if (strlen($string) > $length) {
      $string = substr($string, 0, $length) . $append;
    }

    return $string;
  }

  public static function generateRandomString($length = 10)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  public static function isAjaxRequest()
  {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
  }

  public static function redirect($url)
  {
    header("Location: {$url}");
    exit;
  }
}
