<?php
require 'connexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Suppression
    $stmt = $pdo->prepare("DELETE FROM exercice WHERE id = ?");
    $stmt->execute([$id]);
    
    // Redirection avec message
    header("Location: index.php?msg=delete");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>