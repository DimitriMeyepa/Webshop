<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';

try {
    $pdo = getPDO();

    // Récupérer tous les produits pour hommes, les plus récents d'abord
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE genreprod = 'homme' ORDER BY dateajoutprod DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chemin absolu côté serveur pour retirer le préfixe Windows
    $frontendPath = 'C:/wamp64/www/Webshop/Webshop-frontend/';

    foreach ($products as &$p) {
        // Image principale
        $p['image_principale'] = !empty($p['imageprod']) ? str_replace($frontendPath, '', $p['imageprod']) : null;

        // Images supplémentaires
        $stmt2 = $pdo->prepare("SELECT image_path FROM produit_images WHERE produit_id = ?");
        $stmt2->execute([$p['referenceprod']]);
        $images = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        $p['images'] = array_map(fn($img) => str_replace($frontendPath, '', $img), $images);

        // Tailles et couleur
        $p['tailles_disponibles'] = explode(',', $p['tailleprod'] ?? '');
        $p['couleur_actuelle'] = explode(',', $p['couleurprod'])[0] ?? null;
    }

    echo json_encode([
        'success' => true,
        'products' => $products
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur', 'detail' => $e->getMessage()]);
}
