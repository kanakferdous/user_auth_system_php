<?php
// Include database connection
require_once '../config/db.php';

// Check if the token is in the URL (GET request)
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    die("Invalid or missing token.");
}

$message = '';
$pageTitle = "Reset Password";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Reset Password</h2>

                <?php echo $message; ?>

                <form action="" method="POST" class="p-3">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button type="submit" name="reset_password" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

<?php
    // Include the footer
    include '../includes/footer.php';
    // Handle the form submission (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ensure the token is passed correctly in the POST request
        if (isset($_POST['token'])) {
            $token = trim($_POST['token']);
        } else {
            die("Token is missing from POST request!");
        }

        // Get the new password and hash it
        $new_password = $_POST['password'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        try {
            // Find the user by the reset token
            $sql = "SELECT * FROM users WHERE verification_token = :token";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();

            // Check if the token is valid
            if ($stmt->rowCount() > 0) {
                // Update the user's password and clear the token
                $sql = "UPDATE users SET password = :password, verification_token = NULL WHERE verification_token = :token";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                $message = "<div class='alert alert-success text-center'>Password has been reset! You can now <a href='index.php'>log in</a>.</div>";
            } else {
                $message = "<div class='alert alert-danger text-center'>Invalid or expired reset token!</div>";
            }
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
        }
    }
?>
