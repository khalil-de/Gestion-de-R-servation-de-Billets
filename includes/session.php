<?php
// session.php : Gestion de la session utilisateur

session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['client_id'])) {
    header('Location: /auth/login.php');
    exit();
}
?>

