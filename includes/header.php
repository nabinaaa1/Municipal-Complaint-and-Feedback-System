<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/municipal-system/includes/language.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechinagar Municipality - Complaint System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/municipal-system/assets/css/style.css">
    <style>
        .top-header {
            background-color: white;
            padding: 10px 0;
            border-bottom: 3px solid #b91c1c;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .municipality-logo {
            height: 60px;
            width: auto;
        }
        
        .municipality-title h1 {
            color: #b91c1c;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .municipality-title p {
            color: #2c3e50;
            font-size: 0.8rem;
            margin: 3px 0 0 0;
        }
        
        .lang-switcher {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .lang-btn {
            padding: 5px 15px;
            border: 2px solid #2563a8;
            border-radius: 5px;
            background: white;
            color: #2563a8;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .lang-btn:hover {
            background: #2563a8;
            color: white;
        }
        
        .lang-btn.active {
            background: #2563a8;
            color: white;
        }
    </style>
</head>
<body class="bg-light">

<!-- Top Header with Logo -->
<div class="top-header">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="/municipal-system/assets/images/mechinagar-logo.png" alt="Mechinagar Municipality" class="municipality-logo">
            </div>
            <div class="col">
                <div class="municipality-title">
                    <h1><?= t('municipality_title'); ?></h1>
                    <p><?= t('municipality_subtitle'); ?></p>
                </div>
            </div>
            <div class="col-auto">
                <div class="lang-switcher">
                    <a href="?lang=en" class="lang-btn <?= getCurrentLang() == 'en' ? 'active' : ''; ?>">
                        English
                    </a>
                    <a href="?lang=ne" class="lang-btn <?= getCurrentLang() == 'ne' ? 'active' : ''; ?>">
                        नेपाली
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #2c3e50;">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="/municipal-system/">
            <i class="fas fa-comments"></i> <?= t('complaint_system'); ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/dashboard.php">
                            <i class="fas fa-home"></i> <?= t('dashboard'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/submit_complaint.php">
                            <i class="fas fa-plus"></i> <?= t('new_complaint'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/my_complaints.php">
                            <i class="fas fa-list"></i> <?= t('my_complaints'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/feedback.php">
                            <i class="fas fa-star"></i> <?= t('feedback'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['fullname']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/logout.php">
                            <i class="fas fa-sign-out-alt"></i> <?= t('logout'); ?>
                        </a>
                    </li>
                <?php elseif(isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> <?= t('dashboard'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/complaints.php">
                            <i class="fas fa-tasks"></i> <?= t('manage_complaints'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/feedback.php">
                            <i class="fas fa-comments"></i> <?= t('view_feedback'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/statistics.php">
                            <i class="fas fa-chart-bar"></i> <?= t('statistics'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="fas fa-user-shield"></i> <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/logout.php">
                            <i class="fas fa-sign-out-alt"></i> <?= t('logout'); ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/login.php">
                            <i class="fas fa-sign-in-alt"></i> <?= t('citizen_login'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/citizen/register.php">
                            <i class="fas fa-user-plus"></i> <?= t('register'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/municipal-system/admin/login.php">
                            <i class="fas fa-lock"></i> <?= t('admin_login'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 mt-4">