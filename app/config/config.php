<?php
// app/config/config.php

return [
  'app' => [
    'name' => 'Singer Shop',
    'url' => $_ENV['APP_URL'],
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
  ],

  'language' => [
    'default' => 'en',     // Default language (fr or en)
    'available' => ['fr', 'en'], // Available languages
  ],

  'database' => [
    'host' => $_ENV['DB_HOST'],
    'name' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
  ],

  'mail' => [
    'host' => $_ENV['MAIL_HOST'] ?? '',
    'port' => $_ENV['MAIL_PORT'] ?? 587,
    'username' => $_ENV['MAIL_USERNAME'] ?? '',
    'password' => $_ENV['MAIL_PASSWORD'] ?? '',
    'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@singer-fr.com',
    'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'Singer France',
    'zeptomail_api_key' => $_ENV['ZEPTOMAIL_API_KEY'] ?? '',
  ],

  'session' => [
    'name' => 'singer_session',
    'lifetime' => 7200, // 2 hours
    'secure' => true,
    'httponly' => true,
  ],

  'products' => [
    'data_path' => __DIR__ . '/../../data/products',
    'images_path' => '/assets/images/products',
  ],

  'categories' => [
    'data_path' => __DIR__ . '/../../data/categories',
  ],

  'pagination' => [
    'items_per_page' => 12,
  ],
];
