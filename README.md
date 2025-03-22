# Recherche Stage

## 📌 Fonctionnalités

### Gestion des Comptes Étudiants

- [ ] **SFx 17 – Rechercher et afficher un compte Étudiant**  
  **Description :** Recherche un compte Étudiant et affiche ses informations (nom, prénom, email).  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/UserController.php
  Modèle: app/Models/User.php
  ```

- [ ] **SFx 18 – Créer un compte Étudiant**  
  **Description :** Permet de créer un compte Étudiant.  
  **Implémentation :**  
  ```
  Vue: app/Views/user/create.php
  Contrôleur: app/Controllers/UserController.php
  ```

- [ ] **SFx 19 – Modifier un compte Étudiant**  
  **Description :** Permet de modifier un compte Étudiant.  
  **Implémentation :**  
  ```
  Vue: app/Views/user/edit.php
  Contrôleur: app/Controllers/UserController.php
  ```

- [ ] **SFx 20 – Supprimer un compte Étudiant**  
  **Description :** Permet de supprimer un compte Étudiant.  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/UserController.php
  Modèle: app/Models/User.php
  ```

- [ ] **SFx 21 – Consulter les statistiques d'un compte Étudiant**  
  **Description :** Permet de suivre l'état de la recherche de stage d'un étudiant.  
  **Implémentation :**  
  ```
  Vue: (Vue spécifique pour le suivi des candidatures)
  Contrôleur: app/Controllers/UserController.php
  ```

### 🎯 Gestion des Candidatures et Wish-list

- [ ] **SFx 22 – Ajouter une offre à la wish-list**  
  **Description :** Permet à l'utilisateur d'ajouter une offre à sa liste d'intérêts.  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/WishlistController.php
  Modèle: app/Models/Wishlist.php
  ```

- [ ] **SFx 23 – Retirer une offre de la wish-list**  
  **Description :** Permet de retirer une offre de la wish-list.  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/WishlistController.php
  ```

- [ ] **SFx 24 – Afficher les offres ajoutées à la wish-list**  
  **Description :** Permet d'afficher la liste des offres ajoutées à la wish-list.  
  **Implémentation :**  
  ```
  Vue: app/Views/wishlist/index.php
  ```

- [ ] **SFx 25 – Postuler à une offre**  
  **Description :** Permet à un étudiant de postuler en soumettant une lettre de motivation et en téléversant son CV.  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/CandidatureController.php
  Modèle: app/Models/Candidature.php
  Vue: app/Views/candidature/postuler.php
  ```

- [ ] **SFx 26 – Afficher les offres en cours de candidature**  
  **Description :** Affiche les offres auxquelles un étudiant a postulé.  
  **Implémentation :**  
  ```
  Contrôleur: app/Controllers/CandidatureController.php
  Vue: app/Views/candidature/index.php
  ```

### 📌 Pagination et Bonus PWA

- [ ] **SFx 27 – Pagination**  
  **Description :** Chaque liste (entreprises, offres, utilisateurs, etc.) intègre une pagination pour améliorer la navigation.  
  **Implémentation :**  
  ```
  mazal tsbroulna chwiya mais ani ndirr fiha douka
  ```

- [ ] **Bonus – Accès mobile (PWA)**  
  **Description :** TKT, tu me fais pas confiance 🤔  
