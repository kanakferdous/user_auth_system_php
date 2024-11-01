<?php

// Set the page title
$pageTitle = "Forget Password";

// Include the header
include '../includes/header.php';
?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Forgot Password</h2>
                
                <?php
                require_once '../config/db.php';

                if (isset($_POST['reset'])) {
                    $email = $_POST['email'];
                    $message = '';

                    try {
                        // Check if the email exists in the database
                        $sql = "SELECT * FROM users WHERE email = :email";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            // Generate a password reset token
                            $reset_token = bin2hex(random_bytes(16));

                            // Save the token in the database
                            $sql = "UPDATE users SET verification_token = :token WHERE email = :email";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':token', $reset_token);
                            $stmt->bindParam(':email', $email);
                            $stmt->execute();

                            // Send the password reset link
                            $reset_link = "http://localhost:10077/user_auth_system_php/public/reset_password.php?token=" . $reset_token;
                            $subject = "Password Reset Request";
                            $message = "Hi,\nPlease click the following link to reset your password: $reset_link";
                            $headers = "From: no-reply@auth-system.local";

                            if (mail($email, $subject, $message, $headers)) {
                                $message = "<div class='alert alert-success text-center'>Password reset link sent! Please check your email.</div>";
                            } else {
                                $message = "<div class='alert alert-danger text-center'>Failed to send password reset email!</div>";
                            }
                        } else {
                            $message = "<div class='alert alert-warning text-center'>No account found with that email.</div>";
                        }
                    } catch (PDOException $e) {
                        $message = "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
                    }
                    echo $message;
                }
                ?>

                <form action="forgot_password.php" method="POST" class="p-3">
                    <div class="form-group">
                        <label for="email">Enter your email address:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <button type="submit" name="reset" class="btn btn-primary btn-block">Send Password Reset Link</button>
                </form>

                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-secondary">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
<?php
// Include the footer
include '../includes/footer.php';
?>
