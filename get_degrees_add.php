<?php
include('function/connect.php');

$dept_id = intval($_GET['dept_id']);
$sql = "SELECT DISTINCT d.id, d.degree
        FROM fieldofinterest5 f
        INNER JOIN degree_new d ON f.degree = d.id
        WHERE f.dept = $dept_id";
$result = $conn->query($sql);
$degrees = [];
while($row = $result->fetch_assoc()) {
    $degrees[] = $row;
}
echo json_encode($degrees);
?>
