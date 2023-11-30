document.getElementById('Personne').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value === "") {
        this.value = "0";
    }
    if (parseInt(this.value) > 10) {
        this.value = "10";
    }
    if (parseInt(this.value) < 1) {
        this.value = "1";
    }
    // Supprimer les zéros en tête
    this.value = parseInt(this.value).toString();
});

function increment(id) {
    const input = document.getElementById(id);
    let value = parseInt(input.value, 10) || 0;
    if (value<10){
        value += 1;
    }
    input.value = value;
    currentField = input;
}

function decrement(id) {
    const input = document.getElementById(id);
    let value = parseInt(input.value, 10) || 0;
    if (value > 0) {
        value -= 1;
    }
    
    input.value = value;
    currentField = input;
}