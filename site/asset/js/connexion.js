//visibilité du mot de passe

const eyesPasswordVisible = document.getElementById("eyesPasswordVisible");
const passwordInput = document.getElementById("passwordInput");
var isClosed = false;

eyesPasswordVisible.src = 'asset/icons/bleu/eye-closed.svg'
isClosed = false;
passwordInput.type = "password";

eyesPasswordVisible.addEventListener('click', function () {
    if (!isClosed) {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-open.svg';
        passwordInput.type = "text";
        isClosed = true;
    } else {
        eyesPasswordVisible.src = 'asset/icons/bleu/eye-closed.svg'
        isClosed = false;
        passwordInput.type = "password";
    }
});