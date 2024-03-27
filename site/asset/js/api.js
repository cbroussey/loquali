document.addEventListener("DOMContentLoaded", () => {
    document.body.innerHTML = "<table><thead><tr><th>Nom de la clé API</th><th>Privilèges</th><th>Accès Calendrier</th><th>Mise Indispo</th><th></th><th>Mise Dispo</th></tr></thead><tbody><tr><td></td><td><input type='checkbox' disabled='' name='p'></td><td><input type='checkbox' name='c'></td><td><input type='checkbox' name='i'></td><td class='separBar'></td><td><input type='checkbox' name='d'></td></tr></tbody></table>"
    document.querySelectorAll("#apiSection > * > table > tbody > tr > td > input[type='checkbox']").forEach(el => {
        if (el.name[el.name.length-1] == "d") {
            el.addEventListener("click", e => { 
                console.log("dispo")
            });
        } else {
            el.addEventListener("click", e => { 
                console.log("reste")
            });
        }
        
    })
})