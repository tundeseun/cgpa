<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_POST['field']) && isset($_POST['effective_date'])){
    $fieldID = $_POST['field'];
    $effectiveDate = $_POST['effective_date'];
    $external = $_POST['external'];
    $degreeID = $_POST['degree'];
    // Assume $connection is your database connection
    $query = "SELECT DISTINCT session_of_grad FROM testscore WHERE field = '$fieldID' and dept = '$dept' and degree = '$degreeID'";
    $result = mysqli_query($conn, $query);

    $options = '<option value="">Select Session of Graduation</option>';
    while($row = mysqli_fetch_assoc($result)){
        $options .= '<option value="'.$row['session_of_grad'].'">'.$row['session_of_grad'].'</option>';
    }

    echo $options;
}
?>
