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
    
 // Récupérer les images du produit AVEC leur couleur
$stmt2 = $pdo->prepare("SELECT image_path, couleur FROM produit_images WHERE produit_id = ?");
$stmt2->execute([$ref]);
$images = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Transformer les chemins absolus en chemins relatifs
$images_rel = array_map(function($img) {
    return [
        'path' => str_replace('C:/wamp64/www/Webshop/Webshop-frontend/', '', $img['image_path']),
        'couleur' => $img['couleur']
    ];
}, $images);

// Formatage du JSON final
$response = [
    'success' => true,
    'product' => [
        'id' => $product['referenceprod'],
        'nom' => $product['nomprod'],
        'description' => $product['descriptionprod'],
        'description_longue' => $product['descriptionsupprod'] ?? '',
        'prix' => $product['prixprod'],
        'categorie' => $product['categorieprod'] ?? 'Non catégorisé',
        'image_principale' => $images_rel[0]['path'] ?? null,
        'images' => $images_rel, // <-- Maintenant images + couleurs
        'couleurs_disponibles' => array_column($images_rel, 'couleur'),
        'tailles_disponibles' => array_map('trim', explode(',', $product['tailleprod'] ?? '')),
    ],
];

echo json_encode($response);


} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur', 'detail' => $e->getMessage()]);
}
