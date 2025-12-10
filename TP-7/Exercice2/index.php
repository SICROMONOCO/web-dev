<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice 2 - Générateur de mot de passe</title>
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
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #0b7dda;
        }
        .result {
            margin-top: 20px;
            padding: 20px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            border-radius: 5px;
        }
        .password {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #1565c0;
            word-break: break-all;
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .info {
            margin-top: 10px;
            font-size: 12px;
            color: #666;
        }
        .error {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            border-radius: 5px;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Générateur de Mot de Passe</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="longueur">Longueur du mot de passe :</label>
                <input type="number" id="longueur" name="longueur" min="6" max="50" required 
                       value="<?php echo isset($_POST['longueur']) ? htmlspecialchars($_POST['longueur']) : '12'; ?>">
            </div>
            
            <button type="submit">Générer le mot de passe</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $longueur = $_POST['longueur'];
            
            if (is_numeric($longueur) && $longueur >= 6 && $longueur <= 50) {
                // Définir les caractères disponibles
                $lettresMinuscules = 'abcdefghijklmnopqrstuvwxyz';
                $lettresMajuscules = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $chiffres = '0123456789';
                $caracteresSpeciaux = '!@#$%^&*()-_=+[]{}|;:,.<>?';
                
                // Combiner tous les caractères
                $tousLesCaracteres = $lettresMinuscules . $lettresMajuscules . $chiffres . $caracteresSpeciaux;
                $longueurCaracteres = strlen($tousLesCaracteres);
                
                $motDePasse = '';
                
                // Garantir au moins un caractère de chaque type
                $motDePasse .= $lettresMinuscules[rand(0, strlen($lettresMinuscules) - 1)];
                $motDePasse .= $lettresMajuscules[rand(0, strlen($lettresMajuscules) - 1)];
                $motDePasse .= $chiffres[rand(0, strlen($chiffres) - 1)];
                $motDePasse .= $caracteresSpeciaux[rand(0, strlen($caracteresSpeciaux) - 1)];
                
                // Remplir le reste aléatoirement
                for ($i = strlen($motDePasse); $i < $longueur; $i++) {
                    $index = rand(0, $longueurCaracteres - 1);
                    $motDePasse .= $tousLesCaracteres[$index];
                }
                
                // Mélanger le mot de passe pour que les caractères garantis ne soient pas au début
                $motDePasseArray = str_split($motDePasse);
                shuffle($motDePasseArray);
                $motDePasse = implode('', $motDePasseArray);
                
                echo '<div class="result">';
                echo '<strong>Votre mot de passe :</strong>';
                echo '<div class="password">' . htmlspecialchars($motDePasse) . '</div>';
                echo '<div class="info">✓ Contient des lettres minuscules et majuscules<br>';
                echo '✓ Contient des chiffres<br>';
                echo '✓ Contient des caractères spéciaux</div>';
                echo '</div>';
            } else {
                echo '<div class="error"><strong>Erreur :</strong> La longueur doit être entre 6 et 50 caractères !</div>';
            }
        }
        ?>
    </div>
</body>
</html>
