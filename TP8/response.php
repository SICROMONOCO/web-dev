<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/png" href="ico.png"/> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Formulaire PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="reponse-body">

<?php
// Check if the form was submitted using the POST method
if($_POST) {
    
    // --- MANDATORY SECURITY STEP: SECURE ALL VARIABLES WITH strip_tags() ---
    // This prevents Cross-Site Scripting (XSS) attacks by removing any HTML/PHP tags
    $nom = isset($_POST['nom']) ? strip_tags($_POST['nom']) : '';
    $titre = isset($_POST['titre']) ? strip_tags($_POST['titre']) : '';
    $prenom = isset($_POST['prenom']) ? strip_tags($_POST['prenom']) : '';
    $identifiant = isset($_POST['identifiant']) ? strip_tags($_POST['identifiant']) : '';
    // Note: Passwords are secured, but should ideally be hashed before storage/display
    $mdp = isset($_POST['mdp']) ? strip_tags($_POST['mdp']) : '';
    $sexe = isset($_POST['sexe']) ? strip_tags($_POST['sexe']) : '';
    $annee = isset($_POST['annee']) ? strip_tags($_POST['annee']) : '';
    // Checkbox is usually checked/unchecked, value is "oui" if checked
    $debutant_checked = isset($_POST['debutant']); 

    // Start implementing the logic from image_28.png
    // Si la variable nom n'est pas vide
    if($nom != '') {
        
        // On fait afficher les informations transmise au serveur
        echo "<h1 class='welcome-title'>Bonjour " . $titre . " " . $prenom . ", " . $nom . " !</h1>";
        echo "<h2 class='section-title'>Vos informations de compte :</h2>";
        
        // Afficher l'identifiant
        echo "<p class='mb-2'>Votre identifiant est : <strong>" . $identifiant . "</strong></p>";
        
        // Afficher le mot de passe (Note: We show the secured value, but in a real app, never echo the password)
        // Since we are required to show it in the output, we use the secured variable.
        echo "<p>Votre mot de passe est : <em>" . $mdp . "</em></p>";
        
        // Si on a coché H ou F la variable $mot se différencie
        $mot = "";
        if($sexe == 'H') {
            $mot = "débutant";
        } else {
            // This covers 'F' and any other value (e.g., if we added 'Autre')
            $mot = "débutante";
        }

        // Si on a coché la case debutant, on affiche une phrase qui change selon la valeur de $mot
        if($debutant_checked) {
            echo "<h2 class='success-message'>Comme vous êtes " . $mot . ", C'est une bonne idée de commencer à apprendre à programmer en PHP !</h2>";
        }
        
        // Si la variable annee n'est pas vide on affiche la page wikipédia de l'année
        if($annee != '') {
            echo "<h2 class='info-title'>Voici les faits importants de votre année de naissance : " . $annee . "</h2>";
            
            // On stocke l'url de la page dans une variable
            // Ensure the URL is properly encoded for the iframe to work.
            $url = "https://fr.wikipedia.org/wiki/" . urlencode($annee); 
            
            // On affiche la page wikipédia dans notre réponse
            echo "<iframe src='" . $url . "' width='100%' height='800px' class='reponse-iframe'></iframe>";
        }

    } else {
        echo "<h1 class='error-title'>Erreur de Soumission</h1>";
        echo "<p class='error-message'>Veuillez retourner au formulaire et renseigner au moins le champ 'Nom'.</p>";
    }
} else {
    echo "<h1 class='error-title'>Accès non autorisé</h1>";
    echo "<p class='error-message'>Ce script doit être appelé par la soumission d'un formulaire POST.</p>";
}
?>

</body>
</html>