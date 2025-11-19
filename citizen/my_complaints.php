<?php
session_start();
require_once "../config.php";
include("../includes/header.php"); // Add this for UI + Navbar

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM complaints WHERE user_id=$user_id ORDER BY created_at DESC");
?>

<div class="container">
    <h2 class="mb-4">📌 My Complaints</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Image</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['category']; ?></td>
                <td><?= $row['description']; ?></td>
                
                <!-- 🔹 STATUS COLOR CODING HERE -->
                <td>
                    <?php 
                        if ($row['status'] == 'Pending') echo "<span class='status-pending'>Pending</span>";
                        elseif ($row['status'] == 'In Progress') echo "<span class='status-progress'>In Progress</span>";
                        elseif ($row['status'] == 'Resolved') echo "<span class='status-resolved'>Resolved</span>";
                        else echo "<span class='badge bg-secondary'>Unknown</span>";
                    ?>
                </td>

                <td>
                    <?php if ($row['image_path']) { ?>
                        <img src="../<?= $row['image_path']; ?>" width="100" class="img-thumbnail">
                    <?php } else { ?>
                        <span class="text-muted">No Image</span>
                    <?php } ?>
                </td>

                <td><?= $row['created_at']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
