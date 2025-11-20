<?php
session_start();
require_once "../config.php";
include("../includes/header.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Average rating
$result = $conn->query("SELECT AVG(rating) AS avg_rating, COUNT(*) as total_feedback FROM feedback");
$row = $result->fetch_assoc();
$avg_rating = number_format($row['avg_rating'] ?? 0, 2);
$total_feedback = $row['total_feedback'] ?? 0;

// Most complained category
$result = $conn->query("SELECT complaints.category, COUNT(*) AS total 
                        FROM complaints 
                        GROUP BY complaints.category 
                        ORDER BY total DESC");

$categories = [];
while($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Status breakdown
$result = $conn->query("SELECT status, COUNT(*) as count FROM complaints GROUP BY status");
$status_data = [];
while($row = $result->fetch_assoc()) {
    $status_data[$row['status']] = $row['count'];
}
?>

<h2 class="mb-4"><i class="fas fa-chart-bar"></i> System Statistics</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Average Rating</h5>
                <h2><?= $avg_rating; ?> / 5.0</h2>
                <p class="card-text">From <?= $total_feedback; ?> feedbacks</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Feedbacks</h5>
                <h2><?= $total_feedback; ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Top Category</h5>
                <h2><?= htmlspecialchars($categories[0]['category'] ?? 'N/A'); ?></h2>
                <p><?= $categories[0]['total'] ?? 0; ?> complaints</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="