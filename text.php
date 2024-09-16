<?php session_start();
if (isset($_GET["department"])) {
$dept = $_GET["department"];
} else {
$dept = $_SESSION["dept_new"];
} ?>
<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'pgcollege';
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sec = $_GET['sec'];
$field = $_GET['field'];
$degree = $_GET['degree'];
$effectivedate = $_GET['effectivedate'];
$external = $_GET['external'];
$resulttype = $_GET['resulttype'];
$sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
$row = mysqli_fetch_array($sel);
$sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
$row2 = mysqli_fetch_array($sel);
$naration = $row2['naration'];
$header1 = "<h2 align=center>University of Ibadan</h2>";
$header2 = $row['faculty'];
$header3 = $row['department'];
$header4 = $row['field_title'];
$m0 = strtoupper($header1);
$m1 = "<h3 align=center>" . strtoupper("faculty of " . $header2) . "</h3>";
$m2 = "<h3 align=center>" . strtoupper("Department of " . $header3) . "</h3>";
$m3 = "<h3 align=center>" . strtoupper($naration . " Degree Examination Results " . $sec . " Session") . "</h3>";
$m4 = "<h3 align=center>" . strtoupper("Area of specialization: " . $header4) . "</h3>";
$msg = $m0 . $m1 . $m2 . $m3 . $m4;
$approval_query = "SELECT DISTINCT approval FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'";
$approval_result = $conn->query($approval_query);
$approval_row = $approval_result->fetch_assoc();
$approval_date = $approval_row['approval'];
$external_query = "SELECT * FROM external WHERE id = $external";
$external_result = $conn->query($external_query);
$external_row = $external_result->fetch_assoc();
$external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
$external_sign = $external_row['signature'];
$subdean_query = "SELECT * FROM subdean WHERE dept_new = $dept AND status = 1";
$subdean_result = $conn->query($subdean_query);
$subdean_row = $subdean_result->fetch_assoc();
$subdean_sign = $subdean_row['signature'];
$subdean_title_id = $subdean_row['title'];
$subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
$subdean_title_result = $conn->query($subdean_title_query);
$subdean_title_row = $subdean_title_result->fetch_assoc();
$subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];
$hod_query = "SELECT * FROM hod WHERE dept_new = $dept AND status = 1";
$hod_result = $conn->query($hod_query);
$hod_row = $hod_result->fetch_assoc();
$hod_desig_id = $hod_row['designation'];
$hod_title_id = $hod_row['title'];
$hod_desig_query = "SELECT title FROM designation WHERE id = '$hod_desig_id'";
$hod_desig_result = $conn->query($hod_desig_query);
$hod_desig_row = $hod_desig_result->fetch_assoc();
$hod_desig = $hod_desig_row['title'];
$hod_title_query = "SELECT title FROM title WHERE id = '$hod_title_id'";
$hod_title_result = $conn->query($hod_title_query);
$hod_title_row = $hod_title_result->fetch_assoc();
if ($hod_desig === 'Professor and Head') {
$hod_name = $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
} else {
$hod_name = $hod_title_row['title'] . ' ' . $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
}
$sql = "SELECT t.matric,t.yr_of_entry,t.mode, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code,c.corder, c.unit, c.status,p.type as program
FROM testscore t
LEFT JOIN course_new c ON t.cozid = c.cgpa_id LEFT JOIN programme p ON t.degree = p.degree_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'
ORDER BY c.corder";
$result = $conn->query($sql);
$coz = mysqli_query($conn, "select sum(unit) as core from course_new where dept_newids='$dept' and specialization='$field' and status2='0' and status='C' order by course_code") or die(mysqli_error($conn));
$row = mysqli_fetch_array($coz);
$tctp = $row['core'];
$coz1 = mysqli_query($conn, "select sum(unit) as required from course_new where dept_newids='$dept' and specialization='$field' and status2='0' and status='R' order by course_code") or die(mysqli_error($conn));
$row1 = mysqli_fetch_array($coz1);
$trtp = $row1['required'];
$coz2 = mysqli_query($conn, "select sum(unit) as elective from course_new where dept_newids='$dept' and specialization='$field' and status2='0' and (status='E' or status='EE') order by course_code") or die(mysqli_error($conn));
$row2 = mysqli_fetch_array($coz2);
$tetp = $row2['elective'];
$ttp = $tctp + $trtp;
if (($result->num_rows > 0)) {
$courses = array();
$matrics = array();
$status = array();
$unit = array();
$tupValues = array();
$tgpValues = array();
$coretopass = array();
$corepassed = array();
$unittopass = array();
$requiredpassed = array();
$totalCoreCoursesPassed = 0;
while ($row = $result->fetch_assoc()) {
$matric = $row["matric"];
$mode = $row["mode"];
if ($mode == 1) {
$modeOfStudy = "FullTime";
} elseif ($mode == 2) {
$modeOfStudy = "PartTime";
}
$entry = $row["yr_of_entry"];
$courseCode = $row["course_code"];
$score = $row["score"];
$corder = $row["corder"];
$courseUnit = $row["unit"];
$courseStatus = $row["status"];
$examSec = $row["exam_sec"];
$program = $row["program"];
if (!isset($courses[$courseCode][$matric])) {
$courses[$courseCode][$matric] = array();
}
if (!in_array($matric, $matrics)) {
$matrics[] = $matric;
}
$status[$courseCode] = $courseStatus;
if (!isset($unit[$courseCode])) {
$unit[$courseCode] = $courseUnit;
}
if (!isset($unit[$matric])) {
$unit[$matric] = 0;
}
$unit[$matric] += $courseUnit;
if (($courseStatus === 'C' && $score > 39) || ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E' || $courseStatus === 'EE'))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'
if (!isset($tupValues[$matric])) {
$tupValues[$matric] = 0;
}
$tupValues[$matric] += $courseUnit;
}
if (($courseStatus === 'C')) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'
if (!isset($coretopass[$matric])) {
$coretopass[$matric] = 0;
}
$coretopass[$matric] += $courseUnit;
}
if (($courseStatus == 'C') && ($score >= 40)) {  //($score >= 40) AND //|| $courseStatus === 'E' || $courseStatus === 'EE'
if (!isset($corepassed[$matric])) {
$corepassed[$matric] = 0;
}
$corepassed[$matric] += $courseUnit;
}
if (($courseStatus == 'R') && ($score >= 30)) {  //($score >= 40) AND //|| $courseStatus === 'E' || $courseStatus === 'EE'
if (!isset($requiredpassed[$matric])) {
$requiredpassed[$matric] = 0;
}
$requiredpassed[$matric] += $courseUnit;
}
if ($status[$courseCode] === 'C' and $score >= 40) {
$totalCoreCoursesPassed++;
}
if ($score <= 39) {
$point = 0;
} elseif ($score >= 40 && $score < 45) {
$point = 1;
} elseif ($score >= 45 && $score < 50) {
$point = 2;
} elseif ($score >= 50 && $score < 55) {
$point = 3;
} elseif ($score >= 55 && $score < 60) {
$point = 4;
} elseif ($score >= 60 && $score < 65) {
$point = 5;
} elseif ($score >= 65 && $score < 70) {
$point = 6;
} elseif ($score >= 70 && $score < 101) {
$point = 7;
}
if (!isset($tgpValues[$matric][$courseCode])) {
$tgpValues[$matric][$courseCode] = 0;
}
$tgpValues[$matric][$courseCode] += $point * $courseUnit;
$courses[$courseCode][$matric][$examSec] = $score;
}
$p0 =  '<table width=100% class=custom-table   align=center>';
$p1 =  '<tr>';
$p2 =  '<th class=codea>S/N</th><th >MATRICNO</th><th >Session</th><th >Mode</th>';
foreach ($courses as $courseCode => $scores) {
$p3 =  "<th class=codea>$courseCode</th>";
}
$p4 =  '<th class=codea>TUT</th><th class=codea>TUP</th><th class=codea>TGP</th><th class=codea>CGPA</th><th class=codea>RESULT</th><th class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
$p5 =  '</tr>';
$p6 =  '<tr>';
$p7 =  '<td></td><td></td><td></td><td></td>';
foreach ($status as $courseCode => $courseStatus) {
$p8 =  '<td>' . $courseStatus . '</td>';
}
$p9 =  '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the status row
$p10 =  '</tr>';
$p11 =  '<tr>';
$p12 =  '<td></td><td></td><td></td><td></td>';
foreach ($courses as $courseCode => $scores) {
$p13 =  '<td>' . ($unit[$courseCode] ?? '') . '</td>';
}
$p14 =  '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the unit row
$p15 =  '</tr>';
$totalPhD = 0;
$totalMPhilPhD = 0;
$totalMPhil = 0;
$totalTM = 0;
$totalNG = 0;
$totalProf = 0;
$totalUnknown = 0;
$serialNumber = 1;
foreach ($matrics as $matric) {
$p16 =  '<tr>';
$p17 =  "<td>" . $serialNumber++ . "</td><td>$matric</td><td>$entry</td><td>$modeOfStudy</td>";
foreach ($courses as $courseCode => $scores) {
$p18 =  '<td>';
if (isset($courses[$courseCode][$matric])) {
$courseScores = $courses[$courseCode][$matric];
ksort($courseScores); // Sort scores by exam_sec
if (count($courseScores) > 1) {
$conncatenatedScores = implode('/', $courseScores);
$p19 =  $conncatenatedScores;
} else {
$p20 =  reset($courseScores); // Display the single score
}
} else {
$p21 =  '-';
}
$p22 =  '</td>';
}
$p23 =  '<td>' . ($unit[$matric] ?? '') . '</td>';
$p24 =  '<td>' . ($tupValues[$matric] ?? '') . '</td>';
$tgpSum = 0;
foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
$tgpSum += $tgp;
}
$p25 =  '<td>' . ($tgpSum ?? '') . '</td>';
$cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
if (($program == 'Academics') && ($tupValues[$matric] > 29)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
if ($cgpa < 9.0 && $cgpa >= 5.0) {
$remark = "Ph.D";
$result = "PASS";
$totalPhD++;
} elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
$remark = "M.Phil/Ph.D";
$result = "PASS";
$totalMPhilPhD++;
} elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
$remark = "M.Phil";
$result = "PASS";
$totalMPhil++;
} elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
$remark = "TM";
$result = "PASS";
$totalTM++;
}
}
elseif (($program == 'Academics') && (($tupValues[$matric] < 30)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
$remark = "NG";
$result = "-";
$cgpa = "-";
$totalNG++;
} elseif (($program == 'Professional') && ($tupValues[$matric] > 44)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
$remark = "PASS";
$result = "PASS";
$totalProf++;
}
elseif (($program == 'Professional') && (($tupValues[$matric] < 45)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
$remark = "NG";
$result = "-";
$totalProf++;
} else {
$remark = "-";
$result = "-";
$totalUnknown++;
}
if ($remark === 'NG') {
$p26 =  '<td>' . '-' . '</td>';
} else {
$p27 =  '<td>' . $cgpa . '</td>';
}
$p28 =  '<td>' . $result . '</td>';
$p29 =  '<td>' . $remark . '</td>';
$p30 =  '</tr>';
}
if ($program == 'Academics') {
$totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
$p31 =  '<br><br><table class=custom-table   align=center>';
$p32 =  '<h4 style= text-align:center; >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';
$p33 =  "<tr><td colspan=6>SUMMARY</td></tr>";
$p34 =  '<tr><td>PhD</td><td>M.Phil/Ph.D</td><td>M.Phil</td><td>TM</td><td>NG</td><td>Total </td></tr>';
$p35 =  '<tr><td>' . $totalPhD . '</td>';
$p36 =  '<td>' . $totalMPhilPhD . '</td>';
$p37 =  '<td>' . $totalMPhil . '</td>';
$p38 =  '<td>' . $totalTM . '</td>';
$p39 =  '<td>' . $totalNG . '</td>';
$p40 =  '<td>' . $totalCombined . '</td></tr>';
$totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
$p41 =  '</table>';
$p42 =  "
<div class='foot'>
<div class='container'>
<h4>Effective Date of Award: $effectivedate</h4>
<div class='row'>
<div class='col-md-4'>
<h4 class='non'>Approve</h4>
<p  class='line'>
__________
</p>
<p>
<h4>$hod_name<br> $hod_desig</h4>
</p>
</div>
<div class='col-md-4'>
<h4>Approve at the Faculty Postgraduate Commitee meeting of $approval_date</h4>
<p  class='line'>
__________
</p>
<p>
<h4>$subdean_name<br> Sub-Dean (Postgraduate)</h4>
</p>
</div>
<div class='col-md-4'>
<p>
<img class='sign' src='img/$external_sign' alt='No Signature for this Examiner'>
<h4>$external_name <br> External Examiner</h4>
</p>
</div>
</div>
</div>
</div>
";
} elseif ($program == 'Professional') {
$p43 =  '<br><br><table class=custom-table   align=center>';
$p44 =  '<tr><td>Remark</td><td>Total</td></tr>';
$p45 =  '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
$p46 =  '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
$totalCombined = $totalProf + $totalNG;
$p47 =  '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';
$p48 =  '</table>';
}
} else {
$p49 =  "0 results";
}
$msg2 = $p0 . $p2 . $p3 . $p4 . $p5 . $p6 . $p7 . $p8 . $p9 . $p10 . $p11 . $p12 . $p13 . $p14 . $p15 . $p16 . $p17 . $p18 . $p20 . $p21 . $p22 . $p23 . $p24 . $p25 . $p26 . $p27 . $p28 . $p29 . $p30 . $p31 . $p32 . $p33 . $p34 . $p35 . $p36 . $p37 . $p38 . $p39 . $p40 . $p41 . $p42;
$message = "$msg . $msg2";
return $message;
?>
