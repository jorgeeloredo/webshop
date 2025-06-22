<?php
// app/views/home/index.php
use App\Helpers\ImageHelper;
$reviewModel = new \App\Models\Review();
?>



<!-- Category Navigation -->
<section class="py-12 mx-auto bg-secondary-light">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-center text-gray-800"><?= __('home.categories_title') ?></h2>

    <div class="grid gap-4 mx autogrid-cols-2 md:grid-cols-3 lg:grid-cols-6">
      <?php foreach ($categories as $category): ?>
        <a href="/category/<?= $category['slug'] ?>" class="group">
          <div class="mb-2 overflow-hidden bg-white border border-gray-200 rounded-lg aspect-square">
            <?php if (isset($category['image']) && !empty($category['image'])): ?>
              <img src="<?= ImageHelper::getImageUrl($category['image'], 'thumbnail', ['width' => 300, 'height' => 300]) ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
            <?php else: ?>
              <div class="flex items-center justify-center w-full h-full bg-gray-100">
                <span class="text-gray-400 fas fa-image fa-3x"></span>
              </div>
            <?php endif; ?>
          </div>
          <p class="text-sm font-medium text-center text-gray-800 group-hover:text-primary"><?= htmlspecialchars($category['name']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="py-12">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-gray-800"><?= __('home.featured_products') ?></h2>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($featuredProducts as $product): ?>
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
          <a href="/product/<?= $product['slug'] ?>" class="block">
            <!-- Updated image container with fixed aspect ratio -->
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
          <!--<div class="px-4 pb-4">
            <form action="/cart/buy-now" method="POST">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button
                type="submit"
                class="w-full py-2 text-sm text-white transition rounded-full bg-primary hover:bg-primary-hover"
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? __('general.unavailable') : __('listing.buy_now') ?>
              </button>
            </form>
          </div>-->
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-8 text-center">
      <a href="/products" class="px-6 py-3 transition border rounded-full singer-red-text border-primary hover:bg-primary hover:text-white"><?= __('listing.all_products') ?></a>
    </div>
  </div>
</section>

<!-- Brand Story Section -->
<section class="py-12 bg-white">
  <div class="px-4 site-container">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <div>
        <h2 class="mb-4 text-2xl font-normal text-gray-800"><?= __('home.brand_story_title') ?></h2>
        <p class="mb-4 text-gray-700"><?= __('home.brand_story_p1') ?></p>
        <p class="mb-6 text-gray-700"><?= __('home.brand_story_p2') ?></p>
        <a href="/page/la-marque" class="inline-flex items-center text-sm singer-red-text hover:underline">
          <?= __('home.discover_history') ?>
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>
      <div>
        <img src="/assets/images/singer-heritage.jpg" alt="Histoire Singer" class="object-cover w-full h-full rounded-lg shadow-md">
      </div>
    </div>
  </div>
</section>

<!-- Tutorials and Tips Section -->
<section class="w-full py-12 mt-0 bg-[<?= get_color('secondary_light') ?>]">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-center text-gray-800"><?= __('home.tutorials_title') ?></h2>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
      <!-- First tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto1.jpg" alt="<?= __('home.tutorial1_title') ?>" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800"><?= __('home.tutorial1_title') ?></h3>
          <p class="mb-4 text-sm text-gray-700"><?= __('home.tutorial1_desc') ?></p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            <?= __('home.discover_tutorial') ?>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Second tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto2.jpg" alt="<?= __('home.tutorial2_title') ?>" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800"><?= __('home.tutorial2_title') ?></h3>
          <p class="mb-4 text-sm text-gray-700"><?= __('home.tutorial2_desc') ?></p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            <?= __('home.discover_tutorial') ?>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Third tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto3.jpg" alt="<?= __('home.tutorial3_title') ?>" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800"><?= __('home.tutorial3_title') ?></h3>
          <p class="mb-4 text-sm text-gray-700"><?= __('home.tutorial3_desc') ?></p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            <?= __('home.discover_tutorial') ?>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div class="mt-8 text-center">
      <a href="#" class="px-6 py-3 transition border rounded-full singer-red-text border-primary hover:bg-primary hover:text-white"><?= __('home.all_tutorials') ?></a>
    </div>
  </div>
</section>

<!-- Newsletter Section -->
<section class="py-12 bg-gray-100">
  <div class="px-4 site-container">
    <div class="max-w-2xl p-8 mx-auto text-center bg-white rounded-lg shadow-sm">
      <h2 class="mb-2 text-2xl font-normal text-gray-800"><?= __('home.stay_inspired') ?></h2>
      <p class="mb-6 text-gray-700"><?= __('home.newsletter_desc') ?></p>

      <form class="flex flex-col sm:flex-row">
        <input type="email" placeholder="<?= __('home.email_placeholder') ?>" class="flex-1 px-4 py-2 mb-2 border border-gray-300 rounded-l sm:mb-0 focus:outline-none focus:ring-2 focus:ring-primary">
        <button type="submit" class="px-6 py-2 font-medium text-white transition rounded-r bg-primary hover:bg-primary-hover"><?= __('home.subscribe') ?></button>
      </form>
    </div>
  </div>
</section>