<?php
// app/views/product/list.php

// Use ImageHelper for thumbnails
use App\Helpers\ImageHelper;

// Initialize Review model to get ratings
$reviewModel = new \App\Models\Review();
?>

<div class="px-4 py-8 site-container">
  <div class="mb-6">
    <h1 class="text-2xl font-normal text-gray-800">
      <?= isset($category) ? htmlspecialchars($category['name']) : __('listing.all_products') ?>
    </h1>
    <?php if (isset($category) && !empty($category['description'])): ?>
      <p class="mt-2 text-sm text-gray-600"><?= htmlspecialchars($category['description']) ?></p>
    <?php endif; ?>
  </div>

  <?php if (empty($products)): ?>
    <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
      <div class="flex justify-center mb-4">
        <i class="text-5xl text-gray-300 fas fa-box-open"></i>
      </div>
      <h2 class="mb-2 text-xl font-medium text-gray-800"><?= __('listing.no_products_found') ?></h2>
      <p class="mb-6 text-gray-600"><?= __('listing.no_products_in_category') ?></p>
      <a href="/products" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
        <?= __('listing.view_all_products') ?>
      </a>
    </div>
  <?php else: ?>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      <?php foreach ($products as $product): ?>
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
          <a href="/product/<?= $product['slug'] ?>" class="block">
            <!-- Updated image container with fixed aspect ratio and optimized thumbnails -->
            <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
              <?php if (isset($product['images']) && !empty($product['images'])): ?>
                <img
                  src="<?= ImageHelper::getImageUrl($product['images'][0], 'thumbnail') ?>"
                  alt="<?= htmlspecialchars($product['name']) ?>"
                  class="absolute top-0 left-0 object-contain w-full h-full">
              <?php else: ?>
                <div class="absolute top-0 left-0 flex items-center justify-center w-full h-full">
                  <span class="text-gray-400 fas fa-image fa-3x"></span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <h3 class="mb-2 text-sm font-medium text-gray-800"><?= htmlspecialchars($product['name']) ?></h3>
              <div class="flex items-center mt-1 mb-2">
                <div class="flex">
                  <?php
                  $averageRating = $reviewModel->getAverageRating($product['id']);
                  $reviewCount = $reviewModel->getReviewCount($product['id']);
                  for ($i = 1; $i <= 5; $i++):
                  ?>
                    <span class="<?= $i <= round($averageRating) ? 'text-yellow-400 fas' : 'text-gray-300 far' ?> fa-star text-sm"></span>
                  <?php endfor; ?>
                </div>
                <span class="ml-2 text-xs text-gray-600"><?= $reviewCount ?> <?= __('general.reviews') ?></span>
              </div>
              <div class="flex items-baseline">
                <span class="mr-2 text-lg font-semibold price-color"><?= number_format($product['price'], 2, ',', ' ') ?> €</span>
                <?php if (isset($product['old_price']) && $product['old_price'] > 0): ?>
                  <span class="text-sm text-gray-500 line-through"><?= number_format($product['old_price'], 2, ',', ' ') ?> €</span>
                <?php endif; ?>
              </div>

              <?php if (isset($product['level'])): ?>
                <div class="mt-2">
                  <span class="inline-block px-2 py-1 text-xs text-white bg-green-600 rounded">
                    <?= __('listing.product_level') ?> <?= htmlspecialchars($product['level']) ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>