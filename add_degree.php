<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start();
if (isset($_SESSION['flash_message'])) {
    $flash_type = $_SESSION['flash_type'] ?? 'info';
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}
include_once('function/connect.php');

// Check if the user is logged in
if(!isset($_SESSION["user"])) {
    header('Location: ./');
    exit();
}

// Handle logout
if (isset($_POST["logout"])) {
    session_destroy();
    header('Location: ./');
    exit();
}

$user = $_SESSION["user"];

// Get user name for display in the sidebar
$query_name = "SELECT DISTINCT name FROM users_cgpa_new WHERE username = '$user'";
$result_name = mysqli_query($conn, $query_name);
$row_name = mysqli_fetch_assoc($result_name);
$name_head = $row_name['name'] ?? 'Administrator';


if (isset($_POST["add-degree"])) {
    $dept_id = $_POST['dept_id'] ?? '';
    $degree_id = $_POST['degree_id'] ?? '';
    $type = $_POST['type'] ?? '';

    // Validate input
    if (empty($dept_id) || empty($degree_id) || empty($type)) {
        $_SESSION['flash_message'] = "Please fill all fields!";
        $_SESSION['flash_type'] = "error";
        header("Location: add_degree.php");
        exit();
    }

    // Add error checking for degree query
    $degree_query = mysqli_query($conn, "SELECT degree FROM degree_new WHERE id = '$degree_id'");
    if (!$degree_query) {
        die("Degree query failed: " . mysqli_error($conn));
    }
    
    $degree_row = mysqli_fetch_assoc($degree_query);
    $degree = $degree_row['degree'] ?? '';

    // Add error checking for check query
    $check_query = mysqli_query($conn, "SELECT * FROM programme_cgpa WHERE degree_id = '$degree_id'");
    if (!$check_query) {
        die("Check query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check_query) > 0) {
        // Update existing record
        $update_query = "UPDATE programme_cgpa SET degree = ?, type = ? WHERE degree_id = ?";
        $stmt = $conn->prepare($update_query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn));
        }
        $stmt->bind_param("ssi", $degree, $type, $degree_id);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        $_SESSION['flash_message'] = "Degree updated successfully!";
        $_SESSION['flash_type'] = "success";
    } else {
        // Insert new record
        $insert_query = "INSERT INTO programme_cgpa (degree_id, degree, type, cat) VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn));
        }
        $stmt->bind_param("iss", $degree_id, $degree, $type);
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }
        $stmt->close();
        $_SESSION['flash_message'] = "Degree added successfully!";
        $_SESSION['flash_type'] = "success";
    }

    header("Location: add_degree.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Student Records Search - Result Processing Application">
    <meta name="author" content="University of Ibadan">

    <title>Add Degree - Result Processing Application</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <style>
        .search-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .result-section {
            margin-top: 30px;
        }
        
        .table-responsive {
            margin-top: 20px;
        }
        
        .null-field {
            color: #dc3545;
            font-weight: bold;
        }
        
        .card {
            margin-bottom: 20px;
        }
        
        .alert {
            margin-top: 20px;
        }
        
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
        
        .btn-update {
            background-color: #0a2b4f;
            color: white;
        }
        
        .btn-update:hover {
            background-color: #0d3d6d;
            color: white;
        }
        
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
        
        #selector-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
        }
        
        .selector-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .selector-row label {
            font-weight: bold;
            min-width: 150px;
        }
        
        .success-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }

        #addDegreeForm {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            max-width: 500px;
            margin: 30px auto 0 auto;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        #addDegreeForm label {
            font-weight: 500;
            margin-bottom: 6px;
            color: #0a2b4f;
        }
        #addDegreeForm select {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0;
            background: #f8f9fc;
            margin-bottom: 0;
            font-size: 1rem;
        }
        #addDegreeForm .btn {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        #addDegreeForm .btn-update {
            background: #0a2b4f;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        #addDegreeForm .btn-update:hover {
            background: #1976d2;
        }
        .flash {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 1rem;
        }

        .flash.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .flash.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .flash.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-fw fa-user"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo $name_head; ?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <!-- Nav Item - Student Search -->
            <li class="nav-item">
                <a class="nav-link" href="student_search.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Student Records Search</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="student_record.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Student Zmain</span>
                </a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Add Degree</span>
                </a>
            </li>


             <li class="nav-item ">
                <a class="nav-link" href="./mode" target="_blank">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Change Mode</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="./check_enabled" target="_blank">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Check Record</span>
                </a>
            </li>
            

            <li class="nav-item">
                <form method="post" class="nav-link">
                    <button class="trash logout" type="submit" name="logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

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

                    <!-- Topbar Logo and Title -->
                    <div id="nav1">
                        <img style="width: 30px;" src="img/ui-logo.png" class="logo">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem;" src="img/logo.png" class="logo">
                        <h1 class="h3 m-0 font-weight-100 text-primary">Result Processing Application</h1>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <form method="post" class="nav-link">
                                <button class="trash logout" type="submit" name="logout">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Add Degree</h1>
                    </div>

                    <!-- Success Message Toast -->
                    <?php if (!empty($successMessage)): ?>
                    <div class="toast success-toast show" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
                        <div class="toast-header bg-success text-white">
                            <strong class="mr-auto">Success</strong>
                            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            <?php echo $successMessage; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Search Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add Degree to Programme</h6>
                        </div>
                        <div class="card-body">
                            <?php
                            // Fetch departments for the dropdown
                            $departments = mysqli_query($conn, "SELECT id, department FROM dept_new ORDER BY department");
                            ?>
                            <form method="POST" id="addDegreeForm">
                                 <?php if (isset($flash_message)): ?>
            <div class="flash <?= $flash_type ?>"><?= htmlspecialchars($flash_message) ?></div>
        <?php endif; ?>
                                <div class="selector-row">
                                    <label for="department">Department:</label>
                                    <select name="dept_id" id="department" required>
                                        <option value="">Select Department</option>
                                        <?php while ($row = mysqli_fetch_assoc($departments)): ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['department']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="selector-row">
                                    <label for="degree">Degree:</label>
                                    <select name="degree_id" id="degree" required>
                                        <option value="">Select Degree</option>
                                    </select>
                                </div>
                                <div class="selector-row">
                                    <label for="type">Type:</label>
                                    <select name="type" id="type" required>
                                        <option value="">Select Type</option>
                                        <option value="Academics">Academics</option>
                                        <option value="Professional">Professional</option>
                                        <option value="PGD">PGD</option>
                                    </select>
                                </div>
                                <div class="btn">
                                    <button type="submit" name="add-degree" class="btn-update"><i class="fas fa-fw fa-plus"></i>Add Degree</button>
                                </div>
                            </form>
                            <script>
                            $(document).ready(function() {
                                $('#department').on('change', function() {
                                    var deptId = $(this).val();
                                    $('#degree').html('<option value="">Select Degree</option>');
                                    if (deptId) {
                                        $.get('get_degrees_add.php', { dept_id: deptId }, function(data) {
                                            var degrees = JSON.parse(data);
                                            degrees.forEach(function(row) {
                                                $('#degree').append('<option value="' + row.id + '">' + row.degree + '</option>');
                                            });
                                        });
                                    }
                                });
                            });
                            </script>

                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <p>Copyright &copy;<?php echo date("Y"); ?>, University of Ibadan, Postgraduate College. All Rights Reserved.</p>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>


</body>

</html>