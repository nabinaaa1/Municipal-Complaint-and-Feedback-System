<?php
session_start();
require_once "../config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $image_path = NULL;

    // Validate input
    if (empty($category) || empty($description)) {
        $error = "Category and description are required.";
    } else {
        // Handle file upload
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Allow only jpg, png, jpeg
            $allowed_types = ['jpg','jpeg','png'];
            if (!in_array($imageFileType, $allowed_types)) {
                $error = "Only JPG, JPEG, PNG files are allowed.";
            } else {
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $image_path = "uploads/" . basename($_FILES["image"]["name"]);
            }
        }

        if (!isset($error)) {
            $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description, image_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $category, $description, $image_path);
            if ($stmt->execute()) {
                $success = "Complaint submitted successfully!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<h2>Submit Complaint</h2>
<form method="post" enctype="multipart/form-data">
    Category: <input type="text" name="category" required><br>
    Description: <textarea name="description" required></textarea><br>
    Image (optional): <input type="file" name="image"><br>
    <button type="submit">Submit Complaint</button>
</form>
<?php if(isset($error)) echo $error; ?>
<?php if(isset($success)) echo $success; ?>