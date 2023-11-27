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
    var element = document.getElementById("modificationDescription");
    element.classList.add("active");
    var element2 = document.getElementById("testBouton");
    element2.classList.add("active");
    var element3 = document.getElementById("description");
    element3.classList.add("active");
    var element4 = document.getElementById("descriptionCompte");
    element4.classList.add("active");


}

document.getElementById("testBouton").addEventListener("click", changementBouton);