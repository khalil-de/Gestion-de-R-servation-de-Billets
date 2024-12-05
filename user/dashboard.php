<?php
session_start();
require '../includes/db.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login.php");
    exit();
}

// Récupération des informations utilisateur
$client_id = $_SESSION['client_id'];
$nom_complet = $_SESSION['nom_complet'] ?? 'Utilisateur'; // Fallback si "nom_complet" n'est pas défini

try {
    // Réservations récentes
    $stmt = $pdo->prepare("
        SELECT r.reservation_id, f.titre AS film_titre, s.date_seance, s.heure_debut, sa.nom AS salle_nom, r.places_reservees
        FROM reservations r
        JOIN seances s ON r.seance_id = s.seance_id
        JOIN films f ON s.film_id = f.film_id
        JOIN salles sa ON s.salle_id = sa.salle_id
        WHERE r.client_id = ?
        ORDER BY r.date_reservation DESC
        LIMIT 5
    ");
    $stmt->execute([$client_id]);
    $recent_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Séances disponibles
    $stmt = $pdo->query("
        SELECT s.seance_id, f.titre AS film_titre, s.date_seance, s.heure_debut, sa.nom AS salle_nom
        FROM seances s
        JOIN films f ON s.film_id = f.film_id
        JOIN salles sa ON s.salle_id = sa.salle_id
        WHERE s.date_seance >= CURDATE() -- Filtrer les séances à venir
        ORDER BY s.date_seance ASC, s.heure_debut ASC
        LIMIT 5
    ");
    $available_seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../path/to/bootstrap.css">
    <title>Tableau de bord utilisateur</title>

</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <!-- Bienvenue -->
        <div class="dashboard-header mb-4">
            <h1>Bienvenue, <?= htmlspecialchars($nom_complet); ?> !</h1>
            <p>Accédez à vos réservations et réservez des places pour vos séances préférées.</p>
        </div>

        <!-- Réservations récentes -->
        <div class="mt-4">
            <h2>Vos Réservations Récentes</h2>
            <?php if (count($recent_reservations) > 0): ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Film</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Salle</th>
                            <th>Places réservées</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['film_titre']); ?></td>
                                <td><?= htmlspecialchars($reservation['date_seance']); ?></td>
                                <td><?= htmlspecialchars($reservation['heure_debut']); ?></td>
                                <td><?= htmlspecialchars($reservation['salle_nom']); ?></td>
                                <td><?= htmlspecialchars($reservation['places_reservees']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-warning">Vous n'avez pas encore de réservations.</p>
            <?php endif; ?>
        </div>
        
    </div>
</body>
   <?php include '../includes/footer.php'; ?>
</html>
