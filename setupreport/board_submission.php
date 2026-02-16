<?php
session_start();
include_once '../function/conn.php';

header('Content-Type: application/json');

if ($_POST['action'] === 'submit_for_board') {
    $fieldId = mysqli_real_escape_string($conn, $_POST['field_id']);
    $degreeId = mysqli_real_escape_string($conn, $_POST['degree_id']);
    $effectiveDate = mysqli_real_escape_string($conn, $_POST['effectivedate']);
    $sec = mysqli_real_escape_string($conn, $_POST['sec']);
    $resultType = mysqli_real_escape_string($conn, $_POST['resulttype']);
    $external = mysqli_real_escape_string($conn, $_POST['external']);
    $smode = mysqli_real_escape_string($conn, $_POST['smode']);
    
    // Update testscore table using the combination of field, degree, effectivedate, etc.
    $updateQuery = "UPDATE testscore 
                    SET status = 1, stage = 3 
                    WHERE field = '$fieldId' 
                    WHERE status = 0 
                    AND degree = '$degreeId' 
                    AND effectivedate = '$effectiveDate'
                    AND sec = '$sec'
                    AND resulttype = '$resultType'
                    AND mode = '$smode'
                    AND external = '$external'";
    
    if (mysqli_query($conn, $updateQuery)) {
        $affectedRows = mysqli_affected_rows($conn);
        if ($affectedRows > 0) {
            echo json_encode(['success' => true, 'message' => 'Successfully submitted for board approval']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No matching records found to send / it may have already been submitted'] );
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>