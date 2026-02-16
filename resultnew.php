<?php

// Replace these values with your actual database connection details
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pgcgpa';

// Create a database connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check the connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

function displayscore($field, $dept, $effectivedate, $external) {
    global $conn;

    // SQL queries
    $testscoreQuery = "SELECT matric, yr_of_entry, mode, cozid, score, exam_sec
                       FROM testscore
                       WHERE field='$field' AND effectivedate='$effectivedate' AND external='$external'
                       ORDER BY matric, exam_sec";

    $courseQuery = "SELECT course_code, status, unit
                    FROM course_new
                    WHERE dept_newids='$dept' AND specialization='$field'
                    ORDER BY course_code";

    // Execute queries and fetch results
    $resultTestScore = mysqli_query($conn, $testscoreQuery);
    $resultCourse = mysqli_query($conn, $courseQuery);

    // Create an associative array to store score and units for each matric
    $scoreData = array();

    // Process the testscore results
    while ($rowTestScore = mysqli_fetch_assoc($resultTestScore)) {
        $matric = $rowTestScore['matric'];
        $examSec = $rowTestScore['exam_sec'];
        $scoreData[$matric][$examSec]['yr_of_entry'] = $rowTestScore['yr_of_entry'];
        $scoreData[$matric][$examSec]['mode'] = $rowTestScore['mode'];
        $scoreData[$matric][$examSec]['score'][$rowTestScore['cozid']] = $rowTestScore['score'];
    }

    // Start the HTML table with header
    echo '<table>';
    echo '<tr>';
    echo '<th>MATRICNO</th>';

    // Fetch and display course codes from course_new
    $courseCodes = array();
    while ($rowCourse = mysqli_fetch_assoc($resultCourse)) {
        $courseCode = $rowCourse['course_code'];
        $courseCodes[] = $courseCode;
        echo '<th>' . $courseCode . '</th>';
    }

    // Display additional header columns
    echo '<th>TUT</th>';
    echo '<th>TUP</th>';
    echo '<th>CTP</th>';
    echo '<th>CP</th>';
    echo '<th>NCP</th>';
    echo '<th>TGP</th>';
    echo '<th>CGPA</th>';
    echo '<th>RESULT</th>';
    echo '<th>REMARK</th>';
    echo '</tr>';

    // Process the course_new results
    foreach ($scoreData as $matric => $exams) {
        echo '<tr>';
        echo "<td>$matric</td>";

        foreach ($exams as $examSec => $score) {
            foreach ($courseCodes as $courseCode) {
                // Display the course score
                if (isset($score['score'][$courseCode])) {
                    $courseScore = $score['score'][$courseCode];
                } else {
                    $courseScore = "-";
                }

                echo "<td>$courseScore</td>";
            }

            // ... (remaining code)
        }

        echo '</tr>';
    }

    echo '</table>';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test score</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>Test score</h2>

    <?php
    // Example usage
    $field = 1;
    $dept = 76;
    $effectivedate = '2024-01-05';
    $external = 415;

    displayscore($field, $dept, $effectivedate, $external);
    ?>

</body>

</html>
