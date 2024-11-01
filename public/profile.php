<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include database connection
require_once '../config/db.php';

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    try {
        // Update username and email in the database
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        $message = "<div class='alert alert-success text-center'>Profile updated successfully!</div>";
        $_SESSION['username'] = $username;  // Update session username
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
    }
}

// Set the page title
$pageTitle = "Profile";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Your Profile</h2>
                
                <?php echo $message; ?>

                <form action="profile.php" method="POST" class="p-3">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <button type="submit" name="update_profile" class="btn btn-primary btn-block">Update Profile</button>
                </form>

                <div class="text-center mt-3">
                    <a href="change_password.php" class="btn btn-secondary mb-2">Change Password</a>
                    <a href="dashboard.php" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
