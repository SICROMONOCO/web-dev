<?php
try {
    // Remplacez 'root' et '' par votre login et mot de passe si différents
    $pdo = new PDO('mysql:host=localhost;dbname=TP10;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
?>
