<?php
session_start();
require_once "../config.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered. Please use a different email.";
        } else {
            // Hash password and insert
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $password_hash);
            
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['fullname'] = $fullname;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Registration - Municipal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-body {
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
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus fa-4x mb-3"></i>
                        <h2 class="mb-0">Citizen Registration</h2>
                        <p class="mb-0">Create your account to get started</p>
                    </div>
                    
                    <div class="register-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post" id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="fullname" class="form-control form-control-lg" 
                                       placeholder="Enter your full name" required 
                                       value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="Enter your email" required 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Password <span class="text-danger">*</span>
                                </label>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" 
                                           class="form-control form-control-lg" 
                                           placeholder="Create a password (min 6 characters)" 
                                           required minlength="6">
                                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                                <div class="password-strength" id="strengthBar"></div>
                                <small class="text-muted" id="strengthText"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div style="position: relative;">
                                    <input type="password" name="confirm_password" id="confirm_password" 
                                           class="form-control form-control-lg" 
                                           placeholder="Re-enter your password" required>
                                    <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                                </div>
                                <small class="text-danger" id="matchError"></small>
                            </div>

                            <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                                <i class="fas fa-user-plus"></i> Register
                            </button>

                            <div class="text-center">
                                <p class="mb-2">Already have an account? 
                                    <a href="login.php" class="text-decoration-none fw-bold">Login here</a>
                                </p>
                                <a href="../index.php" class="text-muted text-decoration-none">
                                    <i class="fas fa-arrow-left"></i> Back to Home
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirm_password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Password strength indicator
        password.addEventListener('input', function() {
            const val = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (val.length >= 6) strength++;
            if (val.length >= 10) strength++;
            if (/[a-z]/.test(val) && /[A-Z]/.test(val)) strength++;
            if (/\d/.test(val)) strength++;
            if (/[^a-zA-Z\d]/.test(val)) strength++;

            if (strength <= 2) {
                strengthBar.style.width = '33%';
                strengthBar.style.backgroundColor = '#dc3545';
                strengthText.textContent = 'Weak password';
            } else if (strength <= 3) {
                strengthBar.style.width = '66%';
                strengthBar.style.backgroundColor = '#ffc107';
                strengthText.textContent = 'Medium password';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = '#28a745';
                strengthText.textContent = 'Strong password';
            }
        });

        // Password match validation
        confirmPassword.addEventListener('input', function() {
            const matchError = document.getElementById('matchError');
            if (this.value !== password.value) {
                matchError.textContent = '⚠️ Passwords do not match';
            } else {
                matchError.textContent = '✓ Passwords match';
                matchError.classList.remove('text-danger');
                matchError.classList.add('text-success');
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>