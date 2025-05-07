<?php
// app/models/Category.php
namespace App\Models;

class Category
{
  private string $dataPath;
  private array $categories = [];

  public function __construct()
  {
    $config = require __DIR__ . '/../config/config.php';
    $this->dataPath = $config['categories']['data_path'];
    $this->loadCategories();
  }

  private function loadCategories()
  {
    $jsonFile = $this->dataPath . '/categories.json';

    if (file_exists($jsonFile)) {
      $this->categories = json_decode(file_get_contents($jsonFile), true) ?? [];
    }
  }

  public function getAll()
  {
    return $this->categories;
  }

  public function find($id)
  {
    foreach ($this->categories as $category) {
      if ($category['id'] == $id) {
        return $category;
      }
    }

    return null;
  }

  public function findBySlug($slug)
  {
    foreach ($this->categories as $category) {
      if (isset($category['slug']) && $category['slug'] === $slug) {
        return $category;
      }
    }

    return null;
  }

  public function save($category)
  {
    // Find if category already exists
    $found = false;
    foreach ($this->categories as $key => $existingCategory) {
      if ($existingCategory['id'] == $category['id']) {
        $this->categories[$key] = $category;
        $found = true;
        break;
      }
    }

    // If not found, add as new
    if (!$found) {
      // Generate ID if not provided
      if (!isset($category['id'])) {
        $maxId = 0;
        foreach ($this->categories as $c) {
          if ($c['id'] > $maxId) {
            $maxId = $c['id'];
          }
        }
        $category['id'] = $maxId + 1;
      }

      // Generate slug if not provided
      if (!isset($category['slug'])) {
        $category['slug'] = $this->generateSlug($category['name']);
      }

      $this->categories[] = $category;
    }

    // Save to file
    $this->saveToFile();

    return $category['id'];
  }

  public function delete($id)
  {
    foreach ($this->categories as $key => $category) {
      if ($category['id'] == $id) {
        unset($this->categories[$key]);
        $this->saveToFile();
        return true;
      }
    }

    return false;
  }

  private function saveToFile()
  {
    $jsonFile = $this->dataPath . '/categories.json';
    file_put_contents($jsonFile, json_encode($this->categories, JSON_PRETTY_PRINT));
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
