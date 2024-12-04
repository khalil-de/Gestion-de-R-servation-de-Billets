<?php
session_start();
require '../includes/db.php';  // Include database connection

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');  // Redirect if not admin
    exit();
}

// Fetch all tickets
$stmt = $pdo->query("
    SELECT dt.ticket_id, r.reservation_id, c.nom_complet AS client_nom, s.numero_siege, dt.prix, r.date_reservation
    FROM details_tickets dt
    JOIN reservations r ON dt.reservation_id = r.reservation_id
    JOIN clients c ON r.client_id = c.client_id
    JOIN sieges s ON dt.siege_id = s.siege_id
    ORDER BY r.date_reservation DESC
");

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optionally, fetch the total number of tickets
$stmt = $pdo->query("SELECT COUNT(*) AS total_tickets FROM details_tickets");
$total_tickets = $stmt->fetch(PDO::FETCH_ASSOC)['total_tickets'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tickets - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Gestion des Tickets</h1>

        <!-- Display Total Tickets -->
        <div class="alert alert-info text-center">
            <strong>Total des Tickets: </strong> <?= $total_tickets ?>
        </div>

        <!-- Ticket Table -->
        <h2>Liste des Tickets</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Réservation</th>
                    <th>Client</th>
                    <th>Numéro de Siège</th>
                    <th>Prix</th>
                    <th>Date de Réservation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= $ticket['ticket_id'] ?></td>
                        <td><?= $ticket['reservation_id'] ?></td>
                        <td><?= $ticket['client_nom'] ?></td>
                        <td><?= $ticket['numero_siege'] ?></td>
                        <td><?= number_format($ticket['prix'], 2, ',', ' ') ?> €</td>
                        <td><?= $ticket['date_reservation'] ?></td>
                        <td>
                            <a href="modifier_ticket.php?ticket_id=<?= $ticket['ticket_id'] ?>" class="btn btn-warning">Modifier</a>
                            <a href="supprimer_ticket.php?ticket_id=<?= $ticket['ticket_id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Optionally, a button to add new tickets -->
        <a href="ajouter_ticket.php" class="btn btn-success mt-4">Ajouter un Ticket</a>
        <a href="dashboard.php" class="btn btn-secondary mt-4">Annulé</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
