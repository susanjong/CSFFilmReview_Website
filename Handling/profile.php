<?php
session_start(); // Start the session
include 'database.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the session is not set, redirect to login page
    header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html");
    exit();
}

// Fetch user information from the database using user ID
$user_id = $_SESSION['user_id']; // Assuming you set this during login
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user data was retrieved
if ($user) {
    // Start of the HTML page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <link rel="stylesheet" href="profile.css">
    </head>
    <body>
        <div class="container">
            <h1>User Profile</h1>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <div class="button-container">
                <a href="/tampilanawal.html" class="go-home-btn">Go to Homepage</a>
                <a href="sign-out.php" class="go-home-btn">Sign Out</a>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Display friendly message if user not found
    echo "<h1>User not found. <a href='sign_form.html'>Login here</a></h1>";
}
?>