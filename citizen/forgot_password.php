<?php
session_start();
require_once "../config.php";

$error = '';
$success = '';
$step = isset($_GET['step']) ? $_GET['step'] : 1;

// Step 1: Send verification code
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_code'])) {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        $stmt = $conn->prepare("SELECT id, fullname FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $fullname);
        $stmt->fetch();
        $stmt->close();
        
        if ($user_id) {
            // Generate 6-digit verification code
            $verification_code = sprintf("%06d", mt_rand(1, 999999));
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            
            // Store verification code in database
            $stmt = $conn->prepare("INSERT INTO password_resets (user_id, email, code, expiry) VALUES (?, ?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE code=?, expiry=?, created_at=NOW()");
            $stmt->bind_param("isssss", $user_id, $email, $verification_code, $expiry, $verification_code, $expiry);
            $stmt->execute();
            $stmt->close();
            
            // Send email (configure your mail settings)
            $to = $email;
            $subject = "Password Reset Verification Code - Municipal System";
            $message = "Hello $fullname,\n\n";
            $message .= "Your password reset verification code is: $verification_code\n\n";
            $message .= "This code will expire in 15 minutes.\n\n";
            $message .= "If you didn't request this, please ignore this email.\n\n";
            $message .= "Best regards,\nMunicipal System Team";
            $headers = "From: noreply@municipalsystem.com\r\n";
            $headers .= "Reply-To: support@municipalsystem.com\r\n";
            
            // For development, just show the code (remove in production)
            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['reset_email'] = $email;
                $success = "Verification code sent to your email! (Code: $verification_code)";
                $step = 2;
            } else {
                // If mail fails, still proceed but show the code (for development)
                $_SESSION['reset_email'] = $email;
                $success = "For testing: Your verification code is <strong>$verification_code</strong>";
                $step = 2;
            }
        } else {
            $error = "No account found with this email address.";
        }
    }
}

// Step 2: Verify code and reset password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $email = $_SESSION['reset_email'] ?? '';
    $code = trim($_POST['code']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($code) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
        $step = 2;
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters long.";
        $step = 2;
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
        $step = 2;
    } else {
        // Verify code
        $stmt = $conn->prepare("SELECT user_id FROM password_resets WHERE email=? AND code=? AND expiry > NOW()");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();
        
        if ($user_id) {
            // Update password
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password_hash=? WHERE id=?");
            $stmt->bind_param("si", $password_hash, $user_id);
            $stmt->execute();
            $stmt->close();
            
            // Delete used code
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
            
            unset($_SESSION['reset_email']);
            $success = "Password reset successfully! You can now login.";
            $step = 3;
        } else {
            $error = "Invalid or expired verification code.";
            $step = 2;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Municipal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .reset-body {
            padding: 40px;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #e9ecef;
            margin: 0 5px;
            border-radius: 5px;
            font-weight: bold;
        }
        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="reset-card">
                    <div class="reset-header">
                        <i class="fas fa-key fa-4x mb-3"></i>
                        <h2 class="mb-0">Reset Password</h2>
                        <p class="mb-0">Recover your account access</p>
                    </div>
                    
                    <div class="reset-body">
                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step <?= $step >= 1 ? 'active' : '' ?>">
                                <i class="fas fa-envelope"></i> Email
                            </div>
                            <div class="step <?= $step >= 2 ? 'active' : '' ?>">
                                <i class="fas fa-lock"></i> Verify
                            </div>
                            <div class="step <?= $step >= 3 ? 'active' : '' ?>">
                                <i class="fas fa-check"></i> Done
                            </div>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> <?= $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($step == 1): ?>
                            <!-- Step 1: Enter Email -->
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> Email Address
                                    </label>
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           placeholder="Enter your registered email" required>
                                    <small class="text-muted">
                                        We'll send a verification code to this email
                                    </small>
                                </div>

                                <button type="submit" name="send_code" class="btn btn-primary-custom btn-lg w-100 mb-3">
                                    <i class="fas fa-paper-plane"></i> Send Verification Code
                                </button>

                                <div class="text-center">
                                    <a href="login.php" class="text-decoration-none">
                                        <i class="fas fa-arrow-left"></i> Back to Login
                                    </a>
                                </div>
                            </form>

                        <?php elseif ($step == 2): ?>
                            <!-- Step 2: Verify Code and Reset Password -->
                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-shield-alt"></i> Verification Code
                                    </label>
                                    <input type="text" name="code" class="form-control form-control-lg text-center" 
                                           placeholder="Enter 6-digit code" required maxlength="6" pattern="\d{6}">
                                    <small class="text-muted">
                                        Check your email for the verification code
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock"></i> New Password
                                    </label>
                                    <div style="position: relative;">
                                        <input type="password" name="new_password" id="new_password" 
                                               class="form-control form-control-lg" 
                                               placeholder="Enter new password" required minlength="6">
                                        <i class="fas fa-eye password-toggle" id="toggleNewPassword"></i>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-lock"></i> Confirm New Password
                                    </label>
                                    <div style="position: relative;">
                                        <input type="password" name="confirm_password" id="confirm_password" 
                                               class="form-control form-control-lg" 
                                               placeholder="Confirm new password" required>
                                        <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                                    </div>
                                    <small class="text-danger" id="matchError"></small>
                                </div>

                                <button type="submit" name="reset_password" class="btn btn-primary-custom btn-lg w-100 mb-3">
                                    <i class="fas fa-check"></i> Reset Password
                                </button>

                                <div class="text-center">
                                    <a href="forgot_password.php" class="text-decoration-none">
                                        <i class="fas fa-redo"></i> Resend Code
                                    </a>
                                </div>
                            </form>

                        <?php else: ?>
                            <!-- Step 3: Success -->
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                                <h3 class="mt-3">Password Reset Successful!</h3>
                                <p class="text-muted">You can now login with your new password</p>
                                <a href="login.php" class="btn btn-primary-custom btn-lg mt-3">
                                    <i class="fas fa-sign-in-alt"></i> Go to Login
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const toggleNewPassword = document.getElementById('toggleNewPassword');
        const newPassword = document.getElementById('new_password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirm_password');

        if (toggleNewPassword) {
            toggleNewPassword.addEventListener('click', function() {
                const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                newPassword.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        if (toggleConfirmPassword) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Password match validation
        if (confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                const matchError = document.getElementById('matchError');
                if (this.value !== newPassword.value) {
                    matchError.textContent = '⚠️ Passwords do not match';
                } else {
                    matchError.textContent = '✓ Passwords match';
                    matchError.classList.remove('text-danger');
                    matchError.classList.add('text-success');
                }
            });
        }
    </script>
</body>
</html>