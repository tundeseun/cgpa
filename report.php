<?php
// Replace with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgcgpa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve data from course_new and testscore tables
$sql = "SELECT t.matric, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code, c.unit, c.status
        FROM testscore t
        INNER JOIN course_new c ON t.cozid = c.cgpa_id
        ORDER BY t.matric, c.course_code, t.exam_sec"; // Ordering by matric, course_code, and exam_sec

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Initialize arrays to store data
    $courses = array();
    $matrics = array();
    $status = array();
    $unit = array();
    $tupValues = array();
    $tgpValues = array();

    // Iterate through the results and organize data
    while ($row = $result->fetch_assoc()) {
        $matric = $row["matric"];
        $courseCode = $row["course_code"];
        $score = $row["score"];
        $courseUnit = $row["unit"];
        $courseStatus = $row["status"];
        $examSec = $row["exam_sec"];

        // Store course codes as keys and scores as values in the courses array
        if (!isset($courses[$courseCode][$matric])) {
            $courses[$courseCode][$matric] = array();
        }

        // Store unique matric numbers
        if (!in_array($matric, $matrics)) {
            $matrics[] = $matric;
        }

        // Store status for each course
        $status[$courseCode] = $courseStatus;

        // Store units for each course code
        if (!isset($unit[$courseCode])) {
            $unit[$courseCode] = $courseUnit;
        }

        // Store units for TUT calculation
        if (!isset($unit[$matric])) {
            $unit[$matric] = 0;
        }
        $unit[$matric] += $courseUnit;

        // Calculate TUP values based on different status and score criteria
        if (($courseStatus === 'C' && $score > 39) || ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E'))) {
            if (!isset($tupValues[$matric])) {
                $tupValues[$matric] = 0;
            }
            $tupValues[$matric] += $courseUnit;
        }

        // Calculate point per score for each course_code
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

        // Calculate GP (point per course_code multiplied by unit per course_code)
        if (!isset($tgpValues[$matric][$courseCode])) {
            $tgpValues[$matric][$courseCode] = 0;
        }
        $tgpValues[$matric][$courseCode] += $point * $courseUnit;

        // Store scores for the same course and matric
        $courses[$courseCode][$matric][$examSec] = $score;
    }

    // Sort courses alphabetically
    ksort($courses);

    // Sort status in the desired order: C, R, E
    $status_order = ['C' => 1, 'R' => 2, 'E' => 3];
    uasort($status, function ($a, $b) use ($status_order) {
        return ($status_order[$a] ?? 0) <=> ($status_order[$b] ?? 0);
    });
// $a=0;
// $a=$a + 1;
    // Display the data in a table
    echo '<table border="1">';
    // Output header row
    echo '<tr>';
    echo '<td>S/N</td><td>MATRICNO</td>';
    foreach ($courses as $courseCode => $scores) {
       
        echo "<td>$courseCode</td>";
    }
    echo '<td>TUT</td><td>TUP</td><td>TGP</td><td>CGPA</td><td>RESULT</td><td>REMARK</td>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
    echo '</tr>';

    // Output status row
    echo '<tr>';
    echo '<td></td><td></td>';
    foreach ($status as $courseCode => $courseStatus) {
        echo '<td>' . $courseStatus . '</td>';
    }
    echo '<td></td><td></td>'; // Empty cells for TUT, TUP in the status row
    echo '</tr>';

    // Output unit row
    echo '<tr>';
    echo '<td></td><td></td>';
    foreach ($courses as $courseCode => $scores) {
        echo '<td>' . ($unit[$courseCode] ?? '') . '</td>';
    }
    echo '<td></td><td></td>'; // Empty cells for TUT, TUP in the unit row
    echo '</tr>';

    // Initialize counters for each remark category
    $totalPhD = 0;
    $totalMPhilPhD = 0;
    $totalMPhil = 0;
    $totalTM = 0;
    $totalNG = 0;
    $totalUnknown = 0;
    $serialNumber = 1;
    // Output data rows for each matric number
    foreach ($matrics as $matric) {
        echo '<tr>';
        echo "<td>" . $serialNumber++ . "</td><td>$matric</td>";
        foreach ($courses as $courseCode => $scores) {
            echo '<td>';
            if (isset($courses[$courseCode][$matric])) {
                $courseScores = $courses[$courseCode][$matric];
                ksort($courseScores); // Sort scores by exam_sec

                // If there are multiple scores, concatenate them with '/'
                if (count($courseScores) > 1) {
                    $concatenatedScores = implode('/', $courseScores);
                    echo $concatenatedScores;
                } else {
                    echo reset($courseScores); // Display the single score
                }
            } else {
                echo '-';
            }
            echo '</td>';
        }

        // Output TUT, TUP, TGP, and CGPA for each matric
        echo '<td>' . ($unit[$matric] ?? '') . '</td>';
        echo '<td>' . ($tupValues[$matric] ?? '') . '</td>';
        
        $tgpSum = 0;
        foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
            $tgpSum += $tgp;
        }
        echo '<td>' . ($tgpSum ?? '') . '</td>';
        
        $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
        echo '<td>' . $cgpa . '</td>';

        // Calculate the remarks and increment the corresponding counter
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
        } elseif ($cgpa < 1.0) {
            $remark = "NG";
            $result = "-";
            $cgpa = "-";
            $totalNG++;
        } else {
            $remark = "-";
            $result = "-";
            $totalUnknown++;
        }
        
        // Output the remarks in the main table
        echo '<td>' . $result . '</td>';
        echo '<td>' . $remark . '</td>';
        echo '</tr>';
    }

    // Display total counts for remarks
    echo '<br><br><table border="1" align=center>';
    echo '<tr><th>Remark</th><th>Total</th></tr>';

    echo '<tr><td>PhD</td><td>' . $totalPhD . '</td></tr>';
    echo '<tr><td>M.Phil/Ph.D</td><td>' . $totalMPhilPhD . '</td></tr>';
    echo '<tr><td>M.Phil</td><td>' . $totalMPhil . '</td></tr>';
    echo '<tr><td>TM</td><td>' . $totalTM . '</td></tr>';
    echo '<tr><td>NG</td><td>' . $totalNG . '</td></tr>';
   // echo '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

    // Add a single row to display the combined total
    $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
    echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

    echo '</table>';

} else {
    echo "0 results";
}

// Close the database connection
$conn->close();
?>