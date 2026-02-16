<?php
session_start();
include_once('function/connect.php');

// Get POST data
$dept_id = $_POST['dept_id'] ?? '';
$degree_id = $_POST['degree_id'] ?? '';
$type = $_POST['type'] ?? '';

// Validate input
if (empty($dept_id) || empty($degree_id) || empty($type)) {
    $_SESSION['flash_message'] = "Please fill all fields!";
    $_SESSION['flash_type'] = "error";
    header("Location: add_degree.php");
    exit();
}

$degree_query = mysqli_query($conn, "SELECT degree FROM degree_new WHERE id = '$degree_id'");
$degree_row = mysqli_fetch_assoc($degree_query);
$degree = $degree_row['degree'] ?? '';

$check_query = mysqli_query($conn, "SELECT * FROM programme_cgpa WHERE degree_id = '$degree_id'");
if (mysqli_num_rows($check_query) > 0) {
    // Update existing record
    $update_query = "UPDATE programme_cgpa SET degree = ?, type = ? WHERE degree_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $degree, $type, $degree_id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['flash_message'] = "Degree updated successfully!";
    $_SESSION['flash_type'] = "success";
} else {
    // Insert new record
    $insert_query = "INSERT INTO programme_cgpa (degree_id, degree, type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iss", $degree_id, $degree, $type);
    $stmt->execute();
    $stmt->close();
    $_SESSION['flash_message'] = "Degree added successfully!";
    $_SESSION['flash_type'] = "success";
}

header("Location: add_degree.php");
exit();
?>