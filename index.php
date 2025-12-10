<?php
session_start();
require_once "includes/language.php";

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
    <title>Mechinagar Municipality - Complaint Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .top-header {
            background-color: white;
            padding: 15px 0;
            border-bottom: 3px solid #b91c1c;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .municipality-logo {
            height: 80px;
            width: auto;
        }
        
        .municipality-title {
            margin: 0;
            padding: 0;
        }
        
        .municipality-title h1 {
            color: #b91c1c;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }
        
        .municipality-title p {
            color: #2c3e50;
            font-size: 0.95rem;
            margin: 5px 0 0 0;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 50px 0;
        }
        
        .hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            color: #e8f1f8;
            margin-bottom: 1.5rem;
        }
        
        .access-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            background: white;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .access-card:hover {
            border-color: #2563a8;
            box-shadow: 0 8px 20px rgba(37,99,168,0.15);
            transform: translateY(-5px);
        }
        
        .access-card .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .access-card.citizen .icon {
            color: #2563a8;
        }
        
        .access-card.admin .icon {
            color: #27ae60;
        }
        
        .access-card h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .access-card p {
            color: #333333;
            margin-bottom: 1.5rem;
        }
        
        .feature-section {
            padding: 60px 0;
            background-color: #f5f7fa;
        }
        
        .feature-box {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover {
            border-color: #2563a8;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .feature-box i {
            font-size: 3rem;
            color: #2563a8;
            margin-bottom: 1rem;
        }
        
        .feature-box h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .feature-box p {
            color: #333333;
            font-size: 0.95rem;
        }
        
        .btn-citizen {
            background-color: #2563a8;
            border-color: #2563a8;
            color: white;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-citizen:hover {
            background-color: #1e4f8a;
            border-color: #1e4f8a;
            color: white;
            box-shadow: 0 4px 8px rgba(37,99,168,0.3);
        }
        
        .btn-citizen-outline {
            background-color: transparent;
            border: 2px solid #2563a8;
            color: #2563a8;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-citizen-outline:hover {
            background-color: #2563a8;
            color: white;
        }
        
        .btn-admin {
            background-color: #27ae60;
            border-color: #27ae60;
            color: white;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            background-color: #229954;
            border-color: #229954;
            color: white;
            box-shadow: 0 4px 8px rgba(39,174,96,0.3);
        }
        
        .lang-switcher {
            display: flex;
            gap: 10px;
        }
        
        .lang-btn {
            padding: 8px 20px;
            border: 2px solid white;
            border-radius: 5px;
            background: transparent;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.95rem;
        }
        
        .lang-btn:hover {
            background: white;
            color: #2c3e50;
        }
        
        .lang-btn.active {
            background: white;
            color: #2c3e50;
        }
        
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Top Header with Logo -->
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <!-- Place your logo image here -->
                    <img src="assets/images/mechinagar-logo.png" alt="Mechinagar Municipality Logo" class="municipality-logo">
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

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <i class="fas fa-comments" style="font-size: 3.5rem; margin-bottom: 1rem;"></i>
            <h1 class="hero-title"><?= t('main_title'); ?></h1>
            <p class="hero-subtitle">
                <?= t('main_subtitle'); ?>
            </p>
        </div>
    </div>

    <!-- Access Cards Section -->
    <div class="container my-5">
        <div class="row justify-content-center g-4">
            <!-- Citizen Card -->
            <div class="col-md-5">
                <div class="access-card citizen">
                    <div class="text-center">
                        <i class="fas fa-users icon"></i>
                        <h3><?= t('for_citizens'); ?></h3>
                        <p><?= t('citizens_desc'); ?></p>
                        
                        <div class="d-grid gap-2">
                            <a href="citizen/login.php" class="btn btn-citizen">
                                <i class="fas fa-sign-in-alt"></i> <?= t('login'); ?>
                            </a>
                            <a href="citizen/register.php" class="btn btn-citizen-outline">
                                <i class="fas fa-user-plus"></i> <?= t('register'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Card -->
            <div class="col-md-5">
                <div class="access-card admin">
                    <div class="text-center">
                        <i class="fas fa-user-shield icon"></i>
                        <h3><?= t('for_administrators'); ?></h3>
                        <p><?= t('admin_desc'); ?></p>
                        
                        <div class="d-grid gap-2">
                            <a href="admin/login.php" class="btn btn-admin">
                                <i class="fas fa-lock"></i> <?= t('admin_login_btn'); ?>
                            </a>
                            <div style="height: 48px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="feature-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="color: #2c3e50; font-weight: 700;"><?= t('system_features'); ?></h2>
                <p style="color: #333333;"><?= t('features_subtitle'); ?></p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-box">
                        <i class="fas fa-plus-circle"></i>
                        <h4><?= t('submit_complaints'); ?></h4>
                        <p><?= t('submit_desc'); ?></p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="feature-box">
                        <i class="fas fa-tasks"></i>
                        <h4><?= t('track_status'); ?></h4>
                        <p><?= t('track_desc'); ?></p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="feature-box">
                        <i class="fas fa-star"></i>
                        <h4><?= t('give_feedback'); ?></h4>
                        <p><?= t('feedback_desc'); ?></p>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="feature-box">
                        <i class="fas fa-chart-bar"></i>
                        <h4><?= t('view_statistics'); ?></h4>
                        <p><?= t('stats_desc'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-2" style="color: #e8f1f8; font-weight: 600;">
                <?= getCurrentLang() == 'en' ? 'Mechinagar Municipality, Koshi Province, Nepal' : 'मेचीनगर नगरपालिका, कोशी प्रदेश, नेपाल'; ?>
            </p>
            <p style="color: #e0e0e0; font-size: 0.85rem; margin-top: 10px;">
                © <?= date('Y'); ?> <?= getCurrentLang() == 'en' ? 'Mechinagar Municipality. All rights reserved.' : 'मेचीनगर नगरपालिका। ' . t('all_rights_reserved'); ?>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>