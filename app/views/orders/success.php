<?php
// app/views/orders/success.php

// Get order data
$order = $order ?? null;

// Get shipping method details if available
$shippingMethodCode = $order['shipping_method'] ?? 'colissimo';
$shippingMethod = \App\Helpers\Shipping::getMethod($shippingMethodCode);
$shippingMethodName = $shippingMethod ? $shippingMethod['name'] : 'Standard';
$shippingCost = isset($order['shipping_cost']) ? $order['shipping_cost'] : 0;
$estimatedDelivery = $shippingMethod ? $shippingMethod['estimated_delivery'] : '3-5 jours ouvrés';
?>

<div class="px-4 py-12 site-container">
  <div class="max-w-3xl mx-auto">
    <div class="p-8 text-center bg-white border border-gray-200 rounded-lg shadow-sm">
      <!-- Success icon -->
      <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-white bg-green-500 rounded-full">
        <i class="text-2xl fas fa-check"></i>
      </div>

      <h1 class="mb-4 text-2xl font-medium text-gray-800">Merci pour votre commande !</h1>

      <p class="mb-6 text-gray-600">
        Votre commande #<?= $order['id'] ?? '---' ?> a été confirmée et est en cours de traitement.
        Un email de confirmation a été envoyé à votre adresse.
      </p>

      <!-- Order details summary -->
      <div class="p-6 mb-6 text-left rounded-lg bg-gray-50">
        <h2 class="mb-4 text-lg font-medium text-gray-800">Détails de la commande</h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Date de la commande</h3>
            <p class="text-gray-600">
              <?= isset($order['created_at']) ? date('d/m/Y à H:i', strtotime($order['created_at'])) : date('d/m/Y à H:i') ?>
            </p>
          </div>

          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Montant total</h3>
            <p class="text-gray-600">
              <?= isset($order['total']) ? number_format($order['total'], 2, ',', ' ') . ' €' : '---' ?>
            </p>
          </div>

          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Méthode de paiement</h3>
            <p class="text-gray-600">
              <?php
              if (isset($order['payment_method'])) {
                switch ($order['payment_method']) {
                  case 'card':
                    echo 'Carte bancaire';
                    break;
                  case 'paypal':
                    echo 'PayPal';
                    break;
                  default:
                    echo ucfirst($order['payment_method']);
                }
              } else {
                echo 'Carte bancaire';
              }
              ?>
            </p>
          </div>

          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Statut</h3>
            <p>
              <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                Confirmée
              </span>
            </p>
          </div>

          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Mode de livraison</h3>
            <p class="text-gray-600">
              <?= htmlspecialchars($shippingMethodName) ?>
              (<?= $shippingCost > 0 ? number_format($shippingCost, 2, ',', ' ') . ' €' : 'Gratuit' ?>)
            </p>
          </div>

          <div>
            <h3 class="mb-2 text-sm font-medium text-gray-700">Délai de livraison estimé</h3>
            <p class="text-gray-600">
              <?= htmlspecialchars($estimatedDelivery) ?>
            </p>
          </div>
        </div>

        <?php if (isset($order['shipping_address'])): ?>
          <?php
          $shippingAddress = json_decode($order['shipping_address'], true);
          if ($shippingAddress):
          ?>
            <div class="pt-4 mt-4 border-t border-gray-200">
              <h3 class="mb-2 text-sm font-medium text-gray-700">Adresse de livraison</h3>
              <p class="text-gray-600">
                <?= htmlspecialchars($shippingAddress['first_name'] . ' ' . $shippingAddress['last_name']) ?><br>
                <?= htmlspecialchars($shippingAddress['address']) ?><br>
                <?php if (!empty($shippingAddress['address2'])): ?>
                  <?= htmlspecialchars($shippingAddress['address2']) ?><br>
                <?php endif; ?>
                <?= htmlspecialchars($shippingAddress['postal_code'] . ' ' . $shippingAddress['city']) ?><br>
                <?= htmlspecialchars($shippingAddress['country']) ?>
              </p>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>

      <!-- Next steps -->
      <div class="mb-8 text-left">
        <h2 class="mb-4 text-lg font-medium text-gray-800">Prochaines étapes</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
          <div class="p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center justify-center w-10 h-10 mb-3 text-white rounded-full singer-red">
              <i class="fas fa-shipping-fast"></i>
            </div>
            <h3 class="mb-1 text-sm font-medium text-gray-800">Préparation de la commande</h3>
            <p class="text-xs text-gray-600">
              Votre commande est en cours de préparation dans nos entrepôts
            </p>
          </div>

          <div class="p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center justify-center w-10 h-10 mb-3 text-white rounded-full singer-red">
              <i class="fas fa-truck"></i>
            </div>
            <h3 class="mb-1 text-sm font-medium text-gray-800">Expédition</h3>
            <p class="text-xs text-gray-600">
              Vous recevrez un email dès que votre colis sera expédié
            </p>
          </div>

          <div class="p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center justify-center w-10 h-10 mb-3 text-white rounded-full singer-red">
              <i class="fas fa-box-open"></i>
            </div>
            <h3 class="mb-1 text-sm font-medium text-gray-800">Livraison</h3>
            <p class="text-xs text-gray-600">
              <?= $shippingMethod ? htmlspecialchars($shippingMethod['estimated_delivery']) : 'Livraison estimée sous 3 à 5 jours ouvrés' ?>
            </p>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
        <a href="/account/orders" class="flex-1 px-6 py-3 font-medium text-center text-white transition rounded-full singer-red hover:bg-red-700">
          Voir mes commandes
        </a>
        <a href="/products" class="flex-1 px-6 py-3 font-medium text-center transition border rounded-full singer-red-text singer-red-border hover:bg-red-600 hover:text-white">
          Continuer mes achats
        </a>
      </div>
    </div>
  </div>
</div>