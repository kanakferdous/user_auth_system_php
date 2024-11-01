<?php 
require_once '../config/db.php';

// Set the page title
$pageTitle = "Varify Email";
$message = '';

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  try {
    $sql = "SELECT * FROM users WHERE verification_token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      // Update email verification status
      $sql = "UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = :id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $user['id']);
      $stmt->execute();

      $message = "<div class='alert alert-success text-center'>Email successfully verified! You can now <a href='index.php'>log in</a>.</div>";
    } else {
      $message = "<div class='alert alert-danger text-center'>Invalid verification token!</div>";
    }
  } catch (PDOException $e) {
    $message = "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
  }
} else {
  $message = "<div class='alert alert-warning text-center'>No token provided!</div>";
}

// Include the header
include '../includes/header.php';
?>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow p-4">
        <h2 class="text-center mb-4">Email Verification</h2>
        <?php echo $message; ?>
      </div>
    </div>
  </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
