document.addEventListener("DOMContentLoaded", () => {
  // ========================
  // 1) Gestion du toggle de langue
  // ========================
  const langToggle = document.getElementById("lang-toggle");
  const languageText = document.getElementById("language-text");
  const languageFlag = document.getElementById("language-flag");

  if (langToggle) {
    langToggle.addEventListener("click", () => {
      if (languageText.textContent.trim() === "English") {
        languageText.textContent = "Français";
        languageFlag.src = window.BASE_URL + "public/images/imagefr.png";
        languageFlag.alt = "Français";
      } else {
        languageText.textContent = "English";
        languageFlag.src = window.BASE_URL + "public/images/imageuk.png";
        languageFlag.alt = "English";
      }
    });
  }

  // ========================
  // 2) Gestion de la sidebar avec l'indicateur (VOTRE BLOC D'ORIGINE)
  // ========================
  function moveIndicator(link) {
    // 1) Retirer .active sur tous les liens, l'ajouter sur le lien cliqué
    navLinks.forEach((l) => l.classList.remove("active"));
    link.classList.add("active");
  
    // 2) Calculer la nouvelle position
    const linkRect = link.getBoundingClientRect();
    const sidebarRect = sidebar.getBoundingClientRect();
    const topPosition =
      linkRect.top -
      sidebarRect.top +
      linkRect.height / 2 -
      indicator.offsetHeight / 2;
  
    // 3) Anime.js pour animer la propriété "top" de .indicator
    anime({
      targets: indicator,         // L’élément DOM que vous animez
      top: topPosition,           // nouvelle valeur
      duration: 300,             // en ms
      easing: 'easeInOutQuad'    // type d’interpolation
    });
  }
  

  // ========================
  // 3) Gestion du mini-menu (dropdown) pour l’utilisateur
  // ========================
  const userBlock = document.getElementById("user-block");
  const userDropdown = document.getElementById("user-dropdown");

  if (userBlock && userDropdown) {
    userBlock.addEventListener("click", (event) => {
      event.stopPropagation();
      userDropdown.classList.toggle("show");
    });

    document.addEventListener("click", (event) => {
      if (!userBlock.contains(event.target)) {
        userDropdown.classList.remove("show");
      }
    });
  }

  // ========================
  // 4) Ajout d'event listener par ID pour la redirection
  //    après 300ms (l'animation)
  // ========================

  // Dashboard
  const dashLink = document.getElementById("dashboardLink");
  if (dashLink) {
    dashLink.addEventListener("click", () => {
      // On attend 300ms (après moveIndicator)
      setTimeout(() => {
        // Supposez qu'on veut pointer vers "dashboard&action=admin"
        // ou "dashboard&action=pilote" selon le userRole
        // Pour un exemple simple :
        // window.location.href = "index.php?controller=dashboard&action=admin";

        // OU, si c’est dynamique via userRole en JS :
        let r = (window.userRole === "ADMIN") ? "admin"
              : (window.userRole === "PILOTE") ? "pilote"
              : (window.userRole === "ETUDIANT") ? "etudiant"
              : "login";

        window.location.href = `index.php?controller=dashboard&action=${r}`;
      }, 300);
    });
  }

  // Entreprises
  const companyLink = document.getElementById("companyLink");
  if (companyLink) {
    companyLink.addEventListener("click", () => {
      setTimeout(() => {
        // Redirection
        window.location.href = "index.php?controller=company&action=index";
      }, 300);
    });
  }

  // Utilisateurs
  const userLink = document.getElementById("userLink");
  if (userLink) {
    userLink.addEventListener("click", () => {
      setTimeout(() => {
        window.location.href = "index.php?controller=user&action=index";
      }, 300);
    });
  }

  // Offres
  const offerLink = document.getElementById("offerLink");
  if (offerLink) {
    offerLink.addEventListener("click", () => {
      setTimeout(() => {
        window.location.href = "index.php?controller=offer&action=index";
      }, 300);
    });
  }

  // Candidatures
  const candidatureLink = document.getElementById("candidatureLink");
  if (candidatureLink) {
    candidatureLink.addEventListener("click", () => {
      setTimeout(() => {
        window.location.href = "index.php?controller=candidature&action=index";
      }, 300);
    });
  }

  // Wish-list
  const wishlistLink = document.getElementById("wishlistLink");
  if (wishlistLink) {
    wishlistLink.addEventListener("click", () => {
      setTimeout(() => {
        window.location.href = "index.php?controller=wishlist&action=index";
      }, 300);
    });
  }
});
