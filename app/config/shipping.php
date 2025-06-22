<?php
// app/config/shipping.php

return [
  'methods' => [
    'colissimo' => [
      'name' => 'Colissimo',
      'description' => 'Livraison en 72h ouvrées en France métropolitaine',
      'cost' => 9.90,
      'free_shipping_threshold' => 300,
      'estimated_delivery' => '3-4 jours ouvrés',
      'icon' => 'fas fa-box',
      'code' => 'colissimo',
      'active' => true,
    ],
    'dpd' => [
      'name' => 'DPD',
      'description' => 'Livraison standard économique en France métropolitaine',
      'cost' => 7.90,
      'free_shipping_threshold' => 300,
      'estimated_delivery' => '4-6 jours ouvrés',
      'icon' => 'fas fa-truck',
      'code' => 'dpd',
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
