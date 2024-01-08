class contextMenu {
    ref = ""; // Référence de l'objet externe à modifier (là où va le texte)
    constructor(id, filter = false, elems = []) {
        this.CM = document.getElementById(id); // Sélection de l'élément de classe .contextMenu
        this.filter = filter // Si on peut filtrer en fonction des caractères déjà entrés
        if (!elems.length) { // Si pas d'éléments passé en paramètres
            this.elems = this.CM.innerHTML.split("|"); // On les extrait directement en splittant par |
            // Utile pour simplifier, plutôt que de tout passer en argument on peut les écrire directement dans le HTML
        } else {
            this.elems = elems;
        }
    }
    display(filter = "") {
        let html = ""
        this.elems.forEach((e) => {
            if (this.ref == "" || e.toLowerCase().includes(filter.toLowerCase())) {
                //console.log(this.ref)
                html += `<tr><td>${e}</td></tr>` // Ajout de l'élément s'il fait parti du filtre précisé ou non
            }
        })
        this.CM.querySelector("table > tbody").innerHTML = html
        this.CM.querySelectorAll("table > tbody > tr > td").forEach((e) => { // Pour toutes les entrées du menu
            e.addEventListener("click", () => {
                this.ref.value = e.innerHTML // Pour être sûr que ça marche dans tous les cas
                this.ref.innerHTML = e.innerHTML
                this.display((this.ref.tagName == "INPUT" && !this.ref.readOnly) ? this.ref.value : "");
                if (this.ref.tagName != "INPUT" && e.parentNode.tagName != "TR")
                    toggleCM(this.CM.id, this) // Permet d'éviter une réouverture du menu en cas d'évènement "blur"
            })
        })
    }
}

function toggleCM(id, caller, x = -1, y = -1) {
    let CM = document.getElementById(id);
    contextMenus[id].ref = caller; // Définition de l'objet externe à modifier
    caller.oninput = () => { contextMenus[id].display(caller.value); } // Pour le filtrage d'éléments (si input non-readonly)
    if (caller.onclick) {
        caller.onfocus = caller.onclick // Permet de toggleCM le même élément pour le montrer
        caller.onblur = caller.onclick // Pareil mais pour le cacher
        caller.onclick = ""
    }
    if (x == -1) x = `${caller.offsetLeft}`; // Position au même niveau que l'élément appelant
    if (y == -1) y = `.5em + ${caller.offsetTop}px + ${caller.offsetHeight}`; // Mais un peu en dessous pour que ça soit visible
    if (CM.style.visibility == "visible"){ // S'il est affiché
        // On le cache
        CM.style.visibility = "collapse";
        CM.style.opacity = 0;
    }
    else { // Sinon
        // On le positionne et on l'affiche
        CM.style.top = `calc(${y}px)`
        CM.style.left = `${x}px`
        CM.style.width = `${caller.offsetWidth}px`
        CM.style.visibility = "visible"
        CM.style.opacity = 1
    }
}

let contextMenus = {} // Liste globale de tous les menus

function CMapply() {
    let temp = document.getElementsByClassName("contextMenu")
    // Pour chaque élément menu
    for (let element of temp) {
        element.oncontextmenu = () => { toggleCM(element.id); }
        contextMenus[element.id] = new contextMenu(element.id); // Définition d'un objet et ajout à la liste globale
        element.innerHTML = '<table><tbody></tbody></table>' // Initialisation du layout
        contextMenus[element.id].display() // Initialisation des valeurs (menu caché)
    };
}

document.addEventListener("DOMContentLoaded", (event) => {
    CMapply();
});

let link = document.createElement('link');
link.rel = 'stylesheet';
link.type = 'text/css';
link.href = 'asset/css/contextMenu.css';
document.getElementsByTagName('HEAD')[0].appendChild(link);
