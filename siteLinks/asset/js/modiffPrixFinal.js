



document.getElementById("prixNuit").addEventListener("input", (event) => {
    const nouvelleValeur = event.target.value;
    
    var montantTotal = nouvelleValeur * 1.10 + 29.96;
    var TVA = nouvelleValeur *0.10;
    document.querySelectorAll(".info_prix > .row > .value")[1].innerHTML = TVA.toFixed(2) + "€";    
    document.querySelectorAll(".info_prix > .row > a > .value")[1].value = montantTotal.toFixed(2);
 });
 