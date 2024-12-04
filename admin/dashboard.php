<?php
session_start();
require '../includes/db.php';

// Vérifier l'accès admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Récupérer les statistiques
$films_count = $pdo->query("SELECT COUNT(*) FROM films")->fetchColumn();
$seances_count = $pdo->query("SELECT COUNT(*) FROM seances")->fetchColumn();
$clients_count = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$reservations_count = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Admin</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="films.php">Films</a></li>
                <li class="nav-item"><a class="nav-link" href="choisir_seance.php">Séances</a></li>
                <li class="nav-item"><a class="nav-link" href="salles.php">Salles</a></li>
                <li class="nav-item"><a class="nav-link" href="tickets.php">Tickets</a></li>
                <li class="nav-item"><a class="nav-link" href="../index.php">Clients</a></li>

                <li class="nav-item"><a class="nav-link" href="../logout.php">Déconnexion</a></li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center">Tableau de Bord Admin</h1>
        <div class="row text-center mt-5">
            <div class="col-md-3">
                <div class="card p-4 bg-light">
                    <h2><?= $films_count ?></h2>
                    <p>Films</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 bg-light">
                    <h2><?= $seances_count ?></h2>
                    <p>Séances</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 bg-light">
                    <h2><?= $clients_count ?></h2>
                    <p>Clients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4 bg-light">
                    <h2><?= $reservations_count ?></h2>
                    <p>Réservations</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
