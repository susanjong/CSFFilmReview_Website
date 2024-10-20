<?php
session_start(); // Start the session at the very top
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username, email, and password are set
    if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the user exists by username and email
        $sql = "SELECT * FROM users WHERE username = :username AND email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password and proceed to login if correct
        if ($user && password_verify($password, $user['password'])) {
            // Set session variable
            $_SESSION['user'] = $user['username'];

            // Redirect to homepage
            header("Location:/tampilanawal.html");

            exit(); // Stop the script after redirecting
        } else {
            // Handle invalid login
            echo "Invalid username, email, or password.";
        }
    } else {
        // Handle case where form fields are missing
        echo "Please fill in all fields.";
    }
} else {
    // Handle case where the request method is not POST
    echo "Invalid request method.";
}
?>