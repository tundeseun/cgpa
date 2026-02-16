<?php session_start();

include_once('function/script.php');
$q=$_GET["q"];

$get_external = "SELECT DISTINCT external_cgpa.initial,external_cgpa.lname,external_cgpa.fname, testscore.external AS idx FROM testscore INNER JOIN external_cgpa ON external_cgpa.id=testscore.external where testscore.dept='$q'";
$query_external = mysqli_query($conn,$get_external) or die(mysqli_error($conn));


$query = "SELECT * FROM dept_new where id='$q'";
	$querycon = mysqli_query($conn,$query) or die(mysqli_error($conn));
	while($row=mysqli_fetch_array($querycon))	{
	$dept=$row['department'];
	
	//echo $dept_title;
	}

    echo "<div class='form-group'>";
echo"<label for='degree' >Select Degree:</label>";
    echo"<select name=degree id=degree >";
    $query = "SELECT DISTINCT testscore.degree as id,degree_new.degree as degree FROM testscore inner join degree_new on degree_new.id=testscore.degree where testscore.dept='$q'";
    //echo "$query <br>";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
echo "<option value= selected disabled>";
while ($line = mysqli_fetch_assoc($result)) 
{
    $degree3=$line['degree'];
            $iddegree=$line['id'];

    echo "<option value=".$iddegree.">".$degree3."</b></option>"; 
}

echo"</select>";
echo "</div>";

echo "<div class='form-group'>";
echo"<label for='field' >Select Specialization:</label>";
     echo"<select name='field' id='field' >";
     
     
     $query = "SELECT DISTINCT testscore.field as id,field_new.field_title as field FROM testscore inner join field_new on field_new.id=testscore.field where testscore.dept='$q'";
      $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
echo "<option value= selected disabled>";
 while ($line = mysqli_fetch_assoc($result)) 
 {  
    $field=$line['field'];
            $iddegree=$line['id'];
    echo "<option value=".$iddegree.">".$field."</b></option>"; 
     
     
   
 }
 echo"</select>";
 echo "</div>";
 
 
 echo "<div class='form-group'>";
 echo"<label for='field' >Select External Examiner:</label>";
      echo"<select name='external'  >";
 while($row_external=mysqli_fetch_array($query_external))	{
    $initial=$row_external['initial'];
    $fname=$row_external['fname'];
    $lname=$row_external['lname'];
    $id_ex=$row_external['idx'];
    echo "<option value=".$id_ex.">".$initial." ".$fname." ".$lname."</b></option>";



 }
 echo"</select>";
 echo "</div>";

 echo "<div class='form-group mb'>";
 echo "<label for='date'>Select Effective Date:</label>";

 echo"<select name='effectivedate' id='effectivedate' >";
     
 $query = "SELECT DISTINCT effectivedate FROM testscore WHERE dept = '$q'";
    $result = mysqli_query($conn, $query);

    $options = '<option value="" selected disabled></option>';
    while($row = mysqli_fetch_assoc($result)){
        $options .= '<option value="'.$row['effectivedate'].'">'.$row['effectivedate'].'</option>';
    }

    echo $options;

echo"</select>";     
echo "</div>";

echo"</select>";
echo "</div>";

echo "<div class='form-group mb'>";
echo "<label for='resulttype'>Select Result Type:</label>";

echo"<select name='resulttype' id='resulttype' >";
    
echo "<option value='' selected>Select Result Type</option>
                    <option value='0'>Main Result</option>
                    <option value='1'>Supplementary Result</option>";


echo"</select>";     
echo "</div>";

echo"</select>";
echo "</div>";

echo "<div class='form-group mb'>";
echo "<label for='date'>Session of Graduation:</label>";

echo"<select name='sec' id='sec' >";
    
$query = "SELECT DISTINCT session_of_grad FROM testscore WHERE dept = '$q'";
   $result = mysqli_query($conn, $query);

   $options = '<option value="" selected disabled></option>';
   while($row = mysqli_fetch_assoc($result)){
       $options .= '<option value="'.$row['session_of_grad'].'">'.$row['session_of_grad'].'</option>';
   }

   echo $options;

echo"</select>";     
echo "</div>";



//$i=5;
		
	//	echo "</tr></tr></table>";
		?>

        