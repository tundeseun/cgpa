<?php session_start();

include_once('function/script.php');
$q=$_GET["q"];

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
 
 echo "<div class='form-group mb'>";
 echo "<label for='board'>Pick Board Date:</label>";

 echo"<input type='date' name='board' id='board' >";
          
echo "</div>";
 


     echo"<input type='submit' value='Submit' name='submit' class='btn' id='submitBtn'>";



//$i=5;
		
	//	echo "</tr></tr></table>";

    
		?>

        