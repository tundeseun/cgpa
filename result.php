<?php session_start();
set_time_limit(500);
//ini_set('max_execution_time', 30000000000000);
 require_once("_classes/db_connect.inc");
//$username=$_SESSION['name']; 
$dept="";
if(isset($_SESSION['dept_new'])){
	$dept=$_SESSION['dept_new'];  //echo $dept;  
} //echo $dept;   ?><html>
<head>
<style>
        @page {size: 29.7cm 42cm; margin: 5mm}
        div.page {page-break-after:always}
    </style>
    
<style>
	
  .custom-table {
      text-align: center;
      font-family:Arial Black,;
	  font-size:13px;
      border-collapse: collapse;
      border-spacing: 0;
	  
    }
	
		.codea{
	   -webkit-transform: rotate(-90deg);
	         -ms-transform: rotate(-90deg);
      -o-transform: rotate(-90deg);
      transform: rotate(-90deg);

	}
	 th {
  
    
      width:55px;
      font-weight:normal;
		
    }
	tr:nth-child(even){background-color: #f2f2f2;}
	tr:hover {background-color: #ddd;}
	
	</style>
	<style>

div.image

  {

  
  background:url(images/logged2.png);
	  
  background-repeat:no-repeat;

  background-position:center;
	  

  
  }

div.transparentbox

  {



  background-color:#ffffff;

  

  opacity:0.8;


  }

div.transparentbox p

  {

  

  font-weight:bold;

  color:#CD853F;

  }
.style2 {
	font-size: 13px;
	font-weight: bold;
}
    body,td,th {
	color: #000000;
	font-family: "Arial Black";
	font-weight: normal;
	font-size: 12px;
		
}
		
		@media print{@page {size: landscape}}
		@media print {
* {
    -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
    color-adjust: exact !important;  /*Firefox*/
  }
}
    </style>
	
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head><b><span class="style2"></span>

	<?php $centre="";
	if(isset($_SESSION['dept_new'])){
		$dept=$_SESSION['dept_new'];
	}
	
	
	//include('connect.php');
	$spec="";
	if(isset($_GET['special'])){
		$spec=$_GET['special'];
	}
	$degreetitle="";
	if(isset($_GET['degree'])){
		$degreetitle=$_GET['degree'];
	}
	//echo $dept."<br>";
	
	$query = "SELECT * FROM dept_new where id='$dept'";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	while($row=mysqli_fetch_array($querycon))	{
	$dept_title=$row['department'];
	$inst=substr($dept_title,0,9);
	$centre= substr($dept_title,0,6);
	$dp="Africa Regional Centre for Information Science";
	//echo $dept_title;
	}
//echo $inst;
	$query3 = "SELECT field_new.field_title,fac_new.faculty FROM fieldofinterest5 inner join field_new on field_new.id=fieldofinterest5.field inner join fac_new on fac_new.id=fieldofinterest5.fac where field_new.id='$spec'";
	$querycon3 = mysqli_query($link,$query3) or die(mysqli_error($link));
	//$fac="";
	//$dept_title="";
	//$field="";
	while($row=mysqli_fetch_array($querycon3))	{
	$field=$row['field_title'];
		$fac=$row['faculty'];
		}
	  
	//include "connect.php";
	include "function/functions.php";
	//include "functions2.php";
	
	if(isset($_GET['special'])){
		$special=$_GET['special'];
	}
	//echo $_GET['ext'];
	$alStud1 = array();
$alStud1 = findAllStudent();

$effect = array();
$effect = findEffectiveDate($alStud1);

	$extern = array();
$extern = findExternalExaminer($alStud1);
	$perPage = 10;
   $sn = 1;
    $newRecords = array_chunk($alStud1, $perPage);

    //--- start page printing --- //
    foreach($newRecords as $names){
		$eachExterns='';
	//foreach($extern as $eachExterns){
//if($eachExterns<>""){
       // foreach($effect as $eachEffect){
	//foreach($extern as $eachExterns){
	//echo $extern."<br>";
       // echo $effect."<br>";
		echo"<div class=page style=margin-bottom: 20px>";
	echo"<table width=100% class=custom-table   align=center><tr >";
	echo" <td width=224 rowspan=7><div align=right><img src=images/logo_trans.jpg width=75 height=77 /></div></td><td><span class=style2>UNIVERSITY OF IBADAN </span></td> <td width=207 rowspan=7><div align=left><img src=images/pgcrest2.jpg width=127 height=93 /></div></td>";
	
                if(($dept_title=="Law")){
	echo"<tr><td><span class=style2>FACULTY OF  ".strtoupper($dept_title)."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
	else{

         if(($dept_title=="Dental Surgery")){
	echo"<tr><td><span class=style2>FACULTY OF  ".strtoupper("Dentistry")."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
	else{
                
		if(($dept_title=="Africa Regional Centre for Information Science")){
	echo"<tr><td><span class=style2>".strtoupper($dept_title)."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
        
        else{
                
		if(($inst=="Institute")){
	echo"<tr><td><span class=style2>".strtoupper($dept_title)."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
			  else{
                
		if(($dept_title=="School of Business")){
	echo"<tr><td><span class=style2>".strtoupper($dept_title)."</span></td></tr>";
	
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
			  else{
                
		if(($fac=="School of Business")){
	echo"<tr><td><span class=style2>".strtoupper($fac)."</span></td></tr>";
	echo"<tr><td><span class=style2>DEPARTMENT OF ".strtoupper($dept_title)."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
       
	else{
	
	if(($centre<>"Centre")){
	echo"<tr><td><span class=style2>FACULTY OF  ".strtoupper($fac)."</span></td></tr>";
	echo"<tr><td><span class=style2>DEPARTMENT OF ".strtoupper($dept_title)."</span></td></tr>";
	}
	else{
		if(($centre=="Centre")){
	echo"<tr><td><span class=style2>".strtoupper($dept_title)."</span></td></tr>";
	//echo"<tr><td><h3>DEPARTMENT OF </h3></td></tr>";
	}
	}
	}
}
}
}
	}
	}
	echo"<tr><td><span class=style2>$degreetitle</span></td></tr>";
	echo"<tr><td><span class=style2> AREA OF SPECIALISATION: ".strtoupper($field)."</span></td></tr>";
	echo" </tr></table>";
	
	
	
	?>
	</b>
	<div class="image">

<div class="transparentbox">
<table class="custom-table" BORDER='2'>
<tr height='100px'>

<?php
//echo substr('PGS17130617101155',0,3);
//include "connect.php";
	//include "functions.php";
	//include "functions2.php";
$special=$_GET['special'];
		$ext4=$_GET['ext'];

	$compulsoryCourse = array();
	$requiredCourse = array();
	$electiveCourse = array();
	
gettheheaders();
	
$compulsoryCourse = theCourse("C");
printTheCode($compulsoryCourse); 
	
$requiredCourse = theCourse("R");
printTheCode($requiredCourse);
	
$electiveCourse = theCourse("E");
printTheCode($electiveCourse);	
	
$electiveCourse2 = theCourse("EE");
printTheCode($electiveCourse2);	
	
printTheLastSet();

setRow2();
	
printCourseStatus($compulsoryCourse);
printCourseStatus($requiredCourse);
printCourseStatus($electiveCourse);
printCourseStatus($electiveCourse2);

setRow3();
	
printUnit($compulsoryCourse);
printUnit($requiredCourse);
printUnit($electiveCourse);
printUnit($electiveCourse2);
	
startRow();
	





//$alStud = array();
//$alStud = findAllStudent();
	
$alStud = array();
$alStud = findAllStudent2($eachExterns);	
//echo $eachExterns;	

$i = 1;
foreach ($names as $stud){
	//echo $coside."-";
	//echo $stud."<br>";
	echo "<tr>";	
	echo "<td>";
	echo $sn;
	echo "</td>";
	$sn += 1;
printStudentBio($stud);

findStudentScore($compulsoryCourse, $stud);

findStudentScore($requiredCourse, $stud);
	
findStudentScore($electiveCourse, $stud);
	
findStudentScore($electiveCourse2, $stud);
	
processSummary($stud);
	
	

	
echo "</tr>";
	}


//getscore($student);
//echo array_sum($collectar);

//echo $coscode;
?>
</table>
	  
		<table class="custom-table" width="100%" align="center" bgcolor="#FFFFFF">
                              <tr>
                                <td width="100%" colspan="5"><div align="center"><span class="style83">TUT=Total unit taken,TUP=Total unit passed,TGP=Total grade point,CGPA=Total grade point average, ETPT=Eligible to proceed to</span></div></td>
                              </tr>
                              <tr>
                                <td colspan="5"><div align="center" class="style83"> TM= Terminal Master, NG= Not Graduating, C= Core Course, R= Required Course, E= Elective Course</div></td>
                              </tr>
    </table>
	<table width="1265" align="center">
		  <tr>
		    <td width="398" colspan="3"><table width="324">
		      <tr>
		        <td width="183">Effective Date of the Award:</td>
		        <td width="305"><div align="left"><em>
		          <?php 					       $selext="select* from biodata  where matric='$stud' ";
                          $resext=mysqli_query($link,$selext) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($resext)){
					  $effectivedate=$row['effectivedate'];
					    $approval=$row['approval'];
						$dg=$row['degree'];
						$spec=$row['specialization'];
						  $external=$row['external'];
						  $dep=$row['department'];
										  //echo $subsign;
					  } echo date('j F, Y',strtotime($effectivedate));  //echo  $effectivedate;
   ?>
	            </em></div></td>
	          </tr>
		      </table></td>
		    <td width="266" colspan="2"><table width="200">
		      <tr>
		        <td>&nbsp;</td>
		        <td>&nbsp;</td>
	          </tr>
		      </table></td>
	      </tr>
		  <tr>
		    <td colspan="2" align="center" valign="bottom"><div align="center"><em>
              <?php $dept=$_SESSION['dept_new']; 
		$exttitle='';
		//$hod5=$_GET['hod'];
					      $selext="select * from hod where dept_new='$dept' and status='0' ";
                          $resext=mysqli_query($link,$selext) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($resext)){
					  $hodtitle=$row['title'];
					  $hodini=$row['initial'];
					  $hoddean=$row['lname'];
					  $hoddean=$row['lname'];
					  $hodsign=$row['signature'];
					  $design=$row['designation'];
					  //echo $subsign;
					  }
					       $selext="select* from designation where id='$design' ";
                          $resext=mysqli_query($link,$selext) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($resext)){
					  $designation=$row['title'];
										  //echo $subsign;
					  }
					       $selext="select* from title where id='$exttitle' ";
                          $resext=mysqli_query($link,$selext) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($resext)){
					  $exttitle2=$row['title'];
										  //echo $subsign;
					  }
					 //  echo "<img src=$hodsign  width=90 height=30 />";  ?>
            </em></div><hr></td>
		    <td width="577" rowspan="3"><div align="center">
		      <?php 
				
				$dept=$_SESSION['dept_new']; //include('connect.php');
	$sec2=$_GET['sec2'];
				$ext=$_GET['ext'];
	$spec=$_GET['special'];
		$effect=$_GET['effect'];
				$r="";
	$degreetitle=$_GET['degree'];
	$query = "SELECT * FROM dept_new where id='$dept' and status='0'";
	$querycon = mysqli_query($link,$query) or die(mysqli_error($link));
	while($row=mysqli_fetch_array($querycon))	{
	$dept_title=$row['department'];
	$centre= substr($dept_title,0,6);
	//echo $dept_title;
	}
	$query3 = "SELECT * FROM fieldofinterest4 where id='$spec'";
	$querycon3 = mysqli_query($link,$query3) or die(mysqli_error($link));
	while($row=mysqli_fetch_array($querycon3))	{
	$field=$row['field'];
		$fac=$row['fac'];
		$degree=$row['degree'];
		}				  
				echo"<table  align=center><th colspan=4>SUMMARY</th></tr>";
		//echo array_sum($names);
		    //and matric='$stud'
		//echo $stud."<br>";
								$seled="SELECT  result, count(result)FROM biodata WHERE department='$dept_title' and specialization='$field' and external='$ext' and effectivedate='$effect'  and  yr_of_entry='$sec2' and degree='$degree'  and result<>'$r' GROUP BY result order by result";
								$qqqq=mysqli_query($link,$seled) or die(mysqli_error($link));
								while($row=mysqli_fetch_array($qqqq))
								{ $remrk=$row['count(result)'];
								  $remrk2=$row['result'];
								  echo"<tr><td>$remrk2 </td><td><b>=</b></td><td><b></b></td><td><b>$remrk</b></td></tr>";
								  }
			
		//and matric='$names'
				 
								  
								  	$seled="SELECT  result, count(result)FROM biodata WHERE department='$dept_title' and specialization='$field' and external='$ext' and degree='$degree' and effectivedate='$effect'  and result<>'$r'  and  yr_of_entry='$sec2' GROUP BY external ";
								$qqqq=mysqli_query($link,$seled) or die(mysqli_error($link));
								while($row=mysqli_fetch_array($qqqq))
								{ $remrk=$row['count(result)'];
								  $remrk2=$row['result'];
								  echo"<tr><td colspan=4><hr></td></tr>";
								  echo"<tr><td>Total Number of student(s)</td><td><b>=</b></td><td><b></b></td><td><b>$remrk</b></td>";
								  }
			//}
								 //and effectivedate='$effectivedate'
								  echo"</tr></table>";
								//echo $dept_title;
								     ?>
	        </div></td>
		    <td colspan="2" align="center" valign="bottom"><div align="center"><em>
		      <?php $dept=$_SESSION['dept_new']; 
				$ext=$_GET['ext'];
					      $selhod="select* from external where dept_new='$dept' and id='$ext'  ";  //and id=''
                          $reshod=mysqli_query($link,$selhod) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($reshod)){
					  $exttitle=$row['title'];
					  $extini=$row['initial'];
					  $extdean=$row['lname'];
					  $extdean=$row['lname'];
					  $extsign=$row['signature'];
					  
					  //echo $exttitle;
					  //echo $subsign;
					  }
					  $hodtitle2='';
					  if(isset($hodtitle)){
					       $selhod="select* from title where id='$hodtitle' ";
                          $reshod=mysqli_query($link,$selhod) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($reshod)){
					  $hodtitle2=$row['title'];
										  //echo $subsign;
					  }
					  }
				
				$selext2="select* from title where id='$exttitle' ";
                          $resext2=mysqli_query($link,$selext2) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($resext2)){
					  $exttitle3=$row['title'];
										  //echo $subsign;
						 // echo $exttitle3;
					  }
				
					   echo "<img src=$extsign  width=90 height=30 />";  ?>
	        </em></div><hr></td>
	      </tr>
		  <tr>
		    <td colspan="2" valign="bottom"><div align="center"><em><?php echo "$hodtitle2  $hodini $hoddean";  ?></em></div></td>
		    <td colspan="2" valign="bottom"><div align="center"><em><?php echo "$exttitle3  $extini $extdean";  ?></em></div></td>
	      </tr>
		  <tr>
		    <td colspan="2" valign="top"><div align="center"><b><?php echo $designation;  ?></b></div></td>
		    <td colspan="2" valign="top"><div align="center"><strong>External Examiner</strong></div></td>
	      </tr>
    </table>
	<table width="717" align="center">
	  <tr>
		    <td width="397">Approved at the Faculty Postgraduate Committe meeting of</td>
		    <td width="215"><em>
		      <?php  echo date('j F, Y',strtotime($approval));  ?>
		      </em></td>
		    <td width="89" align="center" valign="bottom">&nbsp;</td>
      </tr>
<tr>
	    <td colspan="3"><table width="200" align="center">
	        <tbody>
	          <tr>
	            <td align="center"><em>
		            <?php $dept=$_SESSION['dept_new']; 
					//$sub5=$_GET['sub'];
					      $selsub="select* from subdean where dept_new='$dept' and status='0' ";
                          $ressub=mysqli_query($link,$selsub) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($ressub)){
					  $subtitle=$row['title'];
					  $subini=$row['initial'];
					  $subdean=$row['lname'];
					  $subdean=$row['lname'];
					  $subsign=$row['signature'];
					  //echo $subsign;
					  }
					       $selsub="select* from title where id='$subtitle' ";
                          $ressub=mysqli_query($link,$selsub) or die(mysqli_error($link));
					  while($row=mysqli_fetch_array($ressub)){
					  $subtitle2=$row['title'];
										  //echo $subsign;
					  }
					  // echo "<img src=$subsign  width=90 height=30 />";  ?>
	            </em><hr></td>
              </tr>
	          <tr>
	            <td align="center"><em>
		            <?php  echo "$subtitle2  $subini $subdean";  ?>
	            </em></td>
              </tr>
<tr>
		          <td align="center"></td>
              </tr>
	          <tr>
	            <td align="center"><strong>Sub-Dean (Postgraduate)</strong></td>
              </tr>
            </tbody>
	        </table>
	        
        </td>
      </tr>
    </table>
    </div></div></div>
    <?php }//} ?>	
<html>