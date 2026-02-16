<?php
session_start();
include_once('function/script.php');

header('Content-Type: application/json');

$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
if (!$course_id) {
    echo json_encode(['success' => false, 'error' => 'No course specified']);
    exit;
}

// Set status to 3 for archived
$stmt = $conn->prepare("UPDATE course_new SET status2 = 3 WHERE id = ?");
$stmt->bind_param("i", $course_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close(); 