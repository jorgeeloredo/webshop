<?php
// app/core/Controller.php
namespace App\Core;

class Controller
{
  /**
   * Render a view with optional data
   *
   * @param string $view
   * @param array $data
   * @return void
   */
  public function view($view, $data = [])
  {
    // Extract data to make them available as variables in the view
    extract($data);

    // Include header
    require_once __DIR__ . "/../views/partials/header.php";

    // Include the view file
    require_once __DIR__ . "/../views/{$view}.php";

    // Include footer
    require_once __DIR__ . "/../views/partials/footer.php";
  }

  /**
   * Redirect to a specific path
   *
   * @param string $path
   * @return void
   */
  public function redirect($path)
  {
    header("Location: {$path}");
    exit;
  }

  /**
   * Send JSON response
   *
   * @param mixed $data
   * @param int $statusCode
   * @return void
   */
  public function json($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }
}
