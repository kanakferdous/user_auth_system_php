<?php 
  // Set the page title
  $pageTitle = "Login";
  // Include the header and database connection
  include '../includes/header.php';
  include '../config/db.php';
?>
  <div class="row justify-content-center">
    <div class="col-md-4">

      <!-- Display Logout Message -->
      <?php
      if (isset($_GET['message']) && $_GET['message'] == 'loggedout') {
          echo "<div class='alert alert-info text-center'>You have successfully logged out.</div>";
          header("Refresh:2; url=index.php"); // Refresh the page after 2 seconds
          exit();
      }
      ?>
      <h2 class="text-center mb-4">Login</h2>
      
      <?php 
        session_start();
        require_once '../config/db.php';

        // Process login when form is submitted
        if (isset($_POST['login'])) {
          $username = $_POST['username'];
          $password = $_POST['password'];

          try {
            // Retrieve user from database
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
              $user = $stmt->fetch(PDO::FETCH_ASSOC);

              // Check if email is verified
              if ($user['email_verified'] == 0) {
                echo "<div class='alert alert-warning text-center'>Please verify your email before logging in.</div>";
              } else {
                if (password_verify($password, $user['password'])) {
                  $_SESSION['user_id'] = $user['id'];
                  $_SESSION['username'] = $user['username'];
                  $_SESSION['role'] = $user['role'];
                  header("Location: dashboard.php");
                  exit;
                } else {
                  echo "<div class='alert alert-danger text-center'>Invalid password!</div>";
                }
              }
            } else {
              echo "<div class='alert alert-danger text-center'>No user found with that username!</div>";
            }
          } catch (PDOException $e) {
            echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
          }
        }
      ?>

      <!-- Form with autocomplete off -->
      <form action="index.php" method="post" class="p-4 shadow bg-white rounded" autocomplete="off">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" class="form-control" name="username" autocomplete="off" required>
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" class="form-control" name="password" autocomplete="off" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
        <div class="text-center mt-3">
          <a href="register.php">Register</a> | 
          <a href="forgot_password.php">Forgot Password?</a>
        </div>
      </form>
    </div>
  </div>
<?php 
  // Include the footer
  include '../includes/footer.php';
?>
