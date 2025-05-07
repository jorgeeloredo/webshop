<?php
// app/views/home/index.php
?>



<!-- Category Navigation -->
<section class="py-12 singer-bg-light">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-center text-gray-800">Nos catégories</h2>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
      <?php foreach ($categories as $category): ?>
        <a href="/category/<?= $category['slug'] ?>" class="group">
          <div class="mb-2 overflow-hidden bg-white border border-gray-200 rounded-lg aspect-square">
            <?php if (isset($category['image']) && !empty($category['image'])): ?>
              <img src="/assets/images/products/<?= $category['image'] ?>" alt="<?= htmlspecialchars($category['name']) ?>" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
            <?php else: ?>
              <div class="flex items-center justify-center w-full h-full bg-gray-100">
                <span class="text-gray-400 fas fa-image fa-3x"></span>
              </div>
            <?php endif; ?>
          </div>
          <p class="text-sm font-medium text-center text-gray-800 group-hover:text-red-600"><?= htmlspecialchars($category['name']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="py-12">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-gray-800">Nos produits vedettes</h2>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($featuredProducts as $product): ?>
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
          <a href="/product/<?= $product['slug'] ?>" class="block">
            <!-- Updated image container with fixed aspect ratio -->
            <div class="relative overflow-hidden bg-gray-100" style="padding-bottom: 100%;">
              <?php if (isset($product['images']) && !empty($product['images'])): ?>
                <img
                  src="/assets/images/products/<?= $product['images'][0] ?>"
                  alt="<?= htmlspecialchars($product['name']) ?>"
                  class="absolute top-0 left-0 object-contain w-full h-full">
              <?php else: ?>
                <div class="absolute top-0 left-0 flex items-center justify-center w-full h-full">
                  <span class="text-gray-400 fas fa-image fa-3x"></span>
                </div>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <h3 class="mb-2 text-sm font-medium text-gray-800"><?= htmlspecialchars($product['name']) ?></h3>
              <div class="flex items-baseline">
                <span class="mr-2 text-lg font-semibold price-color"><?= number_format($product['price'], 2, ',', ' ') ?> €</span>
                <?php if (isset($product['old_price']) && $product['old_price'] > 0): ?>
                  <span class="text-sm text-gray-500 line-through"><?= number_format($product['old_price'], 2, ',', ' ') ?> €</span>
                <?php endif; ?>
              </div>

              <?php if (isset($product['level'])): ?>
                <div class="mt-2">
                  <span class="inline-block px-2 py-1 text-xs text-white bg-green-600 rounded">
                    Niveau <?= htmlspecialchars($product['level']) ?>
                  </span>
                </div>
              <?php endif; ?>
            </div>
          </a>
          <!--<div class="px-4 pb-4">
            <form action="/cart/buy-now" method="POST">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <input type="hidden" name="quantity" value="1">
              <button
                type="submit"
                class="w-full py-2 text-sm text-white transition rounded-full singer-red hover:bg-red-700"
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'disabled' : '' ?>>
                <?= (isset($product['stock']) && $product['stock'] <= 0) ? 'Indisponible' : 'Acheter cet article' ?>
              </button>
            </form>
          </div>-->
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-8 text-center">
      <a href="/products" class="px-6 py-3 transition border rounded-full singer-red-text singer-red-border hover:bg-red-600 hover:text-white">Voir tous les produits</a>
    </div>
  </div>
</section>

<!-- Brand Story Section -->
<section class="py-12 bg-white">
  <div class="px-4 site-container">
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <div>
        <h2 class="mb-4 text-2xl font-normal text-gray-800">Singer, une marque emblématique depuis 1851</h2>
        <p class="mb-4 text-gray-700">Depuis plus de 170 ans, Singer accompagne les couturiers du monde entier. Notre histoire riche et notre engagement envers l'innovation ont fait de nous une référence incontournable dans l'univers de la couture.</p>
        <p class="mb-6 text-gray-700">Nos machines sont conçues pour répondre aux besoins de tous les niveaux, du débutant à l'expert, en offrant une qualité supérieure et une facilité d'utilisation incomparable.</p>
        <a href="/page/la-marque" class="inline-flex items-center text-sm singer-red-text hover:underline">
          Découvrir notre histoire
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>
      <div>
        <img src="/assets/images/singer-heritage.jpg" alt="Histoire Singer" class="object-cover w-full h-full rounded-lg shadow-md">
      </div>
    </div>
  </div>
</section>

<!-- Tutorials and Tips Section -->
<section class="w-full py-12 mt-0 bg-[#fff4ee]">
  <div class="px-4 site-container">
    <h2 class="mb-8 text-2xl font-normal text-center text-gray-800">Tutos & conseils</h2>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
      <!-- First tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto1.jpg" alt="Gilet materlassé" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800">Gilet materlassé</h3>
          <p class="mb-4 text-sm text-gray-700">Apprenez à réaliser un gilet matelassé élégant et confortable, idéal pour les journées fraîches.</p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            Découvrir ce tutoriel
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Second tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto2.jpg" alt="Entretenir sa machine à coudre" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800">Entretenir sa machine à coudre</h3>
          <p class="mb-4 text-sm text-gray-700">Découvrez nos conseils pour entretenir votre machine à coudre et prolonger sa durée de vie.</p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            Découvrir ce tutoriel
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Third tutorial -->
      <div class="relative overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
        <img src="/assets/images/tuto3.jpg" alt="Coudre un tote bag" class="object-cover w-full h-48" />
        <div class="p-4">
          <h3 class="mb-2 text-lg font-medium text-gray-800">Coudre un tote bag</h3>
          <p class="mb-4 text-sm text-gray-700">Suivez notre guide étape par étape pour réaliser un tote bag personnalisé en moins d'une heure.</p>
          <a href="#" class="flex items-center text-sm singer-red-text hover:underline">
            Découvrir ce tutoriel
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div class="mt-8 text-center">
      <a href="#" class="px-6 py-3 transition border rounded-full singer-red-text singer-red-border hover:bg-red-600 hover:text-white">Tous les tutoriels</a>
    </div>
  </div>
</section>

<!-- Newsletter Section -->
<section class="py-12 bg-gray-100">
  <div class="px-4 site-container">
    <div class="max-w-2xl p-8 mx-auto text-center bg-white rounded-lg shadow-sm">
      <h2 class="mb-2 text-2xl font-normal text-gray-800">Restez inspiré</h2>
      <p class="mb-6 text-gray-700">Inscrivez-vous à notre newsletter pour recevoir des conseils, des tutoriels et des offres exclusives.</p>

      <form class="flex flex-col sm:flex-row">
        <input type="email" placeholder="Votre adresse email" class="flex-1 px-4 py-2 mb-2 border border-gray-300 rounded-l sm:mb-0 focus:outline-none focus:ring-2 focus:ring-red-500">
        <button type="submit" class="px-6 py-2 font-medium text-white transition rounded-r singer-red hover:bg-red-700">S'inscrire</button>
      </form>
    </div>
  </div>
</section>