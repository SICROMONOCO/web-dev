<?php 
include 'db.php'; 
$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Nos Produits - Librairie Annalhil</title>
    
    <link rel="stylesheet" href="style.css">

    <style>
        .header-separator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header-separator h2 {
            margin: 0;
            color: #b30300; 
            border: none; 
            padding: 0;
            font-size: 1.8rem;
        }
        .btn-add-new {
            background-color: #28a745; 
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 1rem;
        }
        .grid-system {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        .product-box {
            background: white;
            border: 1px solid #2302cb; 
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .product-box img {
            width: 100%;
            height: 180px; 
            object-fit: cover; 
            border-radius: 5px;
            margin-bottom: 10px;
            background-color: #f0f0f0;
        }
        .product-box h3 {
            margin: 10px 0;
            font-size: 1.2rem;
            color: #333;
        }
        .btn-del {
            display: inline-block;
            margin-top: auto; 
            color: #dc3545;
            border: 1px solid #dc3545;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-del:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container-principal">

        <header>
            <div class="header-content">
                <img src="logo.jpg" alt="Logo" class="logo">
                <div class="header-text">
                    <h1>Librairie Annalhil</h1>
                    <p>Catalogue des Produits</p>
                </div>
            </div>
        </header>

        <nav>
            <a href="index.html">Accueil</a>
            <a href="services.html">Nos Services</a>
            <a href="produits.php" class="active">Produits</a>
            <a href="contact.html">Contact</a>
        </nav>

        <main>
            <div class="header-separator">
                <h2>Nos Fournitures Scolaires</h2>
                <a href="ajouter.php" class="btn-add-new">+ Ajouter un produit</a>
            </div>
            <div class="grid-system">
                <?php
                if (count($produits) > 0) {
                    foreach ($produits as $item) {
                        $imagePath = "images/" . htmlspecialchars($item['image']);
                        echo '<div class="product-box">';
                        echo '<img src="' . $imagePath . '" alt="Produit">';
                        echo '<h3>' . htmlspecialchars($item['nom']) . '</h3>';
                        echo '<p style="color:#666;">' . htmlspecialchars($item['categorie']) . '</p>';
                        echo '<a href="supprimer.php?id=' . $item['id'] . '" class="btn-del" onclick="return confirm(\'Supprimer ?\');">Supprimer</a>';
                        
                        echo '</div>';
                    }
                } else {
                    echo '<p style="grid-column: 1/-1; text-align: center;">Aucun produit disponible.</p>';
                }
                ?>
            </div>
        </main>

        <footer>
            <p>Centre Agdz, Près du Lycée Al Khawarizmi | Tél: 0600539454</p>
            <p>© 2025 Librairie Annalhil</p>
        </footer>

    </div>

</body>
</html>