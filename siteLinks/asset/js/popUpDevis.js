function afficherPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
}

function cacherPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
}

function confirmerRefus() {
    
    cacherPopup();
}


