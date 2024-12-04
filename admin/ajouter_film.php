<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $genre = $_POST['genre'];
    $duree = $_POST['duree'];
    $date_sortie = $_POST['date_sortie'];
    $affiche_url = $_POST['affiche_url'];

    $stmt = $pdo->prepare("INSERT INTO films (titre, genre, duree, date_sortie, affiche_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $genre, $duree, $date_sortie, $affiche_url]);

    header('Location: films.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Film</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter un Film</h1>
        <form action="ajouter_film.php" method="POST">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" required>
            </div>
            <div class="mb-3">
                <label for="duree" class="form-label">Durée (minutes)</label>
                <input type="number" class="form-control" id="duree" name="duree" required>
            </div>
            <div class="mb-3">
                <label for="date_sortie" class="form-label">Date de Sortie</label>
                <input type="date" class="form-control" id="date_sortie" name="date_sortie" required>
            </div>
            <div class="mb-3">
                <label for="affiche_url" class="form-label">URL de l'Affiche</label>
                <input type="text" class="form-control" id="affiche_url" name="affiche_url" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>
