<?php
require '../includes/db.php'; // Connexion à la base de données

// Vérification si l'ID de la séance est fourni
if (!isset($_GET['seance_id'])) {
    die("ID de la séance non spécifié.");
}

$seance_id = $_GET['seance_id'];

try {
    // Préparation et exécution de la requête pour supprimer la séance
    $stmt = $pdo->prepare("DELETE FROM seances WHERE seance_id = ?");
    $stmt->execute([$seance_id]);

    // Redirection après suppression réussie
    header("Location: choisir_seance.php?success=1");
    exit;
} catch (PDOException $e) {
    // Vérification des contraintes d'intégrité référentielles
    if ($e->getCode() == '23000') {
        header("Location: choisir_seance.php?error=1");
        exit;
    } else {
        // Affichage d'une erreur générale si un autre problème survient
        die("Erreur : " . $e->getMessage());
    }
}
