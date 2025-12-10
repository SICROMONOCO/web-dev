<?php
// Définir les questions du quiz avec leurs réponses correctes
$questions = [
    1 => [
        'question' => 'Que signifie PHP ?',
        'options' => [
            'a' => 'Personal Home Page',
            'b' => 'PHP: Hypertext Preprocessor',
            'c' => 'Private Hypertext Processor',
            'd' => 'Programming Hypertext Protocol'
        ],
        'correct' => 'b'
    ],
    2 => [
        'question' => 'Quel symbole est utilisé pour déclarer une variable en PHP ?',
        'options' => [
            'a' => '@',
            'b' => '#',
            'c' => '$',
            'd' => '&'
        ],
        'correct' => 'c'
    ],
    3 => [
        'question' => 'Quelle fonction est utilisée pour afficher du texte en PHP ?',
        'options' => [
            'a' => 'print()',
            'b' => 'echo',
            'c' => 'display()',
            'd' => 'show()'
        ],
        'correct' => 'b'
    ],
    4 => [
        'question' => 'Comment commence un bloc de code PHP ?',
        'options' => [
            'a' => '<php>',
            'b' => '<?php',
            'c' => '<script>',
            'd' => '<!php>'
        ],
        'correct' => 'b'
    ],
    5 => [
        'question' => 'Quelle méthode HTTP est utilisée pour envoyer des données de formulaire de manière sécurisée ?',
        'options' => [
            'a' => 'GET',
            'b' => 'POST',
            'c' => 'PUT',
            'd' => 'DELETE'
        ],
        'correct' => 'b'
    ]
];

// Stocker les questions dans la session pour vérification
session_start();
$_SESSION['questions'] = $questions;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice 6 - Mini Quiz PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
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
            font-style: italic;
        }
        .quiz-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            text-align: center;
            color: #1565c0;
        }
        .question-block {
            background-color: #f5f5f5;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .question-number {
            color: #667eea;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .question-text {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        .option {
            margin: 10px 0;
            padding: 12px;
            background-color: white;
            border-radius: 5px;
            border: 2px solid #ddd;
            transition: all 0.3s;
        }
        .option:hover {
            border-color: #667eea;
            background-color: #f8f9ff;
        }
        .option input[type="radio"] {
            margin-right: 10px;
            cursor: pointer;
        }
        .option label {
            cursor: pointer;
            display: block;
            width: 100%;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        .required-note {
            color: #f44336;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Mini Quiz PHP</h1>
        <p class="subtitle">Testez vos connaissances en PHP</p>
        
        <div class="quiz-info">
            📝 Ce quiz contient <?php echo count($questions); ?> questions<br>
            ✓ Choisissez la meilleure réponse pour chaque question
        </div>
        
        <form method="POST" action="result.php">
            <?php foreach ($questions as $num => $q): ?>
                <div class="question-block">
                    <div class="question-number">Question <?php echo $num; ?>/<?php echo count($questions); ?></div>
                    <div class="question-text"><?php echo htmlspecialchars($q['question']); ?></div>
                    
                    <?php foreach ($q['options'] as $key => $option): ?>
                        <div class="option">
                            <label>
                                <input type="radio" name="q<?php echo $num; ?>" value="<?php echo $key; ?>" required>
                                <strong><?php echo strtoupper($key); ?>)</strong> <?php echo htmlspecialchars($option); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            
            <button type="submit">📊 Soumettre mes réponses</button>
            <p class="required-note">* Toutes les questions sont obligatoires</p>
        </form>
    </div>
</body>
</html>
