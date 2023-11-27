/* function modifierInfos(button, champId) {
    var champInfos = document.getElementById(champId);

    if (button.innerHTML === '<img src="asset/icons/blanc/repas.svg" alt="modifier">') {
        // Transformation en champ de texte
        champInfos.innerHTML = '<input type="text" id="nouveauNom" value="' + champInfos.innerHTML + '">';
        button.innerHTML = "Enregistrer";
    } else {
        // Enregistrement du nouveau nom
        var nouveauNom = document.getElementById("nouveauNom").value;
        // Ici, tu pourrais envoyer le nouveau nom côté serveur avec une requête AJAX en utilisant PHP, mais pour l'exemple, on va le changer directement en JavaScript
        champInfos.innerHTML = nouveauNom;
        button.innerHTML = '<img src="asset/icons/blanc/repas.svg" alt="modifier">';

    }
}
 */




function changementBouton (){
    var element = document.querySelector(".modifBtn");
    element.classList.add("active");
    var element2 = document.querySelector(".testBouton");
    element2.classList.add("active");
    var element3 = document.querySelector(".descriptionModif");
    element3.classList.add("active");
    var element4 = document.querySelector(".descriptionCompte");
    element4.classList.add("active");
}

document.querySelector(".testBouton").addEventListener("click", changementBouton);