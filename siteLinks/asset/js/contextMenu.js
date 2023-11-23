class contextMenu {
    ref = "";
    constructor(id, filter = false, elems = []) {
        this.CM = document.getElementById(id);
        this.filter = filter
        if (!elems.length) {
            this.elems = this.CM.innerHTML.split("|");
        } else {
            this.elems = elems;
        }
    }
    display(filter = "") {
        let html = ""
        this.elems.forEach((e) => {
            if (this.ref == "" || e.toLowerCase().includes(filter.toLowerCase())) {
                //console.log(this.ref)
                html += `<tr><td>${e}</td></tr>`
            }
        })
        this.CM.querySelector("table > tbody").innerHTML = html
        this.CM.querySelectorAll("table > tbody > tr > td").forEach((e) => {
            e.addEventListener("click", () => {
                this.ref.value = e.innerHTML
                this.ref.innerHTML = e.innerHTML
                this.display((this.ref.tagName == "INPUT" && !this.ref.readOnly) ? this.ref.value : "");
                if (this.ref.tagName != "INPUT" && e.parentNode.tagName != "TR") toggleCM(this.CM.id, this)
            })
        })
    }
}

function toggleCM(id, caller, x = -1, y = -1) {
    let CM = document.getElementById(id);
    contextMenus[id].ref = caller;
    caller.oninput = () => { contextMenus[id].display(caller.value); }
    if (caller.onclick) {
        caller.onfocus = caller.onclick
        caller.onblur = caller.onclick
        caller.onclick = ""
    }
    if (x == -1) x = `${caller.offsetLeft}`;
    if (y == -1) y = `.5em + ${caller.offsetTop}px + ${caller.offsetHeight}`;
    if (CM.style.visibility == "visible"){
        CM.style.visibility = "collapse";
        CM.style.opacity = 0;
    }
    else {
        CM.style.top = `calc(${y}px)`
        CM.style.left = `${x}px`
        CM.style.width = `${caller.offsetWidth}px`
        CM.style.visibility = "visible"
        CM.style.opacity = 1
    }
}

let contextMenus = {}

function CMapply() {
    let temp = document.getElementsByClassName("contextMenu")
    for (let element of temp) {
        element.oncontextmenu = () => { toggleCM(element.id); }
        contextMenus[element.id] = new contextMenu(element.id);
        element.innerHTML = '<table><tbody></tbody></table>'
        contextMenus[element.id].display()
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
