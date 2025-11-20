<?php
session_start();
require_once "../config.php";
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$complaints_result = $conn->query("SELECT id, category FROM complaints WHERE user_id=$user_id ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = !empty($_POST['complaint_id']) ? (int)$_POST['complaint_id'] : NULL;
    $rating = (int)($_POST['rating'] ?? 0);
    $message = trim($_POST['message'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = "Rating must be between 1 and 5.";
    } elseif (empty($message) || strlen($message) < 5) {
        $error = "Message must be at least 5 characters.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, complaint_id, rating, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $complaint_id, $rating, $message);
        
        if($stmt->execute()) {
            $success = "✅ Thank you! Your feedback has been submitted.";
            $_POST = [];
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="fas fa-star"></i> Submit Feedback
                </h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Related Complaint (Optional)</label>
                        <select name="complaint_id" class="form-select">
                            <option value="">-- Select complaint --</option>
                            <?php while($row = $complaints_result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['category']) . "</option>";
                            } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <select name="rating" class="form-select" required>
                            <option value="">-- Select rating --</option>
                            <option value="1">⭐ Poor</option>
                            <option value="2">⭐⭐ Fair</option>
                            <option value="3">⭐⭐⭐ Good</option>
                            <option value="4">⭐⭐⭐⭐ Very Good</option>
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="4" required 
                                  placeholder="Share your feedback..." minlength="5"></textarea>
                        <small class="text-muted">Min 5 characters</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check"></i> Submit Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>