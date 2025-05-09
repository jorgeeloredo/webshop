<?php
// app/Controllers/FacebookFeedController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class FacebookFeedController extends Controller
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

    // Get all products
    $products = $this->productModel->getAll();

    // Base URL
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
    $config = require __DIR__ . '/../config/config.php';
    // Start XML
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
    echo '  <title>' . $config['app']['name'] . ' - Facebook Catalog Feed</title>' . PHP_EOL;
    echo '  <link href="' . $baseUrl . '"/>' . PHP_EOL;
    echo '  <updated>' . date('Y-m-d\TH:i:sP') . '</updated>' . PHP_EOL;
    echo '  <id>' . $baseUrl . '</id>' . PHP_EOL;

    foreach ($products as $product) {
      // Skip products without essential data
      if (empty($product['name']) || empty($product['price'])) {
        continue;
      }

      // Get category info if available
      $categoryPath = '';
      if (isset($product['category_id'])) {
        $category = $this->categoryModel->find($product['category_id']);
        if ($category) {
          $categoryPath = $category['name'];
        }
      }

      // Get first image URL
      $imageUrl = '';
      if (isset($product['images']) && !empty($product['images'])) {
        $imageUrl = $baseUrl . '/assets/images/products/' . $product['images'][0];
      }

      // Get stock status
      $availability = 'in stock';
      if (isset($product['stock']) && $product['stock'] <= 0) {
        $availability = 'out of stock';
      }

      // Generate unique ID
      $id = isset($product['sku']) ? $product['sku'] : 'SINGER-' . $product['id'];

      // Clean description
      $description = isset($product['description']) ? strip_tags($product['description']) : '';
      $description = html_entity_decode($description);
      // Truncate if needed (max 5000 characters)
      if (strlen($description) > 5000) {
        $description = substr($description, 0, 4997) . '...';
      }

      // Entry URL
      $productUrl = $baseUrl . '/product/' . $product['slug'];

      // Add product entry
      echo '  <entry>' . PHP_EOL;
      echo '    <id>' . htmlspecialchars($id) . '</id>' . PHP_EOL;
      echo '    <title>' . htmlspecialchars($product['name']) . '</title>' . PHP_EOL;
      echo '    <g:description>' . htmlspecialchars($description) . '</g:description>' . PHP_EOL;
      echo '    <g:brand>Singer</g:brand>' . PHP_EOL;
      echo '    <g:condition>new</g:condition>' . PHP_EOL;
      echo '    <g:availability>' . $availability . '</g:availability>' . PHP_EOL;

      // Handle price and sale price
      if (isset($product['old_price']) && $product['old_price'] > $product['price']) {
        echo '    <g:price>' . number_format($product['old_price'], 2, '.', '') . ' EUR</g:price>' . PHP_EOL;
        echo '    <g:sale_price>' . number_format($product['price'], 2, '.', '') . ' EUR</g:sale_price>' . PHP_EOL;
      } else {
        echo '    <g:price>' . number_format($product['price'], 2, '.', '') . ' EUR</g:price>' . PHP_EOL;
      }

      echo '    <g:link>' . $productUrl . '</g:link>' . PHP_EOL;
      echo '    <g:image_link>' . htmlspecialchars($imageUrl) . '</g:image_link>' . PHP_EOL;

      // Add additional images
      if (isset($product['images']) && count($product['images']) > 1) {
        $additionalImages = array_slice($product['images'], 1, 10);
        foreach ($additionalImages as $image) {
          $additionalImageUrl = $baseUrl . '/assets/images/products/' . $image;
          echo '    <g:additional_image_link>' . htmlspecialchars($additionalImageUrl) . '</g:additional_image_link>' . PHP_EOL;
        }
      }

      // Add product category
      if (!empty($categoryPath)) {
        echo '    <g:product_type>' . htmlspecialchars($categoryPath) . '</g:product_type>' . PHP_EOL;
      }

      // Facebook-specific fields
      echo '    <g:retailer_id>' . htmlspecialchars($id) . '</g:retailer_id>' . PHP_EOL;

      // Add inventory (stock) if available
      if (isset($product['stock'])) {
        echo '    <g:inventory>' . max(0, intval($product['stock'])) . '</g:inventory>' . PHP_EOL;
      }

      // Add MPN (manufacturer part number) if available
      if (isset($product['sku']) && !empty($product['sku'])) {
        echo '    <g:mpn>' . htmlspecialchars($product['sku']) . '</g:mpn>' . PHP_EOL;
      }

      // Add GTIN (EAN) if available
      if (isset($product['gtin']) && !empty($product['gtin'])) {
        echo '    <g:gtin>' . htmlspecialchars($product['gtin']) . '</g:gtin>' . PHP_EOL;
      } else if (isset($product['ean']) && !empty($product['ean'])) {
        echo '    <g:gtin>' . htmlspecialchars($product['ean']) . '</g:gtin>' . PHP_EOL;
      }

      // Add image metadata
      echo '    <g:rich_text_description><![CDATA[' . $description . ']]></g:rich_text_description>' . PHP_EOL;

      // Add required Atom elements
      echo '    <link href="' . $productUrl . '"/>' . PHP_EOL;
      echo '    <updated>' . date('Y-m-d\TH:i:sP') . '</updated>' . PHP_EOL;
      echo '    <summary>' . htmlspecialchars(substr($description, 0, 200)) . (strlen($description) > 200 ? '...' : '') . '</summary>' . PHP_EOL;

      echo '  </entry>' . PHP_EOL;
    }

    // End XML
    echo '</feed>';
    exit;
  }
}
