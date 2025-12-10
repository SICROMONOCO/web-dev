<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice 3 - Formulaire de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #FF9800;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #F57C00;
        }
        .success {
            margin-top: 20px;
            padding: 20px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 5px;
        }
        .data-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .data-label {
            font-weight: bold;
            color: #333;
        }
        .data-value {
            color: #555;
            margin-top: 5px;
        }
        .error {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            border-radius: 5px;
            color: #c62828;
        }
        .required {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulaire de Contact</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom <span class="required">*</span> :</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span> :</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Message <span class="required">*</span> :</label>
                <textarea id="message" name="message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
            </div>
            
            <button type="submit">Envoyer</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = trim($_POST['nom']);
            $email = trim($_POST['email']);
            $message = trim($_POST['message']);
            
            // Vérifier que TOUS les champs sont remplis
            if (!empty($nom) && !empty($email) && !empty($message)) {
                // Tous les champs sont remplis, afficher les données
                echo '<div class="success">';
                echo '<h2 style="margin-top: 0; color: #4CAF50;">✓ Message reçu avec succès !</h2>';
                
                echo '<div class="data-item">';
                echo '<div class="data-label">Nom :</div>';
                echo '<div class="data-value">' . htmlspecialchars($nom) . '</div>';
                echo '</div>';
                
                echo '<div class="data-item">';
                echo '<div class="data-label">Email :</div>';
                echo '<div class="data-value">' . htmlspecialchars($email) . '</div>';
                echo '</div>';
                
                echo '<div class="data-item">';
                echo '<div class="data-label">Message :</div>';
                echo '<div class="data-value">' . nl2br(htmlspecialchars($message)) . '</div>';
                echo '</div>';
                
                echo '</div>';
            } else {
                // Au moins un champ est vide
                echo '<div class="error">';
                echo '<strong>Erreur :</strong> Tous les champs sont obligatoires !<br><br>';
                
                if (empty($nom)) {
                    echo '• Le champ "Nom" est vide<br>';
                }
                if (empty($email)) {
                    echo '• Le champ "Email" est vide<br>';
                }
                if (empty($message)) {
                    echo '• Le champ "Message" est vide<br>';
                }
                
                echo '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
