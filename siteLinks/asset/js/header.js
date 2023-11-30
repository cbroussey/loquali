/* ------------------------- HEADER ------------------------- */

document.addEventListener("DOMContentLoaded", () => {
    // flèche de changement de langue & affichage de la popup
    const headerArrowLang = document.getElementById("headerArrowLang");
    const headerPopup = document.getElementById("headerPopup");
    
    if (headerArrowLang) {
         //si clic sur la flèche
        headerArrowLang.addEventListener("click", function(event) {
            //ajout du délai de 0.5s
            if (headerArrowLang.style.transition === "") {
                headerArrowLang.style.transition = "transform 0.5s ease";
            }
            //inverse l'état de la popup
            if (headerPopup.style.display === "none" || headerPopup.style.display === "") {
                headerPopup.style.display = "block";
                headerArrowLang.style.transform = "rotate(-180deg)";
            } else {
                headerArrowLang.style.transform = "rotate(0deg)"
                headerPopup.style.display = "none";
            }
            event.stopPropagation();
        });
    
        //gestion du clic en dehors de la popup lorsqu'elle est ouverte
        document.addEventListener("click", function() {
            headerArrowLang.style.transform = "rotate(0deg)"
            headerPopup.style.display = "none";
        });
    }
});

const hamburger = document.getElementById("headerHamburger");
const menu = document.getElementById("menu");
var isToggle = false;

hamburger.addEventListener('click', function() {
    if (isToggle) {
        isToggle = false;
        menu.style.display = "none";
    } else {
        isToggle = true;
        menu.style.display = "block";
    }
});
