<?php
// Définition du titre de la page
$pageTitle = 'Vos avantages à commander sur singer-fr.com';

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
  <h1 class="text-3xl font-bold text-gray-800">Vos avantages, nos engagements</h1>
</div>

<div class="grid grid-cols-1 gap-8 md:grid-cols-2">
  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
      <img src="/assets/images/avantages1.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">Un support technique garanti</h2>
        <p class="text-gray-600">Singer dispose d'un réseau de points de vente en France qui assure la prise en main de votre machine à coudre mais aussi le service après-vente de vos produits. Pour contacter le magasin Singer le plus proche de chez vous veuillez consulter la carte de nos magasins !</p>
      </div>
  </div>
  

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
      <img src="/assets/images/avantages2.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">La réparabilité de nos machines</h2>
        <p class="text-gray-600">Singer s’engage à réparer vos produits et à en augmenter la durabilité, permettant ainsi de participer activement à la protection de l’environnement. Nous disposons d'un véritable centre d’expertise dirigé par des professionnels pouvant prendre en charge les vérifications, l’entretien et les réparations des produits Singer.</p>
      </div>
  </div>
  

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
      <img src="/assets/images/avantages3.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">L'expertise Singer</h2>
        <p class="text-gray-600">Depuis 1851, le nom Singer est synonyme de couture. Nous développons continuellement des produits adaptés à chaque niveau de couture afin de proposer la machine qui correspond aux envies et aux besoins de chacun. Le leadership de SINGER® dans l’industrie perdure aujourd’hui, en raison de notre engagement permanent envers la qualité, la fiabilité, l’innovation et le service.</p>
      </div>
  </div>

  <div class="overflow-hidden transition-transform duration-300 border border-gray-100 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1">
      <img src="/assets/images/avantages4.jpg" class="object-cover w-full h-64">
      <div class="p-5">
        <h2 class="mb-2 text-xl font-semibold text-gray-800">L'assistance Singer</h2>
        <p class="text-gray-600">Sur le site de Singer France, vous trouverez des conseils sur la prise en main de votre machine à coudre, des vidéos pour apprendre à coudre, des tutos DIY en accès gratuit adaptés à tous les niveaux de couture et les notices de vos machines à coudre téléchargeables sur chacune de nos fiches produit pour mieux comprendre leurs différentes fonctionnalités.</p>
      </div>
  </div>

</div>


HTML;
