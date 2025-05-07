<?php
// app/config/shipping.php

return [
  'methods' => [
    'chronopost' => [
      'name' => 'Chronopost',
      'description' => 'Livraison 24h garantie pour toute commande du lundi au vendredi avant 18h',
      'cost' => 14.90,
      'free_shipping_threshold' => 300,
      'estimated_delivery' => '1 jour ouvré',
      'icon' => 'fas fa-truck-fast',
      'code' => 'chronopost',
      'active' => true,
    ],
    'colissimo' => [
      'name' => 'Colissimo',
      'description' => 'Livraison en 48h ouvrées en France métropolitaine',
      'cost' => 9.90,
      'free_shipping_threshold' => 300,
      'estimated_delivery' => '2-3 jours ouvrés',
      'icon' => 'fas fa-box',
      'code' => 'colissimo',
      'active' => true,
    ],
    'dpd' => [
      'name' => 'DPD',
      'description' => 'Livraison standard économique en France métropolitaine',
      'cost' => 7.90,
      'free_shipping_threshold' => 300,
      'estimated_delivery' => '3-5 jours ouvrés',
      'icon' => 'fas fa-truck',
      'code' => 'dpd',
      'active' => true,
    ],
    'pickup' => [
      'name' => 'Retrait en magasin',
      'description' => 'Retrait gratuit dans notre magasin de Paris',
      'cost' => 0,
      'free_shipping_threshold' => 0,
      'estimated_delivery' => 'Sous 24h après confirmation',
      'icon' => 'fas fa-store',
      'code' => 'pickup',
      'active' => true,
    ],
  ],

  // Default shipping method (should match one of the codes above)
  'default_method' => 'colissimo',

  // Enable/disable free shipping 
  'enable_free_shipping' => true,

  // Free shipping applies to orders above this amount (in EUR)
  'free_shipping_threshold' => 300,

  // Free shipping applies to all methods or just specific ones
  'free_shipping_applies_to_all' => true,
];
