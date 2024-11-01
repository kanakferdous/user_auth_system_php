<?php 
// Set the page title
$pageTitle = "Register";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h2 class="text-center mb-4">Register</h2>
        
        <?php
          require_once '../config/db.php';

          if (isset($_POST['submit'])) {
              $username = $_POST['username'];
              $email = $_POST['email'];
              $password = $_POST['password'];
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);
              $verification_token = bin2hex(random_bytes(16));

              try {
                  $sql = "INSERT INTO users (username, email, password, verification_token) VALUES (:username, :email, :password, :token)";
                  $stmt = $pdo->prepare($sql);
                  $stmt->bindParam(':username', $username);
                  $stmt->bindParam(':email', $email);
                  $stmt->bindParam(':password', $hashed_password);
                  $stmt->bindParam(':token', $verification_token);
                  $stmt->execute();

                  $verification_link = "http://localhost:10077/user_auth_system_php/public/verify_email.php?token=" . $verification_token;
                  $subject = "Verify Your Email";
                  $message = "Hi $username, \nPlease click the following link to verify your email: $verification_link";
                  $headers = "From: no-reply@user-auth-system.local";

                  if (mail($email, $subject, $message, $headers)) {
                      echo "<div class='alert alert-success text-center'>Registration successful! Please check your email to verify your account.</div>";
                  } else {
                      echo "<div class='alert alert-danger text-center'>Failed to send verification email</div>";
                  }
              } catch (PDOException $e) {
                  if ($e->errorInfo[1] == 1062) {
                      echo "<div class='alert alert-warning text-center'>Username or email already taken!</div>";
                  } else {
                      echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
                  }
              }
          }
        ?>

        <!-- Dummy fields to prevent autofill -->
        <input type="text" style="display:none;" aria-hidden="true">
        <input type="password" style="display:none;" aria-hidden="true">

        <!-- Registration Form -->
        <form action="register.php" method="post" class="p-4 shadow bg-white rounded" autocomplete="off">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" autocomplete="new-username" required>
          </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" autocomplete="new-email" required>
          </div>

          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" autocomplete="new-password" required>
          </div>

          <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
        </form>
      </div>
    </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
