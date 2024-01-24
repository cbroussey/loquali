/* PAGE DE GESTION DES COMPTES */

//header
const accountDisconnect = document.getElementById("accountDisconnect");
const popUpDeco = document.getElementById("popUpDeco");

accountDisconnect.addEventListener('click', function() {
    popUpDeco.style.display = "block";
});

//popup Disconnect
const cancelDisconnect = document.getElementById("cancelDisconnect");

cancelDisconnect.addEventListener('click', function() {
    popUpDeco.style.display = "none";
});

const compteAccueil = document.getElementById("compteAccueil");
const compteInfosPerso = document.getElementById("compteInfosPerso");
const compteConnection = document.getElementById("compteConnection");
const compteFavoris = document.getElementById("compteFavoris");
const compteLogements = document.getElementById("compteLogements");
const compteReservations = document.getElementById("compteReservations");
const compteMessagerie = document.getElementById("compteMessagerie");
const comptePaiement = document.getElementById("comptePaiement");

const listeLiens = [compteAccueil, compteInfosPerso, compteConnection, compteFavoris, compteLogements, compteReservations, compteMessagerie, comptePaiement];

// Sélectionnez tous les liens à l'intérieur de la div avec la classe 'nav'
var links = document.querySelectorAll('.nav > div');
var menuLinks = document.querySelectorAll('figure > figcaption');
var current = compteAccueil;
var currentMenuLink = menuLinks[0];
currentMenuLink.style.color = 'var(--textColor)';
currentMenuLink.parentNode.children[0].children[1].display = "none";
console.log(currentMenuLink.parentNode.children[0].children[1]);

function liens_compte(index) {
    console.log('Vous avez cliqué sur le lien avec l\'index : ' + index);
    currentMenuLink.style.color = 'var(--secondBG)';
    console.log(currentMenuLink);
    currentMenuLink = menuLinks[index];
    currentMenuLink.style.color = 'var(--textColor)';
    switch (index) {
        case 0:
            console.log(this);
            current.style.display = "none";
            current = compteAccueil;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        case 1:
            current.style.display = "none";
            current = compteInfosPerso;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        case 2:
            current.style.display = "none";
            current = compteConnection;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        case 3:
            current.style.display = "none";
            current = compteLogements;
            current.style.display = "block";
            break;
        case 4:
            current.style.display = "none";
            current = compteReservations;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        case 5:
            current.style.display = "none";
            current = compteMessagerie;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        case 6:
            current.style.display = "none";
            current = comptePaiement;
            current.style.display = "block";
            compteLogements.style.display = "none";
            break;
        default:
            console.log("Problème d'indexation");
            break;
    }
};

links.forEach(function(link, index) {
    link.addEventListener('click', () => {
        liens_compte(index)
    });
});

  // Fonction pour réinitialiser la couleur au survol
  function resetColorOnHover(element) {
    element.addEventListener('mouseenter', function() {
        if (element != currentMenuLink) {
            this.style.color = 'var(--textColor)';
        }
    });

    // (Facultatif) Si vous souhaitez réappliquer la couleur après le survol
    element.addEventListener('mouseleave', function() {
        if (element != currentMenuLink) {
            this.style.color = 'var(--secondBG)';
        }
    });
  }

  // Appliquez les fonctions à chaque élément menuLink
  menuLinks.forEach(function(menuLink) {
    resetColorOnHover(menuLink);
  });
