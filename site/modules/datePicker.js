// Ce qu'il reste à faire :
// - Un genre de drag des dates de début/fin plutôt que clic gauche/droit qui est un peu moins intuitif
// - Limitations de date de début et de fin (et durée max/mois min)
// - Un context menu custom

function today(y=0, m=0, d=0) {
    return new Date(new Date().getFullYear()+y, new Date().getMonth()+m, new Date().getDate()+d);
}

function fill0(txt) {
    return `${txt.length%2?"0":""}${txt}`;
}

const monthNames =  ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

class datePicker {
    constructor(id, start, end, dist = 4, off = [], multi = true) {
        this.DP = document.getElementById(id);
        this.dist = dist//*86400000;
        this.start = start;
        if (start >= new Date(end.getFullYear(), end.getMonth(), end.getDate()-this.dist)) this.end = new Date(start.getFullYear(), start.getMonth(), start.getDate()+this.dist)
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
        this.off = off;
        this.multi = multi;
        let ready = false;
        while(!ready) {
            ready = true;
            for (let i = 0; i < this.off.length; i++) {
                if (new Date(this.off[i]) >= this.start && new Date(this.off[i]) <= this.end) {
                    this.start = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate()+1)
                    this.end = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate()+1+this.dist)
                    ready = false;
                    break;
                }
            }
        }
    }

    display() {
        //let temp = new Date(this.end.getTime()-this.dist);
        if (this.start > new Date(this.end.getFullYear(), this.end.getMonth(), this.end.getDate()-this.dist) && this.multi) {
            this.end = new Date(this.start.getFullYear(), this.start.getMonth(), this.start.getDate()+this.dist)
            //temp = this.start
            //this.start = this.end
            //this.end = temp
            if (this.moving == 1) this.moving = 2
            else if (this.moving == 2) this.moving = 1
        }
        if (this.start.getTime() < today().getTime()) {
            this.start = today()
            this.end = today(0, 0, this.dist)
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
                else if (new Date(this.year, this.month, i+1) >= new Date() && !this.off.includes(`${this.year}-${fill0((this.month+1).toString())}-${fill0(i.toString())}`)) {
                    //console.log(`${this.year}-${fill0((this.month+1).toString())}-${fill0(i.toString())}`)
                    html += "class='DPcurrent'><div></div><a>" + i + "</a></td>";
                    i++;
                    if (i%daysInMonth == 1) done = true;
                } else {
                    html += "class='DPno'><div></div><a>" + i + "</a></td>";
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
        this.DP.querySelectorAll(".DPcurrent:not(.DPno)").forEach(element => {
            element.addEventListener('mousedown', () => {
                let selDate = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML))
                if ((this.start.toDateString() == selDate.toDateString() || selDate < this.start || !this.multi)) {
                    this.start = selDate
                    this.moving = 1
                } else if (this.end.toDateString() == selDate.toDateString() || selDate > this.end) {
                    this.end = selDate
                    //console.log(this.end)
                    this.moving = 2
                } else {
                    //console.log("Here should be a custom context menu to ask if start date or end date")
                    this.tmpdist = Math.ceil(Math.abs(selDate - this.start) / (1000 * 60 * 60 * 24));
                    this.moving = 3
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
                let clickedDate = new Date(this.year, this.month, parseInt(element.querySelector("a").innerHTML));
                if (this.moving == 1 /*&& !this.off.includes(`${this.start.getFullYear()}-${fill0((this.start.getMonth()+1).toString())}-${fill0((this.start.getDate()+this.dist).toString())}`)*/) {
                    //console.log(`${this.start.getFullYear()}-${fill0((this.start.getMonth()+1).toString())}-${fill0((this.start.getDate()+this.dist).toString())}`)
                    this.start = clickedDate;
                    if (this.start >= new Date(this.end.getFullYear(), this.end.getMonth(), this.end.getDate()-this.dist)) this.end = new Date(this.start.getFullYear(), this.start.getMonth(), this.start.getDate()+this.dist);
                } else if (this.moving == 2 /*&& this.end >= new Date(new Date().getTime()+86000000*(this.dist))*/ /*&& !this.off.includes(`${this.end.getFullYear()}-${fill0((this.end.getMonth()+1).toString())}-${fill0((this.end.getDate()-this.dist).toString())}`)*/) {
                    this.end = clickedDate;
                    if (this.end <= new Date(this.start.getFullYear(), this.start.getMonth(), this.start.getDate()+this.dist)) this.start = new Date(this.end.getFullYear(), this.end.getMonth(), this.end.getDate()-this.dist);
                } else if (this.moving == 3 && this.multi /*&& (clickedDate < this.start || clickedDate > this.end)*/) {
                    let tmpdist = Math.ceil((clickedDate - this.start) / (1000 * 60 * 60 * 24)) - this.tmpdist;
                    //console.log(tmpdist)
                    this.start = new Date(this.start.getFullYear(), this.start.getMonth(), this.start.getDate()+tmpdist)
                    this.end = new Date(this.end.getFullYear(), this.end.getMonth(), this.end.getDate()+tmpdist)
                }
                this.display()
            })
            element.addEventListener("mouseup", () => {
                this.moving = 0
                for (let i = 0; i < this.off.length; i++) {
                    if (new Date(this.off[i]) >= this.start && new Date(this.off[i]) <= new Date(this.end.getFullYear(), this.end.getMonth(), this.end.getDate()+1)) {
                        alert("Vous ne pouvez pas réserver sur cette période")
                        this.start = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate())
                        this.end = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate()+this.dist)
                        let ready = false;
                        while(!ready) {
                            ready = true;
                            for (let i = 0; i < this.off.length; i++) {
                                if (new Date(this.off[i]) >= this.start && new Date(this.off[i]) <= this.end) {
                                    this.start = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate()+1)
                                    this.end = new Date(new Date(this.off[i]).getFullYear(), new Date(this.off[i]).getMonth(), new Date(this.off[i]).getDate()+1+this.dist)
                                    ready = false;
                                    break;
                                }
                            }
                        }
                        this.display()
                        break;
                    }
                }
            })
        });
        let current = this.DP.querySelectorAll(".DPcurrent, .DPno")
        if (this.month == this.start.getMonth() && this.year == this.start.getFullYear() ) {
            current[this.start.getDate()-1].classList.add("DPselected")
        }
        if (this.month == this.end.getMonth() && this.year == this.end.getFullYear() && this.multi) {
            current[this.end.getDate()-1].classList.add("DPselected")
        }
        DPstart = new Date(this.year, this.month, 0).getDay(); // Jour de la semaine
        for (let i = 0; i < current.length; i++) {
            if (
                (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) >= this.start)
                && (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) <= this.end)
            ) {
                if (this.multi) current[i].querySelector("div").classList.add('DPsel');
                if ((new Date(this.year, this.month, i+1).toDateString() == this.start.toDateString()) || ((i+DPstart)%7 == 0 && i != 0))
                    current[i].querySelector("div").classList.add('DPselS');
                if (((new Date(this.year, this.month, i+1).toDateString() == this.end.toDateString()) || ((i+DPstart)%7 == 6 && i != daysInMonth-1))
                    && this.multi)
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
        datePickers[element.id] = new datePicker(element.id, new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()), new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()))
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
