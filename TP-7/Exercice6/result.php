<?php
session_start();

// Vérifier si les questions sont en session
if (!isset($_SESSION['questions'])) {
    header('Location: index.php');
    exit();
}

$questions = $_SESSION['questions'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Calculer le score
$score = 0;
$total_questions = count($questions);
$resultats = [];

foreach ($questions as $num => $q) {
    $user_answer = isset($_POST["q$num"]) ? $_POST["q$num"] : '';
    $is_correct = ($user_answer === $q['correct']);
    
    if ($is_correct) {
        $score++;
    }
    
    $resultats[$num] = [
        'question' => $q['question'],
        'options' => $q['options'],
        'user_answer' => $user_answer,
        'correct_answer' => $q['correct'],
        'is_correct' => $is_correct
    ];
}

$pourcentage = ($score / $total_questions) * 100;

// Déterminer le message selon le score
if ($pourcentage >= 80) {
    $message = "Excellent ! 🎉";
    $color = "#4CAF50";
} elseif ($pourcentage >= 60) {
    $message = "Bien joué ! 👍";
    $color = "#2196F3";
} elseif ($pourcentage >= 40) {
    $message = "Pas mal ! 📚";
    $color = "#FF9800";
} else {
    $message = "Continuez à apprendre ! 💪";
    $color = "#f44336";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats du Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
            margin-bottom: 30px;
        }
        .score-card {
            background: linear-gradient(135deg, <?php echo $color; ?> 0%, <?php echo $color; ?>dd 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .score-message {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .score-details {
            font-size: 24px;
        }
        .score-percentage {
            font-size: 48px;
            font-weight: bold;
            margin: 15px 0;
        }
        .results-section {
            margin-top: 30px;
        }
        .result-item {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #ddd;
        }
        .result-item.correct {
            border-left-color: #4CAF50;
            background-color: #e8f5e9;
        }
        .result-item.incorrect {
            border-left-color: #f44336;
            background-color: #ffebee;
        }
        .question-header {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-badge.correct {
            background-color: #4CAF50;
            color: white;
        }
        .status-badge.incorrect {
            background-color: #f44336;
            color: white;
        }
        .answer-item {
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            background-color: white;
        }
        .answer-item.user-answer {
            border: 2px solid #2196F3;
            background-color: #e3f2fd;
        }
        .answer-item.correct-answer {
            border: 2px solid #4CAF50;
            background-color: #e8f5e9;
        }
        .answer-item.wrong-answer {
            border: 2px solid #f44336;
            background-color: #ffebee;
        }
        .answer-label {
            font-weight: bold;
            margin-right: 5px;
        }
        .retry-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px;
            background-color: #667eea;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        .retry-btn:hover {
            background-color: #5568d3;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            text-align: center;
        }
        .summary-item {
            flex: 1;
        }
        .summary-value {
            font-size: 32px;
            font-weight: bold;
        }
        .summary-label {
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Résultats du Quiz</h1>
        
        <div class="score-card">
            <div class="score-message"><?php echo $message; ?></div>
            <div class="score-percentage"><?php echo round($pourcentage); ?>%</div>
            <div class="score-details">
                Score : <?php echo $score; ?>/<?php echo $total_questions; ?>
            </div>
            
            <div class="summary">
                <div class="summary-item">
                    <div class="summary-value" style="color: #4CAF50;">✓ <?php echo $score; ?></div>
                    <div class="summary-label">Correctes</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value" style="color: #f44336;">✗ <?php echo $total_questions - $score; ?></div>
                    <div class="summary-label">Incorrectes</div>
                </div>
            </div>
        </div>
        
        <div class="results-section">
            <h2 style="color: #333;">Détails des réponses</h2>
            
            <?php foreach ($resultats as $num => $result): ?>
                <div class="result-item <?php echo $result['is_correct'] ? 'correct' : 'incorrect'; ?>">
                    <div class="question-header">
                        <span>Question <?php echo $num; ?> : <?php echo htmlspecialchars($result['question']); ?></span>
                        <span class="status-badge <?php echo $result['is_correct'] ? 'correct' : 'incorrect'; ?>">
                            <?php echo $result['is_correct'] ? '✓ Correct' : '✗ Incorrect'; ?>
                        </span>
                    </div>
                    
                    <?php foreach ($result['options'] as $key => $option): ?>
                        <?php
                        $class = '';
                        $label = '';
                        
                        if ($key === $result['correct_answer'] && $key === $result['user_answer']) {
                            $class = 'correct-answer';
                            $label = '✓ Votre réponse (Correcte)';
                        } elseif ($key === $result['user_answer']) {
                            $class = 'wrong-answer';
                            $label = '✗ Votre réponse';
                        } elseif ($key === $result['correct_answer']) {
                            $class = 'correct-answer';
                            $label = '✓ Bonne réponse';
                        }
                        ?>
                        
                        <?php if (!empty($class)): ?>
                            <div class="answer-item <?php echo $class; ?>">
                                <strong><?php echo strtoupper($key); ?>)</strong> <?php echo htmlspecialchars($option); ?>
                                <?php if (!empty($label)): ?>
                                    <br><em style="font-size: 12px;"><?php echo $label; ?></em>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="index.php" class="retry-btn">🔄 Refaire le quiz</a>
    </div>
</body>
</html>
