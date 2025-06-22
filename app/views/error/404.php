<?php
// app/views/error/404.php
$message = $message ?? __('error.page_not_found');
?>

<div class="px-4 py-16 site-container">
  <div class="flex flex-col items-center justify-center max-w-md mx-auto text-center">
    <div class="mb-6 text-6xl font-bold text-primary">404</div>
    <h1 class="mb-4 text-2xl font-normal text-gray-800"><?= htmlspecialchars($message) ?></h1>
    <p class="mb-8 text-gray-600"><?= __('error.page_not_found_message') ?></p>
    <a href="/" class="px-6 py-3 text-white transition rounded-full bg-primary hover:bg-primary-hover">
      <?= __('error.back_to_home') ?>
    </a>
  </div>
</div>