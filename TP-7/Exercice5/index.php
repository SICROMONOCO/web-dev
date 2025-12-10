<?php
// Chemin du fichier de messages
$fichier_messages = 'messages.txt';

// Traiter l'ajout d'un nouveau message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $message = trim($_POST['message']);
    
    if (!empty($nom) && !empty($message)) {
        $date = date('d/m/Y H:i:s');
        $ligne = $nom . '|' . $message . '|' . $date . PHP_EOL;
        
        // Ajouter le message au fichier
        file_put_contents($fichier_messages, $ligne, FILE_APPEND);
        
        $success = "Votre message a été ajouté au livre d'or !";
    } else {
        $erreur = "Veuillez remplir tous les champs !";
    }
}

// Lire tous les messages
$messages = [];
if (file_exists($fichier_messages)) {
    $lignes = file($fichier_messages, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) {
        $parties = explode('|', $ligne);
        if (count($parties) == 3) {
            $messages[] = [
                'nom' => $parties[0],
                'message' => $parties[1],
                'date' => $parties[2]
            ];
        }
    }
}

// Inverser pour afficher les plus récents en premier
$messages = array_reverse($messages);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice 5 - Livre d'or</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
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
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #9C27B0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #7B1FA2;
        }
        .success {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 5px;
            color: #2e7d32;
        }
        .error {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            border-radius: 5px;
            color: #c62828;
        }
        .messages-section {
            margin-top: 40px;
        }
        .messages-section h2 {
            color: #333;
            border-bottom: 2px solid #9C27B0;
            padding-bottom: 10px;
        }
        .message-card {
            background-color: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-left: 4px solid #9C27B0;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .message-author {
            font-weight: bold;
            color: #9C27B0;
            font-size: 16px;
        }
        .message-date {
            color: #999;
            font-size: 12px;
        }
        .message-content {
            color: #555;
            line-height: 1.6;
        }
        .no-messages {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
        .message-count {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📖 Livre d'or</h1>
        <p class="subtitle">Laissez votre message et partagez vos impressions</p>
        
        <?php if (isset($success)): ?>
            <div class="success">✓ <?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (isset($erreur)): ?>
            <div class="error">✗ <?php echo htmlspecialchars($erreur); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Votre nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="message">Votre message :</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            
            <button type="submit" name="ajouter">Ajouter mon message</button>
        </form>
    </div>
    
    <div class="messages-section">
        <h2>Messages (<?php echo count($messages); ?>)</h2>
        
        <?php if (empty($messages)): ?>
            <div class="no-messages">
                Aucun message pour le moment. Soyez le premier à laisser un message !
            </div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message-card">
                    <div class="message-header">
                        <span class="message-author">👤 <?php echo htmlspecialchars($msg['nom']); ?></span>
                        <span class="message-date">🕒 <?php echo htmlspecialchars($msg['date']); ?></span>
                    </div>
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
