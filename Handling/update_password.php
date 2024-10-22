<?php
// update_password.php
include 'database.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input data
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Check if new passwords match
    if ($new_password !== $confirm_new_password) {
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?error=password_mismatch");
        exit();
    }

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Email found, update the password
        $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE users SET password = :password WHERE email = :email";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->execute();

        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?success=password_updated");
        exit();
    } else {
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/update_password.html?error=email_not_found");
        exit();
    }
}
?>