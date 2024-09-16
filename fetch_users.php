<?php
session_start();
include_once('function/script.php');


$limit = 20; 
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start_from = ($page - 1) * $limit;

$queryDisplay = "SELECT * FROM users_cgpa_new LIMIT $start_from, $limit";
$result = mysqli_query($conn, $queryDisplay);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $dept_id = $row['dept_new'];
    $department = getUsersDepartment($conn, $dept_id);
    $row['department_name'] = $department;
    $data[] = $row;
}

$queryTotal = "SELECT COUNT(id) FROM users_cgpa_new";
$resultTotal = mysqli_query($conn, $queryTotal);
$rowTotal = mysqli_fetch_row($resultTotal);
$total_records = $rowTotal[0];

$total_pages = ceil($total_records / $limit);

$response = array(
    'data' => $data,
    'total_pages' => $total_pages,
);

echo json_encode($response);
?>
