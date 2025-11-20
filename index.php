<?php
session_start();

// Redirect based on login status
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
    exit;
} elseif (isset($_SESSION['user_id'])) {
    header("Location: citizen/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipal Complaint System - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="fas fa-city"></i> Municipal Complaint System
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 mb-4">
                    <i class="fas fa-city text-primary"></i><br>
                    Welcome to Municipal Complaint System
                </h1>
                <p class="lead mb-5">
                    Report civic issues, track complaints, and help improve your community
                </p>
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-user fa-3x text-primary mb-3"></i>
                                <h3>Citizens</h3>
                                <p>Submit and track your complaints</p>
                                <a href="citizen/login.php" class="btn btn-primary me-2">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                                <a href="citizen/register.php" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4">
                                <i class="fas fa-user-shield fa-3x text-success mb-3"></i>
                                <h3>Administrators</h3>
                                <p>Manage complaints and system</p>
                                <a href="admin/login.php" class="btn btn-success">
                                    <i class="fas fa-lock"></i> Admin Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>