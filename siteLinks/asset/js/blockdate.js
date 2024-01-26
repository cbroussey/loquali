let disabledDates = []

let startDate = ""
let endDate = ""

async function recupDatesBloquees(idlog){
    let data = await fetch(`./dateBlockDevis.php?id_logement=${idlog}`)
    data = await data.json()
    return data
}


function setDatesBloquees() {
    
    recupDatesBloquees(document.getElementById("idlog").innerHTML)
        .then((datesBloquees) => {
          
            datesBloquees.forEach(date => {
                console.log(date);
                //date = date["jour"].split("-")
                //disabledDates.push(date[2] + "/" + date[1] + "/" + date[0])
                disabledDates.push(date["jour"])
            });
           
        })
        .catch((error) => {
          
            console.error("Erreur lors de la récupération des dates bloquées:", error);
});


}


document.addEventListener("DOMContentLoaded", () => {
    setDatesBloquees();

    const startInput = document.getElementById("start");
    const endInput = document.getElementById("end");

    startInput.addEventListener("change", (e) => {
        handleDateChange2(e.target.value, endInput.value);
    });

    endInput.addEventListener("change", (e) => {
        handleDateChange(startInput.value, e.target.value);
    });

    startDate = startInput.value;
    endDate = endInput.value;

    function handleDateChange(newStart, newEnd) {
         if (!isValidDateRange(newStart, newEnd)) {
            endDate = getAdjustedEndDate(newStart);
            startDate = getAdjustedStartDate(newStart);
            endInput.value = endDate;
            startInput.value = startDate;

            alert("La date de fin a été ajustée.");
        } else if (isDateRangeBlocked(newStart, newEnd)) {
            startInput.value = startDate;
            endInput.value = endDate;
            alert("L'intervalle de dates sélectionné contient des dates indisponibles.");
        } else {
            startDate = newStart;
            endDate = newEnd;
        }
    }

    function handleDateChange2(newStart, newEnd) {
        if (isDateRangeBlocked(newStart, newEnd)) {
            endDate = getAdjustedEndDate(newStart);
            startDate = getAdjustedStartDate(newStart);
            endInput.value = endDate;
            startInput.value = startDate;
            alert("L'intervalle de dates sélectionné contient des dates indisponibles.");
        } else if (!isValidDateRange(newStart, newEnd)) {
            endDate = getAdjustedEndDate(newStart);
            startDate = getAdjustedStartDate(newStart);
            endInput.value = endDate;
            startInput.value = startDate;

            alert("La date de fin a été ajustée.");
        } else {
            startDate = newStart;
            endDate = newEnd;
        }
    }

    function isDateRangeBlocked(start, end) {
        const selectedRange = getDatesBetween(start, end);
        return selectedRange.some(date => disabledDates.includes(date));
    }

    function isValidDateRange(start, end) {
        return new Date(start) <= new Date(end);
    }

    function getAdjustedEndDate(newStart) {
        const proposedEndDate = new Date(newStart);
        proposedEndDate.setDate(proposedEndDate.getDate() + 1);
        return proposedEndDate.toISOString().split('T')[0];
    }

    function getAdjustedStartDate(newStart) {
        const proposedEndDate = new Date(newStart);
        proposedEndDate.setDate(proposedEndDate.getDate() + 0);
        return proposedEndDate.toISOString().split('T')[0];
    }

    function getDatesBetween(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        const dates = [];

        while (startDate <= endDate) {
            dates.push(startDate.toISOString().split('T')[0]);
            startDate.setDate(startDate.getDate() + 1);
        }

        return dates;
    }
});

