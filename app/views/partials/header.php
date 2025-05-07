<?php
// app/views/partials/header.php (Modified to use translations)

// Get cart items if Cart model is available
$cartCount = 0;
try {
  $cartModel = new \App\Models\Cart();
  $cartCount = $cartModel->getTotalQuantity();
} catch (\Exception $e) {
  // Silently fail
}

// Check if user is logged in
$isLoggedIn = \App\Helpers\Auth::check();
$user = $isLoggedIn ? \App\Helpers\Auth::user() : null;

// Load navigation configuration
$navigation = require __DIR__ . '/../../config/navigation.php';
$mainMenu = $navigation['main_menu'];

// Determine current page for active menu item
$currentUrl = $_SERVER['REQUEST_URI'];

// Default meta description if none is provided
$metaDescription = isset($metaDescription) ? $metaDescription : __('general.meta_description');

// Default meta keywords if none are provided
$metaKeywords = isset($metaKeywords) ? $metaKeywords : __('general.meta_keywords');

// Canonical URL to prevent duplicate content issues
$canonicalUrl = isset($canonicalUrl) ? $canonicalUrl : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="<?= get_language() ?>">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <title><?= isset($title) ? $title . ' | Singer ' . (get_language() === 'fr' ? 'France' : 'Shop') : 'Singer ' . (get_language() === 'fr' ? 'France' : 'Shop') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
  <meta name="keywords" content="<?= htmlspecialchars($metaKeywords) ?>">
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">

  <!-- Open Graph tags for social sharing -->
  <meta property="og:title" content="<?= isset($title) ? htmlspecialchars($title) . ' - Singer Shop' : 'Singer Shop' ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Singer Shop">
  <?php if (isset($ogImage)): ?>
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
  <?php else: ?>
    <meta property="og:image" content="/assets/images/logo.png">
  <?php endif; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
      font-family: 'Poppins', Arial, sans-serif;
    }

    .singer-red {
      background-color: #c63437;
    }

    .singer-red-text {
      color: #c63437;
    }

    .singer-red-border {
      border-color: #c63437;
    }

    .singer-bg-light {
      background-color: #fff9f5;
    }

    .price-color {
      color: #b86c49;
      /* Brownish-orange color for the price */
      background-color: #fdf7f1;
      padding: 1px 5px 2px;
    }

    .custom-input:focus {
      outline: none;
      border-color: #c63437;
    }

    .site-container {
      max-width: 2300px;
      margin-left: auto;
      margin-right: auto;
      width: 100%;
    }

    .product-image-bg {
      background-color: #fff4ee;
    }

    .thumbnail-active {
      border-color: #c63437;
    }

    .green-badge {
      background-color: #5a8d3f;
    }

    /* Dropdown menu styles */
    .desktop-menu-item {
      position: relative;
    }

    .desktop-submenu {
      display: none;
      position: absolute;
      left: 0;
      top: 100%;
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      width: 600px;
      /* Increased from 400px to 600px */
      z-index: 50;
      padding: 1.5rem;
      /* Increased padding */
    }

    .desktop-menu-item:hover .desktop-submenu {
      display: block;
    }

    .submenu-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      /* 2 columns grid */
      gap: 1rem;
    }

    .submenu-item {
      display: flex;
      flex-direction: column;
      /* Changed to column for larger images */
      align-items: center;
      padding: 0.75rem;
      border-radius: 0.25rem;
      transition: all 0.2s;
      text-align: center;
    }

    .submenu-item:hover {
      background-color: #fff9f5;
    }

    .submenu-image {
      width: 120px;
      /* Increased from 60px to 120px */
      height: 120px;
      /* Increased from 60px to 120px */
      object-fit: contain;
      background-color: #fff9f5;
      border-radius: 0.25rem;
      margin-bottom: 0.75rem;
      /* Margin bottom instead of right */
      padding: 0.5rem;
    }

    .submenu-links {
      margin-top: 1.5rem;
      padding-top: 1rem;
      border-top: 1px solid #f1f1f1;
      display: flex;
      justify-content: space-between;
    }

    /* Mobile menu improvements */
    .mobile-submenu {
      background-color: #f9f9f9;
    }

    .pagecontent h3 {
      font-size: 25px;
      font-family: La Belle Aurore, cursive;
      font-weight: 400;
      line-height: 37px;
    }
  </style>
  <!-- Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-P4GLVBPJ');
  </script>
  <!-- End Google Tag Manager -->
</head>

<body class="bg-white">
  <!-- Non-fixed promotional bar at the top (full-width background) -->
  <div class="singer-red text-white text-center text-sm font-medium py-1.5">
    <div class="site-container"><?= __('general.top_text', ['amount' => '300']) ?></div>
  </div>

  <!-- Sticky/fixed header that adjusts position on scroll (full-width background) -->
  <header class="sticky top-0 z-40 w-full bg-white shadow-md">
    <div class="site-container flex items-center justify-between px-4 h-[50px] lg:h-[64px]">
      <!-- Logo -->
      <a href="/" class="flex-shrink-0">
        <img src="/assets/images/logo.png" alt="Singer Logo" class="h-6 lg:h-8" />
      </a>

      <!-- Navigation - hidden on mobile -->
      <nav class="hidden space-x-6 text-sm font-light lg:flex">
        <?php foreach ($mainMenu as $item): ?>
          <div class="desktop-menu-item">
            <a
              href="<?= $item['url'] ?>"
              class="font-normal text-gray-800 hover:text-red-600 <?= isset($item['special_class']) ? $item['special_class'] : '' ?> <?= strpos($currentUrl, $item['url']) === 0 ? 'text-red-600' : '' ?>">
              <?= htmlspecialchars($item['name']) ?>
            </a>
            <?php if (!empty($item['submenu'])): ?>
              <div class="desktop-submenu">
                <div class="submenu-grid">
                  <?php foreach ($item['submenu'] as $subitem): ?>
                    <a href="<?= $subitem['url'] ?>" class="submenu-item">
                      <img src="<?= $subitem['image'] ?>" alt="<?= htmlspecialchars($subitem['name']) ?>" class="submenu-image">
                      <span class="font-medium"><?= htmlspecialchars($subitem['name']) ?></span>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </nav>

      <!-- Right side controls -->
      <div class="flex items-center">
        <form action="/search" method="GET" class="flex items-center">
          <input
            type="text"
            name="q"
            placeholder="<?= __('general.search') ?>"
            class="hidden h-8 px-3 py-1 mr-2 text-sm border border-gray-300 rounded md:block custom-input" />
          <button type="submit" class="flex items-center justify-center w-8 h-8 text-gray-600">
            <i class="fas fa-search"></i>
          </button>
        </form>
        <a href="<?= $isLoggedIn ? '/account' : '/login' ?>" class="flex items-center ml-4">
          <i class="text-gray-600 fas fa-user"></i>
        </a>
        <a href="/cart" class="flex items-center ml-4">
          <i class="text-gray-600 fas fa-shopping-cart"></i>
          <span class="ml-1 text-xs text-gray-600">(<?= $cartCount ?>)</span>
        </a>
        <button class="flex items-center justify-center w-8 h-8 ml-4 text-gray-600 lg:hidden" id="mobile-menu-button">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile menu (hidden by default) -->
  <div id="mobile-menu" class="fixed inset-0 z-50 hidden bg-white">
    <div class="flex flex-col h-full">
      <div class="flex items-center justify-between px-4 h-[50px]">
        <a href="/" class="flex-shrink-0">
          <img src="/assets/images/logo.png" alt="Singer Logo" class="h-6" />
        </a>
        <button id="close-mobile-menu" class="flex items-center justify-center w-8 h-8 text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="flex-1 px-4 py-6 overflow-y-auto">
        <div class="mb-6">
          <form action="/search" method="GET" class="flex">
            <input
              type="text"
              name="q"
              placeholder="<?= __('general.search') ?>"
              class="flex-1 h-10 px-3 border border-gray-300 rounded-l custom-input" />
            <button type="submit" class="flex items-center justify-center w-10 text-white rounded-r singer-red">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>

        <nav>
          <div id="mobile-main-menu">
            <?php foreach ($mainMenu as $index => $item): ?>
              <?php if (!empty($item['submenu'])): ?>
                <!-- Item with submenu -->
                <div class="border-b border-gray-200">
                  <div class="flex items-center justify-between py-4" data-submenu="<?= $index ?>">
                    <span class="font-medium text-gray-800 <?= isset($item['special_class']) ? $item['special_class'] : '' ?>">
                      <?= htmlspecialchars($item['name']) ?>
                    </span>
                    <i class="text-gray-500 fas fa-chevron-right"></i>
                  </div>
                </div>
              <?php else: ?>
                <!-- Regular item -->
                <div class="border-b border-gray-200">
                  <a href="<?= $item['url'] ?>" class="flex items-center py-4">
                    <span class="font-medium text-gray-800 <?= isset($item['special_class']) ? $item['special_class'] : '' ?>">
                      <?= htmlspecialchars($item['name']) ?>
                    </span>
                  </a>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <?php foreach ($mainMenu as $index => $item): ?>
            <?php if (!empty($item['submenu'])): ?>
              <!-- Submenu container (hidden initially) -->
              <div id="submenu-<?= $index ?>" class="hidden">
                <div class="flex items-center py-4 mb-2 border-b border-gray-200">
                  <button class="mr-2 back-to-main">
                    <i class="text-gray-500 fas fa-chevron-left"></i>
                  </button>
                  <span class="font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></span>
                </div>

                <?php foreach ($item['submenu'] as $subitem): ?>
                  <a href="<?= $subitem['url'] ?>" class="flex items-center py-3">
                    <div class="flex-shrink-0 w-12 h-12 mr-3 overflow-hidden rounded">
                      <img src="<?= $subitem['image'] ?>" alt="<?= htmlspecialchars($subitem['name']) ?>" class="object-contain w-full h-full bg-gray-50">
                    </div>
                    <span class="text-gray-800"><?= htmlspecialchars($subitem['name']) ?></span>
                  </a>
                <?php endforeach; ?>

              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </nav>

        <div class="mt-8 space-y-4">
          <a href="<?= $isLoggedIn ? '/account' : '/login' ?>" class="flex items-center text-gray-800">
            <i class="w-8 mr-2 text-center fas fa-user"></i>
            <span><?= $isLoggedIn ? __('general.my_account') : __('general.login') ?></span>
          </a>
          <a href="/cart" class="flex items-center text-gray-800">
            <i class="w-8 mr-2 text-center fas fa-shopping-cart"></i>
            <span><?= __('general.cart') ?> (<?= $cartCount ?>)</span>
          </a>
          <?php if ($isLoggedIn): ?>
            <a href="/logout" class="flex items-center text-gray-800">
              <i class="w-8 mr-2 text-center fas fa-sign-out-alt"></i>
              <span><?= __('general.logout') ?></span>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <main class="min-h-screen">