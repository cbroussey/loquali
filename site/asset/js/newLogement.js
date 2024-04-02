/* Fonct */
document.addEventListener("DOMContentLoaded", function() {
    const selectElement = document.getElementById("reg");
    
    selectElement.addEventListener("change", function() {
        if (selectElement.options[0].value === "") {
            selectElement.remove(0);
        }
    });
});


/* fonction pour incrépenté et décrémenté les logements */

function increment(id) {
    const input = document.getElementById(id);
    let value = parseInt(input.value, 10) || 0;
    if (value<99){
        value += 1;
    }
    input.value = value;
    currentField = input;
}

function decrement(id) {
    const input = document.getElementById(id);
    let value = parseInt(input.value, 10) || 0;
    if (value > 0) {
        value -= 1;
    }
    
    input.value = value;
    currentField = input;
}


document.getElementById('code_postal').addEventListener('input', function() {
    var regexBretagne = /^(29|35|22|56)[0-9]{3}$/;

    this.value = this.value.replace(/\D/g, '');
    if (this.value.length > 5) {
        this.value = this.value.substring(0, this.value.length - 1);
        this.style.borderColor = "#1D4C77";

    } else {
        this.style.borderColor = "red";
        console.log(this.style.borderColor);
    }

    if (regexBretagne.test(this.value) ) {
        this.style.borderColor = "#1D4C77";
    } else {
        this.style.borderColor = 'red';
    }


  });


document.getElementById('nbChambre').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, ''); 
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 99) {
        this.value = "99";
    }
    if (parseInt(this.value) < 0) {
        this.value = "0";
    }
    // Supprimer les zéros en tête
    this.value = parseInt(this.value).toString();
});





document.getElementById('nbSalle_bain').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 99) {
        this.value = "99";
    }
    if (parseInt(this.value) < 0) {
        this.value = "0";
    }
    this.value = parseInt(this.value).toString();
});


document.getElementById('Pieces').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 99) {
        this.value = "99";
    }
    if (parseInt(this.value) < 0) {
        this.value = "0";
    }
    this.value = parseInt(this.value).toString();
});


document.getElementById('Personne').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 99) {
        this.value = "99";
    }
    if (parseInt(this.value) < 0) {
        this.value = "0";
    }
    this.value = parseInt(this.value).toString();
});


function rectifLongTxt(elem) {
    if (elem.value.length < 50 && elem.value.length>0){
        elem.style.borderColor = "#1D4C77";

    } else {
        elem.style.borderColor = 'red';
    }

}


function rectifLongTxt2(elem) {
    if (elem.value.length < 250 && elem.value.length>0){
        elem.style.borderColor = "#1D4C77";

    } else {
        elem.style.borderColor = 'red';
    }

}

// correction pour 50 char max

document.getElementById('ville').addEventListener('input', function(){
    rectifLongTxt(this);
});

document.getElementById('adresse').addEventListener('input', function(){
    rectifLongTxt(this);
});

document.getElementById('appartement').addEventListener('input', function(){
    rectifLongTxt(this);
});

document.getElementById('titre').addEventListener('input', function(){
    rectifLongTxt(this);
});

document.getElementById('description2').addEventListener('input', function(){
    rectifLongTxt2(this);
});

document.getElementById('Règlement').addEventListener('input', function(){
    rectifLongTxt2(this);
});

document.getElementById('info_arrive').addEventListener('input', function(){
    rectifLongTxt2(this);
});

document.getElementById('info_depart').addEventListener('input', function(){
    rectifLongTxt2(this);
});


/* Partie pour gèrer le fait de tout remplir pour passer à la page d'après */




const gros_div = document.getElementById("gros_div");



const suivantBtn1 = document.getElementById('suivantBtn1');
const suivantBtn2 = document.getElementById("suivantBtn2");
const suivantBtn3 = document.getElementById("suivantBtn3");

const Page1 = document.getElementById("page1");
const Page2 = document.getElementById("page2");
const Page3 = document.getElementById("page3");
const Page4 = document.getElementById("page4");




const inputFieldsP1 = document.querySelectorAll('#page1 input[type="text"], #page1 select');
const inputFieldsP2 = document.querySelectorAll('#page2 input[type="text"], #page2 select, #page2 input[type="file"], #page2 textarea');
const inputFieldsP3 = document.querySelectorAll('#page3 input[type="text"], #page3 select');


function checkFieldsP1() {
  let tousLesChampsSontRemplis1 = true;

  for (const champ of inputFieldsP1) {
    if (!champ.value) {
      tousLesChampsSontRemplis1 = false;
      break;
    }
  }

  suivantBtn1.disabled = !tousLesChampsSontRemplis1;
  suivantBtn1.style.opacity = tousLesChampsSontRemplis1 ? '1' : '0.5';
}



function checkFieldsP2() {
    let tousLesChampsSontRemplis2 = true;

    for (const champ of inputFieldsP2) {
        if (!champ.value) {
        tousLesChampsSontRemplis2 = false;
        break;
        }
    }

    suivantBtn2.disabled = !tousLesChampsSontRemplis2;
    suivantBtn2.style.opacity = tousLesChampsSontRemplis2 ? '1' : '0.5';
}




function checkFieldsP3() {
    let tousLesChampsSontRemplis3 = true;

    for (const champ of inputFieldsP3) {
        if (!champ.value) {
        tousLesChampsSontRemplis3 = false;
        break;
        }
    }

    suivantBtn3.disabled = !tousLesChampsSontRemplis3;
    suivantBtn3.style.opacity = tousLesChampsSontRemplis3 ? '1' : '0.5';
}



for (const champ of inputFieldsP1) {
  champ.addEventListener('input', checkFieldsP1);
}

for (const champ of inputFieldsP2) {
    champ.addEventListener('input', checkFieldsP2);
}

for (const champ of inputFieldsP3) {
    champ.addEventListener('input', checkFieldsP3);
}

suivantBtn1.addEventListener('click', function() {
    if (suivantBtn1.disabled) {
    alert("Veuillez remplir tous les champs du formulaire avant de passer à l'étape suivante.");
    } else {
        gros_div.style.transform = 'translateX(-25%)';
        Page2.style.display = 'block';
        setTimeout(function() {
            Page1.style.display = 'none';
            gros_div.style.marginLeft='100%';
        }, 800);
    }
});

suivantBtn2.addEventListener('click', function() {
    if (suivantBtn2.disabled) {
    alert("Veuillez remplir tous les champs du formulaire avant de passer à l'étape suivante.");
    } else {
        gros_div.style.transform = 'translateX(-50%)';
        Page4.style.display = 'block';
        setTimeout(function() {
            Page2.style.display = 'none';
            gros_div.style.marginLeft='200%';
        }, 800);
    }
});

/* suivantBtn3.addEventListener('click', function() {
    if (suivantBtn3.disabled) {
    alert("Veuillez remplir tous les champs du formulaire avant de passer à l'étape suivante.");
    } else {
        gros_div.style.transform = 'translateX(-75%)';
        Page4.style.display = 'block';
        setTimeout(function() {
            Page3.style.display = 'none';
            gros_div.style.marginLeft='300%';
        }, 800);
    }
}); */


checkFieldsP1();
checkFieldsP2();
checkFieldsP3();





const retour1 = document.getElementById("retour1");
const retour2 = document.getElementById("retour2");
const retour3 = document.getElementById("retour3");










retour1.addEventListener("click", function () {
    Page1.style.display = 'block';
    gros_div.style.marginLeft='0%';
    gros_div.style.transform = "translateX(0%)";
    setTimeout(function() {
        Page2.style.display = 'none';
    }, 800);
});

retour2.addEventListener("click", function () {
    Page2.style.display = 'block';
    gros_div.style.marginLeft='100%';
    gros_div.style.transform = "translateX(-25%)";
    setTimeout(function() {
        Page3.style.display = 'none';
    }, 800);});

retour3.addEventListener("click", function () {
    Page3.style.display = 'block';
    gros_div.style.marginLeft='200%';
    gros_div.style.transform = "translateX(-50%)";
    setTimeout(function() {
        Page4.style.display = 'none';
    }, 800);});





/* Fin de Partie */


/* ajout des photos */

document.getElementById('photo').addEventListener('change', function (event) {
    const imageContainer = document.getElementById('liste_img_ajlog');
    
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.type.startsWith('image/')) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        imageContainer.appendChild(img);
      }
    }
});
  





/* document.getElementById('photo').addEventListener('change', function (event) {
    const imageContainer = document.getElementById('liste_img_ajlog');
    
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file.type.startsWith('image/')) {
        let img =  `<input type='radio' name='couverture'/><label class="btn_choix_ajlog"><img src=${URL.createObjectURL(file)}></label>`
        imageContainer.innerHTML += img
      }
    }
}); */
  
/*

<input type="radio" id="type2" name="type" value="Appartement" />
<label for="type2" class="btn_choix_ajlog">Appartement</label>
                            */




    /* calcul des prix */


  function calculerPrix() {
    const inputElement = document.getElementById("prix");
    let prix = parseInt(inputElement.value);

    if (prix > 9999) {
        prix = 9999;
    }

    if (isNaN(prix)) {
        prix = 0;
    }

    const fraisService = prix / 10;
    const prixTotal = prix + fraisService;
    const prixTotalSem = parseInt(prixTotal * 7);

    document.getElementById("prixDeBase").textContent = prix + " €";
    document.getElementById("fraisService").textContent = fraisService + " €";
    document.getElementById("prixTotal").textContent = prixTotal + " €";
    document.getElementById("prixTotalSem").textContent = prixTotalSem + " €";
}

window.onload = function () {
    document.getElementById("prix").value = '0';
    calculerPrix();
};

document.getElementById('prix').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 9999) {
        this.value = "9999";
    }
    if (parseInt(this.value) < 0) {
        this.value = "0";
    }
    this.value = parseInt(this.value).toString();
});





/* Bouton Momo */


function afficherPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
   }
   
   function cacherPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
   }
   
   function confirmerRefus() {
    cacherPopup();
   }

   