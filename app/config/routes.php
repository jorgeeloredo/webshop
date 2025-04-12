<?php
// app/config/routes.php

// Get the router from the app
$router = $this;

// Home routes
$router->get('/', 'HomeController', 'index');

// Product routes
$router->get('/product/{slug}', 'ProductController', 'show');
$router->get('/products', 'ProductController', 'index');

// Category routes
$router->get('/category/{slug}', 'CategoryController', 'show');
$router->get('/categories', 'CategoryController', 'index');

// Cart routes
$router->get('/cart', 'CartController', 'index');
$router->post('/cart/add', 'CartController', 'add');
$router->post('/cart/update', 'CartController', 'update');
$router->post('/cart/remove', 'CartController', 'remove');
$router->post('/cart/buy-now', 'CartController', 'buyNow');

// Authentication routes
$router->get('/login', 'AccountController', 'loginForm');
$router->post('/login', 'AccountController', 'login');
$router->get('/register', 'AccountController', 'registerForm');
$router->post('/register', 'AccountController', 'register');
$router->get('/logout', 'AccountController', 'logout');

// User account routes
$router->get('/account', 'AccountController', 'dashboard');
$router->get('/account/profile', 'AccountController', 'profile');
$router->post('/account/profile', 'AccountController', 'updateProfile');

// Account addresses routes
$router->get('/account/addresses', 'AccountController', 'addresses');
$router->post('/account/addresses/add', 'AccountController', 'addAddress');
$router->post('/account/addresses/update', 'AccountController', 'updateAddress');
$router->post('/account/addresses/delete', 'AccountController', 'deleteAddress');

// Order routes
$router->get('/account/orders', 'OrderController', 'index');
$router->get('/account/orders/{id}', 'OrderController', 'show');
$router->post('/checkout', 'OrderController', 'checkout');
$router->get('/checkout', 'OrderController', 'checkout');
$router->get('/checkout/success', 'OrderController', 'success');
$router->get('/sitemap.xml', 'SitemapController', 'index');

// Route for static pages
$router->get('/page/{slug}', 'PageController', 'show');
