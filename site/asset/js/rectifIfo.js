

// correction pour 50 char max


function rectifLongTxtCompte(elem) {
    if (elem.value.length < 50 && elem.value.length>0){
        elem.style.borderColor = "#1D4C77";

    } else {
        elem.style.borderColor = 'red';
    }

}

function isMail(elem) {
    var regexEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    if (regexEmail.test(elem.value)){
        elem.style.borderColor = "#1D4C77";
    } else {
        elem.style.borderColor = "red";
    }
}


function isTel(elem) {
    var regexTel = /^(0|\+33|0033)[1-9]([-. ]?[0-9]{2}){4}$/;
    elem.value = elem.value.replace(/[^0-9+]/g, '');
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
