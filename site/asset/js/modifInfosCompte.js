// function modifierInfos(button, champId) {
//         var champInfos = document.getElementById(champId);

//         if (button.innerHTML === "Modifier") {
//             // Transformation en champ de texte
//             champInfos.innerHTML = '<input type="text" id="nouveauNom" value="' + champInfos.innerHTML + '">';
//             button.innerHTML = "Enregistrer";
//         } else {
//             // Enregistrement du nouveau nom
//             var nouveauNom = document.getElementById("nouveauNom").value;
//             // Ici, tu pourrais envoyer le nouveau nom côté serveur avec une requête AJAX en utilisant PHP, mais pour l'exemple, on va le changer directement en JavaScript
//             champInfos.innerHTML = nouveauNom;
//             button.innerHTML = "Modifier";
//         }
// }

function changementBouton (){
    var element = document.querySelector(".modifBouton");
    element.classList.add("active");
    var element2 = document.querySelector(".modificationsBtn");
    element2.classList.add("active");
    var element3 = document.querySelector(".modifInfos");
    element3.classList.add("active");
    var element4 = document.querySelector(".displayInfos");
    element4.classList.add("active");
}

document.querySelector(".modificationsBtn").addEventListener("click", changementBouton); 





function changementBouton2 (){
    var element = document.querySelector(".modifBouton2");
    element.classList.add("active");
    var element2 = document.querySelector(".modificationsBtn2");
    element2.classList.add("active");
    var element3 = document.querySelector(".modifInfos2");
    element3.classList.add("active");
    var element4 = document.querySelector(".displayInfos2");
    element4.classList.add("active");
}

document.querySelector(".modificationsBtn2").addEventListener("click", changementBouton2);




function changementBouton3 (){
    var element = document.querySelector(".modifBouton3");
    element.classList.add("active");
    var element2 = document.querySelector(".modificationsBtn3");
    element2.classList.add("active");
    var element3 = document.querySelector(".modifInfos3");
    element3.classList.add("active");
    var element4 = document.querySelector(".displayInfos3");
    element4.classList.add("active");
}

document.querySelector(".modificationsBtn3").addEventListener("click", changementBouton3);




function changementBouton4 (){
    var element = document.querySelector(".modifBouton4");
    element.classList.add("active");
    var element2 = document.querySelector(".modificationsBtn4");
    element2.classList.add("active");
    var element3 = document.querySelector(".modifInfos4");
    element3.classList.add("active");
    var element4 = document.querySelector(".displayInfos4");
    element4.classList.add("active");
}

document.querySelector(".modificationsBtn4").addEventListener("click", changementBouton4);




function changementBouton5 (){
    var element = document.querySelector(".modifBouton5");
    element.classList.add("active");
    var element2 = document.querySelector(".modificationsBtn5");
    element2.classList.add("active");
    var element3 = document.querySelector(".modifInfos5");
    element3.classList.add("active");
    var element4 = document.querySelector(".displayInfos5");
    element4.classList.add("active");
}

document.querySelector(".modificationsBtn5").addEventListener("click", changementBouton5);


// Partie pour le changement de photo de profil


function submitForm() {
    document.getElementById("profileForm").submit();
}





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