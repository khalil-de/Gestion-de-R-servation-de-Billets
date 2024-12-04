<?php
session_start();
require '../includes/db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');  // Rediriger vers la page d'accueil si l'utilisateur n'est pas admin
    exit();
}

// Vérifier si un ID de film est passé en paramètre
if (!isset($_GET['film_id']) || !is_numeric($_GET['film_id'])) {
    header('Location: films.php');  // Si l'ID du film n'est pas valide, rediriger vers la page des films
    exit();
}

$film_id = $_GET['film_id'];

// Récupérer les informations du film à modifier
$stmt = $pdo->prepare("SELECT * FROM films WHERE film_id = ?");
$stmt->execute([$film_id]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si le film existe
if (!$film) {
    header('Location: films.php');  // Si le film n'existe pas, rediriger vers la page des films
    exit();
}

// Modifier le film
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $genre = $_POST['genre'];
    $duree = $_POST['duree'];
    $date_sortie = $_POST['date_sortie'];
    $description = $_POST['description'];
    $affiche_url = $_POST['affiche_url'];

    // Mise à jour du film dans la base de données
    $stmt = $pdo->prepare("
        UPDATE films
        SET titre = ?, genre = ?, duree = ?, date_sortie = ?, description = ?, affiche_url = ?
        WHERE film_id = ?
    ");
    $stmt->execute([$titre, $genre, $duree, $date_sortie, $description, $affiche_url, $film_id]);

    // Rediriger vers la page des films après la modification
    header('Location: films.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Film - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">Modifier le Film</h1>

        <!-- Formulaire de modification du film -->
        <form method="POST" action="modifier_film.php?film_id=<?= $film['film_id'] ?>">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" id="titre" value="<?= htmlspecialchars($film['titre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" name="genre" id="genre" value="<?= htmlspecialchars($film['genre']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="duree" class="form-label">Durée (en minutes)</label>
                <input type="number" class="form-control" name="duree" id="duree" value="<?= $film['duree'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="date_sortie" class="form-label">Date de Sortie</label>
                <input type="date" class="form-control" name="date_sortie" id="date_sortie" value="<?= $film['date_sortie'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="4"><?= htmlspecialchars($film['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="affiche_url" class="form-label">URL de l'Affiche</label>
                <input type="text" class="form-control" name="affiche_url" id="affiche_url" value="<?= htmlspecialchars($film['affiche_url']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Modifier le Film</button>
        </form>

        <div class="mt-3">
            <a href="films.php" class="btn btn-secondary">Retour à la Liste des Films</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
