<?php include("../includes/header.php"); ?>
<?php include("../includes/session.php"); ?>

<h3>Welcome, Admin</h3>
<hr>

<div class="row text-center">
    <div class="col-md-3"><div class="card-status bg-secondary">Total: <?= $totalComplaints ?></div></div>
    <div class="col-md-3"><div class="card-status status-pending">Pending: <?= $pending ?></div></div>
    <div class="col-md-3"><div class="card-status status-progress">In Progress: <?= $progress ?></div></div>
    <div class="col-md-3"><div class="card-status status-resolved">Resolved: <?= $resolved ?></div></div>
</div>

<br>
<a href="complaints.php" class="btn btn-warning">Manage Complaints</a>
<?php include("../includes/footer.php"); ?>
