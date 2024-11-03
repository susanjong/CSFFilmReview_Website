window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search); // Get the URL parameters
    const error = urlParams.get('error'); // Get the 'error' parameter value from the URL
    const success = urlParams.get('success');

// Show error messages if present
if (error) {
    let errorMessage = '';
    
    // Set error message based on the type of error
    if (error === 'invalid_credentials') {
        errorMessage = 'Invalid email or password. Please try again.';
    } else if (error == 'username_exists') {
        errorMessage = 'Username already exists. Please choose a different one.';
    } else if (error === 'email_exists') {
        errorMessage = 'Email already exists. Please choose a different one.';
    }else if (error === 'invalid_email') {
        errorMessage = 'Please use a valid Gmail address.';
    }
    

    // Display the alert with the error message
    if (errorMessage) {
        alert(errorMessage); // Show the alert with the appropriate error message

        const newURL = window.location.href.split('?')[0]; // Get URL without parameters
        window.history.replaceState(null, null, newURL);
    }
    if (success) {
        let successMessage = '';
        
        // Check for specific success messages
        if (success === 'password_updated') {
            successMessage = 'Password updated successfully!';
        } else if (success === 'registration_complete') {
            successMessage = 'Registration successful! You can now log in.';
        }

        if (successMessage) {
            alert(successMessage);

            const newURL = window.location.href.split('?')[0];
            window.history.replaceState(null, null, newURL);
        }
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