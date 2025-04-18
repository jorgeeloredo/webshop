<?php
// app/views/orders/checkout_redesigned.php

// Get cart data
$cart = $cart ?? null;
$totalPrice = $cart ? $cart->getTotalPrice() : 0;
$items = $cart ? $cart->getItems() : [];
$totalQuantity = $cart ? $cart->getTotalQuantity() : 0;

// Get user data if logged in
$isLoggedIn = \App\Helpers\Auth::check();
$user = $isLoggedIn ? \App\Helpers\Auth::user() : null;

// Get old input and errors if any
$old = $old ?? [];
$errors = $errors ?? [];

// Calculate shipping cost
$freeShippingThreshold = 300;
$shippingCost = ($totalPrice >= $freeShippingThreshold) ? 0 : 10;
$finalTotal = $totalPrice + $shippingCost;

// Function to generate image URL
function getImageUrl($image)
{
  return '/assets/images/products/' . $image;
}
?>

<div class="px-4 py-8 site-container">
  <div class="mb-6">
    <h1 class="text-2xl font-normal text-gray-800">Finaliser la commande</h1>
    <p class="text-sm text-gray-600">
      Veuillez remplir les informations ci-dessous pour finaliser votre commande
    </p>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-200 rounded-lg">
      <p class="font-medium">Veuillez corriger les erreurs suivantes :</p>
      <ul class="ml-4 list-disc">
        <?php foreach ($errors as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="/checkout" method="POST" class="mb-8">
    <!-- Checkout page content -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
      <!-- Left Side: Customer Information -->
      <div class="lg:col-span-7">
        <!-- Account Information Section (only show if not logged in) -->
        <?php if (!$isLoggedIn): ?>
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-medium text-gray-800">Mon compte</h2>
              <a href="/login" class="text-sm singer-red-text hover:underline">
                Déjà un compte ? Se connecter
              </a>
            </div>

            <div class="mb-4">
              <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email *</label>
              <input
                type="email"
                id="email"
                name="email"
                class="w-full px-3 py-2 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                required>
              <?php if (isset($errors['email'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['email']) ?></p>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label class="flex items-center">
                <input type="checkbox" id="create_account" name="create_account" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" <?= isset($old['create_account']) && $old['create_account'] ? 'checked' : '' ?>>
                <span class="ml-2 text-sm text-gray-700">Créer un compte avec ce mail</span>
              </label>
            </div>

            <div id="account_password_fields" class="<?= isset($old['create_account']) && $old['create_account'] ? '' : 'hidden' ?> space-y-4">
              <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Mot de passe</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="w-full px-3 py-2 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                <?php if (isset($errors['password'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['password']) ?></p>
                <?php else: ?>
                  <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                <?php endif; ?>
              </div>

              <div>
                <label for="password_confirm" class="block mb-1 text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input
                  type="password"
                  id="password_confirm"
                  name="password_confirm"
                  class="w-full px-3 py-2 border <?= isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                <?php if (isset($errors['password_confirm'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['password_confirm']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Customer Information Section -->
        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <h2 class="mb-4 text-xl font-medium text-gray-800">Informations personnelles</h2>

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- First Name -->
            <div>
              <label for="first_name" class="block mb-1 text-sm font-medium text-gray-700">Prénom *</label>
              <input
                type="text"
                id="first_name"
                name="first_name"
                class="w-full px-3 py-2 border <?= isset($errors['first_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['first_name'] ?? ($isLoggedIn ? $user['first_name'] : '')) ?>"
                required>
              <?php if (isset($errors['first_name'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['first_name']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Last Name -->
            <div>
              <label for="last_name" class="block mb-1 text-sm font-medium text-gray-700">Nom *</label>
              <input
                type="text"
                id="last_name"
                name="last_name"
                class="w-full px-3 py-2 border <?= isset($errors['last_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['last_name'] ?? ($isLoggedIn ? $user['last_name'] : '')) ?>"
                required>
              <?php if (isset($errors['last_name'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['last_name']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="sm:col-span-2">
              <label for="phone" class="block mb-1 text-sm font-medium text-gray-700">Téléphone *</label>
              <input
                type="tel"
                id="phone"
                name="phone"
                class="w-full px-3 py-2 border <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                required>
              <?php if (isset($errors['phone'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['phone']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Shipping Information Section -->
        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <h2 class="mb-4 text-xl font-medium text-gray-800">Adresse de livraison</h2>

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- First Name -->
            <div>
              <label for="shipping_first_name" class="block mb-1 text-sm font-medium text-gray-700">Prénom *</label>
              <input
                type="text"
                id="shipping_first_name"
                name="shipping_first_name"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_first_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_first_name'] ?? ($isLoggedIn ? $user['first_name'] : '')) ?>"
                required>
              <?php if (isset($errors['shipping_first_name'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_first_name']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Last Name -->
            <div>
              <label for="shipping_last_name" class="block mb-1 text-sm font-medium text-gray-700">Nom *</label>
              <input
                type="text"
                id="shipping_last_name"
                name="shipping_last_name"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_last_name']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_last_name'] ?? ($isLoggedIn ? $user['last_name'] : '')) ?>"
                required>
              <?php if (isset($errors['shipping_last_name'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_last_name']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Address -->
            <div class="sm:col-span-2">
              <label for="shipping_address" class="block mb-1 text-sm font-medium text-gray-700">Adresse *</label>
              <input
                type="text"
                id="shipping_address"
                name="shipping_address"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_address']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_address'] ?? '') ?>"
                required>
              <?php if (isset($errors['shipping_address'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_address']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Address 2 (optional) -->
            <div class="sm:col-span-2">
              <label for="shipping_address2" class="block mb-1 text-sm font-medium text-gray-700">Complément d'adresse</label>
              <input
                type="text"
                id="shipping_address2"
                name="shipping_address2"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_address2'] ?? '') ?>">
            </div>

            <!-- Postal Code -->
            <div>
              <label for="shipping_postal_code" class="block mb-1 text-sm font-medium text-gray-700">Code postal *</label>
              <input
                type="text"
                id="shipping_postal_code"
                name="shipping_postal_code"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_postal_code']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_postal_code'] ?? '') ?>"
                required>
              <?php if (isset($errors['shipping_postal_code'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_postal_code']) ?></p>
              <?php endif; ?>
            </div>

            <!-- City -->
            <div>
              <label for="shipping_city" class="block mb-1 text-sm font-medium text-gray-700">Ville *</label>
              <input
                type="text"
                id="shipping_city"
                name="shipping_city"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_city']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_city'] ?? '') ?>"
                required>
              <?php if (isset($errors['shipping_city'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_city']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Country -->
            <div>
              <label for="shipping_country" class="block mb-1 text-sm font-medium text-gray-700">Pays *</label>
              <select
                id="shipping_country"
                name="shipping_country"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_country']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                required>
                <option value="France" <?= (isset($old['shipping_country']) && $old['shipping_country'] === 'France') ? 'selected' : '' ?>>France</option>
                <option value="Belgique" <?= (isset($old['shipping_country']) && $old['shipping_country'] === 'Belgique') ? 'selected' : '' ?>>Belgique</option>
                <option value="Suisse" <?= (isset($old['shipping_country']) && $old['shipping_country'] === 'Suisse') ? 'selected' : '' ?>>Suisse</option>
                <option value="Luxembourg" <?= (isset($old['shipping_country']) && $old['shipping_country'] === 'Luxembourg') ? 'selected' : '' ?>>Luxembourg</option>
              </select>
              <?php if (isset($errors['shipping_country'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_country']) ?></p>
              <?php endif; ?>
            </div>

            <!-- Phone -->
            <div>
              <label for="shipping_phone" class="block mb-1 text-sm font-medium text-gray-700">Téléphone *</label>
              <input
                type="tel"
                id="shipping_phone"
                name="shipping_phone"
                class="w-full px-3 py-2 border <?= isset($errors['shipping_phone']) ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                value="<?= htmlspecialchars($old['shipping_phone'] ?? $old['phone'] ?? '') ?>"
                required>
              <?php if (isset($errors['shipping_phone'])): ?>
                <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['shipping_phone']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Billing Information Section -->
        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <div class="flex items-center mb-4">
            <input
              type="checkbox"
              id="same_as_billing"
              name="same_as_billing"
              class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
              checked>
            <label for="same_as_billing" class="block ml-2 text-sm font-medium text-gray-700">
              Utiliser la même adresse pour la facturation
            </label>
          </div>

          <div id="billing_form" class="hidden">
            <h3 class="mb-4 text-lg font-medium text-gray-800">Adresse de facturation</h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <!-- First Name -->
              <div>
                <label for="billing_first_name" class="block mb-1 text-sm font-medium text-gray-700">Prénom *</label>
                <input
                  type="text"
                  id="billing_first_name"
                  name="billing_first_name"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_first_name'] ?? '') ?>">
                <?php if (isset($errors['billing_first_name'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_first_name']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Last Name -->
              <div>
                <label for="billing_last_name" class="block mb-1 text-sm font-medium text-gray-700">Nom *</label>
                <input
                  type="text"
                  id="billing_last_name"
                  name="billing_last_name"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_last_name'] ?? '') ?>">
                <?php if (isset($errors['billing_last_name'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_last_name']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Address -->
              <div class="sm:col-span-2">
                <label for="billing_address" class="block mb-1 text-sm font-medium text-gray-700">Adresse *</label>
                <input
                  type="text"
                  id="billing_address"
                  name="billing_address"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_address'] ?? '') ?>">
                <?php if (isset($errors['billing_address'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_address']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Address 2 (optional) -->
              <div class="sm:col-span-2">
                <label for="billing_address2" class="block mb-1 text-sm font-medium text-gray-700">Complément d'adresse</label>
                <input
                  type="text"
                  id="billing_address2"
                  name="billing_address2"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_address2'] ?? '') ?>">
              </div>

              <!-- Postal Code -->
              <div>
                <label for="billing_postal_code" class="block mb-1 text-sm font-medium text-gray-700">Code postal *</label>
                <input
                  type="text"
                  id="billing_postal_code"
                  name="billing_postal_code"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_postal_code'] ?? '') ?>">
                <?php if (isset($errors['billing_postal_code'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_postal_code']) ?></p>
                <?php endif; ?>
              </div>

              <!-- City -->
              <div>
                <label for="billing_city" class="block mb-1 text-sm font-medium text-gray-700">Ville *</label>
                <input
                  type="text"
                  id="billing_city"
                  name="billing_city"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_city'] ?? '') ?>">
                <?php if (isset($errors['billing_city'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_city']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Country -->
              <div>
                <label for="billing_country" class="block mb-1 text-sm font-medium text-gray-700">Pays *</label>
                <select
                  id="billing_country"
                  name="billing_country"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200">
                  <option value="France" <?= (isset($old['billing_country']) && $old['billing_country'] === 'France') ? 'selected' : '' ?>>France</option>
                  <option value="Belgique" <?= (isset($old['billing_country']) && $old['billing_country'] === 'Belgique') ? 'selected' : '' ?>>Belgique</option>
                  <option value="Suisse" <?= (isset($old['billing_country']) && $old['billing_country'] === 'Suisse') ? 'selected' : '' ?>>Suisse</option>
                  <option value="Luxembourg" <?= (isset($old['billing_country']) && $old['billing_country'] === 'Luxembourg') ? 'selected' : '' ?>>Luxembourg</option>
                </select>
                <?php if (isset($errors['billing_country'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_country']) ?></p>
                <?php endif; ?>
              </div>

              <!-- Phone -->
              <div>
                <label for="billing_phone" class="block mb-1 text-sm font-medium text-gray-700">Téléphone *</label>
                <input
                  type="tel"
                  id="billing_phone"
                  name="billing_phone"
                  class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200"
                  value="<?= htmlspecialchars($old['billing_phone'] ?? '') ?>">
                <?php if (isset($errors['billing_phone'])): ?>
                  <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['billing_phone']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Method Section -->
        <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <h2 class="mb-4 text-xl font-medium text-gray-800">Méthode de paiement</h2>

          <div class="space-y-3">
            <div class="flex items-center p-3 border border-gray-200 rounded">
              <input
                type="radio"
                id="payment_card"
                name="payment_method"
                value="card"
                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                checked>
              <label for="payment_card" class="block ml-3 text-sm font-medium text-gray-700">
                Carte bancaire
              </label>
              <div class="flex items-center ml-auto space-x-2">
                <img src="/assets/images/visa.png" alt="Visa" class="h-6">
                <img src="/assets/images/mastercard.png" alt="Mastercard" class="h-6">
              </div>
            </div>

            <!--<div class="flex items-center p-3 border border-gray-200 rounded">
              <input
                type="radio"
                id="payment_paypal"
                name="payment_method"
                value="paypal"
                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
              <label for="payment_paypal" class="block ml-3 text-sm font-medium text-gray-700">
                PayPal
              </label>
              <div class="ml-auto">
                <img src="/assets/images/payments/paypal.svg" alt="PayPal" class="h-6">
              </div>
            </div>-->
          </div>

          <p class="flex items-center mt-4 text-sm text-gray-600">
            <i class="mr-2 text-green-600 fas fa-lock"></i>
            Paiement 100% sécurisé
          </p>
        </div>
      </div>

      <!-- Right Side: Order Summary -->
      <div class="lg:col-span-5">
        <div class="sticky p-6 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <h2 class="mb-4 text-xl font-medium text-gray-800">Récapitulatif de la commande</h2>

          <!-- Products List -->
          <div class="mb-4 space-y-4">
            <?php foreach ($items as $itemId => $item): ?>
              <div class="flex items-start pb-4 border-b border-gray-200 last:border-0 last:pb-0">
                <div class="flex-shrink-0 w-16 h-16 mr-4 overflow-hidden rounded bg-gray-50">
                  <?php if (isset($item['image']) && !empty($item['image'])): ?>
                    <img src="<?= getImageUrl($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-contain w-full h-full">
                  <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full">
                      <span class="text-gray-400 fas fa-image"></span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between">
                    <h3 class="text-sm font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                    <span class="text-sm font-medium price-color"><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €</span>
                  </div>
                  <div class="mt-1 text-sm text-gray-600">
                    <span>Quantité: <?= $item['quantity'] ?></span>
                    <?php if (!empty($item['attributes'])): ?>
                      <div class="mt-1">
                        <?php foreach ($item['attributes'] as $attribute => $value): ?>
                          <div><span class="font-medium"><?= htmlspecialchars(ucfirst($attribute)) ?>:</span> <?= htmlspecialchars($value) ?></div>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Price Summary -->
          <div class="pt-4 mb-6 space-y-2 border-t border-gray-200">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Sous-total</span>
              <span class="font-medium text-gray-800"><?= number_format($totalPrice, 2, ',', ' ') ?> €</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-600">Frais de livraison</span>
              <span class="font-medium text-gray-800">
                <?php if ($totalPrice >= $freeShippingThreshold): ?>
                  Gratuit
                <?php else: ?>
                  <?= number_format($shippingCost, 2, ',', ' ') ?> €
                <?php endif; ?>
              </span>
            </div>
            <div class="flex justify-between pt-4 mt-2 text-lg font-medium border-t border-gray-200">
              <span>Total</span>
              <span class="singer-red-text"><?= number_format($finalTotal, 2, ',', ' ') ?> €</span>
            </div>
          </div>

          <!-- Submit Order Button -->
          <button type="submit" class="w-full py-3 text-base font-medium text-center text-white transition rounded-full singer-red hover:bg-red-700">
            Commander
          </button>

          <p class="mt-4 text-xs text-center text-gray-500">
            En passant votre commande, vous acceptez nos <a href="/page/conditions-generales-de-vente" class="singer-red-text hover:underline">conditions générales de vente</a>
          </p>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle billing address toggle
    const sameAsBillingCheckbox = document.getElementById('same_as_billing');
    const billingForm = document.getElementById('billing_form');

    function toggleBillingForm() {
      if (sameAsBillingCheckbox.checked) {
        billingForm.classList.add('hidden');
      } else {
        billingForm.classList.remove('hidden');
      }
    }

    if (sameAsBillingCheckbox && billingForm) {
      sameAsBillingCheckbox.addEventListener('change', toggleBillingForm);
      toggleBillingForm(); // Initialize on page load
    }

    // Handle account creation toggle
    const createAccountCheckbox = document.getElementById('create_account');
    const passwordFields = document.getElementById('account_password_fields');

    function togglePasswordFields() {
      if (createAccountCheckbox.checked) {
        passwordFields.classList.remove('hidden');
      } else {
        passwordFields.classList.add('hidden');
      }
    }

    if (createAccountCheckbox && passwordFields) {
      createAccountCheckbox.addEventListener('change', togglePasswordFields);
      togglePasswordFields(); // Initialize on page load
    }

    // Copy shipping info to personal info fields
    const shippingFirstName = document.getElementById('shipping_first_name');
    const shippingLastName = document.getElementById('shipping_last_name');
    const shippingPhone = document.getElementById('shipping_phone');
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const phone = document.getElementById('phone');

    // Initial copy if fields are empty (for new users only)
    if (firstName && lastName && phone && shippingFirstName && shippingLastName && shippingPhone) {
      if (!firstName.value && shippingFirstName.value) {
        firstName.value = shippingFirstName.value;
      }
      if (!lastName.value && shippingLastName.value) {
        lastName.value = shippingLastName.value;
      }
      if (!phone.value && shippingPhone.value) {
        phone.value = shippingPhone.value;
      }

      // Copy shipping to personal info if they change
      shippingFirstName.addEventListener('change', function() {
        if (!firstName.value || firstName.value === shippingFirstName.value) {
          firstName.value = shippingFirstName.value;
        }
      });

      shippingLastName.addEventListener('change', function() {
        if (!lastName.value || lastName.value === shippingLastName.value) {
          lastName.value = shippingLastName.value;
        }
      });

      shippingPhone.addEventListener('change', function() {
        if (!phone.value || phone.value === shippingPhone.value) {
          phone.value = shippingPhone.value;
        }
      });

      // Copy personal info to shipping if they change
      firstName.addEventListener('change', function() {
        if (!shippingFirstName.value || shippingFirstName.value === firstName.value) {
          shippingFirstName.value = firstName.value;
        }
      });

      lastName.addEventListener('change', function() {
        if (!shippingLastName.value || shippingLastName.value === lastName.value) {
          shippingLastName.value = lastName.value;
        }
      });

      phone.addEventListener('change', function() {
        if (!shippingPhone.value || shippingPhone.value === phone.value) {
          shippingPhone.value = phone.value;
        }
      });
    }
  });
</script>