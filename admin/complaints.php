<?php
session_start();
require_once "../config.php";
include("../includes/header.php"); // UI & Bootstrap

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Get filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';

$query = "SELECT complaints.*, users.fullname FROM complaints 
    JOIN users ON complaints.user_id = users.id 
    WHERE 1";
if ($status_filter) $query .= " AND complaints.status='$status_filter'";
if ($category_filter) $query .= " AND complaints.category LIKE '%$category_filter%'";
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
?>

<h2 class="mb-4">📋 All Complaints</h2>

<!-- Filter Form -->
<form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="Pending" <?= ($status_filter=='Pending') ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= ($status_filter=='In Progress') ? 'selected' : '' ?>>In Progress</option>
            <option value="Resolved" <?= ($status_filter=='Resolved') ? 'selected' : '' ?>>Resolved</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="text" name="category" class="form-control" placeholder="Search Category"
        value="<?= $category_filter ?>">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-3">
        <a href="complaints.php" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<!-- Complaints Table -->
<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Citizen</th>
            <th>Category</th>
            <th>Description</th>
            <th>Status</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['fullname']; ?></td>
            <td><?= $row['category']; ?></td>
            <td><?= $row['description']; ?></td>

            <!-- Status Color -->
            <td>
                <?php 
                    if ($row['status'] == "Pending") echo "<span class='status-pending'>Pending</span>";
                    elseif ($row['status'] == "In Progress") echo "<span class='status-progress'>In Progress</span>";
                    elseif ($row['status'] == "Resolved") echo "<span class='status-resolved'>Resolved</span>";
                ?>
            </td>

            <!-- Image Preview -->
            <td>
                <?php if ($row['image_path']) { ?>
                    <img src="../<?= $row['image_path']; ?>" width="80" class="img-thumbnail">
                <?php } else { ?>
                    <span class="text-muted">No Image</span>
                <?php } ?>
            </td>

            <!-- Action Buttons -->
            <td>
                <div class="btn-group">
                    <a href="update_status.php?id=<?= $row['id']; ?>&status=Pending" class="btn btn-sm btn-warning">Pending</a>
                    <a href="update_status.php?id=<?= $row['id']; ?>&status=In Progress" class="btn btn-sm btn-primary">In Progress</a>
                    <a href="update_status.php?id=<?= $row['id']; ?>&status=Resolved" class="btn btn-sm btn-success">Resolved</a>
                </div>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
