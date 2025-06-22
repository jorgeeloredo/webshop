<?php
// app/views/account/login.php

// Get old input and errors
$old = $old ?? [];
$errors = $errors ?? [];
$error = $error ?? null;
?>

<div class="px-4 py-8 site-container">
  <div class="max-w-md mx-auto">
    <h1 class="mb-6 text-2xl font-normal text-center text-gray-800"><?= __('account.login') ?></h1>

    <?php if ($error): ?>
      <div class="p-4 mb-4 text-primary-hover bg-red-100 border border-red-200 rounded-lg">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
      <form action="/login" method="POST">
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
          <?php if (isset($errors['password'])): ?>
            <p class="mt-1 text-xs text-primary"><?= htmlspecialchars($errors['password']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Remember me -->
        <div class="flex items-center mb-4">
          <input
            type="checkbox"
            id="remember"
            name="remember"
            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
            <?= isset($old['remember']) && $old['remember'] ? 'checked' : '' ?>>
          <label for="remember" class="block ml-2 text-sm text-gray-700"><?= __('account.remember_me') ?></label>
        </div>

        <!-- Submit button -->
        <button type="submit" class="w-full py-2 text-white transition rounded-full bg-primary hover:bg-primary-hover">
          <?= __('account.login_button') ?>
        </button>
      </form>

      <div class="mt-4 text-center">
        <a href="#" class="text-sm text-gray-600 hover:text-primary"><?= __('account.forgot_password') ?></a>
      </div>

      <div class="pt-4 mt-6 text-sm text-center border-t border-gray-200">
        <p class="text-gray-600"><?= __('account.no_account') ?></p>
        <a href="/register" class="font-medium singer-red-text hover:underline"><?= __('account.create_account') ?></a>
      </div>
    </div>
  </div>
</div>