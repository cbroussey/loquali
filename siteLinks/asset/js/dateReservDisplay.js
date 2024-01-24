// Ce qu'il reste à faire : 

// - Un genre de drag des dates de début/fin plutôt que clic gauche/droit qui est un peu moins intuitif 

// - Limitations de date de début et de fin (et durée max/mois min) 

// - Un context menu custom 

const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]; 

  

class dateDisplay { 

    constructor(id, start, end, multi = true) { 

        this.DP = document.getElementById(id); 

        this.start = start; 

        if (start.getTime() == end.getTime()) this.end = new Date(year, month, this.start.getDate() + 1); 

        else this.end = end; 

        this.month = start.getMonth(); 

        this.year = start.getFullYear(); 

        this.DP.querySelector(".DPprev").addEventListener('click', () => { 

            this.month--; 

            if (this.month < 0) { 

                this.month = 11; 

                this.year--; 

            } 

            this.display(); 

        }); 

        this.DP.querySelector(".DPnext").addEventListener('click', () => { 

            this.month++; 

            if (this.month > 11) { 

                this.month = 0; 

                this.year++; 

            } 

            this.display(); 

        }); 

    } 

  

    display() { 

        if (this.start > this.end) { 

            let temp = this.start; 

            this.start = this.end; 

            this.end = temp; 

            if (this.moving == 1) this.moving = 2; 

            else if (this.moving == 2) this.moving = 1; 

        } 

        this.DP.querySelector('.DPmonth').innerHTML = monthNames[this.month] + ' ' + this.year; 

        let DPstart = new Date(this.year, this.month, 0).getDay(); 

        let daysInMonth = new Date(this.year, this.month + 1, 0).getDate() /*- DPstart*/; 

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

                } else { 

                    let currentDay = new Date(this.year, this.month, i - 1); 

                    dateIndispo.find(date => date.toDateString() == currentDay.toDateString()); 

                    if (dateIndispo.find(date => date.toDateString() == currentDay.toDateString())) { 

                        html += "<td class='DPcurrent DPundispo'><div></div><a>" + i + "</a></td>"; 

                    } else { 

                        html += "<td class='DPcurrent DPdispo'><div></div><a>" + i + "</a></td>"; 

                    } 

                    i++; 

                    if (i % daysInMonth == 1) done = true; 

                } 

            } 

            html += "</tr>" 

        } 

        this.DP.querySelector("table > tbody").innerHTML = html; 

  

        let current = this.DP.querySelectorAll(".DPcurrent") 

        DPstart = new Date(this.year, this.month, 0).getDay(); 

        for (let i = 0; i < current.length; i++) { 

            if ( 

                (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) >= this.start) && 

                (new Date(this.year, this.month, current[i].querySelector("a").innerHTML) <= this.end) 

            ) { 

                current[i].querySelector("div").classList.add('DPsel'); 

                if ((new Date(this.year, this.month, i + 1).toDateString() == this.start.toDateString()) || ((i + DPstart) % 7 == 0 && i != 0)) 

                    current[i].querySelector("div").classList.add('DPselS'); 

                if ((new Date(this.year, this.month, i + 1).toDateString() == this.end.toDateString()) || ((i + DPstart) % 7 == 6 && i != daysInMonth - 1)) 

                    current[i].querySelector("div").classList.add('DPselE'); 

            } 

        } 

    } 

} 

  

function toggleDP(id, caller) { 
    console.log("click");
    let DP = document.getElementById(id); 

    if (DP.style.visibility == "visible") { 

        DP.style.visibility = "collapse"; 

        DP.style.opacity = 0; 

    } else { 

        DP.style.top = `calc(${caller.offsetHeight}px + 1em)` 

        DP.style.left = `${caller.offsetLeft}px` 

        DP.style.visibility = "visible" 

        DP.style.opacity = 1 

    } 

} 

  

let dateDisplays = {}; 

  

function DPapply() { 

    let temp = document.getElementsByClassName("dateDisplay") 

    for (let element of temp) { 

        element.oncontextmenu = () => { return false; } 

        element.innerHTML = '<div class="DPheader"><button class="DPprev">&lt;</button><div class="DPmonth"></div><button class="DPnext">&gt;</button></div><table><thead><th>Lun.</th><th>Mar.</th><th>Mer.</th><th>Jeu.</th><th>Ven.</th><th>Sam.</th><th>Dim.</th></thead><tbody></tbody></table>' 

        dateDisplays[element.id] = new dateDisplay(element.id, new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()), new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1)) 

        dateDisplays[element.id].display() 

    }; 

} 

  

document.addEventListener("DOMContentLoaded", (event) => { 

    DPapply(); 

}); 

  

let link = document.createElement('link'); 

link.rel = 'stylesheet'; 

link.type = 'text/css'; 

link.href = '../css/datePicker.css'; 

document.getElementsByTagName('HEAD')[0].appendChild(link); 