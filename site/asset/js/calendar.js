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

submit.addEventListener('click', function () {
    prevOrNext.value = "submit";
});

//gestion des clics sur les dates
const boxesCalendar = document.getElementsByClassName('nbcasejourcalend');
const boxIsClicked = new Map();

Array.from(boxesCalendar).forEach(box => {
    boxIsClicked.set(box, false);
    box.addEventListener('click', function () {
        if (boxIsClicked.get(box) === false) {
            boxIsClicked.set(box, true);
            box.style.backgroundColor = '#2072BC';
        } else {
            boxIsClicked.set(box, false);
            box.style.backgroundColor = '';
        }
        updateTitle();
    })
});

//changement du titre de la fenêtre de modification en fonction des jours sélectionnés
const titleChangeBox = document.getElementById('date');
const date = titleChangeBox.innerText;

function updateTitle() {
    let selectedDays = [];
    boxIsClicked.forEach((value, key) => {
        if (value) {
            selectedDays.push(key.value);
        }
    });
    titleChangeBox.innerText = selectedDays.join(', ') + ' ' + date;
}

//gestion du clic sur le switch de disponibilité du logement
const switchIsAvailable = document.getElementById('isAvailable');
const textIsAvailable = document.getElementById('isAvailableText');
let daysAreAvailable = true;

switchIsAvailable.addEventListener('click', () => {
    daysAreAvailable = daysAreAvailable ? false : true;
    textIsAvailable.textContent = daysAreAvailable ? 'logement disponible' : 'logement indisponible';
});

//gestion du clic sur le bouton valider
const allDays = document.getElementById('allDays');
const calendar = document.getElementById('calendar');

submit.addEventListener('click', () => {
    let selectedDays = [];
    boxIsClicked.forEach((value, key) => {
        if (value) {
            selectedDays.push(key.value);
        }
    });

    if (selectedDays.length > 0) {
        allDays.value = daysAreAvailable.toString() + ',' + selectedDays.join(',');
        calendar.submit();
    }
})

