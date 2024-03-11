import './bootstrap.js';
function openMenuMobile() {
  document.querySelector('.header__nav').classList.add('open');
  document.querySelector('.overlay-menu-mobile').classList.add('open');
}

function closeMenuMobile() {
  const overlay = document.querySelector('.overlay-menu-mobile');
  const nav = document.querySelector('.header__nav');

  // Ajoute une classe temporaire pour déclencher l'animation de fermeture
  overlay.classList.add('closing');
  nav.classList.add('closing');

  // Supprime la classe 'open' après un court délai pour permettre à l'animation de se produire
  setTimeout(() => {
      overlay.classList.remove('open', 'closing');
      nav.classList.remove('open', 'closing')
  }, 300); // ajustez la durée en fonction de votre animation CSS
} 

// Change language
document.addEventListener("DOMContentLoaded", function () {
  const menuDeroulant = document.querySelector(".menu-deroulant");
  const sousMenu = document.querySelector(".sous-menu");

  sousMenu.style.display = "none";

  menuDeroulant.addEventListener("click", function () {
      if (sousMenu.style.display === "none") {
          sousMenu.style.display = "block";
      } else {
          sousMenu.style.display = "none";
      }
  });

  const sousMenuButtons = sousMenu.querySelectorAll("button");

  sousMenuButtons.forEach(function (button) {
      button.addEventListener("click", function () {
          // Échanger les images des boutons (drapeaux)
          const menuDeroulantImgSrc = menuDeroulant.querySelector("img").src;
          const sousMenuImgSrc = button.querySelector("img").src;

          menuDeroulant.querySelector("img").src = sousMenuImgSrc;
          button.querySelector("img").src = menuDeroulantImgSrc;

          // Échanger les labels (alt) des boutons (facultatif, uniquement pour l'accessibilité)
          const menuDeroulantImgAlt = menuDeroulant.querySelector("img").alt;
          const sousMenuImgAlt = button.querySelector("img").alt;

          menuDeroulant.querySelector("img").alt = sousMenuImgAlt;
          button.querySelector("img").alt = menuDeroulantImgAlt;

          // Fermer le sous-menu après l'échange (uniquement lorsqu'un drapeau est cliqué)
          sousMenu.style.display = "none";
      });
  });

  // Empêcher la propagation du clic sur le sous-menu pour qu'il ne ferme pas automatiquement
  sousMenu.addEventListener("click", function (event) {
      event.stopPropagation();
  });

  // Fermer le sous-menu lorsqu'on clique sur un drapeau
  document.addEventListener("click", function (event) {
      if (sousMenu.contains(event.target)) {
          sousMenu.style.display = "none";
      }
  });
});

// Fonction pour afficher l'overlay et empêcher le défilement de la page
function showFullScreenImage() {
  document.getElementById('overlay').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

// Fonction pour masquer l'overlay et rétablir le défilement de la page
function hideFullScreenImage() {
  document.getElementById('overlay').style.display = 'none';
  document.body.style.overflow = 'auto';
}
function hideOverlay() {
  var overlay = document.getElementById("cvOverlay");
  hideFullScreenImage()
}