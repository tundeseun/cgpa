<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_POST['field']) && isset($_POST['effective_date'])){
    $fieldID = $_POST['field'];
    $effectiveDate = $_POST['effective_date'];
    // Assume $connection is your database connection
    $query = "SELECT DISTINCT testscore.external,external_cgpa.fname,external_cgpa.lname FROM testscore inner join external_cgpa on external_cgpa.id=testscore.external WHERE field = '$fieldID' AND testscore.dept = '$dept'";
    $result = mysqli_query($conn, $query);

    $options = '<option value="">Select External</option>';
    while($row = mysqli_fetch_assoc($result)){
        $options .= '<option value="'.$row['external'].'">'.$row['fname'].' '.$row['lname'].'</option>';
    }

    echo $options;
}
?>
