var messages = {
    username    : 'Doit contenir entre 3 et 25 caractères',
    email       : 'Veuillez entrer une adresse mail valide',
    emailConf   : 'Les adresses ne correspondent pas',
    password    : 'Doit contenir entre 5 et 50 caractères',
    passwordConf: 'Les mots de passe ne correspondent pas',
    empty       : 'Ce champ ne dois pas être vide'
};

var username     = document.getElementById('registerUsername');
var email        = document.getElementById('registerEmail');
var emailConf    = document.getElementById('registerEmailConf');
var password     = document.getElementById('registerPassword');
var passwordConf = document.getElementById('registerPasswordConf');

username.onfocusout = function(){validateUsername(this.value)};
email.onfocusout = function(){validateEmail(this.value)};
emailConf.onfocusout = function(){validateEmailConf(this.value)};
password.onfocusout = function(){validatePassword(this.value)};
passwordConf.onfocusout = function(){validatePasswordConf(this.value)};

function validateUsername (username) {
    if (username.length >= 3 && username.length <= 25) {
        document.getElementById('registerUsername').classList.remove("form-deny");
        document.getElementById('registerUsername').classList.add("form-validate");
        document.getElementById('registerUsername').setCustomValidity("");
    } else {
        document.getElementById('registerUsername').classList.remove("form-validate");
        document.getElementById('registerUsername').classList.add("form-deny");
        document.getElementById('registerUsername').setCustomValidity(messages.username);
    }
}

function validateEmail (email) {
    if (/(.+)@(.+){2,}\.(.+){2,}/.test(email)) {
        document.getElementById('registerEmail').classList.remove("form-deny");
        document.getElementById('registerEmail').classList.add("form-validate");
        document.getElementById('registerEmail').setCustomValidity("");
    } else {
        document.getElementById('registerEmail').classList.remove("form-validate");
        document.getElementById('registerEmail').classList.add("form-deny");
        document.getElementById('registerEmail').setCustomValidity(messages.email);
    }
    if (email.length > 5) {

    }
}

function validateEmailConf () {
    if (email.value === emailConf.value) {
        document.getElementById('registerEmailConf').classList.remove("form-deny");
        document.getElementById('registerEmailConf').classList.add("form-validate");
        document.getElementById('registerEmailConf').setCustomValidity("");
    } else {
        document.getElementById('registerEmailConf').classList.remove("form-validate");
        document.getElementById('registerEmailConf').classList.add("form-deny");
        document.getElementById('registerEmailConf').setCustomValidity(messages.emailConf);
    }
}

function validatePassword (password) {
    if (password.length >= 5 && password.length <= 50) {
        document.getElementById('registerPassword').classList.remove("form-deny");
        document.getElementById('registerPassword').classList.add("form-validate");
        document.getElementById('registerPassword').setCustomValidity("");
    } else {
        document.getElementById('registerPassword').classList.remove("form-validate");
        document.getElementById('registerPassword').classList.add("form-deny");
        document.getElementById('registerPassword').setCustomValidity(messages.password);
    }
}

function validatePasswordConf () {
    if (password.value === passwordConf.value) {
        document.getElementById('registerPasswordConf').classList.remove("form-deny");
        document.getElementById('registerPasswordConf').classList.add("form-validate");
        document.getElementById('registerPasswordConf').setCustomValidity("");
    } else {
        document.getElementById('registerPasswordConf').classList.remove("form-validate");
        document.getElementById('registerPasswordConf').classList.add("form-deny");
        document.getElementById('registerPasswordConf').setCustomValidity(messages.passwordConf);
    }
}
