<?php
// Définition du titre de la page
$pageTitle = 'Tutos & conseils';

// Définition du contenu de la page
$pageContent = <<<HTML
<div class="mb-12 text-center">
  <div class="inline-block mb-6">
    <svg class="w-10 h-10 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M3 7v10a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V7m-18 0a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3m-18 0v10a3 3 0 0 0 3 3m15-13v10a3 3 0 0 1-3 3"></path>
      <path d="M12 7v13M9 14l-3-3 3-3M15 14l3-3-3-3"></path>
    </svg>
  </div>
  <h1 class="text-3xl font-bold text-gray-800">Tutos & conseils</h1>
</div>

<div class="grid grid-cols-1 gap-6 mt-8 md:grid-cols-2 lg:grid-cols-4">
  <!-- Tuto 1 -->
  <div class="flex flex-col">
    <a href="#" class="block mb-4 overflow-hidden rounded-lg">
      <img src="/assets/images/tutos1.jpg" alt="Connaître nos machines" class="object-cover w-full h-56 transition-transform duration-300 hover:scale-105">
    </a>
    <h2 class="mb-3 font-serif text-xl italic text-gray-800">Connaître nos machines</h2>
    <p class="text-sm text-gray-600">Découvrez des vidéos de prise en main et les démonstration de nos machines à coudre, surjeteuses, brodeuses.</p>
  </div>
  
  <!-- Tuto 2 -->
  <div class="flex flex-col">
    <a href="#" class="block mb-4 overflow-hidden rounded-lg">
      <img src="/assets/images/tutos2.jpg" alt="Apprendre à coudre" class="object-cover w-full h-56 transition-transform duration-300 hover:scale-105">
    </a>
    <h2 class="mb-3 font-serif text-xl italic text-gray-800">Apprendre à coudre</h2>
    <p class="text-sm text-gray-600">Coudre un biais, un ourlet ou encore réaliser une boutonnières, retrouvez des vidéos instructives pour vous faciliter la couture !</p>
  </div>
  
  <!-- Tuto 3 -->
  <div class="flex flex-col">
    <a href="#" class="block mb-4 overflow-hidden rounded-lg">
      <img src="/assets/images/tutos3.jpg" alt="Tutos DIY" class="object-cover w-full h-56 transition-transform duration-300 hover:scale-105">
    </a>
    <h2 class="mb-3 font-serif text-xl italic text-gray-800">Tutos DIY</h2>
    <p class="text-sm text-gray-600">Inspirez vous de nos nombreux tutos pour réaliser des vêtements, accessoires ou décorations tendances.</p>
  </div>
  
  <!-- Tuto 4 -->
  <div class="flex flex-col">
    <a href="#" class="block mb-4 overflow-hidden rounded-lg">
      <img src="/assets/images/tutos4.jpg" alt="Tutos upcycling" class="object-cover w-full h-56 transition-transform duration-300 hover:scale-105">
    </a>
    <h2 class="mb-3 font-serif text-xl italic text-gray-800">Tutos upcycling</h2>
    <p class="text-sm text-gray-600">Transformer un vêtement que vous ne portez plus c'est possible grâce à votre machine à coudre. Ne jetez plus, upcyclez !</p>
  </div>
</div>

<!-- Section additionnelle si nécessaire -->
<div class="mt-16 text-center">
  <p class="mb-6 text-gray-600">Retrouvez aussi nos tutoriels sur notre chaîne YouTube</p>
  <a href="https://www.youtube.com/singerfrance" target="_blank" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
    </svg>
    Voir notre chaîne YouTube
  </a>
</div>
HTML;
