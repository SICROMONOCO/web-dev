# TP8 - Projet MicroBlog - Rapport de Projet

## 📋 Informations du Projet

- **Étudiant:** bilal siki
- **Module:** Web Development
- **Travail Pratique:** TP8 - Prototype MVC Micro-Blogging
- **Dépôt GitHub:** [SICROMONOCO/web-dev](https://github.com/SICROMONOCO/web-dev)
- **Dossier:** TP8/Projet_MicroBlog/
- **Date:** 20 Décembre 2025

---

## 🎯 Objectif du Projet

Créer un **prototype de micro-blogging sécurisé et autonome** utilisant une architecture MVC avec PHP vanilla, HTML, CSS et JavaScript. Le projet doit être entièrement auto-suffisant sans aucune dépendance externe (pas de Bootstrap, Tailwind ou jQuery).

---

## 💻 Technologies Utilisées

- **PHP 7.4+** - Backend et API REST
- **HTML5** - Structure de la page
- **CSS3** - Styles embarqués, design minimaliste
- **JavaScript ES6+** - Logique client, Fetch API
- **JSON** - Stockage plat des données
- **Fetch API** - Communication asynchrone

---

## 🏗️ Architecture

### 📄 index.html (14 KB)
**Vue + Logique Client**

- Design minimaliste inspiré de Perplexity
- Layout centré (max-width: 800px)
- CSS embarqué avec thème clair
- JavaScript avec Fetch API
- Fonctions principales:
  - `chargerPosts()` - Charge les posts au démarrage
  - `soumettrePost()` - Soumet un nouveau post
  - `afficherPost()` - Affiche un post dans le DOM
  - `formaterDate()` - Formate les horodatages
- Nommage complet en français
- Gestion d'erreurs avec try/catch

### ⚙️ api.php (9 KB)
**Contrôleur + Modèle**

- Classe `MicroBlogController` pour la logique métier
- Endpoints RESTful:
  - **GET /api.php** - Récupère tous les posts
  - **POST /api.php** - Crée un nouveau post
- Stockage JSON plat (posts.json)
- Commentaires détaillés en français
- Méthodes principales:
  - `gererRequeteGET()` - Gestion des requêtes GET
  - `gererRequetePOST()` - Gestion des requêtes POST
  - `chargerPosts()` - Lecture du fichier JSON
  - `sauvegarderPosts()` - Écriture dans le fichier JSON

### 💾 posts.json
**Stockage de Données**

- Fichier JSON auto-créé au premier usage
- Structure simple:
```json
[
  {
    "id": "post_e506c1ace8ff59820b97c5291fea7bb0",
    "contenu": "Contenu du post",
    "horodatage": "2025-12-20T19:57:30+00:00"
  }
]
```

### 📚 README.md (4 KB)
**Documentation Complète**

- Instructions d'installation
- Documentation API avec exemples
- Guide de sécurité
- Liste des fonctionnalités
- Instructions de test

### 🚫 .gitignore
**Exclusions**

- Exclut `posts.json` du contrôle de version (fichier généré)

---

## 🔐 Fonctionnalités de Sécurité

### 1. Protection XSS
- Tous les contenus utilisateur nettoyés avec `htmlspecialchars(ENT_QUOTES, 'UTF-8')`
- Empêche l'injection de scripts malveillants
- Test effectué avec `<script>alert('XSS')</script>` ✅

### 2. Génération d'ID Sécurisée
- Utilisation de `bin2hex(random_bytes(16))` au lieu de `uniqid()`
- IDs cryptographiquement sécurisés de 32 caractères hexadécimaux
- Évite les attaques par prédiction d'ID

### 3. Validation des Entrées
- Vérification côté serveur et côté client
- Détection des champs vides
- Limite de longueur: 500 caractères maximum
- Messages d'erreur conviviaux

### 4. Gestion Complète des Erreurs
- Gestion des erreurs de lecture/écriture de fichiers
- Vérification des opérations d'encodage/décodage JSON
- Messages d'erreur détaillés pour le débogage
- Exceptions lancées en cas de problème critique

### 5. En-têtes CORS
- Configuration appropriée pour les requêtes cross-origin
- En-têtes Content-Type correctement définis
- Support des requêtes OPTIONS (preflight)

---

## 📡 API Endpoints

### GET /api.php
**Récupère tous les posts**

**Réponse:**
```json
{
  "success": true,
  "message": "Posts récupérés avec succès",
  "posts": [
    {
      "id": "post_...",
      "contenu": "Contenu du post",
      "horodatage": "2025-12-20T19:57:30+00:00"
    }
  ],
  "total": 1
}
```

### POST /api.php
**Crée un nouveau post**

**Requête:**
```json
{
  "contenu": "Votre message ici"
}
```

**Réponse (succès):**
```json
{
  "success": true,
  "message": "Post créé avec succès",
  "post": {
    "id": "post_e506c1ace8ff59820b97c5291fea7bb0",
    "contenu": "Votre message ici",
    "horodatage": "2025-12-20T19:57:30+00:00"
  }
}
```

**Réponse (erreur):**
```json
{
  "success": false,
  "message": "Le contenu ne peut pas être vide."
}
```

---

## 🎨 Fonctionnalités de l'Interface

### Zone de Création
- **Textarea** pour composer un nouveau post
- Placeholder: "Qu'avez-vous en tête ?"
- Limite de 500 caractères (validation HTML5)
- **Bouton "Publier"** avec état disabled pendant la soumission
- Changement de texte: "Publication..." pendant le traitement
- Nettoyage automatique du formulaire après soumission réussie

### Fil d'Actualité
- Affichage des posts du plus récent au plus ancien
- Cartes de post avec:
  - Contenu du post (texte avec word-wrap)
  - Horodatage relatif:
    - "À l'instant" (< 1 minute)
    - "Il y a X minute(s)" (< 1 heure)
    - "Il y a X heure(s)" (< 24 heures)
    - Date complète (> 24 heures)
- Hover effect sur les cartes (ombre plus prononcée)

### Gestion d'Erreurs
- **Messages d'erreur** en rouge avec bordure
- Affichage automatique pendant 5 secondes
- **Indicateur de chargement** pendant le chargement initial
- **Message d'état vide**: "Aucun post pour le moment. Soyez le premier à publier !"

### Design
- **Palette de couleurs**:
  - Fond: Blanc (#ffffff)
  - Texte: Gris foncé (#1a1a1a)
  - Accent: Bleu (#2563eb)
  - Bordures: Gris clair (#e5e5e5)
- **Typographie**: System-ui, Inter, Segoe UI (sans-serif)
- **Cartes** avec bordures arrondies (12px) et ombres subtiles
- **Responsive** avec max-width de 800px

---

## 📸 Captures d'Écran

### Interface Principale

![MicroBlog - Interface Principale](https://github.com/user-attachments/assets/e4ba4d5c-6601-4cfc-a791-1df464e17dac)

*Interface principale avec le formulaire de création et le fil d'actualité affichant plusieurs posts. On peut voir:*
- *Le formulaire de création en haut avec la zone de texte*
- *Le bouton "Publier" en bleu*
- *Le fil d'actualité avec 4 posts*
- *Les horodatages relatifs "À l'instant"*
- *Le design minimaliste et épuré*

---

## 📝 Conventions de Code

### Nommage
- **Langue:** Tous les commentaires et noms de variables/fonctions en français
- **JavaScript:** camelCase
  - `chargerPosts`, `soumettrePost`, `afficherPost`
  - `messageErreur`, `boutonSoumettre`, `champContenu`
- **PHP:** camelCase pour les méthodes, snake_case pourrait être utilisé pour les propriétés
  - `gererRequeteGET()`, `sauvegarderPosts()`
  - `$fichierDonnees`, `$contenuNettoye`
- **CSS:** Classes descriptives en français avec tirets
  - `.zone-creation`, `.carte-post`, `.bouton-soumettre`

### Documentation
- **Commentaires JSDoc** pour toutes les fonctions JavaScript
- **DocBlocks PHP** pour la classe et toutes les méthodes
- **Commentaires inline** expliquant le flux de données
- **README complet** avec exemples et instructions

---

## ✅ Tests Effectués

### Tests Fonctionnels
- ✅ **Création de post** via le formulaire
- ✅ **Chargement des posts** au démarrage de la page
- ✅ **Tri des posts** (plus récent en premier)
- ✅ **Mise à jour dynamique du DOM** sans rechargement
- ✅ **Nettoyage du formulaire** après soumission
- ✅ **Affichage des horodatages relatifs**
- ✅ **Persistence des données** après rechargement

### Tests de Sécurité
- ✅ **Protection XSS**: Balises `<script>` correctement échappées
- ✅ **Validation des champs vides**: Rejet des posts vides
- ✅ **Validation de longueur**: Respect de la limite de 500 caractères
- ✅ **Génération d'ID sécurisée**: IDs aléatoires avec `random_bytes()`

### Tests d'Erreur
- ✅ **Gestion des erreurs réseau**: Try/catch dans le Fetch API
- ✅ **Gestion des erreurs de fichiers**: Vérification des opérations I/O
- ✅ **Gestion des erreurs JSON**: Vérification encodage/décodage
- ✅ **Messages utilisateur**: Affichage d'erreurs conviviaux

### Tests API
- ✅ **GET /api.php**: Retourne liste vide initialement
- ✅ **POST /api.php**: Crée un nouveau post avec succès
- ✅ **GET après POST**: Retourne le post créé
- ✅ **Tri des posts**: Posts dans l'ordre décroissant

---

## 📦 Structure des Fichiers

```
Projet_MicroBlog/
├── .gitignore              # Exclut posts.json
├── README.md               # Documentation (4 KB)
├── REPORT.md               # Ce rapport
├── REPORT.html             # Version HTML du rapport
├── generateReport.js       # Script de génération de rapport HTML
├── generatePDF.js          # Script de conversion HTML vers PDF
├── index.html              # Vue et logique client (14 KB)
├── api.php                 # Contrôleur et modèle (9 KB)
└── posts.json              # Stockage (généré automatiquement, ignoré par git)
```

---

## 🚀 Installation et Utilisation

### Prérequis
- PHP 7.4 ou supérieur
- Serveur web (Apache, Nginx) ou serveur de développement PHP

### Méthode 1: Serveur de développement PHP
```bash
# Naviguer dans le répertoire du projet
cd TP8/Projet_MicroBlog

# Démarrer le serveur
php -S localhost:8080

# Ouvrir dans le navigateur
# http://localhost:8080/index.html
```

### Méthode 2: Apache/Nginx
1. Placer le dossier dans votre répertoire web
2. Configurer le serveur pour pointer vers le répertoire
3. Accéder via le navigateur

### Première Utilisation
1. Ouvrir `index.html` dans le navigateur
2. Le fichier `posts.json` sera créé automatiquement
3. Créer un premier post pour tester

---

## 🎓 Compétences Démontrées

### Architecture et Design Patterns
- ✅ **Pattern MVC** avec séparation claire des responsabilités
- ✅ **API RESTful** avec endpoints GET/POST
- ✅ **Single Page Application** sans rechargement
- ✅ **Communication asynchrone** avec Fetch API

### Sécurité
- ✅ **Protection XSS** avec sanitization
- ✅ **Validation des entrées** côté client et serveur
- ✅ **Génération sécurisée d'identifiants**
- ✅ **Gestion complète des erreurs**

### Frontend
- ✅ **Design minimaliste moderne** sans framework
- ✅ **CSS vanilla** avec layout responsive
- ✅ **JavaScript ES6+** avec async/await
- ✅ **Manipulation du DOM** optimisée

### Backend
- ✅ **PHP orienté objet** avec classe contrôleur
- ✅ **Gestion de fichiers** JSON
- ✅ **API REST** avec réponses standardisées
- ✅ **Gestion d'erreurs** robuste

### Documentation et Bonnes Pratiques
- ✅ **Code commenté** en français
- ✅ **Nommage cohérent** et descriptif
- ✅ **Documentation complète** (README)
- ✅ **Versioning** avec .gitignore approprié

---

## 📊 Statistiques du Projet

| Métrique | Valeur |
|----------|--------|
| **Fichiers créés** | 6 fichiers |
| **Lignes de code PHP** | ~230 lignes |
| **Lignes de code HTML/CSS/JS** | ~370 lignes |
| **Taille totale** | ~30 KB |
| **Fonctions JavaScript** | 6 fonctions |
| **Méthodes PHP** | 6 méthodes |
| **Endpoints API** | 2 endpoints |
| **Durée de développement** | ~2 heures |
| **Tests effectués** | 20+ tests |

---

## 🏆 Points Forts du Projet

1. **✨ Code Quality**
   - Code propre et bien structuré
   - Commentaires détaillés en français
   - Séparation claire des responsabilités

2. **🔒 Sécurité**
   - Protection XSS complète
   - Validation robuste des entrées
   - IDs sécurisés cryptographiquement

3. **🎨 Design**
   - Interface moderne et intuitive
   - Design responsive
   - Expérience utilisateur fluide

4. **⚡ Performance**
   - Pas de dépendances externes
   - Chargement rapide
   - Mises à jour DOM optimisées

5. **📚 Documentation**
   - README complet
   - Code auto-documenté
   - Exemples d'utilisation

---

## 🔮 Améliorations Possibles

### Fonctionnalités
- [ ] Édition des posts existants
- [ ] Suppression de posts
- [ ] Recherche et filtrage
- [ ] Pagination pour les listes longues
- [ ] Support des images et liens
- [ ] Système de likes/reactions

### Technique
- [ ] Base de données SQL au lieu de JSON
- [ ] Authentification utilisateur
- [ ] API versionnée
- [ ] Tests unitaires automatisés
- [ ] CI/CD pipeline
- [ ] Docker containerization

### Design
- [ ] Mode sombre/clair
- [ ] Animations CSS
- [ ] Progressive Web App (PWA)
- [ ] Support mobile amélioré

---

## 📝 Conclusion

Ce projet démontre une compréhension solide des principes de développement web full-stack, incluant:

- **Architecture MVC** bien implémentée
- **Sécurité web** avec protection XSS et validation
- **Design moderne** sans dépendances externes
- **Code propre** avec documentation en français
- **API RESTful** fonctionnelle

Le prototype est **entièrement fonctionnel, sécurisé, et prêt pour la production** dans un environnement de démonstration. Tous les objectifs du TP8 ont été atteints avec succès.

### Points Clés
✅ 2 fichiers principaux (index.html + api.php)  
✅ Architecture MVC respectée  
✅ Sécurité XSS implémentée  
✅ Stockage JSON fonctionnel  
✅ Design Perplexity-inspired  
✅ Code en français  
✅ Tests complets effectués  

---

**Rapport généré automatiquement**  
*TP8 - Projet MicroBlog | Web Development | 20 Décembre 2025*  
*Étudiant: bilal siki | Dépôt: SICROMONOCO/web-dev*
