<?php
// app/Controllers/PageController.php

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
  public function show($slug)
  {
    // In a real application, you would fetch the page content from a database
    // For this example, we'll use a simple switch statement

    switch ($slug) {
      case 'la-marque':
        $pageTitle = "La Marque Singer";
        $pageContent = $this->getAboutContent();
        break;
      case 'livraison':
        $pageTitle = "Informations de Livraison";
        $pageContent = $this->getDeliveryContent();
        break;
      case 'conditions-generales':
        $pageTitle = "Conditions Générales de Vente";
        $pageContent = $this->getTermsContent();
        break;
      default:
        return $this->view('error/404', [
          'message' => 'Page non trouvée',
          'title' => '404 - Page non trouvée'
        ]);
    }

    // SEO meta data
    $metaDescription = "Singer, la marque de référence en matière de couture depuis 1851. Découvrez notre histoire, nos valeurs et notre engagement envers la qualité.";

    $this->view('page/show', [
      'title' => $pageTitle,
      'content' => $pageContent,
      'metaDescription' => $metaDescription,
      'slug' => $slug
    ]);
  }

  private function getAboutContent()
  {
    return [
      'hero' => [
        'title' => 'La Marque Singer',
        'subtitle' => 'Plus de 170 ans d\'histoire et d\'innovation',
        'image' => '/assets/images/pages/about-hero.jpg'
      ],
      'sections' => [
        [
          'title' => 'Notre Histoire',
          'content' => '<p>Fondée en 1851 par Isaac Merritt Singer, la marque Singer a révolutionné le monde de la couture en créant la première machine à coudre domestique accessible au grand public. Depuis plus de 170 ans, Singer accompagne les générations de couturiers, du débutant à l\'expert, dans leurs créations quotidiennes.</p>
                    <p>L\'innovation a toujours été au cœur de notre démarche. Des premières machines à coudre à pédale aux modèles électroniques actuels, Singer n\'a cessé de développer des technologies pour rendre la couture plus simple et plus créative.</p>',
          'image' => '/assets/images/pages/about-history.jpg',
          'image_position' => 'right'
        ],
        [
          'title' => 'Notre Engagement',
          'content' => '<p>Chez Singer, nous sommes engagés à fournir des produits de qualité supérieure qui répondent aux besoins de tous les couturiers. Chaque machine est conçue avec soin pour garantir une expérience de couture optimale, quelle que soit votre niveau d\'expertise.</p>
                    <p>Nous sommes fiers de notre héritage et de la confiance que nos clients nous accordent depuis des générations. C\'est pourquoi nous continuons à investir dans la recherche et le développement pour proposer des machines innovantes et performantes.</p>',
          'image' => '/assets/images/pages/about-commitment.jpg',
          'image_position' => 'left'
        ],
        [
          'title' => 'Nos Valeurs',
          'content' => '<p>L\'innovation, la qualité et l\'accessibilité sont au cœur de nos valeurs. Nous croyons que la couture doit être accessible à tous, quel que soit le niveau ou le budget. C\'est pourquoi nous proposons une large gamme de machines adaptées à tous les besoins.</p>
                    <p>La satisfaction de nos clients est notre priorité. Nous nous engageons à offrir un service client exceptionnel et à accompagner nos utilisateurs dans leur parcours créatif.</p>',
          'image' => '/assets/images/pages/about-values.jpg',
          'image_position' => 'right'
        ]
      ],
      'cta' => [
        'title' => 'Découvrez nos machines',
        'button_text' => 'Voir tous nos produits',
        'button_url' => '/products'
      ]
    ];
  }

  private function getDeliveryContent()
  {
    // Similar structure to the about content, but with delivery information
    // You would implement this for your other static pages
    return [];
  }

  private function getTermsContent()
  {
    // Similar structure for terms and conditions
    return [];
  }
}
