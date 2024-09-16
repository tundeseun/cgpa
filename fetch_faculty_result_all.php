<?php
session_start();
$fac = $_SESSION["faculty_id"];
include_once('function/script.php');

$limit = 20;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start_from = ($page - 1) * $limit;

$today = date('Y-m-d');

// Fetch the paginated results
$queryDisplay = "
  SELECT DISTINCT 
  fac_new.faculty AS faculty,
  dept_new.department AS department,
  degree_new.degree AS degree,
  field_new.field_title AS specialization,
  testscore.fac AS fac_id,
  testscore.dept AS dept_id,
  testscore.degree AS degree_id,
  testscore.field AS field_id,
  testscore.resulttype AS resulttype,
  testscore.stage AS stage,   
  testscore.mode AS smode, 
  testscore.effectivedate AS effectivedate, 
  testscore.faculty_date AS facultyDate 
  FROM testscore 
  INNER JOIN fac_new ON fac_new.id = testscore.fac 
  INNER JOIN degree_new ON degree_new.id = testscore.degree 
  INNER JOIN field_new ON field_new.id = testscore.field 
  INNER JOIN dept_new ON dept_new.id = testscore.dept  WHERE testscore.fac = '$fac' AND (session_of_grad IS NOT NULL AND session_of_grad != '') AND testscore.stage > 1 Order BY faculty
  LIMIT $start_from, $limit 
";
$result = mysqli_query($conn, $queryDisplay);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $resultType = $row['resulttype'];
  $smode = $row['smode'];
  if ($smode == 0) {

    $mode = "Part-Time";
} else if ($smode == 1) {
    $mode = "Full-Time";
}
  if ($resultType == 0) {

    $rType = "Main Result";
  } else if ($resultType == 1) {
    $rType = "Supplementary Result";
  }
  $row['resultT'] = $rType;
  $row['mode'] = $mode;
  $data[] = $row;
}

// Fetch the total number of records
$queryTotal = "
  SELECT COUNT(DISTINCT field) AS total_records 
  FROM testscore 
  WHERE fac = '$fac'";
$resultTotal = mysqli_query($conn, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$total_records = $rowTotal['total_records'];

$total_pages = ceil($total_records / $limit);

$response = array(
  'data' => $data,
  'total_pages' => $total_pages,
);

echo json_encode($response);
