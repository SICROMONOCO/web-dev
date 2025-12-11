<?php
require 'connexion.php';

// Vérifier si un ID est passé
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// --- TRAITEMENT : MISE À JOUR ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier'])) {
    $sql = "UPDATE exercice SET titre = ?, auteur = ?, date_creation = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['titre'], $_POST['auteur'], $_POST['date_creation'], $id]);
    
    header("Location: index.php?msg=update");
    exit();
}

// --- RÉCUPÉRATION DE L'EXERCICE ACTUEL ---
$stmt = $pdo->prepare("SELECT * FROM exercice WHERE id = ?");
$stmt->execute([$id]);
$exercice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exercice) {
    die("Exercice introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un exercice</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <fieldset>
            <legend>Modifier un exercice</legend>
            <form method="post" action="">
                <label>Titre de l'exercice</label>
                <input type="text" name="titre" value="<?= htmlspecialchars($exercice['titre']) ?>" required>
                
                <label>Auteur de l'exercice</label>
                <input type="text" name="auteur" value="<?= htmlspecialchars($exercice['auteur']) ?>" required>
                
                <label>Date création</label>
                <input type="date" name="date_creation" value="<?= htmlspecialchars($exercice['date_creation']) ?>" required>
                
                <br>
                <button type="submit" name="modifier">Modifier</button>
            </form>
        </fieldset>
    </div>
</body>
</html>