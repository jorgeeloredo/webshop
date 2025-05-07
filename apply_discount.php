<?php
// Read the original products.json file
$jsonContent = file_get_contents('data/products/products.json');
$products = json_decode($jsonContent, true);

// Apply 20% discount to products over $500
foreach ($products as &$product) {
  if ($product['price'] > 500) {
    $product['old_price'] = $product['price'];
    $product['price'] = round($product['price'] * 0.8); // 20% discount, rounded to nearest integer
  }
}

// Write the updated product list to products_discounted.json
file_put_contents('data/products/products.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

// Count how many products were discounted
$discountedCount = 0;
foreach ($products as $product) {
  if (isset($product['old_price']) && $product['old_price'] !== null) {
    $discountedCount++;
  }
}

echo "Process completed successfully.\n";
echo "Total products: " . count($products) . "\n";
echo "Products discounted: " . $discountedCount . "\n";
echo "Updated product list saved to products_discounted.json\n";
