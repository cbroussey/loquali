cbSaved = []

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll("#CM3 > table > tbody > tr > td > input").forEach((e) => {
        cbSaved.push(e.value)
    })
    document.getElementById("cardNumber").addEventListener("input", () => {
        if (cbSaved.includes(document.getElementById("cardNumber").value)) {
            document.querySelector("#paymentSaved > input").value = document.getElementById("cardNumber").value
        } else {
            document.querySelector("#paymentSaved > input").value = "Nouveau type de paiement"
        }
    })
})