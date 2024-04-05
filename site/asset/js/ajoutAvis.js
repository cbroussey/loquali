document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.container input');

    checkboxes.forEach(function (checkbox, index) {
        checkbox.addEventListener('change', function () {
            const starImg = this.parentElement.querySelector('.star-img');
            if (this.checked) {
                // Si la case est cochée, cocher les cases précédentes
                for (let i = 0; i <= index; i++) {
                    checkboxes[i].checked = true;
                    checkboxes[i].parentElement.querySelector('.star-img').src = 'asset/icons/bleu/star2.svg';
                }
            } else {
                // Si la case est décochée, décocher les cases suivantes
                for (let i = checkboxes.length - 1; i > index; i--) {
                    checkboxes[i].checked = false;
                    checkboxes[i].parentElement.querySelector('.star-img').src = 'asset/icons/blanc/star.svg';
                }
            }
        });
    });
});

function openModal2() {
    document.getElementById('myModal2').style.display = 'block';
}

function closeModal2() {
    document.getElementById('myModal2').style.display = 'none';
}

document.getElementById("AjoutAvis").addEventListener("click", function() {
    var formulaire = document.getElementById("ajoutAvisForm");
    var btnAjAvis = document.getElementById("AjoutAvis");
    if (formulaire.style.display === "none") {
        formulaire.style.display = "block";
        btnAjAvis.style.display = "none";
    } else {
        formulaire.style.display = "none";
    }
});

const AjoutAvis = document.getElementById('AjoutAvis');

AjoutAvis.addEventListener('click', function() {
    AjoutAvis.click();
})

//gestion du mois du calendrier (passage au mois suivant et précédent)
const prevYear = document.getElementById("prevYear");
const nextYear = document.getElementById("nextYear");
const submit = document.getElementById("valideyy");
const prevOrNext = document.getElementById("prevOrNext");

prevYear.addEventListener('click', function () {
    prevOrNext.value = "prev";
});

nextYear.addEventListener('click', function () {
    prevOrNext.value = "next";
});

//gestion des clics sur les dates
const boxesCalendar = document.getElementsByClassName('nbcasejourcalend');
const reservations = document.getElementsByClassName('reservations');
const oldDates = document.getElementsByClassName('oldDates');
const boxIsClicked = new Map();

let i = 0;
Array.from(boxesCalendar).forEach(box => {
    console.log("box" + i);
    if (reservations[i].value == 1) {
        box.style.backgroundColor = '#DC6C3C';
    } else if (oldDates[i].value == 1) {
        box.style.backgroundColor = '#B5B5B5';
    }
    i++;
});


