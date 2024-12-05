// includes/auth.php
<?php
session_start();

function checkAdminAccess() {
    if (!isset($_SESSION['client_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        header('Location: login.php');
        exit();
    }
}
?>
