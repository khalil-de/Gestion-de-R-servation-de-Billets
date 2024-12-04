<?php
session_start();
require '../includes/db.php'; // Database connection

// Check if the admin is logged in
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php"); // Redirect to login page if not an admin
    exit;
}

// Query to get all reservations with details
$query = "
    SELECT 
        r.reservation_id,
        f.titre AS film,
        s.nom AS salle,
        se.date_seance,
        se.heure_debut,
        se.heure_fin,
        r.places_reservees,
        r.date_reservation,
        c.nom_complet AS client_name
    FROM 
        reservations r
    INNER JOIN seances se ON r.seance_id = se.seance_id
    INNER JOIN films f ON se.film_id = f.film_id
    INNER JOIN salles s ON se.salle_id = s.salle_id
    INNER JOIN clients c ON r.client_id = c.client_id
    ORDER BY 
        r.date_reservation DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Réservations - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Réservation Cinéma</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Tableau de Bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="historique_reservations.php">Historique des Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Historique des Réservations</h1>
        <div class="table-responsive mt-4">
            <?php if (count($reservations) > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Film</th>
                            <th>Salle</th>
                            <th>Date de Séance</th>
                            <th>Heure</th>
                            <th>Places Réservées</th>
                            <th>Date de Réservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $index => $reservation): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($reservation['client_name']) ?></td>
                                <td><?= htmlspecialchars($reservation['film']) ?></td>
                                <td><?= htmlspecialchars($reservation['salle']) ?></td>
                                <td><?= htmlspecialchars($reservation['date_seance']) ?></td>
                                <td>
                                    <?= htmlspecialchars($reservation['heure_debut']) ?> - 
                                    <?= htmlspecialchars($reservation['heure_fin'] ?: 'Non spécifiée') ?>
                                </td>
                                <td><?= htmlspecialchars($reservation['places_reservees']) ?></td>
                                <td><?= htmlspecialchars($reservation['date_reservation']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    Aucune réservation n'a encore été effectuée.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Réservation Cinéma. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
