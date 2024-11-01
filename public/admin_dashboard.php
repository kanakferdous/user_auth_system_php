<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to an access denied page or display a friendly message
    echo "<div style='text-align:center; margin-top:20%; font-family:sans-serif;'>
            <h2>Access Denied</h2>
            <p>You do not have permission to access this page.</p>
            <a href='dashboard.php' style='color: #007bff; text-decoration: none;'>Return to Dashboard</a>
          </div>";
    exit();
}

// Set the page title
$pageTitle = "Admin Dashboard";

// Include the header
include '../includes/header.php';
?>
    <div class="card shadow p-4">
        <h2 class="text-center text-primary mb-4">Welcome to the Admin Dashboard!</h2>
        <p class="text-center">Only users with the admin role can view this page.</p>
        <div class="text-center">
            <a href="dashboard.php" class="btn btn-secondary">Back to User Dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
