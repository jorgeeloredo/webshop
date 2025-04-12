<?php
// app/views/orders/index.php

// Get user orders data
$orders = $orders ?? [];
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <h1 class="text-2xl font-normal text-gray-800">Mes commandes</h1>
      <p class="text-sm text-gray-600">
        Historique et suivi de vos commandes
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
        <?php if (empty($orders)): ?>
          <div class="p-6 text-center bg-white border border-gray-200 rounded-lg">
            <i class="mb-2 text-3xl text-gray-300 fas fa-shopping-bag"></i>
            <p class="text-gray-600">Vous n'avez pas encore passé de commande.</p>
            <a href="/products" class="inline-block px-4 py-2 mt-4 text-sm text-white transition rounded-full singer-red hover:bg-red-700">
              Découvrir nos produits
            </a>
          </div>
        <?php else: ?>
          <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                    N° commande
                  </th>
                  <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                    Date
                  </th>
                  <th scope="col" class="hidden px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:table-cell">
                    Statut
                  </th>
                  <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                    Total
                  </th>
                  <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($orders as $order): ?>
                  <tr>
                    <td class="px-4 py-4 whitespace-nowrap">
                      <span class="text-sm font-medium text-gray-900">#<?= $order['id'] ?></span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-500">
                        <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                      </span>
                    </td>
                    <td class="hidden px-4 py-4 whitespace-nowrap sm:table-cell">
                      <?php
                      $statusClass = '';
                      $statusLabel = '';
                      switch ($order['status']) {
                        case 'pending':
                          $statusClass = 'bg-yellow-100 text-yellow-800';
                          $statusLabel = 'En attente';
                          break;
                        case 'processing':
                          $statusClass = 'bg-blue-100 text-blue-800';
                          $statusLabel = 'En cours';
                          break;
                        case 'shipped':
                          $statusClass = 'bg-indigo-100 text-indigo-800';
                          $statusLabel = 'Expédié';
                          break;
                        case 'delivered':
                          $statusClass = 'bg-green-100 text-green-800';
                          $statusLabel = 'Livré';
                          break;
                        case 'cancelled':
                          $statusClass = 'bg-red-100 text-red-800';
                          $statusLabel = 'Annulé';
                          break;
                        default:
                          $statusClass = 'bg-gray-100 text-gray-800';
                          $statusLabel = ucfirst($order['status']);
                      }
                      ?>
                      <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full <?= $statusClass ?>">
                        <?= $statusLabel ?>
                      </span>
                    </td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                      <span class="text-sm text-gray-900">
                        <?= number_format($order['total'], 2, ',', ' ') ?> €
                      </span>
                    </td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                      <a href="/account/orders/<?= $order['id'] ?>" class="text-sm singer-red-text hover:underline">
                        Détails
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>