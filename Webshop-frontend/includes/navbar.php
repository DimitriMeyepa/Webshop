<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="border-b bg-white shadow-sm relative">
  <div class="container mx-auto flex justify-between items-center py-4 px-6">
    <h1 class="brand-font playfair text-2xl font-bold tracking-wide">
      <a href="index.php">Webshop<span class="text-red-500">.</span></a>
    </h1>

    <nav class="hidden md:flex space-x-8 text-sm font-medium">
      <a href="index.php" class="hover:text-red-400 transition">Accueil</a>
      <a href="collection_men.php" class="hover:text-red-400 transition">Hommes</a>
      <a href="collection_women.php" class="hover:text-red-400 transition">Femmes</a>
      <a href="collection_homedecor.php" class="hover:text-red-400 transition">Décoration intérieure</a>
      <a href="contact.php" class="hover:text-red-400 transition">Contact</a>
    </nav>

    <div class="flex items-center space-x-4 text-gray-600">
      <div class="relative">
        <?php if (isset($_SESSION['idutilisateur'])): ?>
          <button id="user-menu-btn" class="flex items-center hover:text-black transition">
            <i class="fas fa-user cursor-pointer"></i>
            <span class="ml-2 text-sm text-gray-800"><?php echo htmlspecialchars($_SESSION['prenomutilisateur'] ?? ''); ?></span>
          </button>
          <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
            <a href="profil.php" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 transition font-medium">
              <i class="fas fa-user-circle mr-2"></i> Mon Profil
            </a>
            <a href="logout.php" class="block px-4 py-3 text-red-600 hover:bg-red-50 transition font-medium border-t border-gray-200">
              <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
            </a>
          </div>
        <?php else: ?>
          <button id="auth-menu-btn" class="flex items-center hover:text-black transition">
            <i class="fas fa-user cursor-pointer"></i>
          </button>
          <div id="auth-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
            <a href="login.php" class="block px-4 py-3 text-gray-800 hover:bg-gray-100 transition font-medium">
              <i class="fas fa-user mr-2"></i> Se connecter
            </a>
            <a href="register.php" class="block px-4 py-3 text-red-600 hover:bg-red-50 transition font-medium border-t border-gray-200">
              <i class="fas fa-user-plus mr-2"></i> S'inscrire
            </a>
          </div>
        <?php endif; ?>
      </div>
      <div class="relative">
        <i id="cart-icon" class="fas fa-shopping-bag cursor-pointer hover:text-black"></i>
        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-400 text-white text-xs rounded-full px-1.5">0</span>
      </div>

      <button id="hamburger" class="md:hidden focus:outline-none">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>
  </div>

  <nav id="mobile-menu"
    class="md:hidden absolute top-full left-0 w-full bg-white shadow-md max-h-0 overflow-hidden transition-all duration-500 ease-in-out z-10">
    <ul class="flex flex-col text-sm font-medium">
      <li><a href="index.php" class="block py-3 px-6 hover:bg-gray-100">Accueil</a></li>
      <li><a href="collection_men.php" class="block py-3 px-6 hover:bg-gray-100">Hommes</a></li>
      <li><a href="collection_women.php" class="block py-3 px-6 hover:bg-gray-100">Femmes</a></li>
      <li><a href="contact.php" class="block py-3 px-6 hover:bg-gray-100">Contact</a></li>
      <?php if (isset($_SESSION['idutilisateur'])): ?>
        <li><a href="profil.php" class="block py-3 px-6 hover:bg-gray-100 border-t border-gray-200"><i class="fas fa-user-circle mr-2"></i>Mon Profil</a></li>
        <li><a href="logout.php" class="block py-3 px-6 hover:bg-red-50 text-red-600"><i class="fas fa-sign-out-alt mr-2"></i>Se déconnecter</a></li>
      <?php else: ?>
        <li><a href="login.php" class="block py-3 px-6 hover:bg-gray-100 border-t border-gray-200">Se connecter</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<div id="cart-sidebar" class="fixed top-0 right-0 w-80 h-full bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
  <div class="flex justify-between items-center p-6 border-b">
    <h2 class="text-xl font-bold">Mon panier</h2>
    <button id="close-cart-btn" class="text-gray-500 hover:text-black">
      <i class="fas fa-times text-2xl"></i>
    </button>
  </div>

  <div id="cart-items" class="flex-1 overflow-y-auto p-6">
  </div>

  <div class="border-t p-6">
    <div class="flex justify-between mb-4 font-semibold">
      <span>Total:</span>
      <span id="cart-total"></span>
    </div>
    <a href="payment.php" class="block w-full bg-red-400 text-white py-3 px-4 rounded-lg hover:bg-red-500 transition font-semibold text-center shadow-md hover:shadow-lg">
      Procéder au paiement
    </a>
    <button id="continue-shopping-btn" class="w-full mt-2 border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition font-semibold">
      Continuer vos achats
    </button>
  </div>
</div>

<div id="cart-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-all duration-300"></div>

<script>
  const isLoggedIn = <?php echo isset($_SESSION['idutilisateur']) ? 'true' : 'false'; ?>;
  document.addEventListener('DOMContentLoaded', function () {
    const userMenuBtn = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    const authMenuBtn = document.getElementById('auth-menu-btn');
    const authDropdown = document.getElementById('auth-dropdown');

    if (userMenuBtn && userDropdown) {
      userMenuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('hidden');
        if (authDropdown) authDropdown.classList.add('hidden');
      });
    }

    if (authMenuBtn && authDropdown) {
      authMenuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        authDropdown.classList.toggle('hidden');
      });
    }

    document.addEventListener('click', function (e) {
      if (userDropdown && !userMenuBtn?.contains(e.target) && !userDropdown.contains(e.target)) {
        userDropdown.classList.add('hidden');
      }
      if (authDropdown && !authMenuBtn?.contains(e.target) && !authDropdown.contains(e.target)) {
        authDropdown.classList.add('hidden');
      }
    });

    try {
      const path = window.location.pathname;
      let current = path.split('/').pop() || 'index';
      current = current.split('?')[0].split('#')[0].replace(/\.(php|html)$/, '');

      const navLinks = Array.from(document.querySelectorAll('header nav a, #mobile-menu a'));
      navLinks.forEach(a => {
        try {
          const href = a.getAttribute('href') || '';
          const url = new URL(href, window.location.origin);
          let linkName = url.pathname.split('/').pop() || 'index';
          linkName = linkName.split('?')[0].split('#')[0].replace(/\.(php|html)$/, '');

          if (linkName === current) {
            a.classList.add('text-red-400', 'border-b-2', 'border-red-400', 'pb-1');
            a.setAttribute('aria-current', 'page');
          } else {
            a.classList.remove('text-red-400', 'border-b-2', 'border-red-400', 'pb-1');
            a.removeAttribute('aria-current');
          }
        } catch (e) {
        }
      });
    } catch (e) {
      console.error('nav-active error', e);
    }
    const cartIcon = document.getElementById('cart-icon');

    if (cartIcon) { 
      cartIcon.addEventListener('click', function () {

      if(!isLoggedIn) {
        window.location.href = 'login.php';
        return;
      }
        
      });
    }
  });
</script>
