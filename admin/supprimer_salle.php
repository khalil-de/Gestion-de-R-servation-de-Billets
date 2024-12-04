<?php
require '../includes/db.php'; // Chemin vers la connexion PDO

if (isset($_GET['salle_id'])) {
    $salle_id = $_GET['salle_id'];

    try {
        // Vérifier si la salle est liée à des séances
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM seances WHERE salle_id = ?");
        $stmt->execute([$salle_id]);
        $seances_count = $stmt->fetchColumn();

        if ($seances_count > 0) {
            // Rediriger avec un message d'erreur si la salle est liée à des séances
            header("Location: salles.php?message=error&detail=salle_liee");
            exit;
        }

        // Supprimer la salle si aucune séance ne dépend d'elle
        $stmt = $pdo->prepare("DELETE FROM salles WHERE salle_id = ?");
        $stmt->execute([$salle_id]);

        // Rediriger avec un message de succès
        header("Location: salles.php?message=success");
        exit;

    } catch (PDOException $e) {
        // Gestion des erreurs
        header("Location: salles.php?message=error&detail=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Rediriger si l'ID de la salle n'est pas fourni
    header("Location: salles.php?message=error&detail=id_manquant");
    exit;
}
