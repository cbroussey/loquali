function inputAlphanumeric(value){
    return value.replace(/[^a-zA-ZÀ-ÿ -]/g, '');
}

/* function inputNumber(value){
    value = value.replace(/\D/g, '').trim();
    if (value.length % 3 === 0 && value.length > 0) {
        value += ' ';
    }
    return value;
}
 */
function surnameFormat(value) {
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
}

function addSpaces(value) {
    return value.replace(/(.{2})/g, '$1 ');
}

const pays = document.getElementById("pays");
const ville = document.getElementById("ville");
//const telephone = document.getElementById("telephone");

pays.addEventListener('input', function() {
    this.value = surnameFormat(inputAlphanumeric(this.value));
});

ville.addEventListener('input', function() {
    this.value = surnameFormat(inputAlphanumeric(this.value));
});

/* telephone.addEventListener('input', function() {
    this.value = addSpaces(inputNumber(this.value));
});

document.addEventListener('keydown', function(event) {
    if ((event.key === 'Delete' || event.key === 'Backspace') && event.target.id === 'telephone') {
        telephone.value = telephone.value.slice(0, -1);
    }
});
 */