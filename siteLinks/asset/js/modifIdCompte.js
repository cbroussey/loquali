function modifierPieceIdentite(button) {
    var champPieceIdentiteText = document.getElementById("pieceIdentiteText");
    var inputPieceIdentite = document.getElementById("pieceIdentiteInput");

    if (button.innerHTML === "Modifier") {
        // Affiche le champ de type file
        inputPieceIdentite.style.display = "block";
        button.innerHTML = "Enregistrer";
        // Cache le texte "Non fournie"
        champPieceIdentiteText.style.display = "none";
    } else {
        // Enregistre la nouvelle pièce d'identité
        // Ici, tu devrais gérer l'upload côté serveur avec PHP
        // L'exemple suivant ne fonctionne que côté client et ne sauvegarde pas l'image
        var nouvellePieceIdentite = inputPieceIdentite.files[0];
        if (nouvellePieceIdentite) {
            // Affiche le nom de l'image à la place de "Non fournie"
            champPieceIdentiteText.innerHTML = nouvellePieceIdentite.name;
        }

        // Cache le champ de type file
        inputPieceIdentite.style.display = "none";
        button.innerHTML = "Modifier";
        // Réaffiche le texte "Non fournie"
        champPieceIdentiteText.style.display = "block";
    }
}