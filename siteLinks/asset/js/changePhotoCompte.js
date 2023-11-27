function changeProfilePhoto(event) {
    const fileInput = event.target;
    const files = fileInput.files;

    if (files.length > 0) {
        const newPhoto = files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('photoProfil').src = e.target.result;
            // Vous pouvez ajouter ici le code pour envoyer la nouvelle photo au serveur si nécessaire.
        };

        reader.readAsDataURL(newPhoto);
    }
}