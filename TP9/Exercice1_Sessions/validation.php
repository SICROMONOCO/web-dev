<?php
/**
 * Page de traitement du formulaire.
 * Elle vérifie les identifiants et redirige l'utilisateur.
 */

// 1. Inclure le fichier de configuration pour accéder aux constantes USERLOGIN et USERPASS
require_once('config.php');

// Gérer la déconnexion si la variable 'affaire=deconnexion' est fournie en GET
if (isset($_GET['affaire']) && $_GET['affaire'] === 'deconnexion') {
    session_start();
    // Détruire toutes les données de session
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    // Rediriger vers la page de login avec le code d'erreur 3
    header('Location: login.php?err=3');
    exit;
}

// Assurer que le script ne continue pas si les constantes n'existent pas (vérification de sécurité)
if (!defined('USERLOGIN') || !defined('USERPASS')) {
    header('Location: login.php?err=99'); // Redirection vers login avec un code d'erreur inconnu
    exit;
}

// Récupérer les données du formulaire
// Utilisation de l'opérateur de coalescence nul (??) pour éviter les notices si les clés n'existent pas
$submittedLogin = $_POST['login'] ?? '';
$submittedPass = $_POST['password'] ?? '';

// 2. Vérification si le login ou le mot de passe est vide (Erreur 1)
if (empty($submittedLogin) || empty($submittedPass)) {
    // Redirection vers login.php avec le code d'erreur 1
    header('Location: login.php?err=1');
    exit;
}

// 3. Vérification des identifiants (Erreur 2 ou Succès)
// Note: Utilisation stricte pour éviter les problèmes de type
if ($submittedLogin === USERLOGIN && $submittedPass === USERPASS) {

    // Identifiants corrects : ouvrir une session et stocker les infos
    session_start();
    // Variable de contrôle
    $_SESSION['CONNECT'] = 'OK';
    // Stocker le login et le mot de passe dans la session (selon l'exercice)
    $_SESSION['login'] = $submittedLogin;
    $_SESSION['password'] = $submittedPass;

    // Redirection vers la page d'accueil
    header('Location: accueil.php');
    exit;

} else {

    // Identifiants incorrects : Redirection vers login.php avec le code d'erreur 2
    header('Location: login.php?err=2');
    exit;
}

?>