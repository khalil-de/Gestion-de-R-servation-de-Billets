<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login.php");
    exit();
}

// Récupérer les réservations de l'utilisateur
$stmt = $pdo->prepare("SELECT reservations.reservation_id, films.titre AS film_titre, salles.nom AS salle_nom, seances.date_seance, seances.heure_debut
                       FROM reservations
                       JOIN seances ON reservations.seance_id = seances.seance_id
                       JOIN films ON seances.film_id = films.film_id
                       JOIN salles ON seances.salle_id = salles.salle_id
                       WHERE reservations.client_id = ?");
$stmt->execute([$_SESSION['client_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>


<div class="container mt-5">
    <h1 class="mb-4">Historique des Réservations</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Film</th>
                <th>Salle</th>
                <th>Date</th>
                <th>Heure Début</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= htmlspecialchars($reservation['film_titre']) ?></td>
                    <td><?= htmlspecialchars($reservation['salle_nom']) ?></td>
                    <td><?= $reservation['date_seance'] ?></td>
                    <td><?= $reservation['heure_debut'] ?></td>
                    <td>
                        <a href="ticket_details.php?ticket_id=<?= $reservation['reservation_id'] ?>" class="btn btn-info btn-sm">Détails</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


