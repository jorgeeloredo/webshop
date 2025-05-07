<?php
// app/views/admin/orders.php

// Get success and error messages if any
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

// Clear session messages
if (isset($_SESSION['success'])) {
  unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
  unset($_SESSION['error']);
}
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <h1 class="text-2xl font-normal text-gray-800">Administration - Gestion des commandes</h1>
      <p class="text-sm text-gray-600">
        Gérez toutes les commandes de la boutique
      </p>
    </div>

    <?php if ($success): ?>
      <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-200 rounded-lg">
        <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-200 rounded-lg">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
      <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-800">Liste des commandes</h2>
        <div class="flex items-center space-x-2">
          <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200">
            <option value="">Tous les statuts</option>
            <?php foreach ($orderStatuses as $statusKey => $statusLabel): ?>
              <option value="<?= $statusKey ?>"><?= $statusLabel ?></option>
            <?php endforeach; ?>
          </select>
          <button id="applyFilter" class="px-4 py-2 text-white transition rounded-md singer-red hover:bg-red-700">
            Filtrer
          </button>
        </div>
      </div>

      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
              N° commande
            </th>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
              Date
            </th>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
              Client
            </th>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
              Statut
            </th>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
              Total
            </th>
            <th scope="col" class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                Aucune commande trouvée.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $order): ?>
              <tr class="order-row" data-status="<?= $order['status'] ?>">
                <td class="px-4 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900">#<?= $order['id'] ?></span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                  <span class="text-sm text-gray-500">
                    <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                  </span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                  <?php
                  $email = $order['email'] ?? 'N/A';
                  $isGuest = isset($order['guest_checkout']) && $order['guest_checkout'] ? ' (Invité)' : '';
                  ?>
                  <span class="text-sm text-gray-500"><?= htmlspecialchars($email) . $isGuest ?></span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                  <form action="/admin/update-order-status" method="POST" class="inline-flex">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <select
                      name="status"
                      class="px-2 py-1 text-xs border border-gray-300 rounded status-select focus:outline-none focus:ring-2 focus:ring-red-200"
                      onchange="this.parentNode.submit()">
                      <?php foreach ($orderStatuses as $statusKey => $statusLabel): ?>
                        <option value="<?= $statusKey ?>" <?= $order['status'] === $statusKey ? 'selected' : '' ?>>
                          <?= $statusLabel ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </form>
                </td>
                <td class="px-4 py-4 text-right whitespace-nowrap">
                  <span class="text-sm text-gray-900">
                    <?= number_format($order['total'], 2, ',', ' ') ?> €
                  </span>
                </td>
                <td class="px-4 py-4 text-right whitespace-nowrap">
                  <a href="/admin/orders/<?= $order['id'] ?>" class="text-sm singer-red-text hover:underline">
                    Détails
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterStatus = document.getElementById('filterStatus');
    const applyFilter = document.getElementById('applyFilter');
    const orderRows = document.querySelectorAll('.order-row');

    if (filterStatus && applyFilter && orderRows.length) {
      applyFilter.addEventListener('click', function() {
        const selectedStatus = filterStatus.value;

        orderRows.forEach(row => {
          if (!selectedStatus || row.dataset.status === selectedStatus) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    }
  });
</script>