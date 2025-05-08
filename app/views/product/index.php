<?php
// app/views/product/index.php

// Function to generate image URL
function getImageUrl($image)
{
  return '/assets/images/products/' . $image;
}

// Get product features in two columns
$featuresCount = count($product['features']);
$featuresFirstColumn = array_slice($product['features'], 0, ceil($featuresCount / 2));
$featuresSecondColumn = array_slice($product['features'], ceil($featuresCount / 2));
?>

<!-- Main Product Content -->
<div class="px-4 pb-12 site-container">
  <!-- Product Display Row -->
  <div class="grid grid-cols-1 gap-8 mb-12 md:grid-cols-2">
    <!-- Left: Product Gallery -->
    <div class="relative p-4 rounded-lg product-image-bg">
      <!-- Navigation Buttons positioned inside the product image -->
      <div class="absolute z-10 top-4 left-4">
        <a href="javascript:history.back()" class="flex items-center text-sm text-gray-600 hover:text-red-600">
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
          src="<?= getImageUrl($product['images'][0]) ?>"
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
        <!-- Thumbnails -->
        <div class="grid grid-cols-6 gap-2 mt-4">
          <?php foreach ($product['images'] as $index => $image): ?>
            <div class="border-2 <?= $index === 0 ? 'border-red-600 thumbnail-active' : 'border-gray-200' ?> rounded cursor-pointer thumbnail" data-index="<?= $index ?>">
              <img src="<?= getImageUrl($image) ?>" alt="Vue <?= $index + 1 ?>" class="object-cover w-full" />
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
          <div class="flex items-center text-red-600">
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
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-red-600 quantity-btn" data-action="decrease">
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
            <button type="button" class="px-3 py-1 text-gray-600 hover:text-red-600 quantity-btn" data-action="increase">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </div>

        <button
          type="submit"
          class="singer-red singer-red-border w-full md:w-[365px] py-3 mb-6 text-white transition rounded-3xl border hover:bg-white hover:text-red-500"
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
          <?= (isset($product['stock']) && $product['stock'] <= 0) ? __('product.unavailable') : __('product.add_to_cart') ?>
        </button>
      </form>-->

      <form action="/cart/buy-now" method="POST">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="quantity" value="1">
        <button
          type="submit"
          class="singer-red singer-red-border w-full md:w-[365px] py-3 mb-6 text-white transition rounded-3xl border hover:bg-white hover:text-red-500"
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
        <a href="#" class="ml-4 text-sm singer-red-text hover:underline"><?= __('product.more_info') ?></a>
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
                <a href="/assets/pdfs/<?= htmlspecialchars($document['url']) ?>" class="flex items-center text-sm singer-red-text hover:underline">
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

  <!-- Related Products Section (if any) -->
  <?php if (isset($relatedProducts) && !empty($relatedProducts)): ?>
    <section class="py-12 mt-16 bg-gray-50">
      <div class="px-4 site-container">
        <h2 class="mb-8 text-2xl font-normal text-gray-800"><?= __('product.similar_products') ?></h2>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <?php foreach ($relatedProducts as $relatedProduct): ?>
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
              <a href="/product/<?= $relatedProduct['slug'] ?>" class="block">
                <!-- Updated image container with fixed aspect ratio -->
                <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
                  <?php if (isset($relatedProduct['images']) && !empty($relatedProduct['images'])): ?>
                    <img
                      src="<?= getImageUrl($relatedProduct['images'][0]) ?>"
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
                <a href="#" class="text-sm singer-red-text hover:underline"><?= __('product.view_full_faq') ?></a>
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
  <!-- Schema.org markup for Product -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Product",
      "name": "<?= htmlspecialchars($product['name']) ?>",
      "image": "<?= (isset($product['images'][0])) ? getImageUrl($product['images'][0]) : '' ?>",
      "description": "<?= htmlspecialchars(strip_tags($product['description'])) ?>",
      "sku": "<?= htmlspecialchars($product['sku']) ?>",
      <?php if (isset($product['gtin']) && !empty($product['gtin'])): ?> "gtin13": "<?= htmlspecialchars($product['gtin']) ?>",
      <?php endif; ?> "brand": {
        "@type": "Brand",
        "name": "Singer"
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
</div>

<script>
  // Image gallery functionality
  document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');
    const images = <?= json_encode(array_map(function ($img) {
                      return getImageUrl($img);
                    }, $product['images'])) ?>;
    let currentIndex = 0;

    // Function to update main image
    function updateMainImage(index) {
      mainImage.src = images[index];

      // Update active thumbnail
      thumbnails.forEach(thumb => {
        thumb.classList.remove('thumbnail-active', 'border-red-600');
        thumb.classList.add('border-gray-200');
      });

      thumbnails[index].classList.add('thumbnail-active', 'border-red-600');
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

    // Toggle description
    const toggleBtn = document.getElementById('toggle-description');
    const fullDescription = document.getElementById('full-description');

    if (toggleBtn && fullDescription) {
      toggleBtn.addEventListener('click', function() {
        const isHidden = fullDescription.classList.contains('hidden');

        if (isHidden) {
          fullDescription.classList.remove('hidden');
          toggleBtn.textContent = 'Lire moins';
        } else {
          fullDescription.classList.add('hidden');
          toggleBtn.textContent = 'Lire plus';
        }
      });
    }

    // Quantity buttons
    const quantityInput = document.getElementById('quantity');
    const quantityBtns = document.querySelectorAll('.quantity-btn');

    if (quantityInput && quantityBtns.length) {
      quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const action = this.getAttribute('data-action');
          const currentValue = parseInt(quantityInput.value);
          const max = parseInt(quantityInput.getAttribute('max'));

          if (action === 'increase' && currentValue < max) {
            quantityInput.value = currentValue + 1;
          } else if (action === 'decrease' && currentValue > 1) {
            quantityInput.value = currentValue - 1;
          }
        });
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
</script>