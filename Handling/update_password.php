<?php
// update_password.php
include 'database.php'; // Include your database connection

// Handle the back button action
if (isset($_POST['back_button'])) {
    // Check if the user is logged in
    session_start();
    if (isset($_SESSION['user_id'])) {
        // User is logged in, redirect to settings page
        header("Location: /Handling/settings.php");
    } else {
        // User is not logged in, redirect to sign-in page
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html");
    }
    exit(); // Ensure script stops after redirect
}

// Handle password update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['back_button'])) {
    // Collect input data
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $favorite_food = $_POST['favorite_food'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Check if new passwords match
    if ($new_password !== $confirm_new_password) {
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?error=password_mismatch");
        exit();
    }

    // Check if email exists and verify birthday and favorite_food
    $sql = "SELECT * FROM users WHERE email = :email AND birthday = :birthday AND favorite_food = :favorite_food";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':birthday', $birthday);
    $stmt->bindParam(':favorite_food', $favorite_food);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Email, birthday, and favorite_food verified, update the password
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET password = :password WHERE email = :email";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->execute();

        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?success=password_updated");
        exit();
    } else {
        // If any of the fields are incorrect, show an error
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?error=verification_failed");
        exit();
    }
}
?>
