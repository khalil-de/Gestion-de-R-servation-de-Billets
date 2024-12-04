<?php
require '../includes/db.php'; // Connexion à la base de données

// Vérifier si un ID de séance a été fourni
if (!isset($_GET['seance_id'])) {
    die("ID de la séance non spécifié.");
}

$seance_id = $_GET['seance_id'];

// Récupérer les détails de la séance
$stmt = $pdo->prepare("
    SELECT seances.*, films.titre AS film_titre, salles.nom AS salle_nom
    FROM seances
    JOIN films ON seances.film_id = films.film_id
    JOIN salles ON seances.salle_id = salles.salle_id
    WHERE seances.seance_id = ?
");
$stmt->execute([$seance_id]);
$seance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    die("Séance introuvable.");
}

// Récupérer la liste des films et des salles pour les options de sélection
$films = $pdo->query("SELECT film_id, titre FROM films")->fetchAll(PDO::FETCH_ASSOC);
$salles = $pdo->query("SELECT salle_id, nom FROM salles")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $film_id = $_POST['film_id'];
    $salle_id = $_POST['salle_id'];
    $date_seance = $_POST['date_seance'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    // Mise à jour des données de la séance
    $stmt = $pdo->prepare("
        UPDATE seances
        SET film_id = ?, salle_id = ?, date_seance = ?, heure_debut = ?, heure_fin = ?
        WHERE seance_id = ?
    ");
    $stmt->execute([$film_id, $salle_id, $date_seance, $heure_debut, $heure_fin, $seance_id]);

    // Redirection après la mise à jour
    header("Location: choisir_seance.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Séance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Modifier une Séance</h1>
        <form action="" method="POST" class="mt-4">
            <div class="mb-3">
                <label for="film_id" class="form-label">Film</label>
                <select name="film_id" id="film_id" class="form-select" required>
                    <?php foreach ($films as $film): ?>
                        <option value="<?= $film['film_id'] ?>" <?= $film['film_id'] == $seance['film_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($film['titre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="salle_id" class="form-label">Salle</label>
                <select name="salle_id" id="salle_id" class="form-select" required>
                    <?php foreach ($salles as $salle): ?>
                        <option value="<?= $salle['salle_id'] ?>" <?= $salle['salle_id'] == $seance['salle_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($salle['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_seance" class="form-label">Date</label>
                <input type="date" name="date_seance" id="date_seance" class="form-control" value="<?= htmlspecialchars($seance['date_seance']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="heure_debut" class="form-label">Heure de Début</label>
                <input type="time" name="heure_debut" id="heure_debut" class="form-control" value="<?= htmlspecialchars($seance['heure_debut']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="heure_fin" class="form-label">Heure de Fin</label>
                <input type="time" name="heure_fin" id="heure_fin" class="form-control" value="<?= htmlspecialchars($seance['heure_fin']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="choisir_seance.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
