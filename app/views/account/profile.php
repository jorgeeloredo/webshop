<?php
// app/views/account/profile.php

// Get user data and errors if any
$user = $user ?? null;
$errors = $errors ?? [];
$success = $_SESSION['success'] ?? null;

// Clear success message after displaying it
if (isset($_SESSION['success'])) {
  unset($_SESSION['success']);
}
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <h1 class="text-2xl font-normal text-gray-800"><?= __('profile.my_profile') ?></h1>
      <p class="text-sm text-gray-600">
        <?= __('profile.manage_info') ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar navigation -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <nav class="space-y-1">
            <a href="/account" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-tachometer-alt"></i>
              <?= __('dashboard.dashboard') ?>
            </a>
            <a href="/account/orders" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-shopping-bag"></i>
              <?= __('account.my_orders') ?>
            </a>
            <a href="/account/profile" class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md singer-red">
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
        <?php if ($success): ?>
          <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-200 rounded-lg">
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <h2 class="mb-6 text-lg font-medium text-gray-800"><?= __('profile.personal_info') ?></h2>

          <form action="/account/profile" method="POST">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <!-- First Name -->
              <div>
                <label for="first_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.first_name') ?></label>
                <input
                  type="text"
                  id="first_name"
                  name="first_name"
                  class="w-full px-3 py-2 border <?= isset($errors['first_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                  required>
                <?php if (isset($errors['first_name'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['first_name']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Last Name -->
              <div>
                <label for="last_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.last_name') ?></label>
                <input
                  type="text"
                  id="last_name"
                  name="last_name"
                  class="w-full px-3 py-2 border <?= isset($errors['last_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                  required>
                <?php if (isset($errors['last_name'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['last_name']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Email -->
              <div class="sm:col-span-2">
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.email') ?></label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="w-full px-3 py-2 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                  required>
                <?php if (isset($errors['email'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
              </div>
            </div>

            <div class="pt-6 mt-6 border-t border-gray-200">
              <h3 class="mb-4 font-medium text-gray-800 text-md"><?= __('profile.change_password') ?></h3>
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Current Password -->
                <div>
                  <label for="current_password" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.current_password') ?></label>
                  <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="w-full px-3 py-2 border <?= isset($errors['current_password']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                  <?php if (isset($errors['current_password'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['current_password']) ?></p>
                  <?php endif; ?>
                </div>

                <!-- Spacer for alignment -->
                <div class="hidden sm:block"></div>

                <!-- New Password -->
                <div>
                  <label for="new_password" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.new_password') ?></label>
                  <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    class="w-full px-3 py-2 border <?= isset($errors['new_password']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                  <?php if (isset($errors['new_password'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['new_password']) ?></p>
                  <?php endif; ?>
                  <p class="mt-1 text-xs text-gray-500"><?= __('profile.min_char') ?></p>
                </div>

                <!-- Confirm New Password -->
                <div>
                  <label for="new_password_confirm" class="block mb-1 text-sm font-medium text-gray-700"><?= __('profile.confirm_password') ?></label>
                  <input
                    type="password"
                    id="new_password_confirm"
                    name="new_password_confirm"
                    class="w-full px-3 py-2 border <?= isset($errors['new_password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                  <?php if (isset($errors['new_password_confirm'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['new_password_confirm']) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-6">
              <button type="submit" class="px-6 py-2 text-white transition rounded-full singer-red hover:bg-red-700">
                <?= __('profile.update_profile') ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>