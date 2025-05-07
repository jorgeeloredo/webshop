<?php
// app/config/navigation.php

return [
  'main_menu' => [
    [
      'name' => 'Nos machines',
      'url' => '/products',
      'submenu' => [
        [
          'name' => 'Mécaniques',
          'url' => '/category/machines-mecaniques',
          'image' => '/assets/images/products/machine_%C3%A0_coudre_madam_2-0.jpg'
        ],
        [
          'name' => 'Électroniques',
          'url' => '/category/machines-electroniques',
          'image' => '/assets/images/products/machine_%C3%A0_coudre_supera_f687c-0.jpg'
        ],
        [
          'name' => 'Surjeteuses & Recouvreuses',
          'url' => '/category/surjeteuses-recouvreuses',
          'image' => '/assets/images/products/surjeteuse_s0555-0.jpg'
        ],
        [
          'name' => 'Brodeuses',
          'url' => '/category/brodeuses',
          'image' => '/assets/images/products/brodeuse_em_9305-0.jpg'
        ],
        [
          'name' => 'Accessoires',
          'url' => '/category/accessoires',
          'image' => '/assets/images/products/valise_de_transport_pour_machine_%C3%A0_coudre-1.jpg'
        ]
      ]
    ],
    [
      'name' => 'Autres produits',
      'url' => '#',
      'submenu' => [
        [
          'name' => 'Soin du linge',
          'url' => '/category/soin-du-linge',
          'image' => '/assets/images/products/fer_à_repasser_steamcraft-0.jpg'
        ],
        [
          'name' => 'Électroménager',
          'url' => '/category/electromenager',
          'image' => '/assets/images/products/extracteur_de_jus_sjw-200-0.jpg'
        ],
        [
          'name' => 'Soin du sol',
          'url' => '/category/soin-du-sol',
          'image' => '/assets/images/products/aspirateur_balais_sonic_vc_250-0.jpg'
        ]
      ]
    ],
    [
      'name' => 'Tutos & conseils',
      'url' => '/page/tutos',
      'submenu' => []
    ],
    [
      'name' => 'La marque',
      'url' => '/page/la-marque',
      'submenu' => []
    ],
    [
      'name' => 'Actualités',
      'url' => '/page/actualites',
      'submenu' => []
    ],
  ]
];
