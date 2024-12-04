<?php
require '../includes/db.php';

// Récupérer l'ID de la salle à modifier
if (isset($_GET['salle_id'])) {
    $salle_id = $_GET['salle_id'];

    // Récupérer les détails de la salle
    $stmt = $pdo->prepare("SELECT * FROM salles WHERE salle_id = ?");
    $stmt->execute([$salle_id]);
    $salle = $stmt->fetch();

    if (!$salle) {
        echo "Salle introuvable.";
        exit;
    }
}

// Mettre à jour les informations de la salle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];

    $updateStmt = $pdo->prepare("UPDATE salles SET nom = ?, capacite = ? WHERE salle_id = ?");
    $updateStmt->execute([$nom, $capacite, $salle_id]);

    header("Location: salles.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Salle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Modifier la Salle</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la Salle</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($salle['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" class="form-control" id="capacite" name="capacite" value="<?= htmlspecialchars($salle['capacite']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="admin/salles.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
