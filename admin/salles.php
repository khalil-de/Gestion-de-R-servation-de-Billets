<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}
if (isset($_GET['message'])): ?>
    <div class="alert <?= $_GET['message'] === 'success' ? 'alert-success' : 'alert-danger' ?> mt-3">
        <?php
        if ($_GET['message'] === 'success') {
            echo "La salle a été supprimée avec succès.";
        } elseif ($_GET['message'] === 'error') {
            echo "Erreur : ";
            if (isset($_GET['detail']) && $_GET['detail'] === 'salle_liee') {
                echo "La salle est liée à des séances. Veuillez d'abord supprimer ou modifier les séances associées.";
            } elseif (isset($_GET['detail'])) {
                echo htmlspecialchars($_GET['detail']);
            } else {
                echo "Une erreur inconnue s'est produite.";
            }
        }
        ?>
    </div>
<?php endif; 



// Récupérer les salles
$salles = $pdo->query("SELECT * FROM salles")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Salles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestion des Salles</h1>
        <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($salles as $salle): ?>
            <tr>
                <td><?= htmlspecialchars($salle['salle_id']) ?></td>
                <td><?= htmlspecialchars($salle['nom']) ?></td>
                <td><?= htmlspecialchars($salle['capacite']) ?> places</td>
                <td>
                    <!-- Bouton Modifier -->
                    <a href="modifier_salle.php?salle_id=<?= $salle['salle_id'] ?>" class="btn btn-warning btn-sm">
                        Modifier
                    </a>
                    <!-- Bouton Supprimer -->
                    <a href="supprimer_salle.php?salle_id=<?= $salle['salle_id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div>
        <a href="ajouter_salle.php" class="btn btn-primary mb-3">Ajouter une Salle</a>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Annulé</a>
    </div>
    </div>
    
</body>
</html>
