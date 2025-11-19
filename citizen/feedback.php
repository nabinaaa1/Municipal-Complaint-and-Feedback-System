<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Optional: fetch citizen complaints for dropdown
$complaints_result = $conn->query("SELECT id, category FROM complaints WHERE user_id=$user_id ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = !empty($_POST['complaint_id']) ? $_POST['complaint_id'] : NULL;
    $rating = $_POST['rating'];
    $message = $_POST['message'];

    if(empty($rating) || empty($message)) {
        $error = "Rating and message are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, complaint_id, rating, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $complaint_id, $rating, $message);
        if($stmt->execute()) {
            $success = "Feedback submitted successfully!";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<h2>Submit Feedback</h2>
<form method="post">
    Complaint (optional):
    <select name="complaint_id">
        <option value="">Select complaint (optional)</option>
        <?php while($row = $complaints_result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['category']}</option>";
        } ?>
    </select><br>
    Rating: 
    <select name="rating" required>
        <option value="">Select rating</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
    </select><br>
    Message:<br>
    <textarea name="message" required></textarea><br>
    <button type="submit">Submit Feedback</button>
</form>

<?php if(isset($error)) echo $error; ?>
<?php if(isset($success)) echo $success; ?>