window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const success = urlParams.get('success');

    if (error) {
        let errorMessage = '';

        if (error === 'password_mismatch') {
            errorMessage = 'New passwords do not match. Please try again.';
        } else if (error === 'email_not_found') {
            errorMessage = 'Email not found. Please check and try again.';
        }

        if (errorMessage) {
            alert(errorMessage);
            const newURL = window.location.href.split('?')[0]; // Get URL without parameters
            window.history.replaceState(null, null, newURL);
        }
    }

    if (success) {
        if (success === 'password_updated') {
            alert('Password updated successfully!');

            const newURL = window.location.href.split('?')[0]; // Get URL without parameters
            window.history.replaceState(null, null, newURL);
        }
    }
}