# Recherche Stage

Bienvenue dans le projet **Recherche Stage**. Ce document fournit une description complète et détaillée du système, incluant l'architecture, la structure des répertoires, le rôle de chaque fichier, classe et fonction, ainsi que des conseils pratiques pour modifier ou étendre le projet en se référant aux exigences du cahier des charges.

> **Palette de Couleurs & Style**  
> Le design du projet s'inspire d'une palette moderne et dynamique soigneusement désignée par la "madeleine" :  
> - **Noir** (#000000) – Pour les arrière-plans et textes de contraste  
> - **Bleu** (#2196F3) – Pour les boutons et éléments d'action  
> - **Violet** (#9C27B0) – Pour les titres et séparateurs  
> - **Rose** (#E91E63) – Pour les accents et éléments interactifs  
>
> Ces couleurs guident le design des feuilles de style (CSS) et l'interface utilisateur, en particulier dans les fichiers du dossier `public/css`.

---

## Table des Matières

- [Introduction](#introduction)
- [Architecture du Système](#architecture-du-système)
- [Diagramme Mermaid](#diagramme-mermaid)
- [Structure des Répertoires](#structure-des-répertoires)
- [Description Détaillée des Fichiers](#description-détaillée-des-fichiers)
  - [Controllers](#controllers)
  - [Models](#models)
  - [Views](#views)
  - [Configurations](#configurations)
  - [Public (Assets)](#public-assets)
- [Spécifications Fonctionnelles & Techniques](#spécifications-fonctionnelles--techniques)
  - [Gestion d'accès et Cookies](#gestion-daccès-et-cookies)
  - [Gestion des entreprises](#gestion-des-entreprises)
  - [Gestion des offres de stage](#gestion-des-offres-de-stage)
  - [Gestion des comptes Pilote et Étudiant](#gestion-des-comptes-pilote-et-étudiant)
  - [Gestion des candidatures et Wish-list](#gestion-des-candidatures-et-wish-list)
  - [Pagination et Bonus PWA](#pagination-et-bonus-pwa)
- [Guide de Modification et Personnalisation](#guide-de-modification-et-personnalisation)
  - [Modification de la Page de Connexion](#modification-de-la-page-de-connexion)
- [Technologies et Langages Utilisés](#technologies-et-langages-utilisés)
- [Spécifications Techniques (STx)](#spécifications-techniques-stx)
- [Conclusion](#conclusion)

---

## Introduction

Le projet **Recherche Stage** est une application web basée sur le modèle MVC (Modèle-Vue-Contrôleur) écrite principalement en **PHP** pour la logique serveur, avec **HTML**, **CSS** et **JavaScript** pour l'interface utilisateur.  
L'application gère plusieurs rôles (Admin, Étudiant, Pilote) et propose des fonctionnalités telles que l'authentification, la gestion des offres, des candidatures et la gestion des entreprises.

Ce README vous guide pour comprendre la structure, naviguer dans le code et effectuer des modifications précises en se référant aux exigences fonctionnelles (SFx) et techniques (STx) du cahier des charges.

---

## Architecture du Système

L'application est organisée en quatre couches principales :

- **Frontend Layer**  
  Composé des fichiers de vues (PHP, HTML, CSS et JavaScript) qui forment l'interface utilisateur.  
  *Exemples* :  
  - `app/Views/auth/login.php` pour la page de connexion  
  - `app/Views/dashboard/admin.php` pour le dashboard administrateur

- **Controller Layer**  
  Contient la logique métier et la gestion des requêtes HTTP.  
  *Exemples* :  
  - `app/Controllers/AuthController.php` – Gère l'authentification, les cookies, et la redirection en fonction du rôle  
  - `app/Controllers/DashboardController.php` – Redirige l'utilisateur vers le tableau de bord correspondant

- **Model Layer**  
  Implémente la logique d'accès aux données et interagit avec la base de données MySQL via PDO.  
  *Exemples* :  
  - `app/Models/User.php` – Gestion des utilisateurs  
  - `app/Models/Offer.php` – Gestion des offres de stage

- **Data Layer**  
  Utilise une base de données MySQL pour le stockage des données et le système de fichiers local pour les uploads (CV, images, etc.).

---

## Diagramme Mermaid

```mermaid
graph TB
    User((User))
    Admin((Admin))
    Pilote((Pilote))
    Etudiant((Étudiant))
    
    subgraph "Web Application"
        subgraph "Frontend Layer"
            WebUI["Interface Web<br>HTML/CSS/JS"]
            subgraph "Composants Frontend"
                AuthUI["Interface Authentification<br>PHP Views"]
                DashboardUI["Interface Dashboard<br>PHP Views"]
                OfferUI["Interface Offres<br>PHP Views"]
                CandidatureUI["Interface Candidatures<br>PHP Views"]
                CompanyUI["Interface Entreprises<br>PHP Views"]
                ProfileUI["Interface Profil Utilisateur<br>PHP Views"]
            end
        end

        subgraph "Controller Layer"
            BaseController["BaseController<br>PHP"]
            AuthController["AuthController<br>PHP"]
            DashboardController["DashboardController<br>PHP"]
            OfferController["OfferController<br>PHP"]
            CandidatureController["CandidatureController<br>PHP"]
            CompanyController["CompanyController<br>PHP"]
            UserController["UserController<br>PHP"]
            WishlistController["WishlistController<br>PHP"]
            ErrorController["ErrorController<br>PHP"]
        end

        subgraph "Model Layer"
            UserModel["User Model<br>PHP"]
            OfferModel["Offer Model<br>PHP"]
            CompanyModel["Company Model<br>PHP"]
            CandidatureModel["Candidature Model<br>PHP"]
            EvaluationModel["Evaluation Model<br>PHP"]
            WishlistModel["Wishlist Model<br>PHP"]
        end
    end

    subgraph "Data Layer"
        Database[("MySQL Database<br>PDO")]
        FileStorage["Stockage Fichiers<br>Système de Fichiers Local"]
    end

    User -->|"Utilise"| WebUI
    Admin -->|"Gère"| WebUI
    Pilote -->|"Supervise"| WebUI
    Etudiant -->|"Postule et suit"| WebUI

    WebUI -->|"Route vers"| BaseController
    AuthUI -->|"Soumet données à"| AuthController
    DashboardUI -->|"Requête via"| DashboardController
    OfferUI -->|"Gère via"| OfferController
    CandidatureUI -->|"Traite via"| CandidatureController
    CompanyUI -->|"Gère via"| CompanyController
    ProfileUI -->|"Mets à jour via"| UserController
    WishlistController -->|"Gère wish-list via"| WishlistModel

    BaseController -->|"Sécurise"| AuthController
    AuthController -->|"Valide et gère"| UserModel
    OfferController -->|"Opère sur"| OfferModel
    CompanyController -->|"Opère sur"| CompanyModel
    CandidatureController -->|"Opère sur"| CandidatureModel
    DashboardController -->|"Affiche vue en fonction du rôle"| UserModel

    UserModel, OfferModel, CompanyModel, CandidatureModel -->|"Stocke/Récupère données dans"| Database
    UserModel, CompanyModel, CandidatureModel -->|"Gère fichiers (images, CV)"| FileStorage

```


# Spécifications Fonctionnelles & Techniques

Pour faciliter le suivi et la correction, chaque spécification est accompagnée d'une case à cocher.

## Gestion d'accès et Cookies
- [ ] **SFx 1 – Authentifier**  
  **Description**: Permet à l'utilisateur de s'authentifier (email & mot de passe).  
  **Implémentation**:
  - **Fichier**: `app/Controllers/AuthController.php`
    - `login()`: Vérifie les identifiants, démarre la session, redirige selon le rôle.
    - `setRememberCookie()`: Crée le cookie `remember_me` sur 30 jours (hash de l'ID, email et clé secrète).
    - `cookieConsent()`: Affiche la vue `app/Views/auth/cookie_consent.php` pour gérer le consentement aux cookies.
  - **STx 10 – Sécurité**: Aucune donnée sensible n'est stockée en clair.

## Gestion des entreprises
- [ ] **SFx 2 – Rechercher et afficher une entreprise**  
  **Description**: Recherche la fiche d'une entreprise via des critères (nom, description, contacts) et affiche ses offres et évaluations.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/CompanyController.php`
  - **Modèle**: `app/Models/Company.php` (méthodes: `getAll()`, `findById()`, `searchOrderByMatch()`)

- [ ] **SFx 3 – Créer une entreprise**  
  **Description**: Permet de créer la fiche d'une entreprise (nom, description, email, téléphone).  
  **Implémentation**:
  - **Vue**: `app/Views/company/create.php`
  - **Contrôleur**: `app/Controllers/CompanyController.php`

- [ ] **SFx 4 – Modifier une entreprise**  
  **Description**: Permet de modifier la fiche d'une entreprise.  
  **Implémentation**:
  - **Vue**: `app/Views/company/edit.php`
  - **Contrôleur**: `app/Controllers/CompanyController.php`

- [ ] **SFx 5 – Évaluer une entreprise**  
  **Description**: Permet à un utilisateur d'évaluer une entreprise (notation, commentaires).  
  **Implémentation**:
  - **Modèle**: `app/Models/Evaluation.php`
  - Mise à jour dans `CompanyModel::updateAverageRating()`

- [ ] **SFx 6 – Supprimer une entreprise**  
  **Description**: Permet de retirer une entreprise du système.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/CompanyController.php`
  - **Modèle**: Méthode `delete()` dans `app/Models/Company.php`

## Gestion des offres de stage
- [ ] **SFx 8 – Rechercher et afficher une offre**  
  **Description**: Recherche une offre par critères (entreprise, titre, compétences, rémunération, dates, candidatures).  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/OfferController.php`
  - **Modèle**: `app/Models/Offer.php` (méthodes: `getAll()`, `searchOrderByMatch()`)

- [ ] **SFx 9 – Créer une offre**  
  **Description**: Permet de créer une offre de stage (compétences, titre, description, entreprise, rémunération, dates).  
  **Implémentation**:
  - **Vue**: `app/Views/offer/create.php`
  - **Contrôleur**: `app/Controllers/OfferController.php` (méthode: `create()`)

- [ ] **SFx 10 – Modifier une offre**  
  **Description**: Permet de modifier une offre existante.  
  **Implémentation**:
  - **Vue**: `app/Views/offer/edit.php`
  - **Contrôleur**: `app/Controllers/OfferController.php` (méthode: `update()`)

- [ ] **SFx 11 – Supprimer une offre**  
  **Description**: Permet de retirer une offre du système.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/OfferController.php` (méthode: `delete()`)
  - **Modèle**: `app/Models/Offer.php`

- [ ] **SFx 12 – Consulter les statistiques des offres**  
  **Description**: Affiche un dashboard des statistiques (compétences, durée des stages, top offres en wish-list).  
  **Implémentation**:
  - **Vue**: `app/Views/dashboard/admin.php` (ou vue dédiée)
  - **Contrôleur**: `app/Controllers/DashboardController.php`

## Gestion des comptes Pilote et Étudiant
- [ ] **SFx 13 – Rechercher et afficher un compte Pilote**  
  **Description**: Recherche et affiche un compte Pilote (nom, prénom).  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/UserController.php`
  - **Vue**: `app/Views/user/`

- [ ] **SFx 14 – Créer un compte Pilote**  
  **Description**: Permet de créer un compte Pilote.  
  **Implémentation**:
  - **Vue**: `app/Views/user/create.php`
  - **Contrôleur**: `app/Controllers/UserController.php`

- [ ] **SFx 15 – Modifier un compte Pilote**  
  **Description**: Permet de modifier un compte Pilote.  
  **Implémentation**:
  - **Vue**: `app/Views/user/edit.php`
  - **Contrôleur**: `app/Controllers/UserController.php`

- [ ] **SFx 16 – Supprimer un compte Pilote**  
  **Description**: Permet de supprimer un compte Pilote.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/UserController.php`
  - **Modèle**: `app/Models/User.php` (méthode: `delete()`)

- [ ] **SFx 17 – Rechercher et afficher un compte Étudiant**  
  **Description**: Recherche un compte Étudiant et affiche ses informations (nom, prénom, email).  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/UserController.php`
  - **Modèle**: `app/Models/User.php`

- [ ] **SFx 18 – Créer un compte Étudiant**  
  **Description**: Permet de créer un compte Étudiant.  
  **Implémentation**:
  - **Vue**: `app/Views/user/create.php`
  - **Contrôleur**: `app/Controllers/UserController.php`

- [ ] **SFx 19 – Modifier un compte Étudiant**  
  **Description**: Permet de modifier un compte Étudiant.  
  **Implémentation**:
  - **Vue**: `app/Views/user/edit.php`
  - **Contrôleur**: `app/Controllers/UserController.php`

- [ ] **SFx 20 – Supprimer un compte Étudiant**  
  **Description**: Permet de supprimer un compte Étudiant.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/UserController.php`
  - **Modèle**: `app/Models/User.php`

- [ ] **SFx 21 – Consulter les statistiques d'un compte Étudiant**  
  **Description**: Permet de suivre l'état de la recherche de stage d'un étudiant.  
  **Implémentation**:
  - **Vue**: (Vue spécifique pour le suivi des candidatures)
  - **Contrôleur**: `app/Controllers/UserController.php`

## Gestion des candidatures et Wish-list
- [ ] **SFx 22 – Ajouter une offre à la wish-list**  
  **Description**: Permet à l'utilisateur d'ajouter une offre à sa liste d'intérêts.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/WishlistController.php`
  - **Modèle**: `app/Models/Wishlist.php`

- [ ] **SFx 23 – Retirer une offre à la wish-list**  
  **Description**: Permet de retirer une offre de la wish-list.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/WishlistController.php`

- [ ] **SFx 24 – Afficher les offres ajoutées à la wish-list**  
  **Description**: Permet d'afficher la liste des offres ajoutées à la wish-list.  
  **Implémentation**:
  - **Vue**: `app/Views/wishlist/index.php`

- [ ] **SFx 25 – Postuler à une offre**  
  **Description**: Permet à un étudiant de postuler en soumettant une lettre de motivation et en téléversant son CV.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/CandidatureController.php`
  - **Modèle**: `app/Models/Candidature.php`
  - **Vue**: `app/Views/candidature/postuler.php`

- [ ] **SFx 26 – Afficher les offres en cours de candidature**  
  **Description**: Affiche les offres auxquelles un étudiant a postulé.  
  **Implémentation**:
  - **Contrôleur**: `app/Controllers/CandidatureController.php`
  - **Vue**: `app/Views/candidature/index.php`

## Pagination et Bonus PWA
- [ ] **SFx 27 – Pagination**  
  **Description**: Chaque liste (entreprises, offres, utilisateurs, etc.) intègre une pagination pour améliorer la navigation.  
  **Implémentation**:
  - **Logique**: Intégrée dans les contrôleurs (ex. `OfferController.php`) et les vues associées.

- [ ] **Bonus – Accès mobile (PWA)**  
  **Description**: avec @media dans les css


