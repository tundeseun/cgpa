<?php
ob_start(); // Start output buffering
include('connect.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

use function PHPSTORM_META\type;

//

/// Mrs Adeyemi Function Start

//

function assignExternalAndEffectiveDate($conn, $excelFile, $effective, $approval, $external, $admin)
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
                $query = "UPDATE testscore SET effectivedate = '$effective', external ='$external',approval ='$approval' WHERE matric = '$record' AND status = 0";
                $result = mysqli_query($conn, $query);

                $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update User with Matric:$record and set (Effective date:$effective, External Examiner:$external)')";
                $queryAudit = mysqli_query($conn, $sqlAudit);

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

        echo "<button type='submit' class='btn danger'><a href='../dashboard.php?p=assign'>Return to Form</a></button>";

        //     foreach ($matricArray as $mat) {




        //         $sql = "SELECT t.matric,t.dept, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code, c.unit, c.status,p.type as program
        //            FROM testscore t LEFT JOIN course_new c ON t.cozid = c.cgpa_id LEFT JOIN programme p ON t.degree = p.degree_id where t.matric='$mat'"; // Ordering by matric, course_code, and exam_sec





        //         $result = $conn->query($sql);

        //     //     if (($result->num_rows > 0)) {
        //     //         // Initialize arrays to store data
        //     //         $courses = array();
        //     //         $matrics = array();
        //     //         $status = array();
        //     //         $unit = array();
        //     //         $tupValues = array();
        //     //         $tgpValues = array();
        //     //         $coretopass = array();
        //     //         $corepassed = array();
        //     //         $unittopass = array();
        //     //         $requiredpassed = array();
        //     //         $totalCoreCoursesPassed = 0;

        //     //         //$queryAudit->bind_param("ss", $admin, 'Process Result for field of Interest: '.$field.', Effective Date: '.$effectivedate .'and External Examiner:'.$external);


        //     //         // Iterate through the results and organize data
        //     //         while ($row = $result->fetch_assoc()) {
        //     //             $matric = $row["matric"];
        //     //             $courseCode = $row["course_code"];
        //     //             $score = $row["score"];
        //     //             $dept = $row["dept"];
        //     //             $field = $row["field"];
        //     //             $courseUnit = $row["unit"];
        //     //             $courseStatus = $row["status"];
        //     //             $examSec = $row["exam_sec"];
        //     //             $program = $row["program"];


        //     //             $coz = mysqli_query($conn, "select sum(unit) as core from course_new where dept_newids='$dept' and specialization='$field' and status2='0' and status='C' order by course_code") or die(mysqli_error($conn));
        //     //             $row = mysqli_fetch_array($coz);
        //     //             $tctp = $row['core'];

        //     //             $coz1 = mysqli_query($conn, "select sum(unit) as required from course_new where dept_newids='$dept' and specialization='$field' and status2='0' and status='R' order by course_code") or die(mysqli_error($conn));
        //     //             $row1 = mysqli_fetch_array($coz1);
        //     //             $trtp = $row1['required'];

        //     //             // Store course codes as keys and scores as values in the courses array
        //     //             if (!isset($courses[$courseCode][$matric])) {
        //     //                 $courses[$courseCode][$matric] = array();
        //     //             }

        //     //             // Store unique matric numbers
        //     //             if (!in_array($matric, $matrics)) {
        //     //                 $matrics[] = $matric;
        //     //             }

        //     //             // Store status for each course
        //     //             $status[$courseCode] = $courseStatus;

        //     //             // Store units for each course code
        //     //             if (!isset($unit[$courseCode])) {
        //     //                 $unit[$courseCode] = $courseUnit;
        //     //             }

        //     //             // Store units for TUT calculation
        //     //             if (!isset($unit[$matric])) {
        //     //                 $unit[$matric] = 0;
        //     //             }
        //     //             $unit[$matric] += $courseUnit;

        //     //             // Calculate TUP values based on different status and score criteria
        //     //             if (($courseStatus === 'C' && $score > 39) || ($score > 29 && ($courseStatus === 'R' || $courseStatus === 'E' || $courseStatus === 'EE'))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'
        //     //                 if (!isset($tupValues[$matric])) {
        //     //                     $tupValues[$matric] = 0;
        //     //                 }
        //     //                 $tupValues[$matric] += $courseUnit;
        //     //             }
        //     //             if (($courseStatus === 'C')) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'
        //     //                 if (!isset($coretopass[$matric])) {
        //     //                     $coretopass[$matric] = 0;
        //     //                 }
        //     //                 $coretopass[$matric] += $courseUnit;
        //     //             }
        //     //             if (($courseStatus == 'C') && ($score >= 40)) {  //($score >= 40) AND //|| $courseStatus === 'E' || $courseStatus === 'EE'
        //     //                 if (!isset($corepassed[$matric])) {
        //     //                     $corepassed[$matric] = 0;
        //     //                 }
        //     //                 $corepassed[$matric] += $courseUnit;
        //     //             }

        //     //             if (($courseStatus == 'R') && ($score >= 30)) {  //($score >= 40) AND //|| $courseStatus === 'E' || $courseStatus === 'EE'
        //     //                 if (!isset($requiredpassed[$matric])) {
        //     //                     $requiredpassed[$matric] = 0;
        //     //                 }
        //     //                 $requiredpassed[$matric] += $courseUnit;
        //     //             }

        //     //             if ($status[$courseCode] === 'C' and $score >= 40) {
        //     //                 $totalCoreCoursesPassed++;
        //     //             }


        //     //             // Calculate point per score for each course_code
        //     //             if ($score <= 39) {
        //     //                 $point = 0;
        //     //             } elseif ($score >= 40 && $score < 45) {
        //     //                 $point = 1;
        //     //             } elseif ($score >= 45 && $score < 50) {
        //     //                 $point = 2;
        //     //             } elseif ($score >= 50 && $score < 55) {
        //     //                 $point = 3;
        //     //             } elseif ($score >= 55 && $score < 60) {
        //     //                 $point = 4;
        //     //             } elseif ($score >= 60 && $score < 65) {
        //     //                 $point = 5;
        //     //             } elseif ($score >= 65 && $score < 70) {
        //     //                 $point = 6;
        //     //             } elseif ($score >= 70 && $score < 101) {
        //     //                 $point = 7;
        //     //             }

        //     //             // Calculate GP (point per course_code multiplied by unit per course_code)
        //     //             if (!isset($tgpValues[$matric][$courseCode])) {
        //     //                 $tgpValues[$matric][$courseCode] = 0;
        //     //             }
        //     //             $tgpValues[$matric][$courseCode] += $point * $courseUnit;

        //     //             // Store scores for the same course and matric
        //     //             $courses[$courseCode][$matric][$examSec] = $score;
        //     //         }


        //     //         // Initialize counters for each remark category
        //     //         $totalPhD = 0;
        //     //         $totalMPhilPhD = 0;
        //     //         $totalMPhil = 0;
        //     //         $totalTM = 0;
        //     //         $totalNG = 0;
        //     //         $totalProf = 0;
        //     //         $totalUnknown = 0;
        //     //         $serialNumber = 1;
        //     //         // Output data rows for each matric number
        //     //         foreach ($matrics as $matric) {




        //     //             $tgpSum = 0;
        //     //             foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
        //     //                 $tgpSum += $tgp;
        //     //             }

        //     //             $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';

        //     //             //&& ($tupValues[$matric] >= $ttp)
        //     //             if (($program == 'Academics') && ($tupValues[$matric] > 29)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
        //     //                 if ($cgpa < 9.0 && $cgpa >= 5.0) {
        //     //                     $remark = "Ph.D";
        //     //                     $result = "PASS";
        //     //                     $totalPhD++;
        //     //                 } elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
        //     //                     $remark = "M.Phil/Ph.D";
        //     //                     $result = "PASS";
        //     //                     $totalMPhilPhD++;
        //     //                 } elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
        //     //                     $remark = "M.Phil";
        //     //                     $result = "PASS";
        //     //                     $totalMPhil++;
        //     //                 } elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
        //     //                     $remark = "TM";
        //     //                     $result = "PASS";
        //     //                     $totalTM++;
        //     //                 }
        //     //             }
        //     //             // elseif (($program=='Academics') && ($tupValues[$matric] < 30)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {
        //     //             //     $remark = "NG";
        //     //             //     $result = "-";
        //     //             //     $cgpa = "-";
        //     //             //     $totalNG++;
        //     //             // } 

        //     //             elseif (($program == 'Academics') && (($tupValues[$matric] < 30)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
        //     //                 $remark = "NG";
        //     //                 $result = "-";
        //     //                 $cgpa = "-";
        //     //                 $totalNG++;
        //     //             } elseif (($program == 'Professional') && ($tupValues[$matric] > 44)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {

        //     //                 $remark = "PASS";
        //     //                 $result = "PASS";
        //     //                 $totalProf++;
        //     //             }
        //     //             //     elseif(($program=='Professional') && ($tupValues[$matric] < 45)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {

        //     //             //         $remark = "NG";
        //     //             //         $result = "-";
        //     //             //         $totalProf++;

        //     //             // }

        //     //             elseif (($program == 'Professional') && (($tupValues[$matric] < 45)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {

        //     //                 $remark = "NG";
        //     //                 $result = "-";
        //     //                 $totalProf++;
        //     //             } else {
        //     //                 $remark = "-";
        //     //                 $result = "-";
        //     //                 $totalUnknown++;
        //     //             }


        //     //         }
        //     //     } else {
        //     //         echo "0 results";
        //     //     }
        //     // }
        //     // Close the database connnection
        //     $conn->close();
        // }

    }
}


function getDataFromTestScore($conn, $matric)
{
    $query = "SELECT new.Surname, new.Other_names, testscore.effectivedate, testscore.external, external_cgpa.lname, external_cgpa.fname, external_cgpa.initial FROM testscore INNER JOIN new ON testscore.user_id = new.id LEFT JOIN external_cgpa ON testscore.external = external_cgpa.id WHERE testscore.matric = '$matric'";
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

function showcourse($conn, $dept)
{
    // SELECT course_code, MAX(cgpa_id) as cgpa_id, MAX(status) as status, MAX(unit) as unit
    // FROM course_new
    // WHERE dept_newids = '$dept'
    // GROUP BY course_code
    // ORDER BY course_code;

    $select = mysqli_query($conn, "select DISTINCT course_code,id AS cgpa_id,status,unit from course_new where dept_newids='$dept' order by course_code") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select)) {
        $id = $row['cgpa_id'];
        $course_code = $row['course_code'];
        $status = $row['status'];
        $unit = $row['unit'];

        //  echo "<option value=''></option>";
        echo " <option value=" . $id . "," . $status . "," . $unit . " >";
        echo $course_code . "( " . $status . " )";
    }
    echo "</option>";
}

function showcourse2($conn, $dept)
{
    // SELECT course_code, MAX(cgpa_id) as cgpa_id, MAX(status) as status, MAX(unit) as unit
    // FROM course_new
    // WHERE dept_newids = '$dept'
    // GROUP BY course_code
    // ORDER BY course_code;

    $sel = mysqli_query($conn, "SELECT DISTINCT field  FROM `fieldofinterest5` WHERE dept='$dept'") or die(mysqli_error($conn));
    while ($row2 = mysqli_fetch_array($sel)) {
        $field = $row2['field'];
        //echo $field."\n";
        $select = mysqli_query($conn, "SELECT DISTINCT course_code  FROM `course_new` WHERE `course_code` LIKE '% %' and dept_newids='$dept' and specialization='$field' AND status2=0
    ORDER BY `course_new`.`course_code` ASC") or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($select))
        //select DISTINCT course_code from course_new where course_code LIKE '% %' and dept_newids='$dept' and status2='0' order by course_code
        //     SELECT DISTINCT course_code  FROM `course_new` WHERE `course_code` LIKE '% %' and dept_newids='6'  
        // ORDER BY `course_new`.`course_code` ASC
        {
            //$id=$row['cgpa_id'];
            $course_code = $row['course_code'];
            //   $status=$row['status'];
            //   $unit=$row['unit'];

            //  echo "<option value=''></option>";
            //$result = str_replace(' ', '&nbsp;', $stringVariable);
            echo " <option value=" . urlencode($course_code) . " >";   //,".$status.",".$unit."
            echo $course_code;
        }    //."( ".$status." )"
        echo "</option>";
    }
}



function showfield($conn, $dept)
{
    $query = "select DISTINCT field FROM fieldofinterest5 where dept='$dept' order by field ";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    echo "<option value= >";
    while ($line = mysqli_fetch_assoc($result)) {
        $key = array_keys($line);
        $se = mysqli_query($conn, "select id,field_title from field_new where id=" . $line['field'] . "") or die(mysqli_error($conn)); //where id=".$line[$key[0]]."
        while ($line2 = mysqli_fetch_assoc($se)) {
            $key = array_keys($line);
            $field = $line2['field_title'];
            $iddegree = $line2['id'];
        }
        echo "<option value=" . $iddegree . ">" . $field . "</b></option>";
    }
}

function uploadToResolve($excelFile, $conn, $dept)
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

            $checkQuery1 = "select studentrecord.matric,studentrecord.name,field_new.field_title AS field,studentrecord.status,studentrecord.specialization from studentrecord INNER JOIN field_new ON field_new.id=studentrecord.specialization where studentrecord.matric = ? AND studentrecord.DEPT = ?";
            $checkStmt1 = $conn->prepare($checkQuery1);

            if (!$checkStmt1) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $checkStmt1->bind_param("ss", $rowData[0], $dept);
                $checkStmt1->execute();
                $checkStmt1->bind_result($matric, $name, $field, $status, $specialization);
                $checkStmt1->fetch();
                $checkStmt1->close();




                echo "<tr class='data'>
                                             <td>" . $matric . "</td>
                                             <td>" . $name . "</td>
                                             <td id='responseDiv' class='responseDiv'>" . $field . "</td>
                                             <td > ";

                $fieldvalue = mysqli_query($conn, "select DISTINCT field_new.field_title,fieldofinterest5.field from fieldofinterest5 INNER JOIN field_new on fieldofinterest5.field=field_new.id where fieldofinterest5.dept='$dept' ") or die(mysqli_error($con));
                echo  "<select name='field' id='field' class='sinput field'>
                                                <option value= selected>Select</option>";

                while ($selfield = mysqli_fetch_array($fieldvalue))
                //  //print_r($record);
                {
                    echo " <option value=" . $selfield['field'] . ">" . $selfield['field_title'] . "</option>";
                }
                echo " </select>
                                                </td>";

                echo "<td><input type='hidden' name='matric' class='matric' id='matric' value=" . $matric . " /></td>";


                echo "</tr>";
            }
        }
    } else {
        echo "File upload failed!";
    }
}

function uploadToResolveN($excelFile, $conn, $dept)
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

            $checkQuery1 = "select prev_app.matric,new.Surname,new.Other_names,field_new.field_title AS field from zmain_app INNER JOIN field_new ON field_new.id=zmain_app.field_of_interest INNER JOIN new ON new.id=zmain_app.user_id INNER JOIN prev_app ON prev_app.user_id=zmain_app.user_id where prev_app.matric = ? AND zmain_app.department = ?";
            $checkStmt1 = $conn->prepare($checkQuery1);

            if (!$checkStmt1) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $checkStmt1->bind_param("ss", $rowData[0], $dept);
                $checkStmt1->execute();
                $checkStmt1->bind_result($matric, $snmae, $oname, $field);
                $checkStmt1->fetch();
                $checkStmt1->close();


                $name = $snmae . ' ' . $oname;


                echo "<tr class='data'>
                                             <td>" . $matric . "</td>
                                             <td>" . $name . "</td>
                                             <td id='responseDiv' class='responseDiv'>" . $field . "</td>
                                             <td > ";

                $fieldvalue = mysqli_query($conn, "select DISTINCT field_new.field_title,fieldofinterest5.field from fieldofinterest5 INNER JOIN field_new on fieldofinterest5.field=field_new.id where fieldofinterest5.dept='$dept' ") or die(mysqli_error($con));
                echo  "<select name='field' id='field' class='sinput field'>
                                                <option value= selected>Select</option>";

                while ($selfield = mysqli_fetch_array($fieldvalue))
                //  //print_r($record);
                {
                    echo " <option value=" . $selfield['field'] . ">" . $selfield['field_title'] . "</option>";
                }
                echo " </select>
                                                </td>";

                echo "<td><input type='hidden' name='matric' class='matric' id='matric' value=" . $matric . " /></td>";


                echo "</tr>";
            }
        }
    } else {
        echo "File upload failed!";
    }
}

function showprogramme($conn, $dept)
{

    $select = mysqli_query($conn, "select DISTINCT degree_new.degree, fieldofinterest5.degree as degreeid FROM fieldofinterest5 inner join degree_new on degree_new.id=fieldofinterest5.degree where fieldofinterest5.dept='$dept' order by degree") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select)) {
        $degreeid = $row['degreeid'];
        $degree = $row['degree'];

        echo "<option value=''></option>";
        echo " <option value=" . $degreeid . " >";
        echo $degree;
    }
    echo "</option>";
}
function showsessionexamined($conn)
{

    $select = mysqli_query($conn, "select id,sec from sec_examined") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select)) {
        $id = $row['id'];
        $sec = $row['sec'];

        echo "<option value=''></option>";
        echo " <option value=" . $id . " >";
        echo $sec;
    }
    echo "</option>";
}

function showsessionexamined2($conn)
{

    $select = mysqli_query($conn, "select id,sec from sec_examined") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($select)) {
        $id = $row['id'];
        $sec = $row['sec'];

        echo "<option value=''></option>";
        echo " <option value=" . $sec . " >";
        echo $sec;
    }
    echo "</option>";
}


function showdept($dept, $conn)
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
function checkuser($username, $password, $conn)
{

    $checkQuery = "SELECT COUNT(*),dept_new,name FROM users_cgpa_new WHERE username = ? AND password = ? AND status = 0";
    $checkStmt = $conn->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $username, $password);
        $checkStmt->execute();
        $checkStmt->bind_result($count, $dept_new, $name);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the prepared statement

        if ($count > 0) {


            echo "<script>";
            echo "let dept_new = '" . $dept_new . "';";
            echo "let name = '" . $name . "';";
            echo "let username = '" . $username . "';";
            echo "Swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: 'Welcome, ' + name + '!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'dashboard.php?p=1&dept_new=' + dept_new + '&name=' + name + '&user=' + username;
                    });";
            echo "</script>";
            // header('Location: dashboard.php?p=1&dept_new=' . $dept_new . '&name=' . $name . '&user=' . $username);
            // ob_end_flush(); // Flush the output buffer and send the headers
            // exit;
        } else {

            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Incorrect Username or Password / Invalid User'
                    });
                </script>";
        }
    }
}
function checkHeadIct($username, $password, $conn)
{

    $checkQuery = "SELECT COUNT(*),name,username FROM users_cgpa_new WHERE username = ? AND password = ?";
    $checkStmt = $conn->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $username, $password);
        $checkStmt->execute();
        $checkStmt->bind_result($count, $name, $user);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the prepared statement

        if ($count > 0) {

            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome, $name!',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                window.location.href = 'dashboard.php?p=head&user=$user';
            });
        </script>";
        } else {

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect Username or Password / Invalid User'
                });
            </script>";
        }
    }
}
function checkExams($username, $password, $conn)
{

    $checkQuery = "SELECT COUNT(*),name,username FROM users_cgpa_new WHERE username = ? AND password = ?";
    $checkStmt = $conn->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $username, $password);
        $checkStmt->execute();
        $checkStmt->bind_result($count, $name, $user);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the prepared statement

        if ($count > 0) {

            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome, $name!',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                window.location.href = 'dashboard.php?p=exam&user=$user';
            });
        </script>";
        } else {

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect Username or Password / Invalid User'
                });
            </script>";
        }
    }
}
function checkBCM($username, $password, $conn)
{

    $checkQuery = "SELECT COUNT(*),name,username FROM users_cgpa_new WHERE username = ? AND password = ?";
    $checkStmt = $conn->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $username, $password);
        $checkStmt->execute();
        $checkStmt->bind_result($count, $name, $user);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the prepared statement

        if ($count > 0) {

            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome, $name!',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                window.location.href = 'dashboard.php?p=bcm&user=$user';
            });
        </script>";
        } else {

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect Username or Password / Invalid User'
                });
            </script>";
        }
    }
}
function checkFacUser($username, $password, $conn)
{

    $checkQuery = "SELECT COUNT(*),name,username,faculty_id FROM fac_users WHERE username = ? AND password = ?";
    $checkStmt = $conn->prepare($checkQuery);

    if ($checkStmt) {
        $checkStmt->bind_param("ss", $username, $password);
        $checkStmt->execute();
        $checkStmt->bind_result($count, $name, $user, $facId);
        $checkStmt->fetch();
        $checkStmt->close(); // Close the prepared statement

        if ($count > 0) {

            echo "<script>";
            echo "let facId = '" . $facId . "';";
            echo "let username = '" . $username . "';";
            echo "Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: 'Welcome, $name!',
                timer: 2000,
                showConfirmButton: false
            }).then(function() {
                window.location.href = 'dashboard.php?p=deen&faculty_id=' + facId + '&user=' + username;
            });
        </script>";
        } else {

            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Incorrect Username or Password / Invalid User'
                });
            </script>";
        }
    }
}
// Function to upload and process an Excel file to MySQL
function uploadExcelToMySQL($excelFile, $conn, $sec, $coz, $admin)
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
                        $checkStmt->bind_param("ssss", $rowData[0], $rowData[1], $department, $sec);
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
                            // $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
                            // $queryAudit = $conn->prepare($sqlAudit);
                            //&& $queryAudit
                            if (!($stmt)) {
                                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                            } else {
                                $adminvalue = 'Add New Score for User with Matric:' . $rowData[0] . ' and User ID:' . $user_id;
                                $stmt->bind_param("sssssssssssss", $rowData[0], $rowData[1], $degree, $department, $user_id, $field_of_interest, $faculty, $sec, $coz1, $status, $unit, $mode, $form_year);
                                // $queryAudit->bind_param("ss", $admin, $adminvalue);
                                if ($stmt->execute()) {   //&& $queryAudit->execute()
                                    $insertedData[] = $rowData;
                                } else {
                                    $failedData[] = $rowData;
                                }
                                $stmt->close();
                                // $queryAudit->close();
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
        } else {
            echo "No failed data to display.";
        }
    } else {
        echo "File upload failed!";
    }
    echo "</div>";
}


function uploadscorewithoutspecialization($excelFile, $conn, $sec, $coz, $admin)
{
    $coz2 = urldecode($coz);
    echo $coz2 . "<br>";

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



            // Fetch data from zmain_app and prev_app tables
            $checkQuery1 = "SELECT prev_app.matric, zmain_app.faculty, zmain_app.department, zmain_app.user_id, zmain_app.field_of_interest, zmain_app.mode_of_study, zmain_app.degree 
                            FROM zmain_app 
                            INNER JOIN prev_app ON prev_app.user_id = zmain_app.user_id 
                            WHERE zmain_app.degree <> '3' AND zmain_app.degree <> '4' AND zmain_app.degree <> '5' 
                            AND prev_app.matric = ?";
            $checkStmt1 = $conn->prepare($checkQuery1);

            if (!$checkStmt1) {
                echo "Prepare failed (checkQuery1): (" . $conn->errno . ") " . $conn->error;
                continue;
            }

            $checkStmt1->bind_param("s", $rowData[0]);
            $checkStmt1->execute();
            $checkStmt1->bind_result($matric, $faculty, $department, $user_id, $field_of_interest, $mode, $degree);
            if (!$checkStmt1->fetch()) {
                echo "No results for checkQuery1 with matric: " . $rowData[0] . "<br>";
                $checkStmt1->close();
                continue;
            }
            $checkStmt1->close();

            $getField = "SELECT field_title
                              FROM field_new 
                              WHERE id = $field_of_interest";
            $getFieldQuery = mysqli_query($conn, $getField);
            $rowFieldQuery = mysqli_fetch_assoc($getFieldQuery);
            $fieldTitle = $rowFieldQuery['field_title'];


            // Fetch data from course_new table
            $checkQuerycoz = "SELECT id AS cgpa_id, status, unit, corder 
                              FROM course_new 
                              WHERE course_code = ? AND specialization = ?";
            $checkStmtcoz = $conn->prepare($checkQuerycoz);

            if (!$checkStmtcoz) {
                echo "Prepare failed (checkQuerycoz): (" . $conn->errno . ") " . $conn->error;
                continue;
            }

            $checkStmtcoz->bind_param("ss", $coz2, $field_of_interest);
            $checkStmtcoz->execute();
            $checkStmtcoz->bind_result($cgpa_id, $status, $unit, $corder);
            if (!$checkStmtcoz->fetch()) {

                echo "No Course Code: " . $coz2 . " Attached to Specialization: " . $fieldTitle . "<br>";
                $checkStmtcoz->close();
                continue;
            }
            $checkStmtcoz->close();

            // Fetch data from form table
            $checkQuery3 = "SELECT form_year 
                            FROM form 
                            WHERE user_id = ?";
            $checkStmt3 = $conn->prepare($checkQuery3);

            if (!$checkStmt3) {
                echo "Prepare failed (checkQuery3): (" . $conn->errno . ") " . $conn->error;
                continue;
            }

            $checkStmt3->bind_param("s", $user_id);
            $checkStmt3->execute();
            $checkStmt3->bind_result($form_year);
            if (!$checkStmt3->fetch()) {
                echo "No results for checkQuery3 with user_id: " . $user_id . "<br>";
                $checkStmt3->close();
                continue;
            }
            $checkStmt3->close();

            // Check for existing record in testscore table
            $checkQuery = "SELECT COUNT(*) 
                           FROM testscore 
                           WHERE matric = ? AND score = ? AND cozid = ? AND dept = ? AND exam_sec = ?";
            $checkStmt = $conn->prepare($checkQuery);

            if (!$checkStmt) {
                echo "Prepare failed (checkQuery): (" . $conn->errno . ") " . $conn->error;
                continue;
            }

            $checkStmt->bind_param("sssss", $rowData[0], $rowData[1], $cgpa_id, $department, $sec);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($count == 0) {
                $query = "INSERT INTO testscore (matric, score, degree, dept, user_id, field, fac, exam_sec, cozid, cstatus, cunit, mode, yr_of_entry) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                if (!($stmt)) {
                    echo "Prepare failed (insertQuery): (" . $conn->errno . ") " . $conn->error;
                    continue;
                }

                $adminvalue = 'Add New Score for User with Matric:' . $rowData[0] . ' and User ID:' . $user_id;
                $stmt->bind_param("sssssssssssss", $rowData[0], $rowData[1], $degree, $department, $user_id, $field_of_interest, $faculty, $sec, $cgpa_id, $status, $unit, $mode, $form_year);

                if ($stmt->execute()) {
                    $insertedData[] = $rowData;
                } else {
                    echo "Failed to insert record for matric: " . $rowData[0] . "<br>";
                    $failedData[] = $rowData;
                }
                $stmt->close();
            } else {
                echo "Record already exists for matric: " . $rowData[0] . "<br>";
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
        } else {
            echo "No failed data to display.";
        }
    } else {
        echo "File upload failed!";
    }
    echo "</div>";
}






function chkstudentrecord($excelFile, $conn, $admin, $token)
{
    //     $coz2=urldecode($coz);
    //  echo $coz2."<br>";
    $status = "";
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


                $checkQuerycoz = "SELECT Surname,Other_names  from new WHERE id = ?";
                $checkStmtcoz = $conn->prepare($checkQuerycoz);  // course_code =? AND    

                if (!$checkStmtcoz) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                } else {
                    $checkStmtcoz->bind_param("s", $user_id);  //$coz,  
                    $checkStmtcoz->execute();
                    $checkStmtcoz->bind_result($Surname, $Other_names);
                    $checkStmtcoz->fetch();
                    $checkStmtcoz->close();
                    $name = $Surname . " " . $Other_names;
                    //echo $cgpa_id."<br>";echo $status."<br>"; echo $unit."<br>";

                    $checkQuery3 = "SELECT field  FROM fieldofinterest5 WHERE dept=? AND field = ? ";
                    $checkStmt3 = $conn->prepare($checkQuery3);

                    if (!$checkStmt3) {
                        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                    } else {
                        $checkStmt3->bind_param("ss", $department, $field_of_interest);
                        $checkStmt3->execute();
                        $checkStmt3->bind_result($field5);
                        $checkStmt3->fetch();
                        $checkStmt3->close();


                        $checkQuery = "SELECT COUNT(*) FROM studentrecord WHERE matric = ? AND dept = ? AND specialization= ? ";
                        $checkStmt = $conn->prepare($checkQuery);

                        if (!$checkStmt) {
                            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                        } else {
                            $checkStmt->bind_param("sss", $rowData[0], $department, $field_of_interest);
                            $checkStmt->execute();
                            $checkStmt->bind_result($count);
                            $checkStmt->fetch();
                            $checkStmt->close();

                            if ($count == 0) {
                                echo $field_of_interest . "<br/>";

                                if ($field_of_interest == $field5) {
                                    $status = "PASS";
                                }
                                if ($field_of_interest <> $field5) {
                                    $status = "FAIL";
                                }
                                // $split = explode(",", $coz);
                                // $coz1 = $split[0];
                                // $status = $split[1];
                                // $unit = $split[2];
                                $query = "INSERT INTO studentrecord (matric, name , dept,specialization,status,code,user_id,specialization2) VALUES ( ?, ?, ?, ?, ?, ?, ?,?)";
                                $stmt = $conn->prepare($query);

                                //Audit Trail Query Start Here
                                // $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
                                // $queryAudit = $conn->prepare($sqlAudit);
                                //&& $queryAudit
                                if (!$stmt) {
                                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                                } else {
                                    // $adminvalue = 'Add New Score for User with Matric:' . $rowData[0] . ' and User ID:' . $user_id;
                                    $stmt->bind_param("ssssssss", $rowData[0], $name, $department, $field_of_interest, $status, $token, $user_id, $field5);
                                    // $queryAudit->bind_param("ss", $admin, $adminvalue);
                                    if ($stmt->execute()) {   //&& $queryAudit->execute()
                                        // $insertedData[] = $rowData;
                                        // array_push( $insertedData,$name,$department, $field_of_interest, $status);
                                        $insertedData = array(
                                            'matric' => $rowData[0],
                                            'name' => $name,
                                            'dept' => $department,
                                            'specialization' => $field_of_interest,
                                            'status' => $status,
                                            'user_id' => $user_id,
                                            'speciallization2' => $field5
                                        );
                                    } else {
                                        // $failedData[] = $rowData;
                                        // array_push( $failedData,$name,$department, $field_of_interest, $status);
                                        $failedData = array(
                                            'matric' => $rowData[0],
                                            'name' => $name,
                                            'dept' => $department,
                                            'specialization' => $field_of_interest,
                                            'status' => $status,
                                            'user_id' => $user_id,
                                            'speciallization2' => $field5
                                        );
                                    }
                                    $stmt->close();

                                    if (isset($insertedData)) {
                                        header('location: ../dashboard.php?p=lsr&token=' . $token);
                                    }
                                }
                            } elseif ($count >= 1) {
                                $queryE = "UPDATE studentrecord SET code = ? WHERE matric = ?";
                                $stmtE = $conn->prepare($queryE);
                                $stmtE->bind_param("ss", $token, $rowData[0]);
                                if ($stmtE->execute()) {
                                    $updatedData = array(
                                        'matric' => $rowData[0],
                                        'name' => $name,
                                        'dept' => $department,
                                        'specialization' => $field_of_interest,
                                        'status' => $status,
                                        'user_id' => $user_id,
                                        'speciallization2' => $field5
                                    );
                                }

                                $stmtE->close();

                                if (isset($updatedData)) {
                                    header('location: ../dashboard.php?p=lsr&token=' . $token);
                                }
                            }
                        }
                    }
                }
            }
        }
        // if ($count >= 1) {
        //     // echo '<script type="text/javascript">'
        //     // . '$( document ).ready(function() {'
        //     // . '$("#addHod").modal("show");'
        //     // . '});'
        //     // . '</script>';
        //     echo "<script> alert('Result for these students has been processed. Kindly Check Report.');</script>";
        // }
    }

    //if (!empty($insertedData)){
    // echo"<h2>Records Inserted Successfully</h2>";
    // Count the number of records in the array
    // $recordCount = count($insertedData, COUNT_RECURSIVE);

    // Display the count
    //echo "Number of records: $recordCount";
    //}

}





function updateuserid($excelFile, $conn, $admin, $token)
{
    //     $coz2=urldecode($coz);
    //  echo $coz2."<br>";
    $status = "";
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

            $checkQuery1 = "UPDATE zmain_app set field_of_interest='1' where user_id= ?";
            $checkStmt1 = $conn->prepare($checkQuery1);

            if (!$checkStmt1) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            } else {
                $checkStmt1->bind_param("s", $rowData[0]);
                $checkStmt1->execute();
                // $checkStmt1->bind_result($matric, $faculty, $department, $user_id, $field_of_interest, $mode, $degree);
                // $checkStmt1->fetch();
                $checkStmt1->close();


                // $stmt->close();


            }
        }
    }
}







function checkstudentdetails($excelFile, $conn, $sec, $admin)
{
    //     $coz2=urldecode($coz);
    //  echo $coz2."<br>";

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
                $checkStmt1->bind_result($matric, $department, $user_id, $field_of_interest);
                $checkStmt1->fetch();
                $checkStmt1->close();


                $checkQuerycoz = "SELECT id AS cgpa_id,status,unit from course_new WHERE course_code = ? AND  specialization = ?";
                $checkStmtcoz = $conn->prepare($checkQuerycoz);  // course_code =? AND    

                if (!$checkStmtcoz) {
                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                } else {
                    $checkStmtcoz->bind_param("ss", $coz2, $field_of_interest);  //$coz,  
                    $checkStmtcoz->execute();
                    $checkStmtcoz->bind_result($cgpa_id, $status, $unit);
                    $checkStmtcoz->fetch();
                    $checkStmtcoz->close();
                    echo $cgpa_id . "<br>";
                    echo $status . "<br>";
                    echo $unit . "<br>";

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
                            $checkStmt->bind_param("ssss", $rowData[0], $rowData[1], $department, $sec);
                            $checkStmt->execute();
                            $checkStmt->bind_result($count);
                            $checkStmt->fetch();
                            $checkStmt->close();

                            if ($count == 0) {
                                // $split = explode(",", $coz);
                                // $coz1 = $split[0];
                                // $status = $split[1];
                                // $unit = $split[2];
                                $query = "INSERT INTO testscore (matric, score,degree, dept, user_id, field, fac, exam_sec, cozid, cstatus, cunit,mode,yr_of_entry) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt = $conn->prepare($query);

                                //Audit Trail Query Start Here
                                // $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
                                // $queryAudit = $conn->prepare($sqlAudit);
                                //&& $queryAudit
                                if (!($stmt)) {
                                    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                                } else {
                                    $adminvalue = 'Add New Score for User with Matric:' . $rowData[0] . ' and User ID:' . $user_id;
                                    $stmt->bind_param("sssssssssssss", $rowData[0], $rowData[1], $degree, $department, $user_id, $field_of_interest, $faculty, $sec, $cgpa_id, $status, $unit, $mode, $form_year);
                                    // $queryAudit->bind_param("ss", $admin, $adminvalue);
                                    if ($stmt->execute()) {   //&& $queryAudit->execute()
                                        $insertedData[] = $rowData;
                                    } else {
                                        $failedData[] = $rowData;
                                    }
                                    $stmt->close();
                                    // $queryAudit->close();
                                }
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
        } else {
            echo "No failed data to display.";
        }
    } else {
        echo "File upload failed!";
    }
    echo "</div>";
}



function processresult($conn, $field, $effectivedate, $external, $dept, $resulttype)
{



    $approval_query = "SELECT DISTINCT approval,stage,faculty_date FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'";
    $approval_result = $conn->query($approval_query);
    $approval_row = $approval_result->fetch_assoc();
    $approval_date = $approval_row['approval'];
    $stage = $approval_row['stage'];
    $faculty_date = $approval_row['faculty_date'];
    

    $external_query = "SELECT * FROM external_cgpa WHERE id = $external";
    $external_result = $conn->query($external_query);
    $external_row = $external_result->fetch_assoc();
    $external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
    $external_sign = $external_row['signature'];

    $subdean_query = "SELECT * FROM subdean_cgpa WHERE dept_new = $dept AND status = 0";
    $subdean_result = $conn->query($subdean_query);
    $subdean_row = $subdean_result->fetch_assoc();
    $subdean_sign = $subdean_row['signature'];
    $subdean_title_id = $subdean_row['title'];
    $subdean_sign = '';
    if($stage >= 2){

        $subdean_sign = $subdean_row['signature'];
    }

    $subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
    $subdean_title_result = $conn->query($subdean_title_query);
    $subdean_title_row = $subdean_title_result->fetch_assoc();

    $subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];


    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
    $hod_result = $conn->query($hod_query);
    $hod_row = $hod_result->fetch_assoc();
    $hod_desig_id = $hod_row['designation'];
    $hod_title_id = $hod_row['title'];
    $hod_sign = $hod_row['signature'];

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
        LEFT JOIN course_new c ON t.cozid = c.id LEFT JOIN programme_cgpa p ON t.degree = p.degree_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'
        ORDER BY c.corder"; // Ordering by matric, course_code, and exam_sec





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

    // $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
    // $queryAudit = $conn->prepare($sqlAudit);

    if (($result->num_rows > 0)) {
        // Initialize arrays to store data
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

        //$queryAudit->bind_param("ss", $admin, 'Process Result for field of Interest: '.$field.', Effective Date: '.$effectivedate .'and External Examiner:'.$external);


        // Iterate through the results and organize data
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


        // Display the data in a table
        echo '<table width=100% class=custom-table   align=center>';
        // Output header row
        echo '<tr>';
        echo '<th class=codea>S/N</th><th >MATRICNO</th><th >Session</th><th >Mode</th>';
        foreach ($courses as $courseCode => $scores) {

            echo "<th class=codea>$courseCode</th>";
        }
        //<th>TTP</th><th>TCTP</th><th>CP</th><th>RTP</th><th>RP</th>
        echo '<th class=codea>TUT</th><th class=codea>TUP</th><th class=codea>TGP</th><th class=codea>CGPA</th><th class=codea>RESULT</th><th class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
        echo '</tr>';

        // Output status row
        echo '<tr>';
        echo '<td></td><td></td><td></td><td></td>';
        foreach ($status as $courseCode => $courseStatus) {
            echo '<td>' . $courseStatus . '</td>';
        }
        echo '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the status row
        echo '</tr>';

        // Output unit row
        echo '<tr>';
        echo '<td></td><td></td><td></td><td></td>';
        foreach ($courses as $courseCode => $scores) {
            echo '<td>' . ($unit[$courseCode] ?? '') . '</td>';
        }
        echo '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the unit row
        echo '</tr>';

        // Initialize counters for each remark category
        $totalPhD = 0;
        $totalMPhilPhD = 0;
        $totalMPhil = 0;
        $totalTM = 0;
        $totalNG = 0;
        $totalProf = 0;
        $totalUnknown = 0;
        $serialNumber = 1;
        // Output data rows for each matric number
        foreach ($matrics as $matric) {
            echo '<tr>';
            echo "<td>" . $serialNumber++ . "</td><td>$matric</td><td>$entry</td><td>$modeOfStudy</td>";
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
            // echo '<td>' . ($ttp ?? '') . '</td>';
            // echo '<td>' . ($tctp ?? '') . '</td>';
            // echo '<td>' . ($corepassed[$matric] ?? '') . '</td>';
            // echo '<td>' . ($trtp ?? '') . '</td>';
            // echo '<td>' . ($requiredpassed[$matric] ?? '') . '</td>';


            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            echo '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';


            //&& ($tupValues[$matric] >= $ttp)
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
            // elseif (($program=='Academics') && ($tupValues[$matric] < 30)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {
            //     $remark = "NG";
            //     $result = "-";
            //     $cgpa = "-";
            //     $totalNG++;
            // } 

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
            //     elseif(($program=='Professional') && ($tupValues[$matric] < 45)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {

            //         $remark = "NG";
            //         $result = "-";
            //         $totalProf++;

            // }

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
                echo '<td>' . '-' . '</td>';
            } else {
                echo '<td>' . $cgpa . '</td>';
            }


            // Output the remarks in the main table
            echo '<td>' . $result . '</td>';
            echo '<td>' . $remark . '</td>';
            echo '</tr>';

            $get_user_id = "SELECT user_id  FROM `prev_app` WHERE `matric` = '$matric'"; // Ordering by matric, course_code, and exam_sec
            $result_user_id = mysqli_query($conn, $get_user_id);
            $row_user_id = mysqli_fetch_assoc($result_user_id);
            $user_id = $row_user_id['user_id'];

            $query_get_remark = "SELECT COUNT(*) AS count FROM remark WHERE user_id = '$user_id' AND matric = '$matric'";
            $result_get_remark = mysqli_query($conn, $query_get_remark);
            $row_get_remark = mysqli_fetch_assoc($result_get_remark);

            if ($row_get_remark['count'] == 0) {
                $query_insert_remark = "INSERT INTO remark (user_id,matric,grade,remark,dept) VALUES ('$user_id','$matric','$result','$remark','$dept')";
                $result_insert_remark = mysqli_query($conn, $query_insert_remark);
            }
        }


        // Display total counts for remarks
        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;

            echo '<br><br><table class=custom-table   align=center>';
            echo '<h4 style= text-align:center; >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';

            echo "<tr><td colspan=6>SUMMARY</td></tr>";
            echo '<tr><td>PhD</td><td>M.Phil/Ph.D</td><td>M.Phil</td><td>TM</td><td>NG</td><td>Total </td></tr>';

            echo '<tr><td>' . $totalPhD . '</td>';
            echo '<td>' . $totalMPhilPhD . '</td>';
            echo '<td>' . $totalMPhil . '</td>';
            echo '<td>' . $totalTM . '</td>';
            echo '<td>' . $totalNG . '</td>';
            echo '<td>' . $totalCombined . '</td></tr>';






            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';

            echo "
    
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
            
            <img class='sign' src='img/$hod_sign' alt='No Signature for this Examiner'>
            <h4>$hod_name<br> $hod_desig</h4> 
            </p>
            </div>
            <div class='col-md-4'>
            <h4>Approve at the Faculty Postgraduate Commitee meeting of $approval_date</h4>
            <p  class='line'>
            __________
            </p>
                <p>
                <img class='sign' src='img/$subdean_sign' alt='No Signature for this Examiner'>
                <h4>$faculty_date <br>$subdean_name<br> Sub-Dean (Postgraduate)</h4> 
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
            echo '<br><br><table class=custom-table   align=center>';
            echo '<tr><td>Remark</td><td>Total</td></tr>';

            echo '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            echo '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // echo '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';
        }
    } else {
        echo "0 results";
    }
    // Close the database connnection
    $conn->close();
}

function    processboardresult($conn, $field, $effectivedate, $external, $dept, $resulttype, $sec)
{



    $approval_query = "SELECT DISTINCT approval,stage,faculty_date FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'";
    $approval_result = $conn->query($approval_query);
    $approval_row = $approval_result->fetch_assoc();
    $approval_date = $approval_row['approval'];
    $stage = $approval_row['stage'];
    $faculty_date = $approval_row['faculty_date'];


    $external_query = "SELECT * FROM external_cgpa WHERE id = $external";
    $external_result = $conn->query($external_query);
    $external_row = $external_result->fetch_assoc();
    $external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
    $external_sign = $external_row['signature'];

    $subdean_query = "SELECT * FROM subdean_cgpa WHERE dept_new = $dept AND status = 0";
    $subdean_result = $conn->query($subdean_query);
    $subdean_row = $subdean_result->fetch_assoc();
    $subdean_title_id = $subdean_row['title'];
    $subdean_sign = '';
    if($stage >= 2){

        $subdean_sign = $subdean_row['signature'];
    }

    $subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
    $subdean_title_result = $conn->query($subdean_title_query);
    $subdean_title_row = $subdean_title_result->fetch_assoc();

    $subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];


    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
    $hod_result = $conn->query($hod_query);
    $hod_row = $hod_result->fetch_assoc();
    $hod_desig_id = $hod_row['designation'];
    $hod_title_id = $hod_row['title'];

    $hod_desig_query = "SELECT title FROM designation WHERE id = '$hod_desig_id'";
    $hod_desig_result = $conn->query($hod_desig_query);
    $hod_desig_row = $hod_desig_result->fetch_assoc();
    $hod_desig = $hod_desig_row['title'];
    $hod_sign = $hod_row['signature'];

    $hod_title_query = "SELECT title FROM title WHERE id = '$hod_title_id'";
    $hod_title_result = $conn->query($hod_title_query);
    $hod_title_row = $hod_title_result->fetch_assoc();

    if ($hod_desig === 'Professor and Head') {

        $hod_name = $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
    } else {

        $hod_name = $hod_title_row['title'] . ' ' . $hod_row['initial'] . ' ' . $hod_row['lname'] . ' ' . $hod_row['fname'];
    }


    $sql = "SELECT t.matric,t.yr_of_entry,t.mode,t.score,t.exam_sec,t.field,t.external,t.effectivedate,t.mode,c.course_code,c.corder,
    c.unit,c.status,p.type as program,new.numeration,reginvoice.amount_paid,reginvoice.amount_charge
FROM 
    testscore t LEFT JOIN new ON new.id = t.user_id LEFT JOIN reginvoice ON reginvoice.appno = new.numeration 
LEFT JOIN course_new c ON t.cozid = c.id  LEFT JOIN programme_cgpa p ON t.degree = p.degree_id 
WHERE t.field='$field' AND t.effectivedate='$effectivedate' AND t.external='$external' AND t.resulttype='$resulttype' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
ORDER BY c.corder;"; // Ordering by matric, course_code, and exam_sec




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

    // $sqlAudit = "INSERT INTO audit (name,action) VALUES ('?','?')";
    // $queryAudit = $conn->prepare($sqlAudit);

    if (($result->num_rows > 0)) {
        // Initialize arrays to store data
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

        //$queryAudit->bind_param("ss", $admin, 'Process Result for field of Interest: '.$field.', Effective Date: '.$effectivedate .'and External Examiner:'.$external);


        // Iterate through the results and organize data
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


        // Display the data in a table
        echo '<table width=100% class=custom-table   align=center>';
        // Output header row
        echo '<tr>';
        echo '<th class=codea>S/N</th><th >MATRICNO</th><th >Session</th><th >Mode</th>';
        foreach ($courses as $courseCode => $scores) {

            echo "<th class=codea>$courseCode</th>";
        }
        //<th>TTP</th><th>TCTP</th><th>CP</th><th>RTP</th><th>RP</th>
        echo '<th class=codea>TUT</th><th class=codea>TUP</th><th class=codea>TGP</th><th class=codea>CGPA</th><th class=codea>RESULT</th><th class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
        echo '</tr>';

        // Output status row
        echo '<tr>';
        echo '<td></td><td></td><td></td><td></td>';
        foreach ($status as $courseCode => $courseStatus) {
            echo '<td>' . $courseStatus . '</td>';
        }
        echo '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the status row
        echo '</tr>';

        // Output unit row
        echo '<tr>';
        echo '<td></td><td></td><td></td><td></td>';
        foreach ($courses as $courseCode => $scores) {
            echo '<td>' . ($unit[$courseCode] ?? '') . '</td>';
        }
        echo '<td></td><td></td><td></td><td></td>'; // Empty cells for TUT, TUP in the unit row
        echo '</tr>';

        // Initialize counters for each remark category
        $totalPhD = 0;
        $totalMPhilPhD = 0;
        $totalMPhil = 0;
        $totalTM = 0;
        $totalNG = 0;
        $totalProf = 0;
        $totalUnknown = 0;
        $serialNumber = 1;
        // Output data rows for each matric number
        foreach ($matrics as $matric) {
            echo '<tr>';
            echo "<td>" . $serialNumber++ . "</td><td>$matric</td><td>$entry</td><td>$modeOfStudy</td>";
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
            // echo '<td>' . ($ttp ?? '') . '</td>';
            // echo '<td>' . ($tctp ?? '') . '</td>';
            // echo '<td>' . ($corepassed[$matric] ?? '') . '</td>';
            // echo '<td>' . ($trtp ?? '') . '</td>';
            // echo '<td>' . ($requiredpassed[$matric] ?? '') . '</td>';


            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            echo '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';


            //&& ($tupValues[$matric] >= $ttp)
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
            // elseif (($program=='Academics') && ($tupValues[$matric] < 30)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {
            //     $remark = "NG";
            //     $result = "-";
            //     $cgpa = "-";
            //     $totalNG++;
            // } 

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
            //     elseif(($program=='Professional') && ($tupValues[$matric] < 45)  && ($corepassed[$matric] < $tctp) && ($requiredpassed[$matric] < $trtp)) {

            //         $remark = "NG";
            //         $result = "-";
            //         $totalProf++;

            // }

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
                echo '<td>' . '-' . '</td>';
            } else {
                echo '<td>' . $cgpa . '</td>';
            }


            // Output the remarks in the main table
            echo '<td>' . $result . '</td>';
            echo '<td>' . $remark . '</td>';
            echo '</tr>';

            $get_user_id = "SELECT user_id  FROM `prev_app` WHERE `matric` = '$matric'"; // Ordering by matric, course_code, and exam_sec
            $result_user_id = mysqli_query($conn, $get_user_id);
            $row_user_id = mysqli_fetch_assoc($result_user_id);
            $user_id = $row_user_id['user_id'];

            $query_get_remark = "SELECT COUNT(*) AS count FROM remark WHERE user_id = '$user_id' AND matric = '$matric'";
            $result_get_remark = mysqli_query($conn, $query_get_remark);
            $row_get_remark = mysqli_fetch_assoc($result_get_remark);

            if ($row_get_remark['count'] == 0) {
                $query_insert_remark = "INSERT INTO remark (user_id,matric,grade,remark,dept) VALUES ('$user_id','$matric','$result','$remark','$dept')";
                $result_insert_remark = mysqli_query($conn, $query_insert_remark);
            }
        }


        // Display total counts for remarks
        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;

            echo '<br><br><table class=custom-table   align=center>';
            echo '<h4 style= text-align:center; >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';

            echo "<tr><td colspan=6>SUMMARY</td></tr>";
            echo '<tr><td>PhD</td><td>M.Phil/Ph.D</td><td>M.Phil</td><td>TM</td><td>NG</td><td>Total </td></tr>';

            echo '<tr><td>' . $totalPhD . '</td>';
            echo '<td>' . $totalMPhilPhD . '</td>';
            echo '<td>' . $totalMPhil . '</td>';
            echo '<td>' . $totalTM . '</td>';
            echo '<td>' . $totalNG . '</td>';
            echo '<td>' . $totalCombined . '</td></tr>';






            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';

            echo "
    
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
            echo '<br><br><table class=custom-table   align=center>';
            echo '<tr><td>Remark</td><td>Total</td></tr>';

            echo '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            echo '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // echo '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';
        }
    } else {
        echo "0 results";
    }
    // Close the database connnection
    $conn->close();
}
function processResultPDF($conn, $field, $effectivedate, $external, $dept, $resulttype)
{

    $approval_query = "SELECT DISTINCT approval FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'";
    $approval_result = $conn->query($approval_query);
    $approval_row = $approval_result->fetch_assoc();
    $approval_date = $approval_row['approval'];


    $external_query = "SELECT * FROM external_cgpa WHERE id = $external";
    $external_result = $conn->query($external_query);
    $external_row = $external_result->fetch_assoc();
    $external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
    $external_sign = $external_row['signature'];

    $subdean_query = "SELECT * FROM subdean_cgpa WHERE dept_new = $dept AND status = 0";
    $subdean_result = $conn->query($subdean_query);
    $subdean_row = $subdean_result->fetch_assoc();
    $subdean_sign = $subdean_row['signature'];
    $subdean_title_id = $subdean_row['title'];

    $subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
    $subdean_title_result = $conn->query($subdean_title_query);
    $subdean_title_row = $subdean_title_result->fetch_assoc();

    $subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];


    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
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
            LEFT JOIN course_new c ON t.cozid = c.id  LEFT JOIN programme_cgpa p ON t.degree = p.degree_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'
            ORDER BY c.corder"; // Ordering by matric, course_code, and exam_sec





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
        // Initialize arrays to store data
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
        //  $message = processresult2($conn,$field,$effectivedate,$external,$dept,$resulttype);



        $i = 0;
        while ($row = $result->fetch_assoc()) {





            $matric = $row['matric'];
            $mode = $row['mode'];
            if ($mode == 1) {
                $modeOfStudy = 'FullTime';
            } elseif ($mode == 2) {
                $modeOfStudy = 'PartTime';
            }
            $entry = $row['yr_of_entry'];
            $courseCode = $row['course_code'];
            $score = $row['score'];
            $corder = $row['corder'];
            $courseUnit = $row['unit'];
            $courseStatus = $row['status'];
            $examSec = $row['exam_sec'];
            $program = $row['program'];

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
        $html = '<table width=100% class=custom-table   align=center>';
        // Output header row
        $html .=  '<tr>';
        $html .=  '<th style=font-weight:bold class="codea">S/N</th>
        <th style=font-weight:bold>MATRICNO</th>
        <th style=font-weight:bold>Session</th>
        <th style=font-weight:bold>Mode</th>';
        foreach ($courses as $courseCode => $scores) {

            $html .=  '<th style=font-weight:bold class=codea>' . $courseCode . '</th>';
        }
        $html .= '<th style=font-weight:bold class=codea>TUT</th><th style=font-weight:bold class=codea>TUP</th><th style=font-weight:bold class=codea>TGP</th><th style=font-weight:bold class=codea>CGPA</th><th style=font-weight:bold class=codea>RESULT</th><th style=font-weight:bold class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK

        $html .=  '</tr>';

        // Output status row
        $html .=  '<tr>';
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>';
        foreach ($status as $courseCode => $courseStatus) {
            $html .=  '<td>' . $courseStatus . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row
        $html .=  '</tr>';

        $html .=  '<tr>';
        $html .=  '<td > -- </td>
    <td> -- </td>
    <td> -- </td>
    <td> -- </td>';
        foreach ($courses as $courseCode => $scores) {
            $html .= '<td>' . ($unit[$courseCode] ?? '') . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row


        $html .= '</tr>';

        $totalPhD = 0;
        $totalMPhilPhD = 0;
        $totalMPhil = 0;
        $totalTM = 0;
        $totalNG = 0;
        $totalProf = 0;
        $totalUnknown = 0;
        $serialNumber = 1;

        foreach ($matrics as $matric) {
            $html .=  '<tr>';
            $html .=  '<td>' . $serialNumber++ . '</td> <td>' . $matric . '</td><td>' . $entry . '</td><td>' . $modeOfStudy . '</td>';

            foreach ($courses as $courseCode => $scores) {
                $html .= '<td>';
                if (isset($courses[$courseCode][$matric])) {
                    $courseScores = $courses[$courseCode][$matric];
                    ksort($courseScores); // Sort scores by exam_sec

                    // If there are multiple scores, conncatenate them with '/'
                    if (count($courseScores) > 1) {
                        $conncatenatedScores = implode('/', $courseScores);
                        $html .=  $conncatenatedScores;
                    } else {
                        $html .=  reset($courseScores); // Display the single score
                    }
                } else {
                    $html .=  '-';
                }
                $html .= '</td>';
            }
            $html .=  '<td>' . ($unit[$matric] ?? '') . '</td>';
            $html .=  '<td>' . ($tupValues[$matric] ?? '') . '</td>';
            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            $html .=  '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
            if (($program == 'Academics') && ($tupValues[$matric] > 29) && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
                if ($cgpa < 9.0 && $cgpa >= 5.0) {
                    $remark = 'Ph.D';
                    $result = 'PASS';
                    $totalPhD++;
                } elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
                    $remark = 'M.Phil/Ph.D';
                    $result = 'PASS';
                    $totalMPhilPhD++;
                } elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
                    $remark = 'M.Phil';
                    $result = 'PASS';
                    $totalMPhil++;
                } elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
                    $remark = 'TM';
                    $result = 'PASS';
                    $totalTM++;
                }
            } elseif (($program == 'Academics') && (($tupValues[$matric] < 30)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
                $remark = 'NG';
                $result = '-';
                $cgpa = '-';
                $totalNG++;
            } elseif (($program == 'Professional') && ($tupValues[$matric] > 44)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {

                $remark = 'PASS';
                $result = 'PASS';
                $totalProf++;
            } elseif (($program == 'Professional') && (($tupValues[$matric] < 45)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {

                $remark = 'NG';
                $result = '-';
                $totalProf++;
            } else {
                $remark = '-';
                $result = '-';
                $totalUnknown++;
            }
            if ($remark === 'NG') {
                $html .=  '<td> - </td>';
            } else {
                $html .=  '<td>' . $cgpa . '</td>';
            }

            $html .=  '<td>' . $result . '</td>';
            $html .= '<td>' . $remark . '</td>';
        }

        $html .=  '</tr>';


        $html .=  '</table>';
        $html .=  '<h4 align=center >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';

        $html .=  '<br>';

        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;

            $html .=  '<table class=custom-table   align=center>';
            $html .=  '<tr><td style=font-weight:bold colspan=6>SUMMARY</td></tr>';
            $html .=  '<tr><td> PhD </td><td> M.Phil/Ph.D </td><td> M.Phil </td><td> TM </td><td> NG </td><td> Total </td></tr>';

            $html .=  '<tr><td>' . $totalPhD . '</td>';
            $html .=  '<td>' . $totalMPhilPhD . '</td>';
            $html .=  '<td>' . $totalMPhil . '</td>';
            $html .=  '<td>' . $totalTM . '</td>';
            $html .=  '<td>' . $totalNG . '</td>';
            $html .=  '<td>' . $totalCombined . '</td></tr>';






            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //$mps = '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        } elseif ($program == 'Professional') {
            $html .=  '<br><br><table class=custom-table   align=center>';
            $html .=  '<tr><td>Remark</td><td>Total</td></tr>';

            $html .=  '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            $html .=  '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // $mps = '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            $html .=  '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        }
    }
    $html .=  "
    
    <h4>Effective Date of Award: $effectivedate</h4>
    <h4 align=center>Approve at the Faculty Postgraduate Commitee meeting of $approval_date</h4>
    <table class='foot'>
    
    <tr>
    
    <td align=center>
    <br><br><br>
    
                <p  style=color:#CD853F>
                _______________________________
                </p>
                <p>
                
    
                    <h4>$hod_name<br> $hod_desig</h4> 
                </p>
    </td>
    
    <td style=color:white align=center>
    ------------------------------------------------------------------------------
    </td>
    
    <td align=center>
    <br><br>
                <p  style=color:#CD853F>
                _______________________________
                </p>
                    <p>
                        <h4>$subdean_name<br> Sub-Dean (Postgraduate)</h4> 
                    </p>
    </td>
    <td style=color:white align=center>
    ------------------------------------------------------------------------
    </td>
    <td align=center>
    
    <p>
                        
                        <img class='sign' src='img/$external_sign' width='80' height='60' alt='No Signature for this Examiner'>
                      
                        <h4>$external_name <br> External Examiner</h4> 
                    </p>
    
    </td>
    </tr>
    
    
    </table>
        
    ";


    return $html;
}
function processBoardResultPDF($conn, $field, $effectivedate, $external, $dept, $resulttype)
{

    $approval_query = "SELECT DISTINCT approval FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype'";
    $approval_result = $conn->query($approval_query);
    $approval_row = $approval_result->fetch_assoc();
    $approval_date = $approval_row['approval'];


    $external_query = "SELECT * FROM external_cgpa WHERE id = $external";
    $external_result = $conn->query($external_query);
    $external_row = $external_result->fetch_assoc();
    $external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
    $external_sign = $external_row['signature'];

    $subdean_query = "SELECT * FROM subdean_cgpa WHERE dept_new = $dept AND status = 0";
    $subdean_result = $conn->query($subdean_query);
    $subdean_row = $subdean_result->fetch_assoc();
    $subdean_sign = $subdean_row['signature'];
    $subdean_title_id = $subdean_row['title'];

    $subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
    $subdean_title_result = $conn->query($subdean_title_query);
    $subdean_title_row = $subdean_title_result->fetch_assoc();

    $subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];


    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
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


    $sql = "SELECT t.matric,t.yr_of_entry,t.mode,t.score,t.exam_sec,t.field,t.external,t.effectivedate,t.mode,c.course_code,c.corder,
    c.unit,c.status,p.type as program,new.numeration,reginvoice.amount_paid,reginvoice.amount_charge
FROM 
    testscore t LEFT JOIN new ON new.id = t.user_id LEFT JOIN reginvoice ON reginvoice.appno = new.numeration 
LEFT JOIN course_new c ON t.cozid = c.id  LEFT JOIN programme_cgpa p ON t.degree = p.degree_id 
WHERE t.field='$field' AND t.effectivedate='$effectivedate' AND t.external='$external' AND t.resulttype='$resulttype' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
ORDER BY c.corder;";




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
        // Initialize arrays to store data
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
        //  $message = processresult2($conn,$field,$effectivedate,$external,$dept,$resulttype);



        $i = 0;
        while ($row = $result->fetch_assoc()) {





            $matric = $row['matric'];
            $mode = $row['mode'];
            if ($mode == 1) {
                $modeOfStudy = 'FullTime';
            } elseif ($mode == 2) {
                $modeOfStudy = 'PartTime';
            }
            $entry = $row['yr_of_entry'];
            $courseCode = $row['course_code'];
            $score = $row['score'];
            $corder = $row['corder'];
            $courseUnit = $row['unit'];
            $courseStatus = $row['status'];
            $examSec = $row['exam_sec'];
            $program = $row['program'];

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
        $html = '<table width=100% class=custom-table   align=center>';
        // Output header row
        $html .=  '<tr>';
        $html .=  '<th style=font-weight:bold class="codea">S/N</th>
        <th style=font-weight:bold>MATRICNO</th>
        <th style=font-weight:bold>Session</th>
        <th style=font-weight:bold>Mode</th>';
        foreach ($courses as $courseCode => $scores) {

            $html .=  '<th style=font-weight:bold class=codea>' . $courseCode . '</th>';
        }
        $html .= '<th style=font-weight:bold class=codea>TUT</th><th style=font-weight:bold class=codea>TUP</th><th style=font-weight:bold class=codea>TGP</th><th style=font-weight:bold class=codea>CGPA</th><th style=font-weight:bold class=codea>RESULT</th><th style=font-weight:bold class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK

        $html .=  '</tr>';

        // Output status row
        $html .=  '<tr>';
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>';
        foreach ($status as $courseCode => $courseStatus) {
            $html .=  '<td>' . $courseStatus . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row
        $html .=  '</tr>';

        $html .=  '<tr>';
        $html .=  '<td > -- </td>
    <td> -- </td>
    <td> -- </td>
    <td> -- </td>';
        foreach ($courses as $courseCode => $scores) {
            $html .= '<td>' . ($unit[$courseCode] ?? '') . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row


        $html .= '</tr>';

        $totalPhD = 0;
        $totalMPhilPhD = 0;
        $totalMPhil = 0;
        $totalTM = 0;
        $totalNG = 0;
        $totalProf = 0;
        $totalUnknown = 0;
        $serialNumber = 1;

        foreach ($matrics as $matric) {
            $html .=  '<tr>';
            $html .=  '<td>' . $serialNumber++ . '</td> <td>' . $matric . '</td><td>' . $entry . '</td><td>' . $modeOfStudy . '</td>';

            foreach ($courses as $courseCode => $scores) {
                $html .= '<td>';
                if (isset($courses[$courseCode][$matric])) {
                    $courseScores = $courses[$courseCode][$matric];
                    ksort($courseScores); // Sort scores by exam_sec

                    // If there are multiple scores, conncatenate them with '/'
                    if (count($courseScores) > 1) {
                        $conncatenatedScores = implode('/', $courseScores);
                        $html .=  $conncatenatedScores;
                    } else {
                        $html .=  reset($courseScores); // Display the single score
                    }
                } else {
                    $html .=  '-';
                }
                $html .= '</td>';
            }
            $html .=  '<td>' . ($unit[$matric] ?? '') . '</td>';
            $html .=  '<td>' . ($tupValues[$matric] ?? '') . '</td>';
            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            $html .=  '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
            if (($program == 'Academics') && ($tupValues[$matric] > 29) && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
                if ($cgpa < 9.0 && $cgpa >= 5.0) {
                    $remark = 'Ph.D';
                    $result = 'PASS';
                    $totalPhD++;
                } elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
                    $remark = 'M.Phil/Ph.D';
                    $result = 'PASS';
                    $totalMPhilPhD++;
                } elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
                    $remark = 'M.Phil';
                    $result = 'PASS';
                    $totalMPhil++;
                } elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
                    $remark = 'TM';
                    $result = 'PASS';
                    $totalTM++;
                }
            } elseif (($program == 'Academics') && (($tupValues[$matric] < 30)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
                $remark = 'NG';
                $result = '-';
                $cgpa = '-';
                $totalNG++;
            } elseif (($program == 'Professional') && ($tupValues[$matric] > 44)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {

                $remark = 'PASS';
                $result = 'PASS';
                $totalProf++;
            } elseif (($program == 'Professional') && (($tupValues[$matric] < 45)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {

                $remark = 'NG';
                $result = '-';
                $totalProf++;
            } else {
                $remark = '-';
                $result = '-';
                $totalUnknown++;
            }
            if ($remark === 'NG') {
                $html .=  '<td> - </td>';
            } else {
                $html .=  '<td>' . $cgpa . '</td>';
            }

            $html .=  '<td>' . $result . '</td>';
            $html .= '<td>' . $remark . '</td>';
        }

        $html .=  '</tr>';


        $html .=  '</table>';
        $html .=  '<h4 align=center >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';

        $html .=  '<br>';

        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;

            $html .=  '<table class=custom-table   align=center>';
            $html .=  '<tr><td style=font-weight:bold colspan=6>SUMMARY</td></tr>';
            $html .=  '<tr><td> PhD </td><td> M.Phil/Ph.D </td><td> M.Phil </td><td> TM </td><td> NG </td><td> Total </td></tr>';

            $html .=  '<tr><td>' . $totalPhD . '</td>';
            $html .=  '<td>' . $totalMPhilPhD . '</td>';
            $html .=  '<td>' . $totalMPhil . '</td>';
            $html .=  '<td>' . $totalTM . '</td>';
            $html .=  '<td>' . $totalNG . '</td>';
            $html .=  '<td>' . $totalCombined . '</td></tr>';






            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //$mps = '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        } elseif ($program == 'Professional') {
            $html .=  '<br><br><table class=custom-table   align=center>';
            $html .=  '<tr><td>Remark</td><td>Total</td></tr>';

            $html .=  '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            $html .=  '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // $mps = '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            $html .=  '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        }
    }
    $html .=  "
    
    <h4>Effective Date of Award: $effectivedate</h4>
    <h4 align=center>Approve at the Faculty Postgraduate Commitee meeting of $approval_date</h4>
    <table class='foot'>
    
    <tr>
    
    <td align=center>
    <br><br><br>
    
                <p  style=color:#CD853F>
                _______________________________
                </p>
                <p>
                
    
                    <h4>$hod_name<br> $hod_desig</h4> 
                </p>
    </td>
    
    <td style=color:white align=center>
    ------------------------------------------------------------------------------
    </td>
    
    <td align=center>
    <br><br>
                <p  style=color:#CD853F>
                _______________________________
                </p>
                    <p>
                        <h4>$subdean_name<br> Sub-Dean (Postgraduate)</h4> 
                    </p>
    </td>
    <td style=color:white align=center>
    ------------------------------------------------------------------------
    </td>
    <td align=center>
    
    <p>
                        
                        <img class='sign' src='img/$external_sign' width='80' height='60' alt='No Signature for this Examiner'>
                      
                        <h4>$external_name <br> External Examiner</h4> 
                    </p>
    
    </td>
    </tr>
    
    
    </table>
        
    ";


    return $html;
}

function processBoardResultPDF2($conn, $field, $effectivedate, $external, $dept, $resulttype,$mode)
{

    $approval_query = "SELECT DISTINCT approval,stage,faculty_date FROM testscore AS t WHERE t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external' AND t.resulttype='$resulttype' AND t.mode='$mode'";
    $approval_result = $conn->query($approval_query);
    $approval_row = $approval_result->fetch_assoc();
    $approval_date = $approval_row['approval'];
    $stage = $approval_row['stage'];
    $faculty_date = $approval_row['faculty_date'];

    $external_query = "SELECT * FROM external_cgpa WHERE id = $external";
    $external_result = $conn->query($external_query);
    $external_row = $external_result->fetch_assoc();
    $external_name = $external_row['initial'] . ' ' . $external_row['lname'] . ' ' . $external_row['fname'];
    $external_sign = $external_row['signature'];

    $subdean_query = "SELECT * FROM subdean_cgpa WHERE dept_new = $dept AND status = 0";
    $subdean_result = $conn->query($subdean_query);
    $subdean_row = $subdean_result->fetch_assoc();
    $subdean_title_id = $subdean_row['title'];
    $subdean_sign = $subdean_row['signature'];
    $subdean_title_query = "SELECT title FROM title WHERE id = $subdean_title_id";
    $subdean_title_result = $conn->query($subdean_title_query);
    $subdean_title_row = $subdean_title_result->fetch_assoc();

    $subdean_name = $subdean_title_row['title'] . ' ' . $subdean_row['initial'] . ' ' . $subdean_row['lname'] . ' ' . $subdean_row['fname'];


    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
    $hod_result = $conn->query($hod_query);
    $hod_row = $hod_result->fetch_assoc();
    $hod_desig_id = $hod_row['designation'];
    $hod_title_id = $hod_row['title'];
    $hod_sign = $hod_row['signature'];

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


    $sql = "SELECT t.matric,t.yr_of_entry,t.mode,t.score,t.exam_sec,t.field,t.external,t.effectivedate,t.mode,c.course_code,c.corder,
    c.unit,c.status,p.type as program,new.numeration,reginvoice.amount_paid,reginvoice.amount_charge
FROM 
    testscore t LEFT JOIN new ON new.id = t.user_id LEFT JOIN reginvoice ON reginvoice.appno = new.numeration 
LEFT JOIN course_new c ON t.cozid = c.id  LEFT JOIN programme_cgpa p ON t.degree = p.degree_id 
WHERE t.field='$field' AND t.effectivedate='$effectivedate' AND t.external='$external' AND t.resulttype='$resulttype' AND t.mode='$mode' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
ORDER BY c.corder;";




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
        // Initialize arrays to store data
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
        //  $message = processresult2($conn,$field,$effectivedate,$external,$dept,$resulttype);



        $i = 0;
        while ($row = $result->fetch_assoc()) {





            $matric = $row['matric'];
            $mode = $row['mode'];
            if ($mode == 1) {
                $modeOfStudy = 'FullTime';
            } elseif ($mode == 2) {
                $modeOfStudy = 'PartTime';
            }
            $entry = $row['yr_of_entry'];
            $courseCode = $row['course_code'];
            $score = $row['score'];
            $corder = $row['corder'];
            $courseUnit = $row['unit'];
            $courseStatus = $row['status'];
            $examSec = $row['exam_sec'];
            $program = $row['program'];

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
        $html = '<table width=100% class=custom-table   align=center>';
        // Output header row
        $html .=  '<tr>';
        $html .=  '<th style=font-weight:bold class="codea">S/N</th>
        <th style=font-weight:bold>MATRICNO</th>
        <th style=font-weight:bold>Session</th>
        <th style=font-weight:bold>Mode</th>';
        foreach ($courses as $courseCode => $scores) {

            $html .=  '<th style=font-weight:bold class=codea>' . $courseCode . '</th>';
        }
        $html .= '<th style=font-weight:bold class=codea>TUT</th><th style=font-weight:bold class=codea>TUP</th><th style=font-weight:bold class=codea>TGP</th><th style=font-weight:bold class=codea>CGPA</th><th style=font-weight:bold class=codea>RESULT</th><th style=font-weight:bold class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK

        $html .=  '</tr>';

        // Output status row
        $html .=  '<tr>';
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>';
        foreach ($status as $courseCode => $courseStatus) {
            $html .=  '<td>' . $courseStatus . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row
        $html .=  '</tr>';

        $html .=  '<tr>';
        $html .=  '<td > -- </td>
    <td> -- </td>
    <td> -- </td>
    <td> -- </td>';
        foreach ($courses as $courseCode => $scores) {
            $html .= '<td>' . ($unit[$courseCode] ?? '') . '</td>';
        }
        $html .=  '<td> -- </td><td> -- </td><td> -- </td><td> -- </td>'; // Empty cells for TUT, TUP in the status row


        $html .= '</tr>';

        $totalPhD = 0;
        $totalMPhilPhD = 0;
        $totalMPhil = 0;
        $totalTM = 0;
        $totalNG = 0;
        $totalProf = 0;
        $totalUnknown = 0;
        $serialNumber = 1;

        foreach ($matrics as $matric) {
            $html .=  '<tr>';
            $html .=  '<td>' . $serialNumber++ . '</td> <td>' . $matric . '</td><td>' . $entry . '</td><td>' . $modeOfStudy . '</td>';

            foreach ($courses as $courseCode => $scores) {
                $html .= '<td>';
                if (isset($courses[$courseCode][$matric])) {
                    $courseScores = $courses[$courseCode][$matric];
                    ksort($courseScores); // Sort scores by exam_sec

                    // If there are multiple scores, conncatenate them with '/'
                    if (count($courseScores) > 1) {
                        $conncatenatedScores = implode('/', $courseScores);
                        $html .=  $conncatenatedScores;
                    } else {
                        $html .=  reset($courseScores); // Display the single score
                    }
                } else {
                    $html .=  '-';
                }
                $html .= '</td>';
            }
            $html .=  '<td>' . ($unit[$matric] ?? '') . '</td>';
            $html .=  '<td>' . ($tupValues[$matric] ?? '') . '</td>';
            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            $html .=  '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
            if (($program == 'Academics') && ($tupValues[$matric] > 29) && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {
                if ($cgpa < 9.0 && $cgpa >= 5.0) {
                    $remark = 'Ph.D';
                    $result = 'PASS';
                    $totalPhD++;
                } elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
                    $remark = 'M.Phil/Ph.D';
                    $result = 'PASS';
                    $totalMPhilPhD++;
                } elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
                    $remark = 'M.Phil';
                    $result = 'PASS';
                    $totalMPhil++;
                } elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
                    $remark = 'TM';
                    $result = 'PASS';
                    $totalTM++;
                }
            } elseif (($program == 'Academics') && (($tupValues[$matric] < 30)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {
                $remark = 'NG';
                $result = '-';
                $cgpa = '-';
                $totalNG++;
            } elseif (($program == 'Professional') && ($tupValues[$matric] > 44)  && ($corepassed[$matric] >= $tctp) && ($requiredpassed[$matric] >= $trtp)) {

                $remark = 'PASS';
                $result = 'PASS';
                $totalProf++;
            } elseif (($program == 'Professional') && (($tupValues[$matric] < 45)  || (($corepassed[$matric] < $tctp) || ($requiredpassed[$matric] < $trtp)))) {

                $remark = 'NG';
                $result = '-';
                $totalProf++;
            } else {
                $remark = '-';
                $result = '-';
                $totalUnknown++;
            }
            if ($remark === 'NG') {
                $html .=  '<td> - </td>';
            } else {
                $html .=  '<td>' . $cgpa . '</td>';
            }

            $html .=  '<td>' . $result . '</td>';
            $html .= '<td>' . $remark . '</td>';
        }

        $html .=  '</tr>';


        $html .=  '</table>';
        $html .=  '<h4 align=center >TUT= Total Unit Taken, TUP= Total Unit Passed, TGP= Total Grade Point, CGPA= Total Grade Point Average, TM= Terminal Master, NG= Not Graduating, <br> C= Core Course, R= Required Course, E= Elective.</h4>';

        $html .=  '<br>';

        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;

            $html .=  '<table class=custom-table   align=center>';
            $html .=  '<tr><td style=font-weight:bold colspan=6>SUMMARY</td></tr>';
            $html .=  '<tr><td> PhD </td><td> M.Phil/Ph.D </td><td> M.Phil </td><td> TM </td><td> NG </td><td> Total </td></tr>';

            $html .=  '<tr><td>' . $totalPhD . '</td>';
            $html .=  '<td>' . $totalMPhilPhD . '</td>';
            $html .=  '<td>' . $totalMPhil . '</td>';
            $html .=  '<td>' . $totalTM . '</td>';
            $html .=  '<td>' . $totalNG . '</td>';
            $html .=  '<td>' . $totalCombined . '</td></tr>';






            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //$mps = '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        } elseif ($program == 'Professional') {
            $html .=  '<br><br><table class=custom-table   align=center>';
            $html .=  '<tr><td>Remark</td><td>Total</td></tr>';

            $html .=  '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            $html .=  '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // $mps = '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            $html .=  '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            $html .=  '</table>';
        }
    }
    $html .=  "
    
    <h4>Effective Date of Award: $effectivedate</h4>
    <h4 align=center>Approve at the Faculty Postgraduate Commitee meeting of $approval_date</h4>
    <table class='foot'>
    
    <tr>
    
    <td align=center>
    <br><br><br>
    
               <p>

    <img class='sign' src='../img/$hod_sign' alt='No Signature for this Examiner'>
<h4>$hod_name<br> $hod_desig</h4>
</p>
    </td>
    
    <td style=color:white align=center>
    ------------------------------------------------------------------------------
    </td>
    
    <td align=center>
    <br><br>
              
                <p>
                <h4>$faculty_date</h4>
                <img class='sign' src='../img/$subdean_sign' alt='No Signature for this Examiner'>
            <h4>$subdean_name<br> Sub-Dean (Postgraduate)</h4>
            </p>
    </td>
    <td style=color:white align=center>
    ------------------------------------------------------------------------
    </td>
    <td align=center>
    
    <p>

        <img class='sign' src='../img/$external_sign' alt='No Signature for this Examiner'>

    <h4>$external_name <br> External Examiner</h4>
    </p>
    
    </td>
    </tr>
    
    
    </table>
        
    ";


    return $html;
}




function processresult_old($conn, $field, $effectivedate, $external, $admin, $dept)
{

    $sql = "SELECT t.matric, t.score, t.exam_sec,t.field , t.external ,t.effectivedate, t.mode, c.course_code,c.corder, c.unit, c.status,p.type as program
        FROM testscore t
        INNER JOIN course_new c ON t.cozid = c.id  INNER JOIN programme_cgpa p ON t.degree = p.degree_id where t.field='$field' and t.effectivedate='$effectivedate' and t.external='$external'
        ORDER BY c.corder"; // Ordering by matric, course_code, and exam_sec

    $result = $conn->query($sql);

    $coz = mysqli_query($conn, "select * from course_new where dept_newids='$dept' and specialization='$field' order by course_code") or die(mysqli_error($conn));
    $tctp = 0;
    $trtp = 0;
    while ($row = mysqli_fetch_array($coz)) {
        if ($row['status'] == 'C') {
            $tctp += $row['unit'];
            echo $row['course_code'] . ": " . $row['status'] . " : " . $row['unit'] . "<br>";
        }
    }
    //echo $tctp."<br>";
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
        $coretopass = array();
        $corepassed = array();
        $unittopass = array();
        $totalCoreCoursesPassed = 0;

        //$queryAudit->bind_param("ss", $admin, 'Process Result for field of Interest: '.$field.', Effective Date: '.$effectivedate .'and External Examiner:'.$external);


        // Iterate through the results and organize data
        while ($row = $result->fetch_assoc()) {
            $matric = $row["matric"];
            $courseCode = $row["course_code"];
            $score = $row["score"];
            $corder = $row["corder"];
            $courseUnit = $row["unit"];
            $courseStatus = $row["status"];
            $examSec = $row["exam_sec"];
            $program = $row["program"];

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
            if (($courseStatus == 'C') and  (!isset($score))) {  //($score >= 40) AND //|| $courseStatus === 'E' || $courseStatus === 'EE'
                if (!isset($corepassed[$matric])) {
                    $corepassed[$matric] = 0;
                }
                $corepassed[$matric] += $courseUnit;
            }

            if ($status[$courseCode] === 'C' and $score >= 40) {
                $totalCoreCoursesPassed++;
            }
            // if (($courseStatus === 'C' && $score < 40) || ($score < 30 && ($courseStatus === 'R' ))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'
            //     // if (!isset($unittopass[$matric])) {
            //     //     $unittopass[$matric] = 0;
            //     // }
            //     $unittopass[$matric]="FAIL";
            //    // else{
            //     //$unittopass[$matric] += $courseUnit;
            //     }

            //     //$sta="FAIL";
            //     if (($courseStatus === 'C') && $score > 39)  {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="PASS";
            //     }

            //     if (($score > 29 && ($courseStatus === 'R' ))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="PASS";
            //     }

            //     if (($courseStatus === 'C') && $score < 40)  {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="FAIL";
            //     }

            //     if (($score < 30 && ($courseStatus === 'R' ))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="FAIL";
            //     }

            //     if (($courseStatus === 'C') && $score =="-")  {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="FAIL";
            //     }

            //     if (($score == "-" && ($courseStatus === 'R' ))) {  //|| $courseStatus === 'E' || $courseStatus === 'EE'

            //         $unittopass[$matric]="FAIL";
            //     }

            // echo $score. "= ". $unittopass[$matric]."<br>";
            //echo $score."<br>";

            // }

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
        // $status_order = ['C' => 1, 'R' => 2, 'E' => 3];
        // uasort($status, function ($a, $b) use ($status_order) {
        //     return ($status_order[$a] ?? 0) <=> ($status_order[$b] ?? 0);
        // });
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
        echo '<th class=codea>TUT</th><th class=codea>TUP</th><th>CTP</th><th>CP</th><th>NCP</th><th class=codea>TGP</th><th class=codea>CGPA</th><th class=codea>RESULT</th><th class=codea>REMARK</th>'; // Added columns for TUT, TUP, TGP, CGPA, RESULT, and REMARK
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
        $totalProf = 0;
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
            echo '<td>' . ($coretopass[$matric] ?? '') . '</td>';
            echo '<td>' . ($corepassed[$matric] ?? '') . '</td>';
            echo '<td>' . ($totalCoreCoursesPassed ?? '') . '</td>';

            $tgpSum = 0;
            foreach ($tgpValues[$matric] as $courseCodeTGP => $tgp) {
                $tgpSum += $tgp;
            }
            echo '<td>' . ($tgpSum ?? '') . '</td>';

            $cgpa = ($unit[$matric] !== 0) ? number_format($tgpSum / $unit[$matric], 2) : '-';
            echo '<td>' . $cgpa . '</td>';

            // Calculate the remarks and increment the corresponding counter
            //echo $program."<br>";

            // if ($score < 40 && $courseStatus === 'C') {
            //     $remark = "NG";
            //     $result = "-";
            //     $cgpa = "-";
            // } elseif ($score < 30 && $courseStatus === 'R') {
            //     $remark = "NG";
            //     $result = "-";
            //     $cgpa = "-";
            //echo $tupValues[$matric]."<br>";



            // if (($program == 'Academics') && ($tupValues[$matric] > 29)) {
            //     if ($score >= 40 && $courseStatus === 'C') {
            //         $remark = "PASS";
            //         $result = "PASS";
            //         $grade = getGrade($cgpa);
            //     } elseif ($score >= 30 && $courseStatus === 'R') {
            //         $remark = "PASS";
            //         $result = "PASS";
            //         $grade = getGrade($cgpa);
            //     }
            //  else {
            //     $remark = "-";
            //     $result = "-";
            //     $grade = "-";
            // }
            // }
            // elseif (($program == 'Academics') && ($tupValues[$matric] > 29)) {
            //     if ($score < 40 && $courseStatus === 'C') {
            //         $remark = "NG";
            //         $result = "-";
            //         $cgpa = "-";
            //     } elseif ($score < 30 && $courseStatus === 'R') {
            //         $remark = "NG";
            //         $result = "-";
            //         $cgpa = "-";
            //     } 
            // }









            if (($program == 'Academics') && ($tupValues[$matric] > 29)) {
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
            } elseif (($program == 'Academics') && ($tupValues[$matric] < 30)) {
                $remark = "NG";
                $result = "-";
                $cgpa = "-";
                $totalNG++;
            } elseif (($program == 'Professional') && ($tupValues[$matric] > 44)) {

                $remark = "PASS";
                $result = "PASS";
                $totalProf++;
            } elseif (($program == 'Professional') && ($tupValues[$matric] < 45)) {

                $remark = "NG";
                $result = "-";
                $totalProf++;
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
        if ($program == 'Academics') {
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            echo '<br><br><table class=custom-table   align=center>';
            echo "<tr><td colspan=6>SUMMARY</td></tr>";
            echo '<tr><td>PhD</td><td>M.Phil/Ph.D</td><td>M.Phil</td><td>TM</td><td>NG</td><td>Total </td></tr>';

            echo '<tr><td>' . $totalPhD . '</td>';
            echo '<td>' . $totalMPhilPhD . '</td>';
            echo '<td>' . $totalMPhil . '</td>';
            echo '<td>' . $totalTM . '</td>';
            echo '<td>' . $totalNG . '</td>';
            echo '<td>' . $totalCombined . '</td></tr>';





            // echo '<tr><td>Remark</td><td>Total</td></tr>';

            // echo '<tr><td>PhD</td><td>' . $totalPhD . '</td></tr>';
            // echo '<tr><td>M.Phil/Ph.D</td><td>' . $totalMPhilPhD . '</td></tr>';
            // echo '<tr><td>M.Phil</td><td>' . $totalMPhil . '</td></tr>';
            // echo '<tr><td>TM</td><td>' . $totalTM . '</td></tr>';
            // echo '<tr><td>NG</td><td>' . $totalNG . '</td></tr>';
            // echo '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalPhD + $totalMPhilPhD + $totalMPhil + $totalTM + $totalNG + $totalUnknown;
            //echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';
        } elseif ($program == 'Professional') {
            echo '<br><br><table class=custom-table   align=center>';
            echo '<tr><td>Remark</td><td>Total</td></tr>';

            echo '<tr><td>PASS</td><td>' . $totalProf . '</td></tr>';
            echo '<tr><td>FAIL</td><td>' . $totalNG . '</td></tr>';
            // echo '<tr><td>Total Unknown</td><td>' . $totalUnknown . '</td></tr>';

            // Add a single row to display the combined total
            $totalCombined = $totalProf + $totalNG;
            echo '<tr><td>Total </td><td>' . $totalCombined . '</td></tr>';

            echo '</table>';
        }
    } else {
        echo "0 results";
    }
    // Close the database connnection
    $conn->close();

    // function getGrade($cgpa)
    // {

    //     if ($cgpa < 9.0 && $cgpa >= 5.0) {
    //                       return "Ph.D";
    //                       $totalPhD++;
    //                 } elseif ($cgpa < 5.0 && $cgpa >= 4.0) {
    //                     return "M.Phil/Ph.D";
    //                     $totalMPhilPhD++;
    //                 } elseif ($cgpa < 4.0 && $cgpa >= 3.0) {
    //                     return "M.Phil";
    //                     $totalMPhil++;
    //                 } elseif ($cgpa < 3.0 && $cgpa >= 1.0) {
    //                     return "TM";
    //                     $totalTM++;

    //                 }

    // }


}



//

/// Hammed Function Start

//



function getHod($conn, $dept)
{

    $sql = "SELECT title.title, designation.designation, fname, lname, initial FROM hod_cgpa INNER JOIN title ON hod_cgpa.title=title.id INNER JOIN designation ON hod_cgpa.designation=designation.id WHERE dept_new = $dept";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function displayHod($conn, $dept)
{
    $sql = "SELECT title.title,designation.title as designation, hod_cgpa.id,hod_cgpa.status,hod_cgpa.signature,fname, lname, initial FROM hod_cgpa INNER JOIN title ON hod_cgpa.title=title.id INNER JOIN designation ON hod_cgpa.designation=designation.id WHERE dept_new = $dept ORDER BY hod_cgpa.id DESC";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function getTitle($conn)
{
    $sql = "SELECT * FROM title";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function getDesig($conn)
{
    $sql = "SELECT * FROM designation";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function getTitleforAudit($conn, $id)
{
    $sql = "SELECT title FROM title WHERE id = $id";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($query);
    $title = $row['title'];

    return $title;
}

function getDepartmentforAudit($conn, $id)
{
    $sqlDept = "SELECT department FROM dept_new WHERE id = $id";
    $queryDept = mysqli_query($conn, $sqlDept);
    $row = mysqli_fetch_assoc($queryDept);
    $department = $row['department'];

    return $department;
}

function getDesigforAudit($conn, $id)
{
    $sql = "SELECT title FROM designation WHERE id = $id";
    $query = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($query);
    $designation = $row['title'];

    return $designation;
}

function addHodWithSignature($conn, $title, $lname, $fname, $initial, $designation, $dept, $filename, $tempname, $folder, $admin)
{


    $sql = "INSERT INTO hod_cgpa (title,lname,fname,initial,designation,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$designation','$dept','$filename')";
    $query = mysqli_query($conn, $sql);

    $titleInWord = getTitleforAudit($conn, $title);
    $designationInWord = getDesigforAudit($conn, $designation);
    $departmentInWord = getDepartmentforAudit($conn, $dept);
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Hod ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord as $designationInWord')";

    $queryAudit = mysqli_query($conn, $sqlAudit);
    if ((move_uploaded_file($tempname, $folder)) && $query && $queryAudit) {
        echo "<script>alert('External Examiner Added Successfully With Signature')</script>";
        // echo "<script>location.replace('../dashboard.php?p=ex')</script>";
    } else {
        echo "error" . mysqli_error($conn);
    }
}
function addHod($conn, $title, $lname, $fname, $initial, $designation, $dept, $admin)
{


    $sql = "INSERT INTO hod_cgpa (title,lname,fname,initial,designation,dept_new) VALUES ('$title','$lname','$fname','$initial','$designation','$dept')";
    $query = mysqli_query($conn, $sql);

    $titleInWord = getTitleforAudit($conn, $title);
    $designationInWord = getDesigforAudit($conn, $designation);
    $departmentInWord = getDepartmentforAudit($conn, $dept);
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Hod ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord as $designationInWord')";

    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (!($query && $queryAudit)) {
        echo "Unable to Add Hod";
    } else {
        echo "<script>alert('Hod Added Successfully')</script>";
    }
}

function statusHod($conn)
{
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];
    $admin = $_GET['admin'];
    $dept = $_GET['dept'];
    if ($status == 1) {
        $flag = 0;
        $queryeCount = "SELECT COUNT(status) AS NumberOfEnable FROM hod_cgpa WHERE status = $flag AND dept_new = $dept";
        $resulteCount = mysqli_query($conn, $queryeCount);
        $row = mysqli_fetch_assoc($resulteCount);
        $count = $row['NumberOfEnable'];

        if ($count < 1) {
            $querye2 = "UPDATE hod_cgpa SET `status` = $flag WHERE id = $user_id";
            $resulte2 = mysqli_query($conn, $querye2);

            $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Hod with ID: $user_id to Enable')";
            $queryAudit = mysqli_query($conn, $sqlAudit);



            if ($resulte2 && $queryAudit) {

                echo "<script>alert('Hod Enabled Successfully')</script>";
                echo "<script>location.replace('../dashboard.php?p=enable')</script>";
            } else echo mysqli_error($conn);
        } elseif ($count >= 1) {

            echo "<script>alert('You can not Enable more than one External Examiner')</script>";
            echo "<script>location.replace('../dashboard.php?p=enable')</script>";
        }
    } elseif ($status == 0) {
        $flag = 1;
        $querye2w = "UPDATE hod_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn, $querye2w);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Hod with ID: $user_id to Disable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2w && $queryAudit) {

            echo "<script>alert('Hod Disabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=enable')</script>";
        } else echo mysqli_error($conn);
    }
}




function updateHodWithSignature($conn, $title, $lname, $fname, $initial, $user, $filename, $tempname, $folder, $admin, $designation)
{
    $queryUp = "UPDATE hod_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',designation = '$designation',`signature` = '$filename' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);

    $titleInWord = getTitleforAudit($conn, $title);
    $designationInWord = getDesigforAudit($conn, $designation);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Hod with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname , initial =$initial and designation = $designationInWord)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ((move_uploaded_file($tempname, $folder)) && $resultUp && $queryAudit) {

        echo "<script>alert('Hod Updated Successfully With Signature')</script>";
        echo "<script>location.replace('../dashboard.php?p=hod')</script>";
    } else echo mysqli_error($conn);
}


function updateHod($conn, $title, $lname, $fname, $initial, $designation, $user, $admin)
{
    $queryUp = "UPDATE hod_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',designation = '$designation' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);

    $titleInWord = getTitleforAudit($conn, $title);
    $designationInWord = getDesigforAudit($conn, $designation);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Hod with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname , initial =$initial and designation = $designationInWord)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ($resultUp && $queryAudit) {

        echo "<script>alert('Hod Updated Successfully')</script>";
        echo "<script>location.replace('../dashboard.php?p=hod')</script>";
    } else echo mysqli_error($conn);
}

// Sub-Dean functions start

function displaySubdean($conn, $dept)
{
    $sql = "SELECT title.title,subdean_cgpa.id,subdean_cgpa.status,fname,`signature`, lname, initial FROM subdean_cgpa INNER JOIN title ON subdean_cgpa.title=title.id WHERE dept_new = $dept ORDER BY subdean_cgpa.id DESC";
    $query = mysqli_query($conn, $sql);

    return $query;
}


function statusSubDean($conn)
{
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];
    $admin = $_GET['admin'];
    $dept = $_GET['dept'];
    if ($status == 1) {
        $flag = 0;

        $queryeCount = "SELECT COUNT(status) AS NumberOfEnable FROM subdean_cgpa WHERE status = $flag AND dept_new = $dept";
        $resulteCount = mysqli_query($conn, $queryeCount);
        $row = mysqli_fetch_assoc($resulteCount);
        $count = $row['NumberOfEnable'];

        if ($count < 1) {

            $querye2 = "UPDATE subdean_cgpa SET `status` = $flag WHERE id = $user_id";
            $resulte2 = mysqli_query($conn, $querye2);

            $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Sub-Dean with ID: $user_id to Enable')";
            $queryAudit = mysqli_query($conn, $sqlAudit);

            if ($resulte2 && $queryAudit) {

                echo "<script>alert('Sub-Dean Enabled Successfully')</script>";
                echo "<script>location.replace('../dashboard.php?p=disablesd')</script>";
            } else echo mysqli_error($conn);
        } elseif ($count >= 1) {

            echo "<script>alert('You can not Enable more than one Subdean')</script>";
            echo "<script>location.replace('../dashboard.php?p=disablesd')</script>";
        }
    } elseif ($status == 0) {
        $flag = 1;
        $querye2w = "UPDATE subdean_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn, $querye2w);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of Sub-Dean with ID: $user_id to Disable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2w && $queryAudit) {
            echo "<script>alert('Sub-Dean Disabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=disablesd')</script>";
        } else echo mysqli_error($conn);
    }
}



function addSubdean($conn, $title, $lname, $fname, $initial, $dept, $admin)
{

    $sql = "INSERT INTO subdean_cgpa (title,lname,fname,initial,dept_new) VALUES ('$title','$lname','$fname','$initial','$dept')";
    $query = mysqli_query($conn, $sql);

    $titleInWord = getTitleforAudit($conn, $title);

    $departmentInWord = getDepartmentforAudit($conn, $dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Subdean ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord')";
    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (!($query && $queryAudit)) {
        echo "Unable to Add Sub-Dean";
    } else {
        echo "<script>alert('Sub-Dean Added Successfully')</script>";
    }
}
function addSubdeanWithSignature($conn, $title, $lname, $fname, $initial, $dept, $filename, $tempname, $folder, $admin)

{

    $sql = "INSERT INTO subdean_cgpa (title,lname,fname,initial,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$dept','$filename')";
    $query = mysqli_query($conn, $sql);

    $titleInWord = getTitleforAudit($conn, $title);

    $departmentInWord = getDepartmentforAudit($conn, $dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New Subdean ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ((move_uploaded_file($tempname, $folder)) && $query && $queryAudit) {
        echo "<script>alert('Sub-Dean Added Successfully With Signature')</script>";
    } else {
        echo "error" . mysqli_error($conn);
    }
    
}

function updateSubdean($conn, $title, $lname, $fname, $initial, $user, $admin)
{
    $queryUp = "UPDATE subdean_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);
    $titleInWord = getTitleforAudit($conn, $title);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Sub-Dean with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ($resultUp && $queryAudit) {

        echo "<script>alert('Sub-Dean Updated Successfully')</script>";
        echo "<script>location.replace('../dashboard.php?p=disablesd')</script>";
    } else echo mysqli_error($conn);
}
function updateSubdeanWithSignature($conn, $title, $lname, $fname, $initial, $filename, $tempname, $folder, $user, $admin)

{
    $queryUp = "UPDATE subdean_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',`signature` = '$filename' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);
    $titleInWord = getTitleforAudit($conn, $title);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update Sub-Dean with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ((move_uploaded_file($tempname, $folder)) && $resultUp && $queryAudit) {

        echo "<script>alert('Sub-Dean Updated Successfully With Signature')</script>";
    } else echo mysqli_error($conn);

}


// External functions start

function displayExternal($conn, $dept)
{
    $sql = "SELECT title.title,external_cgpa.id,external_cgpa.status,fname, lname, initial,external_cgpa.signature FROM external_cgpa INNER JOIN title ON external_cgpa.title=title.id WHERE dept_new = $dept ORDER BY external_cgpa.id DESC";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function statusExternal($conn)
{
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];
    $admin = $_GET['admin'];

    if ($status == 1) {
        $flag = 0;
        $querye2 = "UPDATE external_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn, $querye2);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of External Examiner with ID: $user_id to Enable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2 && $queryAudit) {

            echo "<script>alert('External Examiner Enabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=enableex')</script>";
        } else echo mysqli_error($conn);
    } elseif ($status == 0) {
        $flag = 1;
        $querye2w = "UPDATE external_cgpa SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn, $querye2w);
        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Change status of External Examiner with ID: $user_id to Disable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2w && $queryAudit) {

            echo "<script>alert('External Examiner Disabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=disableex')</script>";
        } else echo mysqli_error($conn);
    }
}


function addExternal($conn, $title, $lname, $fname, $initial, $dept, $filename, $tempname, $folder, $admin)
{

    $sql = "INSERT INTO external (title,lname,fname,initial,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$dept','$filename')";
    $query = mysqli_query($conn, $sql);
    $titleInWord = getTitleforAudit($conn, $title);

    $departmentInWord = getDepartmentforAudit($conn, $dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New External Examiner ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord with signature: $filename')";
    $queryAudit = mysqli_query($conn, $sqlAudit);


    if ((move_uploaded_file($tempname, $folder)) && $query && $queryAudit) {
        echo "<script>alert('External Examiner Added Successfully With Signature')</script>";
        echo "<script>location.replace('../dashboard.php?p=ex')</script>";
    } else {
        echo "error" . mysqli_error($conn);
    }
}

function addExternalNoSign($conn, $title, $lname, $fname, $initial, $dept, $filename, $admin)
{

    $sql = "INSERT INTO external (title,lname,fname,initial,dept_new,`signature`) VALUES ('$title','$lname','$fname','$initial','$dept','$filename')";
    $query = mysqli_query($conn, $sql);

    $titleInWord = getTitleforAudit($conn, $title);
    $departmentInWord = getDepartmentforAudit($conn, $dept);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Add New External Examiner ($titleInWord ' ' $lname ' ' $fname ' ' $initial) for $departmentInWord with no Signature')";
    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (!($query && $queryAudit)) {

        echo "<script>alert('External Examiner Added Successfully With No Signature')</script>";
    } else {
        echo "error" . mysqli_error($conn);
    }
}

function updateExternal($conn, $title, $lname, $fname, $initial, $user, $filename, $tempname, $folder, $admin)
{
    $queryUp = "UPDATE external_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial',`signature` = '$filename' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);

    $titleInWord = getTitleforAudit($conn, $title);
    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update External Examiner with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial) with signature: $filename')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ((move_uploaded_file($tempname, $folder)) && $resultUp && $queryAudit) {

        echo "<script>alert('External Supervisor Updated Successfully With Signature')</script>";
    } else echo mysqli_error($conn);
}

function updateExternalNoSign($conn, $title, $lname, $fname, $initial, $user, $admin)
{
    $queryUp = "UPDATE external_cgpa SET title = '$title',lname = '$lname',fname = '$fname',initial = '$initial' WHERE id = '$user'";
    $resultUp = mysqli_query($conn, $queryUp);
    $titleInWord = getTitleforAudit($conn, $title);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update External Examiner with ID: $user and set (title = $titleInWord , Surname = $lname , Firstname = $fname and initial =$initial) with no signature')";
    $queryAudit = mysqli_query($conn, $sqlAudit);

    if ($resultUp && $queryAudit) {

        echo "<script>alert('External Supervisor Updated Successfully With No Signature')</script>";
        echo "<script>location.replace('../dashboard.php?p=ex')</script>";
    } else echo mysqli_error($conn);
}

// Head ICT USERS functions start

function displayUsers($conn)
{
    $sql = "SELECT * FROM users_cgpa_new ORDER BY id DESC";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function statusUsers($conn, $user_id, $status, $user)
{

    if ($status == 1) {
        $flag = 0;
        $querye2 = "UPDATE users_cgpa_new SET `status` = $flag WHERE id = $user_id";
        $resulte2 = mysqli_query($conn, $querye2);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$user','Change status of User with ID: $user_id to Enable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2 && $queryAudit) {
            // dashboard.php?p=head&user=' . $user

            echo "<script>alert('Users Enabled Successfully')</script>";
            echo "<script>location.replace('dashboard.php?p=head&user=$user')</script>";
        } else echo mysqli_error($conn);
    } elseif ($status == 0) {
        $flag = 1;
        $querye2w = "UPDATE users_cgpa_new SET `status` = $flag WHERE id = $user_id";
        $resulte2w = mysqli_query($conn, $querye2w);

        $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$user','Change status of User with ID: $user_id to Disable')";
        $queryAudit = mysqli_query($conn, $sqlAudit);

        if ($resulte2w && $queryAudit) {
            echo "<script>alert('Users Disabled Successfully')</script>";
            echo "<script>location.replace('dashboard.php?p=head&user=$user')</script>";
        } else echo mysqli_error($conn);
    }
}

function getUsersDepartment($conn, $dept_id)
{
    $sql = "SELECT department FROM dept_new WHERE id = $dept_id";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $dept = $row['department'];
    } else {
        $dept = "<span style='color:#dc3545;'>No Department for this User</span>";
    }

    return $dept;
}

function getDepartment($conn)
{
    $sql = "SELECT * FROM dept_new";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function addUser($conn, $name, $username, $password, $department, $user)
{

    $sql = "INSERT INTO users_cgpa_new (name,username,password,dept_new) VALUES ('$name','$username','$password','$department')";
    $query = mysqli_query($conn, $sql);

    //Get Department Title for the Department ID

    $departmentInWord = getDepartmentforAudit($conn, $department);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$user','Add New User (Name:$name, Username:$username, Password:$password, Department:$departmentInWord)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (!($query && $queryAudit)) {
        echo "Unable to Add User";
    } else {
        echo "<script>alert('User Added Successfully')</script>";
    }
}

function updateUser($conn, $name, $username, $password, $department, $id, $user)
{
    $queryUp = "UPDATE users_cgpa_new SET name = '$name',username = '$username',password = '$password',dept_new = '$department' WHERE id = '$id'";
    $resultUp = mysqli_query($conn, $queryUp);

    //Get Department Title for the Department ID
    $departmentInWord = getDepartmentforAudit($conn, $department);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$user','Update User with ID:$id and set (Name:$name, Username:$username, Password:$password, Department:$departmentInWord)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (($resultUp && $queryAudit)) {

        echo "<script>alert('User Updated Successfully')</script>";
    } else echo mysqli_error($conn);
}

function displaySection($conn)
{
    $sql = "SELECT * FROM sec_examined ORDER BY id DESC";
    $query = mysqli_query($conn, $sql);

    return $query;
}

function addSection($conn, $section, $user)
{

    $sql = "INSERT INTO sec_examined (sec) VALUES ('$section')";
    $query = mysqli_query($conn, $sql);

    $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$user','Add New Section ($section)')";
    $queryAudit = mysqli_query($conn, $sqlAudit);
    if (!($query && $queryAudit)) {
        echo "Unable to Add Section";
    } else {
        echo "<script>alert('Section Added Successfully')</script>";
    }
}

function resultheader($conn, $dept, $field, $degree, $sec, $effectivedate, $external, $resulttype)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = "<h2 align=center>University of Ibadan</h2>";
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    // echo "<h3 align=center>";
    echo "
    <a href='pdf.php?degree=$degree&field=$field&effectivedate=$effectivedate&external=$external&resulttype=$resulttype&sec=$sec'>
    <img src='images/save-as-pdf1.gif'/>
    </a>";
    echo "<div class='head'>";
    echo "<div class='first'>";
    echo "<img align=center src=images/logged2.png width=100 height=80 alt=No Signature for this Examiner>";
    echo "</div>";
    echo "<div class='text' align=center>";
    echo strtoupper($header1);
    echo "<h3 align=center>" . strtoupper("faculty of " . $header2) . "</h3>";
    echo "<h3 align=center>" . strtoupper("Department of " . $header3) . "</h3>";
    echo "<h3 align=center>" . strtoupper($naration . " Degree Examination Results " . $sec . " Session") . "</h3>";
    echo "<h3 align=center>" . strtoupper("Area of specialization: " . $header4) . "</h3>";
    echo "</div>";
    echo "<div class='second'>";
    echo "<img style=float:right src=images/logo.png width=100 height=80 alt=No Signature for this Examiner>";
    echo "</div>";
    echo "</div>";
}
function resulBoardtheader($conn, $dept, $field, $degree, $sec, $effectivedate, $external, $resulttype)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = "<h2 align=center>University of Ibadan</h2>";
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    // echo "<h3 align=center>";
    echo "
    <a href='pdfBoard.php?degree=$degree&field=$field&effectivedate=$effectivedate&external=$external&resulttype=$resulttype&sec=$sec'>
    <img src='images/save-as-pdf1.gif'/>
    </a>";
    echo "<div class='head'>";
    echo "<div class='first'>";
    echo "<img align=center src=images/logged2.png width=100 height=80 alt=No Signature for this Examiner>";
    echo "</div>";
    echo "<div class='text' align=center>";
    echo strtoupper($header1);
    echo "<h3 align=center>" . strtoupper("faculty of " . $header2) . "</h3>";
    echo "<h3 align=center>" . strtoupper("Department of " . $header3) . "</h3>";
    echo "<h3 align=center>" . strtoupper($naration . " Degree Examination Results " . $sec . " Session") . "</h3>";
    echo "<h3 align=center>" . strtoupper("Area of specialization: " . $header4) . "</h3>";
    echo "</div>";
    echo "<div class='second'>";
    echo "<img style=float:right src=images/logo.png width=100 height=80 alt=No Signature for this Examiner>";
    echo "</div>";
    echo "</div>";
}
function resultHeaderPDF($conn, $dept, $field, $degree, $sec)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = '<h2 align=center>University of Ibadan</h2>';
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    $msg = '<table><tr>';
    $msg .= '<td>';
    $msg .= '<img align=center src=images/logged2.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<td align=center>';
    $msg .= strtoupper($header1);
    $msg .= '<h3 align=center>' . strtoupper('faculty of ' . $header2) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Department of ' . $header3) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper($naration . ' Degree Examination Results ' . $sec . ' Session') . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Area of specialization: ' . $header4) . '</h3>';
    $msg .= '</td>';
    $msg .= '<td>';
    $msg .= '<img style=float:right src=images/logo.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<tr></table>';

    return $msg;
}
function resultHeaderPDF2($conn, $dept, $field, $degree, $sec)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = '<h2 align=center>University of Ibadan</h2>';
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    $msg = '<table><tr>';
    $msg .= '<td>';
    $msg .= '<img align=center src=../images/logged2.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<td align=center>';
    $msg .= strtoupper($header1);
    $msg .= '<h3 align=center>' . strtoupper('faculty of ' . $header2) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Department of ' . $header3) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper($naration . ' Degree Examination Results ' . $sec . ' Session') . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Area of specialization: ' . $header4) . '</h3>';
    $msg .= '</td>';
    $msg .= '<td>';
    $msg .= '<img style=float:right src=../images/logo.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<tr></table>';

    return $msg;
}
function GradListHeaderPDF($conn, $dept, $field, $degree, $sec)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = '<h2 align=center>University of Ibadan</h2>';
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    $msg = '<table><tr>';
    $msg .= '<td>';
    $msg .= '<img align=center src=images/logged2.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<td align=center>';
    $msg .= strtoupper($header1);
    $msg .= '<h3 align=center>' . strtoupper('faculty of ' . $header2) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Department of ' . $header3) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper($naration . ' Degree Examination Results <br>' . $sec . ' Session') . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Area of specialization: ' . $header4) . '</h3>';
    $msg .= '<br>';
    $msg .= '<h2 align=center> LIST OF GRADUATING STUDENTS</h2>';
    $msg .= '</td>';
    $msg .= '<td>';
    $msg .= '<img style=float:right src=images/logo.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<tr></table>';

    return $msg;
}
function RegStatusHeaderPDF($conn, $dept, $field, $degree, $sec)
{
    $sel = mysqli_query($conn, "select fac_new.faculty,dept_new.department,field_new.field_title from fieldofinterest5 inner join field_new on fieldofinterest5.field=field_new.id inner join dept_new on dept_new.id=fieldofinterest5.dept inner join fac_new on fac_new.id=fieldofinterest5.fac where fieldofinterest5.dept='$dept' and fieldofinterest5.field='$field'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($sel);

    $sel = mysqli_query($conn, "select naration from rendition where dept='$dept' and specialization='$field' and degree='$degree'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_array($sel);
    $naration = $row2['naration'];
    $header1 = '<h2 align=center>University of Ibadan</h2>';
    $header2 = $row['faculty'];
    $header3 = $row['department'];
    $header4 = $row['field_title'];
    $msg = '<table><tr>';
    $msg .= '<td>';
    $msg .= '<img align=center src=images/logged2.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<td align=center>';
    $msg .= strtoupper($header1);
    $msg .= '<h3 align=center>' . strtoupper('faculty of ' . $header2) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Department of ' . $header3) . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper($naration . ' Degree Examination Results <br>' . $sec . ' Session') . '</h3>';
    $msg .= '<h3 align=center>' . strtoupper('Area of specialization: ' . $header4) . '</h3>';
    $msg .= '<br>';
    $msg .= '<h2 align=center> REGISTRATION STATUS</h2>';
    $msg .= '</td>';
    $msg .= '<td>';
    $msg .= '<img style=float:right src=images/logo.png width=100 height=80 alt=No Signature for this Examiner>';
    $msg .= '</td>';
    $msg .= '<tr></table>';

    return $msg;
}


function getStudentResults($conn, $matric, $effectivedate, $dept)
{

    $query = "SELECT course_new.course_code,studentrecord.name,testscore.cozid,testscore.cstatus,testscore.cunit,testscore.score FROM testscore INNER JOIN course_new ON course_new.id  = testscore.cozid INNER JOIN studentrecord ON studentrecord.user_id = testscore.user_id WHERE testscore.matric = '$matric' AND testscore.effectivedate = '$effectivedate' AND testscore.dept = '$dept'";
    $result = mysqli_query($conn, $query);

    return $result;
}
function getStudentResultsWithCode($conn, $code, $effectivedate, $dept)
{

    $query = "SELECT testscore.matric,testscore.score,studentrecord.name FROM testscore INNER JOIN studentrecord ON studentrecord.user_id=testscore.user_id WHERE testscore.cozid = '$code' AND testscore.effectivedate = '$effectivedate' AND testscore.dept = '$dept'";
    $result = mysqli_query($conn, $query);

    return $result;
}


function statusCourse($conn)
{
    $id = $_GET['id'];
    $status = $_GET['status'];

    $dept = $_GET['dept'];
    if ($status == 1) {
        $flag = 0;

        $querye2 = "UPDATE course_new SET `status2` = $flag WHERE id = $id AND dept_newids = $dept";
        $resulte2 = mysqli_query($conn, $querye2);


        if ($resulte2) {

            echo "<script>alert('Course Enabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=viewcourse')</script>";
        } else echo mysqli_error($conn);
    } elseif ($status == 0) {
        $flag = 1;
        $querye2w = "UPDATE course_new SET `status2` = $flag WHERE id = $id AND dept_newids = $dept";
        $resulte2w = mysqli_query($conn, $querye2w);



        if ($resulte2w) {

            echo "<script>alert('Course Disabled Successfully')</script>";
            echo "<script>location.replace('../dashboard.php?p=viewcourse')</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
}

function processGradutingList($conn, $field, $effectivedate, $dept)
{

    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
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


    $sql = "SELECT DISTINCT t.matric,f.field_title ,t.effectivedate, n.Surname,n.Other_names
            FROM testscore t
            LEFT JOIN new n ON t.user_id = n.id LEFT JOIN field_new f ON t.field = f.id LEFT JOIN remark r ON t.matric = r.matric LEFT JOIN new ON new.id = t.user_id LEFT JOIN reginvoice ON reginvoice.appno = new.numeration where t.field='$field' and t.effectivedate='$effectivedate' and t.dept= '$dept' and r.remark<>'NG' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
            ORDER BY n.Surname"; // Ordering by matric, course_code, and exam_sec
    $result = $conn->query($sql);

    $table = '<table table width=100% class=custom-table   align=center>';

    $table .= '<tr>';
    $table .= '<th style=font-weight:bold >S/N</th>';
    $table .= '<th style=font-weight:bold >Matric No.</th>';
    $table .= '<th style=font-weight:bold >Candidate Fullname</th>';
    $table .= '<th style=font-weight:bold >Area of Specialisation</th>';
    $table .= '<th style=font-weight:bold >Effective Date of Award</th>';
    $table .= '</tr>';

    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        $dateString = $row['effectivedate'];
        $date = new DateTime($dateString);
        $formattedDate = $date->format('d F, Y');


        $table .= '<tr>';
        $table .= '<td>' . $i++ . '</td>';
        $table .= '<td>' . $row['matric'] . '</td>';
        $table .= '<td>' . strtoupper($row['Surname']) . ' ' . $row['Other_names'] . '</td>';
        $table .= '<td>' . $row['field_title'] . '</td>';
        $table .= '<td>' . $formattedDate . '</td>';
        $table .= '</tr>';
    }
    $table .= '</table>';


    $table .=  "
    <br><br>
    <table class='foot'>
    
    <tr>
    
    <td align=center>
    
    <br>
                <p  style=color:#CD853F;font-weight:bold>
                _______________________________
                </p>
                <p>
                
    
                    <h4>$hod_name<br> $hod_desig</h4> 
                </p>
    </td>
    
    <td style=color:white align=center>
    ------------------------------------------------------------------------------------------------------------------------------------------------------------
    </td>
    
    <td align=center style=float:right>
   
                <p  style=color:#CD853F;font-weight:bold>
                _______________________________
                </p>
                    <p>
                        <h4>Date</h4> 
                    </p>
    </td>
   
    </tr>
    
    
    </table>
        
    ";

    return $table;
}
function regStausList($conn, $field, $effectivedate, $dept)
{

    $hod_query = "SELECT * FROM hod_cgpa WHERE dept_new = $dept AND status = 0";
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

    $sql = "SELECT DISTINCT t.matric,t.user_id,f.field_title ,t.effectivedate, n.Surname, n.numeration,n.Other_names,r.remark
            FROM testscore t
            LEFT JOIN new n ON t.user_id = n.id LEFT JOIN field_new f ON t.field = f.id LEFT JOIN remark r ON t.matric = r.matric LEFT JOIN new ON new.id = t.user_id LEFT JOIN reginvoice ON reginvoice.appno = new.numeration where t.field='$field' and t.effectivedate='$effectivedate' and t.dept= '$dept' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0
            ORDER BY n.Surname"; // Ordering by matric, course_code, and exam_sec
    $result = $conn->query($sql);

    $table = '<table table width=100% class=custom-table   align=center>';

    $table .= '<tr>';
    $table .= '<th style=font-weight:bold >S/N</th>';
    $table .= '<th style=font-weight:bold >Matric No.</th>';
    $table .= '<th style=font-weight:bold >Name</th>';
    $table .= '<th style=font-weight:bold >Date of First Registration for the Current Programme</th>';
    $table .= '<th style=font-weight:bold >Date of Registration for the Session of Graduation</th>';
    $table .= '<th style=font-weight:bold >Has the Candidate Completed the Programme?</th>';
    $table .= '<th style=font-weight:bold >If yes,Effective Date of Award</th>';
    $table .= '</tr>';

    $i = 1;
    while ($row = mysqli_fetch_array($result)) {
        $dateString = $row['effectivedate'];
        $date = new DateTime($dateString);
        $formattedDate = $date->format('d F, Y');
        $remark = $row['remark'];
        $appno = $row['numeration'];

        $sql_invoice = "SELECT paid_time FROM reginvoice WHERE appno = '$appno' and invoice_flag = '0' ";
        $result_invoice = $conn->query($sql_invoice);
        $row_invoice = mysqli_fetch_array($result_invoice);

        $sql_coz = "SELECT lockupdate FROM reg_coz WHERE appno = '$appno'";
        $result_coz = $conn->query($sql_coz);
        $row_coz = mysqli_fetch_array($result_coz);

        $dateCurr = $row_invoice['paid_time'];
        $date2 = new DateTime($dateCurr);
        $formattedCurr = $date2->format("d/m/Y");


        $dateCoz = $row_coz['lockupdate'];
        $dateCoz = new DateTime($dateCoz);
        $formattedCoz = $dateCoz->format("d/m/Y");


        $table .= '<tr>';
        $table .= '<td>' . $i++ . '</td>';
        $table .= '<td>' . $row['matric'] . '</td>';
        $table .= '<td>' . $row['Other_names'] . ' ' . strtoupper($row['Surname']) . '</td>';
        $table .= '<td>' . $formattedCurr . '</td>';
        $table .= '<td>' . $formattedCoz . '</td>';
        if ($remark == 'NG') {

            $table .= '<td>NO</td>';
            $table .= '<td> - </td>';
        } else {
            $table .= '<td>YES</td>';
            $table .= '<td>' . $formattedDate . '</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</table>';


    $table .=  "
    <br><br>
    <table class='foot'>
    
    <tr>
    
    <td align=center>
    
    <br>
                <p  style=color:#CD853F;font-weight:bold>
                _______________________________
                </p>
                <p>
                
    
                    <h4>$hod_name<br> $hod_desig</h4> 
                </p>
    </td>
    
    <td style=color:white align=center>
    ------------------------------------------------------------------------------------------------------------------------------------------------------------
    </td>
    
    <td align=center style=float:right>
   
                <p  style=color:#CD853F;font-weight:bold>
                _______________________________
                </p>
                    <p>
                        <h4>Date</h4> 
                    </p>
    </td>
   
    </tr>
    
    
    </table>
        
    ";

    return $table;
}

function processResultReal($conn, $dept, $degree, $field, $effectivedate, $external, $resulttype, $sec,$mode)
{
    // Check the status of the result
    $checkQuery = "SELECT DISTINCT status,fac,field FROM testscore WHERE dept = '$dept' AND degree = '$degree' AND field = '$field' AND effectivedate = '$effectivedate' AND external = '$external'";
    $result = mysqli_query($conn, $checkQuery);
    if (!$result) {
        return mysqli_error($conn);
    }
    $row = mysqli_fetch_assoc($result);
    $status = $row['status'];
    $facId = $row['fac'];
    $fieldId = $row['field'];

    $sel = mysqli_query($conn, "select faculty from fac_new where id='$facId'") or die(mysqli_error($conn));
    $rowFac = mysqli_fetch_array($sel);

    $selDept = mysqli_query($conn, "select department from dept_new where id='$dept'") or die(mysqli_error($conn));
    $rowDept = mysqli_fetch_array($selDept);

    $selField = mysqli_query($conn, "select field_title from field_new where id='$fieldId'") or die(mysqli_error($conn));
    $rowField = mysqli_fetch_array($selField);

    if ($mode == 0) {

        $rMode = "Part-Time";
    } else if ($mode == 1) {
        $rMode = "Full-Time";
    }

    if ($resulttype == 0) {

        $rType = "Main Result";
    } else if ($resulttype == 1) {
        $rType = "Supplementary Result";
    }
    // Update the result if the status is '0'
    if ($status === '0') {
        $updatedSql = "UPDATE testscore SET session_of_grad = '$sec', resulttype = '$resulttype' WHERE dept = '$dept' AND degree = '$degree' AND field = '$field' AND effectivedate = '$effectivedate' AND external = '$external' AND mode = '$mode'";
        $updateResult = $conn->query($updatedSql);
        if (!$updateResult) {
            return mysqli_error($conn);
        } else {
            // Create the directories if they do not exist
            $projectDir = dirname(__DIR__);
            $ProcessedBoardResultDir = $projectDir . '/ProcessedBoardResult';
            $facultyDir = $ProcessedBoardResultDir . '/' . $rowFac['faculty'];
            $departmentDir = $facultyDir . '/' . $rowDept['department'];
            $specializationDir = $departmentDir . '/' . $rowField['field_title'];
            $mainResultDir = $specializationDir . '/' . $rType;
            $ModeOfStudyDir = $mainResultDir . '/' . $rMode;

            if (!is_dir($ProcessedBoardResultDir)) {
                mkdir($ProcessedBoardResultDir, 0777, true);
            }
            if (!is_dir($facultyDir)) {
                mkdir($facultyDir, 0777, true);
            }
            if (!is_dir($departmentDir)) {
                mkdir($departmentDir, 0777, true);
            }
            if (!is_dir($specializationDir)) {
                mkdir($specializationDir, 0777, true);
            }
          
            if (!is_dir($mainResultDir)) {
                mkdir($mainResultDir, 0777, true);
            }
            if (!is_dir($ModeOfStudyDir)) {
                mkdir($ModeOfStudyDir, 0777, true);
            }

            // Load MPDF
            require "../vendor/autoload.php";
            $mpdf = new \Mpdf\Mpdf([
                "mode" => "utf-8",
                "orientation" => "L",
                "format" => "A4-L"
            ]);

            // Generate the PDF content
            $html = "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Document</title>
                <style>
                    @page {size: 29.7cm 42cm; margin: 5mm}
                    div.page {page-break-after:always}
                </style>
                <style>
                   h2, h3 {
                    margin: 0; /* Remove default margin */
                    line-height: 1.5; /* Set line height to 1.5 */
                }
            </style>
            <style>
                .custom-table {
                    text-align: center;
                    font-family: Arial Black;
                    font-size: 13px;
                    border-collapse: collapse;
                    border-spacing: 0;
                }
                .custom-table th, .custom-table td {
                    border: 1px solid #000;
                }
                .custom-table th {
                    height: 50px;
                    padding: 8px; /* Adjust padding as needed */
                    width: 55px;
                    font-weight: normal;
                }
                tr:nth-child(even) { background-color: #f2f2f2; }
                tr:hover { background-color: #ddd; }
                .codea {
                    -webkit-transform: rotate(-90deg);
                    -ms-transform: rotate(-90deg);
                    -o-transform: rotate(-90deg);
                    transform: rotate(-90deg);
                }
            </style>
            <style>
                div.image {
                    background: url(../images/logged2.png) no-repeat center;
                }
                div.transparentbox {
                    background-color: #ffffff;
                    opacity: 0.8;
                }
                div.transparentbox p {
                    font-weight: bold;
                    color: #CD853F;
                }
                .style2 {
                    font-size: 13px;
                    font-weight: bold;
                }
                body, td, th {
                    color: #000000;
                    font-family: Arial Black;
                    font-weight: normal;
                    font-size: 12px;
                }
                .foot .container .row .sign {
                    width: 10rem !important;
                    height: 5rem !important;
                    border-bottom: 2px solid #CD853F;
                }
                .foot .container .row .non {
                    visibility: hidden;
                }
                .foot .line {
                    font-size: 2rem;
                    font-weight: bolder;
                    color: #CD853F;
                    margin: 0 !important;
                    padding: 0 !important;
                }
                .foot .container .row {
                    display: flex;
                    justify-content: space-around;
                    align-items: center;
                    text-align: center;
                }
                @media print {
                    @page { size: landscape; }
                    * {
                        -webkit-print-color-adjust: exact !important; /* Chrome, Safari */
                        color-adjust: exact !important; /* Firefox */
                    }
                }
                .sign{
                    width: 10rem !important;
                    height: 5rem !important;
                    border-bottom: 2px solid #CD853F;
                  }
            </style>
            </head>
            <body>
            <div>
            <div style='opacity:0.8;background:url(../images/logoover.png);background-repeat:no-repeat;background-position:center;' class='transparentbox'>
            ";

            $html .= resultHeaderPDF2($conn, $dept, $field, $degree, $sec);
            $html .= processBoardResultPDF2($conn, $field, $effectivedate, $external, $dept, $resulttype,$mode);

            $html .= "</div></div>
            </body>
            </html>";

            // Write HTML to PDF
            $mpdf->WriteHTML($html);

            // Save the PDF in the mainresult folder
            $pdfFilePath = $ModeOfStudyDir . "/BroadSheet.pdf";
            $mpdf->Output($pdfFilePath, "F");

            // Return success message
            return "Success";
        }
    } else {
        return "Error";
    }
}
function setFacultyMeetingDate($effective, $facDate, $conn)
{
    $sql = "UPDATE testscore SET faculty_date = '$facDate' WHERE effectivedate = '$effective'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Unable to Set Faculty Meeting Date" . mysqli_error($conn);
    } else {
        echo "<script>
    
    
                                    // Check if the page has already reloaded
                                    if (!localStorage.getItem('reloaded')) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Successful',
                                            text: 'Date Set Successfully',
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(function() {
                                            // Set the reloaded flag in local storage
                                            localStorage.setItem('reloaded', 'true');
                                            location.reload();
                                        });
                                    } else {
                                        // Clear the reloaded flag for future logins
                                        localStorage.removeItem('reloaded');
                                    }
                                </script>";
    }
}


?>


<?php

if ((isset($_GET['user_id'])) && (isset($_GET['status'])) && (isset($_GET['hod']))) {
    statusHod($conn);
}

if ((isset($_GET['user_id'])) && (isset($_GET['status'])) && (isset($_GET['subdean']))) {
    statusSubDean($conn);
}
if ((isset($_GET['user_id'])) && (isset($_GET['status'])) && (isset($_GET['external']))) {
    statusExternal($conn);
}

if ((isset($_GET['id'])) && (isset($_GET['status'])) && (isset($_GET['course']))) {
    statusCourse($conn);
}

?>