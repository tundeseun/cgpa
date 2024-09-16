<?php
  ob_start(); // Start output buffering
include('connect.php'); 
use PhpOffice\PhpSpreadsheet\IOFactory;

//Mrs Adeyemi

function getStudentCourseID($appno, $conn)
{
  // include "conn.php";
  $sql = "SELECT coz_id FROM reg_coz WHERE appno ='$appno'";
  $result = $conn->query($sql);

  return $result;

}

function getStudentCourseCode($regid, $conn)
{
    $sql = "SELECT course_code FROM course_new WHERE course_online_id =$regid";
    $resultnew = $conn->query($sql);

    if ($resultnew && $resultnew->num_rows > 0) {  //$resultnew && 
        $rowidnew = $resultnew->fetch_array(MYSQLI_ASSOC);
        echo $rowidnew['course_code'];
        echo ", ";
    } else {
        echo 'No record, ';
    }
}



//Abiona
function showcourse($conn,$dept)
{

    $select=mysqli_query($conn,"select cgpa_id,course_code,status,unit from course_new where dept_newids='$dept'") or die(mysqli_error($conn));
    while($row=mysqli_fetch_array($select))
    {
      $id=$row['cgpa_id'];
      $course_code=$row['course_code'];
      $status=$row['status'];
      $unit=$row['unit'];
    
    //  echo "<option value=''></option>";
     echo " <option value=".$id.",".$status.",".$unit." >";
       echo $course_code."( ".$status." )"; }
      echo "</option>";

}

function showprogramme($conn,$dept)
{

    $select=mysqli_query($conn,"select DISTINCT degree_new.degree, fieldofinterest5.degree as degreeid FROM fieldofinterest5 inner join degree_new on degree_new.id=fieldofinterest5.degree where fieldofinterest5.dept='$dept' order by degree") or die(mysqli_error($conn));
    while($row=mysqli_fetch_array($select))
    {
      $degreeid=$row['degreeid'];
      $degree=$row['degree'];
    
     echo "<option value=''></option>";
     echo " <option value=".$degreeid." >";
       echo $degree; }
      echo "</option>";

}
function showsessionexamined($conn)
{

    $select=mysqli_query($conn,"select id,sec from sec_examined") or die(mysqli_error($conn));
    while($row=mysqli_fetch_array($select))
    {
      $id=$row['id'];
      $sec=$row['sec'];
    
     echo "<option value=''></option>";
     echo " <option value=".$id." >";
       echo $sec; }
      echo "</option>";

}
function showdept($dept,$conn)
{

    $checkQuery = "SELECT department FROM dept_new WHERE id = ? ";
            $checkStmt = $conn->prepare($checkQuery);

            if ($checkStmt) {
                $checkStmt->bind_param("s", $dept);
                $checkStmt->execute();
                $checkStmt->bind_result($department);
                $checkStmt->fetch();
                $checkStmt->close(); // Close the prepared statement

             echo $department;
            }

}
function checkuser($username,$password,$conn)
{

    $checkQuery = "SELECT COUNT(*),dept_new FROM users WHERE username = ? AND password = ?";
            $checkStmt = $conn->prepare($checkQuery);

            if ($checkStmt) {
                $checkStmt->bind_param("ss", $username, $password);
                $checkStmt->execute();
                $checkStmt->bind_result($count,$dept_new);
                $checkStmt->fetch();
                $checkStmt->close(); // Close the prepared statement

                if ($count > 0) {
                  
            header('Location: dashboard.php?p=1&dept_new=' . $dept_new);
            // ob_end_flush(); // Flush the output buffer and send the headers
            // exit;
                }
            }

}
// Function to upload and process an Excel file to MySQL
function uploadExcelToMySQL($excelFile, $conn, $sec, $coz)
{
    if (isset($excelFile) && $excelFile['error'] === UPLOAD_ERR_OK) {
        $tmpFileName = $excelFile['tmp_name'];

        require 'vendor/autoload.php'; // Require the autoload.php from PhpSpreadsheet

        $objPHPExcel = IOFactory::load($tmpFileName);
        $worksheet = $objPHPExcel->getActiveSheet();
        $insertedData = [];
        $failedData = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            $checkQuery1 = "SELECT prev_app.matric,zmain_app.faculty,zmain_app.department,zmain_app.user_id,zmain_app.field_of_interest,zmain_app.mode_of_study,zmain_app.degree FROM zmain_app INNER JOIN prev_app ON prev_app.user_id = zmain_app.user_id  WHERE zmain_app.degree <> '3' AND zmain_app.degree <> '4' AND zmain_app.degree <> '5' AND prev_app.matric = ?";
            $checkStmt1 = $conn->prepare($checkQuery1);

            if (!$checkStmt1) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $checkStmt1->bind_param("s", $rowData[0]);
                $checkStmt1->execute();
                $checkStmt1->bind_result($matric, $faculty, $department, $user_id, $field_of_interest, $mode, $degree);
                $checkStmt1->fetch();
                $checkStmt1->close();


                $checkQuery3 = "SELECT form_year FROM form WHERE user_id=?";
                $checkStmt3 = $conn->prepare($checkQuery3);
    
                if (!$checkStmt3) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                } else {
                    $checkStmt3->bind_param("s", $user_id);
                    $checkStmt3->execute();
                    $checkStmt3->bind_result($form_year);
                    $checkStmt3->fetch();
                    $checkStmt3->close();


                $checkQuery = "SELECT COUNT(*) FROM testscore WHERE matric = ? AND score = ? AND dept=? AND exam_sec=?";
                $checkStmt = $conn->prepare($checkQuery);

                if (!$checkStmt) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                } else {
                    $checkStmt->bind_param("ssss", $rowData[0], $rowData[1],$department,$sec);
                    $checkStmt->execute();
                    $checkStmt->bind_result($count);
                    $checkStmt->fetch();
                    $checkStmt->close();

                    if ($count == 0) {
                        $split = explode(",", $coz);
                        $coz1 = $split[0];
                        $status = $split[1];
                        $unit = $split[2];
                        $query = "INSERT INTO testscore (matric, score,degree, dept, user_id, field, fac, exam_sec, cozid, cstatus, cunit,mode,yr_of_entry) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);

                        if (!$stmt) {
                            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                        } else {
                            $stmt->bind_param("sssssssssssss", $rowData[0], $rowData[1], $degree,$department, $user_id, $field_of_interest, $faculty, $sec, $coz1, $status, $unit,$mode,$form_year);
                            if ($stmt->execute()) {
                                $insertedData[] = $rowData;
                            } else {
                                $failedData[] = $rowData;
                            }
                            $stmt->close();
                        }
                    }
                }
            }
        }
    }

        $conn->close();
echo "<div align='center'>";
        echo "Records successfully uploaded: " . count($insertedData);
        echo "<h3>Uploaded Records:</h3>";
        echo "<ul>";
        foreach ($insertedData as $record) {
            echo "<li>Inserted: " . implode(' | ', $record) . "</li>";
        }
        echo "</ul>";
        if (isset($failedData) && is_array($failedData) && count($failedData) > 0) {
        echo "<h3>Failed Records:</h3>";
        echo "<ul>";
        foreach ($failedData as $record) {
            echo "<li>Not Inserted: " . implode(' | ', $record) . "</li>";
        }
        echo "</ul>";
    }
    else {
        echo "No failed data to display.";
    }
    } else {
        echo "File upload failed!";
    }
    echo "</div>";
}

// Usage: Handle the file upload form submission


function processresult($conn,$field,$effectivedate,$external)
{

$sql = "SELECT t.matric, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code, c.unit, c.status
        FROM testscore t
        INNER JOIN course_new c ON t.cozid = c.cgpa_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external'
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
        if (($courseStatus === 'C' && $score > 39) || ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E' || $courseStatus === 'EE'))) {
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
    echo '<table width=100% class=custom-table   align=center>';
    // Output header row
    echo '<tr>';
    echo '<th class=codea>S/N</th><th >MATRICNO</th>';
    foreach ($courses as $courseCode => $scores) {
       
        echo "<th class=codea>$courseCode</th>";
    }
    echo '<th class=codea>TUT</th><th class=codea>TUP</th><th class=codea>TGP</th><th class=codea>CGPA</th><th class=codea>RESULT</th><th class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
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
    echo '<br><br><table class=custom-table   align=center>';
    echo '<tr><td>Remark</td><td>Total</td></tr>';

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
}

?>