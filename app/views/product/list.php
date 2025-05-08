<?php
// app/views/product/list.php

// Function to generate image URL
function getImageUrl($image)
{
  return '/assets/images/products/' . $image;
}
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
      <a href="/products" class="px-6 py-3 text-white transition rounded-full singer-red hover:bg-red-700">
        <?= __('listing.view_all_products') ?>
      </a>
    </div>
  <?php else: ?>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
      <?php foreach ($products as $product): ?>
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
          <a href="/product/<?= $product['slug'] ?>" class="block">
            <!-- Updated image container with fixed aspect ratio -->
            <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
              <?php if (isset($product['images']) && !empty($product['images'])): ?>
                <img
                  src="<?= getImageUrl($product['images'][0]) ?>"
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
          <!--<div class="px-4 pb-4">
            <form action="/cart/buy-now" method="POST">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button
                type="submit"
                class="w-full py-2 text-sm text-white transition rounded-full singer-red hover:bg-red-700"
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? __('general.unavailable') : __('listing.buy_now') ?>
              </button>
            </form>
          </div>-->
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>