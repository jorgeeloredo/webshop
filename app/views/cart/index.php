<?php
// app/views/cart/index.php

// Get cart items
$items = $cart->getItems();
$totalPrice = $cart->getTotalPrice();
$totalQuantity = $cart->getTotalQuantity();
$shippingCost = $cart->getShippingCost();
$finalTotal = $cart->getFinalTotal();
$currentShippingMethod = $cart->getShippingMethod();

// Function to generate image URL
function getImageUrl($image)
{
  if (empty($image)) {
    return '';
  }
  return '/assets/images/products/' . $image;
}
?>

<div class="px-4 py-8 site-container">
  <div class="mb-6">
    <h1 class="text-2xl font-normal text-gray-800"><?= __('cart.your_cart') ?></h1>
    <p class="text-sm text-gray-600">
      <?= $totalQuantity ?> <?= __('cart.items_in_cart') ?>
    </p>
  </div>

  <?php if ($totalQuantity > 0): ?>
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Cart items (left side) -->
      <div class="lg:col-span-2">
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
          <!-- Table header -->
          <div class="hidden grid-cols-12 gap-4 p-4 border-b border-gray-200 sm:grid">
            <div class="col-span-7">
              <span class="text-sm font-medium text-gray-700"><?= __('cart.product') ?></span>
            </div>
            <div class="col-span-2 text-center">
              <span class="text-sm font-medium text-gray-700"><?= __('cart.quantity') ?></span>
            </div>
            <div class="col-span-3 text-right">
              <span class="text-sm font-medium text-gray-700"><?= __('cart.price') ?></span>
            </div>
          </div>

          <!-- Cart items -->
          <?php foreach ($items as $itemId => $item): ?>
            <div class="grid grid-cols-1 gap-4 p-4 border-b border-gray-200 sm:grid-cols-12 last:border-0">
              <!-- Mobile: Product image + info in a row -->
              <div class="flex items-start col-span-1 sm:hidden">
                <div class="w-20 h-20 mr-4 overflow-hidden bg-gray-100 rounded">
                  <?php if (isset($item['image']) && !empty($item['image'])): ?>
                    <img src="<?= getImageUrl($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-contain w-full h-full">
                  <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full">
                      <span class="text-gray-400 fas fa-image"></span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="flex-1">
                  <h3 class="mb-1 text-sm font-medium text-gray-800">
                    <?php if (isset($item['slug']) && !empty($item['slug'])): ?>
                      <a href="/product/<?= $item['slug'] ?>" class="hover:text-primary">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>
                    <?php else: ?>
                      <?= htmlspecialchars($item['name']) ?>
                    <?php endif; ?>
                  </h3>
                  <div class="flex items-center justify-between">
                    <div class="flex items-center border border-gray-300 rounded">
                      <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease" data-item-id="<?= $itemId ?>">
                        <i class="fas fa-minus"></i>
                      </button>
                      <input
                        type="number"
                        value="<?= $item['quantity'] ?>"
                        min="1"
                        class="w-10 py-1 text-center border-gray-300 border-x quantity-input"
                        data-item-id="<?= $itemId ?>">
                      <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase" data-item-id="<?= $itemId ?>">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                    <span class="text-sm font-medium price-color"><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €</span>
                  </div>
                  <div class="mt-2">
                    <button type="button" class="text-xs text-gray-500 hover:text-primary remove-item" data-item-id="<?= $itemId ?>">
                      <i class="mr-1 fas fa-trash-alt"></i> <?= __('cart.remove') ?>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Desktop: Product info -->
              <div class="items-center hidden col-span-7 sm:flex">
                <div class="flex-shrink-0 w-20 h-20 mr-4 overflow-hidden bg-gray-100 rounded">
                  <?php if (isset($item['image']) && !empty($item['image'])): ?>
                    <img src="<?= getImageUrl($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-contain w-full h-full">
                  <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full">
                      <span class="text-gray-400 fas fa-image"></span>
                    </div>
                  <?php endif; ?>
                </div>
                <div>
                  <h3 class="mb-1 text-sm font-medium text-gray-800">
                    <?php if (isset($item['slug']) && !empty($item['slug'])): ?>
                      <a href="/product/<?= $item['slug'] ?>" class="hover:text-primary">
                        <?= htmlspecialchars($item['name']) ?>
                      </a>
                    <?php else: ?>
                      <?= htmlspecialchars($item['name']) ?>
                    <?php endif; ?>
                  </h3>
                  <button type="button" class="text-xs text-gray-500 hover:text-primary remove-item" data-item-id="<?= $itemId ?>">
                    <i class="mr-1 fas fa-trash-alt"></i> <?= __('cart.remove') ?>
                  </button>
                </div>
              </div>

              <!-- Desktop: Quantity selector -->
              <div class="items-center justify-center hidden col-span-2 sm:flex">
                <div class="flex items-center border border-gray-300 rounded">
                  <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="decrease" data-item-id="<?= $itemId ?>">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input
                    type="number"
                    value="<?= $item['quantity'] ?>"
                    min="1"
                    class="w-10 py-1 text-center border-gray-300 border-x quantity-input"
                    data-item-id="<?= $itemId ?>">
                  <button type="button" class="px-2 py-1 text-gray-600 hover:text-primary quantity-btn" data-action="increase" data-item-id="<?= $itemId ?>">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>

              <!-- Desktop: Price -->
              <div class="items-center justify-end hidden col-span-3 sm:flex">
                <span class="text-sm font-medium price-color"><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €</span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Continue Shopping button -->
        <div class="mt-6">
          <a href="/products" class="inline-flex items-center text-sm text-gray-600 hover:text-primary">
            <i class="mr-2 fas fa-chevron-left"></i>
            <?= __('cart.continue_shopping') ?>
          </a>
        </div>
      </div>

      <!-- Order summary (right side) -->
      <div class="lg:col-span-1">
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
          <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('cart.summary') ?></h2>

          <!-- Shipping Methods Selection -->
          <div class="mb-4">
            <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('cart.shipping_method') ?></h3>

            <div class="space-y-2">
              <?php foreach ($shippingMethods as $code => $method): ?>
                <div class="flex items-center p-2 border <?= $currentShippingMethod === $code ? 'border-red-300 bg-red-50' : 'border-gray-200' ?> rounded">
                  <input
                    type="radio"
                    name="shipping_method"
                    id="shipping_<?= $code ?>"
                    value="<?= $code ?>"
                    class="w-4 h-4 text-primary border-gray-300 shipping-method-radio"
                    <?= $currentShippingMethod === $code ? 'checked' : '' ?>>
                  <label for="shipping_<?= $code ?>" class="flex flex-col flex-1 ml-3">
                    <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($method['name']) ?></span>
                    <span class="text-xs text-gray-500"><?= htmlspecialchars($method['estimated_delivery']) ?></span>
                  </label>
                  <span class="text-sm font-medium">
                    <?php
                    $methodCost = \App\Helpers\Shipping::calculateCost($code, $totalPrice);
                    echo $methodCost > 0 ? number_format($methodCost, 2, ',', ' ') . ' €' : __('general.free');
                    ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="mb-4 space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><?= __('cart.subtotal') ?></span>
              <span class="font-medium text-gray-800"><?= number_format($totalPrice, 2, ',', ' ') ?> €</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><?= __('cart.shipping_cost') ?></span>
              <span class="font-medium text-gray-800" id="shipping-cost-display">
                <?= $shippingCost > 0 ? number_format($shippingCost, 2, ',', ' ') . ' €' : __('general.free') ?>
              </span>
            </div>
          </div>

          <div class="pt-4 mb-6 border-t border-gray-200">
            <div class="flex justify-between">
              <span class="text-base font-medium text-gray-800"><?= __('cart.total') ?></span>
              <span class="text-base font-bold text-gray-800" id="final-total-display">
                <?= number_format($finalTotal, 2, ',', ' ') ?> €
              </span>
            </div>
            <?php if ($cart->getRemainingForFreeShipping() > 0): ?>
              <p class="mt-2 text-xs text-gray-500" id="free-shipping-message">
                <?= __('cart.free_shipping_remaining', ['amount' => number_format($cart->getRemainingForFreeShipping(), 2, ',', ' ')]) ?>
              </p>
            <?php else: ?>
              <p class="mt-2 text-xs text-green-600" id="free-shipping-message"><?= __('cart.free_shipping_achieved') ?></p>
            <?php endif; ?>
          </div>


          <a href="/checkout"
            class="block w-full py-3 text-base font-medium text-center text-white transition rounded-full bg-primary hover:bg-primary-hover">
            <?= __('cart.checkout') ?>
          </a>

          <div class="flex flex-col mt-4 space-y-2">
            <div class="flex items-center">
              <img src="/assets/images/visa.png" alt="Visa" class="h-6 mr-2">
              <img src="/assets/images/mastercard.png" alt="Mastercard" class="h-6 mr-2">
              <span class="text-xs text-gray-500">et plus...</span>
            </div>
            <div class="flex items-center">
              <i class="mr-2 text-green-600 fas fa-lock"></i>
              <span class="text-xs text-gray-500"><?= __('cart.secure_payment') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Forms for cart operations -->
    <form id="update-form" action="/cart/update" method="POST" class="hidden">
      <input type="hidden" name="item_id" id="update-item-id">
      <input type="hidden" name="quantity" id="update-quantity">
    </form>

    <form id="remove-form" action="/cart/remove" method="POST" class="hidden">
      <input type="hidden" name="item_id" id="remove-item-id">
    </form>

    <form id="shipping-form" action="/cart/update-shipping" method="POST" class="hidden">
      <input type="hidden" name="shipping_method" id="shipping-method-input">
    </form>

  <?php else: ?>
    <!-- Empty cart message -->
    <div class="p-8 text-center bg-white border border-gray-200 rounded-lg">
      <div class="flex justify-center mb-4">
        <i class="text-5xl text-gray-300 fas fa-shopping-cart"></i>
      </div>
      <h2 class="mb-2 text-xl font-medium text-gray-800"><?= __('cart.empty_cart') ?></h2>
      <p class="mb-6 text-gray-600"><?= __('cart.empty_cart_message') ?></p>
      <a href="/products" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
        <?= __('cart.discover_products') ?>
      </a>
    </div>
  <?php endif; ?>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const updateForm = document.getElementById('update-form');
    const updateItemId = document.getElementById('update-item-id');
    const updateQuantity = document.getElementById('update-quantity');

    // Remove buttons
    const removeButtons = document.querySelectorAll('.remove-item');
    const removeForm = document.getElementById('remove-form');
    const removeItemId = document.getElementById('remove-item-id');

    // Shipping method radios
    const shippingRadios = document.querySelectorAll('.shipping-method-radio');
    const shippingForm = document.getElementById('shipping-form');
    const shippingMethodInput = document.getElementById('shipping-method-input');
    const shippingCostDisplay = document.getElementById('shipping-cost-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const freeShippingMessage = document.getElementById('free-shipping-message');

    // Handle quantity button clicks
    if (quantityBtns.length) {
      quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const itemId = this.getAttribute('data-item-id');
          const action = this.getAttribute('data-action');
          const inputElement = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
          let currentValue = parseInt(inputElement.value);

          if (action === 'increase') {
            currentValue++;
          } else if (action === 'decrease' && currentValue > 1) {
            currentValue--;
          }

          inputElement.value = currentValue;
          updateCartItem(itemId, currentValue);
        });
      });
    }

    // Handle quantity input changes
    if (quantityInputs.length) {
      quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
          const itemId = this.getAttribute('data-item-id');
          let value = parseInt(this.value);

          // Ensure minimum quantity of 1
          if (isNaN(value) || value < 1) {
            value = 1;
            this.value = 1;
          }

          updateCartItem(itemId, value);
        });
      });
    }

    // Handle remove item buttons
    if (removeButtons.length) {
      removeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const itemId = this.getAttribute('data-item-id');

          if (confirm('<?= __('cart.confirm_remove'); ?>')) {
            removeItemId.value = itemId;
            removeForm.submit();
          }
        });
      });
    }

    // Handle shipping method changes
    if (shippingRadios.length) {
      shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
          shippingMethodInput.value = this.value;

          // Use AJAX to update shipping method
          const formData = new FormData();
          formData.append('shipping_method', this.value);

          fetch('/cart/update-shipping', {
              method: 'POST',
              body: formData,
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Update the shipping cost and final total displays
                shippingCostDisplay.textContent = data.shippingCost;
                finalTotalDisplay.textContent = data.finalTotal;

                // Update free shipping message if needed
                if (data.freeShippingRemaining > 0) {
                  freeShippingMessage.textContent = `<?= __('cart.free_shipping_remaining', ['amount' => '300']); ?>`;
                  freeShippingMessage.classList.remove('text-green-600');
                  freeShippingMessage.classList.add('text-gray-500');
                } else {
                  freeShippingMessage.textContent = '<?= __('cart.free_shipping_achieved'); ?>';
                  freeShippingMessage.classList.remove('text-gray-500');
                  freeShippingMessage.classList.add('text-green-600');
                }

                // Highlight the selected shipping method
                document.querySelectorAll('.shipping-method-radio').forEach(r => {
                  const container = r.closest('div');
                  if (r.checked) {
                    container.classList.add('border-red-300', 'bg-red-50');
                    container.classList.remove('border-gray-200');
                  } else {
                    container.classList.remove('border-red-300', 'bg-red-50');
                    container.classList.add('border-gray-200');
                  }
                });
              }
            })
            .catch(error => {
              console.error('Error updating shipping method:', error);
            });
        });
      });
    }

    // Function to update cart item
    function updateCartItem(itemId, quantity) {
      updateItemId.value = itemId;
      updateQuantity.value = quantity;
      updateForm.submit();
    }
  });
</script>