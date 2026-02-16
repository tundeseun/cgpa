<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_POST['field'])){
    $fieldID = $_POST['field'];
    // Assume $connection is your database connection
    $query = "SELECT DISTINCT effectivedate FROM testscore WHERE field = '$fieldID' and dept = '$dept'";
    $result = mysqli_query($conn, $query);

    $options = '<option value="">Select Effective Date</option>';
    while($row = mysqli_fetch_assoc($result)){
        $options .= '<option value="'.$row['effectivedate'].'">'.$row['effectivedate'].'</option>';
    }

    echo $options;
}
?>
