<?php
session_start();
include_once('../function/script.php');

header('Content-Type: application/json');

if (!isset($_POST['id']) || !isset($_POST['matric']) || !isset($_POST['effectivedate'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = $_POST['id'];
$matric = $_POST['matric'];
$effectivedate = $_POST['effectivedate'];

$delete_query = "DELETE FROM testscore WHERE id = ? AND matric = ? AND effectivedate = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, "sss", $id, $matric, $effectivedate);

$success = mysqli_stmt_execute($stmt);

echo json_encode(['success' => $success]); 