<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fullname, email, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($fullname, $email, $created_at);
$stmt->fetch();
?>

<h2>My Profile</h2>
Full Name: <?php echo $fullname; ?><br>
Email: <?php echo $email; ?><br>
Joined: <?php echo $created_at; ?><br>