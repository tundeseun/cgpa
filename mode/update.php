<?php
include_once('../function/connect.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = trim($_POST['matric']);
    $new_mode = $_POST['mode']; // 1 = full-time, 2 = part-time

    // Get user_id from testscore
    $query = "SELECT user_id FROM testscore WHERE matric = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $matric);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($user_id) {
        // Update both tables
        $update_testscore = "UPDATE testscore SET mode = ? WHERE user_id = ?";
        $stmt1 = mysqli_prepare($conn, $update_testscore);
        mysqli_stmt_bind_param($stmt1, 'ii', $new_mode, $user_id);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_close($stmt1);

        $update_zmain = "UPDATE zmain_app SET mode_of_study = ? WHERE user_id = ?";
        $stmt2 = mysqli_prepare($conn, $update_zmain);
        mysqli_stmt_bind_param($stmt2, 'ii', $new_mode, $user_id);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        echo "Mode of study updated successfully for matric: $matric.";
    } else {
        echo "Matric number not found.";
    }

    mysqli_close($conn);
}
?>
