<?php
// app/views/account/register.php

// Get old input and errors
$old = $old ?? [];
$errors = $errors ?? [];
?>

<div class="px-4 py-8 site-container">
  <div class="max-w-md mx-auto">
    <h1 class="mb-6 text-2xl font-normal text-center text-gray-800"><?= __('account.register') ?></h1>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
      <form action="/register" method="POST">
        <!-- First Name -->
        <div class="mb-4">
          <label for="first_name" class="block mb-2 text-sm font-medium text-gray-700"><?= __('account.first_name') ?></label>
          <input
            type="text"
            id="first_name"
            name="first_name"
            class="w-full px-3 py-2 border <?= isset($errors['first_name']) ? 'border-primary' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
            value="<?= htmlspecialchars($old['first_name'] ?? '') ?>"
            required>
          <?php if (isset($errors['first_name'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['first_name']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Last Name -->
        <div class="mb-4">
          <label for="last_name" class="block mb-2 text-sm font-medium text-gray-700"><?= __('account.last_name') ?></label>
          <input
            type="text"
            id="last_name"
            name="last_name"
            class="w-full px-3 py-2 border <?= isset($errors['last_name']) ? 'border-primary' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
            value="<?= htmlspecialchars($old['last_name'] ?? '') ?>"
            required>
          <?php if (isset($errors['last_name'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['last_name']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block mb-2 text-sm font-medium text-gray-700"><?= __('account.email') ?></label>
          <input
            type="email"
            id="email"
            name="email"
            class="w-full px-3 py-2 border <?= isset($errors['email']) ? 'border-primary' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            required>
          <?php if (isset($errors['email'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['email']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label for="password" class="block mb-2 text-sm font-medium text-gray-700"><?= __('account.password') ?></label>
          <input
            type="password"
            id="password"
            name="password"
            class="w-full px-3 py-2 border <?= isset($errors['password']) ? 'border-primary' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
            required>
          <p class="mt-1 text-xs text-gray-500"><?= __('account.min_password') ?></p>
          <?php if (isset($errors['password'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['password']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
          <label for="password_confirm" class="block mb-2 text-sm font-medium text-gray-700"><?= __('account.confirm_password') ?></label>
          <input
            type="password"
            id="password_confirm"
            name="password_confirm"
            class="w-full px-3 py-2 border <?= isset($errors['password_confirm']) ? 'border-primary' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
            required>
          <?php if (isset($errors['password_confirm'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['password_confirm']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Agreement -->
        <div class="mb-6">
          <div class="flex items-start">
            <input
              type="checkbox"
              id="agreement"
              name="agreement"
              class="w-4 h-4 mt-1 border-gray-300 rounded text-primary focus:ring-primary"
              required>
            <label for="agreement" class="block ml-2 text-sm text-gray-700">
              <?= __('account.agree_terms') ?>
            </label>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="w-full py-2 text-white transition rounded-full bg-primary hover:bg-primary-hover">
          <?= __('account.register_button') ?>
        </button>
      </form>

      <div class="pt-4 mt-6 text-sm text-center border-t border-gray-200">
        <p class="text-gray-600"><?= __('account.already_account') ?></p>
        <a href="/login" class="font-medium text-primary hover:underline"><?= __('account.login_button') ?></a>
      </div>
    </div>
  </div>
</div>