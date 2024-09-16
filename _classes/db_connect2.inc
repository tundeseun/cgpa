<?php
/* Connecting, selecting database */

//$link=mysqli_connect ("localhost", "picos2_root",
//"AqEEda") or die('Cannot connect to the database because: ' . mysqli_error());
//mysqli_select_db ("picos2_quiz"); 


$link=mysqli_connect ("localhost", "root",
"BAbalola2018") or die('Cannot connect to the database because: ' . mysqli_error());
mysqli_select_db($link,"app_admin"); 


function fetch_records($query){
	$result = mysqli_query($query) or die(mysqli_error());
	return $result;
}

function combo($cat = array(),$p,$n){

	echo "<select name=$n>\n";
	foreach ($cat as $varname => $varvalue)
	{
		echo "<option value=$varvalue";
		if ($varvalue == trim($p)){ #adds selected attribute if today
		echo ' selected="selected" ';
		}
		echo "> $varvalue\n";
	}
	echo "</select>";
}
function combo2($cat = array(),$p,$n){

	echo "<select name=$n>\n";
	foreach ($cat as $varname => $varvalue)
	{
		echo "<option value=$varvalue";
		if ($varvalue == trim($p)){ #adds selected attribute if today
		echo ' selected="selected" ';
		}
		echo "> $varvalue\n";
	}
	echo "</select>";
}

?>
