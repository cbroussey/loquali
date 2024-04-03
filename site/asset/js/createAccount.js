function inputClipping(value){
    return value.replace(/[^a-zA-ZÀ-ÿ -]/g, '');
}

function surnameFormat(value) {
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
}

//vérification de la conformité du nom
const nom = document.getElementById("nom");

nom.addEventListener('input', function() {
    this.value = inputClipping(this.value).toUpperCase();
});

//vérification de la conformité du prénom
const prenom = document.getElementById("prenom");

prenom.addEventListener('input', function() {
    this.value = surnameFormat(inputClipping(this.value));
});

//visibilité des mots de passe
const eyesPasswordVisible = document.getElementById("eyesPasswordVisible");
const eyesPasswordVisible2 = document.getElementById("eyesPasswordVisible2");
const passwordInput = document.getElementById("passwordInput");
const passwordInput2 = document.getElementById("passwordInput2");
var isClosed = false;
var isClosed2 = false;

eyesPasswordVisible.addEventListener('click', function() {
    if (!isClosed) {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-closed.svg';
        passwordInput.type = "text";
        isClosed = true;
    } else {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-open.svg';
        isClosed = false;
        passwordInput.type = "password";
    }
});

eyesPasswordVisible2.addEventListener('click', function() {
    if (!isClosed2) {
        eyesPasswordVisible2.src = 'asset/icons/bleu/eye-closed.svg';
        passwordInput2.type = "text";
        isClosed2 = true;
    } else {
        eyesPasswordVisible2.src = 'asset/icons/bleu/eye-open.svg';
        isClosed2 = false;
        passwordInput2.type = "password";
    }
});

//Restriction saisie


// correction pour 50 char max


function rectifLongTxtCompte(elem) {
    if (elem.value.length < 50 && elem.value.length>0){
        elem.style.borderColor = "#1D4C77";

    } else {
        elem.style.borderColor = 'red';
    }

}

function isMail(elem) {
    var regexEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,10}$/;
    if (regexEmail.test(elem.value)){
        elem.style.borderColor = "#1D4C77";
    } else {
        elem.style.borderColor = "red";
    }
}


function isTel(elem) {
    var regexTel = /^(0|\+33|0033)[1-9]([-. ]?[0-9]{2}){4}$/;
    if (regexTel.test(elem.value)){
        elem.style.borderColor = "#1D4C77";
    } else {
        elem.style.borderColor = "red";
    }
}

document.getElementById('nom').addEventListener('input', function(){
    rectifLongTxtCompte(this);
});

document.getElementById('prenom').addEventListener('input', function(){
    rectifLongTxtCompte(this);
});

document.getElementById('adresse_mail').addEventListener('input', function(){
    isMail(this);
});

document.getElementById('numero').addEventListener('input', function(){
    isTel(this);
});

document.getElementById('adressePersonne').addEventListener('input', function(){
    rectifLongTxtCompte(this);
});
