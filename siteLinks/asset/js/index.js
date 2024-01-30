
/* Partie pour l'animation du carrousel */



document.addEventListener("DOMContentLoaded", function () {

    /* Récupération des données */
    var slider = document.querySelector(".slider1 .slider");
    var dots = document.querySelectorAll(".dot");
    var currentIndex = 0;
    var imageCount = 5;
    var animationInProgress = false;
    var intervalId;

    /* Réalisation de l'animation */
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

    function dotDecthover(index) {

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
    radioButtonClient.addEventListener("click", function () {
        if (radioButtonClient.checked) {
            confirmButton.value = "Créer mon compte";
        }
    });
}

if (radioButtonOwner) { //vérifie si l'élément existe pour éviter les erreurs
    // Vérifie s'il est coché
    radioButtonOwner.addEventListener("click", function () {
        if (radioButtonOwner.checked) {
            confirmButton.value = "Suivant";
        }
    });
}


/* Mise en place de la restriction de saise */

const champTel = document.getElementById("telephone");

document.addEventListener("DOMContentLoaded", function () {
    champTel.addEventListener("input", function (event) {
        // Remplacer tout caractère non numérique par une chaîne vide
        this.value = this.value.replace(/\D/g, "");
    });
});


/* Code pour faire apparaitre la listre des filtres + tri une fois clické */

document.addEventListener("DOMContentLoaded", () => {

    /* Récupération des éléments */
    const Btn_Filtre = document.getElementById("Btn_Filtre");
    const Liste_Filtre = document.getElementById("filtreContainer");

    if (Btn_Filtre) {
        Btn_Filtre.addEventListener("click", function (event) {
            // montre popup quand click
            if (Liste_Filtre.style.display === "none" || Liste_Filtre.style.display === "") {
                Liste_Filtre.style.display = "block";
                document.body.style.overflow = "hidden";
            } else {
                Liste_Filtre.style.display = "none";
                document.body.style.overflow = "visible";
            }
            event.stopPropagation();
        });

        // Ajoute un écouteur d'événements au document pour fermer la popup si on clique en dehors
        document.addEventListener("click", function (event) {
            const isClickInside = Liste_Filtre.contains(event.target) || Btn_Filtre.contains(event.target);

            if (!isClickInside) {
                Liste_Filtre.style.display = "none";
            }
        });
    }
});
