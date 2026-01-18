<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once '../config/config.php';

if (!isset($_SESSION['idutilisateur'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = false;
$user = null;

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE idutilisateur = ?");
$stmt->execute([$_SESSION['idutilisateur']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $pays = trim($_POST['pays'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($nom) || strlen($nom) < 2) {
        $errors[] = "Le nom est invalide.";
    }

    if (empty($prenom) || strlen($prenom) < 2) {
        $errors[] = "Le prénom est invalide.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    } elseif ($email !== $user['emailutilisateur']) {
        $stmt = $pdo->prepare("SELECT idutilisateur FROM utilisateur WHERE emailutilisateur = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Cet email est déjà utilisé.";
        }
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

    if (!empty($new_password) || !empty($confirm_password)) {
        if (empty($old_password)) {
            $errors[] = "Le mot de passe actuel est requis pour changer le mot de passe.";
        } elseif (!password_verify($old_password, $user['motdepasseutilisateur'])) {
            $errors[] = "Le mot de passe actuel est incorrect.";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
    }

    if (empty($errors)) {
        try {
            $updateFields = [];
            $updateValues = [];

            $updateFields[] = "nomutilisateur = ?";
            $updateValues[] = $nom;
            $updateFields[] = "prenomutilisateur = ?";
            $updateValues[] = $prenom;
            $updateFields[] = "emailutilisateur = ?";
            $updateValues[] = $email;
            $updateFields[] = "adresseutilisateur = ?";
            $updateValues[] = $adresse;
            $updateFields[] = "villeutilisateur = ?";
            $updateValues[] = $ville;
            $updateFields[] = "codepostalutilisateur = ?";
            $updateValues[] = $code_postal;
            $updateFields[] = "paysutilisateur = ?";
            $updateValues[] = $pays;
            $updateFields[] = "telephoneutilisateur = ?";
            $updateValues[] = $telephone;

            if (!empty($new_password)) {
                $updateFields[] = "motdepasseutilisateur = ?";
                $updateValues[] = password_hash($new_password, PASSWORD_BCRYPT);
            }

            $updateValues[] = $_SESSION['idutilisateur'];
            $query = "UPDATE utilisateur SET " . implode(", ", $updateFields) . " WHERE idutilisateur = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute($updateValues);

            $_SESSION['nomutilisateur'] = $nom;
            $_SESSION['prenomutilisateur'] = $prenom;
            $_SESSION['email'] = $email;

            $success = true;

            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE idutilisateur = ?");
            $stmt->execute([$_SESSION['idutilisateur']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erreur mise à jour profil: " . $e->getMessage());
            $errors[] = "Une erreur s'est produite lors de la mise à jour.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mon Profil - Webshop</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body class="bg-gray-50 text-gray-800">
  <?php include 'includes/navbar.php'; ?>

  <main class="container mx-auto px-6 py-16 min-h-screen">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-bold mb-2 text-gray-800">Mon Profil</h1>
      <p class="text-gray-600 mb-8">Gérez vos informations personnelles</p>

      <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
          <i class="fas fa-check-circle mr-2"></i> Profil mis à jour avec succès!
        </div>
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

      <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <h2 class="col-span-full text-2xl font-bold text-gray-800 mb-4">Informations Personnelles</h2>

            <div>
              <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
              <input type="text" id="nom" name="nom" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['nomutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
              <input type="text" id="prenom" name="prenom" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['prenomutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
              <input type="email" id="email" name="email" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['emailutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="telephone" class="block text-gray-700 font-semibold mb-2">Téléphone</label>
              <input type="tel" id="telephone" name="telephone" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['telephoneutilisateur'] ?? ''); ?>">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <h2 class="col-span-full text-2xl font-bold text-gray-800 mb-4">Adresse</h2>

            <div class="col-span-full">
              <label for="adresse" class="block text-gray-700 font-semibold mb-2">Adresse</label>
              <input type="text" id="adresse" name="adresse" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['adresseutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="ville" class="block text-gray-700 font-semibold mb-2">Ville</label>
              <input type="text" id="ville" name="ville" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['villeutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="code_postal" class="block text-gray-700 font-semibold mb-2">Code Postal</label>
              <input type="text" id="code_postal" name="code_postal" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['codepostalutilisateur'] ?? ''); ?>" required>
            </div>

            <div>
              <label for="pays" class="block text-gray-700 font-semibold mb-2">Pays</label>
              <input type="text" id="pays" name="pays" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                value="<?php echo htmlspecialchars($user['paysutilisateur'] ?? ''); ?>">
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <h2 class="col-span-full text-2xl font-bold text-gray-800 mb-4">Sécurité</h2>
            <p class="col-span-full text-gray-600 text-sm">Laissez les champs de mot de passe vides si vous ne souhaitez pas changer votre mot de passe.</p>

            <div>
              <label for="old_password" class="block text-gray-700 font-semibold mb-2">Mot de passe actuel</label>
              <input type="password" id="old_password" name="old_password" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                placeholder="••••••••">
            </div>

            <div>
              <label for="new_password" class="block text-gray-700 font-semibold mb-2">Nouveau mot de passe</label>
              <input type="password" id="new_password" name="new_password" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                placeholder="••••••••">
            </div>

            <div>
              <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">Confirmer le mot de passe</label>
              <input type="password" id="confirm_password" name="confirm_password" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition" 
                placeholder="••••••••">
            </div>
          </div>

          <div class="flex items-center space-x-4">
            <button type="submit" class="bg-red-400 text-white px-6 py-3 rounded-lg hover:bg-red-500 transition font-semibold">
              <i class="fas fa-save mr-2"></i> Enregistrer les modifications
            </button>
            <a href="index.php" class="text-gray-600 hover:text-gray-800 font-semibold">
              <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="bg-gray-800 text-gray-400 py-6 text-center mt-12">
    <p><i class="far fa-copyright mr-1"></i> 2025 Webshop. Tous droits réservés.</p>
  </footer>

  <script src="js/nav.js"></script>
  <script src="js/cart.js"></script>
</body>

</html>
