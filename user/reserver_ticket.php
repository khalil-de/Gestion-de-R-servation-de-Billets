<?php
include 'db.php';
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$siege_id = intval($_GET['siege_id']);
$user_id = $_SESSION['user_id'];

// Marquer le siège comme réservé
$query = "UPDATE sieges SET est_reserve = 1, user_id = $user_id WHERE id = $siege_id";
if (mysqli_query($conn, $query)) {
    header('Location: historique_reservations.php');
    exit();
} else {
    echo "Erreur lors de la réservation : " . mysqli_error($conn);
}
?>