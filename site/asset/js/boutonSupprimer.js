function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}






function openModal3() {
    document.getElementById('myModal3').style.display = 'block';
}

function closeModal3() {
    document.getElementById('myModal3').style.display = 'none';
}

var modal = document.getElementById('myModal4');


function openModal4() {
    var modal = document.getElementById('myModal4');

    modal.style.display = 'block';

}

function closeModal4() {
    var modal = document.getElementById('myModal4');

    modal.style.display = 'none';
}



function deleteLogement() {
     
    alert('Logement supprimé avec succès !');
    closeModal();
}

var testmod = document.getElementsByClassName('testmodal');

console.log(testmod)

var testmodquery = document.querySelectorAll('.testmodal');

testmodquery.forEach(element => {
    element.addEventListener('click', function() {
        openModal4()
        
        document.addEventListener('click', function(event) {
            console.log(event.target)
            if (event.target != modal.querySelector('.modal-content') && event.target != element.querySelector('img') && modal.querySelector('p') && element.querySelector('.boutons_choix')) {
                console.log("pipi")
                closeModal4()
            }
        })
    })
    
});