# Gestion de Réservation de Billets

The JIRA ( Project / Modelisation diagrams / Sprints ) FOR MEMBERS 🔒:
[`https://ounessaghezzaf18.atlassian.net/jira/software/projects/SCRUM/boards/1/backlog?atlOrigin=eyJpIjoiZTFiN2ViOWI5OWU4NDM0ZDhiMjE0YmYxYjgzZTE0MmEiLCJwIjoiaiJ9`](https://khaliljazouli2.atlassian.net/jira/software/projects/GDRB/boards/2/backlog?atlOrigin=eyJpIjoiOWJhMmVjYzQwZDIzNGIwNzhmMjkyZWMzOGVmZmZjMTQiLCJwIjoiaiJ9)

Une application web full-stack pour la gestion de réservation de billets, construite avec PHP et MySQL.

### 🌟 Fonctionnalités
Fonctionnalités pour les Clients :
Inscription et authentification sécurisées.
Parcourir la liste des films avec des catégories.
Fonctionnalité de recherche.
Gestion d'un panier de réservation (sièges sélectionnés).
Réservation des billets et suivi des commandes.
Gestion du profil utilisateur.
Historique des réservations.
Gestion des sièges via un plan interactif.

### Fonctionnalités pour les Administrateurs :
Tableau de bord sécurisé pour les administrateurs.
Gestion des films (ajouter/modifier/supprimer).
Gestion des séances (horaires, salles).
Gestion des utilisateurs.
Suivi des réservations.
Gestion des salles et des sièges.
Gestion des comptes administrateurs.
Statistiques des ventes.
Système de messagerie pour les utilisateurs.

## 🛠️ Technologies Utilisées
Frontend : HTML5, CSS3, JavaScript
Backend : PHP
Base de données : MySQL
Autres : PDO pour les opérations sur la base de données

## 📋 Pré-requis
PHP : Version ≥ 8.1
MySQL : Version ≥ 5.7
Serveur web (XAMPP ou Nginx)
Navigateur web


## ⚙️ Installation

1. Clone the repository

- git clone https://github.com/AliTalebmoh/FoodApp

### Importer la base de données :

Créez une base de données nommée reservation_db.
Importez le fichier reservation_db.sql situé à la racine du projet.
Configurer la connexion à la base de données :

Ouvrez includes/db.php.
Modifiez les identifiants de connexion à la base de données si nécessaire.
Démarrez le serveur web :

Accédez à l'application :

Espace client : http://localhost/reservation_billets
Espace admin : http://localhost/reservation_billets/admin
## 📱 Screenshots





## 🔒 Fonctionnalités de Sécurité

Sanitation des entrées utilisateur.
Gestion sécurisée des sessions.
Prévention des injections SQL via des requêtes préparées PDO.
Contrôle d'accès pour le panneau administrateur.

## 🗂️ Project Structure
```
Gestion_Réservation_Billets/
├── admin/                  # Fichiers pour le panneau admin
├── components/             # Composants PHP réutilisables
├── includes/                # Fichiers de la connei
├── .gitlab-ci.yml           # config de gitlab
├── *.php                   # Fichiers principaux PHP
└── db.sql      # Fichier de la base de données

```

## 💡 Explication des Fonctionnalités Clés
### Gestion des Utilisateurs :
Système d'inscription et de connexion sécurisé.
Mise à jour du profil utilisateur.

### Gestion des Films et Séances :
Organisation des films par catégorie.
Fonctionnalités de recherche et filtres.
Gestion des horaires et des salles.

### Système de Réservation :
Sélection des sièges via un plan interactif.
Options multiples pour le paiement.
Suivi des réservations et historique des commandes.

### Tableau de Bord Admin :
Aperçu des statistiques de ventes.
Gestion centralisée des films, séances, Salles ,et utilisateurs.





## 👨‍💻 Author

Anas Lkhayat | Khalil Jazoul
