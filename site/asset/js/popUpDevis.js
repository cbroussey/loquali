function afficherPopup() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup').style.display = 'block';
}

function cacherPopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup').style.display = 'none';
}

async function supprimerRers(idlog, idresrvation){
    await fetch(`./supprimerDevis.php?id_logement=${idlog}&idreservation=${idresrvation}`);
}


function confirmerRefus() {
    supprimerRers(document.getElementById("idlog").innerHTML, document.getElementById("id_reservation").innerHTML)
    .then(() => {
        cacherPopup();
    })
    .catch(error => {
        console.error('Une erreur s\'est produite : ', error);
    });
}
