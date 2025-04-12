<?php
// app/views/orders/details.php

// Get order data
$order = $order ?? null;

// Helper function for formatting order status
function getStatusClass($status)
{
  switch ($status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'processing':
      return 'bg-blue-100 text-blue-800';
    case 'shipped':
      return 'bg-indigo-100 text-indigo-800';
    case 'delivered':
      return 'bg-green-100 text-green-800';
    case 'cancelled':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
}

function getStatusLabel($status)
{
  switch ($status) {
    case 'pending':
      return 'En attente';
    case 'processing':
      return 'En cours';
    case 'shipped':
      return 'Expédié';
    case 'delivered':
      return 'Livré';
    case 'cancelled':
      return 'Annulé';
    default:
      return ucfirst($status);
  }
}

// Parse shipping and billing addresses from JSON
$shippingAddress = isset($order['shipping_address']) ? json_decode($order['shipping_address'], true) : [];
$billingAddress = isset($order['billing_address']) ? json_decode($order['billing_address'], true) : [];

// Get order items
$orderItems = isset($order['items']) ? $order['items'] : [];
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <div class="flex items-center mb-2">
        <a href="/account/orders" class="mr-2 text-sm text-gray-600 hover:text-red-600">
          <i class="mr-1 fas fa-chevron-left"></i> Retour aux commandes
        </a>
      </div>
      <h1 class="text-2xl font-normal text-gray-800">Commande #<?= $order['id'] ?></h1>
      <p class="text-sm text-gray-600">
        Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar navigation -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <nav class="space-y-1">
            <a href="/account" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-tachometer-alt"></i>
              Tableau de bord
            </a>
            <a href="/account/orders" class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md singer-red">
              <i class="w-5 mr-2 fas fa-shopping-bag"></i>
              Mes commandes
            </a>
            <a href="/account/profile" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-user"></i>
              Mon profil
            </a>
            <a href="/account/addresses" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-map-marker-alt"></i>
              Mes adresses
            </a>
            <div class="pt-4 mt-4 border-t border-gray-200">
              <a href="/logout" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
                <i class="w-5 mr-2 fas fa-sign-out-alt"></i>
                Déconnexion
              </a>
            </div>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <div class="md:col-span-3">
        <?php if (!$order): ?>
          <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
            <i class="mb-2 text-3xl text-gray-300 fas fa-exclamation-circle"></i>
            <p class="text-gray-600">Commande non trouvée.</p>
            <a href="/account/orders" class="inline-block px-4 py-2 mt-4 text-sm text-white transition rounded-full singer-red hover:bg-red-700">
              Voir toutes mes commandes
            </a>
          </div>
        <?php else: ?>
          <!-- Order Status and Summary -->
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-col justify-between md:flex-row md:items-center">
              <div>
                <h2 class="text-lg font-medium text-gray-800">Résumé de la commande</h2>
                <p class="mt-1 text-sm text-gray-600">
                  Commande passée le <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                </p>
              </div>
              <div class="mt-4 md:mt-0">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?= getStatusClass($order['status']) ?>">
                  <?= getStatusLabel($order['status']) ?>
                </span>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 pt-4 mt-6 border-t border-gray-200 md:grid-cols-2">
              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700">Méthode de paiement</h3>
                <p class="text-sm text-gray-600">
                  <?php
                  $paymentMethod = 'Non spécifié';
                  if (isset($order['payment_method'])) {
                    switch ($order['payment_method']) {
                      case 'card':
                        $paymentMethod = 'Carte bancaire';
                        break;
                      case 'paypal':
                        $paymentMethod = 'PayPal';
                        break;
                      default:
                        $paymentMethod = ucfirst($order['payment_method']);
                    }
                  }
                  echo $paymentMethod;
                  ?>
                </p>
              </div>
              <div>
                <h3 class="mb-2 text-sm font-medium text-gray-700">Total</h3>
                <p class="text-sm font-semibold price-color">
                  <?= number_format($order['total'], 2, ',', ' ') ?> €
                </p>
              </div>
            </div>
          </div>

          <!-- Order Items -->
          <div class="p-6 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <h2 class="mb-4 text-lg font-medium text-gray-800">Produits commandés</h2>
            <?php if (empty($orderItems)): ?>
              <p class="text-gray-600">Aucun produit dans cette commande.</p>
            <?php else: ?>
              <div class="border-t border-gray-200">
                <?php foreach ($orderItems as $item): ?>
                  <div class="flex items-start py-4 border-b border-gray-200 last:border-b-0">
                    <div class="flex-shrink-0 w-16 h-16 mr-4 overflow-hidden bg-gray-100 rounded">
                      <?php if (isset($item['image_url'])): ?>
                        <img src="<?= $item['image_url'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="object-cover w-full h-full">
                      <?php else: ?>
                        <div class="flex items-center justify-center w-full h-full text-gray-400">
                          <i class="fas fa-box"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="flex-1">
                      <h3 class="text-sm font-medium text-gray-800"><?= htmlspecialchars($item['name']) ?></h3>
                      <p class="mt-1 text-sm text-gray-600">
                        Quantité: <?= $item['quantity'] ?>
                      </p>
                      <?php if (isset($item['attributes']) && !empty($item['attributes'])):
                        $attributes = is_string($item['attributes']) ? json_decode($item['attributes'], true) : $item['attributes'];
                        if ($attributes && is_array($attributes) && !empty($attributes)):
                      ?>
                          <div class="mt-1 text-sm text-gray-600">
                            <?php foreach ($attributes as $key => $value): ?>
                              <span class="mr-2"><?= htmlspecialchars(ucfirst($key)) ?>: <?= htmlspecialchars($value) ?></span>
                            <?php endforeach; ?>
                          </div>
                      <?php
                        endif;
                      endif;
                      ?>
                    </div>
                    <div class="ml-4 text-right">
                      <p class="text-sm font-medium price-color">
                        <?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> €
                      </p>
                      <p class="mt-1 text-xs text-gray-500">
                        <?= number_format($item['price'], 2, ',', ' ') ?> € / unité
                      </p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Shipping and Billing Information -->
          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <?php if (!empty($shippingAddress)): ?>
              <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-medium text-gray-800">Adresse de livraison</h2>
                <p class="text-sm text-gray-600">
                  <?= htmlspecialchars($shippingAddress['first_name'] . ' ' . $shippingAddress['last_name']) ?><br>
                  <?= htmlspecialchars($shippingAddress['address']) ?><br>
                  <?php if (!empty($shippingAddress['address2'])): ?>
                    <?= htmlspecialchars($shippingAddress['address2']) ?><br>
                  <?php endif; ?>
                  <?= htmlspecialchars($shippingAddress['postal_code'] . ' ' . $shippingAddress['city']) ?><br>
                  <?= htmlspecialchars($shippingAddress['country']) ?><br>
                  <?php if (!empty($shippingAddress['phone'])): ?>
                    Tél: <?= htmlspecialchars($shippingAddress['phone']) ?>
                  <?php endif; ?>
                </p>
              </div>
            <?php endif; ?>

            <?php if (!empty($billingAddress)): ?>
              <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="mb-4 text-lg font-medium text-gray-800">Adresse de facturation</h2>
                <p class="text-sm text-gray-600">
                  <?= htmlspecialchars($billingAddress['first_name'] . ' ' . $billingAddress['last_name']) ?><br>
                  <?= htmlspecialchars($billingAddress['address']) ?><br>
                  <?php if (!empty($billingAddress['address2'])): ?>
                    <?= htmlspecialchars($billingAddress['address2']) ?><br>
                  <?php endif; ?>
                  <?= htmlspecialchars($billingAddress['postal_code'] . ' ' . $billingAddress['city']) ?><br>
                  <?= htmlspecialchars($billingAddress['country']) ?><br>
                  <?php if (!empty($billingAddress['phone'])): ?>
                    Tél: <?= htmlspecialchars($billingAddress['phone']) ?>
                  <?php endif; ?>
                </p>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>