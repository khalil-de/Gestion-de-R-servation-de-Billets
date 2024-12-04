<?php
session_start();
require '../includes/db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php'); // Rediriger vers l'accueil si non admin
    exit();
}

// Initialisation des variables
$nom = '';
$capacite = '';
$error_message = '';
$success_message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $capacite = trim($_POST['capacite']);

    // Validation des champs
    if (empty($nom) || empty($capacite)) {
        $error_message = "Tous les champs sont obligatoires.";
    } elseif (!is_numeric($capacite) || $capacite <= 0) {
        $error_message = "La capacité doit être un nombre positif.";
    } else {
        // Insertion dans la base de données
        try {
            $stmt = $pdo->prepare("INSERT INTO salles (nom, capacite) VALUES (?, ?)");
            $stmt->execute([$nom, $capacite]);
            $success_message = "Salle ajoutée avec succès.";
            $nom = ''; // Réinitialiser les champs du formulaire
            $capacite = '';
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'ajout de la salle : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Salle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Ajouter une Salle</h1>
        
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la Salle</label>
                <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
            </div>
            <div class="mb-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" id="capacite" name="capacite" class="form-control" value="<?= htmlspecialchars($capacite) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="salles.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
