<?php
// app/views/account/dashboard.php

// Get user data
$user = $user ?? null;
$recentOrders = $recentOrders ?? [];
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <h1 class="text-2xl font-normal text-gray-800"><?= __('dashboard.my_account') ?></h1>
      <p class="text-sm text-gray-600">
        <?= __('dashboard.welcome') ?>, <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar navigation -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <nav class="space-y-1">
            <a href="/account" class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md bg-primary">
              <i class="w-5 mr-2 fas fa-tachometer-alt"></i>
              <?= __('dashboard.dashboard') ?>
            </a>
            <a href="/account/orders" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">
              <i class="w-5 mr-2 fas fa-shopping-bag"></i>
              <?= __('account.my_orders') ?>
            </a>
            <a href="/account/profile" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">
              <i class="w-5 mr-2 fas fa-user"></i>
              <?= __('account.profile') ?>
            </a>
            <a href="/account/addresses" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">
              <i class="w-5 mr-2 fas fa-map-marker-alt"></i>
              <?= __('account.addresses') ?>
            </a>
            <div class="pt-4 mt-4 border-t border-gray-200">
              <a href="/logout" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">
                <i class="w-5 mr-2 fas fa-sign-out-alt"></i>
                <?= __('account.logout') ?>
              </a>
            </div>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <div class="md:col-span-3">
        <!-- Account summary cards -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-3">
          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
              <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <i class="fas fa-shopping-bag"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500"><?= __('dashboard.orders') ?></p>
                <p class="text-lg font-semibold text-gray-800"><?= count($recentOrders) ?></p>
              </div>
            </div>
          </div>

          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
              <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <i class="fas fa-star"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500"><?= __('dashboard.loyalty_points') ?></p>
                <p class="text-lg font-semibold text-gray-800">120</p>
              </div>
            </div>
          </div>

          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:col-span-2 lg:col-span-1">
            <div class="flex items-center">
              <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-full">
                <i class="fas fa-gift"></i>
              </div>
              <div>
                <p class="text-sm text-gray-500"><?= __('dashboard.available_coupons') ?></p>
                <p class="text-lg font-semibold text-gray-800">2</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent orders -->
        <div class="mb-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-800"><?= __('dashboard.recent_orders') ?></h2>
            <a href="/account/orders" class="text-sm singer-red-text hover:underline"><?= __('dashboard.view_all') ?></a>
          </div>

          <?php if (empty($recentOrders)): ?>
            <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
              <i class="mb-2 text-3xl text-gray-300 fas fa-shopping-bag"></i>
              <p class="text-gray-600"><?= __('dashboard.no_orders') ?></p>
              <a href="/products" class="inline-block px-4 py-2 mt-4 text-sm text-white transition rounded-full bg-primary hover:bg-primary-hover">
                <?= __('dashboard.discover_products') ?>
              </a>
            </div>
          <?php else: ?>
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                      <?= __('order.order_number') ?>
                    </th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                      <?= __('order.date') ?>
                    </th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                      <?= __('order.status') ?>
                    </th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                      <?= __('order.total') ?>
                    </th>
                    <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                      <?= __('general.actions') ?>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <?php foreach ($recentOrders as $order): ?>
                    <tr>
                      <td class="px-4 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#<?= $order['id'] ?></span>
                      </td>
                      <td class="px-4 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-500">
                          <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                        </span>
                      </td>
                      <td class="px-4 py-4 whitespace-nowrap">
                        <?php
                        $statusClass = '';
                        switch ($order['status']) {
                          case 'pending':
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            break;
                          case 'processing':
                            $statusClass = 'bg-blue-100 text-blue-800';
                            break;
                          case 'shipped':
                            $statusClass = 'bg-indigo-100 text-indigo-800';
                            break;
                          case 'delivered':
                            $statusClass = 'bg-green-100 text-green-800';
                            break;
                          case 'cancelled':
                            $statusClass = 'bg-red-100 text-red-800';
                            break;
                        }
                        ?>
                        <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full <?= $statusClass ?>">
                          <?= __('order.status_' . $order['status']) ?>
                        </span>
                      </td>
                      <td class="px-4 py-4 text-right whitespace-nowrap">
                        <span class="text-sm text-gray-900">
                          <?= number_format($order['total'], 2, ',', ' ') ?> €
                        </span>
                      </td>
                      <td class="px-4 py-4 text-right whitespace-nowrap">
                        <a href="/account/orders/<?= $order['id'] ?>" class="text-sm singer-red-text hover:underline">
                          <?= __('order.details') ?>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- User info and quick links -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="mb-3 text-base font-medium text-gray-800"><?= __('dashboard.personal_info') ?></h3>
            <p class="mb-1 text-sm text-gray-600">
              <span class="font-medium text-gray-800"><?= __('account.first_name') ?> :</span>
              <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
            </p>
            <p class="mb-1 text-sm text-gray-600">
              <span class="font-medium text-gray-800"><?= __('account.email') ?> :</span>
              <?= htmlspecialchars($user['email']) ?>
            </p>
            <p class="mb-4 text-sm text-gray-600">
              <span class="font-medium text-gray-800"><?= __('dashboard.member_since') ?> :</span>
              <?= date('d/m/Y', strtotime($user['created_at'])) ?>
            </p>
            <a href="/account/profile" class="text-sm singer-red-text hover:underline">
              <?= __('dashboard.edit_info') ?>
            </a>
          </div>

          <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h3 class="mb-3 text-base font-medium text-gray-800"><?= __('dashboard.quick_links') ?></h3>
            <ul class="space-y-2">
              <li>
                <a href="/account/orders" class="flex items-center text-sm text-gray-600 hover:text-primary">
                  <i class="w-5 mr-2 fas fa-shopping-bag"></i>
                  <?= __('dashboard.order_history') ?>
                </a>
              </li>
              <li>
                <a href="/account/addresses" class="flex items-center text-sm text-gray-600 hover:text-primary">
                  <i class="w-5 mr-2 fas fa-map-marker-alt"></i>
                  <?= __('dashboard.manage_addresses') ?>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center text-sm text-gray-600 hover:text-primary">
                  <i class="w-5 mr-2 fas fa-heart"></i>
                  <?= __('dashboard.wishlist') ?>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center text-sm text-gray-600 hover:text-primary">
                  <i class="w-5 mr-2 fas fa-gift"></i>
                  <?= __('dashboard.my_coupons') ?>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>