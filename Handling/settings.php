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

// Update user information if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    try {
        $update_sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':username', $new_username, PDO::PARAM_STR);
        $update_stmt->bindParam(':email', $new_email, PDO::PARAM_STR);
        $update_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $update_stmt->execute();
        // Update session variable and user data
        $_SESSION['username'] = $new_username;
        $user['username'] = $new_username;
        $user['email'] = $new_email;
        $message = "Profile updated successfully!";
    } catch (PDOException $e) {
        $message = "Error updating profile: " . $e->getMessage();
    }
}

// Delete user account if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {
    try {
        $delete_sql = "DELETE FROM users WHERE id = :id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $delete_stmt->execute();
        session_destroy(); // Destroy session
        header("Location: /PROJEK%20AKHIR_PEMWEB/PROJEK%20PEMWEB%20AKHIR/sign_form/sign.html");
        exit();
    } catch (PDOException $e) {
        $message = "Error deleting account: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="settings.css">
</head>
<body>
    <div class="settings-card">
        <div class="settings-header text-center">
            <h4>Account Settings</h4>
        </div>
        <div class="settings-content">
            <?php if (isset($message)): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>
            <form method="POST" action="settings.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" 
                        value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                        value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="button-section text-center">
                    <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                    <a href="/PROJEK AKHIR_PEMWEB/PROJEK PEMWEB AKHIR/tampilan awal/film.html" 
                       class="btn btn-secondary">Cancel</a>
                </div>
                <div class="text-center mt-4">
                    <button type="button" id="deleteAccountBtn" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
            <div class="button-group">
                <button id="confirmYes" class="btn btn-danger">Yes</button>
                <button id="confirmNo" class="btn btn-secondary">No</button>
            </div>
        </div>
    </div>

    <script>
        const deleteAccountBtn = document.getElementById('deleteAccountBtn');
        const modal = document.getElementById('confirmationModal');
        const confirmYes = document.getElementById('confirmYes');
        const confirmNo = document.getElementById('confirmNo');

        // Show modal on delete button click
        deleteAccountBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Submit the delete account form on confirmation
        confirmYes.addEventListener('click', () => {
            modal.style.display = 'none';
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'settings.php';
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'delete_account';
            form.appendChild(hiddenInput);
            document.body.appendChild(form);
            form.submit();
        });

        // Close modal on cancel
        confirmNo.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    </script>
</body>
</html>