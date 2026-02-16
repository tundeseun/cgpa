<?php
session_start();
include_once('../function/script.php');

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION["dept_new"]) || !isset($_SESSION["name"])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

$dept = $_SESSION["dept_new"];

if (!isset($_POST['type']) || !isset($_POST['effectivedate'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters'
    ]);
    exit;
}

try {
    $type = $_POST['type'];
    $effectivedate = $_POST['effectivedate'];

    switch($type) {
        case 'matric':
            if (!isset($_POST['matric'])) {
                throw new Exception('Matric number is required');
            }
            $matric = $_POST['matric'];
            
            // First check if any results are locked
            $check_query = "SELECT COUNT(*) as locked_count 
                          FROM testscore 
                          WHERE matric = ? 
                          AND effectivedate = ? 
                          AND dept = ?
                          AND status = 1";
            
            $stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt, "sss", $matric, $effectivedate, $dept);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            
            if ($row['locked_count'] > 0) {
                throw new Exception('Cannot delete: Some results are locked');
            }
            
            // If no results are locked, proceed with deletion
            $delete_query = "DELETE FROM testscore 
                           WHERE matric = ? 
                           AND effectivedate = ? 
                           AND dept = ?
                           AND status = 0";
            $stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($stmt, "sss", $matric, $effectivedate, $dept);
            break;

        case 'course':
            if (!isset($_POST['code'])) {
                throw new Exception('Course code is required');
            }
            $code = $_POST['code'];
            
            // First check if any results are locked
            $check_query = "SELECT COUNT(*) as locked_count 
                          FROM testscore 
                          WHERE cozid = ? 
                          AND effectivedate = ? 
                          AND dept = ?
                          AND status = 1";
            
            $stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt, "sss", $code, $effectivedate, $dept);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            
            if ($row['locked_count'] > 0) {
                throw new Exception('Cannot delete: Some results are locked');
            }
            
            // If no results are locked, proceed with deletion
            $delete_query = "DELETE FROM testscore 
                           WHERE cozid = ? 
                           AND effectivedate = ? 
                           AND dept = ?
                           AND status = 0";
            $stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($stmt, "sss", $code, $effectivedate, $dept);
            break;

        case 'date':
            // First check if any results are locked
            $check_query = "SELECT COUNT(*) as locked_count 
                          FROM testscore 
                          WHERE effectivedate = ? 
                          AND dept = ?
                          AND status = 1";
            
            $stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt, "ss", $effectivedate, $dept);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            
            if ($row['locked_count'] > 0) {
                throw new Exception('Cannot delete: Some results are locked');
            }
            
            // If no results are locked, proceed with deletion
            $delete_query = "DELETE FROM testscore 
                           WHERE effectivedate = ? 
                           AND dept = ?
                           AND status = 0";
            $stmt = mysqli_prepare($conn, $delete_query);
            mysqli_stmt_bind_param($stmt, "ss", $effectivedate, $dept);
            break;

        default:
            throw new Exception('Invalid deletion type');
    }

    $success = mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);

    if ($success) {
        if ($affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => "Successfully deleted $affected_rows result(s)"
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => "No unlocked results found to delete"
            ]);
        }
    } else {
        throw new Exception(mysqli_error($conn));
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} 