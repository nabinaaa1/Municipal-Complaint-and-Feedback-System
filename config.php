<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

$host = "localhost";
$dbname = "municipal_system";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

define('BASE_URL', 'http://localhost/municipal-system/');
define('UPLOAD_DIR', $_SERVER['DOCUMENT_ROOT'] . '/municipal-system/uploads/');
?>