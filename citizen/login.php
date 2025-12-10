<?php
session_start();
require_once "../config.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, fullname, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $fullname, $password_hash);
        $stmt->fetch();
        
        if ($id && password_verify($password, $password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['fullname'] = $fullname;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
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
    <title>Citizen Login - Municipal System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            border: 1px solid #e0e0e0;
        }
        .login-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #0066cc;
        }
        .login-header i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .login-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }
        .login-header p {
            color: #ecf5ff;
            margin: 0;
        }
        .login-body {
            padding: 40px;
        }
        .form-label {
            color: #333333;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 1px solid #e0e0e0;
            padding: 12px;
            border-radius: 5px;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.15);
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            transition: color 0.3s;
        }
        .password-toggle:hover {
            color: #0066cc;
        }
        .btn-login {
            background-color: #0066cc;
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #0052a3;
            box-shadow: 0 4px 8px rgba(0, 102, 204, 0.3);
            color: white;
        }
        .link-primary {
            color: #0066cc;
            text-decoration: none;
            transition: color 0.3s;
        }
        .link-primary:hover {
            color: #0052a3;
            text-decoration: underline;
        }
        .alert {
            border-radius: 5px;
            border-left: 4px solid;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-left-color: #e74c3c;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-user-circle"></i>
                        <h2>Citizen Login</h2>
                        <p>Welcome back! Please login to your account</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address
                                </label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="Enter your email" required 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Password
                                </label>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" 
                                           class="form-control" 
                                           placeholder="Enter your password" required>
                                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                            </div>

                            <div class="mb-3 text-end">
                                <a href="forgot_password.php" class="link-primary">
                                    <i class="fas fa-question-circle"></i> Forgot Password?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>

                            <div class="text-center">
                                <p class="mb-2" style="color: #333333;">
                                    Don't have an account? 
                                    <a href="register.php" class="link-primary fw-bold">Register here</a>
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
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>