</main>

<!-- Footer (full-width backgrounds with contained content) -->
<footer class="border-t border-gray-300">
  <!-- Top benefits section -->
  <div class="w-full py-8 border-b border-gray-200">
    <div class="grid grid-cols-1 gap-6 px-4 site-container md:grid-cols-2 lg:grid-cols-4">
      <div class="flex flex-col items-center text-center">
        <img src="/assets/images/footer1.png" alt="<?= __('general.free_shipping') ?>" />
        <p class="font-medium text-gray-800"><?= __('general.free_shipping') ?></p>
        <p class="text-sm text-gray-600"><?= __('general.from_amount', ['amount' => '300']) ?></p>
      </div>

      <div class="flex flex-col items-center text-center">
        <img src="/assets/images/footer2.png" alt="<?= __('general.payment_installments') ?>" />
        <p class="font-medium text-gray-800"><?= __('general.payment_installments') ?></p>
        <p class="text-sm text-gray-600"><?= __('general.no_fees') ?></p>
      </div>

      <div class="flex flex-col items-center text-center">
        <img src="/assets/images/footer3.png" alt="<?= __('general.free_returns') ?>" />
        <p class="font-medium text-gray-800"><?= __('general.free_returns') ?></p>
        <p class="text-sm text-gray-600"><?= __('general.within_days', ['days' => '30']) ?></p>
      </div>

      <div class="flex flex-col items-center text-center">
        <img src="/assets/images/footer4.png" alt="<?= __('general.need_help') ?>" />
        <p class="font-medium text-gray-800"><?= __('general.need_help') ?></p>
        <p class="text-sm text-gray-600"><?= __('general.contact_us') ?> <a href="#" class="text-primary hover:underline"><?= __('general.here') ?></a></p>
      </div>
    </div>
  </div>

  <!-- Main footer content -->
  <div class="bg-[#fdf7f1] w-full py-8">
    <div class="grid grid-cols-1 gap-8 px-4 site-container md:grid-cols-4">
      <!-- Singer brand info -->
      <div class="mb-6 md:mb-0">
        <img src="/assets/images/logo.png" alt="Singer Logo" class="h-6 mb-4" />
        <p class="mb-4 text-sm italic text-gray-600">
          <?= get_language() === 'fr'
            ? 'Singer, la marque de référence en matière de couture : 170 ans de savoir-faire et de notoriété'
            : 'Singer, the reference brand in sewing: 170 years of expertise and renown' ?>
        </p>

        <div class="flex mt-4 space-x-4">
          <a href="#" class="text-gray-600 hover:text-primary">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="text-gray-600 hover:text-primary">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="#" class="text-gray-600 hover:text-primary">
            <i class="fab fa-pinterest-p"></i>
          </a>
          <a href="#" class="text-gray-600 hover:text-primary">
            <i class="fab fa-instagram"></i>
          </a>
        </div>
      </div>

      <!-- General Links -->
      <div>
        <h3 class="mb-4 font-semibold text-gray-800"><?= __('general.general') ?></h3>
        <ul class="space-y-2">
          <li><a href="/page/politique-de-confidentialite" class="text-sm text-gray-600 hover:text-primary"><?= __('general.privacy_policy') ?></a></li>
          <li><a href="/page/conditions-generales-de-vente" class="text-sm text-gray-600 hover:text-primary"><?= __('general.terms_of_sale') ?></a></li>
          <li><a href="/page/mentions-legales" class="text-sm text-gray-600 hover:text-primary"><?= __('general.legal_notice') ?></a></li>
          <li><a href="/page/la-marque" class="text-sm text-gray-600 hover:text-primary"><?= __('general.the_brand') ?></a></li>
        </ul>
      </div>

      <!-- Machines Links -->
      <div>
        <h3 class="mb-4 font-semibold text-gray-800"><?= __('general.machines') ?></h3>
        <ul class="space-y-2">
          <li><a href="/category/brodeuses" class="text-sm text-gray-600 hover:text-primary">Brodeuses</a></li>
          <li><a href="/category/machines-electroniques" class="text-sm text-gray-600 hover:text-primary">Électroniques</a></li>
          <li><a href="/category/machines-mecaniques" class="text-sm text-gray-600 hover:text-primary">Mécaniques</a></li>
          <li><a href="/category/soin-du-linge" class="text-sm text-gray-600 hover:text-primary">Soin du linge</a></li>
          <li><a href="/category/surjeteuses-recouvreuses" class="text-sm text-gray-600 hover:text-primary">Surjeteuses & Recouvreuses</a></li>
        </ul>
      </div>

      <!-- Help & Newsletter -->
      <div>
        <h3 class="mb-4 font-semibold text-gray-800"><?= __('general.purchase_help') ?></h3>
        <ul class="mb-6 space-y-2">
          <li><a href="/page/questions-frequentes" class="text-sm text-gray-600 hover:text-primary"><?= __('general.faq') ?></a></li>
          <li><a href="/page/vos-avantages" class="text-sm text-gray-600 hover:text-primary"><?= __('general.your_advantages') ?></a></li>
          <li><a href="/page/tutos" class="text-sm text-gray-600 hover:text-primary"><?= __('general.tutorials') ?></a></li>
          <li><a href="/page/actualites" class="text-sm text-gray-600 hover:text-primary"><?= __('general.news') ?></a></li>
          <li><a href="/page/nous-contacter" class="text-sm text-gray-600 hover:text-primary"><?= __('general.contact_us_page') ?></a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Scroll to top button -->
  <div class="fixed z-30 bottom-6 right-6">
    <button id="scroll-to-top" class="flex items-center justify-center w-10 h-10 text-white shadow-lg bg-primary">
      <i class="fas fa-arrow-up"></i>
    </button>
  </div>

  <!-- Copyright footer -->
  <div class="py-2 text-sm font-medium text-center text-white bg-primary">
    <div class="site-container"><?= __('general.copyright', ['year' => date('Y')]) ?></div>
  </div>
</footer>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4GLVBPJ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<script>
  // Mobile menu toggle
  document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.remove('hidden');
  });

  document.getElementById('close-mobile-menu')?.addEventListener('click', function() {
    document.getElementById('mobile-menu').classList.add('hidden');
  });

  // Mobile submenu navigation
  const mainMenu = document.getElementById('mobile-main-menu');
  const backButtons = document.querySelectorAll('.back-to-main');
  const submenuTriggers = document.querySelectorAll('[data-submenu]');

  // Handle submenu open
  submenuTriggers.forEach(trigger => {
    trigger.addEventListener('click', function() {
      const submenuId = this.dataset.submenu;
      const targetSubmenu = document.getElementById(`submenu-${submenuId}`);

      if (targetSubmenu) {
        // Hide main menu
        mainMenu.classList.add('hidden');

        // Show submenu
        targetSubmenu.classList.remove('hidden');
      }
    });
  });

  // Handle back button click
  backButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Find parent submenu
      const submenu = this.closest('[id^="submenu-"]');

      if (submenu) {
        // Hide current submenu
        submenu.classList.add('hidden');

        // Show main menu
        mainMenu.classList.remove('hidden');
      }
    });
  });

  // Scroll to top button
  document.getElementById('scroll-to-top')?.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // Show/hide scroll to top button based on scroll position
  window.addEventListener('scroll', function() {
    const scrollButton = document.getElementById('scroll-to-top');
    if (scrollButton) {
      if (window.scrollY > 300) {
        scrollButton.classList.remove('hidden');
      } else {
        scrollButton.classList.add('hidden');
      }
    }
  });
</script>
<script type="text/javascript">
  window._mfq = window._mfq || [];
  (function() {
    var mf = document.createElement("script");
    mf.type = "text/javascript"; mf.defer = true;
    mf.src = "//cdn.mouseflow.com/projects/439b3f19-b164-437e-af31-65bf791dd888.js";
    document.getElementsByTagName("head")[0].appendChild(mf);
  })();
</script>
</body>

</html>