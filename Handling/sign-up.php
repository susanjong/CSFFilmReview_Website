<?php
// sign_up.php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $favorite_food = $_POST['favorite_food'];
    $birthday = $_POST['birthday'];
    $confirm_password = $_POST['confirm-password'];

    if ($password !== $confirm_password) {
        // Passwords do not match, handle the error
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html?error=password_mismatch");
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html?error=invalid_email");
        exit();
    }    

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

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
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html?error=username_exists");
        exit();
    } elseif ($stmt_check_email->rowCount() > 0) {
        // Email exists, handle the error
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html?error=email_exists");
        exit();
    } else {
        // Insert the new user since both username and email are unique
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();
        
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html?success=registration_successful");
    }
}
?>