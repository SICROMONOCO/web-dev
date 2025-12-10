<?php
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Gestion de la déconnexion
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Utilisateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Exercice 4</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        h1 {
            color: #1a73e8;
            margin: 0;
        }
        .welcome-text {
            font-size: 1.2rem;
            color: #333;
            line-height: 1.6;
        }
        .btn-logout {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
        .content-box {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 4px;
            border-left: 4px solid #1a73e8;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Espace Membre</h1>
            <form method="POST" style="margin: 0;">
                <button type="submit" name="logout" class="btn-logout">Se déconnecter</button>
            </form>
        </div>

        <div class="welcome-text">
            <p>Bonjour <strong><?php echo htmlspecialchars($username); ?></strong>,</p>
            <p>Bienvenue sur votre espace sécurisé. Vous êtes maintenant connecté.</p>
        </div>

        <div class="content-box">
            <h3>Information</h3>
            <p>Ceci est une page protégée. Seuls les utilisateurs authentifiés peuvent voir ce contenu.</p>
        </div>
    </div>
</body>
</html>
