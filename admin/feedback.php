<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Optional filters
$rating_filter = isset($_GET['rating']) ? $_GET['rating'] : '';
$complaint_filter = isset($_GET['complaint_id']) ? $_GET['complaint_id'] : '';

$query = "SELECT feedback.*, users.fullname, complaints.category 
          FROM feedback 
          JOIN users ON feedback.user_id=users.id 
          LEFT JOIN complaints ON feedback.complaint_id=complaints.id 
          WHERE 1";

if($rating_filter) $query .= " AND rating='$rating_filter'";
if($complaint_filter) $query .= " AND complaint_id='$complaint_filter'";

$query .= " ORDER BY feedback.created_at DESC";

$result = $conn->query($query);
?>

<h2>All Feedback</h2>

<form method="get">
    Rating: 
    <select name="rating">
        <option value="">All</option>
        <?php for($i=1;$i<=5;$i++) echo "<option value='$i'>$i</option>"; ?>
    </select>
    Complaint ID: <input type="text" name="complaint_id" value="<?php echo $complaint_filter; ?>">
    <button type="submit">Filter</button>
</form>

<table border="1">
<tr><th>ID</th><th>Citizen</th><th>Complaint</th><th>Rating</th><th>Message</th><th>Date</th></tr>
<?php
while($row = $result->fetch_assoc()) {
    $complaint = $row['category'] ?? "N/A";
    echo "<tr>
    <td>{$row['id']}</td>
    <td>{$row['fullname']}</td>
    <td>{$complaint}</td>
    <td>{$row['rating']}</td>
    <td>{$row['message']}</td>
    <td>{$row['created_at']}</td>
    </tr>";
}
?>
</table>
