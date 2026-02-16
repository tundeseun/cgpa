<?php
session_start();
$dept = $_SESSION["dept_new"];
include_once('function/script.php');

$limit = 100;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start_from = ($page - 1) * $limit;

// Fetch the paginated results
$queryDisplay = "
  SELECT DISTINCT 
  fac_new.faculty AS faculty,
  dept_new.department AS department,
  degree_new.degree AS degree_name,
  external_cgpa.fname AS external_fname,
  external_cgpa.lname AS external_lname,
  field_new.field_title AS specialization,
  testscore.resulttype AS resulttype,
  testscore.external AS external, 
  testscore.session_of_grad AS sec, 
  testscore.stage AS stage, 
    testscore.degree AS degree_id, 
  testscore.field AS field_id,
    testscore.effectivedate AS effectivedate, 
  testscore.mode AS smode,
  testscore.faculty_date AS facultyDate 
  FROM testscore 
  INNER JOIN fac_new ON fac_new.id = testscore.fac 
  INNER JOIN external_cgpa ON external_cgpa.id = testscore.external 
  INNER JOIN degree_new ON degree_new.id = testscore.degree 
  INNER JOIN field_new ON field_new.id = testscore.field 
  INNER JOIN dept_new ON dept_new.id = testscore.dept  WHERE testscore.dept = '$dept' AND (session_of_grad IS NOT NULL AND session_of_grad != '') Order BY faculty
  LIMIT $start_from, $limit 
";
$result = mysqli_query($conn, $queryDisplay);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $externalName = $row['external_fname'] . ' ' . $row['external_lname'];
  $resultType = $row['resulttype'];
  $smode = $row['smode'];
  if ($smode == 1) {
    $mode = "Full-Time";  // Mode 1 is Full-Time
} else if ($smode == 2) {
    $mode = "Part-Time";  // Mode 2 is Part-Time
} else {
    $mode = "Unknown";    // Added default case for unexpected values
}
  if ($resultType == 0) {

    $rType = "Main Result";
  } else if ($resultType == 1) {
    $rType = "Supplementary Result";
  }
  $row['resultT'] = $rType;
  $row['mode'] = $mode;
  $row['smode'] = $smode;
  $row['externalName'] = $externalName;
  $data[] = $row;
}

// Fetch the total number of records
$queryTotal = "
  SELECT COUNT(DISTINCT field) AS total_records 
  FROM testscore 
  WHERE dept = '$dept'";
$resultTotal = mysqli_query($conn, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$total_records = $rowTotal['total_records'];

$total_pages = ceil($total_records / $limit);

$response = array(
  'data' => $data,
  'total_pages' => $total_pages,
);

echo json_encode($response);
