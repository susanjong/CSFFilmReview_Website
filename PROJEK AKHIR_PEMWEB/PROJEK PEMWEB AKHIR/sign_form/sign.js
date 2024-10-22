
















































const flipContainer = document.querySelector('.flip-container');
const showSignUp = document.getElementById('show-sign-up');
const showSignIn = document.getElementById('show-sign-in');

showSignUp.addEventListener('click', (e) => {
    e.preventDefault();
    flipContainer.classList.add('flipped');
});

showSignIn.addEventListener('click', (e) => {
    e.preventDefault();
    flipContainer.classList.remove('flipped');
});