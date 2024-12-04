<?php
require '../includes/db.php'; // Inclure la connexion à la base de données

// Récupérer toutes les séances avec les informations des films et des salles
$stmt = $pdo->query("
    SELECT 
        seances.seance_id, 
        films.titre AS film_titre, 
        salles.nom AS salle_nom, 
        seances.date_seance, 
        seances.heure_debut, 
        seances.heure_fin 
    FROM seances
    JOIN films ON seances.film_id = films.film_id
    JOIN salles ON seances.salle_id = salles.salle_id
    ORDER BY seances.date_seance, seances.heure_debut
");
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success">
        La séance a été supprimée avec succès.
    </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
    <div class="alert alert-danger">
        Impossible de supprimer la séance. Elle est liée à d'autres entités.
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Séances</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gérer les Séances</h1>
        

        <?php if (count($seances) > 0): ?>
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Film</th>
                        <th>Salle</th>
                        <th>Date</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seances as $seance): ?>
                        <tr>
                            <td><?= htmlspecialchars($seance['seance_id']) ?></td>
                            <td><?= htmlspecialchars($seance['film_titre']) ?></td>
                            <td><?= htmlspecialchars($seance['salle_nom']) ?></td>
                            <td><?= htmlspecialchars($seance['date_seance']) ?></td>
                            <td><?= htmlspecialchars($seance['heure_debut']) ?></td>
                            <td><?= htmlspecialchars($seance['heure_fin']) ?></td>
                            <td>
                                <a href="modifier_seance.php?seance_id=<?= $seance['seance_id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                <a href="supprimer_seance.php?seance_id=<?= $seance['seance_id'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette séance ?');">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Aucune séance trouvée.</div>
        <?php endif; ?>
        <div>
        <a href="seances.php" class="btn btn-primary mb-3">Ajouter une Séance</a>
        <a href="dashboard.php" class="btn btn-secondary mb-3">Annulé</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
