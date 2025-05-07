<?php
// app/Controllers/PageController.php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
  /**
   * Affiche une page statique du répertoire des pages
   * 
   * @param string $slug Le nom de la page à afficher
   * @return void
   */
  public function show($slug = 'index')
  {
    // Sécuriser le nom de la page pour éviter les attaques de traversée de répertoire
    $slug = preg_replace('/[^a-zA-Z0-9_\-]/', '', $slug);

    // Définir le chemin vers le répertoire des pages
    $pagesDir = __DIR__ . '/../../pages/';

    // Vérifier si la page demandée existe
    $pageFile = $pagesDir . $slug . '.php';

    if (file_exists($pageFile)) {
      // Inclure la page pour obtenir son contenu et son titre
      // Le titre et le contenu sont définis dans le fichier de la page
      include $pageFile;

      // Assurer que $pageTitle et $pageContent sont définis
      $pageTitle = $pageTitle ?? 'Singer Shop';

      // Afficher la vue de la page
      $this->view('page/show', [
        'title' => $pageTitle,
        'content' => $pageContent ?? '',
        'slug' => $slug
      ]);
    } else {
      // Page non trouvée, afficher la page d'erreur 404
      $this->view('error/404', [
        'message' => 'Page non trouvée',
        'title' => '404 - Page non trouvée'
      ]);
    }
  }
}
