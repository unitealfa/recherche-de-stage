// Afficher ou masquer le bandeau des cookies en fonction de l'acceptation
document.addEventListener("DOMContentLoaded", function() {
    const cookieBanner = document.getElementById("cookieBanner");
    const cookieConsent = getCookie("cookies_accepted");

    // Si le cookie est déjà accepté, on masque le bandeau
    if (cookieConsent === "true") {
        cookieBanner.style.display = "none";
    } else {
        // Si le cookie n'est pas encore accepté, on affiche le bandeau
        cookieBanner.style.display = "block";
    }
});

function acceptCookies() {
    // Enregistrer l'acceptation des cookies
    document.cookie = "cookies_accepted=true; path=/; max-age=31536000"; // Cookie valable pendant 1 an
    document.getElementById("cookieBanner").style.display = "none"; // Masquer le bandeau

    // Sauvegarder les informations de connexion si elles sont disponibles
    const cookieData = getCookie("remember_me");
    if (cookieData) {
        const data = JSON.parse(atob(cookieData));
        document.querySelector('input[name="email"]').value = data.user_email;
        document.querySelector('input[name="password"]').value = data.user_password;
    }
}

function declineCookies() {
    document.getElementById("cookieBanner").style.display = "none"; // Masquer le bandeau si refusé
}

// Fonction pour obtenir un cookie par son nom
function getCookie(name) {
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name + "=") == 0) {
            return c.substring(name.length + 1, c.length);
        }
    }
    return "";
}
