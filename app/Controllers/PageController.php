<?php
// app/Controllers/PageController.php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
  /**
   * Display a static page from the pages directory
   * 
   * @param string $slug The name of the page to display
   * @return void
   */
  public function show($slug = 'index')
  {
    // Secure the page name to prevent directory traversal attacks
    $slug = preg_replace('/[^a-zA-Z0-9_\-]/', '', $slug);

    // Define the path to the pages directory
    $pagesDir = __DIR__ . '/../../pages/';

    // Check if the requested page exists
    $pageFile = $pagesDir . $slug . '.php';

    if (file_exists($pageFile)) {
      // Include the page to get its content and title
      // Title and content are defined in the page file
      include $pageFile;

      // Ensure $pageTitle and $pageContent are defined
      $pageTitle = $pageTitle ?? 'Singer Shop';

      // Display the page view
      $this->view('page/show', [
        'title' => $pageTitle,
        'content' => $pageContent ?? '',
        'slug' => $slug
      ]);
    } else {
      // Page not found, display 404 error page
      $this->view('error/404', [
        'message' => __('error.page_not_found'),
        'title' => '404 - ' . __('error.page_not_found')
      ]);
    }
  }
}
