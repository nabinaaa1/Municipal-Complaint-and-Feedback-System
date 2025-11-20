<?php
session_start();
require_once "../config.php";
include("../includes/header.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$rating_filter = isset($_GET['rating']) && !empty($_GET['rating']) ? (int)$_GET['rating'] : '';
$complaint_filter = isset($_GET['complaint_id']) && !empty($_GET['complaint_id']) ? (int)$_GET['complaint_id'] : '';

$query = "SELECT feedback.*, users.fullname, complaints.category 
          FROM feedback 
          JOIN users ON feedback.user_id=users.id 
          LEFT JOIN complaints ON feedback.complaint_id=complaints.id 
          WHERE 1=1";

if($rating_filter && $rating_filter >= 1 && $rating_filter <= 5) {
    $query .= " AND feedback.rating=$rating_filter";
}

if($complaint_filter) {
    $query .= " AND feedback.complaint_id=$complaint_filter";
}

$query .= " ORDER BY feedback.created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4"><i class="fas fa-comments"></i> All Feedback</h2>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Filter by Rating</label>
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    <?php for($i=1;$i<=5;$i++) {
                        $selected = ($rating_filter == $i) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i ⭐</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter by Complaint ID</label>
                <input type="number" name="complaint_id" class="form-control" 
                       placeholder="Enter complaint ID" value="<?= htmlspecialchars($complaint_filter) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="feedback.php" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Feedback Table -->
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Citizen</th>
                <th>Complaint Category</th>
                <th>Rating</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $complaint = $row['category'] ?? "N/A";
                $rating_stars = str_repeat('⭐', $row['rating']);
        ?>
            <tr>
                <td><?= htmlspecialchars($row['id']); ?></td>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td><?= htmlspecialchars($complaint); ?></td>
                <td>
                    <span class="badge bg-warning text-dark">
                        <?= $rating_stars; ?> (<?= $row['rating']; ?>/5)
                    </span>
                </td>
                <td><?= htmlspecialchars(substr($row['message'], 0, 100)); ?></td>
                <td><?= htmlspecialchars($row['created_at']); ?></td>
            </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6' class='text-center text-muted'>No feedback found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>