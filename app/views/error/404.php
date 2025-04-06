<?php
// app/views/error/404.php
$message = $message ?? 'Page non trouvée';
?>

<div class="px-4 py-16 site-container">
  <div class="flex flex-col items-center justify-center max-w-md mx-auto text-center">
    <div class="mb-6 text-6xl font-bold text-red-600">404</div>
    <h1 class="mb-4 text-2xl font-normal text-gray-800"><?= htmlspecialchars($message) ?></h1>
    <p class="mb-8 text-gray-600">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
    <a href="/" class="px-6 py-3 text-white transition rounded-full singer-red hover:bg-red-700">
      Retour à l'accueil
    </a>
  </div>
</div>