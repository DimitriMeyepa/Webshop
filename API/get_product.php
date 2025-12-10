<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/config.php';

if (!isset($_GET['ref']) || !is_numeric($_GET['ref'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Référence produit invalide']);
    exit;
}

$ref = (int)$_GET['ref'];

try {
    $pdo = getPDO();
    
    // Récupérer le produit
    $stmt = $pdo->prepare("SELECT * FROM produit WHERE referenceprod = ?");
    $stmt->execute([$ref]);
    $product = $stmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Produit non trouvé']);
        exit;
    }
    
    // Récupérer les images du produit
    $stmt2 = $pdo->prepare("SELECT image_path FROM produit_images WHERE produit_id = ?");
    $stmt2->execute([$ref]);
    $images = $stmt2->fetchAll(PDO::FETCH_COLUMN);

    // Transformer les chemins absolus en chemins relatifs pour le web
    $images_rel = array_map(function($path) {
        return str_replace('C:/wamp64/www/Webshop/Webshop-frontend/', '', $path);
    }, $images);

    // Formatage du JSON
    $response = [
        'success' => true,
        'product' => [
            'id' => $product['referenceprod'],
            'nom' => $product['nomprod'],
            'description' => $product['descriptionprod'],
            'description_longue' => $product['descriptionsupprod'] ?? '',
            'prix' => $product['prixprod'],
            'categorie' => $product['categorieprod'] ?? 'Non catégorisé',
            'image_principale' => $images_rel[0] ?? null, // PAS de fallback
            'images' => $images_rel,
            'couleur_actuelle' => explode(',', $product['couleurprod'])[0] ?? null,
            'tailles_disponibles' => explode(',', $product['tailleprod'] ?? ''),
        ],
    ];
    
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur', 'detail' => $e->getMessage()]);
}
