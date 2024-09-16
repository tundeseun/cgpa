<?php
session_start();
$dept = $_SESSION["dept_new"];
$name = $_SESSION["name"];
include_once('../function/script.php');

if (!($dept && $name)) {
    session_destroy();
    header('Location: ../');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../');
}

$query = "SELECT course_new.id AS id,course_code,course_title,unit,status,status2,field_new.field_title AS specialization FROM course_new INNER JOIN field_new ON field_new.id = course_new.specialization WHERE course_new.dept_newids = '$dept' ORDER BY course_new.id DESC";
$result = mysqli_query($conn, $query);

$query_field = "SELECT DISTINCT field_new.field_title, field_new.id FROM fieldofinterest5 INNER JOIN field_new ON field_new.id = fieldofinterest5.field WHERE dept = '$dept'";
$result_field = mysqli_query($conn, $query_field);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    // Extract data from the form
    $sections = $_POST['sections'];

    // Loop through each section and insert data into the database
    foreach ($sections as $section) {
        $courseCode = $section['text'] . ' ' . $section['number'];

        $courseTitle = $section['title'];
        $unit = $section['unit'];
        $status = $section['status'];
        $field = $section['field'];


        // Check if the course already exists in the database
        $checkQuery = "SELECT * FROM course_new WHERE course_code = ? AND course_title = ? AND unit = ? AND status = ? AND specialization = ?";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "sssss", $courseCode, $courseTitle, $unit, $status, $field);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        // If the course already exists, skip insertion
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo "<script>alert('Course with code $courseCode and title $courseTitle already exists.');</script>";
            continue;
        } else {
            // Perform database insertion using prepared statements
            $query = "INSERT INTO course_new (course_code, course_title, unit, status, dept_newids, specialization) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            mysqli_stmt_bind_param($stmt, "ssssss", $courseCode, $courseTitle, $unit, $status, $dept, $field);
            mysqli_stmt_execute($stmt);
            //mysqli_stmt_close($stmt);
            if ($stmt) {
                echo "<script>alert('Course Successfully Added')</script>";
                echo "<script>location.replace('../dashboard.php?p=viewcourse')</script>";
            } else {
                echo mysqli_error($conn);
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>View Course - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <!--  <link rel="stylesheet" href="style.css">-->
    <style>
        .trash i {
            margin-top: 0.3rem;
        }

        #nav1 {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            text-align: center;
            background: #fff;

        }

        .container {
            margin: auto;
            max-width: 1100px;
            padding: 1rem;
            overflow: auto;

        }

        .logout {
            background: #dc3545;
            padding: 0.5rem;
            margin-right: 10px;
            border-radius: 5px;
            display: flex;
            align-self: center;
            border: none;
            color: #fff;
        }

        @media (max-width:768px) {
            #nav1 h1 {
                font-size: 1rem;
            }
        }

        @media (max-width:850px) {
            #nav1 h1 {
                font-size: 1.2rem;
            }
        }

        #form {
            background: #fff;

            padding: 3rem;
            display: flex;
            flex-direction: column;
        }

        #form label {
            color: #0a2b4f;
            display: block !important;
            font-weight: bold;
        }



        .contain {
            margin: auto;
            max-width: 800px;
            padding: 1rem;
            overflow: auto;

        }

        #form form .btn {
            background: #dc3545;
            border: 1px solid #dc3545 !important;
            transition: 0.3s ease-in-out !important;
            color: #fff;
            font-weight: bolder;
        }

        #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;

        }

        .lock {
            background: #dc3545;
            border: 1px solid #dc3545 !important;
            transition: 0.3s ease-in-out !important;
            color: #fff;
            font-weight: bolder;
            padding-left: 2rem !important;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            width: 100%;
            text-align: center;
        }


        .forms .modal-body .input,
        .forms .modal-body select {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            border-radius: 0.4rem;
            margin-right: 0.5rem;
            width: 80%;
            outline: none;
            border: 1px solid #0a2b4f;
            text-align: center;

        }

        .forms .modal-body .sfield {
            width: 120% !important;

        }

        .forms .modal-body .inputTitle {
            padding-top: 0rem !important;
            padding-bottom: 0rem !important;
            border-radius: 0.4rem;
            margin-left: 3.5rem;
            border-radius: 0.4rem;
            width: 100% !important;
            outline: none;
            border: 1px solid #0a2b4f;
            text-align: center;
            height: 2.6rem !important;
            margin-top: 2rem !important;
        }


        th {
            text-align: left !important;
            background: #0a2b4f;
            color: #fff;

        }

        td {
            text-align: left !important;
            vertical-align: baseline !important;
        }

        th:first-child {
            border-top-left-radius: 10px;

        }

        th:last-child {
            border-top-right-radius: 10px;

        }

        tr:hover {
            background-color: #01314852;
            border: 2px solid #f1f1f1 !important;
            color: #000;
        }

        .course {
            display: flex;
            margin-bottom: 0.5rem;


        }

        .btwnum {
            padding: 1rem;
            font-weight: bolder;

        }

        .forms {
            display: flex;

            flex-direction: column;
            padding-top: 0;
            padding-bottom: 0;
        }


        .mb {
            margin-bottom: 1rem !important;
        }

        .mr {
            margin-right: 1rem;
        }

        .modal-dialog {
            max-width: 800px !important;
        }

        .group {
            display: flex;
            flex-direction: column;

        }

        .st {
            width: 100% !important;
        }

        .ser {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }


        .ser input {

            border-radius: 0.4rem;

            border-radius: 0.4rem;
            width: 50% !important;
            outline: none;
            border: 1px solid #0a2b4f;
            text-align: center;
            height: 2.6rem !important;

        }

        .success {
            padding: 0.2rem 1rem;
            background: #28a745;
            border: none;
            border-radius: 5px;
            color: #fff;
        }

        .danger {
            padding: 0.2rem 1rem;
            background: #dc3545;
            border: none;
            border-radius: 5px;
            color: #fff;
        }
    </style>


</head>

<body id="page-top">


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->

        <?php include('../menu/menu.php'); ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include('../navbar/nav.php'); ?>


                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">View Course</h1>

                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCourse">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Add Course
                        </button>

                        <div class="modal fade" id="addCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Add New Course</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="" method="post" class="forms">

                                        <div class="modal-body" id="formfield">
                                            <button type="button" class="btn btn-success mr mb" id="addInputSection">Add More</button>

                                            <button type="button" class="btn btn-danger mb removeInputSection">Remove</button>
                                            <div class="form-group">

                                                <div class="course">
                                                    <div class="group">

                                                        <label for="let">Letters:</label>
                                                        <input id="let" class="input" type="text" name="sections[0][text]" onkeyup="this.value = this.value.toUpperCase();" placeholder="CSC" maxlength="3" required>

                                                    </div>
                                                    <div class="group">

                                                        <label for="num">Number:</label>
                                                        <input id="num" class="input" type="number" name="sections[0][number]" placeholder="101" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;" required>

                                                    </div>
                                                    <div class="group">

                                                        <label for="unit">Unit:</label>
                                                        <input id="unit" class="input" type="number" name="sections[0][unit]" placeholder="3" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==1) return false;" required>
                                                    </div>

                                                    <div class="group mr">

                                                        <label for="status">Status:</label>
                                                        <select id="status" class="input st" name="sections[0][status]" required>
                                                            <option value="R"> R </option>
                                                            <option value="C">C</option>
                                                            <option value="E">E</option>
                                                        </select>
                                                        <!-- <input id="status" class="input" type="text" name="sections[0][status]" onkeyup="this.value = this.value.toUpperCase();" placeholder="C" maxlength="2"> -->
                                                    </div>
                                                    <div class="group">

                                                        <label for="status">Specialization:</label>
                                                        <select id="field" class="sfield" name="sections[0][field]" required>
                                                            <option value="">Select Specialization</option>
                                                            <!-- Options will be populated dynamically using PHP -->
                                                            <?php
                                                            while ($row_field = mysqli_fetch_assoc($result_field)) {

                                                                echo '<option value="' . $row_field['id'] . '">' . $row_field['field_title'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <input id="title" class="inputTitle" type="text" name="sections[0][title]" onkeyup="this.value = this.value.toUpperCase();" placeholder="Introduction to Programming" required>


                                                </div>
                                                <div id="additionalInputSections"></div>



                                            </div>




                                        </div>




                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            <input type="submit" value="Submit" class="btn btn-primary" name="submit">

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="" class="contain">


                        <form method="post">

                            <div class="form-group ser">
                                <label for="myInput">Search By Course Code:</label>
                                <input type="text" class="" id="myInput" onkeyup="myFunction()" placeholder="E.g. ABC 210" aria-label="Search">

                            </div>
                        </form>
                        <table id="myTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Course&nbsp;Code</th>
                                    <th>Course Title</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Specialization</th>
                                    <th>Enable/Disable</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $sn = 0;
                                while ($row2 = $result->fetch_assoc()) {
                                    $sn++; ?>




                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row2['course_code'] ?? 'No record' ?></td>
                                        <td><?php echo $row2['course_title']  ?? 'No record' ?></td>
                                        <td><?php echo $row2['unit']  ?? 'No record' ?></td>
                                        <td><?php echo $row2['status']  ?? 'No record' ?></td>
                                        <td><?php echo $row2['specialization']  ?? 'No record' ?></td>
                                        <td>

                                            <?php

                                            $cosid = $row2['id'];
                                            $status = $row2['status2'];
                                            if ($status == 1) {

                                                echo "<a href='../function/script.php?&status=$status&course=course&id=$cosid&dept=$dept' class='success'>Enable</a>";
                                            } elseif ($status == 0) {
                                                echo "<a href='../function/script.php?&status=$status&course=course&id=$cosid&dept=$dept' class='danger'>Disable</a>";
                                            }

                                            ?>


                                        </td>

                                    </tr>

                                <?php } ?>





                            </tbody>




                        </table>



                    </div>





                </div>




                <?php


                ?>

            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <p>Copyright &copy;<?php echo date("Y"); ?>, University of Ibadan, Postgraduate College. All Rights Reserved.</p>
                    </div>
                </div>
            </footer>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script>
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];

                if (td) {
                    txtValue = td.textContent || td.innerText;

                    if ((txtValue.toUpperCase().indexOf(filter) > -1)) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    <!-- Include jQuery from a different source -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Your script section -->
    <script>
        $(document).ready(function() {
            var maxInputSections = 5;
            var currentSectionIndex = 0;

            $("#addInputSection").click(function() {
                var currentSections = $(".course").length;

                if (currentSections < maxInputSections) {
                    currentSectionIndex++;

                    var newInputSection = $(".course:first").clone();

                    // Clear the values of the cloned inputs
                    newInputSection.find('input').val('');

                    // Update the name attribute for each input in the cloned section
                    newInputSection.find('[name]').each(function() {
                        var originalName = $(this).attr('name');
                        var newName = originalName.replace(/\[\d+\]/, '[' + currentSectionIndex + ']');
                        $(this).attr('name', newName);
                    });

                    $("#additionalInputSections").append(newInputSection);
                } else {
                    alert("Maximum number of input sections reached.");
                }
            });

            $(document).on("click", ".removeInputSection", function() {
                var currentSections = $(".course").length;
                if (currentSections > 1) {
                    $(".course:last").remove();
                    currentSectionIndex--;
                }
            });
        });
    </script>










    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>


    <script type="text/javascript" src="../selectnaration.js"></script>

</body>

</html>