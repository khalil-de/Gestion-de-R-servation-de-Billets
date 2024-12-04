<?php
session_start();
require '../includes/db.php';  // Inclure le fichier de connexion à la base de données

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');  // Rediriger vers la page d'accueil si l'utilisateur n'est pas admin
    exit();
}

// Récupérer toutes les séances
$stmt = $pdo->query("
    SELECT s.seance_id, f.titre AS film_titre, sa.nom AS salle_nom, s.date_seance, s.heure_debut, s.heure_fin
    FROM seances s
    JOIN films f ON s.film_id = f.film_id
    JOIN salles sa ON s.salle_id = sa.salle_id
    ORDER BY s.date_seance, s.heure_debut
");
$seances = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajouter une nouvelle séance
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_seance'])) {
    $film_id = $_POST['film_id'];
    $salle_id = $_POST['salle_id'];
    $date_seance = $_POST['date_seance'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];

    $stmt = $pdo->prepare("
        INSERT INTO seances (film_id, salle_id, date_seance, heure_debut, heure_fin)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$film_id, $salle_id, $date_seance, $heure_debut, $heure_fin]);

    header('Location: choisir_seance.php');  // Rediriger vers la même page après l'ajout
    exit();
}

// Supprimer une séance
if (isset($_GET['delete_seance'])) {
    $seance_id = $_GET['delete_seance'];

    // Vérifier s'il existe des réservations pour cette séance
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE seance_id = ?");
    $stmt->execute([$seance_id]);
    $reservation_count = $stmt->fetchColumn();

    if ($reservation_count > 0) {
        // Si des réservations existent, afficher un message d'erreur
        echo "<script>alert('Impossible de supprimer cette séance, des réservations existent pour celle-ci.'); window.location.href='seances.php';</script>";
    } else {
        // Si aucune réservation n'existe, supprimer la séance
        $stmt = $pdo->prepare("DELETE FROM seances WHERE seance_id = ?");
        $stmt->execute([$seance_id]);

        header('Location: seances.php');  // Rediriger vers la même page après la suppression
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Séances - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">Gestion des Séances</h1>

        <!-- Formulaire pour ajouter une nouvelle séance -->
        <h2>Ajouter une Séance</h2>
        <form method="POST" action="seances.php" class="mb-5">
            <div class="mb-3">
                <label for="film_id" class="form-label">Film</label>
                <select name="film_id" id="film_id" class="form-select" required>
                    <?php
                    // Récupérer tous les films
                    $stmt = $pdo->query("SELECT film_id, titre FROM films");
                    $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($films as $film) {
                        echo "<option value='{$film['film_id']}'>{$film['titre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="salle_id" class="form-label">Salle</label>
                <select name="salle_id" id="salle_id" class="form-select" required>
                    <?php
                    // Récupérer toutes les salles
                    $stmt = $pdo->query("SELECT salle_id, nom FROM salles");
                    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($salles as $salle) {
                        echo "<option value='{$salle['salle_id']}'>{$salle['nom']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_seance" class="form-label">Date de la Séance</label>
                <input type="date" name="date_seance" id="date_seance" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="heure_debut" class="form-label">Heure de Début</label>
                <input type="time" name="heure_debut" id="heure_debut" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="heure_fin" class="form-label">Heure de Fin</label>
                <input type="time" name="heure_fin" id="heure_fin" class="form-control">
            </div>
            <button type="submit" name="add_seance" class="btn btn-primary">Ajouter la Séance</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
