<?php
// app/core/App.php
namespace App\Core;

class App
{
  protected static $registry = [];
  protected Router $router;
  protected static $instance = null;

  public function __construct()
  {
    $this->router = new Router();
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function getRouter(): Router
  {
    return $this->router;
  }

  public static function bind($key, $value)
  {
    static::$registry[$key] = $value;
  }

  public static function get($key)
  {
    if (!array_key_exists($key, static::$registry)) {
      throw new \Exception("No {$key} is bound in the container.");
    }
    return static::$registry[$key];
  }

  public function run()
  {
    $this->router->resolve();
  }
}
