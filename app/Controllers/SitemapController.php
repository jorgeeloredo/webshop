<?php
// app/Controllers/SitemapController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class SitemapController extends Controller
{
  private Product $productModel;
  private Category $categoryModel;

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
  }

  public function index()
  {
    // Set the content type
    header('Content-Type: application/xml; charset=utf-8');

    // Get all products and categories
    $products = $this->productModel->getAll();
    $categories = $this->categoryModel->getAll();

    // Base URL
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    // Start XML
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

    // Add home page
    echo '<url>' . PHP_EOL;
    echo '  <loc>' . $baseUrl . '/</loc>' . PHP_EOL;
    echo '  <changefreq>daily</changefreq>' . PHP_EOL;
    echo '  <priority>1.0</priority>' . PHP_EOL;
    echo '</url>' . PHP_EOL;

    // Add category pages
    foreach ($categories as $category) {
      echo '<url>' . PHP_EOL;
      echo '  <loc>' . $baseUrl . '/category/' . $category['slug'] . '</loc>' . PHP_EOL;
      echo '  <changefreq>weekly</changefreq>' . PHP_EOL;
      echo '  <priority>0.8</priority>' . PHP_EOL;
      echo '</url>' . PHP_EOL;
    }

    // Add product pages
    foreach ($products as $product) {
      echo '<url>' . PHP_EOL;
      echo '  <loc>' . $baseUrl . '/product/' . $product['slug'] . '</loc>' . PHP_EOL;

      // Use updated_at for lastmod if available
      if (isset($product['updated_at'])) {
        $lastmod = date('Y-m-d', strtotime($product['updated_at']));
        echo '  <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
      }

      echo '  <changefreq>weekly</changefreq>' . PHP_EOL;
      echo '  <priority>0.7</priority>' . PHP_EOL;
      echo '</url>' . PHP_EOL;
    }

    // Add other static pages
    $staticPages = [
      '/cart' => 0.5,
      '/login' => 0.4,
      '/register' => 0.4
    ];

    foreach ($staticPages as $page => $priority) {
      echo '<url>' . PHP_EOL;
      echo '  <loc>' . $baseUrl . $page . '</loc>' . PHP_EOL;
      echo '  <changefreq>monthly</changefreq>' . PHP_EOL;
      echo '  <priority>' . $priority . '</priority>' . PHP_EOL;
      echo '</url>' . PHP_EOL;
    }

    // End XML
    echo '</urlset>';
    exit;
  }
}
