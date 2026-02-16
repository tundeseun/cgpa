<?php session_start();
$dept=$_SESSION["dept_new"];
// Assume $connection is your database connection
// Fetch distinct fields from the table
include('function/connect.php');
$query = "SELECT DISTINCT testscore.degree,degree_new.degree as title FROM testscore inner join degree_new on degree_new.id=testscore.degree where testscore.dept='$dept'";
$result = mysqli_query($conn, $query);

$options = '<option value="">Select Degree</option>';
while($row = mysqli_fetch_assoc($result)){
    $options .= '<option value="'.$row['degree'].'">'.$row['title'].'</option>';
}

echo $options;
?>
