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
    <?php 
    require_once '../config/config.php';
    
    $products = [];
    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM produit ORDER BY dateajoutprod DESC LIMIT 4");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as &$p) {
            $stmtImg = $pdo->prepare("SELECT image_path FROM produit_images WHERE produit_id = ? LIMIT 1");
            $stmtImg->execute([$p['referenceprod']]);
            $imgData = $stmtImg->fetch(PDO::FETCH_ASSOC);
            
            if ($imgData && !empty($imgData['image_path'])) {
                $p['image_principale'] = str_replace('C:/wamp64/www/Webshop/Webshop-frontend/', '', $imgData['image_path']);
                if (!file_exists($p['image_principale'])) {
                    $p['image_principale'] = 'img/homepage/chemise_en_lin.jpg';
                }
            } else {
                $p['image_principale'] = 'img/homepage/chemise_en_lin.jpg';
            }
        }
    } catch (PDOException $e) {
        error_log("Erreur PDO: " . $e->getMessage());
    }
    
    include 'includes/navbar.php'; 
    ?>   


  <section class="relative overflow-hidden bg-[#f8f6f5] w-full">
    <div id="carousel-slides" class="relative w-full h-[560px] overflow-hidden">
      <div
        class="absolute inset-0 flex flex-col md:flex-row items-center justify-center transition-all duration-1000 slide opacity-100 pointer-events-auto bg-center bg-cover"
        style="background-image: url('img/homepage/about.png')" data-aos="fade-in">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 text-center md:text-left max-w-lg text-white px-6" data-aos="fade-right"
          data-aos-delay="200">
          <p class="uppercase text-sm tracking-wider text-white-400 font-semibold mb-4">
            Qui sommes-nous?
          </p>
          <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
            À Propos de Webshop
          </h2>
          <p class="text-gray-200 mb-6">
            Nous proposons les meilleures collections de vêtements et d'accessoires pour tous les styles et toutes les occasions.
          </p>
          <a href="about.php"
            class="inline-block bg-red-400 text-white px-6 py-3 rounded hover:bg-red-500 transition font-medium"
            data-aos="zoom-in" data-aos-delay="400">Voir plus</a>
        </div>
      </div>

      <div
        class="absolute inset-0 flex flex-col md:flex-row items-center justify-center transition-all duration-1000 slide opacity-0 pointer-events-none bg-center bg-cover"
        style="background-image: url('img/homepage/homed.webp')" data-aos="fade-in">
        <div class="absolute inset-0 bg-black/25"></div>
        <div class="relative z-10 text-center md:text-left max-w-lg text-white px-6" data-aos="fade-right"
          data-aos-delay="200">
          <p class="uppercase text-sm tracking-wider text-white-400 font-semibold mb-4">
            Collection spéciale
          </p>
          <h2 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">
            Home Decor & Accessoires
          </h2>
          <p class="text-gray-200 mb-6">
            Explorez notre sélection exclusive de décoration intérieure et d'accessoires pour embellir votre espace.
          </p>
          <a href="collection_homedecor.php"
            class="inline-block bg-red-400 text-white px-6 py-3 rounded hover:bg-red-500 transition font-medium"
            data-aos="zoom-in" data-aos-delay="400">Voir la collection</a>
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
        <a href="collection_men.php"
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
        <a href="collection_women.php"
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
      <?php if (empty($products)): ?>
        <div class="col-span-full text-center py-12">
          <p class="text-gray-500 text-lg">Aucun produit disponible pour le moment.</p>
        </div>
      <?php else: ?>
        <?php foreach ($products as $product): ?>
          <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition" data-aos="zoom-in">
            <img src="<?php echo htmlspecialchars($product['image_principale']); ?>" 
                 alt="<?php echo htmlspecialchars($product['nomprod']); ?>"
                 class="w-full h-80 object-cover product-image" />
            <div class="p-4 text-center">
              <h3 class="font-semibold text-lg text-gray-800"><?php echo htmlspecialchars($product['nomprod']); ?></h3>
              <p class="text-gray-500 mb-4"><?php echo number_format($product['prixprod'], 0, ',', ' '); ?> MUR</p>
              <?php 
                $genre = $product['genreprod'] ?? 'homme';
                $page = ($genre === 'femme') ? 'display_women.php' : 'display_men.php';
              ?>
              <a href="<?php echo $page; ?>?ref=<?php echo $product['referenceprod']; ?>" 
                 class="inline-block bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition font-medium">
                Découvrir
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
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