<?php
session_start();
require '../includes/db.php';

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');  // Rediriger vers la page d'accueil si l'utilisateur n'est pas admin
    exit();
}

// Vérifier si un film ID est passé via l'URL
if (isset($_GET['film_id'])) {
    $film_id = $_GET['film_id'];

    try {
        // Démarrer une transaction
        $pdo->beginTransaction();

        // Étape 1: Supprimer les réservations associées aux séances du film
        $stmt = $pdo->prepare("
            DELETE FROM reservations 
            WHERE seance_id IN (
                SELECT seance_id FROM seances WHERE film_id = ?
            )
        ");
        $stmt->execute([$film_id]);

        // Étape 2: Supprimer les séances associées au film
        $stmt = $pdo->prepare("DELETE FROM seances WHERE film_id = ?");
        $stmt->execute([$film_id]);

        // Étape 3: Supprimer le film de la base de données
        $stmt = $pdo->prepare("DELETE FROM films WHERE film_id = ?");
        $stmt->execute([$film_id]);

        // Confirmer la transaction
        $pdo->commit();

        // Rediriger vers la liste des films après la suppression
        header('Location: films.php');
        exit();

    } catch (PDOException $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
        exit();
    }

} else {
    // Si aucun ID de film n'est passé, rediriger vers la liste des films
    header('Location: films.php');
    exit();
}
?>
