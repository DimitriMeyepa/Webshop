<?php
header('Content-Type: text/html; charset=UTF-8');
require_once '../config/config.php';

$ref = isset($_GET['ref']) ? (int)$_GET['ref'] : 0;
$isEditMode = isset($_GET['edit']) && $_GET['edit'] === 'true';
$editColor = isset($_GET['color']) ? $_GET['color'] : '';
$editSize = isset($_GET['size']) ? $_GET['size'] : '';
$product = null;
$colorImages = []; 

if ($ref > 0) {
    try {
        $pdo = getPDO();
        
        $stmt = $pdo->prepare("SELECT * FROM produit WHERE referenceprod = ?");
        $stmt->execute([$ref]);
        $product = $stmt->fetch();
        
        if ($product) {
            $stmt2 = $pdo->prepare("SELECT image_path, couleur FROM produit_images WHERE produit_id = ?");
            $stmt2->execute([$ref]);
            $imagesData = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($imagesData as $img) {
                $couleur = trim($img['couleur']);
                $imagePath = str_replace('C:/wamp64/www/Webshop/Webshop-frontend/', '', $img['image_path']);
                
                if ($couleur && $imagePath) {
                    $colorImages[$couleur] = $imagePath;
                }
            }
            
            if (empty($colorImages) && !empty($imagesData)) {
                $colorImages['default'] = str_replace('C:/wamp64/www/Webshop/Webshop-frontend/', '', $imagesData[0]['image_path']);
            }
        }
    } catch (PDOException $e) {
        error_log("Erreur PDO: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product ? htmlspecialchars($product['nomprod']) : 'Produit'; ?> - Femme Wardrobe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-50">

  <!-- Navbar -->
  <?php include 'includes/navbar.php'; ?>

  <!-- Retour -->
  <div class="container mx-auto px-6 mt-4">
    <a href="collection_men.php"
      class="inline-flex items-center text-gray-700 border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium hover:bg-gray-100 transition">
      <i class="fas fa-arrow-left mr-2"></i> Retour à la collection
    </a>
  </div>

  <main class="container mx-auto px-4 py-8">
    <?php if (!$product): ?>
      <div class="text-center py-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Produit introuvable</h2>
        <p class="text-gray-600 mb-6">Le produit que vous recherchez n'existe pas ou a été supprimé.</p>
        <a href="collection_women.php" class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition">
          Retourner à la collection
        </a>
      </div>
    <?php else: ?>
      <?php
      $couleurs = [];
      if (!empty($product['couleurprod'])) {
          $couleurs = array_map('trim', explode(',', $product['couleurprod']));
      }
            $defaultImage = !empty($colorImages) ? reset($colorImages) : 'img/costume/costume_noir.jpg';
      ?>
      
      <div class="flex flex-col md:flex-row gap-8 items-stretch">
        <div class="md:w-1/3">
          <div class="bg-white p-8 rounded-lg shadow-sm h-full flex items-center justify-center">
            <div class="bg-gray-100 w-full h-full rounded overflow-hidden">
              <img id="product-image" 
                   src="<?php echo htmlspecialchars($defaultImage); ?>" 
                   alt="<?php echo htmlspecialchars($product['nomprod']); ?>" 
                   class="w-full h-[inherit] object-cover">
            </div>
          </div>
        </div>

        <div class="md:w-2/3">
          <div class="bg-white p-6 rounded-lg shadow-sm h-full">
            <div class="mb-4">
              <span class="text-sm text-gray-500"><?php echo htmlspecialchars($product['categorieprod'] ?? ''); ?></span>
              <h1 class="text-2xl font-bold text-gray-800 mt-1"><?php echo htmlspecialchars($product['nomprod']); ?></h1>
            </div>

            <div class="mb-4">
              <p class="text-lg font-semibold text-gray-800">
                <span id="product-price"><?php echo number_format($product['prixprod'], 0, ',', ' '); ?> MUR</span>
                <span class="text-green-600 text-sm font-normal ml-2">
                  <i class="fas fa-shipping-fast mr-1"></i> Livraison gratuite
                </span>
              </p>
            </div>

            <div class="mb-6">
              <p class="text-gray-600"><?php echo htmlspecialchars($product['descriptionprod']); ?></p>
            </div>

            <div class="mb-4">
              <div class="flex justify-between items-center mb-2">
                <p class="text-sm text-gray-700">Sélectionnez votre taille :</p>
                <button id="openSizeGuide" class="text-xs text-red-500 hover:text-red-600 underline font-medium">
                  Guide des tailles
                </button>
              </div>
              <div id="sizes" class="flex items-center space-x-4 mb-2">
                <?php 
                if (isset($product['tailleprod'])) {
                  $tailles = array_map('trim', explode(',', $product['tailleprod']));
                  foreach ($tailles as $taille):
                    if (!empty($taille)):
                ?>
                  <span class="size-option text-gray-700 border border-gray-300 px-3 py-1 rounded hover:bg-gray-100 cursor-pointer">
                    <?php echo htmlspecialchars($taille); ?>
                  </span>
                <?php 
                    endif;
                  endforeach;
                }
                ?>
              </div>
            </div>

            <?php if (!empty($couleurs)): ?>
            <div class="mb-6">
              <p class="text-sm text-gray-700 mb-2">Couleurs disponibles :</p>
              <div id="colors" class="flex items-center space-x-3">
                <?php 
                $firstColor = true;
                foreach ($couleurs as $index => $couleur): 
                  $imageForColor = '';
                  
                  if (isset($colorImages[$couleur])) {
                      $imageForColor = $colorImages[$couleur];
                  } 
                  else {
                      foreach ($colorImages as $imgCouleur => $imgPath) {
                          if (strtolower(trim($imgCouleur)) == strtolower(trim($couleur))) {
                              $imageForColor = $imgPath;
                              break;
                          }
                      }
                  }
                  
                  if (empty($imageForColor) && !empty($colorImages)) {
                      $imageForColor = reset($colorImages);
                  }
                ?>
                  <div class="color-dot w-6 h-6 rounded-full border border-gray-300 cursor-pointer <?php echo $firstColor ? 'border-2 border-black' : ''; ?>"
                       title="<?php echo htmlspecialchars($couleur); ?>"
                       style="background-color: <?php echo mapColorNameToHex($couleur); ?>"
                       data-image="<?php echo htmlspecialchars($imageForColor); ?>"
                       data-color="<?php echo htmlspecialchars($couleur); ?>">
                  </div>
                <?php 
                  $firstColor = false;
                endforeach; 
                ?>
              </div>
            </div>
            <?php endif; ?>

            <div class="mb-5">
              <div class="flex items-center space-x-2">
                <div class="flex items-center border border-gray-300 rounded">
                  <button id="decrease" class="px-2 py-1 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-minus"></i>
                  </button>
                  <span id="quantity" class="px-3 py-1">1</span>
                  <button id="increase" class="px-2 py-1 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
                <button id="add-to-cart" 
                        class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition flex items-center"
                        data-id="<?php echo $product['referenceprod']; ?>"
                        data-name="<?php echo htmlspecialchars($product['nomprod']); ?>"
                        data-price="<?php echo $product['prixprod']; ?>"
                        data-image="<?php echo htmlspecialchars($defaultImage); ?>"
                        data-color=""
                        data-edit-mode="<?php echo $isEditMode ? 'true' : 'false'; ?>"
                        data-edit-color="<?php echo htmlspecialchars($editColor); ?>"
                        data-edit-size="<?php echo htmlspecialchars($editSize); ?>">
                  <i class="fas fa-shopping-cart mr-2"></i> <?php echo $isEditMode ? 'Modifier' : 'Ajouter au panier'; ?>
                </button>
              </div>
            </div>

            <div class="mb-6 text-sm text-gray-500">
              <p>SKU: <?php echo htmlspecialchars($product['referenceprod']); ?></p>
              <p>Catégorie : <?php echo htmlspecialchars($product['categorieprod'] ?? ''); ?></p>
            </div>

            <div class="border-t border-gray-200 pt-6">
              <p class="text-sm text-gray-500 mb-2 flex items-center">
                <i class="fas fa-shield-alt mr-2 text-green-500"></i> Paiement sécurisé garanti
              </p>
              <div class="flex items-center space-x-4">
                <div class="payment-icon">
                  <img src="img/logo/Visa-Logo.png" alt="VISA" class="h-8">
                </div>
                <div class="payment-icon">
                  <img src="img/logo/mastercard.png" alt="MasterCard" class="h-8">
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="mt-8 bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200">
          <nav class="flex -mb-px">
            <button class="py-3 px-6 border-b-2 border-black text-black font-medium flex items-center">
              <i class="fas fa-info-circle mr-2"></i> Description
            </button>
            <button class="py-3 px-6 text-gray-500 hover:text-gray-700 font-medium flex items-center">
              <i class="fas fa-list-alt mr-2"></i> Informations complémentaires
            </button>
            <button class="py-3 px-6 text-gray-500 hover:text-gray-700 font-medium flex items-center">
              <i class="fas fa-star mr-2"></i> Avis (<?php echo $product['nb_avis'] ?? 0; ?>)
            </button>
          </nav>
        </div>
        <div class="p-6">
          <p class="text-gray-600"><?php echo htmlspecialchars($product['descriptionsupprod'] ?? $product['descriptionprod']); ?></p>
          <?php if (isset($product['caracteristiques']) && !empty($product['caracteristiques'])): ?>
            <ul class="mt-4 text-gray-600 list-disc pl-5 space-y-2">
              <?php 
              $caracteristiques = explode(',', $product['caracteristiques']);
              foreach ($caracteristiques as $caract):
                if (trim($caract)):
              ?>
                <li><i class="fas fa-check text-green-500 mr-2"></i><?php echo htmlspecialchars(trim($caract)); ?></li>
              <?php 
                endif;
              endforeach;
              ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <div id="sizeGuideModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-11/12 max-h-96 overflow-y-auto">
      <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Guide des Tailles</h2>
        <button id="closeSizeGuide" class="text-gray-600 hover:text-gray-800">
          <i class="fas fa-times text-2xl"></i>
        </button>
      </div>
      <div class="p-6">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Taille</th>
              <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Poitrine (cm)</th>
              <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Taille (cm)</th>
              <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Longueur (cm)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="border border-gray-300 px-4 py-2 font-semibold">XS</td>
              <td class="border border-gray-300 px-4 py-2">80-86</td>
              <td class="border border-gray-300 px-4 py-2">64-70</td>
              <td class="border border-gray-300 px-4 py-2">66-70</td>
            </tr>
            <tr class="bg-gray-50">
              <td class="border border-gray-300 px-4 py-2 font-semibold">S</td>
              <td class="border border-gray-300 px-4 py-2">86-92</td>
              <td class="border border-gray-300 px-4 py-2">70-76</td>
              <td class="border border-gray-300 px-4 py-2">70-74</td>
            </tr>
            <tr>
              <td class="border border-gray-300 px-4 py-2 font-semibold">M</td>
              <td class="border border-gray-300 px-4 py-2">92-98</td>
              <td class="border border-gray-300 px-4 py-2">76-82</td>
              <td class="border border-gray-300 px-4 py-2">74-78</td>
            </tr>
            <tr class="bg-gray-50">
              <td class="border border-gray-300 px-4 py-2 font-semibold">L</td>
              <td class="border border-gray-300 px-4 py-2">98-104</td>
              <td class="border border-gray-300 px-4 py-2">82-88</td>
              <td class="border border-gray-300 px-4 py-2">78-82</td>
            </tr>
            <tr>
              <td class="border border-gray-300 px-4 py-2 font-semibold">XL</td>
              <td class="border border-gray-300 px-4 py-2">104-110</td>
              <td class="border border-gray-300 px-4 py-2">88-94</td>
              <td class="border border-gray-300 px-4 py-2">82-86</td>
            </tr>
            <tr class="bg-gray-50">
              <td class="border border-gray-300 px-4 py-2 font-semibold">XXL</td>
              <td class="border border-gray-300 px-4 py-2">110-116</td>
              <td class="border border-gray-300 px-4 py-2">94-100</td>
              <td class="border border-gray-300 px-4 py-2">86-90</td>
            </tr>
          </tbody>
        </table>
        <p class="text-sm text-gray-600 mt-6">
          <i class="fas fa-info-circle mr-2"></i> Pour trouver votre taille, mesurez votre tour de poitrine et votre tour de taille avec un mètre ruban. Consultez le tableau ci-dessus pour déterminer votre taille idéale.
        </p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-400 py-6 text-center">
    <p><i class="far fa-copyright mr-1"></i> 2025 Webshop. Tous droits réservés.</p>
  </footer>

  <!-- Scripts -->
  <script src="js/taille.js"></script>
  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const quantityEl = document.getElementById('quantity');
      const decreaseBtn = document.getElementById('decrease');
      const increaseBtn = document.getElementById('increase');
      const addToCartBtn = document.getElementById('add-to-cart');
      const priceEl = document.getElementById('product-price');
      const productImage = document.getElementById('product-image');
      
      let quantity = 1;
      const unitPrice = <?php echo $product ? $product['prixprod'] : 0; ?>;
      let selectedColor = '';

      function updateQuantityDisplay() {
        quantityEl.textContent = quantity;
      }

      function updatePriceDisplay() {
        const totalPrice = unitPrice * quantity;
        priceEl.textContent = totalPrice.toLocaleString() + ' MUR';
      }

      increaseBtn.addEventListener('click', () => {
        quantity++;
        updateQuantityDisplay();
        updatePriceDisplay();
      });

      decreaseBtn.addEventListener('click', () => {
        if (quantity > 1) {
          quantity--;
          updateQuantityDisplay();
          updatePriceDisplay();
        }
      });

      const sizeOptions = document.querySelectorAll('.size-option');
      let selectedSize = null;
      
      const urlParams = new URLSearchParams(window.location.search);
      const preSelectedColor = urlParams.get('color');
      const preSelectedSize = urlParams.get('size');

      sizeOptions.forEach(option => {
        option.addEventListener('click', () => {
          if (selectedSize === option) {
            option.classList.remove('bg-black', 'text-white');
            selectedSize = null;
          } else {
            sizeOptions.forEach(s => s.classList.remove('bg-black', 'text-white'));
            option.classList.add('bg-black', 'text-white');
            selectedSize = option;
          }
        });
        
        if (preSelectedSize && option.textContent.trim() === preSelectedSize) {
          option.classList.add('bg-black', 'text-white');
          selectedSize = option;
        }
      });

      const colorDots = document.querySelectorAll('.color-dot');

      colorDots.forEach(dot => {
        const colorValue = dot.getAttribute('data-color');
        const newImage = dot.getAttribute('data-image');
        
        dot.addEventListener('click', () => {
          if (newImage && productImage) {
            productImage.src = newImage;
            selectedColor = colorValue;
            
            if (addToCartBtn) {
              addToCartBtn.setAttribute('data-image', newImage);
              addToCartBtn.setAttribute('data-color', colorValue);
            }
            
            colorDots.forEach(d => d.classList.remove('border-2', 'border-black'));
            dot.classList.add('border-2', 'border-black');
          }
        });
        
        if (preSelectedColor && colorValue === preSelectedColor) {
          const img = dot.getAttribute('data-image');
          if (img && productImage) {
            productImage.src = img;
            selectedColor = preSelectedColor;
            if (addToCartBtn) {
              addToCartBtn.setAttribute('data-image', img);
              addToCartBtn.setAttribute('data-color', preSelectedColor);
            }
            dot.classList.add('border-2', 'border-black');
          }
        }
      });

      // Ajouter au panier ou Modifier
      addToCartBtn.addEventListener('click', function() {
        const sizeValue = selectedSize ? selectedSize.textContent.trim() : '';
        const colorValue = selectedColor || this.getAttribute('data-color') || '';
        const isEditMode = this.getAttribute('data-edit-mode') === 'true';
        const editColor = this.getAttribute('data-edit-color') || '';
        const editSize = this.getAttribute('data-edit-size') || '';
        
        if (!sizeValue) {
          alert('Veuillez sélectionner une taille');
          return;
        }
        
        const newId = this.getAttribute('data-id') + '-' + colorValue + '-' + sizeValue;
        const oldId = isEditMode ? this.getAttribute('data-id') + '-' + editColor + '-' + editSize : '';
        
        const cartItem = {
          id: newId,
          name: this.getAttribute('data-name'),
          price: parseFloat(this.getAttribute('data-price')),
          quantity: quantity,
          image: this.getAttribute('data-image') || 'img/homepage/chemise_en_lin.jpg',
          color: colorValue,
          size: sizeValue,
          productId: this.getAttribute('data-id')
        };
        
        if (typeof window.cartFunctions !== 'undefined') {
          if (isEditMode && oldId && oldId !== newId) {
            // Mode modification: enlever l'ancien et ajouter le nouveau
            window.cartFunctions.removeFromCart(oldId);
            window.cartFunctions.addToCart(cartItem);
          } else {
            // Mode normal: juste ajouter
            window.cartFunctions.addToCart(cartItem);
          }
          
          window.cartFunctions.openCart();
          
          // Feedback visuel
          const btnText = isEditMode ? 'Modifié' : 'Ajouté au panier';
          addToCartBtn.textContent = '✓ ' + btnText;
          addToCartBtn.classList.add('bg-green-600', 'hover:bg-green-700');
          addToCartBtn.classList.remove('bg-black', 'hover:bg-gray-800');
          setTimeout(() => {
            addToCartBtn.innerHTML = isEditMode ? '<i class="fas fa-pencil-alt mr-2"></i> Modifier' : '<i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier';
            addToCartBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            addToCartBtn.classList.add('bg-black', 'hover:bg-gray-800');
          }, 2000);
        } else {
          console.error('Fonction cartFunctions non trouvée');
        }
      });

      updatePriceDisplay();
    });
  </script>

</body>
</html>

<?php
function mapColorNameToHex($color) {
    $colorMap = [
        "bleu alice" => "#F0F8FF",
        "blanc antique" => "#FAEBD7",
        "aqua" => "#00FFFF",
        "aigue-marine" => "#7FFFD4",
        "azur" => "#F0FFFF",
        "beige" => "#F5F5DC",
        "bisque" => "#FFE4C4",
        "noir" => "#000000",
        "amande blanchie" => "#FFEBCD",
        "bleu" => "#0000FF",
        "violet bleu" => "#8A2BE2",
        "marron" => "#A52A2A",
        "bois de rose" => "#DEB887",
        "bleu cadet" => "#5F9EA0",
        "chartreuse" => "#7FFF00",
        "chocolat" => "#D2691E",
        "corail" => "#FF7F50",
        "bleu fleur de maïs" => "#6495ED",
        "soie de maïs" => "#FFF8DC",
        "cramoisi" => "#DC143C",
        "cyan" => "#00FFFF",
        "bleu foncé" => "#00008B",
        "cyan foncé" => "#008B8B",
        "or foncé" => "#B8860B",
        "gris foncé" => "#A9A9A9",
        "vert foncé" => "#006400",
        "kaki foncé" => "#BDB76B",
        "magenta foncé" => "#8B008B",
        "olive foncé" => "#556B2F",
        "orange foncé" => "#FF8C00",
        "orchidée foncé" => "#9932CC",
        "rouge foncé" => "#8B0000",
        "saumon foncé" => "#E9967A",
        "vert marin foncé" => "#8FBC8F",
        "bleu ardoise foncé" => "#483D8B",
        "gris ardoise foncé" => "#2F4F4F",
        "turquoise foncé" => "#00CED1",
        "violet foncé" => "#9400D3",
        "rose profond" => "#FF1493",
        "bleu ciel profond" => "#00BFFF",
        "gris moyen" => "#696969",
        "bleu éclair" => "#1E90FF",
        "brique" => "#B22222",
        "blanc floral" => "#FFFAF0",
        "vert forêt" => "#228B22",
        "fuchsia" => "#FF00FF",
        "gris gain" => "#DCDCDC",
        "blanc fantôme" => "#F8F8FF",
        "or" => "#FFD700",
        "or brun" => "#DAA520",
        "gris" => "#808080",
        "vert" => "#008000",
        "vert-jaune" => "#ADFF2F",
        "blanc miel" => "#F0FFF0",
        "rose chaud" => "#FF69B4",
        "rouge indien" => "#CD5C5C",
        "indigo" => "#4B0082",
        "ivoire" => "#FFFFF0",
        "kaki" => "#F0E68C",
        "lavande" => "#E6E6FA",
        "lavande rosée" => "#FFF0F5",
        "vert pelouse" => "#7CFC00",
        "citron clair" => "#FFFACD",
        "bleu clair" => "#ADD8E6",
        "corail clair" => "#F08080",
        "cyan clair" => "#E0FFFF",
        "jaune doré clair" => "#FAFAD2",
        "gris clair" => "#D3D3D3",
        "vert clair" => "#90EE90",
        "rose clair" => "#FFB6C1",
        "saumon clair" => "#FFA07A",
        "vert mer clair" => "#20B2AA",
        "bleu ciel clair" => "#87CEFA",
        "gris ardoise clair" => "#778899",
        "bleu acier clair" => "#B0C4DE",
        "jaune clair" => "#FFFFE0",
        "lime" => "#00FF00",
        "vert lime" => "#32CD32",
        "lin" => "#FAF0E6",
        "magenta" => "#FF00FF",
        "marron rouge" => "#800000",
        "aigue-marine moyen" => "#66CDAA",
        "bleu moyen" => "#0000CD",
        "orchidée moyen" => "#BA55D3",
        "pourpre moyen" => "#9370DB",
        "vert moyen" => "#3CB371",
        "bleu moyen ardoise" => "#7B68EE",
        "vert printemps moyen" => "#00FA9A",
        "turquoise moyen" => "#48D1CC",
        "rouge violet moyen" => "#C71585",
        "bleu minuit" => "#191970",
        "crème menthe" => "#F5FFFA",
        "rose brume" => "#FFE4E1",
        "mocassin" => "#FFE4B5",
        "blanc navajo" => "#FFDEAD",
        "bleu marine" => "#000080",
        "dentelle ancienne" => "#FDF5E6",
        "olive" => "#808000",
        "olive dru" => "#6B8E23",
        "orange" => "#FFA500",
        "rouge orangé" => "#FF4500",
        "orchidée" => "#DA70D6",
        "or pâle" => "#EEE8AA",
        "vert pâle" => "#98FB98",
        "turquoise pâle" => "#AFEEEE",
        "rouge violet pâle" => "#DB7093",
        "crème papaye" => "#FFEFD5",
        "pêche" => "#FFDAB9",
        "pérou" => "#CD853F",
        "rose" => "#FFC0CB",
        "prune" => "#DDA0DD",
        "bleu poudre" => "#B0E0E6",
        "pourpre" => "#800080",
        "rouge" => "#FF0000",
        "brun rosé" => "#BC8F8F",
        "bleu royal" => "#4169E1",
        "brun selle" => "#8B4513",
        "saumon" => "#FA8072",
        "sable" => "#F4A460",
        "vert mer" => "#2E8B57",
        "coquille" => "#FFF5EE",
        "sienne" => "#A0522D",
        "argent" => "#C0C0C0",
        "bleu ciel" => "#87CEEB",
        "bleu ardoise" => "#6A5ACD",
        "gris ardoise" => "#708090",
        "neige" => "#FFFAFA",
        "vert printemps" => "#00FF7F",
        "bleu acier" => "#4682B4",
        "brun clair" => "#D2B48C",
        "bleu sarcelle" => "#008080",
        "chardon" => "#D8BFD8",
        "tomate" => "#FF6347",
        "turquoise" => "#40E0D0",
        "violet" => "#EE82EE",
        "blé" => "#F5DEB3",
        "blanc" => "#FFFFFF",
        "fumée blanche" => "#F5F5F5",
        "jaune" => "#FFFF00",
        "vert jaune" => "#9ACD32"
    ];
    
    $colorLower = strtolower(trim($color));
    return isset($colorMap[$colorLower]) ? $colorMap[$colorLower] : "#000000";
}