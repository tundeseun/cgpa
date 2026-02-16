<?php
  ob_start(); // Start output buffering
// include('connnect.php'); 
use PhpOffice\PhpSpreadsheet\IOFactory;

//

/// Mrs Adeyemi Function Start

//

function assignExternalAndEffectiveDate($conn, $excelFile, $effective, $external,$admin)
{
    if (isset($excelFile) && $excelFile['error'] === UPLOAD_ERR_OK) {
        $tmpFileName = $excelFile['tmp_name'];

        require 'vendor/autoload.php'; // Require the autoload.php from PhpSpreadsheet

        $objPHPExcel = PhpOffice\PhpSpreadsheet\IOFactory::load($tmpFileName);
        $worksheet = $objPHPExcel->getActiveSheet();

        $matricArray = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }

            foreach ($rowData as $record) {
                $query = "UPDATE testscore SET effectivedate = '$effective', external_cgpa ='$external' WHERE matric = '$record' ";
                $result = mysqli_query($conn, $query);

                $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update User with Matric:$record and set (Effective date:$effective, External Examiner:$external)')";
                $queryAudit = mysqli_query($conn,$sqlAudit);

                if ($result && $queryAudit) {
                    $matricArray[] = $record;
                }
            }
        }
               echo "<h3>Assigned Records:</h3>";
               echo "<table class='table table-bordered'>";
               echo "<thead><tr><th>S/N</th><th>Matric</th><th>Name</th><th>Effective Date</th><th>Examiner</th></tr></thead>";
               echo "<tbody>";
               $sn = 1;
               foreach ($matricArray as $record) {
                   $data = getDataFromTestScore($conn, $record);
                   $name = $data['name'];
                   $effectiveDate = $data['effective_date'];
                   $external = $data['external'];
       
                   echo "<tr><td>$sn</td><td>$record</td><td>$name</td><td>$effectiveDate</td><td>$external</td></tr>";
                   $sn++;

               }
               echo "</tbody></table>";
               echo "<script>document.getElementById('form').style.display = 'none';</script>";

               echo "<button type='submit' class='btn'><a href='assign2.php'>Return to Form</a></button>";     

           }

          


       }

       
       
       function getDataFromTestScore($conn, $matric)
{
    $query = "SELECT new.Surname, new.Other_names, testscore.effectivedate, testscore.external_cgpa, external_cgpa.lname, external_cgpa.fname, external_cgpa.initial FROM testscore INNER JOIN new ON testscore.user_id = new.id LEFT JOIN external_cgpa ON testscore.external = external_cgpa.id WHERE testscore.matric = '$matric'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $data = [
            'name' => $row['Surname'] . ' ' . $row['Other_names'],
            'effective_date' => $row['effectivedate'],
            'external' => $row['lname'] . ' ' . $row['fname'] . ' ' . $row['initial'],
        ];
        return $data;
    }

    return [];
}



function getStudentCourseID($appno, $conn)
{
  // include "connn.php";
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


//

/// Abiona Function Start

//

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

    $checkQuery = "SELECT COUNT(*),dept_new FROM users_cgpa_new WHERE username = ? AND password = ?";
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
function uploadExcelToMySQL($excelFile, $conn, $sec, $coz,$admin)
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

                        //Audit Trail Query Start Here
                        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
                        $queryAudit = $conn->prepare($sqlAudit);

                        if (!($stmt && $queryAudit)) {
                            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                        } else {
                            $stmt->bind_param("sssssssssssss", $rowData[0], $rowData[1], $degree,$department, $user_id, $field_of_interest, $faculty, $sec, $coz1, $status, $unit,$mode,$form_year);
                            $queryAudit->bind_param("ss", $admin, 'Add New Score for User with Matric:' .$rowData[0].' and User ID:' .$user_id);
                            if ($stmt->execute() && $queryAudit->execute()) {
                                $insertedData[] = $rowData;
                            } else {
                                $failedData[] = $rowData;
                            }
                            $stmt->close();
                            $queryAudit->close();
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


function processresult($conn,$field,$effectivedate,$external,$admin)
{

$sql = "SELECT t.matric, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code, c.unit, c.status
        FROM testscore t
        INNER JOIN course_new c ON t.cozid = c.cgpa_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external'
        ORDER BY t.matric, c.course_code, t.exam_sec"; // Ordering by matric, course_code, and exam_sec

$result = $conn->query($sql);


$sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
$queryAudit = $conn->prepare($sqlAudit);

if (($result->num_rows > 0) && $queryAudit) {
    // Initialize arrays to store data
    $courses = array();
    $matrics = array();
    $status = array();
    $unit = array();
    $tupValues = array();
    $tgpValues = array();

    $queryAudit->bind_param("ss", $admin, 'Process Result for field of Interest: '.$field.', Effective Date: '.$effectivedate .'and External Examiner:'.$external);


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

                // If there are multiple scores, conncatenate them with '/'
                if (count($courseScores) > 1) {
                    $conncatenatedScores = implode('/', $courseScores);
                    echo $conncatenatedScores;
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
// Close the database connnection
$conn->close();
}




//

/// Hammed Function Start

//



function getHod($conn,$dept){

    $sql = "SELECT title.title, designation.designation, fname, lname, initial FROM hod_cgpa INNER JOIN title ON hod_cgpa.title=title.id INNER JOIN designation ON hod_cgpa.designation=designation.id WHERE dept_new = $dept";
    $query = mysqli_query($conn,$sql);

    return $query;
    

}

function displayHod($conn,$dept){
    $sql = "SELECT title.title,designation.designation, hod_cgpa.id,hod_cgpa.status,fname, lname, initial FROM hod_cgpa INNER JOIN title ON hod_cgpa.title=title.id INNER JOIN designation ON hod_cgpa.designation=designation.id WHERE dept_new = $dept ORDER BY hod_cgpa.id DESC";
    $query = mysqli_query($conn,$sql);

    return $query;
    
}

function getTitle($conn){
    $sql = "SELECT * FROM title";
    $query = mysqli_query($conn,$sql);

    return $query;
    
}

function getDesig($conn){
    $sql = "SELECT * FROM designation";
    $query = mysqli_query($conn,$sql);

    return $query;
    
}

function getTitleforAudit($conn,$id){
    $sql = "SELECT title FROM title WHERE id = $id";
    $query = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($query);
    $title = $row['title'];

    return $title;
    
}

function getDepartmentforAudit($conn,$id){
    $sqlDept = "SELECT department FROM dept_new WHERE id = $id";
    $queryDept = mysqli_query($conn,$sqlDept);
    $row = mysqli_fetch_assoc($queryDept);
    $department = $row['department'];

    return $department;
   
}

function getDesigforAudit($conn,$id){
    $sql = "SELECT designation FROM designation WHERE id = $id";
    $query = mysqli_query($conn,$sql);

    $row = mysqli_fetch_assoc($query);
    $designation = $row['designation'];

    return $designation;
   
}

function addHod($conn,$title,$lname,$fname,$initial,$designation,$dept,$admin){

    
    $sql = "INSERT INTO hod_cgpa (title,lname,fname,initial,designation,dept_new) VALUES ('$title','$lname','$fname','$initial','$designation','$dept')";
    $query = mysqli_query($conn,$sql);

    $titleInWord = getTitleforAudit($conn,$title);
    $designationInWord = getDesigforAudit($conn,$designation);
    $departmentInWord = getDepartmentforAudit($conn,$dept);
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Hod ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord as $designationInWord')";
    
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(!($query && $queryAudit)){
        echo "Unable to Add Hod";
    } else{
        echo "<script>alert('Hod Added Successfully')</script>";
                                
    }
}

function statusHod($conn,$user_id,$status,$admin){

    if($status == 1 ){
        $flag = 0;
        $querye2 = "UPDATE hod_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn,$querye2);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Hod with ID: $user_id to Enable'";
    $queryAudit = mysqli_query($conn,$sqlAudit);

        
    
    if ($resulte2 && $queryAudit){
       
        echo "<script>alert('Hod Enabled Successfully')</script>";
        echo "<script>location.replace('createHod.php')</script>";
    } else echo mysqli_error($conn);
        
    } elseif($status == 0){
        $flag = 1;
        $querye2w = "UPDATE hod_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn,$querye2w);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Hod with ID: $user_id to Disable'";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    
    if ($resulte2w && $queryAudit){
       
        echo "<script>alert('Hod Disabled Successfully')</script>";
        echo "<script>location.replace('createHod.php')</script>";
    } else echo mysqli_error($conn);
        
        
    }

}

function updateHod($conn,$title,$lname,$fname,$initial,$designation,$user,$admin){
    $queryUp = "UPDATE hod_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',designation = '$designation' WHERE id = '$user'";
    $resultUp = mysqli_query($conn,$queryUp);

    $titleInWord = getTitleforAudit($conn,$title);
    $designationInWord = getDesigforAudit($conn,$designation);
    
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Hod with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname , initial =$initial and designation = $designationInWord)')";
    $queryAudit = mysqli_query($conn,$sqlAudit);

    if ($resultUp && $queryAudit){
       
        echo "<script>alert('Hod Updated Successfully')</script>";
        echo "<script>location.replace('createHod.php')</script>";
    } else echo mysqli_error($conn);

}

// Sub-Dean functions start

function displaySubdean($conn,$dept){
    $sql = "SELECT title.title,subdean_cgpa.id,subdean_cgpa.status,fname, lname, initial FROM subdean_cgpa INNER JOIN title ON subdean_cgpa.title=title.id WHERE dept_new = $dept ORDER BY subdean_cgpa.id DESC";
    $query = mysqli_query($conn,$sql);

    return $query;
}

function statusSubDean($conn,$user_id,$status,$admin){

    if($status == 1 ){
        $flag = 0;
        $querye2 = "UPDATE subdean_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn,$querye2);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Sub-Dean with ID: $user_id to Enable'";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    
    if ($resulte2 && $queryAudit){
       
        echo "<script>alert('Sub-Dean Enabled Successfully')</script>";
        echo "<script>location.replace('subdean.php')</script>";
    } else echo mysqli_error($conn);
        
    } elseif($status == 0){
        $flag = 1;
        $querye2w = "UPDATE subdean_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn,$querye2w);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Sub-Dean with ID: $user_id to Disable'";
        $queryAudit = mysqli_query($conn,$sqlAudit);
        
        if ($resulte2w && $queryAudit){
        echo "<script>alert('Sub-Dean Disabled Successfully')</script>";
        echo "<script>location.replace('subdean.php')</script>";
    } else echo mysqli_error($conn);
        
        
    }

}


function addSubdean($conn,$title,$lname,$fname,$initial,$dept,$admin){

    $sql = "INSERT INTO subdean_cgpa (title,lname,fname,initial,dept_new) VALUES ('$title','$lname','$fname','$initial','$dept')";
    $query = mysqli_query($conn,$sql);

    $titleInWord = getTitleforAudit($conn,$title);
    
    $departmentInWord = getDepartmentforAudit($conn,$dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Subdean ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(!($query && $queryAudit)){
        echo "Unable to Add Sub-Dean";
    } else{
        echo "<script>alert('Sub-Dean Added Successfully')</script>";
                                
    }
}

function updateSubdean($conn,$title,$lname,$fname,$initial,$user,$admin){
    $queryUp = "UPDATE subdean_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial' WHERE id = '$user'";
    $resultUp = mysqli_query($conn,$queryUp);
    $titleInWord = getTitleforAudit($conn,$title);
  
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Sub-Dean with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial)')";
    $queryAudit = mysqli_query($conn,$sqlAudit);

    if ($resultUp && $queryAudit){
       
        echo "<script>alert('Sub-Dean Updated Successfully')</script>";
        echo "<script>location.replace('subdean.php')</script>";
    } else echo mysqli_error($conn);

}


// External functions start

function displayExternal($conn,$dept){
    $sql = "SELECT title.title,external_cgpa.id,external_cgpa.status,fname, lname, initial,external_cgpa.signature FROM external_cgpa INNER JOIN title ON external_cgpa.title=title.id WHERE dept_new = $dept ORDER BY external_cgpa.id DESC";
    $query = mysqli_query($conn,$sql);

    return $query;
}

function statusExternal($conn,$user_id,$status,$admin){

    if($status == 1 ){
        $flag = 0;
        $querye2 = "UPDATE external_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn,$querye2);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of External Examiner with ID: $user_id to Enable'";
        $queryAudit = mysqli_query($conn,$sqlAudit);
        
        if ($resulte2 && $queryAudit){
       
        echo "<script>alert('External Examiner Enabled Successfully')</script>";
        echo "<script>location.replace('external.php')</script>";
    } else echo mysqli_error($conn);
        
    } elseif($status == 0){
        $flag = 1;
        $querye2w = "UPDATE external_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn,$querye2w);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of External Examiner with ID: $user_id to Disable'";
        $queryAudit = mysqli_query($conn,$sqlAudit);
        
        if ($resulte2w && $queryAudit){
       
        echo "<script>alert('External Examiner Disabled Successfully')</script>";
        echo "<script>location.replace('external.php')</script>";
    } else echo mysqli_error($conn);
        
        
    }

}


function addExternal($conn,$title,$lname,$fname,$initial,$dept,$filename,$tempname,$folder,$admin){

    $sql = "INSERT INTO external_cgpa (title,lname,fname,initial,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$dept','$filename')";
    $query = mysqli_query($conn,$sql);
    $titleInWord = getTitleforAudit($conn,$title);
   
    $departmentInWord = getDepartmentforAudit($conn,$dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New External Examiner ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord with signature: $filename')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
   

    if ((move_uploaded_file($tempname, $folder)) && $query && $queryAudit)  {
        echo "<script>alert('External Examiner Added Successfully With Signature')</script>";
        echo "<script>location.replace('external.php')</script>";
        } else {
             echo "error".mysqli_error($conn);
        }

}

function addExternalNoSign($conn,$title,$lname,$fname,$initial,$dept,$filename,$admin){

    $sql = "INSERT INTO external_cgpa (title,lname,fname,initial,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$dept','$filename')";
    $query = mysqli_query($conn,$sql);

    $titleInWord = getTitleforAudit($conn,$title);
    $departmentInWord = getDepartmentforAudit($conn,$dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New External Examiner ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord with no Signature')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(!($query && $queryAudit)){

        echo "<script>alert('External Examiner Added Successfully With No Signature')</script>";
        echo "<script>location.replace('external.php')</script>";
        } else {
             echo "error".mysqli_error($conn);
        }

}

function updateExternal($conn, $title, $lname, $fname, $initial, $user,$filename,$tempname,$folder,$admin){
    $queryUp = "UPDATE external_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',`signature` = '$filename' WHERE id = '$user'";
    $resultUp = mysqli_query($conn,$queryUp);

    $titleInWord = getTitleforAudit($conn,$title);
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update External Examiner with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial) with signature: $filename')";
    $queryAudit = mysqli_query($conn,$sqlAudit);

    if ((move_uploaded_file($tempname, $folder)) && $resultUp && $queryAudit){
       
        echo "<script>alert('External Supervisor Updated Successfully With Signature')</script>";
        echo "<script>location.replace('external.php')</script>";
    } else echo mysqli_error($conn);

}

function updateExternalNoSign($conn, $title, $lname, $fname, $initial, $user, $admin){
    $queryUp = "UPDATE external_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial' WHERE id = '$user'";
    $resultUp = mysqli_query($conn,$queryUp);
    $titleInWord = getTitleforAudit($conn,$title);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update External Examiner with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial) with no signature')";
    $queryAudit = mysqli_query($conn,$sqlAudit);

    if ($resultUp && $queryAudit){
       
        echo "<script>alert('External Supervisor Updated Successfully With No Signature')</script>";
        echo "<script>location.replace('external.php')</script>";
    } else echo mysqli_error($conn);


}

// Head ICT USERS functions start

function displayUsers($conn){
    $sql = "SELECT * FROM users_cgpa_new ORDER BY id DESC";
    $query = mysqli_query($conn,$sql);

    return $query;

}

function statusUsers($conn,$user_id,$status,$admin){

    if($status == 1 ){
        $flag = 0;
        $querye2 = "UPDATE users_cgpa_new SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn,$querye2);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of User with ID: $user_id to Enable'";
        $queryAudit = mysqli_query($conn,$sqlAudit);
        
        if ($resulte2 && $queryAudit){
    
       
        echo "<script>alert('Users Enabled Successfully')</script>";
        echo "<script>location.replace('headict.php')</script>";
    } else echo mysqli_error($conn);
        
    } elseif($status == 0){
        $flag = 1;
        $querye2w = "UPDATE users_cgpa_new SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn,$querye2w);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of User with ID: $user_id to Disable'";
        $queryAudit = mysqli_query($conn,$sqlAudit);
        
        if ($resulte2w && $queryAudit){
        echo "<script>alert('Users Disabled Successfully')</script>";
        echo "<script>location.replace('headict.php')</script>";
    } else echo mysqli_error($conn);
        
        
    }

}

function getUsersDepartment($conn,$dept_id){
    $sql = "SELECT department FROM dept_new WHERE id = $dept_id";
    $query = mysqli_query($conn,$sql);
    if(mysqli_num_rows($query)>0){
        $row = mysqli_fetch_assoc($query);
    $dept = $row['department'];

    
    } else{
        $dept = "<span style='color:#dc3545;'>No Department for this User</span>";
    }

    return $dept;

    
}

function getDepartment($conn){
    $sql = "SELECT * FROM dept_new";
    $query = mysqli_query($conn,$sql);

    return $query;

}

function addUser($conn, $name, $username, $password, $department, $admin){

    $sql = "INSERT INTO users_cgpa_new (name,username,password,dept_new) VALUES ('$name','$username','$password','$department')";
    $query = mysqli_query($conn,$sql);

    //Get Department Title for the Department ID

    $departmentInWord = getDepartmentforAudit($conn,$department);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New User (Name:$name, Username:$username, Password:$password, Department:$departmentInWord)')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(!($query && $queryAudit)){
        echo "Unable to Add User";
    } else{
        echo "<script>alert('User Added Successfully')</script>";
                                
    }
}

function updateUser($conn, $name, $username, $password, $department,$id,$admin){
    $queryUp = "UPDATE users_cgpa_new SET name = '$name',username = '$username',password = '$password',dept_new = '$department' WHERE id = '$id'";
    $resultUp = mysqli_query($conn,$queryUp);

    //Get Department Title for the Department ID
    $departmentInWord = getDepartmentforAudit($conn,$department);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update User with ID:$id and set (Name:$name, Username:$username, Password:$password, Department:$departmentInWord)')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(($resultUp && $queryAudit)){
       
        echo "<script>alert('User Updated Successfully')</script>";
        echo "<script>location.replace('headict.php')</script>";
    } else echo mysqli_error($conn);

}

function displaySection($conn){
    $sql = "SELECT * FROM sec_examined ORDER BY id DESC";
    $query = mysqli_query($conn,$sql);

    return $query;

}

function addSection($conn, $section, $admin){

    $sql = "INSERT INTO sec_examined (sec) VALUES ('$section')";
    $query = mysqli_query($conn,$sql);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Section ($section)')";
    $queryAudit = mysqli_query($conn,$sqlAudit);
    if(!($query && $queryAudit)){
        echo "Unable to Add Section";
    } else{
        echo "<script>alert('Section Added Successfully')</script>";
                                
    }
}

?>