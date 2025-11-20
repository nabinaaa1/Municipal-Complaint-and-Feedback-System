<?php
session_start();
require_once "../config.php";
include("../includes/header.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Get filters with proper sanitization
$status_filter = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) && !empty($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT complaints.*, users.fullname FROM complaints 
    JOIN users ON complaints.user_id = users.id 
    WHERE 1=1";

if ($status_filter) {
    $allowed_statuses = ['Pending', 'In Progress', 'Resolved'];
    if (in_array($status_filter, $allowed_statuses)) {
        $query .= " AND complaints.status='" . $conn->real_escape_string($status_filter) . "'";
    }
}

if ($category_filter) {
    $query .= " AND complaints.category LIKE '%" . $conn->real_escape_string($category_filter) . "%'";
}

$query .= " ORDER BY complaints.created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4"><i class="fas fa-tasks"></i> All Complaints</h2>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" class="row g-2">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Pending" <?= ($status_filter=='Pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="In Progress" <?= ($status_filter=='In Progress') ? 'selected' : '' ?>>In Progress</option>
                    <option value="Resolved" <?= ($status_filter=='Resolved') ? 'selected' : '' ?>>Resolved</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="category" class="form-control" placeholder="Search Category" value="<?= htmlspecialchars($category_filter) ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <div class="col-md-3">
                <a href="complaints.php" class="btn btn-secondary w-100">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Complaints Table -->
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Citizen</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Image</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) { 
        ?>
            <tr>
                <td><?= htmlspecialchars($row['id']); ?></td>
                <td><?= htmlspecialchars($row['fullname']); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>
                <td><?= htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>

                <!-- Status Color -->
                <td>
                    <?php 
                        if ($row['status'] == "Pending") echo "<span class='badge bg-warning'>Pending</span>";
                        elseif ($row['status'] == "In Progress") echo "<span class='badge bg-primary'>In Progress</span>";
                        elseif ($row['status'] == "Resolved") echo "<span class='badge bg-success'>Resolved</span>";
                    ?>
                </td>

                <!-- Image Preview -->
                <td>
                    <?php if ($row['image_path'] && file_exists("../" . $row['image_path'])) { ?>
                        <img src="../<?= htmlspecialchars($row['image_path']); ?>" width="60" class="img-thumbnail cursor-pointer" 
                             data-bs-toggle="modal" data-bs-target="#imageModal" 
                             onclick="setImage('<?= htmlspecialchars($row['image_path']); ?>')">
                    <?php } else { ?>
                        <span class="text-muted">No Image</span>
                    <?php } ?>
                </td>

                <td><?= htmlspecialchars($row['created_at']); ?></td>

                <!-- Action Buttons -->
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="update_status.php?id=<?= $row['id']; ?>&status=Pending" class="btn btn-warning">
                            <i class="fas fa-clock"></i>
                        </a>
                        <a href="update_status.php?id=<?= $row['id']; ?>&status=In Progress" class="btn btn-primary">
                            <i class="fas fa-spinner"></i>
                        </a>
                        <a href="update_status.php?id=<?= $row['id']; ?>&status=Resolved" class="btn btn-success">
                            <i class="fas fa-check"></i>
                        </a>
                    </div>
                </td>
            </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='8' class='text-center text-muted'>No complaints found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complaint Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function setImage(path) {
    document.getElementById('modalImage').src = '../' + path;
}
</script>

<?php include("../includes/footer.php"); ?>