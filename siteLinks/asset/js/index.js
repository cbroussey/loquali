/* ------------------------- HEADER ------------------------- */

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

document.addEventListener("DOMContentLoaded", function () {
var slider = document.querySelector(".slider1 .slider");
var dots = document.querySelectorAll(".dot");
var currentIndex = 0;
var imageCount = 5;
var animationInProgress = false;
var intervalId;

function animateSlider() {
    if (!animationInProgress) {
        animationInProgress = true;

        var translateValue = currentIndex * -100;

        slider.style.transition = "transform 3s ease-in-out";
        slider.style.transform = "translateX(" + translateValue + "%)";
        dots.forEach(function (dot, index) {
            dot.classList.remove("active");
                if (index === currentIndex || (currentIndex === imageCount - 1 && index === 0)) {
                    dot.classList.add("active");
                }
            });

        setTimeout(function () {
            animationInProgress = false;
            if (currentIndex === imageCount - 1) {
                slider.style.transition = "none";
                currentIndex = 0;
                slider.style.transform = "translateX(0)";
                setTimeout(function () {
                    slider.style.transition = "";
                }, 0);
            } else {
                currentIndex = (currentIndex + 1) % imageCount;
            }
        }, 3000);
    }
}

function dotClickHandler(index) {
    return function () {
        currentIndex = index;
        clearInterval(intervalId);
        animateSlider();
        setTimeout(function () {
            intervalId = setInterval(animateSlider, 2000);
        }, 2000);
    };
}

function dotDecthover(index){

}

dots.forEach(function (dot, index) {
    dot.addEventListener("click", dotClickHandler(index));
});

intervalId = setInterval(animateSlider, 2000);
});

/* CREATION DE COMPTE */

const radioButtonOwner = document.getElementById("owner");
const radioButtonClient = document.getElementById("client");
const confirmButton = document.getElementById("confirmIsOwner");

if (radioButtonClient) { //vérifie si l'élément existe pour éviter les erreurs
    // Vérifie s'il est coché
    radioButtonClient.addEventListener("click", function() {
    if (radioButtonClient.checked) {
        confirmButton.value = "Créer mon compte";
    }
});
}

if (radioButtonOwner) { //vérifie si l'élément existe pour éviter les erreurs
    // Vérifie s'il est coché
    radioButtonOwner.addEventListener("click", function() {
    if (radioButtonOwner.checked) {
        confirmButton.value = "Suivant";
    }
});
}

const champTel = document.getElementById("telephone");

document.addEventListener("DOMContentLoaded", function() {
    

    champTel.addEventListener("input", function(event) {
        // Remplacer tout caractère non numérique par une chaîne vide
        this.value = this.value.replace(/\D/g, "");
    });
});









