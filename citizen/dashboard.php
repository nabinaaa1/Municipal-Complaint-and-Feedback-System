<?php include("../includes/header.php"); ?>
<?php include("../includes/session.php"); ?>

<h3>Welcome, <?php echo $_SESSION['fullname']; ?> 👋</h3>
<hr>

<div class="row text-center">
    <div class="col-md-4">
        <div class="card-status status-pending">Pending: <?= $pendingCount ?></div>
    </div>
    <div class="col-md-4">
        <div class="card-status status-progress">In Progress: <?= $progressCount ?></div>
    </div>
    <div class="col-md-4">
        <div class="card-status status-resolved">Resolved: <?= $resolvedCount ?></div>
    </div>
</div>

<br>
<a href="submit_complaint.php" class="btn btn-primary">Submit New Complaint</a>
<?php include("../includes/footer.php"); ?>
