<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Webshop</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display&display=swap"
    rel="stylesheet" />

  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet" />

  <!-- Ton fichier CSS -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="bg-white text-gray-800">
    <?php include 'includes/navbar.php'; ?>   


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


  <section class="relative overflow-hidden bg-[#f8f6f5] w-full">
    <div id="carousel-slides" class="relative w-full h-[560px] overflow-hidden">
      <div
        class="absolute inset-0 flex flex-col md:flex-row items-center justify-center transition-all duration-1000 slide opacity-100 pointer-events-auto bg-center bg-cover"
        style="background-image: url('img/homepage/wintercollection.png')" data-aos="fade-in">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 text-center md:text-left max-w-lg text-white px-6" data-aos="fade-right"
          data-aos-delay="200">
          <p class="uppercase text-sm tracking-wider text-white-400 font-semibold mb-4">
            Nouvelle collection
          </p>
          <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
            Winter Collection 2026
          </h2>
          <p class="text-gray-200 mb-6">
            Découvrez nos tenues modernes et élégantes pour cette saison
            hivernale.
          </p>
          <a href="#"
            class="inline-block bg-red-400 text-white px-6 py-3 rounded hover:bg-red-500 transition font-medium"
            data-aos="zoom-in" data-aos-delay="400">Découvrir maintenant</a>
        </div>
      </div>

      <div
        class="absolute inset-0 flex flex-col md:flex-row items-center justify-center transition-all duration-1000 slide opacity-0 pointer-events-none bg-center bg-cover"
        style="background-image: url('img/homepage/summercollection.png')" data-aos="fade-in">
        <div class="absolute inset-0 bg-black/25"></div>
        <div class="relative z-10 text-center md:text-left max-w-lg text-white px-6" data-aos="fade-right"
          data-aos-delay="200">
          <p class="uppercase text-sm tracking-wider text-white-400 font-semibold mb-4">
            Nouvelle collection
          </p>
          <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
            Summer Collection 2026
          </h2>
          <p class="text-gray-200 mb-6">
            Découvrez nos tenues légères et élégantes pour cette saison
            estivale.
          </p>
          <a href="#"
            class="inline-block bg-red-400 text-white px-6 py-3 rounded hover:bg-red-500 transition font-medium"
            data-aos="zoom-in" data-aos-delay="400">Découvrir maintenant</a>
        </div>
      </div>
    </div>

    <button id="prev"
      class="absolute top-1/2 left-4 -translate-y-1/2 bg-red-400 text-white p-3 rounded-full shadow-lg hover:bg-red-500 transition z-20">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button id="next"
      class="absolute top-1/2 right-4 -translate-y-1/2 bg-red-400 text-white p-3 rounded-full shadow-lg hover:bg-red-500 transition z-20">
      <i class="fas fa-chevron-right"></i>
    </button>
  </section>

  <section class="container mx-auto px-6 mt-16 text-center" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-black inline-block border-b-4 border-red-400 pb-2">
      Nos Collections
    </h2>
  </section>

  <section class="container mx-auto px-6 py-16 grid md:grid-cols-2 gap-8">
    <div class="relative group overflow-hidden rounded-lg shadow-sm" data-aos="fade-up">
      <img src="img/homepage/collectionhomme.jpg" alt="Hommes"
        class="w-full h-[450px] object-cover transform group-hover:scale-105 transition duration-500" />
      <div class="absolute bottom-6 left-6" data-aos="fade-left" data-aos-delay="200">
        <h3 class="text-2xl font-bold mb-1 text-white drop-shadow-md">
          Collection Hommes 2026
        </h3>
        <a href="collection_men.html"
          class="inline-block bg-red-400 text-white font-semibold uppercase text-sm px-5 py-3 rounded shadow-md hover:bg-red-500 transition"
          data-aos="zoom-in" data-aos-delay="400">
          Achetez maintenant
        </a>
      </div>
    </div>

    <div class="relative group overflow-hidden rounded-lg shadow-sm" data-aos="fade-up" data-aos-delay="100">
      <img src="img/homepage/collectionfemme.jpg" alt="Femmes"
        class="w-full h-[450px] object-cover transform group-hover:scale-105 transition duration-500" />
      <div class="absolute bottom-6 left-6" data-aos="fade-left" data-aos-delay="300">
        <h3 class="text-2xl font-bold mb-1 text-white drop-shadow-md">
          Collection Femmes 2026
        </h3>
        <a href="collection_women.html"
          class="inline-block bg-red-400 text-white font-semibold uppercase text-sm px-5 py-3 rounded shadow-md hover:bg-red-500 transition"
          data-aos="zoom-in" data-aos-delay="500">
          Achetez maintenant
        </a>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-16">
    <div class="text-center mb-12" data-aos="fade-up">
      <h2 class="text-3xl font-bold text-black inline-block border-b-4 border-red-400 pb-2">
        Nouveautés
      </h2>
      <p class="text-gray-500 mt-3">
        Découvrez nos dernières pièces fraîchement ajoutées à la collection.
      </p>
    </div>

    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden" data-aos="zoom-in">
        <img src="img/homepage/chemise_en_lin.jpg" alt="Chemise en lin"
          class="w-full h-80 object-cover product-image" />
        <div class="p-4 text-center">
          <h3 class="font-semibold text-lg">Chemise en lin</h3>
          <p class="text-gray-500 mb-2">2,000 MUR</p>
          <button class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">
            Découvrir
          </button>
        </div>
      </div>

      <div class="bg-white shadow-lg rounded-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="100">
        <img src="img/homepage/chemise_pour_femme.jpg" alt="Chemise pour femme"
          class="w-full h-80 object-cover product-image" />
        <div class="p-4 text-center">
          <h3 class="font-semibold text-lg">Chemise pour femme</h3>
          <p class="text-gray-500 mb-2">1,800 MUR</p>
          <button class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">
            Découvrir
          </button>
        </div>
      </div>

      <div class="bg-white shadow-lg rounded-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
        <img src="img/homepage/pantalon_beige.jpg" alt="Pantalon beige"
          class="w-full h-80 object-cover product-image" />
        <div class="p-4 text-center">
          <h3 class="font-semibold text-lg">Pantalon beige</h3>
          <p class="text-gray-500 mb-2">2,000 MUR</p>
          <button class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">
            Découvrir
          </button>
        </div>
      </div>

      <div class="bg-white shadow-lg rounded-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
        <img src="img/homepage/vestelegere.jpg" alt="Veste légère" class="w-full h-80 object-cover product-image" />
        <div class="p-4 text-center">
          <h3 class="font-semibold text-lg">Veste légère</h3>
          <p class="text-gray-500 mb-2">3,400 MUR</p>
          <button class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">
            Découvrir
          </button>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-gray-800 text-gray-400 py-6 text-center">
    <p>
      <i class="far fa-copyright mr-1"></i> 2025 Webshop. Tous droits
      réservés.
    </p>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      easing: "ease-out",
      once: true,
    });
  </script>
  <script src="js/class.js"></script>
  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
</body>

</html>