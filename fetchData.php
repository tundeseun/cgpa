<?php
include_once('function/connect.php');

$sql = "SELECT f.faculty, d.department, g.degree 
        FROM fieldofinterest5 fi 
        JOIN fac_new f ON fi.fac = f.fac_id 
        JOIN dept_new d ON fi.dept = d.dept_id 
        JOIN degree_new g ON fi.degree = g.degree_id
        ORDER BY f.fac_name, d.dept_name, g.degree_name";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[$row['fac_name']][$row['dept_name']][] = $row['degree_name'];
    }
} else {
    echo "0 results";
}
$conn->close();
?>
