function inputAlphanumeric(value){
    return value.replace(/[^a-zA-ZÀ-ÿ -]/g, '');
}

function inputNumber(value){
    return value.replace(/\D/g, '');
}
 
function surnameFormat(value) {
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
}

function addSpaces(value) {
    return value.replace(/(.{2})/g, '$1 ');
}

const pays = document.getElementById("pays");
const ville = document.getElementById("ville");
const telephone = document.getElementById("telephone");

pays.addEventListener('input', function() {
    this.value = surnameFormat(inputAlphanumeric(this.value));
});

ville.addEventListener('input', function() {
    this.value = surnameFormat(inputAlphanumeric(this.value));
});

telephone.addEventListener('input', function() {
    this.value = inputNumber(this.value);
    if (this.value.length > 10) {
        this.value = this.value.slice(0, -1);
        return;
    }
});