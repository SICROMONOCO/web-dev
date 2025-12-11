<?php
session_start();

// Vérifier si l'utilisateur est connecté via la variable de session CONNECT
if (!isset($_SESSION['CONNECT']) || $_SESSION['CONNECT'] !== 'OK') {
    // Rediriger vers la page de login si non connecté
    header('Location: login.php');
    exit;
}

$loginName = $_SESSION['login'] ?? 'itisme';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body { font-family: sans-serif; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f0f4f8; }
        h1 { font-size: 2.2em; color: #10b981; margin: 0 0 1rem 0; }
        a.logout { color: #ef4444; text-decoration: none; font-weight: bold; border: 1px solid #fee2e2; background: #fff7f7; padding: 0.5rem 0.75rem; border-radius: 6px; }
        a.logout:hover { background:#ffecec }
    </style>
</head>
<body>
    <h1>Hello <?php echo htmlspecialchars($loginName); ?></h1>
    <a class="logout" href="validation.php?affaire=deconnexion">Deconnexion</a>
</body>
</html>