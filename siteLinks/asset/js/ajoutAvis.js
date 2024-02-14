document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.container input');

    checkboxes.forEach(function (checkbox, index) {
        checkbox.addEventListener('change', function () {
            const starImg = this.parentElement.querySelector('.star-img');
            if (this.checked) {
                // Si la case est cochée, cocher les cases précédentes
                for (let i = 0; i <= index; i++) {
                    checkboxes[i].checked = true;
                    checkboxes[i].parentElement.querySelector('.star-img').src = 'asset/icons/bleu/star.svg';
                }
            } else {
                // Si la case est décochée, décocher les cases suivantes
                for (let i = checkboxes.length - 1; i > index; i--) {
                    checkboxes[i].checked = false;
                    checkboxes[i].parentElement.querySelector('.star-img').src = 'asset/icons/blanc/star.svg';
                }
            }
        });
    });
});

function openModal2() {
    document.getElementById('myModal2').style.display = 'block';
}

function closeModal2() {
    document.getElementById('myModal2').style.display = 'none';
}

document.getElementById("AjoutAvis").addEventListener("click", function() {
    var formulaire = document.getElementById("ajoutAvisForm");
    if (formulaire.style.display === "none") {
        formulaire.style.display = "block";
    } else {
        formulaire.style.display = "none";
    }
});



