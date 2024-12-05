<?php
session_start();
require '../includes/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit;
}

// Vérifier si l'ID du film est passé dans l'URL
if (!isset($_GET['film_id']) || empty($_GET['film_id'])) {
    header('Location: ../index.php');
    exit;
}

$film_id = $_GET['film_id'];

// Récupérer les informations du film
$sql = "SELECT * FROM films WHERE film_id = :film_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':film_id', $film_id, PDO::PARAM_INT);
$stmt->execute();
$film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    die('Film non trouvé.');
}

// Vérifier si une séance pour ce film est disponible
$sql = "SELECT seance_id FROM seances WHERE film_id = :film_id LIMIT 1"; // Vous pouvez ajuster la condition de sélection en fonction des critères (par exemple, date, salle)
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':film_id', $film_id, PDO::PARAM_INT);
$stmt->execute();
$seance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    die('Aucune séance disponible pour ce film.');
}

$seance_id = $seance['seance_id'];

// Si l'utilisateur confirme la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_SESSION['client_id'];

    // Insérer la réservation dans la base de données
    $sql = "INSERT INTO reservations (client_id, seance_id, places_reservees) VALUES (:client_id, :seance_id, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->bindValue(':seance_id', $seance_id, PDO::PARAM_INT); // Utiliser le seance_id trouvé ci-dessus
    $stmt->execute();

    // Récupérer l'ID de la réservation nouvellement créée
    $reservation_id = $pdo->lastInsertId();

    // Rediriger vers une page de confirmation du ticket
    header('Location: reservation_confirmation.php?reservation_id=' . $reservation_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - <?= htmlspecialchars($film['titre']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Réserver le film : <?= htmlspecialchars($film['titre']) ?></h1>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($film['titre']) ?></h5>
                <p class="card-text">Genre : <?= htmlspecialchars($film['genre']) ?></p>
                <p class="card-text">Durée : <?= htmlspecialchars($film['duree']) ?> minutes</p>
                <p class="card-text">Date de sortie : <?= htmlspecialchars($film['date_sortie']) ?></p>
                <form method="POST">
                    <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
