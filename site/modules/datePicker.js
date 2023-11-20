// Ce qu'il reste à faire :
// - Un genre de drag des dates de début/fin plutôt que clic gauche/droit qui est un peu moins intuitif
// - Limitations de date de début et de fin (et durée max/mois min)
// - Un context menu custom
const monthNames =  ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

class datePicker {
    constructor(id, start, end, multi = true) {
        this.DP = document.getElementById(id);
        this.start = start;
        if (start.getTime() ==  end.getTime()) this.end = new Date(year, month, this.start.getDate()+1);
        else this.end = end;
        this.month = start.getMonth();
        this.year = start.getFullYear();
        this.DP.querySelector(".DPprev").addEventListener('click', () => {
            this.month--;
            if (this.month < 0) {
                this.month = 11;
                this.year--;
            }
            this.display()
        });
        this.DP.querySelector(".DPnext").addEventListener('click', () => {
            this.month++;
            if (this.month > 11) {
                this.month = 0;
                this.year++;
            }
            this.display()
        }); 
    }

    display() {
        if (this.start > this.end) {
            let temp = this.start
            this.start = this.end
            this.end = temp
            if (this.moving == 1) this.moving = 2
            else if (this.moving == 2) this.moving = 1
        }
        this.DP.querySelector('.DPmonth').innerHTML = monthNames[this.month] + ' ' + this.year;
        let DPstart = new Date(this.year, this.month, 0).getDay();
        let daysInMonth = new Date(this.year, this.month+1, 0).getDate() /*- DPstart*/;
        let done = false;
        let i = 1;
        let html = ""
        while (!done) {
            html += "<tr>";
            for (let j = 0; j < 7; j++) {
                html += "<td ";
                if (DPstart || done) {
                    html += "class='DPout'><div></div></td>";
                    DPstart--;
                }
                else {
                    html += "<td class='DPcurrent'><div></div><a>" + i + "</a></td>";
                    i++;
                    if (i%daysInMonth == 1) done = true;
                }
                /*
                if (i < DPstart) {
                    html += "<td class='DPout'>" + (daysPrevMonth + i + 1) + "</td>";
                } else if (i < DPstart + new Date(year, month + 1, 0).getDate()) {
                    html += "<td class='DPcurrent'>" + (i - DPstart + 1) + "</td>";
                } else {
                    html += "<td class='DPout'>" + (i - DPstart - new Date(year, month + 1, 0).getDate() + 1) + "</td>";
                }
                */
            }
            html += "</tr>"
        }
        this.DP.querySelector("table > tbody").innerHTML = html;
        this.DP.querySelectorAll(".DPcurrent").forEach(element => {
            element.addEventListener('mousedown', () => {
                let selDate = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML))
                if (this.start.toDateString() == selDate.toDateString() || selDate < this.start) {
                    this.start = selDate
                    this.moving = 1
                } else if (this.end.toDateString() == selDate.toDateString() || selDate > this.end) {
                    this.end = selDate
                    this.moving = 2
                } else {
                    console.log("Here should be a custom context menu to ask if start date or end date")
                }
                this.display()
            })
            /*
            element.addEventListener("contextmenu", () => {
                if (this.end.toDateString() != new Date(this.year, this.month, element.querySelector("a").innerHTML).toDateString()) {
                    this.start = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML))
                    this.display()
                }
            })
            */
            element.addEventListener("mousemove", () => {
                if (this.moving == 1) {

                    this.start = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML));
                    if (this.start.toDateString() == this.end.toDateString()) this.end = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML)+1);
                } else if (this.moving == 2) {
                    this.end = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML));
                    if (this.end.toDateString() == this.start.toDateString()) this.start = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML)-1);
                }
                this.display()
            })
            element.addEventListener("mouseup", () => {
                this.moving = 0
            })
        });
        let current = this.DP.querySelectorAll(".DPcurrent")
        if (this.month == this.start.getMonth() && this.year == this.start.getFullYear() ) {
            current[this.start.getDate()-1].classList.add("DPselected")
        }
        if (this.month == this.end.getMonth() && this.year == this.end.getFullYear()) {
            current[this.end.getDate()-1].classList.add("DPselected")
        }
        DPstart = new Date(this.year, this.month, 0).getDay();
        for (let i = 0; i < current.length; i++) {
            if (
                (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) >= this.start)
                && (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) <= this.end)
            ) {
                current[i].querySelector("div").classList.add('DPsel');
                if ((new Date(this.year, this.month, i+1).toDateString() == this.start.toDateString()) || ((i+DPstart)%7 == 0 && i != 0))
                    current[i].querySelector("div").classList.add('DPselS');
                if ((new Date(this.year, this.month, i+1).toDateString() == this.end.toDateString()) || ((i+DPstart)%7 == 6 && i != daysInMonth-1))
                    current[i].querySelector("div").classList.add('DPselE');
            }
        } 
    }
}

function toggleDP(id, caller) {
    let DP = document.getElementById(id);
    if (DP.style.visibility == "visible"){
        DP.style.visibility = "collapse";
        DP.style.opacity = 0;
    }
    else {
        DP.style.top = `calc(${caller.offsetHeight}px + 1em)`
        DP.style.left = `${caller.offsetLeft}px`
        DP.style.visibility = "visible"
        DP.style.opacity = 1
    }
}

let datePickers = {};

function DPapply() {
    let temp = document.getElementsByClassName("datePicker")
    for (let element of temp) {
        element.oncontextmenu = () => { return false; }
        element.innerHTML = '<div class="DPheader"><button class="DPprev">&lt;</button><div class="DPmonth"></div><button class="DPnext">&gt;</button></div><table><thead><th>Lun.</th><th>Mar.</th><th>Mer.</th><th>Jeu.</th><th>Ven.</th><th>Sam.</th><th>Dim.</th></thead><tbody></tbody></table>'
        datePickers[element.id] = new datePicker(element.id, new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()), new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()+1))
        datePickers[element.id].display()
    };
}

document.addEventListener("DOMContentLoaded", (event) => {
    DPapply();
});

let link = document.createElement('link');
link.rel = 'stylesheet';
link.type = 'text/css';
link.href = 'modules/datePicker.css';
document.getElementsByTagName('HEAD')[0].appendChild(link);
