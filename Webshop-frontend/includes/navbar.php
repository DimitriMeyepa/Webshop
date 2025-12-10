<header class="border-b bg-white shadow-sm relative">
  <div class="container mx-auto flex justify-between items-center py-4 px-6">
    <h1 class="brand-font playfair text-2xl font-bold tracking-wide">
      Webshop<span class="text-red-500">.</span>
    </h1>

    <nav class="hidden md:flex space-x-8 text-sm font-medium">
      <a href="index.php" class="hover:text-red-400 transition">Accueil</a>
      <a href="collection_men.php" class="hover:text-red-400 transition">Hommes</a>
      <a href="collection_women.php" class="hover:text-red-400 transition">Femmes</a>
      <a href="contact.html" class="hover:text-red-400 transition">Contact</a>
    </nav>

    <div class="flex items-center space-x-4 text-gray-600">
      <i class="fas fa-search cursor-pointer hover:text-black"></i>
      <a href="login.html"><i class="fas fa-user cursor-pointer hover:text-black"></i></a>
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
      <li><a href="collection_women.html" class="block py-3 px-6 hover:bg-gray-100">Femmes</a></li>
      <li><a href="contact.html" class="block py-3 px-6 hover:bg-gray-100">Contact</a></li>
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
    <p class="text-gray-500 text-center">Votre panier est vide</p>
  </div>

  <div class="border-t p-6">
    <div class="flex justify-between mb-4 font-semibold">
      <span>Total:</span>
      <span id="cart-total">0 MUR</span>
    </div>
    <a href="payment.html" class="block w-full bg-red-400 text-white py-3 px-4 rounded-lg hover:bg-red-500 transition font-semibold text-center shadow-md hover:shadow-lg">
      Procéder au paiement
    </a>
    <button id="continue-shopping-btn" class="w-full mt-2 border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition font-semibold">
      Continuer vos achats
    </button>
  </div>
</div>

<div id="cart-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-all duration-300"></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
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
  });
</script>
