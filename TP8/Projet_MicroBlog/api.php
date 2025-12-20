<?php
/**
 * API MicroBlog - Contrôleur et Modèle
 * 
 * Ce fichier gère toutes les opérations du backend pour le micro-blogging.
 * Architecture MVC : Contrôleur + Modèle combinés
 */

// Configuration des en-têtes HTTP pour l'API JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Gestion des requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Classe MicroBlogController
 * Gère la logique métier et le stockage des posts
 */
class MicroBlogController {
    
    /**
     * Nom du fichier de stockage JSON
     * @var string
     */
    private $fichierDonnees = 'posts.json';
    
    /**
     * Constructeur de la classe
     * Initialise le fichier de données si nécessaire
     */
    public function __construct() {
        $this->initialiserFichierDonnees();
    }
    
    /**
     * Initialise le fichier posts.json s'il n'existe pas
     * Crée un tableau vide pour stocker les posts
     */
    private function initialiserFichierDonnees() {
        // Vérifier si le fichier existe
        if (!file_exists($this->fichierDonnees)) {
            // Créer un nouveau fichier avec un tableau vide
            $donneesInitiales = json_encode([], JSON_PRETTY_PRINT);
            $resultat = file_put_contents($this->fichierDonnees, $donneesInitiales);
            
            // Vérifier que l'écriture a réussi
            if ($resultat === false) {
                throw new Exception('Impossible de créer le fichier de données. Vérifiez les permissions.');
            }
        }
    }
    
    /**
     * Charge tous les posts depuis le fichier JSON
     * 
     * Flux de données:
     * 1. Lit le fichier posts.json
     * 2. Décode le JSON en tableau PHP
     * 3. Retourne le tableau de posts
     * 
     * @return array Tableau de posts
     */
    private function chargerPosts() {
        // Vérifier que le fichier existe
        if (!file_exists($this->fichierDonnees)) {
            return [];
        }
        
        // Lire le contenu du fichier
        $contenuFichier = file_get_contents($this->fichierDonnees);
        
        // Vérifier que la lecture a réussi
        if ($contenuFichier === false) {
            throw new Exception('Impossible de lire le fichier de données.');
        }
        
        // Décoder le JSON en tableau associatif
        $posts = json_decode($contenuFichier, true);
        
        // Vérifier que le décodage a réussi
        if ($posts === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erreur de décodage JSON: ' . json_last_error_msg());
        }
        
        // Retourner un tableau vide si le décodage échoue
        return is_array($posts) ? $posts : [];
    }
    
    /**
     * Sauvegarde les posts dans le fichier JSON
     * 
     * Flux de données:
     * 1. Reçoit un tableau de posts
     * 2. Encode le tableau en JSON
     * 3. Écrit le JSON dans le fichier
     * 
     * @param array $posts Tableau de posts à sauvegarder
     * @return bool Succès de l'opération
     */
    private function sauvegarderPosts($posts) {
        // Encoder les posts en JSON avec formatage
        $donneesJson = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Vérifier que l'encodage a réussi
        if ($donneesJson === false) {
            throw new Exception('Erreur d\'encodage JSON: ' . json_last_error_msg());
        }
        
        // Écrire dans le fichier
        $resultat = file_put_contents($this->fichierDonnees, $donneesJson);
        
        // Vérifier que l'écriture a réussi
        if ($resultat === false) {
            throw new Exception('Impossible d\'écrire dans le fichier de données. Vérifiez les permissions ou l\'espace disque.');
        }
        
        return true;
    }
    
    /**
     * Gère les requêtes GET - Récupération des posts
     * 
     * Flux de données:
     * 1. Charge tous les posts depuis le fichier
     * 2. Trie les posts par date (plus récents en premier)
     * 3. Retourne la réponse JSON
     */
    public function gererRequeteGET() {
        // Charger tous les posts
        $posts = $this->chargerPosts();
        
        // Trier les posts par horodatage décroissant (plus récents en premier)
        usort($posts, function($a, $b) {
            return strtotime($b['horodatage']) - strtotime($a['horodatage']);
        });
        
        // Retourner la réponse JSON
        $this->envoyerReponse(true, 'Posts récupérés avec succès', [
            'posts' => $posts,
            'total' => count($posts)
        ]);
    }
    
    /**
     * Gère les requêtes POST - Création d'un nouveau post
     * 
     * Flux de données:
     * 1. Reçoit les données JSON de la requête
     * 2. Valide et nettoie le contenu (protection XSS)
     * 3. Crée un objet post avec horodatage et ID unique
     * 4. Ajoute le post au fichier JSON
     * 5. Retourne le post créé
     */
    public function gererRequetePOST() {
        // Lire les données JSON envoyées dans le corps de la requête
        $donneesEntree = json_decode(file_get_contents('php://input'), true);
        
        // Vérifier que les données sont valides
        if (!$donneesEntree || !isset($donneesEntree['contenu'])) {
            $this->envoyerReponse(false, 'Données invalides. Le champ "contenu" est requis.', null, 400);
            return;
        }
        
        // Récupérer et nettoyer le contenu (PROTECTION XSS)
        $contenu = trim($donneesEntree['contenu']);
        
        // Valider que le contenu n'est pas vide
        if (empty($contenu)) {
            $this->envoyerReponse(false, 'Le contenu ne peut pas être vide.', null, 400);
            return;
        }
        
        // Valider la longueur du contenu
        if (strlen($contenu) > 500) {
            $this->envoyerReponse(false, 'Le contenu ne peut pas dépasser 500 caractères.', null, 400);
            return;
        }
        
        // SÉCURITÉ: Nettoyer le contenu pour prévenir les attaques XSS
        $contenuNettoye = htmlspecialchars($contenu, ENT_QUOTES, 'UTF-8');
        
        // Créer un nouveau post avec un ID unique et un horodatage
        // Utilisation de random_bytes pour une génération d'ID plus sécurisée
        $nouveauPost = [
            'id' => 'post_' . bin2hex(random_bytes(16)),
            'contenu' => $contenuNettoye,
            'horodatage' => date('c') // Format ISO 8601
        ];
        
        // Charger les posts existants
        $posts = $this->chargerPosts();
        
        // Ajouter le nouveau post au début du tableau
        array_unshift($posts, $nouveauPost);
        
        // Sauvegarder dans le fichier
        if ($this->sauvegarderPosts($posts)) {
            // Retourner le post créé
            $this->envoyerReponse(true, 'Post créé avec succès', ['post' => $nouveauPost], 201);
        } else {
            // Erreur lors de la sauvegarde
            $this->envoyerReponse(false, 'Erreur lors de la sauvegarde du post', null, 500);
        }
    }
    
    /**
     * Envoie une réponse JSON formatée
     * 
     * @param bool $success Indique si l'opération a réussi
     * @param string $message Message descriptif
     * @param array|null $donnees Données supplémentaires à retourner
     * @param int $codeHTTP Code de statut HTTP
     */
    private function envoyerReponse($success, $message, $donnees = null, $codeHTTP = 200) {
        // Définir le code de statut HTTP
        http_response_code($codeHTTP);
        
        // Préparer la réponse
        $reponse = [
            'success' => $success,
            'message' => $message
        ];
        
        // Ajouter les données si présentes
        if ($donnees !== null) {
            $reponse = array_merge($reponse, $donnees);
        }
        
        // Encoder et envoyer la réponse JSON
        echo json_encode($reponse, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    /**
     * Route les requêtes vers les bonnes méthodes
     * Point d'entrée principal du contrôleur
     */
    public function traiterRequete() {
        $methode = $_SERVER['REQUEST_METHOD'];
        
        switch ($methode) {
            case 'GET':
                $this->gererRequeteGET();
                break;
            
            case 'POST':
                $this->gererRequetePOST();
                break;
            
            default:
                $this->envoyerReponse(false, 'Méthode HTTP non supportée', null, 405);
                break;
        }
    }
}

// Point d'entrée de l'API
// Instancier le contrôleur et traiter la requête
try {
    $controleur = new MicroBlogController();
    $controleur->traiterRequete();
} catch (Exception $e) {
    // Gestion globale des erreurs
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur serveur interne',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
