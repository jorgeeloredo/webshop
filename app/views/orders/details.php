<?php
// app/views/orders/details.php

// Get order data
$order = $order ?? null;

// Helper function for formatting order status
function getStatusClass($status)
{
  switch ($status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'processing':
      return 'bg-blue-100 text-blue-800';
    case 'shipped':
      return 'bg-indigo-100 text-indigo-800';
    case 'delivered':
      return 'bg-green-100 text-green-800';
    case 'cancelled':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function getStatusLabel($status)
{
  return __('order.status_' . $status);
}

// Parse shipping and billing addresses from JSON
$shippingAddress = isset($order['shipping_address']) ? json_decode($order['shipping_address'], true) : [];
$billingAddress = isset($order['billing_address']) ? json_decode($order['billing_address'], true) : [];

// Get order items
$orderItems = isset($order['items']) ? $order['items'] : [];

// Get shipping method details
$shippingMethodCode = $order['shipping_method'] ?? 'colissimo';
$shippingMethod = \App\Helpers\Shipping::getMethod($shippingMethodCode);
$shippingMethodName = $shippingMethod ? $shippingMethod['name'] : 'Standard';
$shippingCost = isset($order['shipping_cost']) ? $order['shipping_cost'] : 0;
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <div class="flex items-center mb-2">
        <a href="/account/orders" class="mr-2 text-sm text-gray-600 hover:text-red-600">
          <i class="mr-1 fas fa-chevron-left"></i> <?= __('order.back_to_orders') ?>
        </a>
      </div>
      <h1 class="text-2xl font-normal text-gray-800"><?= __('order.order_number', ['id' => $order['id']]) ?></h1>
      <p class="text-sm text-gray-600">
        <?= __('order.placed_on', ['date' => date('d/m/Y à H:i', strtotime($order['created_at']))]) ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar navigation -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <nav class="space-y-1">
            <a href="/account" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-tachometer-alt"></i>
              <?= __('account.dashboard') ?>
            </a>
            <a href="/account/orders" class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md singer-red">
              <i class="w-5 mr-2 fas fa-shopping-bag"></i>
              <?= __('account.my_orders') ?>
            </a>
            <a href="/account/profile" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-user"></i>
              <?= __('account.profile') ?>
            </a>
            <a href="/account/addresses" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-map-marker-alt"></i>
              <?= __('account.addresses') ?>
            </a>
            <div class="pt-4 mt-4 border-t border-gray-200">
              <a href="/logout" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
                <i class="w-5 mr-2 fas fa-sign-out-alt"></i>
                <?= __('account.logout') ?>
              </a>
            </div>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <div class="md:col-span-3">
        <?php if (!$order): ?>
          <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
            <i class="mb-2 text-3xl text-gray-300 fas fa-exclamation-circle"></i>
            <p class="text-gray-600"><?= __('error.order_not_found') ?></p>
            <a href="/account/orders" class="inline-block px-4 py-2 mt-4 text-sm text-white transition rounded-full singer-red hover:bg-red-700">
              <?= __('account.my_orders') ?>
            </a>
          </div>
        <?php else: ?>
          <!-- Order Status and Summary -->
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col justify-between md:flex-row md:items-center">
              <div>
                <h2 class="text-lg font-medium text-gray-800"><?= __('order.order_summary') ?></h2>
                <p class="mt-1 text-sm text-gray-600">
                  <?= __('order.placed_on', ['date' => date('d/m/Y', strtotime($order['created_at']))]) ?>
                </p>
              </div>
              <div class="mt-4 md:mt-0">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?= getStatusClass($order['status']) ?>">
                  <?= getStatusLabel($order['status']) ?>
                </span>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 pt-4 mt-6 border-t border-gray-200 md:grid-cols-3">
              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('order.shipping_method') ?></h3>
                <p class="text-sm text-gray-600">
                  <?= htmlspecialchars($shippingMethodName) ?>
                </p>
                <p class="mt-1 text-sm text-gray-600">
                  <?= $shippingCost > 0 ? number_format($shippingCost, 2, ',', ' ') . ' €' : __('general.free') ?>
                </p>
              </div>

              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('order.payment_method') ?></h3>
                <p class="text-sm text-gray-600">
                  <?php
                  $paymentMethod = __('order.not_specified');
                  if (isset($order['payment_method'])) {
                    switch ($order['payment_method']) {
                      case 'card':
                        $paymentMethod = __('checkout.card');
                        break;
                      case 'paypal':
                        $paymentMethod = 'PayPal';
                        break;
                      default:
                        $paymentMethod = ucfirst($order['payment_method']);
                    }
                  }
                  echo $paymentMethod;
                  ?>
                </p>
              </div>
              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('order.total') ?></h3>
                <p class="text-sm font-semibold price-color">
                  <?= number_format($order['total'], 2, ',', ' ') ?> €
                </p>
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('order.ordered_products') ?></h2>
            <?php if (empty($orderItems)): ?>
              <p class="text-gray-600"><?= __('order.no_products') ?></p>
            <?php else: ?>
              <div class="border-t border-gray-200">
                <?php foreach ($orderItems as $item): ?>
                  <div class="flex items-start py-4 border-b border-gray-200 last:border-b-0">
                    <div class="flex-shrink-0 w-16 h-16 mr-4 overflow-hidden bg-gray-100 rounded">
                      <?php if (isset($item['image_url'])): ?>
                        <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-cover w-full h-full">
                      <?php else: ?>
                        <div class="flex items-center justify-center w-full h-full text-gray-400">
                          <i class="fas fa-box"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="flex-1">
                      <h3 class="text-sm font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                      <p class="mt-1 text-sm text-gray-600">
                        <?= __('order.quantity') ?>: <?= $item['quantity'] ?>
                      </p>
                      <?php if (isset($item['attributes']) && !empty($item['attributes'])):
                        $attributes = is_string($item['attributes']) ? json_decode($item['attributes'], true) : $item['attributes'];
                        if ($attributes && is_array($attributes) && !empty($attributes)):
                      ?>
                          <div class="mt-1 text-sm text-gray-600">
                            <?php foreach ($attributes as $key => $value): ?>
                              <span class="mr-2"><?= htmlspecialchars(ucfirst($key)) ?>: <?= htmlspecialchars($value) ?></span>
                            <?php endforeach; ?>
                          </div>
                      <?php
                        endif;
                      endif;
                      ?>
                    </div>
                    <div class="ml-4 text-right">
                      <p class="text-sm font-medium price-color">
                        <?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €
                      </p>
                      <p class="mt-1 text-xs text-gray-500">
                        <?= number_format($item['price'], 2, ',', ' ') ?> € / <?= __('order.unit_price') ?>
                      </p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Shipping and Billing Information -->
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <?php if (!empty($shippingAddress)): ?>
              <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('order.shipping_address') ?></h2>
                <p class="text-sm text-gray-600">
                  <?= htmlspecialchars($shippingAddress['first_name'] . ' ' . $shippingAddress['last_name']) ?><br>
                  <?= htmlspecialchars($shippingAddress['address']) ?><br>
                  <?php if (!empty($shippingAddress['address2'])): ?>
                    <?= htmlspecialchars($shippingAddress['address2']) ?><br>
                  <?php endif; ?>
                  <?= htmlspecialchars($shippingAddress['postal_code'] . ' ' . $shippingAddress['city']) ?><br>
                  <?= htmlspecialchars($shippingAddress['country']) ?><br>
                  <?php if (!empty($shippingAddress['phone'])): ?>
                    <?= __('checkout.phone') ?>: <?= htmlspecialchars($shippingAddress['phone']) ?>
                  <?php endif; ?>
                </p>
              </div>
            <?php endif; ?>

            <?php if (!empty($billingAddress)): ?>
              <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('order.billing_address') ?></h2>
                <p class="text-sm text-gray-600">
                  <?= htmlspecialchars($billingAddress['first_name'] . ' ' . $billingAddress['last_name']) ?><br>
                  <?= htmlspecialchars($billingAddress['address']) ?><br>
                  <?php if (!empty($billingAddress['address2'])): ?>
                    <?= htmlspecialchars($billingAddress['address2']) ?><br>
                  <?php endif; ?>
                  <?= htmlspecialchars($billingAddress['postal_code'] . ' ' . $billingAddress['city']) ?><br>
                  <?= htmlspecialchars($billingAddress['country']) ?><br>
                  <?php if (!empty($billingAddress['phone'])): ?>
                    <?= __('checkout.phone') ?>: <?= htmlspecialchars($billingAddress['phone']) ?>
                  <?php endif; ?>
                </p>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>