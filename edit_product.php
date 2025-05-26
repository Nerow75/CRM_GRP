<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    die('ID du produit manquant.');
}

$product_id = $_GET['id'];

// Récupérer les détails du produit via l'API
$api_url = "https://hardcore-archimedes.62-210-99-155.plesk.page/modules/bridge/index.php?action=getProduct&id=$product_id&api_key=123456";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    die('Erreur lors de la récupération du produit.');
}

$product = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur lors du décodage des données JSON.');
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_quantity = $_POST['quantity'];

    // Envoyer la mise à jour à l'API
    $update_url = "https://hardcore-archimedes.62-210-99-155.plesk.page/modules/bridge/index.php?action=update&product_id=1&quantity=7&api_key=123456";

    $data = [
        'id' => $product_id,
        'quantity' => $updated_quantity
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $update_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $update_response = curl_exec($ch);
    curl_close($ch);

    if ($update_response === false) {
        die('Erreur lors de la mise à jour du produit.');
    }

    header('Location: listing.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Modifier le Produit</h1>
        <form method="POST">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" disabled>
            </div>
            <div class="form-group">
                <label for="quantity">Stock</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="<?= htmlspecialchars($product['quantity']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="listing.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>

</html>