<?php
session_start();
require 'includes/db.php'; // Inclusion du fichier de connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs du formulaire avec validation basique
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérification des champs vides
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Requête pour récupérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("SELECT client_id, nom_complet, mot_de_passe_hash, is_admin FROM clients WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['mot_de_passe_hash'])) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['client_id'] = $user['client_id'];
                $_SESSION['nom_complet'] = $user['nom_complet']; // Correction ici pour utiliser le bon tableau
                $_SESSION['is_admin'] = (int)$user['is_admin']; // Stockage sécurisé

                // Redirection selon le rôle
                if ($user['is_admin'] == 1) {
                    header('Location: admin/dashboard.php'); // Tableau de bord admin
                } else {
                    header('Location: index.php'); // Tableau de bord utilisateur
                }
                exit();
            } else {
                // Si l'authentification échoue
                $error = "Email ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la connexion : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>

        <!-- Affichage des erreurs si elles existent -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Entrez votre email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de Passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se Connecter</button>
        </form>

        <!-- Lien vers la page d'inscription -->
        <div class="mt-3 text-center">
            <p>Pas encore inscrit ? <a href="register.php">Créez un compte</a>.</p>
        </div>
    </div>
</body>
</html>
