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
Serveur web (XAMPP )
Navigateur web


## ⚙️ Installation

1. Clone the repository

- git clone https://github.com/khalil-de/Gestion-de-R-servation-de-Billets.git

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
# admin :
### Admin dashboard
<img width="932" alt="dashboardAdmin" src="https://github.com/user-attachments/assets/3faae3a0-7d58-4d7f-b808-f617b92f2ca5">

### Connixion
<img width="929" alt="connexion" src="https://github.com/user-attachments/assets/d65eb3eb-a38a-4aac-b77d-8d057300e5fe">

### Inscripsion
<img width="934" alt="inscription" src="https://github.com/user-attachments/assets/e3af8e38-d925-44e4-90f5-fcc4e8d3c8c9">

### list des films
<img width="931" alt="liste des films" src="https://github.com/user-attachments/assets/b7bfa047-7095-4871-881a-5eaca4159c57">

### liste des salles
<img width="929" alt="listSalle" src="https://github.com/user-attachments/assets/6a80b515-8abd-4dd8-99af-cfb94517f149">

### liste des seances 
<img width="931" alt="ListSeance" src="https://github.com/user-attachments/assets/8a9dccdd-928e-4828-8f1c-fcad0440c44a">

# USER 
### acceuil 
<img width="920" alt="PageAcc" src="https://github.com/user-attachments/assets/e5b265ee-b23d-4bac-818a-8b624acc7741">


### reserver
<img width="831" alt="Reserve" src="https://github.com/user-attachments/assets/9ab8755d-5d50-4ce0-9c8a-86d7e301640a">

### confermer 
<img width="821" alt="confirmer" src="https://github.com/user-attachments/assets/b243b18f-0003-4d11-a755-6a268f03b40b">

### historique  
<img width="929" alt="Histork" src="https://github.com/user-attachments/assets/3c8105a4-cbd8-4b6b-9c89-89760cf321c4">

### dashbord
<img width="928" alt="dasborduser" src="https://github.com/user-attachments/assets/acd505a5-4ab2-4a21-a4df-4367e32bc7ac">

### etat des salles
<img width="929" alt="EtatSAll" src="https://github.com/user-attachments/assets/c09e4775-5322-45f8-a024-1ad3211723ea">

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
├── user/                # Fichiers pour le panneau user
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
