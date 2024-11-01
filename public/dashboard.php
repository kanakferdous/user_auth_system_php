<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Set the page title
$pageTitle = "Dashboard";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center text-primary">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p class="text-center">This is your user dashboard.</p>

                <div class="text-center mt-4">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="admin_dashboard.php" class="btn btn-info mb-2">Go to Admin Dashboard</a><br>
                    <?php endif; ?>
                    <a href="profile.php" class="btn btn-primary mb-2">View Profile</a><br>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

<?php
// Include the footer
include '../includes/footer.php';
?>