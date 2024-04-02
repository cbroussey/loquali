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
});

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
var imgLinks = document.querySelectorAll('.img-front');
var menuLinks = document.querySelectorAll('figure > figcaption');

var currentPage = compteAccueil;
var currentMenuLink = menuLinks[0];
var currentMenuImg = imgLinks[0];

//application du style sur le lien sélectionné par défaut
currentPage.style.display = "block";
currentMenuLink.style.color = 'var(--textColor)';
currentMenuImg.style.display = 'none';

//fonction pour conserver la bonne page de compte en cas de refresh
function toPage(index) {

    currentMenuLink.style.color = 'var(--secondBG)';
    currentMenuImg.style.display = 'block';
    currentPage.style.display = 'none';

    currentMenuLink = menuLinks[index];
    currentMenuImg = imgLinks[index];
    currentPage = listeLiens[index];

    currentMenuLink.style.color = 'var(--textColor)';
    currentMenuImg.style.display = 'none';
    currentPage.style.display = 'block';
}

links.forEach(function (link, index) {
    link.addEventListener('click', function liens_compte() {
        console.log('Vous avez cliqué sur le lien avec l\'index : ' + index);

        toPage(index);
        
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

// Récupérer les paramètres de l'URL pour redirection pratique
var queryString = window.location.search;
var params = new URLSearchParams(queryString);
var valeurInd = params.get('ind');
console.log(valeurInd)

if (valeurInd!=null){
    toPage(valeurInd)
}