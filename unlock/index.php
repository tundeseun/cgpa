<?php
session_start();
$user = $_SESSION["user"];

include_once('../function/script.php');


$query_name = "SELECT DISTINCT name FROM users_cgpa_new WHERE username = '$user'";
$result_name = mysqli_query($conn, $query_name);

$query_dept = "SELECT DISTINCT testscore.dept as dept_id,dept_new.department as dept FROM testscore inner join dept_new on dept_new.id=testscore.dept";
$result_dept = mysqli_query($conn, $query_dept);

$row_name = mysqli_fetch_assoc($result_name);
$name_head = $row_name['name'];


if (!($user)) {
    session_destroy();
    header('Location: ../');
}

if (isset($_POST["logout"])) {
    session_destroy();
    header('Location: ../');
}
if (isset($_GET['department'])) {
    $department = $_GET['department'];
    $degree = $_GET["degree"];
    $field = $_GET["field"];
    $effectivedate = $_GET["effectivedate"];
    $update = "UPDATE testscore SET status = 0 WHERE dept = '$department' AND degree = '$degree' AND field = '$field' AND effectivedate = '$effectivedate'";
    $resultp = mysqli_query($conn, $update);

    if ($resultp) {
        echo "<script>alert('Result Successfully Unlocked')</script>";
        echo "<script>location.replace('../dashboard.php?p=unlock&user=$user')</script>";
    } else {
        echo "<script> alert('Error locking result!')</script> ";
    }

    mysqli_close($conn);
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

    <title>Lock Board Approved Result - Dashboard</title>

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


        th {
            text-align: left !important;

            color: #0a2b4f;

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

        .lock:hover {
            background: #0a2b4f;
            border: none !important;
        }

        a,
        a:hover {
            color: #fff;
            text-decoration: none;
        }

        /* .mb{
            margin-bottom: 1rem !important;
        } */
    </style>


</head>

<body id="page-top">


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar --> <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.html">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-fw fa-user"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $name_head;; ?></div>
            </a>


            <!-- Nav Item - Dashboard -->

            <li class="nav-item">
                <a class="nav-link" href="../examView">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>View Approved Result</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../examGradList">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>View Graduating List</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../examRegStatus">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>View Registration Status</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../examSenateList">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>View Senate List</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../examOrder">
                    <i class="fas fa-download"></i>
                    <span>Order Of Proceedings</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../lock">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Lock Approved Result</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="../unlock">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Unlock Result</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">


            <li class="nav-item">
                <form method="post" class=" nav-link"><button class="trash logout" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i>Logout</button></form>


            </li>



            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <div id="nav1" onmouseover="closeCity(event, 'Paris')">

                        <!--
              <div class="top-logo">

                <img style="width: 30px;" src="img/ui-logo.png" class="logo">

            </div>
-->
                        <img style="width: 30px;" src="../img/ui-logo.png" class="logo">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="../img/logo.png" class="logo">
                        <h1 class="h3 m-0 font-weight-100 text-primary" style="">Result Processing Application</h1>
                        <!--
            <div class="top-logo">

                <img style="width: 50px;" src="img/logo.png" class="logo">

            </div>
-->
                    </div>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">



                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <form method="post" class=" nav-link"><button class="trash logout" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i>Logout</button></form>

                        </li>

                    </ul>

                </nav>


                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Unlock Result</h1>

                    </div>

                    <div id="form" class="contain">

                        <form action="" method="post">
                            <div class="form-group mb">
                                <label for="dept">Select Department:</label>

                                <select name="department" id="dept" onchange="showUser(this.value)">
                                    <?php


                                    echo "<option value='' disabled selected>";
                                    while ($row_dept = mysqli_fetch_assoc($result_dept)) {
                                        echo "<option value=" . $row_dept['dept_id'];
                                        echo  " > " . strtoupper($row_dept['dept']) . "</option>";
                                    }
                                    ?>

                                </select>
                            </div>






                            <div align="left"><span id="txtHint"></span>






                            </div>
                        </form>


                        <?php



                        if (isset($_POST['submit'])) {
                            $department = $_POST['department'];
                            $degree = $_POST["degree"];
                            $field = $_POST["field"];
                            $effectivedate = $_POST["effectivedate"];

                            $query = "SELECT DISTINCT testscore.matric, new.Surname,new.Other_names,remark.grade FROM new INNER JOIN testscore ON testscore.user_id=new.id INNER JOIN remark ON remark.user_id=new.id WHERE testscore.dept = '$department' AND testscore.degree = '$degree' AND testscore.field = '$field'";

                            // $row = $result->fetch_assoc();
                            // var_dump($row); exit;
                            if ($result = mysqli_query($conn, $query)) {


                                echo "<a href='index.php?department=$department&degree=$degree&field=$field&effectivedate=$effectivedate' class='lock'>Unlock Result</a>";


                        ?>


                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Matric</th>
                                                <th>Name</th>
                                                <th>Effective Date</th>
                                                <th>Proceed To</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php
                                            $sn = 0;
                                            while ($row2 = $result->fetch_assoc()) { ?>




                                                <tr>
                                                    <td><?php echo ++$sn; ?></td>
                                                    <td><?php echo $row2['matric'] ?? 'No record' ?></td>
                                                    <td><?php echo $row2['Surname'] . ' ' . $row2['Other_names'] ?? 'No record' ?></td>
                                                    <td><?php echo $effectivedate  ?? 'No record' ?></td>
                                                    <td><?php echo $row2['grade']  ?? 'No record' ?></td>

                                                </tr>

                                            <?php } ?>





                                        </tbody>




                                    </table>
                                </div>

                        <?php }
                        }
                        ?>
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