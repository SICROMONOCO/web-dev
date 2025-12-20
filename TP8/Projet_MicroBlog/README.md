# MicroBlog - Prototype MVC

Un prototype de micro-blogging sécurisé et autonome créé avec PHP vanilla, HTML, CSS et JavaScript.

## 📋 Description

Ce projet est un prototype de démonstration rapide qui implémente un système de micro-blogging minimaliste avec une architecture MVC. Il n'utilise aucune bibliothèque externe (pas de Bootstrap, Tailwind ou jQuery).

## 🚀 Caractéristiques

- **Design Minimaliste**: Interface inspirée de Perplexity avec une esthétique épurée
- **Architecture MVC**: Séparation claire entre la vue (index.html) et le contrôleur/modèle (api.php)
- **Sécurité**: Protection contre les attaques XSS avec `htmlspecialchars`
- **Stockage JSON**: Utilise un fichier `posts.json` pour persister les données
- **Communication Asynchrone**: Utilise l'API Fetch pour communiquer avec le backend
- **Temps réel**: Mise à jour dynamique du DOM sans rechargement de page

## 📁 Structure des Fichiers

```
Projet_MicroBlog/
├── index.html     # Vue et logique client (HTML, CSS, JavaScript)
├── api.php        # Contrôleur et modèle (API REST)
├── posts.json     # Stockage des données (généré automatiquement)
└── README.md      # Documentation
```

## 🛠️ Installation et Utilisation

### Prérequis
- PHP 7.4 ou supérieur
- Un serveur web (Apache, Nginx) ou le serveur de développement PHP

### Méthode 1: Serveur de développement PHP
```bash
# Naviguer dans le répertoire du projet
cd TP8/Projet_MicroBlog

# Démarrer le serveur de développement PHP
php -S localhost:8080

# Ouvrir dans le navigateur
# http://localhost:8080/index.html
```

### Méthode 2: Apache/Nginx
1. Placer le dossier dans votre répertoire web (ex: `/var/www/html/`)
2. Configurer votre serveur pour pointer vers le répertoire
3. Accéder via votre navigateur

## 🔐 Sécurité

- **Protection XSS**: Tous les contenus utilisateur sont nettoyés avec `htmlspecialchars()`
- **Validation**: Les entrées sont validées côté serveur et côté client
- **En-têtes CORS**: Configuration pour accepter les requêtes cross-origin

## 📡 Endpoints API

### GET `/api.php`
Récupère tous les posts, triés du plus récent au plus ancien.

**Réponse:**
```json
{
  "success": true,
  "message": "Posts récupérés avec succès",
  "posts": [
    {
      "id": "post_xxx",
      "contenu": "Contenu du post",
      "horodatage": "2025-12-20T19:43:06+00:00"
    }
  ],
  "total": 1
}
```

### POST `/api.php`
Crée un nouveau post.

**Corps de la requête:**
```json
{
  "contenu": "Votre message ici"
}
```

**Réponse:**
```json
{
  "success": true,
  "message": "Post créé avec succès",
  "post": {
    "id": "post_xxx",
    "contenu": "Votre message ici",
    "horodatage": "2025-12-20T19:43:06+00:00"
  }
}
```

## 🎨 Fonctionnalités de l'Interface

- **Zone de création**: Textarea pour composer un nouveau post (max 500 caractères)
- **Fil d'actualité**: Liste des posts affichés du plus récent au plus ancien
- **Horodatage relatif**: Affichage du temps écoulé ("Il y a X minutes/heures")
- **Gestion d'erreurs**: Messages d'erreur conviviaux en cas de problème
- **Messages de statut**: Indicateurs de chargement et messages d'état vide

## 📝 Conventions de Code

- **Langue**: Tous les commentaires et noms de variables sont en français
- **Nomenclature**: camelCase pour JavaScript, snake_case pour PHP
- **Documentation**: Commentaires détaillés expliquant le flux de données

## 🧪 Tests Manuels

Pour tester l'application:

1. **Test de création**: Créer un nouveau post via le formulaire
2. **Test XSS**: Essayer d'injecter `<script>alert('XSS')</script>` (doit être échappé)
3. **Test de validation**: Soumettre un post vide (doit être rejeté)
4. **Test de chargement**: Recharger la page et vérifier que les posts persistent
5. **Test de tri**: Créer plusieurs posts et vérifier qu'ils sont triés correctement

## 📄 Licence

Ce projet est un prototype éducatif pour le module Web Dev.

## 👨‍💻 Auteur

Créé dans le cadre du TP8 - Module Web Development
