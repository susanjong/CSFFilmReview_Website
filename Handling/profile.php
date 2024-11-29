<?php
session_start();
include 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html");
    exit();
}

// Fetch user information from the database using user ID
$user_id = $_SESSION['user_id'];
try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user data: " . $e->getMessage();
    exit();
}

// Check if user data was retrieved
if ($user):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Handling/profile.css">
</head>
<body>
    <div class="profile-card">
        <div class="profile-header text-center">
            <h4>User Profile</h4>
        </div>
        <div class="profile-content">
            <div class="info-section">
                <h6 class="section-title">Information</h6>
                <p class="info-label">Username</p>
                <p class="info-value"><?= htmlspecialchars($user['username']) ?></p>
                <p class="info-label">Email</p>
                <p class="info-value"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            <div class="button-section text-center">
                <a href="/PROJEK AKHIR_PEMWEB/PROJEK PEMWEB AKHIR/tampilan awal/film.html" class="btn homepage-btn">Homepage</a>
                <a href="/Handling/settings.php" class="btn settings-btn">Settings</a>
                <a href="/Handling/sign-out.php" class="btn signout-btn">Sign Out</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php else: ?>
    <h1>User not found. <a href='sign_form.html'>Login here</a></h1>
<?php endif; ?>
