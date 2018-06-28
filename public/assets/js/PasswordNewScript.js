var messages = {
    password    : 'Doit contenir entre 5 et 50 caractères',
    passwordConf: 'Les mots de passe ne correspondent pas'
};

var password     = document.getElementById('recoveryPassword');
var passwordConf = document.getElementById('recoveryPasswordConf');

password.onfocusout     = function(){validatePassword(this.value)};
passwordConf.onfocusout = function(){validatePasswordConf(this.value)};

function validatePassword (password) {
    if (password.length >= 5 && password.length <= 50) {
        document.getElementById('recoveryPassword').classList.remove("form-deny");
        document.getElementById('recoveryPassword').classList.add("form-validate");
        document.getElementById('recoveryPassword').setCustomValidity("");
    } else {
        document.getElementById('recoveryPassword').classList.remove("form-validate");
        document.getElementById('recoveryPassword').classList.add("form-deny");
        document.getElementById('recoveryPassword').setCustomValidity(messages.password);
    }
}

function validatePasswordConf () {
    if (password.value === passwordConf.value) {
        document.getElementById('recoveryPasswordConf').classList.remove("form-deny");
        document.getElementById('recoveryPasswordConf').classList.add("form-validate");
        document.getElementById('recoveryPasswordConf').setCustomValidity("");
    } else {
        document.getElementById('recoveryPasswordConf').classList.remove("form-validate");
        document.getElementById('recoveryPasswordConf').classList.add("form-deny");
        document.getElementById('recoveryPasswordConf').setCustomValidity(messages.passwordConf);
    }
}