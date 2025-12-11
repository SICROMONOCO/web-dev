<?php
require 'connexion.php';

// --- TRAITEMENT : AJOUT D'UN EXERCICE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    if (!empty($_POST['titre']) && !empty($_POST['auteur']) && !empty($_POST['date_creation'])) {
        $sql = "INSERT INTO exercice (titre, auteur, date_creation) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['titre'], $_POST['auteur'], $_POST['date_creation']]);
        
        // Redirection pour éviter la resoumission du formulaire
        header("Location: index.php?msg=add");
        exit();
    }
}

// --- TRAITEMENT : RÉCUPÉRATION DES DONNÉES ---
$stmt = $pdo->query("SELECT * FROM exercice ORDER BY id DESC");
$exercices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exercice 2 : CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Exercice 2 : CRUD avec php et mysql</h2>

        <!-- MESSAGE DE SUCCÈS -->
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert">
                <?php 
                    if ($_GET['msg'] == 'add') echo "L'exercice a été ajouté avec succès";
                    elseif ($_GET['msg'] == 'update') echo "L'exercice a été mis à jour avec succès";
                    elseif ($_GET['msg'] == 'delete') echo "L'exercice a été supprimé avec succès";
                ?>
            </div>
        <?php endif; ?>

        <!-- FORMULAIRE D'AJOUT -->
        <fieldset>
            <legend>Ajouter un exercice</legend>
            <form method="post" action="">
                <label>Titre de l'exercice</label>
                <input type="text" name="titre" required>
                
                <label>Auteur de l'exercice</label>
                <input type="text" name="auteur" required>
                
                <label>Date création</label>
                <input type="date" name="date_creation" required>
                
                <br>
                <button type="submit" name="ajouter">Envoyer</button>
            </form>
        </fieldset>

        <!-- TABLEAU D'AFFICHAGE -->
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>titre</th>
                    <th>auteur</th>
                    <th>date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercices as $exo): ?>
                <tr>
                    <td><?= htmlspecialchars($exo['id']) ?></td>
                    <td><?= htmlspecialchars($exo['titre']) ?></td>
                    <td><?= htmlspecialchars($exo['auteur']) ?></td>
                    <td><?= htmlspecialchars($exo['date_creation']) ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $exo['id'] ?>">Modifier</a>
                        <!-- JS confirmation comme demandé -->
                        <a href="supprimer.php?id=<?= $exo['id'] ?>" 
                           onclick="return confirm('Vous voulez vraiment supprimer cet exercice ?')">
                           Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>