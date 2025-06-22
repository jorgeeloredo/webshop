<?php
// app/views/product/index.php

// Use ImageHelper for thumbnails
use App\Helpers\ImageHelper;

// Function to generate image URL
function getImageUrl($image)
{
  return '/assets/images/products/' . $image;
}
$featuresCount = 0;
// Get product features in two columns
if (isset($product['features']) && !empty($product['features'])) {
  $featuresCount = count($product['features']);
  $featuresFirstColumn = array_slice($product['features'], 0, ceil($featuresCount / 2));
  $featuresSecondColumn = array_slice($product['features'], ceil($featuresCount / 2));
}
$config = require __DIR__ . '/../../config/config.php';
?>

<!-- Main Product Content -->
<div class="px-4 pb-12 site-container">
  <!-- Product Display Row -->
  <div class="grid grid-cols-1 gap-8 mb-12 md:grid-cols-2">
    <!-- Left: Product Gallery -->
    <div class="relative p-4 rounded-lg product-image-bg">
      <!-- Navigation Buttons positioned inside the product image -->
      <div class="absolute z-10 top-4 left-4">
        <a href="javascript:history.back()" class="flex items-center text-sm text-gray-600 hover:text-primary">
          <i class="mr-1 text-xs fas fa-chevron-left"></i> <?= __('product.back') ?>
        </a>
      </div>
      <div class="absolute z-10 top-4 right-4">
        <span class="px-3 py-1 text-xs text-white rounded green-badge">
          <?= __('product.product_level') ?> <?= $product['level'] ?? __('product.beginner') ?>
        </span>
      </div>

      <!-- Main Image -->
      <div class="relative pt-8">
        <img
          src="<?= ImageHelper::getImageUrl($product['images'][0], 'original') ?>"
          alt="<?= htmlspecialchars($product['name']) ?>"
          class="object-contain w-full h-auto mx-auto"
          id="mainProductImage" />

        <?php if (count($product['images']) > 1): ?>
          <!-- Navigation Arrows -->
          <button
            class="absolute left-0 flex items-center justify-center w-8 h-8 transform -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-sm top-1/2"
            id="prev-image">
            <i class="text-gray-600 fas fa-chevron-left"></i>
          </button>
          <button
            class="absolute right-0 flex items-center justify-center w-8 h-8 transform -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-sm top-1/2"
            id="next-image">
            <i class="text-gray-600 fas fa-chevron-right"></i>
          </button>
        <?php endif; ?>
      </div>

      <?php if (count($product['images']) > 1): ?>
        <!-- Thumbnails (use smaller optimized images) -->
        <div class="grid grid-cols-6 gap-2 mt-4">
          <?php foreach ($product['images'] as $index => $image): ?>
            <div class="border-2 <?= $index === 0 ? 'border-[' . get_color('primary') . '] thumbnail-active' : 'border-gray-200' ?> rounded cursor-pointer thumbnail" data-index="<?= $index ?>">
              <img src="<?= ImageHelper::getImageUrl($image, 'thumbnail', ['width' => 140, 'height' => 140]) ?>" alt="Vue <?= $index + 1 ?>" class="object-cover w-full" />
            </div>
          <?php endforeach; ?>

          <?php
          // Add empty thumbnails if less than 6 images
          for ($i = count($product['images']); $i < 6; $i++):
          ?>
            <div class="bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
              <div style="aspect-ratio: 1 / 1"></div>
            </div>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right: Product Information - vertically centered with flex -->
    <div class="flex flex-col justify-center">
      <!-- Product Title & Price -->
      <h1 class="mb-4 text-2xl font-normal text-gray-800"><?= htmlspecialchars($product['name']) ?></h1>


      <div class="mb-6">
        <div class="flex items-baseline">
          <span class="mr-2 text-xl font-semibold price-color"><?= number_format($product['price'], 2, ',', ' ') ?> €</span>
          <?php if (isset($product['eco_part']) && $product['eco_part'] > 0): ?>
            <span class="text-sm text-gray-500"><?= __('product.eco_part', ['amount' => number_format($product['eco_part'], 2, ',', ' ')]) ?></span>
          <?php endif; ?>
        </div>

        <?php if (isset($product['old_price']) && $product['old_price'] > 0): ?>
          <div class="mt-2">
            <span class="text-sm text-gray-500 line-through"><?= number_format($product['old_price'], 2, ',', ' ') ?> €</span>
            <span class="ml-2 text-sm font-medium text-green-600">
              -<?= round((($product['old_price'] - $product['price']) / $product['old_price']) * 100) ?>%
            </span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Product Description -->
      <div class="mb-8">
        <div class="text-sm leading-relaxed text-gray-700">
          <?= $product['description'] ?>
        </div>
      </div>

      <!-- Product identifiers -->
      <div class="mb-6 text-sm text-gray-600">
        <?php if (isset($product['sku'])): ?>
          <p><?= __('product.reference') ?> : <?= htmlspecialchars($product['sku']) ?></p>
        <?php endif; ?>
        <?php if (isset($product['gtin'])): ?>
          <p>GTIN : <?= htmlspecialchars($product['gtin']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Stock Information -->
      <div class="mb-6">
        <?php if (isset($product['stock']) && $product['stock'] > 0): ?>
          <div class="flex items-center text-green-600">
            <i class="mr-2 fas fa-check-circle"></i>
            <span class="text-sm font-medium"><?= __('product.in_stock') ?></span>
            <?php if ($product['stock'] < 5): ?>
              <span class="ml-2 text-sm">(<?= __('product.only_left', ['count' => $product['stock']]) ?>)</span>
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="flex items-center text-primary">
            <i class="mr-2 fas fa-times-circle"></i>
            <span class="text-sm font-medium"><?= __('product.out_of_stock') ?></span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Add to Cart Form -->
      <!--<form action="/cart/add" method="POST" class="mb-6">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

        <div class="flex items-center mb-4">
          <label for="quantity" class="mr-4 text-sm font-medium text-gray-700"><?= __('product.quantity') ?></label>
          <div class="flex items-center border border-gray-300 rounded">
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease">
              <i class="fas fa-minus"></i>
            </button>
            <input
              type="number"
              name="quantity"
              id="quantity"
              value="1"
              min="1"
              max="<?= $product['stock'] ?? 10 ?>"
              class="w-12 py-1 text-center border-gray-300 border-x">
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <button
          type="submit"
          class="bg-primary border-primary w-full md:w-[365px] py-3 mb-6 text-white transition rounded-3xl border hover:bg-white hover:text-primary"
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? __('product.unavailable') : __('product.add_to_cart') ?>
        </button>
      </form>-->

      <form action="/cart/buy-now" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="quantity" value="1">
        <button
          type="submit"
          class="bg-primary border-primary w-full md:w-[365px] py-3 mb-6 text-white transition rounded-3xl border hover:bg-white hover:text-primary"
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? __('product.unavailable') : __('product.buy_now') ?>
        </button>
      </form>

      <!-- Features Bullets -->
      <div class="space-y-3">
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?= __('product.warranty') ?></span>
        </div>
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?= __('product.free_returns_30days') ?></span>
        </div>
        <div class="flex items-start">
          <i class="mt-1 mr-2 text-green-600 fas fa-check"></i>
          <span class="text-sm font-semibold text-gray-700"><?= __('product.payment_instalments') ?></span>
        </div>
      </div>
    </div>
  </div>

  <?php if ($featuresCount > 0): ?>
    <!-- Product Characteristics Section -->
    <div class="max-w-6xl mx-auto mt-12 mb-16">
      <!-- Section Header -->
      <div class="flex items-center mb-8">
        <h2 class="text-2xl font-normal text-gray-800"><?= __('product.characteristics') ?></h2>
        <a href="#" class="ml-4 text-sm text-primary hover:underline"><?= __('product.more_info') ?></a>
        <div class="flex items-center justify-center w-5 h-5 ml-2 border border-gray-300 rounded-full">
          <span class="text-sm text-gray-500">?</span>
        </div>
      </div>

      <!-- Two-column layout with image -->
      <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
        <!-- Left column features -->
        <div class="md:col-span-1">
          <ul class="space-y-4">
            <?php foreach ($featuresFirstColumn as $feature): ?>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3 bg-gray-300 rounded-full"></span>
                <span class="text-gray-700"><?= htmlspecialchars($feature) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Middle column features -->
        <div class="md:col-span-1">
          <ul class="space-y-4">
            <?php foreach ($featuresSecondColumn as $feature): ?>
              <li class="flex items-start">
                <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3 bg-gray-300 rounded-full"></span>
                <span class="text-gray-700"><?= htmlspecialchars($feature) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Right column (Image and download links) -->
        <div class="md:col-span-1">
          <!-- Image -->
          <div class="p-2 mb-4 border border-gray-200 rounded-lg">
            <img
              src="<?= isset($product['images'][1]) ? getImageUrl($product['images'][1]) : getImageUrl($product['images'][0]) ?>"
              alt="<?= htmlspecialchars($product['name']) ?> Caractéristiques"
              class="object-contain w-full h-auto rounded" />
          </div>

          <!-- Download links under the image -->
          <div class="space-y-3">
            <?php if (isset($product['documents']) && !empty($product['documents'])): ?>
              <?php foreach ($product['documents'] as $document): ?>
                <a href="/assets/pdfs/<?= htmlspecialchars($document['url']) ?>" class="flex items-center text-sm text-primary hover:underline">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 mr-2"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  <?= htmlspecialchars($document['name']) ?>
                </a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Customer Reviews Section -->
  <section class="py-16">
    <div class="max-w-[1140px] mx-auto px-4">
      <div class="flex items-center mb-8">
        <h2 class="mr-4 text-2xl font-normal text-gray-800"><?= __('product.customer_reviews') ?></h2>
        <div class="flex items-center">
          <div class="flex mr-2">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <span class="text-yellow-400 <?= $i <= $averageRating ? 'fas' : 'far' ?> fa-star"></span>
            <?php endfor; ?>
          </div>
          <span class="text-lg font-semibold"><?= $averageRating ?></span>
          <span class="ml-2 text-sm text-gray-600">
            <?= __('product.based_on', ['count' => $reviewCount]) ?>
          </span>
        </div>
        <a href="#write-review" class="px-6 py-2 ml-auto text-white transition rounded-full bg-primary hover:bg-primary-hover">
          <?= __('product.write_review') ?>
        </a>
      </div>

      <?php if ($reviewCount > 0): ?>
        <!-- Review filters and sorting -->
        <form id="reviewFilterForm" method="GET" action="" class="flex flex-wrap items-center mb-6">
          <input type="hidden" name="review_page" value="1" id="reviewPageInput">

          <div class="mb-2 mr-4">
            <label class="mr-2 text-sm text-gray-600"><?= __('product.filter_by') ?>:</label>
            <select name="filter" class="px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" onchange="this.form.submit()">
              <option value=""><?= __('product.all_stars') ?></option>
              <option value="5" <?= $currentFilter == 5 ? 'selected' : '' ?>>5 <?= __('product.stars') ?></option>
              <option value="4" <?= $currentFilter == 4 ? 'selected' : '' ?>>4 <?= __('product.stars') ?></option>
              <option value="3" <?= $currentFilter == 3 ? 'selected' : '' ?>>3 <?= __('product.stars') ?></option>
              <option value="2" <?= $currentFilter == 2 ? 'selected' : '' ?>>2 <?= __('product.stars') ?></option>
              <option value="1" <?= $currentFilter == 1 ? 'selected' : '' ?>>1 <?= __('product.stars') ?></option>
            </select>
          </div>

          <div>
            <label class="mr-2 text-sm text-gray-600"><?= __('product.sort_by') ?>:</label>
            <select name="sort" class="px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" onchange="this.form.submit()">
              <option value="recent" <?= $currentSort == 'recent' ? 'selected' : '' ?>><?= __('product.most_recent') ?></option>
              <option value="helpful" <?= $currentSort == 'helpful' ? 'selected' : '' ?>><?= __('product.most_helpful') ?></option>
              <option value="highest" <?= $currentSort == 'highest' ? 'selected' : '' ?>><?= __('product.highest_rating') ?></option>
              <option value="lowest" <?= $currentSort == 'lowest' ? 'selected' : '' ?>><?= __('product.lowest_rating') ?></option>
            </select>
          </div>
        </form>

        <!-- Reviews pagination info -->
        <div class="mb-4 text-sm text-gray-600">
          <?php
          $first = (($reviewData['current_page'] - 1) * $reviewData['per_page']) + 1;
          $last = min($first + $reviewData['per_page'] - 1, $reviewData['total']);
          ?>
          <?= __('product.showing') ?> <?= $first ?> <?= __('product.to') ?> <?= $last ?> <?= __('product.of_total') ?> <?= $reviewData['total'] ?> <?= __('product.reviews') ?>
        </div>

        <!-- Reviews list -->
        <div class="mb-8 space-y-8">
          <?php foreach ($reviewData['reviews'] as $review): ?>
            <div class="p-6 bg-white border border-gray-200 rounded-lg">
              <div class="flex justify-between mb-4">
                <div>
                  <div class="flex items-center mb-2">
                    <div class="flex mr-2">
                      <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="text-yellow-400 <?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star"></span>
                      <?php endfor; ?>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800">
                      <?= htmlspecialchars($review['title'] ?? '') ?>
                    </h3>
                  </div>
                  <p class="text-sm text-gray-600">
                    <?= __('product.by') ?> <?= htmlspecialchars($review['reviewer_name']) ?>
                    <?= __('product.on') ?> <?= date('d/m/Y', strtotime($review['date'])) ?>
                  </p>
                </div>
              </div>

              <div class="mb-4 text-gray-700">
                <?= htmlspecialchars($review['text']) ?>
              </div>

              <?php if (!empty($review['images'])): ?>
                <div class="flex flex-wrap gap-2 mb-4">
                  <?php foreach ($review['images'] as $index => $image): ?>
                    <div class="review-image-thumb" data-review-id="<?= $review['id'] ?>" data-image-index="<?= $index ?>">
                      <img
                        src="/assets/images/reviews/<?= $image ?>"
                        alt="Review image"
                        class="object-cover w-20 h-20 rounded cursor-pointer hover:opacity-80 review-thumbnail"
                        data-full-image="/assets/images/reviews/<?= $image ?>">
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination controls -->
        <?php if ($reviewData['last_page'] > 1): ?>
          <div class="flex items-center justify-center">
            <nav class="flex items-center">
              <!-- First page -->
              <?php if ($reviewData['current_page'] > 1): ?>
                <a href="?review_page=1<?= $currentFilter ? '&filter=' . $currentFilter : '' ?><?= $currentSort ? '&sort=' . $currentSort : '' ?>" class="px-3 py-1 mx-1 border border-gray-300 rounded hover:bg-gray-100">
                  &laquo; <?= __('product.first') ?>
                </a>
              <?php else: ?>
                <span class="px-3 py-1 mx-1 text-gray-400 border border-gray-200 rounded cursor-not-allowed">
                  &laquo; <?= __('product.first') ?>
                </span>
              <?php endif; ?>

              <!-- Previous page -->
              <?php if ($reviewData['current_page'] > 1): ?>
                <a href="?review_page=<?= $reviewData['current_page'] - 1 ?><?= $currentFilter ? '&filter=' . $currentFilter : '' ?><?= $currentSort ? '&sort=' . $currentSort : '' ?>" class="px-3 py-1 mx-1 border border-gray-300 rounded hover:bg-gray-100">
                  &lsaquo; <?= __('product.previous') ?>
                </a>
              <?php else: ?>
                <span class="px-3 py-1 mx-1 text-gray-400 border border-gray-200 rounded cursor-not-allowed">
                  &lsaquo; <?= __('product.previous') ?>
                </span>
              <?php endif; ?>

              <!-- Page info -->
              <span class="px-3 py-1 mx-2 text-gray-700">
                <?= __('product.page') ?> <?= $reviewData['current_page'] ?> <?= __('product.of') ?> <?= $reviewData['last_page'] ?>
              </span>

              <!-- Next page -->
              <?php if ($reviewData['current_page'] < $reviewData['last_page']): ?>
                <a href="?review_page=<?= $reviewData['current_page'] + 1 ?><?= $currentFilter ? '&filter=' . $currentFilter : '' ?><?= $currentSort ? '&sort=' . $currentSort : '' ?>" class="px-3 py-1 mx-1 border border-gray-300 rounded hover:bg-gray-100">
                  <?= __('product.next') ?> &rsaquo;
                </a>
              <?php else: ?>
                <span class="px-3 py-1 mx-1 text-gray-400 border border-gray-200 rounded cursor-not-allowed">
                  <?= __('product.next') ?> &rsaquo;
                </span>
              <?php endif; ?>

              <!-- Last page -->
              <?php if ($reviewData['current_page'] < $reviewData['last_page']): ?>
                <a href="?review_page=<?= $reviewData['last_page'] ?><?= $currentFilter ? '&filter=' . $currentFilter : '' ?><?= $currentSort ? '&sort=' . $currentSort : '' ?>" class="px-3 py-1 mx-1 border border-gray-300 rounded hover:bg-gray-100">
                  <?= __('product.last') ?> &raquo;
                </a>
              <?php else: ?>
                <span class="px-3 py-1 mx-1 text-gray-400 border border-gray-200 rounded cursor-not-allowed">
                  <?= __('product.last') ?> &raquo;
                </span>
              <?php endif; ?>
            </nav>
          </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
          <p class="text-gray-600"><?= __('product.no_reviews') ?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Related Products Section (if any) -->
  <?php if (isset($relatedProducts) && !empty($relatedProducts)): ?>
    <section class="py-12 mt-16 bg-gray-50">
      <div class="px-4 site-container">
        <h2 class="mb-8 text-2xl font-normal text-gray-800"><?= __('product.similar_products') ?></h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <?php foreach ($relatedProducts as $relatedProduct): ?>
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
              <a href="/product/<?= $relatedProduct['slug'] ?>" class="block">
                <!-- Updated image container with optimized thumbnails -->
                <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                  <?php if (isset($relatedProduct['images']) && !empty($relatedProduct['images'])): ?>
                    <img
                      src="<?= ImageHelper::getImageUrl($relatedProduct['images'][0], 'thumbnail') ?>"
                      alt="<?= htmlspecialchars($relatedProduct['name']) ?>"
                      class="absolute top-0 left-0 object-contain w-full h-full">
                  <?php else: ?>
                    <div class="absolute top-0 left-0 flex items-center justify-center w-full h-full">
                      <span class="text-gray-400 fas fa-image fa-3x"></span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="p-4">
                  <h3 class="mb-2 text-sm font-medium text-gray-800"><?= htmlspecialchars($relatedProduct['name']) ?></h3>
                  <div class="flex items-baseline">
                    <span class="mr-2 text-lg font-semibold price-color"><?= number_format($relatedProduct['price'], 2, ',', ' ') ?> €</span>
                    <?php if (isset($relatedProduct['old_price']) && $relatedProduct['old_price'] > 0): ?>
                      <span class="text-sm text-gray-500 line-through"><?= number_format($relatedProduct['old_price'], 2, ',', ' ') ?> €</span>
                    <?php endif; ?>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- FAQ Section -->
  <section class="py-16">
    <div class="max-w-[1140px] mx-auto px-4">
      <h2 class="mb-10 text-2xl font-normal text-gray-800"><?= __('product.all_questions') ?></h2>

      <!-- FAQ Accordion -->
      <div class="border-t border-gray-200">
        <!-- Garanties - Expanded by default -->
        <div class="border-b border-gray-200">
          <button class="flex items-center justify-between w-full py-5 text-left" onclick="toggleFaq('guarantees')">
            <span class="font-medium text-gray-800"><?= __('product.guarantees') ?></span>
            <span class="text-gray-500">
              <i class="fas fa-plus" id="guarantees-icon"></i>
            </span>
          </button>
          <div id="guarantees" class="hidden pb-5">
            <div class="space-y-5">
              <!-- First question -->
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.warranty_question') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.warranty_answer') ?>
                </p>
              </div>

              <!-- Second question -->
              <div>
                <h3 class="mb-1 font-medium text-gray-800">
                  <?= __('product.warranty_online') ?>
                </h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.warranty_online_desc') ?>
                </p>
              </div>

              <!-- Third question -->
              <div>
                <h3 class="mb-1 font-medium text-gray-800">
                  <?= __('product.warranty_expired') ?>
                </h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.warranty_expired_answer') ?>
                </p>
              </div>

              <div>
                <a href="#" class="text-sm text-primary hover:underline"><?= __('product.view_full_faq') ?></a>
              </div>
            </div>
          </div>
        </div>

        <!-- Livraison - Collapsed -->
        <div class="border-b border-gray-200">
          <button class="flex items-center justify-between w-full py-5 text-left" onclick="toggleFaq('delivery')">
            <span class="font-medium text-gray-800"><?= __('product.delivery') ?></span>
            <span class="text-gray-500">
              <i class="fas fa-plus" id="delivery-icon"></i>
            </span>
          </button>
          <div id="delivery" class="hidden pb-5">
            <div class="space-y-5">
              <div>
                <h3 class="mb-1 font-medium text-gray-800">
                  <?= __('product.delivery_time_question') ?>
                </h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.delivery_time_answer') ?>
                </p>
              </div>
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.order_tracking') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.order_tracking_answer') ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Commande - Collapsed -->
        <div class="border-b border-gray-200">
          <button class="flex items-center justify-between w-full py-5 text-left" onclick="toggleFaq('orders')">
            <span class="font-medium text-gray-800"><?= __('product.order') ?></span>
            <span class="text-gray-500">
              <i class="fas fa-plus" id="orders-icon"></i>
            </span>
          </button>
          <div id="orders" class="hidden pb-5">
            <div class="space-y-5">
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.modify_order') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.modify_order_answer') ?>
                </p>
              </div>
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.international_order') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.international_order_answer') ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Paiement - Collapsed -->
        <div class="border-b border-gray-200">
          <button class="flex items-center justify-between w-full py-5 text-left" onclick="toggleFaq('payment')">
            <span class="font-medium text-gray-800"><?= __('product.payment') ?></span>
            <span class="text-gray-500">
              <i class="fas fa-plus" id="payment-icon"></i>
            </span>
          </button>
          <div id="payment" class="hidden pb-5">
            <div class="space-y-5">
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.payment_methods') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.payment_methods_answer') ?>
                </p>
              </div>
              <div>
                <h3 class="mb-1 font-medium text-gray-800"><?= __('product.payment_security') ?></h3>
                <p class="text-sm text-gray-700">
                  <?= __('product.payment_security_answer') ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Review Image Lightbox Modal -->
  <div id="review-image-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-75">
    <div class="relative max-w-3xl mx-4">
      <button id="close-review-modal" class="absolute p-2 text-white bg-black bg-opacity-50 rounded-full top-3 right-3 hover:bg-opacity-70">
        <i class="fas fa-times"></i>
      </button>
      <img id="modal-review-image" src="" alt="Review image" class="max-w-full max-h-[80vh] object-contain">
    </div>
  </div>
</div>

<!-- Schema.org markup for Product -->
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?= htmlspecialchars($product['name']) ?>",
    "image": "<?= (isset($ogImage)) ? $ogImage : '' ?>",
    "description": "<?= htmlspecialchars(strip_tags($product['description'])) ?>",
    "sku": "<?= htmlspecialchars($product['sku']) ?>",
    <?php if (isset($product['gtin']) && !empty($product['gtin'])): ?> "gtin13": "<?= htmlspecialchars($product['gtin']) ?>",
    <?php endif; ?> "brand": {
      "@type": "Brand",
      "name": "<?= $config['app']['name'] ?>"
    },
    <?php if (isset($category) && !empty($category)): ?> "category": "<?= htmlspecialchars($category['name']) ?>",
    <?php endif; ?> "offers": {
      "@type": "Offer",
      "url": "<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/product/' . $product['slug'] ?>",
      "priceCurrency": "EUR",
      "price": "<?= number_format($product['price'], 2, '.', '') ?>",
      "availability": "<?= (isset($product['stock']) && $product['stock'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ?>"
    }
  }
</script>

<script>
  // Image gallery functionality
  // Image gallery functionality
  document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');

    // Get the original image URLs, not thumbnails
    const images = <?= json_encode(array_map(function ($img) {
                      return \App\Helpers\ImageHelper::getImageUrl($img, 'original');
                    }, $product['images'])) ?>;

    let currentIndex = 0;

    // Function to update main image
    function updateMainImage(index) {
      mainImage.src = images[index];

      // Update active thumbnail
      thumbnails.forEach(thumb => {
        thumb.classList.remove('thumbnail-active', 'border-primary');
        thumb.classList.add('border-gray-200');
      });

      thumbnails[index].classList.add('thumbnail-active', 'border-primary');
      thumbnails[index].classList.remove('border-gray-200');

      currentIndex = index;
    }

    // Set up thumbnail click handlers
    thumbnails.forEach(thumbnail => {
      thumbnail.addEventListener('click', function() {
        const index = parseInt(this.getAttribute('data-index'));
        updateMainImage(index);
      });
    });

    // Set up prev/next buttons
    if (prevButton && nextButton) {
      prevButton.addEventListener('click', function() {
        const newIndex = (currentIndex - 1 + images.length) % images.length;
        updateMainImage(newIndex);
      });

      nextButton.addEventListener('click', function() {
        const newIndex = (currentIndex + 1) % images.length;
        updateMainImage(newIndex);
      });
    }
  });

  // FAQ toggle functionality
  function toggleFaq(id) {
    const content = document.getElementById(id);
    const icon = document.getElementById(id + '-icon');

    // Toggle visibility
    content.classList.toggle('hidden');

    // Toggle icon
    if (content.classList.contains('hidden')) {
      icon.classList.remove('fa-minus');
      icon.classList.add('fa-plus');
    } else {
      icon.classList.remove('fa-plus');
      icon.classList.add('fa-minus');
    }
  }

  // Review filter and pagination handling
  document.addEventListener('DOMContentLoaded', function() {
    const reviewFilterForm = document.getElementById('reviewFilterForm');

    if (reviewFilterForm) {
      // Update page number when using pagination links
      const updatePage = (pageNum) => {
        document.getElementById('reviewPageInput').value = pageNum;
        reviewFilterForm.submit();
      };

      // Preserve other query parameters when changing filter/sort
      const queryParams = new URLSearchParams(window.location.search);
      if (queryParams.has('review_page') && !document.getElementById('reviewPageInput').value) {
        document.getElementById('reviewPageInput').value = queryParams.get('review_page');
      }
    }
  });

  // Review image lightbox functionality
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('review-image-modal');
    const modalImage = document.getElementById('modal-review-image');
    const closeBtn = document.getElementById('close-review-modal');
    const reviewThumbnails = document.querySelectorAll('.review-thumbnail');

    // Open modal when clicking on a thumbnail
    reviewThumbnails.forEach(thumbnail => {
      thumbnail.addEventListener('click', function() {
        const fullImageSrc = this.getAttribute('data-full-image');
        modalImage.src = fullImageSrc;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
      });
    });

    // Close modal when clicking the close button
    closeBtn.addEventListener('click', function() {
      closeModal();
    });

    // Close modal when clicking outside the image
    modal.addEventListener('click', function(event) {
      if (event.target === modal) {
        closeModal();
      }
    });

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
        closeModal();
      }
    });

    // Function to close the modal
    function closeModal() {
      modal.classList.add('hidden');
      document.body.style.overflow = ''; // Restore scrolling
    }
  });
</script>