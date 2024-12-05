<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_complet = trim($_POST['nom_complet']);
    $email = trim($_POST['email']);
    $numero_telephone = trim($_POST['numero_telephone']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $mot_de_passe_confirm = $_POST['mot_de_passe_confirm'];

    // Validation des champs
    if (empty($nom_complet) || empty($email) || empty($mot_de_passe) || empty($mot_de_passe_confirm)) {
        $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'adresse e-mail n'est pas valide.";
        header("Location: register.php");
        exit();
    }

    if ($mot_de_passe !== $mot_de_passe_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: register.php");
        exit();
    }

    try {
        // Vérifier si l'email est déjà utilisé
        $stmt = $pdo->prepare("SELECT client_id FROM clients WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $_SESSION['error'] = "L'adresse e-mail est déjà enregistrée.";
            header("Location: register.php");
            exit();
        }

        // Hachage du mot de passe
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);

        // Insérer l'utilisateur dans la base
        $stmt = $pdo->prepare("INSERT INTO clients (nom_complet, email, numero_telephone, mot_de_passe_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom_complet, $email, $numero_telephone, $mot_de_passe_hash]);

        $_SESSION['success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Inscription</title>
</head>
<body>
<div class="container mt-5">
    <h1>Créer un compte</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="nom_complet" class="form-label">Nom complet</label>
            <input type="text" class="form-control" id="nom_complet" name="nom_complet" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="numero_telephone" class="form-label">Numéro de téléphone (facultatif)</label>
            <input type="text" class="form-control" id="numero_telephone" name="numero_telephone">
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <div class="mb-3">
            <label for="mot_de_passe_confirm" class="form-label">Confirmez le mot de passe</label>
            <input type="password" class="form-control" id="mot_de_passe_confirm" name="mot_de_passe_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
        <p class="mt-3">Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
    </form>
</div>
</body>
</html>
