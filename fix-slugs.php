<?php
// sanitize_slugs.php

// Load the products.json file
$jsonFile = '/var/www/html/singer3/data/products/products.json';
$products = json_decode(file_get_contents($jsonFile), true);

// Sanitize slugs
foreach ($products as &$product) {
  if (isset($product['slug'])) {
    // Get the original slug
    $slug = $product['slug'];

    // Create a transliteration map for accented characters
    $transliterationMap = [
      'à' => 'a',
      'á' => 'a',
      'â' => 'a',
      'ä' => 'a',
      'ã' => 'a',
      'å' => 'a',
      'ą' => 'a',
      'è' => 'e',
      'é' => 'e',
      'ê' => 'e',
      'ë' => 'e',
      'ę' => 'e',
      'ì' => 'i',
      'í' => 'i',
      'î' => 'i',
      'ï' => 'i',
      'ò' => 'o',
      'ó' => 'o',
      'ô' => 'o',
      'õ' => 'o',
      'ö' => 'o',
      'ø' => 'o',
      'ù' => 'u',
      'ú' => 'u',
      'û' => 'u',
      'ü' => 'u',
      'ý' => 'y',
      'ÿ' => 'y',
      'ñ' => 'n',
      'ç' => 'c',
      'ß' => 'ss',
      ' ' => '-',
      ':' => '-',
      '/' => '-',
      '\\' => '-'
    ];

    // Apply the transliteration
    $slug = strtr($slug, $transliterationMap);

    // Replace other special characters with hyphens
    $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);

    // Replace multiple consecutive hyphens with a single one
    $slug = preg_replace('/-+/', '-', $slug);

    // Trim hyphens from the beginning and end
    $slug = trim($slug, '-');

    // Save the updated slug
    $product['slug'] = $slug;

    // Output the changes for verification
    echo "Original: {$product['slug']} -> New: {$slug}\n";
  }
}

// Save the updated file
file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Slugs sanitized successfully!\n";
