<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . "/config.php";

// Check if user or admin is logged in
$is_admin = isset($_SESSION['admin_id']);
$is_citizen = isset($_SESSION['user_id']);

// Get statistics for dashboards
if ($is_admin) {
    // Admin statistics
    $result = $conn->query("SELECT 
        COUNT(*) as totalComplaints,
        SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status='In Progress' THEN 1 ELSE 0 END) as progress,
        SUM(CASE WHEN status='Resolved' THEN 1 ELSE 0 END) as resolved
        FROM complaints");
    
    $stats = $result->fetch_assoc();
    $totalComplaints = $stats['totalComplaints'] ?? 0;
    $pending = $stats['pending'] ?? 0;
    $progress = $stats['progress'] ?? 0;
    $resolved = $stats['resolved'] ?? 0;
}

if ($is_citizen) {
    // Citizen statistics
    $user_id = $_SESSION['user_id'];
    $result = $conn->query("SELECT 
        SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) as pendingCount,
        SUM(CASE WHEN status='In Progress' THEN 1 ELSE 0 END) as progressCount,
        SUM(CASE WHEN status='Resolved' THEN 1 ELSE 0 END) as resolvedCount
        FROM complaints WHERE user_id='$user_id'");
    
    $stats = $result->fetch_assoc();
    $pendingCount = $stats['pendingCount'] ?? 0;
    $progressCount = $stats['progressCount'] ?? 0;
    $resolvedCount = $stats['resolvedCount'] ?? 0;
}
?>