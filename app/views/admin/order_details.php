<?php
// app/views/admin/order_details.php

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
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <div class="flex items-center mb-2">
        <a href="/admin/orders" class="mr-2 text-sm text-gray-600 hover:text-red-600">
          <i class="mr-1 fas fa-chevron-left"></i> <?= __('admin.back_to_orders') ?>
        </a>
      </div>
      <h1 class="text-2xl font-normal text-gray-800"><?= __('admin.order_details', ['id' => $order['id']]) ?></h1>
      <p class="text-sm text-gray-600">
        <?= __('order.placed_on', ['date' => date('d/m/Y à H:i', strtotime($order['created_at']))]) ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar with order actions -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('admin.actions') ?></h2>

          <div class="mb-4">
            <form action="/admin/update-order-status" method="POST">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <label for="status" class="block mb-2 text-sm font-medium text-gray-700"><?= __('admin.update_status') ?></label>
              <select
                name="status"
                id="status"
                class="w-full px-3 py-2 mb-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                <?php foreach ($orderStatuses as $statusKey => $statusLabel): ?>
                  <option value="<?= $statusKey ?>" <?= $order['status'] === $statusKey ? 'selected' : '' ?>>
                    <?= $statusLabel ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="w-full py-2 text-white transition rounded-md singer-red hover:bg-red-700">
                <?= __('admin.update_status') ?>
              </button>
            </form>
          </div>

          <div class="pt-4 mt-4 border-t border-gray-200">
            <a href="#" onclick="window.print()" class="flex items-center mb-2 text-sm text-gray-700 hover:text-red-600">
              <i class="w-5 mr-2 text-center fas fa-print"></i>
              <?= __('admin.print_order') ?>
            </a>
            <a href="mailto:<?= htmlspecialchars($order['email']) ?>" class="flex items-center text-sm text-gray-700 hover:text-red-600">
              <i class="w-5 mr-2 text-center fas fa-envelope"></i>
              <?= __('admin.contact_customer') ?>
            </a>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="md:col-span-3">
        <?php if (!$order): ?>
          <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
            <i class="mb-2 text-3xl text-gray-300 fas fa-exclamation-circle"></i>
            <p class="text-gray-600"><?= __('error.order_not_found') ?></p>
            <a href="/admin/orders" class="inline-block px-4 py-2 mt-4 text-sm text-white transition rounded-full singer-red hover:bg-red-700">
              <?= __('admin.order_list') ?>
            </a>
          </div>
        <?php else: ?>
          <!-- Order Status and Summary -->
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col justify-between md:flex-row md:items-center">
              <div>
                <h2 class="text-lg font-medium text-gray-800"><?= __('order.order_summary') ?></h2>
                <p class="mt-1 text-sm text-gray-600">
                  <?= __('order.placed_on', ['date' => date('d/m/Y H:i', strtotime($order['created_at']))]) ?>
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
                <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('admin.customer') ?></h3>
                <p class="text-sm text-gray-600">
                  <?= $order['email'] ?? 'N/A' ?>
                  <?= isset($order['guest_checkout']) && $order['guest_checkout'] ? ' (' . __('admin.guest') . ')' : '' ?>
                </p>
              </div>
              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700"><?= __('order.payment_method') ?></h3>
                <p class="text-sm text-gray-600">
                  <?php
                  $paymentMethod = __('general.not_specified');
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
                    <?= __('account.phone'); ?>: <?= htmlspecialchars($shippingAddress['phone']) ?>
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
                    <?= __('account.phone'); ?>: <?= htmlspecialchars($billingAddress['phone']) ?>
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