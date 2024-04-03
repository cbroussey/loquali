

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

function validMdp(elem) {
    if (elem.value.length < 50 && elem.value.length>0){
        document.getElementById('Psw1').style.borderColor = "#1D4C77";

    } else {
        document.getElementById('Psw1').style.borderColor = 'red';
    }

}

function sameMdp(elem) {
    if (elem.value == document.getElementById('passwordInput').value){
        document.getElementById('Psw2').style.borderColor = "#1D4C77";
        console.log("caca")
    } else {
        document.getElementById('Psw2').style.borderColor = "red"; 
    }}

document.getElementById('nom').addEventListener('input', function(){
    rectifLongTxtCompte(this);
});

document.getElementById('prenom').addEventListener('input', function(){
    rectifLongTxtCompte(this);
});

document.getElementById('email').addEventListener('input', function(){
    isMail(this);
});

document.getElementById('passwordInput').addEventListener('input', function(){
    validMdp(this);
});

document.getElementById('passwordInput2').addEventListener('input', function(){
    sameMdp(this);
});


