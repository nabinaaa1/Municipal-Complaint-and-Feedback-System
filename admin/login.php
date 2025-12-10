<?php
session_start();
require_once "../config.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM admins WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $name, $password_hash);
        $stmt->fetch();
        
        if ($id && password_verify($password, $password_hash)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
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
    <title>Admin Login - Municipal System</title>
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
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #27ae60;
            position: relative;
        }
        .admin-badge {
            background: #f39c12;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .login-header i.fa-user-shield {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .login-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }
        .login-header p {
            color: #d4edda;
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
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.15);
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
            color: #27ae60;
        }
        .btn-admin {
            background-color: #27ae60;
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
            background-color: #229954;
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
            color: white;
        }
        .link-primary {
            color: #2563a8;
            text-decoration: none;
            transition: color 0.3s;
        }
        .link-primary:hover {
            color: #1e4f8a;
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
        .alert-info {
            background-color: #e8f1f8;
            border-left-color: #2563a8;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="login-header">
                        <span class="admin-badge">
                            <i class="fas fa-shield-alt"></i> ADMIN ACCESS
                        </span>
                        <div>
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2>Administrator Login</h2>
                        <p>Secure access to system management</p>
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
                                    <i class="fas fa-user"></i> Username
                                </label>
                                <input type="text" name="username" class="form-control" 
                                       placeholder="Enter your username" required 
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                       autocomplete="username">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Password
                                </label>
                                <div style="position: relative;">
                                    <input type="password" name="password" id="password" 
                                           class="form-control" 
                                           placeholder="Enter your password" required
                                           autocomplete="current-password">
                                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-admin w-100 mb-3">
                                <i class="fas fa-sign-in-alt"></i> Login as Administrator
                            </button>

                            <div class="text-center">
                                <a href="../index.php" class="text-muted text-decoration-none">
                                    <i class="fas fa-arrow-left"></i> Back to Home
                                </a>
                            </div>
                        </form>

                        <hr class="my-4" style="border-color: #e0e0e0;">
                        
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> 
                            <strong>For Citizens:</strong> Please use the 
                            <a href="../citizen/login.php" class="link-primary">Citizen Login</a> page.
                        </div>
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