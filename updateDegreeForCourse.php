<?php
session_start();
include_once('function/script.php');

header('Content-Type: application/json');

$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
$degree_id = isset($_POST['degree_id']) ? $_POST['degree_id'] : '';
if (!$course_id || !$degree_id) {
    echo json_encode(['success' => false, 'error' => 'Missing course or degree.']);
    exit;
}

$stmt = $conn->prepare("UPDATE course_new SET degree_id = ? WHERE id = ?");
$stmt->bind_param("si", $degree_id, $course_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
$stmt->close(); 