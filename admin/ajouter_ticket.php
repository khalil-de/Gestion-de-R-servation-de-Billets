<?php
// Include your database connection
require '../includes/db.php';

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $reservation_id = $_POST['reservation_id'];
    $client_id = $_POST['client_id'];
    $siege_id = $_POST['siege_id'];
    $prix = $_POST['prix'];

    // SQL query to insert the ticket
    $sql = "INSERT INTO details_tickets (reservation_id, siege_id, prix) 
            VALUES (?, ?, ?)";

    // Prepare and execute the query
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bind_param('iis', $reservation_id, $siege_id, $prix);
        if ($stmt->execute()) {
            $message = "Ticket ajouté avec succès!";
        } else {
            $message = "Erreur lors de l'ajout du ticket.";
        }
        $stmt->close();
    } else {
        $message = "Erreur de préparation de la requête.";
    }
}

// Fetch reservations, clients, and seats for the form options
$reservations = $pdo->query("SELECT reservation_id, CONCAT('Réservation #', reservation_id) AS reservation_label FROM reservations");
$clients = $pdo->query("SELECT client_id, nom_complet FROM clients");
$sieges = $pdo->query("SELECT siege_id, numero_siege FROM sieges");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Ticket</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="reservation_id" class="form-label">Réservation</label>
                <select class="form-control" name="reservation_id" id="reservation_id" required>
                    <option value="">Sélectionner une réservation</option>
                    <?php while ($row = $reservations->fetch_assoc()): ?>
                        <option value="<?= $row['reservation_id'] ?>"><?= $row['reservation_label'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select class="form-control" name="client_id" id="client_id" required>
                    <option value="">Sélectionner un client</option>
                    <?php while ($row = $clients->fetch_assoc()): ?>
                        <option value="<?= $row['client_id'] ?>"><?= $row['nom_complet'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="siege_id" class="form-label">Numéro de Siège</label>
                <select class="form-control" name="siege_id" id="siege_id" required>
                    <option value="">Sélectionner un siège</option>
                    <?php while ($row = $sieges->fetch_assoc()): ?>
                        <option value="<?= $row['siege_id'] ?>"><?= $row['numero_siege'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="prix" class="form-label">Prix</label>
                <input type="number" class="form-control" name="prix" id="prix" required step="0.01">
            </div>

            <button type="submit" class="btn btn-success">Ajouter un Ticket</button>
            <a href="index.php" class="btn btn-secondary">Annulé</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
