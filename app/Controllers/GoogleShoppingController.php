<?php
// app/Controllers/GoogleShoppingController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class GoogleShoppingController extends Controller
{
  private Product $productModel;
  private Category $categoryModel;
  private array $categoryMapping = [];

  public function __construct()
  {
    $this->productModel = new Product();
    $this->categoryModel = new Category();
    $this->loadCategoryMapping();
  }

  /**
   * Load category mapping from categories.json
   */
  private function loadCategoryMapping()
  {
    // Default mapping for categories that might not be explicitly mapped
    $this->categoryMapping = [
      'default' => 'Home & Garden > Sewing > Sewing Machines'
    ];

    // Get all categories
    $categories = $this->categoryModel->getAll();

    // Map each category slug to a Google product category
    foreach ($categories as $category) {
      if (isset($category['slug']) && isset($category['google_category'])) {
        $this->categoryMapping[$category['slug']] = $category['google_category'];
      } else if (isset($category['slug'])) {
        // Apply default mappings based on slug patterns if google_category is not set
        if (strpos($category['slug'], 'machine') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Sewing > Sewing Machines';
        } else if (strpos($category['slug'], 'surjet') !== false || strpos($category['slug'], 'recouvr') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Sewing > Sewing Machines';
        } else if (strpos($category['slug'], 'brodeu') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Sewing > Sewing Machines';
        } else if (strpos($category['slug'], 'accessoire') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Sewing > Sewing Machine Accessories';
        } else if (strpos($category['slug'], 'linge') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Household Appliances > Laundry Appliances > Irons & Steamers';
        } else if (strpos($category['slug'], 'sol') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Household Appliances > Vacuum Cleaners';
        } else if (strpos($category['slug'], 'electro') !== false) {
          $this->categoryMapping[$category['slug']] = 'Home & Garden > Household Appliances';
        }
      }
    }
  }

  /**
   * Get Google category for a given category slug
   */
  private function getGoogleCategory($categorySlug)
  {
    if (isset($this->categoryMapping[$categorySlug])) {
      return $this->categoryMapping[$categorySlug];
    }

    return $this->categoryMapping['default'];
  }

  public function index()
  {
    // Set the content type
    header('Content-Type: application/xml; charset=utf-8');

    // Get all products
    $products = $this->productModel->getAll();
    $categories = $this->categoryModel->getAll();

    // Create category ID to slug mapping
    $categoryIdToSlug = [];
    $categoryIdToName = [];
    foreach ($categories as $category) {
      if (isset($category['id']) && isset($category['slug'])) {
        $categoryIdToSlug[$category['id']] = $category['slug'];
      }
      if (isset($category['id']) && isset($category['name'])) {
        $categoryIdToName[$category['id']] = $category['name'];
      }
    }

    // Base URL
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    // Start XML
    echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
    echo '  <title>Singer Shop - Feed Google Shopping</title>' . PHP_EOL;
    echo '  <link rel="self" href="' . $baseUrl . '/feed/google-shopping"/>' . PHP_EOL;
    echo '  <updated>' . date('Y-m-d\TH:i:sP') . '</updated>' . PHP_EOL;
    echo '  <author>' . PHP_EOL;
    echo '    <name>Singer Shop</name>' . PHP_EOL;
    echo '  </author>' . PHP_EOL;
    echo '  <id>' . $baseUrl . '</id>' . PHP_EOL;

    foreach ($products as $product) {
      // Skip products without essential data
      if (empty($product['name']) || empty($product['price'])) {
        continue;
      }

      // Get category info if available
      $categorySlug = '';
      $categoryName = '';
      $googleCategory = $this->categoryMapping['default']; // Default category

      if (isset($product['category_id'])) {
        if (isset($categoryIdToSlug[$product['category_id']])) {
          $categorySlug = $categoryIdToSlug[$product['category_id']];
          $googleCategory = $this->getGoogleCategory($categorySlug);
        }

        if (isset($categoryIdToName[$product['category_id']])) {
          $categoryName = $categoryIdToName[$product['category_id']];
        }
      }

      // Get first image URL
      $imageUrl = '';
      if (isset($product['images']) && !empty($product['images'])) {
        $imageUrl = $baseUrl . '/assets/images/products/' . $product['images'][0];
      }

      // Get stock status
      $availability = 'in_stock';
      if (isset($product['stock']) && $product['stock'] <= 0) {
        $availability = 'out_of_stock';
      }

      // Generate unique ID
      $id = isset($product['sku']) ? $product['sku'] : 'SINGER-' . $product['id'];

      // Clean description
      $description = isset($product['description']) ? strip_tags($product['description']) : '';
      $description = html_entity_decode($description);
      // Truncate if needed (max 5000 characters for Google)
      if (strlen($description) > 5000) {
        $description = substr($description, 0, 4997) . '...';
      }

      // Generate entry ID for Atom feed
      $entryId = $baseUrl . '/product/' . $product['slug'];

      // Add product
      echo '  <entry>' . PHP_EOL;
      echo '    <g:id>' . htmlspecialchars($id) . '</g:id>' . PHP_EOL;
      echo '    <g:title>' . htmlspecialchars($product['name']) . '</g:title>' . PHP_EOL;
      echo '    <g:description>' . htmlspecialchars($description) . '</g:description>' . PHP_EOL;
      echo '    <g:link>' . $entryId . '</g:link>' . PHP_EOL;
      echo '    <g:image_link>' . htmlspecialchars($imageUrl) . '</g:image_link>' . PHP_EOL;

      // Add additional images if available
      if (isset($product['images']) && count($product['images']) > 1) {
        $additionalImages = array_slice($product['images'], 1, 10); // Google allows up to 10 additional images
        foreach ($additionalImages as $image) {
          $additionalImageUrl = $baseUrl . '/assets/images/products/' . $image;
          echo '    <g:additional_image_link>' . htmlspecialchars($additionalImageUrl) . '</g:additional_image_link>' . PHP_EOL;
        }
      }

      echo '    <g:availability>' . $availability . '</g:availability>' . PHP_EOL;

      // Handle regular price vs sale price
      if (isset($product['old_price']) && $product['old_price'] > $product['price']) {
        echo '    <g:price>' . number_format($product['old_price'], 2, '.', '') . ' EUR</g:price>' . PHP_EOL;
        echo '    <g:sale_price>' . number_format($product['price'], 2, '.', '') . ' EUR</g:sale_price>' . PHP_EOL;
      } else {
        echo '    <g:price>' . number_format($product['price'], 2, '.', '') . ' EUR</g:price>' . PHP_EOL;
      }

      // Add GTIN if available
      if (isset($product['gtin']) && !empty($product['gtin'])) {
        echo '    <g:gtin>' . htmlspecialchars($product['gtin']) . '</g:gtin>' . PHP_EOL;
      } else if (isset($product['ean']) && !empty($product['ean'])) {
        echo '    <g:gtin>' . htmlspecialchars($product['ean']) . '</g:gtin>' . PHP_EOL;
      }

      // Add MPN if available
      if (isset($product['sku']) && !empty($product['sku'])) {
        echo '    <g:mpn>' . htmlspecialchars($product['sku']) . '</g:mpn>' . PHP_EOL;
      }

      // Add brand
      echo '    <g:brand>Singer</g:brand>' . PHP_EOL;

      // Add condition
      echo '    <g:condition>new</g:condition>' . PHP_EOL;

      // Add category if available
      if (!empty($categoryName)) {
        echo '    <g:product_type>' . htmlspecialchars($categoryName) . '</g:product_type>' . PHP_EOL;
      }

      // Add Google product category
      echo '    <g:google_product_category>' . htmlspecialchars($googleCategory) . '</g:google_product_category>' . PHP_EOL;

      // Shipping information
      echo '    <g:shipping>' . PHP_EOL;
      echo '      <g:country>FR</g:country>' . PHP_EOL;
      echo '      <g:service>Standard</g:service>' . PHP_EOL;
      echo '      <g:price>10.00 EUR</g:price>' . PHP_EOL;
      echo '    </g:shipping>' . PHP_EOL;

      // Add shipping for orders over 300 EUR
      echo '    <g:shipping_label>Livraison gratuite dès 300€ d\'achat</g:shipping_label>' . PHP_EOL;

      // Add identifier_exists tag (required when GTIN and MPN are missing)
      if ((!isset($product['gtin']) || empty($product['gtin'])) &&
        (!isset($product['ean']) || empty($product['ean'])) &&
        (!isset($product['sku']) || empty($product['sku']))
      ) {
        echo '    <g:identifier_exists>false</g:identifier_exists>' . PHP_EOL;
      }

      // Custom labels
      if (isset($product['level'])) {
        echo '    <g:custom_label_0>' . htmlspecialchars($product['level']) . '</g:custom_label_0>' . PHP_EOL;
      }

      if (isset($product['featured']) && $product['featured']) {
        echo '    <g:custom_label_1>featured</g:custom_label_1>' . PHP_EOL;
      }

      // Atom required fields
      echo '    <id>' . $entryId . '</id>' . PHP_EOL;
      echo '    <title>' . htmlspecialchars($product['name']) . '</title>' . PHP_EOL;
      echo '    <link href="' . $entryId . '"/>' . PHP_EOL;
      echo '    <updated>' . date('Y-m-d\TH:i:sP') . '</updated>' . PHP_EOL;
      echo '    <summary>' . htmlspecialchars(substr($description, 0, 200)) . (strlen($description) > 200 ? '...' : '') . '</summary>' . PHP_EOL;

      echo '  </entry>' . PHP_EOL;
    }

    // End XML
    echo '</feed>';
    exit;
  }
}
