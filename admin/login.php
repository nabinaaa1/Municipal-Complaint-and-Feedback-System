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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
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
        .btn-admin-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            color: white;
        }
        .btn-admin-custom:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            color: white;
        }
        .admin-badge {
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
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
                            <i class="fas fa-user-shield fa-4x mb-3"></i>
                        </div>
                        <h2 class="mb-0">Administrator Login</h2>
                        <p class="mb-0">Secure access to system management</p>
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
                                <input type="text" name="username" class="form-control form-control-lg" 
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
                                           class="form-control form-control-lg" 
                                           placeholder="Enter your password" required
                                           autocomplete="current-password">
                                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-admin-custom btn-lg w-100 mb-3">
                                <i class="fas fa-sign-in-alt"></i> Login as Administrator
                            </button>

                            <div class="text-center">
                                <a href="../index.php" class="text-muted text-decoration-none">
                                    <i class="fas fa-arrow-left"></i> Back to Home
                                </a>
                            </div>
                        </form>

                        <hr class="my-4">
                        
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> 
                            <strong>For Citizens:</strong> Please use the 
                            <a href="../citizen/login.php" class="alert-link">Citizen Login</a> page.
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