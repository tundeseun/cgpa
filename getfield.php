<?php session_start();
//include('connect.php');
// $username=$_SESSION['name']; 
// $dept=$_SESSION['dept_new']; 
require_once("_classes/db_connect.inc");

$q =7; //= $_POST["q"];
if(isset($q)) {
$query = "SELECT id, field FROM fieldofinterest5 WHERE degree = '$q' ORDER BY field";
$result = mysqli_query($link, $query) or die(mysqli_error($link));

$output = '<option value="">Select Option</option>'; // Initial option

while ($line = mysqli_fetch_array($result)) {
    $output .= '<option value="' . $line['id'] . '">' . $line['field'] . '</option>';
}

echo $output;
} else {
    // Handle the case when the 'q' parameter is not received
    echo "No data received for 'q'";
    // You can also use error_log() or print messages for debugging purposes
}




//$i=5;
		
	//	echo "</tr></tr></table>";
		?>