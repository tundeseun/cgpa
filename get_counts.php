<?php session_start();
header('Content-Type: application/json');
include('function/connect.php');

$dept=$_SESSION["dept_new"];

// Get sec value
$query_sec = "SELECT sec FROM sec_examined ORDER BY id DESC LIMIT 1";
$result_sec = $conn->query($query_sec);
$row_sec = $result_sec->fetch_assoc();
$sec = $row_sec['sec'];

// Get programme count
$query_programme = "SELECT DISTINCT field_new.field_title, fieldofinterest5.field FROM fieldofinterest5 INNER JOIN field_new ON fieldofinterest5.field = field_new.id WHERE fieldofinterest5.dept = '$dept'";
$result_programme = $conn->query($query_programme);
$count_programme = $result_programme->num_rows;

// Get count
$query = "SELECT COUNT(*) AS count FROM new INNER JOIN zmain_app ON zmain_app.user_id = new.id INNER JOIN reginvoice ON reginvoice.appno = new.numeration WHERE zmain_app.department = '$dept' AND reginvoice.amount_paid = reginvoice.amount_charge AND reginvoice.amount_paid > 0 AND reginvoice.sessioned = '$sec' AND zmain_app.degree <> '3' AND zmain_app.degree <> '4' AND zmain_app.degree <> '5'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$count = $row['count'];

$response = array(
    'count_programme' => $count_programme,
    'count' => $count
);

echo json_encode($response);

?>

