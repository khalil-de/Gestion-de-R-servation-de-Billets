<?php
// session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Réservation de Billets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">Réservation de Billets</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Accueil</a>
                </li>
                <?php if (isset($_SESSION['client_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="historique_reservations.php">Historique des Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="salles.php">Salle</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Déconnexion</a>
                    </li>
                <?php else: ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/dashboard.php">Dashboard Admin</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
