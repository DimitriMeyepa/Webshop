<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>À Propos - Webshop</title>

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
  <?php 
  require_once '../config/config.php';
  include 'includes/navbar.php'; 
  ?>

  <!-- Hero Section -->
  <section class="relative w-full h-[400px] overflow-hidden">
    <div class="absolute inset-0 bg-center bg-cover" style="background-image: url('img/about/photoabout.png')">
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <div class="relative z-10 flex items-center justify-center h-full">
      <div class="text-center text-white" data-aos="fade-up">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">À Propos de Webshop</h1>
        <p class="text-lg text-gray-200">Découvrez notre histoire et notre passion</p>
      </div>
    </div>
  </section>

  <!-- À Propos Section -->
  <section class="container mx-auto px-6 py-16">
    <div class="grid md:grid-cols-2 gap-12 items-center">
      <div data-aos="fade-right">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Notre Histoire</h2>
        <p class="text-gray-600 mb-4 leading-relaxed">
          Webshop est née d'une passion pour la mode et le style de vie. Depuis ses débuts, notre mission a toujours été 
          de proposer des collections de qualité qui reflètent les tendances actuelles tout en restant accessibles.
        </p>
        <p class="text-gray-600 mb-4 leading-relaxed">
          Nous croyons que chaque client mérite de trouver exactement ce qu'il cherche, qu'il s'agisse de vêtements élégants, 
          de collections mode ou d'accessoires de décoration intérieure.
        </p>
        <p class="text-gray-600 leading-relaxed">
          Aujourd'hui, nous sommes fiers de servir une clientèle diverse et fidèle qui partage nos valeurs de qualité et d'excellence.
        </p>
      </div>
      <div data-aos="fade-left">
        <img src="img/about/team.png" alt="Notre équipe" class="rounded-lg shadow-lg w-full h-[400px] object-cover" />
      </div>
    </div>
  </section>

  <!-- Valeurs Section -->
  <section class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Nos Valeurs</h2>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-lg shadow-md text-center" data-aos="zoom-in">
          <div class="text-red-400 text-4xl mb-4">
            <i class="fas fa-heart"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Qualité</h3>
          <p class="text-gray-600">
            Nous sélectionnons minutieusement chaque produit pour garantir la meilleure qualité à nos clients.
          </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md text-center" data-aos="zoom-in" data-aos-delay="100">
          <div class="text-red-400 text-4xl mb-4">
            <i class="fas fa-users"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Relation Client</h3>
          <p class="text-gray-600">
            Votre satisfaction est notre priorité. Nous offrons un service client exceptionnnel et réactif.
          </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-md text-center" data-aos="zoom-in" data-aos-delay="200">
          <div class="text-red-400 text-4xl mb-4">
            <i class="fas fa-leaf"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Responsabilité</h3>
          <p class="text-gray-600">
            Nous nous engageons à opérer de manière responsable et respectueuse de l'environnement.
          </p>
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
  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
</body>

</html>
