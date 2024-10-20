<?php
// sign_up.php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash the password

    // Check if username already exists
    $sql_check_username = "SELECT * FROM users WHERE username = :username";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bindParam(':username', $username);
    $stmt_check_username->execute();

    // Check if email already exists
    $sql_check_email = "SELECT * FROM users WHERE email = :email";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_username->rowCount() > 0) {
        // Username exists, handle the error
        echo "Username already exists. Please choose a different one.";
    } elseif ($stmt_check_email->rowCount() > 0) {
        // Email exists, handle the error
        echo "Email already exists. Please choose a different one.";
    } else {
        // Insert the new user since both username and email are unique
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        
        echo "Registration successful!";
    }
}
?>