<?php
// app/core/Router.php
namespace App\Core;

class Router
{
  protected $routes = [
    'GET' => [],
    'POST' => []
  ];

  public function get($uri, $controller, $action = null)
  {
    $this->routes['GET'][$uri] = [
      'controller' => $controller,
      'action' => $action
    ];
    return $this;
  }

  public function post($uri, $controller, $action = null)
  {
    $this->routes['POST'][$uri] = [
      'controller' => $controller,
      'action' => $action
    ];
    return $this;
  }

  public function resolve()
  {
    $uri = $this->getUri();
    $method = $_SERVER['REQUEST_METHOD'];

    // Check for direct match
    if (array_key_exists($uri, $this->routes[$method])) {
      return $this->callAction(
        $this->routes[$method][$uri]['controller'],
        $this->routes[$method][$uri]['action']
      );
    }

    // Check for pattern match (like /product/{slug})
    foreach ($this->routes[$method] as $route => $handler) {
      if (strpos($route, '{') !== false) {
        $pattern = preg_replace('/{[^}]+}/', '([^/]+)', $route);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $uri, $matches)) {
          array_shift($matches); // Remove the first match (full string)
          return $this->callAction(
            $handler['controller'],
            $handler['action'],
            $matches
          );
        }
      }
    }

    // No route found
    $this->renderError(404);
  }

  protected function getUri()
  {
    $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    return $uri === '' ? '/' : '/' . $uri;
  }

  protected function callAction($controller, $action, $params = [])
  {
    $controller = "App\\Controllers\\{$controller}";

    if (!class_exists($controller)) {
      throw new \Exception("Controller {$controller} does not exist.");
    }

    $controllerInstance = new $controller();

    if (!method_exists($controllerInstance, $action)) {
      throw new \Exception(
        "{$controller} does not respond to the {$action} action."
      );
    }

    return call_user_func_array([$controllerInstance, $action], $params);
  }

  protected function renderError($code)
  {
    http_response_code($code);
    require_once __DIR__ . "/../views/error/{$code}.php";
    exit;
  }

  public function loadRoutes($routesFile)
  {
    require $routesFile;
  }
}
