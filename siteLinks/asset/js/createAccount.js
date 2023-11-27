function inputClipping(value){
    return value.replace(/[^a-zA-Z]/g, '');
}

//vérification de la conformité du nom
const nom = document.getElementById("nom");

nom.addEventListener('input', function() {
    this.value = inputClipping(this.value);
});

//vérification de la conformité du prénom
const prenom = document.getElementById("prenom");

prenom.addEventListener('input', function() {
    this.value = inputClipping(this.value);
});

//visibilité des mots de passe
const eyesPasswordVisible = document.getElementById("eyesPasswordVisible");
const eyesPasswordVisible2 = document.getElementById("eyesPasswordVisible2");
const passwordInput = document.getElementById("passwordInput");
const passwordInput2 = document.getElementById("passwordInput2");
var isClosed = false;
var isClosed2 = false;

eyesPasswordVisible.addEventListener('click', function() {
    if (!isClosed) {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-closed.svg';
        passwordInput.type = "text";
        isClosed = true;
    } else {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-open.svg';
        isClosed = false;
        passwordInput.type = "password";
    }
});

eyesPasswordVisible2.addEventListener('click', function() {
    if (!isClosed2) {
        eyesPasswordVisible2.src = 'asset/icons/bleu/eye-closed.svg';
        passwordInput2.type = "text";
        isClosed2 = true;
    } else {
        eyesPasswordVisible2.src = 'asset/icons/bleu/eye-open.svg';
        isClosed2 = false;
        passwordInput2.type = "password";
    }
});
