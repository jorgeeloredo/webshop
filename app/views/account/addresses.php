<?php
// app/views/account/addresses.php

// Get user data and addresses if any
$user = $user ?? null;
$addresses = $addresses ?? [];
$errors = $errors ?? [];
$success = $_SESSION['success'] ?? null;

// Clear success message after displaying it
if (isset($_SESSION['success'])) {
  unset($_SESSION['success']);
}
?>

<div class="px-4 py-8 bg-gray-50">
  <div class="site-container">
    <div class="mb-6">
      <h1 class="text-2xl font-normal text-gray-800"><?= __('addresses.my_addresses') ?></h1>
      <p class="text-sm text-gray-600">
        <?= __('addresses.manage_addresses') ?>
      </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
      <!-- Sidebar navigation -->
      <div class="md:col-span-1">
        <div class="sticky p-4 bg-white border border-gray-200 rounded-lg shadow-sm top-20">
          <nav class="space-y-1">
            <a href="/account" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-tachometer-alt"></i>
              <?= __('dashboard.dashboard') ?>
            </a>
            <a href="/account/orders" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-shopping-bag"></i>
              <?= __('account.my_orders') ?>
            </a>
            <a href="/account/profile" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
              <i class="w-5 mr-2 fas fa-user"></i>
              <?= __('account.profile') ?>
            </a>
            <a href="/account/addresses" class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md singer-red">
              <i class="w-5 mr-2 fas fa-map-marker-alt"></i>
              <?= __('account.addresses') ?>
            </a>
            <div class="pt-4 mt-4 border-t border-gray-200">
              <a href="/logout" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-red-600">
                <i class="w-5 mr-2 fas fa-sign-out-alt"></i>
                <?= __('account.logout') ?>
              </a>
            </div>
          </nav>
        </div>
      </div>

      <!-- Main content -->
      <div class="md:col-span-3">
        <?php if ($success): ?>
          <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-200 rounded-lg">
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <!-- Address Cards -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2">
          <?php if (empty($addresses)): ?>
            <div class="p-6 text-center bg-white border border-gray-200 rounded-lg col-span-full">
              <i class="mb-2 text-3xl text-gray-300 fas fa-map-marker-alt"></i>
              <p class="text-gray-600"><?= __('addresses.no_addresses') ?></p>
            </div>
          <?php else: ?>
            <?php foreach ($addresses as $index => $address): ?>
              <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="flex justify-between mb-3">
                  <h3 class="font-medium text-gray-800 text-md">
                    <?= htmlspecialchars($address['type']) === 'shipping' ? __('addresses.shipping_address') : __('addresses.billing_address') ?>
                    <?= isset($address['is_default']) && $address['is_default'] ? ' (' . __('addresses.default') . ')' : '' ?>
                  </h3>
                  <div class="flex space-x-2">
                    <button type="button" class="text-gray-500 hover:text-red-600"
                      onclick="openEditModal(<?= $index ?>)">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="text-gray-500 hover:text-red-600"
                      onclick="confirmDeleteAddress(<?= $index ?>)">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
                <div class="text-sm text-gray-600">
                  <p class="font-medium"><?= htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) ?></p>
                  <p><?= htmlspecialchars($address['address']) ?></p>
                  <?php if (!empty($address['address2'])): ?>
                    <p><?= htmlspecialchars($address['address2']) ?></p>
                  <?php endif; ?>
                  <p><?= htmlspecialchars($address['postal_code'] . ' ' . $address['city']) ?></p>
                  <p><?= htmlspecialchars($address['country']) ?></p>
                  <?php if (!empty($address['phone'])): ?>
                    <p>Tél: <?= htmlspecialchars($address['phone']) ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <!-- Add New Address Card -->
          <div class="p-5 text-center border border-gray-300 border-dashed rounded-lg bg-gray-50">
            <button type="button" onclick="openAddModal()" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-red-600">
              <i class="mb-2 text-2xl fas fa-plus-circle"></i>
              <span class="text-sm font-medium"><?= __('addresses.add_new') ?></span>
            </button>
          </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
          <h2 class="mb-4 text-lg font-medium text-gray-800"><?= __('addresses.shipping_settings') ?></h2>

          <div class="mb-4">
            <label class="flex items-center">
              <input type="checkbox" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" checked>
              <span class="ml-2 text-sm text-gray-700"><?= __('addresses.use_same_address') ?></span>
            </label>
          </div>

          <p class="text-sm text-gray-600">
            <?= __('addresses.preference_notice') ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Address Modal (hidden by default) -->
<div id="addAddressModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="relative z-10 w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl">
    <button type="button" class="absolute text-gray-400 top-4 right-4 hover:text-gray-500" onclick="closeAddModal()">
      <i class="fas fa-times"></i>
    </button>
    <h3 class="mb-4 text-lg font-medium text-gray-900"><?= __('addresses.add_title') ?></h3>
    <form id="addAddressForm" action="/account/addresses/add" method="POST">
      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- Address Type -->
        <div class="sm:col-span-2">
          <label class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.type') ?></label>
          <div class="flex space-x-4">
            <label class="flex items-center">
              <input type="radio" name="address_type" value="shipping" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500" checked>
              <span class="ml-2 text-sm text-gray-700"><?= __('addresses.shipping') ?></span>
            </label>
            <label class="flex items-center">
              <input type="radio" name="address_type" value="billing" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
              <span class="ml-2 text-sm text-gray-700"><?= __('addresses.billing') ?></span>
            </label>
          </div>
        </div>

        <!-- First Name -->
        <div>
          <label for="add_first_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.first_name') ?> *</label>
          <input type="text" id="add_first_name" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Last Name -->
        <div>
          <label for="add_last_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.last_name') ?> *</label>
          <input type="text" id="add_last_name" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Address Line 1 -->
        <div class="sm:col-span-2">
          <label for="add_address" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.address') ?> *</label>
          <input type="text" id="add_address" name="address" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Address Line 2 -->
        <div class="sm:col-span-2">
          <label for="add_address2" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.address2') ?></label>
          <input type="text" id="add_address2" name="address2" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200">
        </div>

        <!-- Postal Code -->
        <div>
          <label for="add_postal_code" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.postal_code') ?> *</label>
          <input type="text" id="add_postal_code" name="postal_code" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- City -->
        <div>
          <label for="add_city" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.city') ?> *</label>
          <input type="text" id="add_city" name="city" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Country -->
        <div>
          <label for="add_country" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.country') ?> *</label>
          <select id="add_country" name="country" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
            <option value="France">France</option>
            <option value="Belgique">Belgique</option>
            <option value="Suisse">Suisse</option>
            <option value="Luxembourg">Luxembourg</option>
          </select>
        </div>

        <!-- Phone -->
        <div>
          <label for="add_phone" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.phone') ?> *</label>
          <input type="tel" id="add_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Set as Default -->
        <div class="sm:col-span-2">
          <label class="flex items-center">
            <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
            <span class="ml-2 text-sm text-gray-700"><?= __('addresses.set_default') ?></span>
          </label>
        </div>
      </div>

      <div class="flex justify-end mt-6 space-x-3">
        <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200" onclick="closeAddModal()">
          <?= __('addresses.cancel') ?>
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md singer-red hover:bg-red-700">
          <?= __('addresses.add') ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Address Modal (hidden by default) -->
<div id="editAddressModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="relative z-10 w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl">
    <button type="button" class="absolute text-gray-400 top-4 right-4 hover:text-gray-500" onclick="closeEditModal()">
      <i class="fas fa-times"></i>
    </button>
    <h3 class="mb-4 text-lg font-medium text-gray-900"><?= __('addresses.edit_title') ?></h3>
    <form id="editAddressForm" action="/account/addresses/update" method="POST">
      <input type="hidden" id="edit_address_id" name="address_id" value="">

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- Address Type -->
        <div class="sm:col-span-2">
          <label class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.type') ?></label>
          <div class="flex space-x-4">
            <label class="flex items-center">
              <input type="radio" id="edit_type_shipping" name="address_type" value="shipping" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
              <span class="ml-2 text-sm text-gray-700"><?= __('addresses.shipping') ?></span>
            </label>
            <label class="flex items-center">
              <input type="radio" id="edit_type_billing" name="address_type" value="billing" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
              <span class="ml-2 text-sm text-gray-700"><?= __('addresses.billing') ?></span>
            </label>
          </div>
        </div>

        <!-- First Name -->
        <div>
          <label for="edit_first_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.first_name') ?> *</label>
          <input type="text" id="edit_first_name" name="first_name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Last Name -->
        <div>
          <label for="edit_last_name" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.last_name') ?> *</label>
          <input type="text" id="edit_last_name" name="last_name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Address Line 1 -->
        <div class="sm:col-span-2">
          <label for="edit_address" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.address') ?> *</label>
          <input type="text" id="edit_address" name="address" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Address Line 2 -->
        <div class="sm:col-span-2">
          <label for="edit_address2" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.address2') ?></label>
          <input type="text" id="edit_address2" name="address2" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200">
        </div>

        <!-- Postal Code -->
        <div>
          <label for="edit_postal_code" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.postal_code') ?> *</label>
          <input type="text" id="edit_postal_code" name="postal_code" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- City -->
        <div>
          <label for="edit_city" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.city') ?> *</label>
          <input type="text" id="edit_city" name="city" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Country -->
        <div>
          <label for="edit_country" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.country') ?> *</label>
          <select id="edit_country" name="country" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
            <option value="France">France</option>
            <option value="Belgique">Belgique</option>
            <option value="Suisse">Suisse</option>
            <option value="Luxembourg">Luxembourg</option>
          </select>
        </div>

        <!-- Phone -->
        <div>
          <label for="edit_phone" class="block mb-1 text-sm font-medium text-gray-700"><?= __('addresses.phone') ?> *</label>
          <input type="tel" id="edit_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-200" required>
        </div>

        <!-- Set as Default -->
        <div class="sm:col-span-2">
          <label class="flex items-center">
            <input type="checkbox" id="edit_is_default" name="is_default" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
            <span class="ml-2 text-sm text-gray-700"><?= __('addresses.set_default') ?></span>
          </label>
        </div>
      </div>

      <div class="flex justify-end mt-6 space-x-3">
        <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200" onclick="closeEditModal()">
          <?= __('addresses.cancel') ?>
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md singer-red hover:bg-red-700">
          <?= __('addresses.update') ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Delete Confirmation Modal (hidden by default) -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="relative z-10 w-full max-w-md p-6 mx-4 bg-white rounded-lg shadow-xl">
    <h3 class="mb-4 text-lg font-medium text-gray-900"><?= __('addresses.delete_title') ?></h3>
    <p class="mb-5 text-sm text-gray-500">
      <?= __('addresses.delete_text') ?>
    </p>
    <form id="deleteAddressForm" action="/account/addresses/delete" method="POST">
      <input type="hidden" id="delete_address_id" name="address_id" value="">
      <div class="flex justify-end space-x-3">
        <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200" onclick="closeDeleteModal()">
          <?= __('addresses.cancel') ?>
        </button>
        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
          <?= __('addresses.delete') ?>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Sample addresses data for demo purposes
  const addressesData = <?= !empty($addresses) ? json_encode($addresses) : '[]' ?>;

  // Modal functions
  function openAddModal() {
    document.getElementById('addAddressModal').classList.remove('hidden');
  }

  function closeAddModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
  }

  function openEditModal(index) {
    const modal = document.getElementById('editAddressModal');

    // Fill form with address data
    if (addressesData[index]) {
      const address = addressesData[index];
      document.getElementById('edit_address_id').value = index;
      document.getElementById('edit_first_name').value = address.first_name || '';
      document.getElementById('edit_last_name').value = address.last_name || '';
      document.getElementById('edit_address').value = address.address || '';
      document.getElementById('edit_address2').value = address.address2 || '';
      document.getElementById('edit_postal_code').value = address.postal_code || '';
      document.getElementById('edit_city').value = address.city || '';
      document.getElementById('edit_country').value = address.country || 'France';
      document.getElementById('edit_phone').value = address.phone || '';

      // Set address type radio
      if (address.type === 'shipping') {
        document.getElementById('edit_type_shipping').checked = true;
      } else {
        document.getElementById('edit_type_billing').checked = true;
      }

      // Set default checkbox
      document.getElementById('edit_is_default').checked = address.is_default || false;
    }

    modal.classList.remove('hidden');
  }

  function closeEditModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
  }

  function confirmDeleteAddress(index) {
    document.getElementById('delete_address_id').value = index;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
  }

  function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
  }

  // Form submission handlers - For demo purposes
  document.getElementById('addAddressForm').addEventListener('submit', function(event) {
    // Uncomment this line for demo purposes to prevent actual submission
    // event.preventDefault();

    // Here you would normally let the form submit to the server
    // For a demo without backend, you could show a success message and close the modal
    // alert('Address added successfully!');
    // closeAddModal();
  });

  document.getElementById('editAddressForm').addEventListener('submit', function(event) {
    // Uncomment this line for demo purposes to prevent actual submission
    // event.preventDefault();

    // Here you would normally let the form submit to the server
    // For a demo without backend, you could show a success message and close the modal
    // alert('Address updated successfully!');
    // closeEditModal();
  });

  document.getElementById('deleteAddressForm').addEventListener('submit', function(event) {
    // Uncomment this line for demo purposes to prevent actual submission
    // event.preventDefault();

    // Here you would normally let the form submit to the server
    // For a demo without backend, you could show a success message and close the modal
    // alert('Address deleted successfully!');
    // closeDeleteModal();
  });
</script>