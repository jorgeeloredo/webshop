<?php
// Définition du titre de la page
$pageTitle = 'Nos actualités';

// Définition du contenu de la page
$pageContent = <<<HTML
<div class="mb-12 text-center">
  <div class="inline-block mb-6">
    <svg class="w-10 h-10 mx-auto text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
      <circle cx="9" cy="9" r="1"></circle>
      <circle cx="15" cy="9" r="1"></circle>
    </svg>
  </div>
  <h1 class="text-3xl font-bold text-gray-800">Nos actualités</h1>
</div>

<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article1.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Portrait de couturière : Theneedlegang</h2>
        <p class="text-gray-600">Découvrez le portrait de notre nouvelle ambassadrice theneedlegang, influenceuse couture spécialisée dans l'upcycling.</p>
      </div>
    </a>
  </div>
  

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article2.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Portrait de couturière : Lucette.lr</h2>
        <p class="text-gray-600">Découvrez le portrait de notre nouvelle ambassadrice Lucette.lr, influenceuse couture.</p>
      </div>
    </a>
  </div>
  

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article3.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Comment choisir son tissu ? </h2>
        <p class="text-gray-600">Découvrez comment choisir son tissu selon vos projet de coutures et votre niveau. </p>
      </div>
    </a>
  </div>

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article4.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Les points de couture</h2>
        <p class="text-gray-600">Découvrez les différents points de couture et leurs caractéristiques. </p>
      </div>
    </a>
  </div>

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article5.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Comment bien choisir les canettes de votre machine à coudre ?</h2>
        <p class="text-gray-600">Découvrez la cannette, accessoire indispensable pour la machine à coudre, ses caractéristiques et son utilisation.</p>
      </div>
    </a>
  </div>

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
    <a href="#" class="block">
      <img src="/assets/images/article6.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Fashion Green Hub : pour une mode plus durable</h2>
        <p class="text-gray-600">Découvrez Fashion Green Hub, une association qui œuvre pour une mode plus durable, locale et circulaire, innovante et inclusive.</p>
      </div>
    </a>
  </div>
</div>


HTML;
