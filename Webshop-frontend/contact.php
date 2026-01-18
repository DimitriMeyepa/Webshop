<?php
session_start();
require_once '../config/config.php';

// Initialisation des variables
$nomComplet = '';
$email = '';
$sujet = '';
$message = '';
$error = '';
$success = '';

// Vérifier si l'utilisateur est connecté - CORRECTION ICI
if (isset($_SESSION['idutilisateur'])) {
    $userId = $_SESSION['idutilisateur']; // Changé de user_id à idutilisateur
    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT nomutilisateur, prenomutilisateur, emailutilisateur FROM utilisateur WHERE idutilisateur = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user) {
            $nomComplet = $user['nomutilisateur'] . ' ' . $user['prenomutilisateur'];
            $email = $user['emailutilisateur'];
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des informations utilisateur.";
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $emailInput = trim($_POST['email'] ?? '');
    
    // Validation
    if (empty($sujet) || empty($message)) {
        $error = "Le sujet et le message sont obligatoires.";
    } elseif (empty($nom) || empty($emailInput)) {
        $error = "Le nom et l'email sont obligatoires.";
    } elseif (!filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        try {
            $pdo = getPDO();
            
            // CORRECTION ICI AUSSI
            $idUtilisateur = isset($_SESSION['idutilisateur']) ? $_SESSION['idutilisateur'] : NULL;
            
            // Insérer dans la base de données
            $stmt = $pdo->prepare("INSERT INTO contact (sujetcontact, messagecontact, dateenvoie, idutilisateur) VALUES (?, ?, NOW(), ?)");
            $stmt->execute([$sujet, $message, $idUtilisateur]);
            
            $success = "Votre message a été envoyé avec succès!";
            
            // Réinitialiser les champs
            $sujet = '';
            $message = '';
            
            // Si l'utilisateur n'était pas connecté, réinitialiser aussi nom et email
            if (!isset($_SESSION['idutilisateur'])) { // Changé ici aussi
                $nomComplet = '';
                $email = '';
            }
            
        } catch (PDOException $e) {
            // Pour debug, affichons l'erreur complète
            $error = "Erreur lors de l'envoi du message: " . $e->getMessage();
            $error .= "<br><small>ID utilisateur: " . ($idUtilisateur ?? 'NULL') . "</small>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact | Webshop</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display&display=swap"
    rel="stylesheet" />

  <!-- Ton fichier CSS -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="bg-white text-gray-800 font-sans">

  <!-- NAVBAR -->
  <?php include 'includes/navbar.php'; ?>

  <!-- CONTACT SECTION -->
  <section class="min-h-screen flex flex-col md:flex-row items-center justify-center p-6 md:p-16 gap-12">

    <!-- IMAGE -->
    <div class="w-full md:w-1/2 flex justify-center md:justify-end">
      <div class="h-[550px] flex items-center">
        <img src="img/contact/contactphoto.png" alt="Models"
          class="w-[450px] h-[550px] object-cover rounded-lg shadow-lg">
      </div>
    </div>

    <!-- FORMULAIRE -->
    <div class="w-full md:w-1/2 flex justify-center md:justify-start">
      <div
        class="bg-transparent border border-gray-300 rounded-lg p-8 md:p-10 w-full max-w-lg h-auto flex flex-col justify-between">
        <div>
          <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center md:text-left">Contactez-nous</h1>
          
          <!-- Messages d'erreur/succès -->
          <?php if ($error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
              <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>
          
          <?php if ($success): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
              <?php echo htmlspecialchars($success); ?>
            </div>
          <?php endif; ?>
          
          <!-- Debug info (à commenter en production) -->
          <?php if (isset($_SESSION['idutilisateur'])): ?>
            <div class="mb-4 p-2 bg-red-400 text-black text-sm rounded">
              Connecté en tant que: <?php echo htmlspecialchars($nomComplet); ?>
            </div>
          <?php else: ?>
            <div class="mb-4 p-2 bg-yellow-100 text-yellow-700 text-sm rounded">
              Non connecté
            </div>
          <?php endif; ?>

          <form method="POST" action="" class="space-y-5">
            <div>
              <label class="block text-sm font-medium mb-2">Nom complet</label>
              <input type="text" name="nom" placeholder="Votre nom" value="<?php echo htmlspecialchars($nomComplet); ?>"
                class="w-full border-b border-gray-400 bg-transparent focus:outline-none focus:border-black py-2"
                <?php echo isset($_SESSION['idutilisateur']) ? 'readonly' : ''; ?>>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">E-mail</label>
              <input type="email" name="email" placeholder="votremail@email.com" value="<?php echo htmlspecialchars($email); ?>"
                class="w-full border-b border-gray-400 bg-transparent focus:outline-none focus:border-black py-2"
                <?php echo isset($_SESSION['idutilisateur']) ? 'readonly' : ''; ?>>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Sujet</label>
              <input type="text" name="sujet" placeholder="Sujet de votre message" value="<?php echo htmlspecialchars($sujet); ?>"
                class="w-full border-b border-gray-400 bg-transparent focus:outline-none focus:border-black py-2">
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Message</label>
              <textarea name="message" placeholder="Votre message" rows="3"
                class="w-full border-b border-gray-400 bg-transparent focus:outline-none focus:border-black py-2"><?php echo htmlspecialchars($message); ?></textarea>
            </div>

            <button type="submit"
              class="w-full md:w-auto bg-red-400 text-white rounded-full px-8 py-3 mt-4 hover:bg-red-500 transition">
              Envoyer
            </button>
          </form>
        </div>

        <div class="flex flex-col md:flex-row justify-center md:justify-start items-center md:space-x-6 mt-8 text-xl space-y-4 md:space-y-0">
          <div class="flex space-x-5">
            <a href="#" aria-label="Facebook" class="hover:text-gray-600">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" aria-label="Instagram" class="hover:text-gray-600">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" aria-label="Twitter" class="hover:text-gray-600">
              <i class="fab fa-twitter"></i>
            </a>
          </div>

          <!-- Email à droite -->
          <div class="text-sm text-gray-700 font-medium border-l border-gray-300 pl-4">
            <a href="mailto:webshop@gmail.com" class="hover:text-red-400 transition">
              webshop@gmail.com
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="bg-gray-800 text-gray-400 py-6 text-center">
    <p>
      <i class="far fa-copyright mr-1"></i>
      2025 Webshop. Tous droits réservés.
    </p>
  </footer>

  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
</body>

</html>