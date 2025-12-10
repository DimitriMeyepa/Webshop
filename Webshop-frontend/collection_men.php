<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Collection Hommes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />
</head>

<body>
  <!-- Navbar -->
  <header class="border-b bg-white shadow-sm relative">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
      <h1 class="brand-font playfair text-2xl font-bold tracking-wide">
        Webshop<span class="text-red-500">.</span>
      </h1>
      <nav class="hidden md:flex space-x-8 text-sm font-medium">
        <a href="index.html" class="hover:text-red-400 transition">Accueil</a>
        <a href="collection_men.html" class="text-red-400 border-b-2 border-red-400 pb-1">Hommes</a>
        <a href="collection_women.html" class="hover:text-red-400 transition">Femmes</a>
        <a href="contact.html" class="hover:text-red-400 transition">Contact</a>
      </nav>
      <div class="flex items-center space-x-4 text-gray-600">
        <i class="fas fa-search cursor-pointer hover:text-black"></i>
        <a href="login.html"><i class="fas fa-user cursor-pointer hover:text-black"></i></a>
        <div class="relative">
          <i id="cart-icon" class="fas fa-shopping-bag cursor-pointer hover:text-black"></i>
          <span class="absolute -top-2 -right-2 bg-red-400 text-white text-xs rounded-full px-1.5">0</span>
        </div>
        <button id="hamburger" class="md:hidden focus:outline-none">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>
    <nav id="mobile-menu" class="md:hidden absolute top-full left-0 w-full bg-white shadow-md max-h-0 overflow-hidden transition-all duration-500 ease-in-out z-10">
      <ul class="flex flex-col text-sm font-medium">
        <li><a href="index.html" class="block py-3 px-6 hover:bg-gray-100">Accueil</a></li>
        <li><a href="collection_men.html" class="block py-3 px-6 hover:bg-gray-100">Hommes</a></li>
        <li><a href="collection_women.html" class="block py-3 px-6 hover:bg-gray-100">Femmes</a></li>
        <li><a href="contact.html" class="block py-3 px-6 hover:bg-gray-100">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Cart Sidebar -->
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

  <!-- Barre recherche et tri -->
  <div class="container mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="w-full md:w-1/2" data-aos="fade-down" data-aos-duration="700">
      <input type="text" id="searchInput" placeholder="Rechercher un produit..."
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400 transition" />
    </div>
    <div class="relative inline-block w-full md:w-1/4" data-aos="fade-down" data-aos-duration="700">
      <button id="sortButton" class="w-full bg-white text-gray-800 font-medium py-2 px-4 border border-gray-300 rounded-xl shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-400 flex justify-between items-center transition">
        <span id="sortLabel">Trier par</span>
        <i class="fas fa-chevron-down ml-2 text-gray-500"></i>
      </button>
      <div id="sortMenu" class="hidden absolute w-full mt-2 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-10">
        <button data-sort="name-asc" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition">Nom A–Z</button>
        <button data-sort="name-desc" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition">Nom Z–A</button>
        <button data-sort="price-asc" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition">Prix croissant</button>
        <button data-sort="price-desc" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition">Prix décroissant</button>
      </div>
    </div>
  </div>

  <!-- Section produits -->
  <section class="container mx-auto px-6 py-16">
    <div class="text-center mb-12" data-aos="fade-up">
      <h2 class="text-3xl font-semibold mb-4">Notre Collection</h2>
      <p class="text-gray-500">Découvrez nos modèles pour hommes.</p>
    </div>

    <div id="products-container" class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="200">
      <!-- Produits chargés dynamiquement -->
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-400 py-6 text-center">
    <p><i class="far fa-copyright mr-1"></i>2025 Webshop. Tous droits réservés.</p>
  </footer>

  <!-- JS -->
  <script src="js/class.js"></script>
  <script src="js/search.js"></script>
  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({ once: true, duration: 800, easing: "ease-out-cubic" });

    document.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('products-container');
      const searchInput = document.getElementById('searchInput');
      const button = document.getElementById("sortButton");
      const menu = document.getElementById("sortMenu");
      const sortLabel = document.getElementById("sortLabel");
      const options = menu.querySelectorAll("button");

      let productsData = [];

      button.addEventListener("click", () => menu.classList.toggle("hidden"));
      document.addEventListener("click", e => { if (!button.contains(e.target)) menu.classList.add("hidden"); });

      options.forEach(opt => {
        opt.addEventListener("click", () => {
          options.forEach(o => o.classList.remove("text-red-500", "bg-red-50"));
          opt.classList.add("text-red-500", "bg-red-50");
          sortLabel.textContent = opt.textContent;
          menu.classList.add("hidden");
          loadProducts(opt.dataset.sort);
        });
      });

      function loadProducts(sort = '') {
        fetch('../Webshop-backend/api_men.php')
          .then(res => res.json())
          .then(data => {
            if (!data.success) throw new Error("Impossible de charger les produits.");
            productsData = data.products;

            // Trier si nécessaire
            if (sort === 'name-asc') productsData.sort((a,b)=> a.nomprod.localeCompare(b.nomprod));
            if (sort === 'name-desc') productsData.sort((a,b)=> b.nomprod.localeCompare(a.nomprod));
            if (sort === 'price-asc') productsData.sort((a,b)=> parseFloat(a.prixprod)-parseFloat(b.prixprod));
            if (sort === 'price-desc') productsData.sort((a,b)=> parseFloat(b.prixprod)-parseFloat(a.prixprod));

            displayProducts(productsData);
          })
          .catch(err => container.innerHTML = '<p class="text-red-500">Impossible de charger les produits.</p>');
      }

      function displayProducts(products) {
        container.innerHTML = '';
        products.forEach(product => {
          const card = document.createElement('div');
          card.className = 'bg-white shadow-lg rounded-lg overflow-hidden';
          card.innerHTML = `
            <img src="${product.image_principale}" alt="${product.nomprod}" class="w-full h-80 object-cover object-top product-image" />
            <div class="p-4 text-center">
              <h3 class="font-semibold text-lg">${product.nomprod}</h3>
              <p class="text-gray-500 mb-2">${parseFloat(product.prixprod).toLocaleString()} MUR</p>
              <a href="display_men.html?ref=${product.referenceprod}" class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition inline-block">
                Découvrir
              </a>
            </div>
          `;
          container.appendChild(card);
        });
      }

      // Recherche en temps réel
      searchInput.addEventListener('input', e => {
        const query = e.target.value.toLowerCase();
        const filtered = productsData.filter(p => p.nomprod.toLowerCase().includes(query));
        displayProducts(filtered);
      });

      // Charger produits au démarrage
      loadProducts();
    });
  </script>
</body>
</html>
