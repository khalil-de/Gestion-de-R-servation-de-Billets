<?php
session_start();
require '../includes/db.php';  // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les salles et leur état
$stmt = $pdo->query("
    SELECT 
        sa.salle_id, 
        sa.nom AS salle_nom, 
        sa.capacite, 
        COALESCE(SUM(r.places_reservees), 0) AS places_reservees,
        (sa.capacite - COALESCE(SUM(r.places_reservees), 0)) AS places_disponibles
    FROM salles sa
    LEFT JOIN seances s ON sa.salle_id = s.salle_id
    LEFT JOIN reservations r ON s.seance_id = r.seance_id
    GROUP BY sa.salle_id, sa.nom, sa.capacite
    ORDER BY sa.nom
");
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État des Salles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>État des Salles</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom de la Salle</th>
                    <th>Capacité</th>
                    <th>Places Réservées</th>
                    <th>Places Disponibles</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salles as $index => $salle): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($salle['salle_nom']) ?></td>
                    <td><?= $salle['capacite'] ?></td>
                    <td><?= $salle['places_reservees'] ?></td>
                    <td><?= $salle['places_disponibles'] ?></td>
                    <td>
                        <?php if ($salle['places_disponibles'] > 0): ?>
                            <span class="badge bg-success">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Complète</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Retour au Tableau de Bord</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
