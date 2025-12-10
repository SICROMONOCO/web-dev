<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="icon" type="image/png" href="ico.png"/> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Formulaire PHP (Style Embarqué)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="reponse-body">

<?php
// Check if the form was submitted using the POST method
if(isset($_POST) && !empty($_POST)) {
    
    // Check if the mandatory 'nom' field is provided and not empty
    if(isset($_POST['nom']) && trim($_POST['nom']) != '') {
?>
        <!-- 1. Displaying Greeting and Basic Info -->
        <h1 class="welcome-title">
            Bonjour, 
            <!-- Output Title, First Name, and Last Name directly from POST, secured with strip_tags() -->
            <?php echo strip_tags($_POST['titre'] ?? ''); ?> 
            <?php echo strip_tags($_POST['prenom'] ?? ''); ?>, 
            <?php echo strip_tags($_POST['nom'] ?? ''); ?>
            !
        </h1>
        
        <h2 class="section-title">Vos informations de compte (style embarqué) :</h2>
        
        <p class='mb-2'>
            Votre identifiant est : 
            <strong><?php echo strip_tags($_POST['identifiant'] ?? ''); ?></strong>
        </p>
        
        <p>
            Votre mot de passe est : 
            <em><?php echo strip_tags($_POST['mdp'] ?? ''); ?></em>
        </p>
        
<?php
        // 2. Conditional Message Logic (Débutant)
        // Determine the gendered adjective ('débutant' or 'débutante')
        $mot = (isset($_POST['sexe']) && $_POST['sexe'] == 'F') ? "débutante" : "débutant";
        
        // If the debutant checkbox was checked
        if(isset($_POST['debutant']) && $_POST['debutant'] == 'oui') {
?>
            <h2 class='success-message'>
                Comme vous êtes <?php echo $mot; ?>, C'est une bonne idée de commencer à apprendre à programmer en PHP !
            </h2>
<?php
        }
        
        // 3. Conditional Iframe Logic (Année)
        // Check if the birth year was provided
        if(isset($_POST['annee']) && trim($_POST['annee']) != '') {
            // Secure the year value before display and use in URL
            $annee_securisee = strip_tags($_POST['annee']);
            $url = "https://fr.wikipedia.org/wiki/" . urlencode($annee_securisee); 
?>
            <h2 class='info-title'>
                Voici les faits importants de votre année de naissance : <?php echo $annee_securisee; ?>
            </h2>
            
            <iframe src='<?php echo $url; ?>' 
                    width='100%' 
                    height='800px' 
                    class='reponse-iframe'>
            </iframe>
<?php
        }
    } else {
        // Error case: Nom is empty
?>
        <h1 class='error-title'>Erreur de Soumission</h1>
        <p class='error-message'>Veuillez retourner au formulaire et renseigner au moins le champ 'Nom'.</p>
<?php
    }
} else {
    // Error case: Not a POST submission or no data
?>
    <h1 class='error-title'>Accès non autorisé</h1>
    <p class='error-message'>Ce script doit être appelé par la soumission d'un formulaire POST.</p>
<?php
}
?>

</body>
</html>