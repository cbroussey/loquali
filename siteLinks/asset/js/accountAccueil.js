
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