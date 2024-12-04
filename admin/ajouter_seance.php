<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../index.php');
    exit;
}

// Récupérer les films et les salles pour les options
$films = $pdo->query("SELECT * FROM films")->fetchAll(PDO::FETCH_ASSOC);
$salles = $pdo->query("SELECT * FROM salles")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $film_id = $_POST['film_id'];
    $salle_id = $_POST['salle_id'];
    $date_heure = $_POST['date_heure'];

    // Découper la valeur de date_heure en date et heure
    $date_time = new DateTime($date_heure);
    $date_seance = $date_time->format('Y-m-d');
    $heure_debut = $date_time->format('H:i:s');

    $stmt = $pdo->prepare("INSERT INTO seances (film_id, salle_id, date_seance, heure_debut) VALUES (?, ?, ?, ?)");
    $stmt->execute([$film_id, $salle_id, $date_seance, $heure_debut]);

    header('Location: seances.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Séance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter une Séance</h1>
        <form action="ajouter_seance.php" method="POST">
            <div class="mb-3">
                <label for="film_id" class="form-label">Film</label>
                <select class="form-control" id="film_id" name="film_id" required>
                    <?php foreach ($films as $film): ?>
                    <option value="<?= $film['film_id'] ?>"><?= htmlspecialchars($film['titre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="salle_id" class="form-label">Salle</label>
                <select class="form-control" id="salle_id" name="salle_id" required>
                    <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle['salle_id'] ?>"><?= htmlspecialchars($salle['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_heure" class="form-label">Date et Heure</label>
                <input type="datetime-local" class="form-control" id="date_heure" name="date_heure" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>
