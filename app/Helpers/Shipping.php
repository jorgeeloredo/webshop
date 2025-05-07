<?php
// app/Helpers/Shipping.php
namespace App\Helpers;

class Shipping
{
  private static $config = null;

  /**
   * Get the shipping configuration
   * 
   * @return array
   */
  public static function getConfig()
  {
    if (self::$config === null) {
      self::$config = require __DIR__ . '/../config/shipping.php';
    }

    return self::$config;
  }

  /**
   * Get all available shipping methods
   * 
   * @return array
   */
  public static function getMethods()
  {
    $config = self::getConfig();

    // Filter out inactive methods
    $methods = array_filter($config['methods'], function ($method) {
      return $method['active'] === true;
    });

    return $methods;
  }

  /**
   * Get a specific shipping method by code
   * 
   * @param string $code
   * @return array|null
   */
  public static function getMethod($code)
  {
    $methods = self::getMethods();

    return $methods[$code] ?? null;
  }

  /**
   * Get the default shipping method
   * 
   * @return array|null
   */
  public static function getDefaultMethod()
  {
    $config = self::getConfig();
    $defaultCode = $config['default_method'];

    return self::getMethod($defaultCode);
  }

  /**
   * Calculate shipping cost for a specific method and order total
   * 
   * @param string $methodCode
   * @param float $orderTotal
   * @return float
   */
  public static function calculateCost($methodCode, $orderTotal)
  {
    $method = self::getMethod($methodCode);
    $config = self::getConfig();

    if (!$method) {
      $method = self::getDefaultMethod();
    }

    // Free shipping is disabled globally
    if (!$config['enable_free_shipping']) {
      return $method['cost'];
    }

    // Check if free shipping applies to this method
    if (!$config['free_shipping_applies_to_all'] && $method['free_shipping_threshold'] <= 0) {
      return $method['cost'];
    }

    // Check if order total is above free shipping threshold
    $threshold = $config['free_shipping_applies_to_all']
      ? $config['free_shipping_threshold']
      : $method['free_shipping_threshold'];

    if ($orderTotal >= $threshold) {
      return 0;
    }

    return $method['cost'];
  }

  /**
   * Get shipping method display name
   * 
   * @param string $methodCode
   * @return string
   */
  public static function getMethodName($methodCode)
  {
    $method = self::getMethod($methodCode);

    return $method ? $method['name'] : 'Standard';
  }

  /**
   * Format shipping cost for display
   * 
   * @param float $cost
   * @return string
   */
  public static function formatCost($cost)
  {
    if ($cost <= 0) {
      return 'Gratuit';
    }

    return number_format($cost, 2, ',', ' ') . ' €';
  }

  /**
   * Get remaining amount for free shipping
   * 
   * @param float $orderTotal
   * @param string $methodCode
   * @return float
   */
  public static function getRemainingForFreeShipping($orderTotal, $methodCode = null)
  {
    $config = self::getConfig();

    if (!$config['enable_free_shipping']) {
      return 0;
    }

    $method = $methodCode ? self::getMethod($methodCode) : self::getDefaultMethod();

    $threshold = $config['free_shipping_applies_to_all']
      ? $config['free_shipping_threshold']
      : $method['free_shipping_threshold'];

    $remaining = $threshold - $orderTotal;

    return max(0, $remaining);
  }
}
