<?php
session_start();
require '../includes/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer l'ID de la réservation depuis l'URL
if (!isset($_GET['reservation_id']) || empty($_GET['reservation_id'])) {
    header('Location: ../index.php');
    exit;
}

$reservation_id = $_GET['reservation_id'];

// Récupérer les détails de la réservation
$sql = "SELECT r.*, f.titre AS film_titre, f.genre AS film_genre, f.duree AS film_duree, f.date_sortie AS film_date_sortie
        FROM reservations r
        JOIN seances s ON r.seance_id = s.seance_id
        JOIN films f ON s.film_id = f.film_id
        WHERE r.reservation_id = :reservation_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':reservation_id', $reservation_id, PDO::PARAM_INT);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$reservation) {
    die('Réservation non trouvée.');
}

// Récupérer les informations de l'utilisateur
$client_id = $reservation['client_id'];
$sql = "SELECT * FROM clients WHERE client_id = :client_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Utilisateur non trouvé.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation - <?= htmlspecialchars($reservation['film_titre']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Confirmation de Réservation</h1>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($reservation['film_titre']) ?></h5>
                <p class="card-text"><strong>Genre :</strong> <?= htmlspecialchars($reservation['film_genre']) ?></p>
                <p class="card-text"><strong>Durée :</strong> <?= htmlspecialchars($reservation['film_duree']) ?> minutes</p>
                <p class="card-text"><strong>Date de sortie :</strong> <?= htmlspecialchars($reservation['film_date_sortie']) ?></p>
                <p class="card-text"><strong>Nombre de places réservées :</strong> <?= htmlspecialchars($reservation['places_reservees']) ?></p>
                <p class="card-text"><strong>Date de réservation :</strong> <?= htmlspecialchars($reservation['date_reservation']) ?></p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Informations de l'Utilisateur</h5>
                <p class="card-text"><strong>Nom :</strong> <?= htmlspecialchars($user['nom_complet']) ?></p>
                <p class="card-text"><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p class="card-text"><strong>Téléphone :</strong> <?= htmlspecialchars($user['numero_telephone']) ?></p>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="../index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
