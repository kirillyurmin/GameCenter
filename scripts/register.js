const form = document.getElementById('register-form');
const fullname = document.getElementById('fullname');
const email = document.getElementById('email');
const login = document.getElementById('login');
const password = document.getElementById('password');
const confirm = document.getElementById('confirm');

function showError(field, message) {
    const span = document.querySelector('.form-error[data-error-for="' + field.id + '"]');
    span.textContent = message;
    field.classList.add('input-error');
}

function clearError(field) {
    const span = document.querySelector('.form-error[data-error-for="' + field.id + '"]');
    span.textContent = '';
    field.classList.remove('input-error');
}

form.addEventListener('submit', function (e) {
    e.preventDefault(); 

    let ok = true; 

    if (fullname.value.trim() === '') {
        showError(fullname, 'Укажите ФИО');
        ok = false;
    } else {
        clearError(fullname);
    }

    const emailValue = email.value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (emailValue === '') {
        showError(email, 'Укажите email');
        ok = false;
    } else if (!emailPattern.test(emailValue)) {
        showError(email, 'Некорректный формат email');
        ok = false;
    } else {
        clearError(email);
    }

    if (login.value.trim() === '') {
        showError(login, 'Укажите логин');
        ok = false;
    } else {
        clearError(login);
    }

    if (password.value === '') {
        showError(password, 'Укажите пароль');
        ok = false;
    } else if (password.value.length < 8) {
        showError(password, 'Пароль должен содержать не менее 8 символов');
        ok = false;
    } else {
        clearError(password);
    }

    if (confirm.value === '') {
        showError(confirm, 'Повторите пароль');
        ok = false;
    } else if (confirm.value !== password.value) {
        showError(confirm, 'Пароли не совпадают');
        ok = false;
    } else {
        clearError(confirm);
    }

    if (!ok) return;

    alert('Регистрация прошла успешно!');
    form.reset();
});

[fullname, email, login, password, confirm].forEach(function (field) {
    field.addEventListener('input', function () {
        clearError(field);
    });
});