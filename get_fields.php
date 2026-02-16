<?php session_start();
$dept=$_SESSION["dept_new"];
// Assume $connection is your database connection
// Fetch distinct fields from the table
include('function/connect.php');
$query = "SELECT DISTINCT testscore.field,field_new.field_title FROM testscore inner join field_new on field_new.id=testscore.field where testscore.dept='$dept'";
$result = mysqli_query($conn, $query);

$options = '<option value="">Select Specialization</option>';
while($row = mysqli_fetch_assoc($result)){
    $options .= '<option value="'.$row['field'].'">'.$row['field_title'].'</option>';
}

echo $options;
?>
