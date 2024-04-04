let disabledDates = []

let startDate = ""
let endDate = ""

function date2date(date) {
    date = date.split("/")
    date = [date[2], date[1], date[0]]
    date = date.join("-")
    return date
}

async function recupDatesBloquees(idlog) {
    let data = await fetch(`./dateBlockDevis.php?id_logement=${idlog}`)
    data = await data.json()
    console.log(data)
    return data
}

async function recupDate(idlog, datedeb, datefin) {
    console.log("zfzecf")
    console.log(`./calculPrix.php?id_logement=${idlog}&date_debut=${date2date(datedeb)}&date_fin=${date2date(datefin)}`)
    console.log("fdp")
    let data = await fetch(`./calculPrix.php?id_logement=${idlog}&date_debut=${date2date(datedeb)}&date_fin=${date2date(datefin)}`)
    data = await data.json()
    return data
  
}




function setDatesBloquees() {

    recupDatesBloquees(document.getElementById("idlog").innerHTML)
        .then((datesBloquees) => {

            datesBloquees.forEach(date => {

                //date = date["jour"].split("-")
                //disabledDates.push(date[2] + "/" + date[1] + "/" + date[0])
                disabledDates.push(date["jour"])
                datePickers["DP2"].off.push(date["jour"])


            });

            datePickers["DP2"].setMinDate();
            document.getElementById("DP2").addEventListener("DPchanged", e => {
                
                document.querySelector("#dates2 > p").innerText = datePickers["DP2"].start.toLocaleDateString() + " - " + datePickers["DP2"].end.toLocaleDateString()
               
                recupDate(document.getElementById("idlog").innerText, datePickers["DP2"].start.toLocaleDateString(), datePickers["DP2"].end.toLocaleDateString()).then((v) => {
                    try { document.querySelectorAll(".info_prix > .row > a > .value")[0].value = v["prix_ht"] }
                    catch { document.querySelectorAll(".info_prix > .row > .value")[0].value = v["prix_ht"] }
                    document.querySelectorAll(".info_prix > .row > .label")[0].innerHTML = v["nbjours"] -1  + " nuits"
                    document.querySelectorAll(".info_prix > .row > .value")[0].innerHTML = v["prix_ht"] * 0.10 + ".00 €";
                    var prixHT = v["prix_ht"];
                    var montantTotal = prixHT * 1.10;
                    document.querySelectorAll(".info_prix > .row > a > .value")[1].value = montantTotal.toFixed(2);
                    document.getElementById('prix').value = v["prix_ht"]
                    document.getElementById('nbJours').value = v["nbjours"]
                   
                
                })
                document.getElementById('start-date').value = datePickers["DP2"].start.toLocaleDateString();
                document.getElementById('end-date').value = datePickers["DP2"].end.toLocaleDateString();
                
            })

            document.getElementById("DP2").dispatchEvent(new CustomEvent("DPchanged",{detail:{id: this.DP},bubbles:true}));

        })
        .catch((error) => {

            console.error("Erreur lors de la récupération des dates bloquées:", error);
        });


}


document.addEventListener("DOMContentLoaded", () => {
    setDatesBloquees();

});


