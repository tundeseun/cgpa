<?php session_start();
$dept = $_SESSION["dept_new"];
$name = $_SESSION["name"];
include_once('../function/script.php');

if (!isset($_SESSION["dept_new"]) && !isset($_SESSION["name"])) {
    session_destroy();
    header('Location: ../');
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ../');
}

$admin = $_SESSION["name"];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Result Processing - Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            display: block;
            font-weight: bold;
        }

        select,
        input {
            padding-left: 2rem !important;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            width: 100%;
            outline: none;
            border: 1px solid #0a2b4f;



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

        .foot .container .row .sign {
            width: 10rem !important;
            height: 5rem !important;
            border-bottom: 2px solid #CD853F;
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
                        <h1 class="h3 mb-0 text-gray-800"><?php if (isset($dept)) {
                                                                showdept($dept, $conn);
                                                            }  ?></h1>

                    </div>

                    <div id="form" class="contain">

                        <form action="" method="post">
                            <h4> Process Result </h4>
                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <select id="degree" name="degree">
                                    <option value="">Select Degree</option>
                                    <!-- Options will be populated dynamically using PHP -->
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="field">Specialization:</label>
                                <select id="field" name="field">
                                    <option value="">Select Specialization</option>
                                    <!-- Options will be populated dynamically using PHP -->
                                </select>

                            </div>

                            <div class="form-group">
                                <label for="effectivedate">Select Effective Date:</label>
                                <select id="effectivedate" name="effectivedate">
                                    <option value="">Select Effective Date</option>
                                    <!-- Options will be populated dynamically using AJAX -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="external">Select External:</label>
                                <select id="external" name="external">
                                    <option value="">Select External</option>
                                    <!-- Options will be populated dynamically using AJAX -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="external">Select Result Type:</label>
                                <select id="resulttype" name="resulttype">
                                    <option value="" selected>Select Result Type</option>
                                    <option value="0">Main Result</option>
                                    <option value="1">Supplementary Result</option>
                                    <!-- Options will be populated dynamically using AJAX -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="mode">Mode of Study:</label>
                                <select id="mode" name="mode">
                                    <option value="" selected>Select Mode of Study</option>
                                    <option value="1">Full-Time</option>
                                    <option value="0">Part-Time</option>
                                    <!-- Options will be populated dynamically using AJAX -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sec">Session of Graduation:</label>
                                <select id="sec" name="sec">
                                    <option value="">Select Session</option>
                                    <?php include_once("function/connect.php");
                                    showsessionexamined2($conn); ?>
                                </select>
                            </div>
                            <input type='submit' value='Process Result' name='send' class='btn'>


                        </form>

                    </div>


                    <?php
                    if (isset($_POST['send'])) {
                        $degree = $_POST['degree'];
                        $field = $_POST['field'];
                        $effectivedate = $_POST['effectivedate'];
                        $external = $_POST['external'];
                        $resulttype = $_POST['resulttype'];
                        $sec = $_POST['sec'];
                        $mode = $_POST['mode'];



                        $status = processResultReal($conn, $dept, $degree, $field, $effectivedate, $external, $resulttype, $sec, $mode);
                        if ($status === "Success") {

                            echo "<script>
    
    
    // Check if the page has already reloaded
    if (!localStorage.getItem('reloaded')) {
        Swal.fire({
            icon: 'success',
            title: 'Successful',
            text: 'Result Process Successful',
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
                        } else  if ($status === "Error") {

                            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'The Result has been Locked'
                    });
                </script>";
                        }
                    }

                    ?>




                </div>




            </div>
            <script>
                $(document).ready(function() {
                    // Populate the first dropdown on page load using PHP
                    $.ajax({
                        url: '../get_degrees.php', // Change to the appropriate PHP file for degrees
                        method: 'GET',
                        success: function(response) {
                            $('#degree').html(response);
                        }
                    });

                    // When a degree is selected, populate the second dropdown
                    $('#degree').on('change', function() {
                        var degreeID = $(this).val();
                        if (degreeID) {
                            $.ajax({
                                url: '../get_fields.php',
                                method: 'POST',
                                data: {
                                    degree: degreeID
                                },
                                success: function(response) {
                                    $('#field').html(response);
                                    $('#entry').html('<option value="">Year of Entry</option>');
                                    $('#effectivedate').html('<option value="">Select Effective Date</option>');
                                    $('#external').html('<option value="">Select External</option>');
                                }
                            });
                        } else {
                            $('#field').html('<option value="">Select Specialization</option>');
                            $('#entry').html('<option value="">Year of Entry</option>');
                            $('#effectivedate').html('<option value="">Select Effective Date</option>');
                            $('#external').html('<option value="">Select External</option>');
                        }
                    });

                    // When a field is selected, populate the third dropdown
                    $('#field').on('change', function() {
                        var fieldID = $(this).val();
                        if (fieldID) {
                            $.ajax({
                                url: '../get_effectivedates.php',
                                method: 'POST',
                                data: {
                                    field: fieldID
                                },
                                success: function(response) {
                                    $('#effectivedate').html(response);
                                    $('#entry').html('<option value="">Year of Entry</option>');
                                    $('#external').html('<option value="">Select External</option>');
                                }
                            });
                        } else {
                            $('#effectivedate').html('<option value="">Select Effective Date</option>');
                            $('#entry').html('<option value="">Year of Entry</option>');
                            $('#external').html('<option value="">Select External</option>');
                        }
                    });


                    // When an effective date is selected, populate the fourth dropdown
                    $('#effectivedate').on('change', function() {
                        var effectiveDate = $(this).val();
                        var fieldID = $('#field').val();
                        if (effectiveDate) {
                            $.ajax({
                                url: '../get_external.php',
                                method: 'POST',
                                data: {
                                    field: fieldID,
                                    effective_date: effectiveDate
                                },
                                success: function(response) {
                                    $('#external').html(response);
                                }
                            });
                        } else {
                            $('#external').html('<option value="">Select External</option>');
                        }
                    });
                });
            </script>
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

</body>

</html>