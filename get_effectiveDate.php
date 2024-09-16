<?php session_start();
$dept=$_SESSION["dept_new"];
// Assume $connection is your database connection
// Fetch distinct fields from the table
include('function/connect.php');
$query = "SELECT DISTINCT effectivedate FROM testscore where testscore.dept='$dept'";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
    $options .= '<option value="'.$row['effectivedate'].'">'.$row['effectivedate'].'</option>';
}

echo $options;
?>
