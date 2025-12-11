<?php
// Initialisation du message d'erreur
$errorMessage = '';
$errorCode = isset($_GET['err']) ? (int)$_GET['err'] : 0;

// Logique d'affichage des messages d'erreur
if ($errorCode === 1) {
    // Erreur 1 : Login ou mot de passe vide
    $errorMessage = 'Erreur 1 : Veuillez saisir un login et un mot de passe.';
} elseif ($errorCode === 2) {
    // Erreur 2 : Login ou mot de passe incorrect
    $errorMessage = 'Erreur 2 : Erreur de login/mot de passe.';
} elseif ($errorCode === 3) {
    // Erreur 3 : Déconnexion réussie
    $errorMessage = 'Vous avez été déconnecté du service.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Basic replacement styles for Tailwind utilities used in the original */
        :root{
            --bg: #f3f4f6;
            --card-bg: #ffffff;
            --border: #d1d5db;
            --muted: #6b7280;
            --accent: #3b82f6;
            --success: #10b981;
            --danger: #f87171;
        }

        html,body{height:100%;}
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            color: #111827;
        }

        .card{
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 20px rgba(17,24,39,0.08);
            width: 100%;
            max-width: 420px;
            border: 1px solid var(--border);
        }

        .title{
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            text-align: center;
            color: #374151;
        }

        .alert{padding:0.75rem;border-radius:0.375rem;margin-bottom:1rem;font-size:0.95rem}
        .alert.error{background:#fff1f2;border:1px solid #fecaca;color:#991b1b}

        form.login-form{display:block}

        .form-row{display:flex;align-items:center;margin-bottom:0.75rem}
        .login-label{display:inline-block;width:100px;text-align:right;margin-right:10px;color:var(--muted)}
        .login-input{flex:1;border:1px solid #d1d5db;padding:8px 10px;border-radius:0.375rem;font-size:0.95rem}
        .login-input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(59,130,246,0.12)}

        .form-actions{display:flex;justify-content:center;padding-top:1rem}
        .btn{padding:0.5rem 1.25rem;background:#e5e7eb;color:#111827;border-radius:0.375rem;border:none;font-weight:600;cursor:pointer;box-shadow:0 1px 2px rgba(0,0,0,0.05)}
        .btn:hover{background:#d1d5db}

        @media (max-width:480px){
            .login-label{display:block;width:100%;text-align:left;margin-bottom:0.25rem}
            .form-row{flex-direction:column;align-items:flex-start}
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="title">Connexion</h2>
        
        <?php if ($errorMessage): ?>
            <div class="alert error" role="alert">
                <p><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php endif; ?>

        <!-- Le formulaire qui envoie les données à validation.php -->
        <form action="validation.php" method="POST" class="login-form">

            <div class="form-row">
                <label for="login" class="login-label">Login:</label>
                <input type="text" id="login" name="login" class="login-input">
            </div>

            <div class="form-row">
                <label for="password" class="login-label">Mot de passe:</label>
                <input type="password" id="password" name="password" class="login-input">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Se connecter !</button>
            </div>

        </form>
    </div>
</body>
</html>