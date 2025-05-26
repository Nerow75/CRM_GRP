<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Configuration de l'API Prestashop
$api_url = 'https://hardcore-archimedes.62-210-99-155.plesk.page/modules/bridge/index.php?action=getAllProducts&api_key=123456';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    die('Erreur lors de la récupération des produits.');
}

// Décoder la réponse JSON
$products = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur lors du décodage des données JSON.');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Liste des Produits</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?> €</td>
                        <td><?= htmlspecialchars($product['quantity']) ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-primary btn-sm">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
    </div>
</body>

</html>