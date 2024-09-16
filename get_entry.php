<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_POST['field']) && isset($_POST['effective_date'])){
    $fieldID = $_POST['field'];
    $effectiveDate = $_POST['effective_date'];
    // Assume $connection is your database connection
    $query = "SELECT DISTINCT yr_of_entry FROM testscore WHERE field = '$fieldID' AND effectivedate = '$effectiveDate'";
    $result = mysqli_query($conn, $query);

    $options = '<option value="">Select External</option>';
    while($row = mysqli_fetch_assoc($result)){
        $options .= '<option value="'.$row['yr_of_entry'].'">'.$row['yr_of_entry'].'</option>';
    }

    echo $options;
}
?>
