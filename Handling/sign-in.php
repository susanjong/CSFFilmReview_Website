<?php
session_start(); // Start the session at the very top
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check email, and password are set
    if (isset($_POST['email'], $_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the user exists by email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password and proceed to login if correct
        if ($user && password_verify($password, $user['password'])) {
            // Set session variable
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];

            // Redirect to homepage
            header("Location:/tampilanawal.html");

            exit(); // Stop the script after redirecting
        } else {
            // Handle invalid login
            echo "Invalid email, or password.";
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