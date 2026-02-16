<?php session_start();
$dept=$_SESSION["dept_new"];
include('function/connect.php');
if(isset($_GET['matric']) && isset($_GET['field'])){
    $fieldID = $_GET['field'];
    $matric = $_GET['matric'];
    $result = mysqli_query($conn,"update studentrecord set specialization='$fieldID',specialization2='$fieldID', status='PASS' WHERE matric='$matric'") or die(mysqli_error($conn));

    $result2 = mysqli_query($conn,"SELECT user_id FROM studentrecord WHERE matric='$matric'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result2);
    $user = $row['user_id'];

    $result3 = mysqli_query($conn,"update zmain_app set field_of_interest='$fieldID' WHERE user_id='$user'") or die(mysqli_error($conn));
    //$result3 = mysqli_query($connOnline,"update zmain_app set field_of_interest='$fieldID' WHERE user_id='$user'") or die(mysqli_error($conn));
    
    // Check if record exists in testscore table and update it
    $check_testscore = mysqli_query($conn,"SELECT id FROM testscore WHERE matric='$matric'") or die(mysqli_error($conn));
    if(mysqli_num_rows($check_testscore) > 0) {
        $result4 = mysqli_query($conn,"update testscore set field='$fieldID' WHERE matric='$matric'") or die(mysqli_error($conn));
    }
    
    if ($result && $result2 && $result3) {
        $result_spec = mysqli_query($conn,"SELECT field_title FROM field_new WHERE id='$fieldID'") or die(mysqli_error($conn));
        $row_spec = mysqli_fetch_assoc($result_spec);
        $specialization = $row_spec['field_title'];
        echo $specialization;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
if(isset($_GET['matric']) && isset($_GET['degree'])){
    $degreeID = $_GET['degree'];
    $matric = $_GET['matric'];

    $result2 = mysqli_query($conn,"SELECT user_id FROM studentrecord WHERE matric='$matric'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result2);
    $user = $row['user_id'];

    $result3 = mysqli_query($conn,"update zmain_app set degree='$degreeID' WHERE user_id='$user'") or die(mysqli_error($conn));
    // $result3 = mysqli_query($connOnline,"update zmain_app set degree='$degreeID' WHERE user_id='$user'") or die(mysqli_error($conn));
    
    // Check if record exists in testscore table and update it
    $check_testscore = mysqli_query($conn,"SELECT id FROM testscore WHERE matric='$matric'") or die(mysqli_error($conn));
    if(mysqli_num_rows($check_testscore) > 0) {
        $result4 = mysqli_query($conn,"update testscore set degree='$degreeID' WHERE matric='$matric'") or die(mysqli_error($conn));
    }
    
    if ($result2 && $result3) {
        $result_spec = mysqli_query($conn,"SELECT degree FROM degree_new WHERE id='$degreeID'") or die(mysqli_error($conn));
        $row_spec = mysqli_fetch_assoc($result_spec);
        $degree = $row_spec['degree'];
        echo $degree;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>