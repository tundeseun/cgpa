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

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            max-width: 1100px;
            padding: 1rem;
            overflow: auto;

        }

        /* #form form .btn {
            background: #dc3545;
            border: 1px solid #dc3545 !important;
            transition: 0.3s ease-in-out !important;
            color: #fff;
            font-weight: bolder;
        } */

        /* #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;

        } */

        .table th {
            text-align: left !important;
            background: #0a2b4f;
            color: #fff;

        }

        .table td {
            text-align: left !important;
            vertical-align: baseline !important;
        }

        .table th:first-child {
            border-top-left-radius: 10px;

        }

        .table th:last-child {
            border-top-right-radius: 10px;

        }

        .table tr:hover {
            background-color: #01314852;
            border: 2px solid #f1f1f1 !important;
            color: #000;
        }

        .table a {
            color: #000;

        }

        .table {
            font-size: 1rem !important;
        }

        #pagination {
            display: flex;
            justify-content: center;
        }

        .pagination_link {
            border: 1px solid #0a2b4f;
            border-radius: 0.5rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            margin-right: 1rem !important;
            text-decoration: none;
            color: #0a2b4f;
        }

        .pagination_link:hover {
            background: #0a2b4f;
            color: #fff;
        }

        .pagination_link.active {
            background: #0a2b4f;
            color: #fff;
            pointer-events: none;

        }

        .success {
            padding: 0.2rem 1rem;
            background: #28a745;
            border: none;
            border-radius: 5px;
            color: #fff !important;
        }

        .head {
            display: flex;
            justify-content: space-between;
        }

        .head a:hover {
            text-decoration: none;
        }

        .stage {
            font-size: 0.75rem;
            padding: 0.2rem;
            border-radius: 0.5rem;
        }

        #stage-success {
            background: #f6c23e !important;
            color: #0a2b4f;
            font-weight: 900 !important;
        }

        #stage-error {
            background: #e74a3b !important;
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
                        <h1 class="h3 mb-0 text-gray-800"><?php if (isset($dept)) {
                                                                showdept($dept, $conn);
                                                            }  ?></h1>

                    </div>

                    <div id="form" class="contain">
                        <!-- 
                        <form action="../boardresult.php" method="post">
                            <h4> View BroadSheet </h4>
                            <div class="form-group">
                                <label for="degree">Degree:</label>
                                <select id="degree" name="degree">
                                    <option value="">Select Degree</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="field">Specialization:</label>
                                <select id="field" name="field">
                                    <option value="">Select Specialization</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="effectivedate">Select Effective Date:</label>
                                <select id="effectivedate" name="effectivedate">
                                    <option value="">Select Effective Date</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="external">Select External:</label>
                                <select id="external" name="external">
                                    <option value="">Select External</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="external">Select Result Type:</label>
                                <select id="resulttype" name="resulttype">
                                    <option value="" selected>Select Result Type</option>
                                    <option value="0">Main Result</option>
                                    <option value="1">Supplementary Result</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sec">Session of Graduation:</label>
                                <select id="sec" name="sec">
                                    <option value="">Select Session</option>
                                    <?php //include_once("function/connect.php");
                                    //showsessionexamined2($conn);
                                    ?>
                                </select>
                            </div>
                            <input type='submit' value='Submit' name='send' class='btn'>


                        </form>-->



                        <div class="head mb-2">
                            <h4> Processed Result </h4>

                        </div>
                        <?php


                            if(isset($_POST['submit'])){
                                $effective = $_POST['effective'];
                                $facDate = $_POST['facDate'];
                                setFacultyMeetingDate($effective, $facDate, $conn);
                            }


                        ?>

                        <table class="table cmt" id="myTable">

                            <thead>

                                <tr>
                                    <th> S/N</th>
                                    <th> Specialization</th>
                                    <th> Result Type</th>
                                    <th> Stage </th>
                                    <th> Faculty Meeting </th>
                                    <th> View Result</th>


                                </tr>
                            </thead>

                            <tbody id="userTableBody">

                            </tbody>

                        </table>

                        <div id="pagination">
                            <!-- Pagination links will be loaded here -->
                        </div>



                    </div>





                </div>




            </div>
            <!-- <script>
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
                                    $('#effectivedate').html('<option value="">Select Effective Date</option>');
                                    $('#external').html('<option value="">Select External</option>');
                                    $('#sec').html('<option value="">Select Session</option>');
                                }
                            });
                        } else {
                            $('#field').html('<option value="">Select Specialization</option>');
                            $('#effectivedate').html('<option value="">Select Effective Date</option>');
                            $('#external').html('<option value="">Select External</option>');
                            $('#sec').html('<option value="">Select Session</option>');
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
                                    $('#external').html('<option value="">Select External</option>');
                                    $('#sec').html('<option value="">Select Session</option>');

                                }
                            });
                        } else {
                            $('#effectivedate').html('<option value="">Select Effective Date</option>');
                            $('#external').html('<option value="">Select External</option>');
                            $('#sec').html('<option value="">Select Session</option>');
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
                                    $('#sec').html('<option value="">Select Session</option>');

                                }
                            });
                        } else {
                            $('#external').html('<option value="">Select External</option>');
                            $('#sec').html('<option value="">Select Session</option>');

                        }
                    });
                    $('#external').on('change', function() {
                        var external = $(this).val();
                        var fieldID = $('#field').val();
                        var effectiveDate = $('#effectivedate').val();
                        var degreeID = $('#degree').val();
                        if (effectiveDate) {
                            $.ajax({
                                url: '../get_session.php',
                                method: 'POST',
                                data: {
                                    field: fieldID,
                                    effective_date: effectiveDate,
                                    external: external,
                                    degree: degreeID
                                },
                                success: function(response) {
                                    $('#sec').html(response);

                                }
                            });
                        } else {
                            $('#sec').html('<option value="">Select Session</option>');

                        }
                    });
                });
            </script> -->


            <script>
                $(document).ready(function() {
                    // Populate the first dropdown on page load using PHP
                    $.ajax({
                        url: '../get_effectiveDate.php', // Change to the appropriate PHP file for degrees
                        method: 'GET',
                        success: function(response) {
                            $('#effective').html(response);
                        }
                    });
                });

                $(document).ready(function() {
                    function loadUsers(page) {
                        $.ajax({
                            url: '../fetch_dept_result.php',
                            method: 'POST',
                            data: {
                                page: page
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#userTableBody').empty();
                                let data = response.data;
                                let total_pages = response.total_pages;
                                let rows = '';
                                data.forEach(function(result, index) {
                                    let stageText, facultyText;
                                    if (result.facultyDate === null || result.facultyDate === '') {
                                        facultyText = '<span class=stage id=stage-error>No Date Assigned</span>';
                                    } else {
                                        facultyText = `<span class=stage id=stage-success>${result.facultyDate}</span>`;
                                    }
                                    switch (result.stage) {
                                        case '1':
                                            stageText = 'Processed <span class=stage id=stage-success>1/4</span>';
                                            break;
                                        case '2':
                                            stageText = 'Approved At Faculty <span class=stage id=stage-success>2/4</span>';
                                            break;
                                        case '3':
                                            stageText = 'Approved At Comittee <span class=stage id=stage-success>3/4</span>';
                                            break;
                                        case '4':
                                            stageText = 'Approved At Board <span class=stage id=stage-success>4/4</span>';
                                            break;
                                        case '5':
                                            stageText = 'Rejected At Faculty <span class=stage id=stage-error>Reprocess</span>';
                                            break;
                                        default:
                                            stageText = `Stage ${result.stage}`;
                                    }

                                    rows += `<tr>
                                    <td>${index + 1 + (page - 1) * 10}</td>
                                    <td>${result.specialization}</td>
                                    <td>${result.resultT}</td>
                                    <td>${stageText}</td>
                                    <td>${facultyText}</td>
                                    <td>
                                    <a  href='../pdf.php?degree=${result.degree_id}&field=${result.field_id}&effectivedate=${result.effectivedate}&sec=${result.sec}&resulttype=${result.resulttype}&external=${result.external}' class='success'>
                                        <i class="fas fa-download fa-sm text-white-50"></i> Download
                                      </a>
                                    </td>
                                    </tr>`;
                                });

                                $('#userTableBody').html(rows);

                                let pagination = '';
                                for (let i = 1; i <= total_pages; i++) {
                                    if (i == page) {
                                        pagination += `<a href="#" class="pagination_link active" id="${i}" aria-current="page">${i}</a> `;
                                    } else {
                                        pagination += `<a href="#" class="pagination_link" id="${i}">${i}</a> `;
                                    }
                                }
                                $('#pagination').html(pagination);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert("An error occurred while fetching data: " + textStatus + " - " + errorThrown);
                            }
                        });
                    }

                    $(document).on('click', '.pagination_link', function(e) {
                        e.preventDefault();
                        let page = $(this).attr('id');
                        loadUsers(page);
                    });

                    loadUsers(1); // Load the first page of users initially
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