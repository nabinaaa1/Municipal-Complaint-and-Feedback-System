<?php
session_start();
require_once "../config.php";
include("../includes/header.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path = NULL;

    // Validate input
    if (empty($category)) {
        $error = "Category is required.";
    } elseif (empty($description) || strlen($description) < 10) {
        $error = "Description must be at least 10 characters long.";
    } else {
        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $filename = $file['name'];
            $filesize = $file['size'];
            $filetmp = $file['tmp_name'];
            $fileerror = $file['error'];

            // Max 5MB
            if ($filesize > 5 * 1024 * 1024) {
                $error = "File size must not exceed 5MB.";
            } else {
                $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileext, $allowed_types)) {
                    $error = "Only JPG, JPEG, PNG, GIF files are allowed.";
                } else {
                    // Create unique filename
                    $new_filename = uniqid('complaint_') . '.' . $fileext;
                    $upload_path = "../uploads/" . $new_filename;

                    // Create uploads folder if it doesn't exist
                    if (!is_dir("../uploads/")) {
                        mkdir("../uploads/", 0755, true);
                    }

                    if (move_uploaded_file($filetmp, $upload_path)) {
                        $image_path = "uploads/" . $new_filename;
                    } else {
                        $error = "Failed to upload file.";
                    }
                }
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description, image_path, status) VALUES (?, ?, ?, ?, 'Pending')");
            
            if (!$stmt) {
                $error = "Database error: " . $conn->error;
            } else {
                $stmt->bind_param("isss", $user_id, $category, $description, $image_path);
                
                if ($stmt->execute()) {
                    $success = "✅ Complaint submitted successfully! Your complaint ID is #" . $stmt->insert_id;
                    $_POST = []; // Clear form
                } else {
                    $error = "Error submitting complaint: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <i class="fas fa-plus-circle"></i> Submit New Complaint
                </h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <option value="Road Maintenance">Road Maintenance</option>
                            <option value="Street Light">Street Light</option>
                            <option value="Water Supply">Water Supply</option>
                            <option value="Sanitation">Sanitation</option>
                            <option value="Traffic">Traffic</option>
                            <option value="Parks">Parks & Recreation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="5" 
                                  required placeholder="Describe your complaint in detail..." 
                                  minlength="10"></textarea>
                        <small class="text-muted">Minimum 10 characters required</small>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image (Optional)</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/gif">
                        <small class="text-muted">Max 5MB. Allowed: JPG, PNG, GIF</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Submit Complaint
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>