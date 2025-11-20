<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipal Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/municipal-system/assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/municipal-system/">
            <i class="fas fa-city"></i> Municipal System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/submit_complaint.php">
                            <i class="fas fa-plus"></i> New Complaint
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/my_complaints.php">
                            <i class="fas fa-list"></i> My Complaints
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/feedback.php">
                            <i class="fas fa-star"></i> Give Feedback
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/profile.php">
                            <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/complaints.php">
                            <i class="fas fa-tasks"></i> Manage Complaints
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/feedback.php">
                            <i class="fas fa-comments"></i> View Feedback
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/statistics.php">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/logout.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/login.php">
                            <i class="fas fa-sign-in-alt"></i> Citizen Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/register.php">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/login.php">
                            <i class="fas fa-lock"></i> Admin Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">