
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




document.addEventListener("DOMContentLoaded", () => {
    // flèche de changement de langue & affichage de la popup
    const Btn_Tri = document.getElementById("Btn_Tri");
    const Liste_Tri = document.getElementById("Liste_Tri");
    const fleche_Tri = document.getElementById("fleche_Tri");

    
    if (Btn_Tri) {
         //si clic sur la flèche
        Btn_Tri.addEventListener("click", function(event) {
            //ajout du délai de 0.5s
            if (fleche_Tri.style.transition === "") {
                fleche_Tri.style.transition = "transform 0.5s ease";
            }
            //inverse l'état de la popup
            if (Liste_Tri.style.display === "none" || Liste_Tri.style.display === "") {
                Liste_Tri.style.display = "block";
                fleche_Tri.style.transform = "rotate(-180deg)";
            } else {
                fleche_Tri.style.transform = "rotate(0deg)"
                Liste_Tri.style.display = "none";
            }
            event.stopPropagation();
        });
    
        //gestion du clic en dehors de la popup lorsqu'elle est ouverte
        document.addEventListener("click", function() {
            fleche_Tri.style.transform = "rotate(0deg)"
            Liste_Tri.style.display = "none";
        });
    }
});





