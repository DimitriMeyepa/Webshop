<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once '../config/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $pays = trim($_POST['pays'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';

    if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    } elseif (strlen($nom) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    }

    if (empty($prenom)) {
        $errors[] = "Le prénom est requis.";
    } elseif (strlen($prenom) < 2) {
        $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    }

    if (empty($adresse)) {
        $errors[] = "L'adresse est requise.";
    }

    if (empty($ville)) {
        $errors[] = "La ville est requise.";
    }

    if (empty($code_postal)) {
        $errors[] = "Le code postal est requis.";
    }

    if (!empty($telephone)) {
    }

    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } else {
        $password_errors = [];
        
        if (strlen($password) < 12) {
            $password_errors[] = "au moins 12 caractères";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $password_errors[] = "au moins une majuscule";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $password_errors[] = "au moins une minuscule";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $password_errors[] = "au moins un chiffre";
        }
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            $password_errors[] = "au moins un caractère spécial (!@#$%^&* etc.)";
        }
        
        if (!empty($password_errors)) {
            $errors[] = "Le mot de passe doit contenir: " . implode(", ", $password_errors);
        }
    }

    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        try {
            $pdo = getPDO();
            
            $stmt = $pdo->prepare("SELECT idutilisateur FROM utilisateur WHERE emailutilisateur = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Cet email est déjà utilisé.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                $stmt = $pdo->prepare("INSERT INTO utilisateur (nomutilisateur, prenomutilisateur, emailutilisateur, motdepasseutilisateur, adresseutilisateur, villeutilisateur, codepostalutilisateur, paysutilisateur, telephoneutilisateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $hashed_password, $adresse, $ville, $code_postal, $pays, $telephone]);
                
                $userId = $pdo->lastInsertId();
                
                $_SESSION['idutilisateur'] = $userId;
                $_SESSION['nomutilisateur'] = $nom;
                $_SESSION['prenomutilisateur'] = $prenom;
                $_SESSION['email'] = $email;
                
                $success = true;
            }
        } catch (PDOException $e) {
            error_log("Erreur inscription: " . $e->getMessage());
            $errors[] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>S'inscrire - Webshop</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="bg-white text-gray-800">
  <?php include 'includes/navbar.php'; ?>

  <main class="container mx-auto px-6 py-16 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
      <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">S'inscrire</h1>

      <?php if ($success): ?>
        <script>
          window.location.href = 'index.php';
        </script>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          <ul class="list-disc pl-5">
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
        <form method="POST">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
              <input type="text" id="nom" name="nom" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                placeholder="Dupont" value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" required>
            </div>
            <div>
              <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
              <input type="text" id="prenom" name="prenom" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                placeholder="Jean" value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" required>
            </div>
          </div>

          <div class="mb-6">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="votre@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
          </div>

          <div class="mb-6">
            <label for="adresse" class="block text-gray-700 font-semibold mb-2">Adresse</label>
            <input type="text" id="adresse" name="adresse" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="123 Rue de la Paix" value="<?php echo htmlspecialchars($_POST['adresse'] ?? ''); ?>" required>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="ville" class="block text-gray-700 font-semibold mb-2">Ville</label>
              <input type="text" id="ville" name="ville"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                placeholder="Port-Louis" value="<?php echo htmlspecialchars($_POST['ville'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="code_postal" class="block text-gray-700 font-semibold mb-2">Code postal</label>
              <input type="text" id="code_postal" name="code_postal"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                placeholder="00000" value="<?php echo htmlspecialchars($_POST['code_postal'] ?? ''); ?>" required>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
              <label for="pays" class="block text-gray-700 font-semibold mb-2">Pays</label>
              <input type="text" id="pays" name="pays"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                placeholder="Maurice" value="<?php echo htmlspecialchars($_POST['pays'] ?? ''); ?>">
            </div>

            <div>
              <label for="telephone" class="block text-gray-700 font-semibold mb-2">Téléphone</label>
              <input type="tel" id="telephone" name="telephone"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                placeholder="+230" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
            </div>
          </div>

          <div class="mb-6">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Mot de passe</label>
            <input type="password" id="password" name="password" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="••••••••" required>
            <p class="text-xs text-gray-500 mt-2">Minimum 12 caractères, avec majuscules, minuscules, chiffres et caractères spéciaux</p>
          </div>

          <div class="mb-6">
            <label for="confirm-password" class="block text-gray-700 font-semibold mb-2">Confirmer le mot de passe</label>
            <input type="password" id="confirm-password" name="confirm-password" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="••••••••" required>
          </div>

          <button type="submit" 
            class="w-full bg-red-400 text-white py-3 rounded-lg hover:bg-red-500 transition font-semibold text-lg">
            S'inscrire
          </button>
        </form>
      </div>

      <p class="text-center text-gray-600 mt-6">
        Vous avez déjà un compte ?
        <a href="login.php" class="text-red-400 font-semibold hover:text-red-500 transition">
          Se connecter
        </a>
      </p>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 py-6 text-center mt-12">
    <p>
      <i class="far fa-copyright mr-1"></i> 2025 Webshop. Tous droits réservés.
    </p>
  </footer>

  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
</body>

</html>
