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


document.getElementById('photo').addEventListener('change', function (event) {
    document.getElementById("profileForm").submit();

});