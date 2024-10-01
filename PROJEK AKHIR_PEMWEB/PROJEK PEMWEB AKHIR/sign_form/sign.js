document.getElementById('show-signup').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('.form-container').classList.add('flipped');
});

document.getElementById('show-login').addEventListener('click', function(event) {
    event.preventDefault();
    document.querySelector('.form-container').classList.remove('flipped');
});
