<?php
include_once('../function/connect.php');
ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Check Student Course Status</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = mysqli_real_escape_string($conn, $_POST['matric']);

    $sql = "
   
SELECT 
    t.matric, t.score, t.exam_sec, t.cstatus, t.cunit, 
    d.department, f.faculty, deg.degree, t.yr_of_entry, 
    t.session_of_grad, ext.lname, ext.fname, t.effectivedate, 
    t.mode AS mode, t.status, t.exam_sec, fe.field AS field2, fieldof.field AS special,
    c.course_code AS course_code, c.course_title AS course_title, sec.sec AS sec, c.status2 AS course_status
FROM testscore t
LEFT JOIN course_new c ON t.cozid = c.id
LEFT JOIN dept_new d ON t.dept = d.id
LEFT JOIN fac_new f ON t.fac = f.id
LEFT JOIN degree_new deg ON t.degree = deg.id
LEFT JOIN external_cgpa ext ON t.external = ext.id
LEFT JOIN fieldofinterest4 fe ON t.field = fe.id
LEFT JOIN fieldofinterest4 fieldof ON c.specialization = fieldof.id
LEFT JOIN sec_examined sec ON t.exam_sec = sec.id
WHERE t.matric = '$matric'
";


    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "
<div class='container mt-5'>
  <div class='card shadow-lg border-0'>
    <div class='card-header bg-primary text-white'>
      <h4 class='mb-0'>Results for Matric: <strong>$matric</strong></h4>
    </div>
    <div class='card-body p-0'>
      <div class='table-responsive'>
        <table class='table table-hover table-bordered mb-0'>
          <thead class='table-light text-center'>
            <tr>
              <th>Course Code</th>
              <th>Course Title</th>
              <th>Score</th>
              <th>Exam Section</th>
              <th>Status</th>
              <th>Course Unit</th>
              <th>TESTSCORE-SPEC</th>
              <th>COURSE_NEW-SPEC</th>
              <th>Degree</th>
              <th>Year of Entry</th>
              <th>Session of Grad</th>
              <th>External Examiner</th>
              <th>Effective Date</th>
              <th>Mode</th>
              <th>Enabled/Disabled</th>
            </tr>
          </thead>
          <tbody>";

        while ($row = mysqli_fetch_assoc($result)) {
            $statusLabel = ($row['course_status'] == 0) ? "Enabled" : "Disabled";
            $name = $row['lname'] ." ". $row['fname'];
            $mode = $row['mode'];

            if ($mode == 1) {
                $modee = "Full-Time";
            } elseif ($mode == 2) {
                $modee = "Part-Time";
            } else {
                $modee = "Unknown";
            }
            echo "<tr>
                    <td>{$row['course_code']}</td>
                    <td>{$row['course_title']}</td>
                    <td>{$row['score']}</td>
                    <td>{$row['sec']}</td>
                    <td>{$row['cstatus']}</td>
                    <td>{$row['cunit']}</td>

                    <td>{$row['field2']}</td>
                    <td>{$row['special']}</td>
                    <td>{$row['degree']}</td>
                    <td>{$row['yr_of_entry']}</td>
                    <td>{$row['session_of_grad']}</td>
                    <td>{$name}</td>
                    <td>{$row['effectivedate']}</td>
                    <td>{$modee}</td>
                    <td><span class='badge bg-" . ($row['course_status'] == 0 ? "success" : "danger") . "'>$statusLabel</span></td>
                  </tr>";
        }
        echo "</tbody></table></div>";
    } else {
        echo "<div class='container mt-5'>
                <div class='alert alert-warning'>No records found for matric: $matric</div>
            </div>";
    }
}
?>
</body>
</html>