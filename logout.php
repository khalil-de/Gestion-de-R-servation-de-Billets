<?php
session_start();
session_destroy();  // Détruire la session
header('Location: index.php');  // Rediriger vers la page d'accueil
exit();
?>
