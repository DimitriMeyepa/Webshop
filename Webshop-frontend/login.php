<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once '../config/config.php';

if (isset($_SESSION['idutilisateur'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors[] = "L'email est requis.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }

    if (empty($errors)) {
        try {
            $pdo = getPDO();
            
            $stmt = $pdo->prepare("SELECT idutilisateur, nomutilisateur, prenomutilisateur, motdepasseutilisateur FROM utilisateur WHERE emailutilisateur = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['motdepasseutilisateur'])) {
                $_SESSION['idutilisateur'] = $user['idutilisateur'];
                $_SESSION['nomutilisateur'] = $user['nomutilisateur'];
                $_SESSION['prenomutilisateur'] = $user['prenomutilisateur'];
                $_SESSION['email'] = $email;
                
                header('Location: index.php');
                exit;
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            error_log("Erreur connexion: " . $e->getMessage());
            $errors[] = "Une erreur s'est produite lors de la connexion.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion - Webshop</title>

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
      <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Connectez-vous</h1>

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
          <div class="mb-6">
            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
            <input type="email" id="email" name="email" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="votre@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
          </div>

          <div class="mb-6">
            <label for="password" class="block text-gray-700 font-semibold mb-2">Mot de passe</label>
            <input type="password" id="password" name="password" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
              placeholder="••••••••" required>
          </div>

          <button type="submit" 
            class="w-full bg-red-400 text-white py-3 rounded-lg hover:bg-red-500 transition font-semibold text-lg">
            Se connecter
          </button>
        </form>
      </div>

      <p class="text-center text-gray-600 mt-6">
        Vous n'avez pas de compte ?
        <a href="register.php" class="text-red-400 font-semibold hover:text-red-500 transition">
          S'inscrire
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
