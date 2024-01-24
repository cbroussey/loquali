async function recupDate(idlog, datedeb, datefin){
    let data = await fetch(`./calculPrix.php?id_logement=${idlog}&date_debut=${datedeb}&date_fin=${datefin}`)
    data = await data.json()
    return data
}

function setPrix() {
    recupDate(document.getElementById("idlog").innerHTML, document.getElementById("start").value, document.getElementById("end").value).then((v) => {
        document.querySelectorAll(".info_prix > .row > .value")[0].innerHTML = v["prix_ht"] + " €"
        document.querySelectorAll(".info_prix > .row > .label")[0].innerHTML = v["nbjours"] + " nuits"
        document.querySelectorAll(".info_prix > .row > .value")[2].innerHTML = v["prix_ht"] *0.10 + ".00 €";    
        var prixHT = v["prix_ht"];
    var montantTotal = prixHT * 1.10 + 29.96;

document.querySelectorAll(".info_prix > .row > .value")[3].value = montantTotal.toFixed(2) + "€";

    })

}

document.addEventListener("DOMContentLoaded", () => {
    setPrix()
    document.getElementById("start").addEventListener("change", () => {
        setPrix()
    })
    document.getElementById("end").addEventListener("change", () => {
        setPrix()
    })
})