<?php
require_once '../config/config.php';
$pdo = getPDO();

// --- CRUD actions ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ajouter un produit
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $stmt = $pdo->prepare("
            INSERT INTO produit (referenceprod, nomprod, prixprod, tailleprod, couleurprod, categorieprod, descriptionprod, descriptionsupprod, informationsupprod, avisprod, dateajoutprod, genreprod, imageprod)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['referenceprod'],
            $_POST['nomprod'],
            $_POST['prixprod'],
            $_POST['tailleprod'],
            $_POST['couleurprod'],
            $_POST['categorieprod'],
            $_POST['descriptionprod'],
            $_POST['descriptionsupprod'],
            $_POST['informationsupprod'],
            $_POST['avisprod'],
            $_POST['dateajoutprod'],
            $_POST['genreprod'],
            $_POST['imageprod']
        ]);
        $lastId = $_POST['referenceprod'];

        // Gestion des images supplémentaires
        if (!empty($_POST['images_sup'])) {
            foreach ($_POST['images_sup'] as $index => $img) {
                $color = explode(',', $_POST['couleurprod'])[$index] ?? null;
                $stmt2 = $pdo->prepare("INSERT INTO produit_images (produit_id, image_path, couleur) VALUES (?, ?, ?)");
                $stmt2->execute([$lastId, $img, $color]);
            }
        }
    }

    // Supprimer un produit
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM produit WHERE referenceprod = ?");
        $stmt->execute([$_POST['id']]);
        $stmt2 = $pdo->prepare("DELETE FROM produit_images WHERE produit_id = ?");
        $stmt2->execute([$_POST['id']]);
    }

    // Mettre à jour un produit
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $stmt = $pdo->prepare("
            UPDATE produit SET nomprod=?, prixprod=?, tailleprod=?, couleurprod=?, categorieprod=?, descriptionprod=?, descriptionsupprod=?, informationsupprod=?, avisprod=?, dateajoutprod=?, genreprod=?, imageprod=? 
            WHERE referenceprod=?
        ");
        $stmt->execute([
            $_POST['nomprod'],
            $_POST['prixprod'],
            $_POST['tailleprod'],
            $_POST['couleurprod'],
            $_POST['categorieprod'],
            $_POST['descriptionprod'],
            $_POST['descriptionsupprod'],
            $_POST['informationsupprod'],
            $_POST['avisprod'],
            $_POST['dateajoutprod'],
            $_POST['genreprod'],
            $_POST['imageprod'],
            $_POST['id']
        ]);

        // Ajouter de nouvelles images supplémentaires
        if (!empty($_POST['images_sup'])) {
            foreach ($_POST['images_sup'] as $index => $img) {
                $color = explode(',', $_POST['couleurprod'])[$index] ?? null;
                $stmt2 = $pdo->prepare("INSERT INTO produit_images (produit_id, image_path, couleur) VALUES (?, ?, ?)");
                $stmt2->execute([$_POST['id'], $img, $color]);
            }
        }

        // Supprimer des images si demandé
        if (!empty($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imgId) {
                $stmt2 = $pdo->prepare("DELETE FROM produit_images WHERE id = ?");
                $stmt2->execute([$imgId]);
            }
        }
    }

    header("Location: admin_products.php");
    exit;
}

// --- Récupérer tous les produits ---
$products = $pdo->query("SELECT * FROM produit ORDER BY dateajoutprod DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Produits</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Gestion des produits</h1>

<!-- Ajouter un produit -->
<form method="post" class="mb-8 bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-semibold mb-4">Ajouter un produit</h2>
    <input type="hidden" name="action" value="add">
    <div class="grid grid-cols-2 gap-4">
        <input type="text" name="referenceprod" placeholder="Référence" class="border p-2 rounded" required>
        <input type="text" name="nomprod" placeholder="Nom" class="border p-2 rounded" required>
        <input type="number" name="prixprod" placeholder="Prix" class="border p-2 rounded" required>
        <input type="text" name="tailleprod" placeholder="Tailles (S,M,L)" class="border p-2 rounded">
        <input type="text" name="couleurprod" placeholder="Couleurs (rouge,bleu)" class="border p-2 rounded">
        <input type="text" name="categorieprod" placeholder="Catégorie" class="border p-2 rounded">
        <input type="text" name="genreprod" placeholder="Genre (homme/femme)" class="border p-2 rounded">
        <input type="text" name="imageprod" placeholder="Image principale (chemin)" class="border p-2 rounded">
        <textarea name="descriptionprod" placeholder="Description courte" class="border p-2 rounded col-span-2"></textarea>
        <textarea name="descriptionsupprod" placeholder="Description longue" class="border p-2 rounded col-span-2"></textarea>
        <textarea name="informationsupprod" placeholder="Informations supplémentaires" class="border p-2 rounded col-span-2"></textarea>
        <input type="number" name="avisprod" placeholder="Avis" class="border p-2 rounded">
        <div id="imagesSupContainer" class="col-span-2 grid gap-2">
            <label class="font-semibold">Images supplémentaires :</label>
            <div class="flex gap-2">
                <input type="text" name="images_sup[]" placeholder="Image 1 (chemin)" class="border p-2 rounded flex-1">
                <button type="button" id="addImageSup" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">+</button>
                <button type="button" id="removeImageSup" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">-</button>
            </div>
        </div>
    </div>
    <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Ajouter</button>
</form>

<!-- Liste des produits -->
<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-semibold mb-4">Produits existants</h2>
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Nom</th>
                <th class="border px-4 py-2">Prix</th>
                <th class="border px-4 py-2">Images</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($products as $p): 
            $images = $pdo->prepare("SELECT * FROM produit_images WHERE produit_id = ?");
            $images->execute([$p['referenceprod']]);
            $imgList = $images->fetchAll(PDO::FETCH_ASSOC);
        ?>
            <tr>
                <td class="border px-4 py-2"><?= $p['referenceprod'] ?></td>
                <td class="border px-4 py-2"><?= $p['nomprod'] ?></td>
                <td class="border px-4 py-2"><?= $p['prixprod'] ?> MUR</td>
                <td class="border px-4 py-2">
                    <?php foreach($imgList as $img): ?>
                        <div class="flex items-center gap-2 mb-1">
                            <img src="<?= $img['image_path'] ?>" class="h-12 w-12 object-cover rounded">
                            <form method="post" class="inline" onsubmit="return confirm('Supprimer cette image ?');">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $p['referenceprod'] ?>">
                                <input type="hidden" name="delete_images[]" value="<?= $img['id'] ?>">
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">X</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </td>
                <td class="border px-4 py-2 flex gap-2">
                    <form method="post" onsubmit="return confirm('Supprimer ce produit ?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['referenceprod'] ?>">
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Supprimer</button>
                    </form>
                    <button onclick="editProduct(<?= htmlspecialchars(json_encode($p)) ?>)" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Modifier</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal éditer produit -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden overflow-auto">
    <form id="editForm" method="post" class="bg-white p-6 rounded shadow-md w-3/4">
        <h2 class="text-xl font-semibold mb-4">Modifier le produit</h2>
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="editId">
        <div class="grid grid-cols-2 gap-4">
            <input type="text" name="nomprod" id="editNom" placeholder="Nom" class="border p-2 rounded" required>
            <input type="number" name="prixprod" id="editPrix" placeholder="Prix" class="border p-2 rounded" required>
            <input type="text" name="tailleprod" id="editTaille" placeholder="Tailles (S,M,L)" class="border p-2 rounded">
            <input type="text" name="couleurprod" id="editCouleur" placeholder="Couleurs (rouge,bleu)" class="border p-2 rounded">
            <input type="text" name="categorieprod" id="editCategorie" placeholder="Catégorie" class="border p-2 rounded">
            <input type="text" name="imageprod" id="editImage" placeholder="Image principale (chemin)" class="border p-2 rounded">
            <textarea name="descriptionprod" id="editDescription" placeholder="Description" class="border p-2 rounded col-span-2"></textarea>
            <textarea name="descriptionsupprod" id="editDescriptionSup" placeholder="Description longue" class="border p-2 rounded col-span-2"></textarea>
            <textarea name="informationsupprod" id="editInfos" placeholder="Informations supplémentaires" class="border p-2 rounded col-span-2"></textarea>
            <input type="number" name="avisprod" id="editAvis" placeholder="Avis" class="border p-2 rounded">
            <input type="text" name="images_sup[]" placeholder="Nouvelle image 1 (chemin)" class="border p-2 rounded col-span-2">
            <input type="text" name="images_sup[]" placeholder="Nouvelle image 2 (chemin)" class="border p-2 rounded col-span-2">
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="closeModal()" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Annuler</button>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Enregistrer</button>
        </div>
    </form>
</div>

<script>
function editProduct(product) {
    document.getElementById('editId').value = product.referenceprod;
    document.getElementById('editNom').value = product.nomprod;
    document.getElementById('editPrix').value = product.prixprod;
    document.getElementById('editTaille').value = product.tailleprod;
    document.getElementById('editCouleur').value = product.couleurprod;
    document.getElementById('editCategorie').value = product.categorieprod;
    document.getElementById('editImage').value = product.imageprod;
    document.getElementById('editDescription').value = product.descriptionprod;
    document.getElementById('editDescriptionSup').value = product.descriptionsupprod;
    document.getElementById('editInfos').value = product.informationsupprod;
    document.getElementById('editAvis').value = product.avisprod;
    document.getElementById('editModal').classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('imagesSupContainer');
    const addBtn = document.getElementById('addImageSup');
    const removeBtn = document.getElementById('removeImageSup');

    addBtn.addEventListener('click', () => {
        const newField = document.createElement('div');
        newField.className = 'flex gap-2 mt-1';
        newField.innerHTML = `
            <input type="text" name="images_sup[]" placeholder="Nouvelle image (chemin)" class="border p-2 rounded flex-1">
        `;
        container.appendChild(newField);
    });

    removeBtn.addEventListener('click', () => {
        const fields = container.querySelectorAll('div.flex');
        if (fields.length > 1) { // garder au moins un champ
            container.removeChild(fields[fields.length - 1]);
        }
    });
});
function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

</script>

</body>
</html>
