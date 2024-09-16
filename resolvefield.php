<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_GET['matric']) && isset($_GET['field'])){
    $fieldID = $_GET['field'];
    $matric = $_GET['matric'];
    $result = mysqli_query($conn,"update studentrecord set specialization='$fieldID',specialization2='$fieldID' WHERE matric='$matric'") or die(mysqli_error($conn));

    $result2 = mysqli_query($conn,"SELECT user_id FROM prev_app INNER JOIN new ON prev_app.user_id=new.id WHERE prev_app.matric='$matric' AND new.activated <> 44") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result2);
    $user = $row['user_id'];

    $result3 = mysqli_query($conn,"update zmain_app set field_of_interest='$fieldID' WHERE user_id='$user'") or die(mysqli_error($conn));
    
    if ($result && $result2 && $result3) {
    $result_spec = mysqli_query($conn,"SELECT field_title FROM field_new WHERE id='$fieldID'") or die(mysqli_error($conn));
    $row_spec = mysqli_fetch_assoc($result_spec);
    $specialization = $row_spec['field_title'];
        echo $specialization;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
