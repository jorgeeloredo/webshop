<?php
// Define the page title
$pageTitle = 'La Marque Singer | Singer Shop';

// Define the page content
$pageContent = <<<HTML
<div class="mx-auto" style="max-width: 1140px;">
    <h1 class="mb-6 text-2xl font-normal text-gray-800">La Marque Singer</h1>

    <div class="space-y-8">
        <div class="grid grid-cols-1 gap-8 mb-8 md:grid-cols-2">
            <div>
                <img src="/assets/images/singer-heritage.jpg" alt="Histoire Singer" class="w-full rounded-lg shadow-sm">
            </div>
            <div class="space-y-4">
                <h2 class="mb-2 text-xl font-medium">Une histoire riche depuis 1851</h2>
                <p class="text-gray-700">Fondée par Isaac Merritt Singer en 1851, la marque Singer a révolutionné le monde de la couture en créant la première machine à coudre domestique pratique et accessible. Cette innovation a transformé la façon dont les vêtements étaient fabriqués, tant dans les foyers que dans l'industrie.</p>
                <p class="text-gray-700">Au fil des décennies, Singer est devenu synonyme de qualité, de fiabilité et d'innovation dans l'univers de la couture. La marque a continué à évoluer, introduisant régulièrement de nouvelles technologies pour répondre aux besoins changeants des couturiers et couturières du monde entier.</p>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="mb-4 text-xl font-medium">Notre Engagement</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium">Qualité</h3>
                    </div>
                    <p class="text-gray-700">Chaque machine Singer est conçue avec des matériaux de haute qualité et soumise à des tests rigoureux pour garantir sa durabilité et ses performances.</p>
                </div>
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h3 class="text-lg font-medium">Innovation</h3>
                    </div>
                    <p class="text-gray-700">L'innovation est au cœur de notre ADN. Nous investissons continuellement dans la recherche et le développement pour offrir des technologies de pointe.</p>
                </div>
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-lg font-medium">Héritage</h3>
                    </div>
                    <p class="text-gray-700">Avec plus de 170 ans d'expérience, nous perpétuons un héritage riche tout en regardant vers l'avenir pour inspirer de nouvelles générations de créateurs.</p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="mb-4 text-xl font-medium">L'Innovation Singer à Travers les Époques</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Année</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Innovation</th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Impact</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">1851</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">Première machine à coudre à usage domestique</td>
                            <td class="px-6 py-4 text-sm text-gray-700">A démocratisé la couture à domicile</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">1889</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">Premier modèle électrique</td>
                            <td class="px-6 py-4 text-sm text-gray-700">A facilité et accéléré le processus de couture</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">1921</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">Machine portable Featherweight</td>
                            <td class="px-6 py-4 text-sm text-gray-700">A rendu la couture plus accessible grâce à sa légèreté</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">1975</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">Première machine électronique</td>
                            <td class="px-6 py-4 text-sm text-gray-700">A introduit une précision de couture inégalée</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">2010</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">Technologie d'écran tactile</td>
                            <td class="px-6 py-4 text-sm text-gray-700">A créé une interface intuitive pour tous les utilisateurs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="space-y-4">
                <h2 class="mb-2 text-xl font-medium">Notre Vision</h2>
                <p class="text-gray-700">Chez Singer, nous croyons en la puissance de la créativité et de l'expression personnelle. Notre vision est de continuer à développer des outils qui permettent à chacun de donner vie à ses idées, qu'il s'agisse de couturiers professionnels ou d'amateurs passionnés.</p>
                <p class="text-gray-700">Nous nous engageons à rester à l'avant-garde de l'innovation tout en restant fidèles à notre héritage de qualité et de fiabilité. Notre objectif est d'inspirer et d'équiper les créateurs du monde entier pour les générations à venir.</p>
                <div class="mt-4">
                    <a href="/page/nous-contacter" class="inline-block px-4 py-2 font-medium text-white transition bg-red-600 rounded-md hover:bg-red-700">
                        Contactez-nous
                    </a>
                </div>
            </div>
            <div>
                <img src="/assets/images/singer-future.jpg" alt="La vision Singer" class="w-full rounded-lg shadow-sm">
            </div>
        </div>
    </div>
</div>
HTML;
