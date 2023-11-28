
//header
const accountDisconnect = document.getElementById("accountDisconnect");
const popUpDeco = document.getElementById("popUpDeco");

console.log("caca");

accountDisconnect.addEventListener('click', function() {
    popUpDeco.style.display = "block";
});

//popup Disconnect
const cancelDisconnect = document.getElementById("cancelDisconnect");

cancelDisconnect.addEventListener('click', function() {
    popUpDeco.style.display = "none";
});

//changement photo de profil
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