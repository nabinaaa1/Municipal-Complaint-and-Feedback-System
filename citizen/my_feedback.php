<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT feedback.*, complaints.category 
                        FROM feedback 
                        LEFT JOIN complaints ON feedback.complaint_id=complaints.id 
                        WHERE feedback.user_id=$user_id
                        ORDER BY feedback.created_at DESC");

echo "<h2>My Feedback</h2>";
echo "<table border='1'>
<tr><th>ID</th><th>Complaint</th><th>Rating</th><th>Message</th><th>Date</th></tr>";

while($row = $result->fetch_assoc()) {
    $complaint = $row['category'] ?? "N/A";
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$complaint}</td>
    <td>{$row['rating']}</td>
    <td>{$row['message']}</td>
    <td>{$row['created_at']}</td>
    </tr>";
}
echo "</table>";