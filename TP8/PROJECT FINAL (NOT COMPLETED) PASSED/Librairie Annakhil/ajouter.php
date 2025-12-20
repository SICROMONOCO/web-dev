<?php
include 'db.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $categorie = $_POST['categorie'];
    $image = $_POST['image']; 
    if (!empty($nom) && !empty($image)) {
        $sql = "INSERT INTO produits (nom, categorie, image) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $categorie, $image]);
        header("Location: produits.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header><div class="container"><h1>Ajouter un Produit</h1></div></header>
    
    <div class="contact-section">
        <div class="container">
            <div class="contact-box">
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Nom du produit :</label>
                        <input type="text" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label>Catégorie :</label>
                        <input type="text" name="categorie" required>
                    </div>
                    <div class="form-group">
                        <label>Nom de l'image (ex: stylo.jpg) :</label>
                        <input type="text" name="image" required>
                        <small style="color:red"> images</small>
                    </div>
                    <button type="submit" class="btn-submit">Ajouter</button>
                </form>
                <br>
                <a href="produits.php" style="text-align:center; display:block;">Retour</a>
            </div>
        </div>
    </div>
</body>
</html>