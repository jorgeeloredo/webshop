<?php
// Define the page title
$pageTitle = 'Nous Contacter';

// Define the page content
$pageContent = <<<HTML
<h1 class="text-2xl font-normal text-gray-800 mb-6">Nous Contacter</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    <!-- Contact Information Column -->
    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-medium mb-3">Nos Coordonnées</h2>
            <div class="space-y-3 text-gray-700">
                <p class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600 flex-shrink-0 mt-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    <span>
                        <strong>Adresse:</strong><br>
                        Singer Shop<br>
                        123 Avenue de la Couture<br>
                        75001 Paris, France
                    </span>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    <span><strong>Téléphone:</strong> 01 23 45 67 89</span>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-600 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                    <span><strong>Email:</strong> contact@singershop.fr</span>
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium mb-3">Horaires d'Ouverture</h2>
            <div class="space-y-1 text-gray-700">
                <p class="flex justify-between">
                    <span>Lundi - Vendredi:</span>
                    <span>9h00 - 18h30</span>
                </p>
                <p class="flex justify-between">
                    <span>Samedi:</span>
                    <span>10h00 - 17h00</span>
                </p>
                <p class="flex justify-between">
                    <span>Dimanche:</span>
                    <span>Fermé</span>
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-medium mb-3">Nos Services</h2>
            <ul class="list-disc list-inside space-y-1 text-gray-700">
                <li>Vente de machines à coudre Singer</li>
                <li>Réparation et entretien</li>
                <li>Pièces détachées et accessoires</li>
                <li>Conseils personnalisés</li>
                <li>Ateliers et formations</li>
            </ul>
        </div>
    </div>

    <!-- Contact Form Column -->
    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
        <h2 class="text-xl font-medium mb-4">Envoyez-nous un Message</h2>
        <form class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" required>
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                <select id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    <option value="question">Question générale</option>
                    <option value="order">Commande</option>
                    <option value="repair">Réparation</option>
                    <option value="workshop">Ateliers</option>
                    <option value="other">Autre</option>
                </select>
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500" required></textarea>
            </div>
            <div class="flex items-start">
                <input id="privacy" type="checkbox" class="h-4 w-4 text-red-600 border-gray-300 rounded mt-1" required>
                <label for="privacy" class="ml-2 block text-sm text-gray-700">
                    J'accepte que mes données soient utilisées pour traiter ma demande conformément à la politique de confidentialité.
                </label>
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition">
                Envoyer
            </button>
        </form>
    </div>
</div>

<!-- Map Section -->
<div class="mt-8">
    <h2 class="text-xl font-medium mb-4">Nous Trouver</h2>
    <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center">
        <!-- This is a placeholder for a map. In a real implementation, you would integrate Google Maps or another mapping service -->
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-gray-600">Carte interactive indisponible</p>
            <p class="text-sm text-gray-500">123 Avenue de la Couture, 75001 Paris, France</p>
        </div>
    </div>
</div>
HTML;
