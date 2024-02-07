document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.container input');

    checkboxes.forEach(function (checkbox, index) {
        checkbox.addEventListener('change', function () {
            const starImg = this.parentElement.querySelector('.star-img');
            if (this.checked) {
                // Si la case est cochée, cocher les cases précédentes
                for (let i = 0; i <= index; i++) {
                    checkboxes[i].checked = true;
                    checkboxes[i].parentElement.querySelector('.star-img').src = '../siteLinks/asset/icons/bleu/star.svg';
                }
            } else {
                // Si la case est décochée, décocher les cases suivantes
                for (let i = checkboxes.length - 1; i > index; i--) {
                    checkboxes[i].checked = false;
                    checkboxes[i].parentElement.querySelector('.star-img').src = '../siteLinks/asset/icons/blanc/star.svg';
                }
            }
        });
    });
});