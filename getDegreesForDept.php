<?php
session_start();
include_once('function/script.php');


header('Content-Type: application/json');

$dept = isset($_POST['dept_new']) ? $_POST['dept_new'] : null;
if (!$dept) {
    echo json_encode(['success' => false, 'error' => 'No department specified']);
    exit;
}

$degrees = [];
$result = mysqli_query($conn, "SELECT DISTINCT degree_new.degree, fieldofinterest5.degree as degreeid FROM fieldofinterest5 INNER JOIN degree_new ON degree_new.id=fieldofinterest5.degree WHERE fieldofinterest5.dept='$dept' ORDER BY degree");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $degrees[] = [
            'id' => $row['degreeid'],
            'name' => $row['degree']
        ];
    }
    echo json_encode(['success' => true, 'degrees' => $degrees]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
} 