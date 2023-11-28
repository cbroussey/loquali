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

const compteInfosPerso = document.getElementById("compteInfosPerso");
const compteConnection = document.getElementById("compteConnection");
const compteFavoris = document.getElementById("compteFavoris");
const compteLogements = document.getElementById("compteLogements");
const compteReservations = document.getElementById("compteReservations");
const compteMessagerie = document.getElementById("compteMessagerie");
const comptePaiement = document.getElementById("comptePaiement");

const listeLiens = [compteInfosPerso, compteConnection, compteFavoris, compteLogements, compteReservations, compteMessagerie, comptePaiement];

// Sélectionnez tous les liens à l'intérieur de la div avec la classe 'nav'
var links = document.querySelectorAll('.nav > div');
var current = compteInfosPerso;

links.forEach(function(link, index) {
    link.addEventListener('click', function() {
        console.log('Vous avez cliqué sur le lien avec l\'index : ' + index);
        switch (index) {
            case 0:
                document.location.href = "compteAccueil.php";
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
                current = compteFavoris;
                current.style.display = "block";
                break;
            case 4:
                current.style.display = "none";
                current = compteLogements;
                current.style.display = "block";
                break;
            case 5:
                current.style.display = "none";
                current = compteReservations;
                current.style.display = "block";
                break;
            case 6:
                current.style.display = "none";
                current = compteMessagerie;
                current.style.display = "block";
                break;
            case 7:
                current.style.display = "none";
                current = comptePaiement;
                current.style.display = "block";
                break;
            default:
                console.log("Problème d'indexation");
                break;
        }
    });
});

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('mouseenter', () => {
        const color = item.dataset.color;
        item.style.backgroundColor = `var(--${color}HoverColor)`;
    });

    item.addEventListener('mouseleave', () => {
        item.style.backgroundColor = 'initial';
    });
});

