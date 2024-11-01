<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
require_once '../config/db.php';

$message = '';

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // Fetch the user's current password from the database
    $sql = "SELECT password FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the current password matches
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            // Hash the new password and update it in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $message = "<div class='alert alert-success'>Password changed successfully!</div>";
        } else {
            $message = "<div class='alert alert-warning'>New passwords do not match!</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Current password is incorrect!</div>";
    }
}

// Set the page title
$pageTitle = "Change Password";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Change Password</h2>
            <?php echo $message; ?>

            <form action="change_password.php" method="POST" class="p-4 shadow bg-white rounded">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>

                <button type="submit" name="change_password" class="btn btn-primary btn-block">Change Password</button>
            </form>

            <div class="text-center mt-3">
                <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
            </div>
        </div>
    </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
