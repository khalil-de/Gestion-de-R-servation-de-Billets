<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login.php");
    exit();
}

$ticket_id = $_GET['ticket_id'] ?? null;
if (!$ticket_id) {
    header("Location: historique_reservations.php");
    exit();
}

// Récupérer les détails du ticket
$stmt = $pdo->prepare("SELECT details_tickets.ticket_id, films.titre AS film_titre, salles.nom AS salle_nom, reservations.date_reservation, sieges.numero_siege
                       FROM details_tickets
                       JOIN reservations ON details_tickets.reservation_id = reservations.reservation_id
                       JOIN seances ON reservations.seance_id = seances.seance_id
                       JOIN films ON seances.film_id = films.film_id
                       JOIN salles ON seances.salle_id = salles.salle_id
                       JOIN sieges ON details_tickets.siege_id = sieges.siege_id
                       WHERE details_tickets.ticket_id = ? AND reservations.client_id = ?");
$stmt->execute([$ticket_id, $_SESSION['client_id']]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    header("Location: historique_reservations.php");
    exit();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Détails du Ticket</h1>

    <h3>Film : <?= htmlspecialchars($ticket['film_titre']) ?></h3>
    <p><strong>Salle :</strong> <?= htmlspecialchars($ticket['salle_nom']) ?></p>
    <p><strong>Siège :</strong> <?= htmlspecialchars($ticket['numero_siege']) ?></p>
    <p><strong>Date de réservation :</strong> <?= $ticket['date_reservation'] ?></p>
</div>

<?php include '../includes/footer.php'; ?>
