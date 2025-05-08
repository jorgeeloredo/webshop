<?php
// public/index.php

// Set error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the application root directory
define('APP_ROOT', dirname(__DIR__));

// Load Composer's autoloader
require_once APP_ROOT . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->safeLoad(); // Won't error if .env is missing

// Initialize language helper
\App\Helpers\Language::getInstance();

// Manual class loading if autoload fails
if (!class_exists('App\Core\App')) {
  require_once APP_ROOT . '/app/core/App.php';
}

// Try using a different case for the directory
if (!class_exists('App\Core\App')) {
  require_once APP_ROOT . '/app/Core/App.php';
}

// Initialize the application
$app = App\Core\App::getInstance();

// Bind essential services to the container
App\Core\App::bind('database', App\Core\Database::getInstance());

// Load routes
$app->getRouter()->loadRoutes(APP_ROOT . '/app/config/routes.php');

// Run the application
$app->run();
