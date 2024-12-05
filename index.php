<?php
session_start();
require 'includes/db.php';

// Vérifier si l'utilisateur est connecté (Admin ou Client)
$is_logged_in = isset($_SESSION['client_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Vérifier si une recherche est effectuée
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Construire la requête de films avec recherche (si applicable)
$sql = "
    SELECT film_id, titre, genre, duree, affiche_url
    FROM films
    WHERE duree > 0
";
if (!empty($search_query)) {
    $sql .= " AND (titre LIKE :search OR genre LIKE :search)";
}
$sql .= " ORDER BY date_sortie DESC LIMIT 9";

$stmt = $pdo->prepare($sql);

// Bind le paramètre de recherche
if (!empty($search_query)) {
    $stmt->bindValue(':search', '%' . $search_query . '%');
}

$stmt->execute();
$films = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion de Réservation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Réservation Cinéma</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                        <li class="nav-item"><a class="nav-link" href="user/salles.php">Salles</a></li>
                        <li class="nav-item"><a class="nav-link" href="user/historique_reservations.php">Mes Réservations</a></li>
                        <li class="nav-item"><a class="nav-link" href="user/dashboard.php">Profil</a></li>
                        <?php if ($is_admin): ?>
                            <li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Administration</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Bienvenue sur l'application de gestion de réservation</h1>
        <p class="text-center">Réservez vos places pour les meilleurs films facilement et rapidement !</p>

        <!-- Form for search functionality -->
        <form class="d-flex mb-4" method="GET" action="index.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Rechercher un film" value="<?= htmlspecialchars($search_query) ?>" aria-label="Search">
            <button class="btn btn-success" type="submit">Rechercher</button>
        </form>

        <h2 class="mt-5">Films Actuellement Disponibles</h2>
        <div class="row mt-3">
            <?php if (count($films) > 0): ?>
                <?php foreach ($films as $film): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <?php if (!empty($film['affiche_url'])): ?>
                                <img src="<?= htmlspecialchars($film['affiche_url']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($film['titre']) ?>" 
                                     style="height: 100%; object-fit: cover; border-radius: 0;">
                            <?php else: ?>
                                <img src="images/default_movie.jpg" 
                                     class="card-img-top" 
                                     alt="Image non disponible" 
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($film['titre']) ?></h5>
                                <p class="card-text">Genre : <?= htmlspecialchars($film['genre']) ?></p>
                                <p class="card-text">Durée : <?= htmlspecialchars($film['duree']) ?> minutes</p>
                            </div>
                            <a href="user/reserver_seance.php?film_id=<?= $film['film_id'] ?>" class="btn btn-primary">Réserver</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun film trouvé correspondant à votre recherche.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 Gestion de Réservation. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
