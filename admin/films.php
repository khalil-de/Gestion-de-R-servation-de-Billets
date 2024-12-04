<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM films");
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Films</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Films Disponibles</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Genre</th>
                    <th>Durée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($films as $film): ?>
                <tr>
                    <td><?= $film['film_id'] ?></td>
                    <td><?= htmlspecialchars($film['titre']) ?></td>
                    <td><?= htmlspecialchars($film['genre']) ?></td>
                    <td><?= htmlspecialchars($film['duree']) ?> minutes</td>
                    <td>
                        <a href="modifier_film.php?id=<?= $film['film_id'] ?>" class="btn btn-warning">Modifier</a>
                        <a href="supprimer_film.php?film_id=<?= $film['film_id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce film ?');">Supprimer</a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div>
        <a href="ajouter_film.php" class="btn btn-primary mb-3 ">Ajouter un Film</a>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Annulé</a>
    </div>
    </div>
   
</body>
</html>
