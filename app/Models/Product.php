<?php
// app/models/Product.php
namespace App\Models;

use Symfony\Component\Yaml\Yaml;

class Product
{
  private string $dataPath;
  private array $products = [];

  public function __construct()
  {
    $config = require __DIR__ . '/../config/config.php';
    $this->dataPath = $config['products']['data_path'];
    $this->loadProducts();
  }

  private function loadProducts()
  {
    $jsonFile = $this->dataPath . '/products.json';

    if (file_exists($jsonFile)) {
      $this->products = json_decode(file_get_contents($jsonFile), true) ?? [];
    }
  }

  public function getAll()
  {
    return $this->products;
  }

  public function find($id)
  {
    foreach ($this->products as $product) {
      if ($product['id'] == $id) {
        return $product;
      }
    }

    return null;
  }

  public function findBySlug($slug)
  {
    foreach ($this->products as $product) {
      if (isset($product['slug']) && $product['slug'] === $slug) {
        return $product;
      }
    }

    return null;
  }

  public function getByCategory($categoryId)
  {
    $result = [];

    foreach ($this->products as $product) {
      if (isset($product['category_id']) && $product['category_id'] == $categoryId) {
        $result[] = $product;
      }
    }

    return $result;
  }

  public function search($query)
  {
    $result = [];
    $query = strtolower($query);

    foreach ($this->products as $product) {
      if (
        stripos($product['name'], $query) !== false ||
        stripos($product['description'], $query) !== false
      ) {
        $result[] = $product;
      }
    }

    return $result;
  }

  public function save($product)
  {
    // Find if product already exists
    $found = false;
    foreach ($this->products as $key => $existingProduct) {
      if ($existingProduct['id'] == $product['id']) {
        $this->products[$key] = $product;
        $found = true;
        break;
      }
    }

    // If not found, add as new
    if (!$found) {
      // Generate ID if not provided
      if (!isset($product['id'])) {
        $maxId = 0;
        foreach ($this->products as $p) {
          if ($p['id'] > $maxId) {
            $maxId = $p['id'];
          }
        }
        $product['id'] = $maxId + 1;
      }

      // Generate slug if not provided
      if (!isset($product['slug'])) {
        $product['slug'] = $this->generateSlug($product['name']);
      }

      $this->products[] = $product;
    }

    // Save to file
    $this->saveToFile();

    return $product['id'];
  }

  public function delete($id)
  {
    foreach ($this->products as $key => $product) {
      if ($product['id'] == $id) {
        unset($this->products[$key]);
        $this->saveToFile();
        return true;
      }
    }

    return false;
  }

  private function saveToFile()
  {
    $jsonFile = $this->dataPath . '/products.json';
    file_put_contents($jsonFile, json_encode($this->products, JSON_PRETTY_PRINT));
  }

  private function generateSlug($name)
  {
    // Convert to lowercase and replace spaces with hyphens
    $slug = strtolower(str_replace(' ', '-', $name));

    // Remove special characters
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

    // Remove duplicate hyphens
    $slug = preg_replace('/-+/', '-', $slug);

    // Check if slug already exists
    $originalSlug = $slug;
    $counter = 1;

    while ($this->findBySlug($slug)) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }
}
