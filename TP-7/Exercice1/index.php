<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice 1 - Calculatrice Simple</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
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
        input[type="number"], select {
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 5px;
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
        <h1>Calculatrice Simple</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre1">Premier nombre :</label>
                <input type="number" id="nombre1" name="nombre1" step="any" required 
                       value="<?php echo isset($_POST['nombre1']) ? htmlspecialchars($_POST['nombre1']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="nombre2">Deuxième nombre :</label>
                <input type="number" id="nombre2" name="nombre2" step="any" required 
                       value="<?php echo isset($_POST['nombre2']) ? htmlspecialchars($_POST['nombre2']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="operation">Opération :</label>
                <select id="operation" name="operation" required>
                    <option value="">-- Choisir une opération --</option>
                    <option value="addition" <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'addition') ? 'selected' : ''; ?>>Addition (+)</option>
                    <option value="soustraction" <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'soustraction') ? 'selected' : ''; ?>>Soustraction (-)</option>
                    <option value="multiplication" <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'multiplication') ? 'selected' : ''; ?>>Multiplication (×)</option>
                    <option value="division" <?php echo (isset($_POST['operation']) && $_POST['operation'] == 'division') ? 'selected' : ''; ?>>Division (÷)</option>
                </select>
            </div>
            
            <button type="submit">Calculer</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre1 = $_POST['nombre1'];
            $nombre2 = $_POST['nombre2'];
            $operation = $_POST['operation'];
            
            if (is_numeric($nombre1) && is_numeric($nombre2) && !empty($operation)) {
                $resultat = 0;
                $operateur = '';
                $erreur = false;
                
                switch ($operation) {
                    case 'addition':
                        $resultat = $nombre1 + $nombre2;
                        $operateur = '+';
                        break;
                    case 'soustraction':
                        $resultat = $nombre1 - $nombre2;
                        $operateur = '-';
                        break;
                    case 'multiplication':
                        $resultat = $nombre1 * $nombre2;
                        $operateur = '×';
                        break;
                    case 'division':
                        if ($nombre2 != 0) {
                            $resultat = $nombre1 / $nombre2;
                            $operateur = '÷';
                        } else {
                            $erreur = true;
                            echo '<div class="error"><strong>Erreur :</strong> Division par zéro impossible !</div>';
                        }
                        break;
                    default:
                        $erreur = true;
                        echo '<div class="error"><strong>Erreur :</strong> Opération non valide !</div>';
                }
                
                if (!$erreur) {
                    echo '<div class="result">';
                    echo '<strong>Résultat :</strong><br>';
                    echo "$nombre1 $operateur $nombre2 = <strong>$resultat</strong>";
                    echo '</div>';
                }
            } else {
                echo '<div class="error"><strong>Erreur :</strong> Veuillez remplir tous les champs correctement !</div>';
            }
        }
        ?>
    </div>
</body>
</html>
