/*
document.addEventListener("DOMContentLoaded", () => {
    //document.body.innerHTML = "<table><thead><tr><th>Nom de la clé API</th><th>Privilèges</th><th>Accès Calendrier</th><th>Mise Indispo</th><th></th><th>Mise Dispo</th></tr></thead><tbody><tr><td></td><td><input type='checkbox' disabled='' name='p'></td><td><input type='checkbox' name='c'></td><td><input type='checkbox' name='i'></td><td class='separBar'></td><td><input type='checkbox' name='d'></td></tr></tbody></table>"
    let perms = document.querySelectorAll("#apiSection > * > table > tbody > tr > td > input[type='checkbox']:not([disabled])")
    perms.forEach(el => {
        if (el.name[el.name.length-1] == "d") {
            el.addEventListener("click", e => { 
                console.log("dispo")
                if (Array.from(perms).filter(e => e.checked).length > 1) {

                }
            });
        } else {
            el.addEventListener("click", e => { 
                console.log("reste")
            });
        }
        
    })
    
})
*/

function openAModal(name) {
    document.getElementById(name).style.display = 'block';
}

function closeAModal(name) {
    document.getElementById(name).style.display = 'none';
}