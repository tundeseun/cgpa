<?php if(!isset($_SESSION)) { 
  session_start(); 
} 

 
 
 ?>
<?php  
$db_host='localhost';
$db_user='root';
$db_password='';
$database='pgcgpa';
$table = "";
$link = mysqli_connect("$db_host","$db_user","$db_password","$database")
or die ('Error connecting to Database');
function confirm_query($result_set){
if(!$result_set){
		
		die(mysqli_error($link)."Sorry! Something went wrong.");
	}
}


function gettheheaders(){
	
	echo "  <th class='codea'>		S/No</th>";
echo "  <th class='codeab'>Matric Number</th>";

echo "  <th class='codeab'>Year Of Entry</th>";
echo "  <th class='codea'>Mode of Study</th>";

}


//key is the course id and value is the course code
function theCourse($stat){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special= $_GET['special'];
	$dept=$_SESSION['dept_new']; 
	$query = "SELECT * FROM course_new where specialization='$special' and status='$stat' and dept_newids='$dept' and status2='0' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$theCos = array();
	
	while($row = mysqli_fetch_assoc($querycon)){
				
		
		$id = $row['cgpa_id'];
		$theCos["$id"] = $row['course_code'];
		
		}
	
	return $theCos;
	
}

function theCourse2(){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special= $_GET['special'];
	$dept=$_SESSION['dept_new']; 
	$query = "SELECT * FROM course_new where specialization='$special' and dept_newids='$dept' and status2='0' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$theCos = array();
	
	while($row = mysqli_fetch_assoc($querycon)){
				
		
		$id = $row['cgpa_id'];
		$theCos["$id"] = $row['course_code'];
		
		}
	
	return $theCos;
	
}

function printTheCode($theCourse){
	$coscode = "";
	foreach($theCourse as $key => $value){
		
$coscode .= "<th class='codea'> $value</th> ";
		
	}
	echo $coscode;
				
	
}

function printTheLastSet(){
	
echo "  <th class='codea'>TUT</th>";
echo "  <th class='codea'>TUP</th>";
echo "  <th class='codea'>TGP</th>";
echo "  <th class='codea'>CGPA</th>";
echo "  <th class='codea'>RESULT</th>";
echo "  <th class='codea'>REMARK</th>";
}

function setRow2(){

	
echo"<tr>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";
	
}


function printCourseStatus($theCourse){
global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	
$cosstat = "";

	foreach($theCourse as $key => $value){
		
$query = "SELECT status FROM course_new where cgpa_id='$key' and status2='0'";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
		confirm_query($querycon);
	$status = mysqli_fetch_array($querycon);
	//$theSt = "";//$status['status'];
		
		
	$theSt =$status['status'];
			
			
		
		
	$cosstat .= "<th class=''>$theSt</th> ";	
		
	}


	
echo $cosstat;
	
}


function setRow3(){
echo"</tr>";

echo"<tr>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";
echo "  <th class='codea'></th>";

}

function printUnit($theCourse){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;

$cosunit = "";

	foreach($theCourse as $key => $value){
		
$query = "SELECT unit FROM course_new where cgpa_id='$key' and status2='0' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
		confirm_query($querycon);
	$unit = mysqli_fetch_array($querycon);
	$theut = $unit['unit'];
	$cosunit .= "<th class=''>$theut</th> ";	
		
	}


	
echo $cosunit;
	
}
	
	
function startRow(){
	
	echo"</tr>";
	echo"<tr>";

}






function findAllStudent(){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
      	$sec2=$_GET['sec2'];
$ext4=$_GET['ext'];
	$effect2=$_GET['effect'];
	//echo $sec2;
	$query = "SELECT DISTINCT matric FROM testscore where field = '$special' and external='$ext4' and effectivedate='$effect2' and exam_sec='$sec2' order by matric asc ";
	
	//agrengdares2a
//SELECT cid,course_title,unit,course_code FROM psyas INNER JOIN results ON cid = '$cos'
	
$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$alstud = array();
while($row = mysqli_fetch_assoc($querycon)){
	$alstud[] = $row['matric'];
		}
	
	return $alstud;
	
}


function findAllStudent2($external){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$effectv= $_GET['effect'];
		$sec2= $_GET['sec2'];
$ext= $_GET['ext'];
$degree= $_GET['degree'];
$special= $_GET['special'];
      
// 	$query33 = "SELECT * FROM fieldofinterest4 where id='$special'";
// 	$querycon33 = mysqli_query($link,$query33) or die(mysqli_error($link));
// 	while($row=mysqli_fetch_array($querycon33))	{
// 	$field2=$row['field'];
// 		$special2=$row['field'];
// 		$degree4=$row['degree'];
// 		$degree55= str_replace("%20"," ", $degree4);
// 				}	
// //echo $degree55;

	$query = "SELECT matric FROM testscore where external='$ext' and effectivedate='$effectv' and field='$special' and degree='$degree'  and external<>'' and yr_of_entry='$sec2'  ORDER BY matric ASC ";
	
	//agrengdares2a
//SELECT cid,course_title,unit,course_code FROM psyas INNER JOIN results ON cid = '$cos'
	
$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$alstud = array();
while($row = mysqli_fetch_assoc($querycon)){
	$alstud[] = $row['matric'];
	
	}
	
	return $alstud;
	
}




function printStudentBio($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special2= $_GET['special'];
	$ext= $_GET['ext'];
	$degree= $_GET['degree'];
	// $query33 = "SELECT * FROM fieldofinterest4 where id='$special'";
	// $querycon33 = mysqli_query($link,$query33) or die(mysqli_error($link));
	// while($row=mysqli_fetch_array($querycon33))	{
	// $field2=$row['field'];
	// 	$special2=$row['field'];
	// 	$degree4=$row['degree'];
	// 	$degree55= str_replace("%20"," ", $degree4);
	// 			}
				
				$dept=$_SESSION['dept_new']; 
 //echo $dept;
// $query = "SELECT * FROM dept_new where id='$dept'";
// 	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
// 	while($row=mysqli_fetch_array($querycon))	{
// 	$dept_title=$row['department'];
	
// 	//echo $dept_title;
// 	}
//echo $dept;		
				
	
	$query = "SELECT yr_of_entry,mode FROM testscore WHERE matric = '$matric' and degree='$degree' and field='$special2' and external='$ext' and dept='$dept' order by matric ASC";

$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	confirm_query($querycon);
	$theStudent = mysqli_fetch_array($querycon);

	$yr=$theStudent['yr_of_entry'];
	$mode=$theStudent['mode'];
	

echo "<td>$matric</td>";
echo "<td>$yr</td>";
echo "<td>$mode</td>";
	
}

function findStudentScore($theCourse, $theStudent){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special= $_GET['special'];
	
	
	foreach($theCourse as $key => $value){
	//we find the first course if it as a match
	//if it does we print it else we print dash.
	$query = "SELECT score FROM testscore WHERE matric = '$theStudent' and cozid = '$key' order by exam_sec";

$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	
if($querycon!="" || !isset($querycon) || !empty($querycon)){
			$theScores = "";
	while($row = mysqli_fetch_array($querycon)){
			
		if($row['score']==0){
				
				$theScores .= 0;
				$theScores .="/";
				
		}else if($row['score']>0){
			
		$theScores .= $row['score'];
		$theScores .="/";
				
		}
	}
	$theScor = trim($theScores,"/");
		
	if(empty($theScor)){
			
			echo "<td>";
			echo "-";
			echo "</td>";
		}else{
			echo "<td>";
			echo $theScor;
			echo "</td>";
			
		}
				
			
	
	}else{
			echo "<td>";
			echo "-";
			echo "</td>";
}
	
}
	
}
			
			
function processScore($theScore){
	
	$theStr = explode("/",theScore);
	if(count($theStr)> 1){
		foreach($theStr as $theVal){
			
			
		}
	}
	
}		

//it is in this processSummary function that all calculationis done
function processSummary($theStudent){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$unitpassed7=0;
	$txt3='';
	$special= $_GET['special'];
	
	$selptype="SELECT fieldofinterest5.id,fieldofinterest5.degree,programme.type FROM fieldofinterest5 inner join programme on programme.degree_id= fieldofinterest5.degree WHERE fieldofinterest5.field='$special'";
		$queryptype=mysqli_query($link,$selptype) or die(mysqli_error($link));
		while($row = mysqli_fetch_array($queryptype)){
				
				$ptype= $row['type'];
				}
	//echo $ptype;
	$theCourse = array();
	$theCourse = theCourse2();
	
	$tut2 = 0;
	$tgp=0;
	//this get the Student programme type
	$programmeType = getStudentProgramme($theStudent);
	
	//is it professional or academics
	//if it is academics it is true else if 
	//it is professional it is false.
	$academicsOrNot = isAcademics($programmeType);
	$totalPassed =0;
	$totalPassed = totalUnitPassed($theStudent);
	$totalToPass = totalUnitTOPass();
	$unitToPass = totalUnitTOPass($theStudent);
	$PassedstatusC=corePassedstatus($theStudent);
	$PassedstatusR=requiredPassedstatus($theStudent);
	$PassedstatusE=electivePassedstatus($theStudent);
	
	$numPassed2 = totalUnitPassed2($theStudent);
	$unitPassinresult=totalUnitPassedinresult($theStudent);
	//echo $numPassed2;
	
	$passedRequired = isPassed($academicsOrNot, $totalPassed);
	$numberOfPassed = 0;
	$numToPass= 0;
	
//echo $passedRequired;	
	
			$point=0;
			$i=0;
			
			$unitPassed = 0;
			$status ="";
	
	foreach($theCourse as $key => $value){
		
		$query = "SELECT * FROM testscore WHERE matric = '$theStudent' and cozid = '$key' and field='$special' order by exam_sec ";

$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
		if($querycon!="" || !isset($querycon) || !empty($querycon)){
			
			$unit =0;
			$theRealScore = 0;
			$theScore = 0;
			
			while($row = mysqli_fetch_array($querycon)){
				//count the number of that course id found
				
		//add the score together get the score unit and status
				$i++;
				$theScore += $row['score'];
				$unit += (int)$row['cunit'];
				$status = $row['cstatus'];
				$unit7 = $row['cunit'];
				$code=$row['cozid'];
			$theScore2 = $row['score'];
			if(($theScore2 <40)){
				//	echo $status;		
			//	$txt3=0;
			}
				echo $txt3;
				if($theScore2 >= 40){
							
				$unitpassed7 +=$unit7;
				$txt="pass";
			}
			//$theScore2;
				//echo $txt;
				
				$point = calculatePoint($theScore2); //$theRealScore
		$gp = $point * $unit7;	//$unit
	
	$tut2 += $unit7;
			/*if($theScore2  >=0){
	$tut2 += $unit7;
	}*/
	$tgp =$tgp + $gp;
			
				
			}
			
			//so here we divide 
			//need to document all this later
			if($i > 1){
				
				$theRealScore = $theScore/$i;
			}else{
				$theRealScore =$theScore;
			}
			
				
			if($status=="R" || $status=="C"){
				$numToPass++;
			
			}
			//echo $numToPass."<br>";
			$i=0;
			
	//if($theRealScore>=30 && $status == "R"){
	//	$numberOfPassed++;
	//}elseif($theRealScore >=40 && $status == "C"){
	//	$numberOfPassed++;
	//}
			$status="";
			
	//if($theRealScore >=40){
		//$unitPassed +=$unit;
	//	$theScore
	//}
		
		/*if( >=40){
		$unitPassed +=$unit;
	
		}	*/
			
			/*if($theScore2 >= 40){
							
				$unitpassed7 +=$unit7;
			}*/
		
	
			
				
		}
		
	}//end of loop time for the final business logic
	$gpa2=0.0;
	$gpa3=0.0;
	$gpa = 0.0;
	
	
	if($tut2==0){
		$gpa2=0.0;
	}else{
		$gpa2=$tgp/$tut2;
	}

	$gpa3=round($gpa2,1);
	$gpa=number_format($gpa3,1);
	//here we perform the business logic
	//if the number passed is greater or equals number expected to be passed
	$remark=""; 
	$remark2="";
	//echo $tut2."<br>";
	//echo $numPassed2."<br>";
	//echo $totalToPass;
	//this next if statement may not be necessary
	//passed required checks for overall total unit to be passed for a programme while num of passed and num to pass checks to see if necessary compulsory and required are passsed.
	//if($passedRequired==true){
	//echo $passedRequired."<br>";
	//echo $txt3;
			/*if(($theScore2<40)){
				$remark2="NG";
			}*/
			//display test output
		//echo	$theStudent.":".$numPassed2 .":".$totalToPass."<br>";
//echo $theStudent.":".$unitPassinresult. ":".$unitToPass.":".$PassedstatusC.":".$PassedstatusR.":".$PassedstatusE."<br>";  //UnitPassed in Result".$unitPassinresult.": Unit To Passed ".$unitToPass."<br>";//.":".$PassedstatusC.":".$PassedstatusR.":".$PassedstatusE."<br>";
//218024:22:26:0:0:0
			//and ($numPassed2 <$totalToPass)
//echo $numPassed2."<br>";
			//echo $ptype."<br>";
			//$numPassed2 >= $totalToPass
				if(( $unitPassinresult >= $unitToPass) and ($ptype=="Academics") and ($tut2>=30) and ($unitpassed7>=30) and ($PassedstatusR==0) and ($PassedstatusC==0)  ){
				$remark2="PASS";
			//echo $gpa2;   and ($PassedstatusE==0) and ($unitToPass==$unitPassinresult)
			if($gpa<9.0 && $gpa>=5.0){
				$remark="Ph.D"; $remark2="PASS";
				
			}
			elseif($gpa<5.0 && $gpa>=4.0){$remark="M.Phil/Ph.D"; $remark2="PASS";
									 }
			elseif($gpa<4.0 && $gpa>=3.0){$remark="M.Phil"; $remark2="PASS";
										 }
			elseif($gpa<3.0 && $gpa>=1.0){$remark="TM"; $remark2="PASS";
									 }
			elseif($gpa<1.0){
				$remark="NG";
				$remark2="-";
				$gpa="-";
							}
							
							//$numPassed2 < $totalToPass
			}elseif(($unitPassinresult < $unitToPass) and ($ptype=="Academics") and ($tut2<30) and ($unitpassed7>=30)  ){
				$remark="NG";
				$remark2="-";
				$gpa="-";

			}
			
			
			
			elseif(($PassedstatusR>0) || ($PassedstatusC>0) ) {
				$remark="NG";
				$remark2="-";
				$gpa="-";
			
			}


			elseif( $unitPassinresult < $unitToPass )
			{
				$remark="NG";
				$remark2="-";
				$gpa="-";
			}
			/*elseif($unitToPass < $unitPassinresult)
			{
				$remark="ERROR";
				$remark2="-";
				$gpa="-";
			}*/ elseif( ($ptype=="Academics")  and ($unitpassed7<30) ){
				$remark="NG";
				$remark2="-";
				$gpa="-";
				//$numPassed2
			} elseif(($unitPassinresult >=$totalToPass) and ($ptype=="Academics") and ($tut2<30) and ($unitpassed7>=30) ){
				$remark="NG";
				$remark2="-";
				$gpa="-";
			} 
			//$numPassed2
		elseif(($unitPassinresult <$totalToPass) and ($ptype=="Academics") and ($tut2>=30) and ($unitpassed7>=30) ){
				$remark="NG";
				$remark2="-";
				$gpa="-";
			}
	//}
		/*elseif(($passedRequired<>false) and ($ptype=="Academics"))
			{
				$remark2="-";
			$remark="NG";
				$gpa="-";
			}*/
	//echo $tut2;
	//echo $passedRequired;
	if(($ptype=="Professional") and ($unitpassed7>=36)){  //($tut2>=45)
		//$numPassed2
				if($unitPassinresult >= $totalToPass){    //($numberOfPassed >=$numToPass)
				$remark2="PASS";
				$remark="PASS";}
		        
				}
				//($numberOfPassed <$numToPass)
				
				if(($PassedstatusR>0) || ($PassedstatusC>0) ) {
				$remark="NG";
				$remark2="-";
				$gpa="-";
			
			}
				if(($ptype=="Professional") and ($unitpassed7<36)){ //
				
				$remark2="NG";
			$remark="NG";
			$tgp="-";
			$gpa="-";
				}
		//	}
	//$numPassed2
	if(($ptype=="Professional") and ($unitPassinresult <$totalToPass)){ 
	$remark2="NG";
			$remark="NG";
			$tgp="-";
			$gpa="-";
				}
		if(($ptype=="PGD") and ($tut2>=24)){
			//$numPassed2
				if($unitPassinresult >=$totalToPass){
				$remark2="PASS";
				$remark="PASS"; 
				}
				
			}
	elseif(($passedRequired==false) and ($ptype=="PGD"))
			{
				$remark2="-";
			$remark="NG";
				$gpa="-";
			}
			
			elseif(($PassedstatusR>0) || ($PassedstatusC>0) ) {
				$remark="NG";
				$remark2="-";
				$gpa="-";
			
			}
				//($numPassed2 <$totalToPass)
				if($tut2<24){
				//echo $passedRequired."<br>";
				//	echo $totalToPass."<br>";
				$remark2="-";
			$remark="NG";
				}
			//}
			

	
		//echo "<td>$totalToPass</td>";	
		//echo "<td>$numPassed2</td>";
	echo "<td>$tut2</td>";
	echo "<td>$unitpassed7</td>"; //$totalPassed
	echo "<td>$tgp</td>";
echo "<td>$gpa</td>";
echo "<td>$remark2</td>";
	echo "<td><a href=detail_result.php?id=$theStudent style=text-decoration:none>$remark</a></td>";
	
		$sql="update biodata set result='$remark' ,remark='$remark2' where matric='$theStudent'";
$result = mysqli_query($link,$sql) or die(mysqli_error($link)); 
}



//this calculate the point
function calculatePoint($score){
$pt1=0;
	if($score <=39){
		$pt1 = 0;
	}
	elseif($score >= 40 && $score < 45 ){
		$pt1 = 1;
	}
	elseif($score >= 45 && $score < 50 ){
		$pt1 = 2;
	}
	elseif($score >= 50 && $score < 55 ){
		$pt1 = 3;
	}
	elseif($score >= 55 && $score < 60 ){
		$pt1 = 4;
	}
	elseif($score >= 60 && $score < 65 ){
		$pt1 = 5;
	}
	elseif($score >= 65 && $score < 70 ){
		$pt1 = 6;
	}
	elseif($score >= 70 && $score < 101 ){
		$pt1 = 7;
	}
	
	return $pt1;

}

//the function below checks condition two to see if the necessary pass mark is obtained for the programme:professional or academics and it utilize the function for condition one to do this.
//this calculate whether the pass mark for each programme is obtained or not
//once again true means academics while false means professional
function isPassed($progType, $totalPassed){
	$passed = false;
	if($progType==true && $totalPassed>=30){
		$passed = true;
	}elseif($progType==false && $totalPassed >=36){
		$passed=true;
	}else{
		$passed=false;
	}
	
	return $passed;
}

//this will get the student  proramme is it Msc or Phd or...
function getStudentProgramme($matric){global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	
	$query = "SELECT degree FROM testscore WHERE matric = '$matric' ";

$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	confirm_query($querycon);
	$theStudent = mysqli_fetch_array($querycon);

	$programme = $theStudent['degree'];
	
	return $programme;
	
}

//here we check if the programme is academics
//if it is not academics then it is professional.
//so if it is academics we return true
//if it is professional we return false
function isAcademics($programme){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$query = "SELECT degree,type FROM programme WHERE degree_id = '$programme' ";

$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	confirm_query($querycon);
	$theProgramme = mysqli_fetch_array($querycon);
	$isAcadems=false;
	if($theProgramme['type']=="Academics"){
		$isAcadems=true;
	}else{
		$isAcadems=false;
	}
	
	return $isAcadems;
	
	
}
//the function below checks condition one for total unit passed
//this function will now get us the total unit passed by a person within a specialization here 
function totalUnitPassed($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$query = "SELECT * FROM testscore where field='$special' and matric='$matric' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$unitPassed2 = 0;
	
	while($row = mysqli_fetch_assoc($querycon)){
	//if progtype is true then it is academics
		//if progtype is false then it is professional
		
		
	if($row['score'] >=40 && $row['cstatus']=="C"){
		
		$unitPassed2 +=$row['cunit'];
		//required are not necessarily passed
	}elseif($row['score'] >=30 && $row['cstatus']=="R"){
			$unitPassed2 +=$row['cunit'];
	//but elective must be passed in order to be relevant
	}elseif( $row['score'] >=40 && $row['cstatus']=="EE"){
		$unitPassed2 +=$row['cunit'];
	}elseif( $row['score'] >=40 && $row['cstatus']=="E"){
		$unitPassed2 +=$row['cunit'];
	}
	
	}
	
	
	return $unitPassed2;
	
}

function totalUnitPassed2($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$query = "SELECT * FROM testscore where field='$special' and matric='$matric'  ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	$txt="";
	$unitPassed2 = 0;
	
	while($row = mysqli_fetch_assoc($querycon)){
	//if progtype is true then it is academics
		//if progtype is false then it is professional
		
		
		if($row['score'] <40 && $row['cstatus']=="C"){
		$txt="notpass";
		$unitPassed2 +=$row['cunit'];
		//required are not necessarily passed
	}elseif($row['score'] <30 && $row['cstatus']=="R"){
		$txt="notpass";
			$unitPassed2 +=$row['cunit'];
			$txt="pass";
	//but elective must be passed in order to be relevant
	}
		
		
		
	if($row['score'] >=40 && $row['cstatus']=="C"){
		$txt="pass";
		$unitPassed2 +=$row['cunit'];
		//required are not necessarily passed
	}elseif($row['score'] >=30 && $row['cstatus']=="R"){
			$unitPassed2 +=$row['cunit'];
			$txt="pass";
	//but elective must be passed in order to be relevant
	}
	
	}
	
	
	return $unitPassed2;
	
}



function totalUnitTOPass(){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$query = "SELECT * FROM course_new where specialization='$special' and status2='0' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	
	$unitToPass=0;
	
while($row = mysqli_fetch_assoc($querycon)){
	
		if($row['status']=="C" || $row['status']=="R"){	
		$unitToPass +=$row['unit'];
				//echo $unitToPass."<br>";
		//required are not necessarily passed
		}

}


return $unitToPass;

}


function totalUnitPassedinresult($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	//foreach($allStudent as $id){
	$query = "SELECT course_new.cgpa_id,course_new.specialization,testscore.score,testscore.cunit,testscore.cstatus,testscore.cozid,testscore.score,testscore.matric FROM testscore inner join course_new on course_new.cgpa_id=testscore.cozid where testscore.matric='$matric' and course_new.specialization='$special' and course_new.status2='0' ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	// and results.score>=40
	$unitPassinresult=0;
	
while($row = mysqli_fetch_assoc($querycon)){
	
		if(($row['cstatus']=="C" && $row['score']>=40)  || (($row['cstatus']=="R") && $row['score']>=30) ) {	
		$unitPassinresult +=$row['cunit'];
				//echo $unitToPass."<br>";
		//required are not necessarily passed
		//echo $unitPassinresult."<br>";
		}

}
//}

return $unitPassinresult;

}

function corePassedstatus($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$PassedstatusC = '';
	//$countfailC=0;
	//$countfailR=0;
	$countfailC=0;
	$countfailC2=0;
	$countfailC3=0;
	//foreach($allStudent as $id){
    /*

if($countfailE==0){
	$PassedstatusE=$countfailE;
	}elseif($countfailE>0){
		$PassedstatusE=1;
		while($row=mysqli_fetch_array($queryconE)){
			$cid2=$row['c_id'];
			//echo $cid."<br>";
			$queryE2 = "SELECT * FROM results where stud_id='$matric' and specialization='$special' and score>='40' and status='E' and c_id='$cid2'";
	         $queryconE2 = mysqli_query($link,$queryE2) or die(mysqli_error($link));
	         $countfailE2= mysqli_num_rows($queryconE2);
	         if($countfailE2>0){
	         	 $countfailE3=0;
	         	 $PassedstatusE=$countfailE3;
	         } elseif($countfailE2==0){
	         	$PassedstatusE=1;
	         }



    */

	$queryC = "SELECT * FROM testscore where matric='$matric' and field='$special' and score<'40' and cstatus='C'  ";
	$queryconC = mysqli_query($link,$queryC) or die(mysqli_error($link));
	$countfailC= mysqli_num_rows($queryconC);
	if($countfailC==0){
	$PassedstatusC=$countfailC;
}elseif($countfailC>0){
	$PassedstatusC=1;
		while($row=mysqli_fetch_array($queryconC)){
			$cid=$row['cozid'];
			//echo $cid."<br>";
			$queryC2 = "SELECT * FROM testscore where matric='$matric' and field='$special' and score>='40' and cstatus='C' and cozid='$cid'  ";
	         $queryconC2 = mysqli_query($link,$queryC2) or die(mysqli_error($link));
	         $countfailC2= mysqli_num_rows($queryconC2);
	         if($countfailC2>0){
	         	 $countfailC3=0;
	         
$PassedstatusC=$countfailC3;
				}elseif($countfailC2==0){
	         	$PassedstatusC=1;
	         }
		}
	}
	
	

return $PassedstatusC;

}



function requiredPassedstatus($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$PassedstatusR = '';
	
	$countfailR=0;
	
	$countfailR2=0;
	$countfailR3=0;
	//foreach($allStudent as $id){
   	$queryR = "SELECT * FROM testscore where matric='$matric' and field='$special' and score<'30' and cstatus='R'  ";
	$queryconR = mysqli_query($link,$queryR) or die(mysqli_error($link));
	$countfailR= mysqli_num_rows($queryconR);
	if($countfailR==0){
	$PassedstatusR=$countfailR;
	}elseif($countfailR>0){
		while($row=mysqli_fetch_array($queryconR)){
			$cid=$row['cozid'];
			//echo $cid."<br>";
			$queryR2 = "SELECT * FROM testscore where matric='$matric' and field='$special' and score>='30' and cstatus='R' and cozid='$cid'  ";
	         $queryconR2 = mysqli_query($link,$queryR2) or die(mysqli_error($link));
	         $countfailR2= mysqli_num_rows($queryconR2);
	         if($countfailR2>0){
	         	 $countfailR3=0;
	         
$PassedstatusR=$countfailR3;
		}elseif($countfailR2==0){
	         	$PassedstatusR=1;
	         }
	}
	}

return $PassedstatusR;

}



function electivePassedstatus($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$PassedstatusE = '';
	//$countfailC=0;
	//$countfailR=0;
	$countfailE=0;
	$countfailE=0;
	$countfailE2=0;
	$countfailE3=0;
	//foreach($allStudent as $id){
    

	$queryE = "SELECT * FROM testscore where matric='$matric' and field='$special' and score<'40' and cstatus='E'  ";
	$queryconE = mysqli_query($link,$queryE) or die(mysqli_error($link));
	$countfailE= mysqli_num_rows($queryconE);
	if($countfailE==0){
	$PassedstatusE=$countfailE;
	}elseif($countfailE>0){
		$PassedstatusE=1;
		while($row=mysqli_fetch_array($queryconE)){
			$cid2=$row['cozid'];
			//echo $cid."<br>";
			$queryE2 = "SELECT * FROM testscore where matric='$matric' and field='$special' and score>='40' and cstatus='E' and cozid='$cid2'";
	         $queryconE2 = mysqli_query($link,$queryE2) or die(mysqli_error($link));
	         $countfailE2= mysqli_num_rows($queryconE2);
	         if($countfailE2>0){
	         	 $countfailE3=0;
	         	 $PassedstatusE=$countfailE3;
	         } elseif($countfailE2==0){
	         	$PassedstatusE=1;
	         }

		}
	}
	
	

return $PassedstatusE;

}



/*function requiredPassedstatus($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$PassedstatusR = '';
	//$countfailC=0;
	//$countfailR=0;
	$countfailR=0;
	//foreach($allStudent as $id){
    

	$queryR = "SELECT * FROM results where stud_id='$matric' and specialization='$special' and score<'30' and status='R'  ";
	$queryconR = mysqli_query($link,$queryR) or die(mysqli_error($link));
	$countfailR= mysqli_num_rows($queryconR);
	$PassedstatusR=$countfailR;
	

return $PassedstatusR;

}*/


/*
function electivePassedstatus($matric){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special = $_GET['special'];
	$PassedstatusE = '';
	//$countfailC=0;
	//$countfailR=0;
	$countfailE=0;
	//foreach($allStudent as $id){
    

	$query = "SELECT * FROM results where stud_id='$matric' and specialization='$special' and score<'40' and status='E'  ";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	$countfailE= mysqli_num_rows($querycon);
	$PassedstatusE=$countfailE;
	

return $PassedstatusE;

}

*/
function findExternalExaminer($allStudent){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$special= $_GET['special'];

// $query3 = "SELECT * FROM fieldofinterest4 where id='$special'";
// 	$querycon3 = mysqli_query($link,$query3) or die(mysqli_error($link));
// 	while($row=mysqli_fetch_array($querycon3))	{
// 	$field=$row['field'];
// 		$fac=$row['fac'];
// 		$degree=$row['degree'];
// 		}


	$external=array();
	foreach($allStudent as $id){

	$query = "SELECT external,effectivedate,sum(id) FROM testscore WHERE matric = '$id' and field='$special' group by effectivedate ";

	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	confirm_query($querycon);
	$theStudent = mysqli_fetch_array($querycon);

	$theId = $theStudent['external'];
//print_r("the id ".$theId);
	if(!in_array($theId, $external)){

		$external[]=$theId;
	}



	}

	
return $external;

}


function findEffectiveDate($allStudent){
	global $db_host;
	global $db_user;
	global $db_password;
	global $database;
	global $link;
	$effectivedate='';
	//$theId2='';
	$effectiveDate=array();
	$effectivedate=[];
	foreach($allStudent as $id){

	$query2 = "SELECT effectivedate FROM testscore WHERE matric = '$id' ";

	$querycon2 = mysqli_query($link,$query2) or die(mysqli_error($link));
	confirm_query($querycon2);
	$theStudent2 = mysqli_fetch_array($querycon2);

	$theId2 = $theStudent2['effectivedate'];
//print_r("the id ".$theId);
	//if(!in_array($theId2, $effectivedate)){

		$effectivedate[]=$theId2;
	//}



	}

	
return $effectivedate;

}
?>