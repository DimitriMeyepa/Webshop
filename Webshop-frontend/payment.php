<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Paiement - Webshop</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="bg-gray-50 text-gray-800">
   <?php 
    require_once '../config/config.php';
    ?>

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
      <a href="payment.php" class="block w-full bg-red-400 text-white py-3 px-4 rounded-lg hover:bg-red-500 transition font-semibold text-center shadow-md hover:shadow-lg">
        Procéder au paiement
      </a>
      <button id="continue-shopping-btn" class="w-full mt-2 border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition font-semibold">
        Continuer vos achats
      </button>
    </div>
  </div>

  <div id="cart-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden transition-all duration-300"></div>

  <main class="container mx-auto px-6 py-12">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Paiement</h1>
      <p class="text-gray-600 mt-2">Finalisez votre commande</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
      <div class="md:col-span-2">
        <form class="space-y-8">
          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold mb-6 flex items-center">
              <i class="fas fa-map-marker-alt text-red-400 mr-3"></i>
              Adresse de livraison
            </h2>

            <div class="grid md:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Prénom</label> <!-- Champ prérempli quand on aura fini le back-->
                <input type="text" placeholder="Jean"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
              </div>
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input type="text" placeholder="Dupont"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 font-semibold mb-2">Email</label>
              <input type="email" placeholder="jean@email.com"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 font-semibold mb-2">Téléphone</label>
              <input type="tel" placeholder="+230 57XX XXXX"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 font-semibold mb-2">Adresse</label>
              <input type="text" placeholder="123 Rue de la Paix"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
            </div>

            <div class="grid md:grid-cols-3 gap-4">
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Ville</label>
                <input type="text" placeholder="Port-Louis"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
              </div>
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Code postal</label>
                <input type="text" placeholder="10000"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" />
              </div>
              <div>
                <label class="block text-gray-700 font-semibold mb-2">Île</label>
                <select
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition">
                  <option>Mauritius</option>
                </select>
              </div>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-xl font-bold mb-6 flex items-center">
              <i class="fas fa-credit-card text-red-400 mr-3"></i>
              Mode de paiement
            </h2>

            <div class="space-y-4">
              <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-400 transition">
                <input type="radio" name="payment-method" value="card" class="w-4 h-4 text-red-400" />
                <span class="ml-3 flex-1">
                  <span class="font-semibold">Carte bancaire</span>
                  <span class="text-sm text-gray-600 block">VISA et MasterCard</span>
                </span>
                <div class="flex gap-2">
                  <img src="img/logo/Visa-Logo.png" alt="VISA" class="h-6" />
                  <img src="img/logo/mastercard.png" alt="MasterCard" class="h-6" />
                </div>
              </label>
            </div>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <label class="flex items-center">
              <input type="checkbox" class="w-5 h-5 text-red-400 rounded" />
              <span class="ml-3 text-gray-700">
                J'accepte les <a href="#" class="text-red-400 font-semibold hover:underline">conditions générales</a> et la <a href="#" class="text-red-400 font-semibold hover:underline">politique de confidentialité</a>
              </span>
            </label>
          </div>
        </form>
      </div>

      <div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 sticky top-6">
          <h2 class="text-xl font-bold mb-6">Résumé de la commande</h2>

          <div id="cart-items-summary" class="space-y-4 mb-6 pb-6 border-b border-gray-200">
            <p class="text-gray-500 text-center">Chargement...</p>
          </div>

          <div class="space-y-3 mb-8 pb-8 border-b border-gray-200">
            <div class="flex justify-between text-gray-600">
              <span>Sous-total</span>
              <span id="subtotal">0 MUR</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>Livraison</span>
              <span>500 MUR</span>
            </div>

          <div class="mb-8">
            <div class="flex justify-between text-2xl font-bold">
              <span>Total</span>
              <span id="total-price" class="text-red-400">0 MUR</span>
            </div>
          </div>

          <div class="space-y-3">
            <button
              class="w-full bg-red-400 text-white py-3 px-4 rounded-lg hover:bg-red-500 transition font-semibold text-lg shadow-md hover:shadow-lg">
              Confirmer et payer
            </button>

            <a href="index.php"
              class="block w-full text-center border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50 transition font-semibold">
              Continuer les achats
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 py-6 text-center mt-12">
    <p>
      <i class="far fa-copyright mr-1"></i> 2025 Webshop. Tous droits réservés.
    </p>
  </footer>

  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
  <script src="js/payment.js"></script>
</body>

</html>
