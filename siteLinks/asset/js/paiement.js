let cbSaved = {}

function testCBexists() {
    cardnum = document.getElementById("cardNumber").value
    if (
        cardnum in cbSaved
        && cbSaved[cardnum]["crypto"] == document.getElementById("crypto").value
        && cbSaved[cardnum]["valid"] == document.getElementById("expiry").value
        ){
        //document.querySelector("#paymentSaved > input").value = document.getElementById("cardNumber").value
        document.getElementById("paymentSaved").innerHTML = contextMenus["CM3"].elems[Object.keys(cbSaved).indexOf(cardnum)]
    } else {
        document.getElementById("paymentSaved").innerHTML = '<input name="paymentSaved" class="inputImg" onclick="toggleCM(\'CM3\', document.querySelector(\'#paymentSaved\'))" value="Nouveau mode de paiement" readonly=""><img class="cmHideElem" src="asset/img/arrow-down.svg" onclick="toggleCM(\'CM3\', document.querySelector(\'#paymentSaved\'))">'
    }
}

document.addEventListener('DOMContentLoaded', () => {
    let cbs = document.querySelectorAll("#CM3 > table > tbody > tr > td > .inputImg")
    let hiddendata = document.querySelectorAll('#CM3 > table > tbody > tr > td > input[type="hidden"]')
    for (let i = 0; i < cbs.length; i++) {
        cbSaved[cbs[i].value] = {}
        cbSaved[cbs[i].value]["crypto"] = hiddendata[i*2].value
        cbSaved[cbs[i].value]["valid"] = hiddendata[i*2+1].value.split("-").reverse().slice(1, 3)
        cbSaved[cbs[i].value]["valid"][1] = cbSaved[cbs[i].value]["valid"][1].substr(2, 5)
        cbSaved[cbs[i].value]["valid"] = cbSaved[cbs[i].value]["valid"].join("/")
    }
    document.getElementById("cardNumber").addEventListener("input", () => {
        testCBexists()
    })
    document.getElementById("expiry").addEventListener("input", () => {
        testCBexists()
    })
    document.getElementById("crypto").addEventListener("input", () => {
        testCBexists()
    })
    
    document.querySelector("#paymentSaved > .inputImg").addEventListener("change", () => {
        document.getElementById("cardNumber").value = document.querySelector("#paymentSaved > .inputImg").value
        document.getElementById("expiry").value = cbSaved[document.querySelectorAll("#paymentSaved > .inputImg").value]["valid"]
        document.getElementById("crypto").value = cbSaved[document.querySelectorAll("#paymentSaved > .inputImg").value]["crypto"]
    })
    
})