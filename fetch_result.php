<?php
session_start();
include_once('function/script.php');

$limit = 20;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$start_from = ($page - 1) * $limit;

// Fetch the paginated results
$queryDisplay = "
  SELECT DISTINCT 
    fac_new.faculty AS faculty,
    dept_new.department AS department,
    field_new.field_title AS specialization,
    testscore.resulttype AS resulttype,
    testscore.fac AS fac_id,
    testscore.dept AS dept_id,
    testscore.degree AS degree_id,
    testscore.field AS field_id,
    testscore.mode AS smode, 
    testscore.effectivedate AS effectivedate
  FROM testscore 
  INNER JOIN fac_new ON fac_new.id = testscore.fac 
  INNER JOIN field_new ON field_new.id = testscore.field 
  INNER JOIN dept_new ON dept_new.id = testscore.dept WHERE session_of_grad <> NULL OR session_of_grad <> '' AND testscore.stage = 2 Order BY faculty
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
  SELECT COUNT(DISTINCT testscore.fac) AS total_records 
  FROM testscore 
  INNER JOIN fac_new ON fac_new.id = testscore.fac 
  INNER JOIN field_new ON field_new.id = testscore.field 
  INNER JOIN dept_new ON dept_new.id = testscore.dept
";
$resultTotal = mysqli_query($conn, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$total_records = $rowTotal['total_records'];

$total_pages = ceil($total_records / $limit);

$response = array(
  'data' => $data,
  'total_pages' => $total_pages,
);

echo json_encode($response);
