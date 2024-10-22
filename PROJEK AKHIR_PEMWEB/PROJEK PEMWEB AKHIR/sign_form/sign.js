window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search); // Get the URL parameters
    const error = urlParams.get('error'); // Get the 'error' parameter value from the URL
    const success = urlParams.get('success');

    if (error) {
        let errorMessage = '';
        
        // Set error message based on the type of error
        if (error === 'invalid_credentials') {
            errorMessage = 'Invalid email or password. Please try again.';
        } else if (error == 'username_exists') {
            errorMessage = 'Username already exists. Please choose a different one.';
        } else if (error === 'email_exists') {
            errorMessage = 'Email already exists. Please choose a different one.';
        } else if (error === 'password_mismatch') {
            errorMessage = 'Passwords do not match. Please try again.';
        }

        // Display the alert with the error message
        if (errorMessage) {
            alert(errorMessage); // Show the alert with the appropriate error message

            const newURL = window.location.href.split('?')[0]; // Get URL without parameters
            window.history.replaceState(null, null, newURL);
        }
    }

    // Handle success messages
    if (success) {
        let successMessage = '';
        
        if (success === 'registration_successful') {
            successMessage = 'Registration successful!';
        }

        // Display the alert with the success message
        if (successMessage) {
            alert(successMessage);
            
            // Clear the URL parameter after showing the success message
            const newURL = window.location.href.split('?')[0];
            window.history.replaceState(null, null, newURL);
        }
    }
}



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