/* PAGE DE GESTION DES COMPTES */

//header
const accountDisconnect = document.getElementById("accountDisconnect");
const accountDisconnect2 = document.getElementById("accountDisconnect2");
const popUpDeco = document.getElementById("popUpDeco");

accountDisconnect.addEventListener('click', function () {
    console.log(popUpDeco);
    popUpDeco.style.display = "block";
});

accountDisconnect2.addEventListener('click', function () {
    popUpDeco.style.display = "block";
})

//popup Disconnect
const cancelDisconnect = document.getElementById("cancelDisconnect");

cancelDisconnect.addEventListener('click', function () {
    popUpDeco.style.display = "none";
});

const compteAccueil = document.getElementById("compteAccueil");
const compteInfosPerso = document.getElementById("compteInfosPerso");
const compteConnection = document.getElementById("compteConnection");
const compteLogementReservation = document.getElementById("compteLogementReservation");
const compteDevis = document.getElementById("compteDevis");
const comptePaiementAPI = document.getElementById("comptePaiementAPI");

const listeLiens = [compteAccueil, compteInfosPerso, compteConnection, compteLogementReservation, compteDevis, comptePaiementAPI];

// Sélectionnez tous les liens à l'intérieur de la div avec la classe 'nav'
var links = document.querySelectorAll('.nav > div');
var menuLinks = document.querySelectorAll('figure > figcaption');
var current = compteAccueil;
current.style.display = "block";
var currentMenuLink = menuLinks[0];
currentMenuLink.style.color = 'var(--textColor)';
currentMenuLink.parentNode.children[0].children[1].display = "none";

links.forEach(function (link, index) {
    link.addEventListener('click', function liens_compte() {
        console.log('Vous avez cliqué sur le lien avec l\'index : ' + index);
        currentMenuLink.style.color = 'var(--secondBG)';
        currentMenuLink = menuLinks[index];
        currentMenuLink.style.color = 'var(--textColor)';
        switch (index) {
            case 0:
                current.style.display = "none";
                current = compteAccueil;
                current.style.display = "block";
                break;
            case 1:
                current.style.display = "none";
                current = compteInfosPerso;
                current.style.display = "block";
                break;
            case 2:
                current.style.display = "none";
                current = compteConnection;
                current.style.display = "block";
                break;
            case 3:
                current.style.display = "none";
                current = compteLogementReservation;
                current.style.display = "block";
                break;
            case 4:
                current.style.display = "none";
                current = compteDevis;
                current.style.display = "block";
                break;
            case 5:
                console.log(current);
                current.style.display = "none";
                current = comptePaiementAPI;
                console.log(current);
                current.style.display = "block";
                break;
            default:
                console.log("Problème d'indexation");
                break;
        }
    });
});


// Fonction pour réinitialiser la couleur au survol
function resetColorOnHover(element) {
    element.addEventListener('mouseenter', function () {
        if (element != currentMenuLink) {
            this.style.color = 'var(--textColor)';
        }
    });

    // (Facultatif) Si vous souhaitez réappliquer la couleur après le survol
    element.addEventListener('mouseleave', function () {
        if (element != currentMenuLink) {
            this.style.color = 'var(--secondBG)';
        }
    });
}

// Appliquez les fonctions à chaque élément menuLink
menuLinks.forEach(function (menuLink) {
    resetColorOnHover(menuLink);
});
