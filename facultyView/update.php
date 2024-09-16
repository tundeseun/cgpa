<?php
session_start();
include_once('../function/script.php');

if (isset($_POST['department'], $_POST['degree'], $_POST['field'], $_POST['effectivedate'])) {
    $dept_id = mysqli_real_escape_string($conn, $_POST['department']);
    $degree_id = mysqli_real_escape_string($conn, $_POST['degree']);
    $field_id = mysqli_real_escape_string($conn, $_POST['field']);
    $effectivedate = mysqli_real_escape_string($conn, $_POST['effectivedate']);

    $sql = "UPDATE testscore SET stage = 2 WHERE effectivedate = ? AND dept = ? AND degree = ? AND field = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssss', $effectivedate, $dept_id, $degree_id, $field_id);

        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Update Successful',
                'message' => 'Date Set Successfully!',
                'redirect' => 'dashboard.php?p=deen&faculty_id=' . $_SESSION['faculty_id'] . '&user=' . $_SESSION['user']
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Unable to Set Date: ' . $stmt->error
            ];
        }

        $stmt->close();
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'Failed to prepare the SQL statement: ' . $conn->error
        ];
    }

    header('Location: index.php');
    exit();
}
?>
