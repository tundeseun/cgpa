<?php
    session_start();
    include_once 'function/connect.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Check if the user is logged in
    if (! isset($_SESSION["user"])) {
    header('Location: ./');
    exit();
    }

    $user = $_SESSION["user"];

    // Get user name for display in the sidebar
    $query_name  = "SELECT DISTINCT name FROM users_cgpa_new WHERE username = '$user'";
    $result_name = mysqli_query($conn, $query_name);
    $row_name    = mysqli_fetch_assoc($result_name);
    $name_head   = $row_name['name'] ?? 'Administrator';

    // Initialize variables
    $matricNumber         = '';
    $resultsFound         = false;
    $prevAppResults       = [];
    $zmainResults         = [];
    $studentRecordResults = [];
    $errorMessage         = '';
    $successMessage       = '';

    // Process form submission for searching
    if (isset($_POST["search"])) {
    $matricNumber = mysqli_real_escape_string($conn, $_POST["matric_number"]);

    if (! empty($matricNumber)) {
        // Get user_id from prev_app using matric number
        $userIdQuery  = "SELECT user_id FROM prev_app WHERE matric = '$matricNumber'";
        $userIdResult = mysqli_query($conn, $userIdQuery);

        if (mysqli_num_rows($userIdResult) > 0) {
            $resultsFound = true;

            // Fetch all user_ids associated with this matric number
            while ($userIdRow = mysqli_fetch_assoc($userIdResult)) {
                $userId = $userIdRow['user_id'];

                // Get numeration from new table for this user_id
                $numerationQuery  = "SELECT id, numeration, Surname, Other_names FROM new WHERE id = '$userId'";
                $numerationResult = mysqli_query($conn, $numerationQuery);

                if ($numerationRow = mysqli_fetch_assoc($numerationResult)) {
                    $numeration  = $numerationRow['numeration'];
                    $studentName = $numerationRow['Surname'] . ', ' . $numerationRow['Other_names'];

                    // Check zmain table for the latest record where amount_charge = amount_paid

                    $zmainQuery = "SELECT prev_app.matric,fac_new.faculty,dept_new.department,zmain_app.user_id,field_new.field_title,zmain_app.mode_of_study,degree_new.degree
                    FROM zmain_app
                    LEFT JOIN prev_app ON prev_app.user_id = zmain_app.user_id
                    LEFT JOIN dept_new ON dept_new.id = zmain_app.department
                    LEFT JOIN degree_new ON degree_new.id = zmain_app.degree
                    LEFT JOIN fac_new ON fac_new.id = zmain_app.faculty
                    LEFT JOIN field_new ON field_new.id = zmain_app.field_of_interest
                    WHERE zmain_app.degree <> '3' AND zmain_app.degree <> '4' AND zmain_app.degree <> '5' AND prev_app.matric = $matricNumber
                    ORDER BY zmain_app.id DESC";
                    $zmainResult = mysqli_query($conn, $zmainQuery);

                    // Keep track of user_ids we've already processed
                    $processedUserIds = [];

                    while ($zmainRow = mysqli_fetch_assoc($zmainResult)) {
                        // Only add this record if we haven't seen this user_id yet
                        if (! in_array($userId, $processedUserIds)) {
                            $zmainInfo = [
                                'user_id'           => $userId,
                                'matric'            => $zmainRow['matric'],
                                'numeration'        => $numeration,
                                'student_name'      => $studentName,
                                'faculty'           => $zmainRow['faculty'],
                                'department'        => $zmainRow['department'],
                                'degree'            => $zmainRow['degree'],
                                'field_of_interest' => $zmainRow['field_title'],
                                'mode_of_study'     => $zmainRow['mode_of_study'] == 1 ? 'Full-Time' : 'Part-Time',
                            ];
                            $zmainResults[] = $zmainInfo;

                            // Mark this user_id as processed
                            $processedUserIds[] = $userId;
                        }
                    }

                    // Get studentRecord information
                    $studentRecordQuery = "SELECT DISTINCT user_id, matric, name, dept_new.department,studentrecord.status
                                      FROM studentrecord
                                      INNER JOIN dept_new ON dept_new.id = studentrecord.dept
                                      WHERE matric = '$matricNumber'";
                    $studentRecordResult = mysqli_query($conn, $studentRecordQuery);

                    if (mysqli_num_rows($studentRecordResult) > 0) {
                        while ($studentRecordRow = mysqli_fetch_assoc($studentRecordResult)) {

                            $studentRecordResults[] = [
                                'user_id'    => $userId,
                                'matric'     => $studentRecordRow['matric'],
                                'name'       => $studentRecordRow['name'],
                                'department' => $studentRecordRow['department'],
                                'status'     => $studentRecordRow['status'],

                            ];
                        }
                    }

                    // Add to prev_app results
                    $prevAppResults[] = [
                        'user_id'      => $userId,
                        'numeration'   => $numeration,
                        'student_name' => $studentName,
                    ];
                }
            }

            if (empty($prevAppResults)) {
                $resultsFound = false;
                $errorMessage = "No records found for this matriculation number in the system.";
            }
        } else {
            $errorMessage = "No records found for this matriculation number.";
        }
    } else {
        $errorMessage = "Please enter a matriculation number.";
    }
    }

    // Handle logout
    if (isset($_POST["logout"])) {
    session_destroy();
    header('Location: ./');
    exit();
    }

    // First, we need to get all unique user_ids from both tables
    $allUserIds = [];

    // Add all studentRecord user_ids to the array
    foreach ($studentRecordResults as $record) {
    if (! in_array($record['user_id'], $allUserIds)) {
        $allUserIds[] = $record['user_id'];
    }
    }

    // Add all payment record user_ids to the array
    foreach ($zmainResults as $record) {
    if (! in_array($record['user_id'], $allUserIds)) {
        $allUserIds[] = $record['user_id'];
    }
    }

    // If we have multiple user_ids, editing should be enabled
    $enableEditing = count($allUserIds) > 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Zmain Record - Result Processing Application">
    <meta name="author" content="University of Ibadan">

    <title>Zmain Record - Result Processing Application</title>

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

<li class="nav-item">
                <a class="nav-link" href="student_search.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Student Records Search</span>
                </a>
            </li>
            <!-- Nav Item - Student Search -->
            <li class="nav-item active">
                <a class="nav-link" href="student_record.php">
                    <i class="fas fa-fw fa-search"></i>
                    <span>Student Zmain</span>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" href="add_degree.php">
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
                        <h1 class="h3 mb-0 text-gray-800">Zmain Record</h1>
                    </div>

                    <!-- Success Message Toast -->
                    <?php if (! empty($successMessage)): ?>
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
                            <h6 class="m-0 font-weight-bold text-primary">Search by Matriculation Number</h6>
                        </div>
                        <div class="card-body">
                            <form method="post" class="search-form">
                                <div class="form-group">
                                    <label for="matric_number">Matriculation Number:</label>
                                    <div class="input-group">
                                        <input type="text" name="matric_number" id="matric_number" class="form-control"
                                               value="<?php echo htmlspecialchars($matricNumber); ?>" required>
                                        <div class="input-group-append">
                                            <button type="submit" name="search" class="btn btn-primary">
                                                <i class="fas fa-search fa-sm"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if (! empty($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $errorMessage; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($resultsFound): ?>
                    <!-- Results Section -->
                    <div class="result-section">

                        <!-- ZmainApp Records -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">ZmainApp Records</h6>
                            </div>
                            <div class="card-body">
                                <?php if (count($zmainResults) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Student Name</th>
                                                <th>User ID</th>
                                                <th>Matric</th>
                                                <th>Faculty</th>
                                                <th>Department</th>
                                                <th>Degree</th>
                                                <th>Field</th>
                                                <th>Mode</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($zmainResults as $record):
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($record['student_name']); ?></td>
                                                <td><?php echo htmlspecialchars($record['user_id']); ?></td>
                                                <td><?php echo htmlspecialchars($record['matric']); ?></td>
                                                <td><?php echo htmlspecialchars($record['faculty']); ?></td>
                                                <td><?php echo htmlspecialchars($record['department']); ?></td>
                                                <td><?php echo htmlspecialchars($record['degree']); ?></td>
                                                <td><?php echo htmlspecialchars($record['field_of_interest']); ?></td>
                                                <td><?php echo htmlspecialchars($record['mode_of_study']); ?></td>

                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-circle"></i> No student records found on zmainApp.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Student Records Table -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Student Records Table</h6>
                            </div>
                            <div class="card-body">
                                <?php if (count($studentRecordResults) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>User ID</th>
                                                <th>Matric</th>
                                                <th>Name</th>
                                                <th>Department</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($studentRecordResults as $record):
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($record['user_id']); ?></td>
                                                <td><?php echo htmlspecialchars($record['matric']); ?></td>
                                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                                <td><?php echo htmlspecialchars($record['department']); ?></td>
                                                <td><?php echo htmlspecialchars($record['status']); ?></td>

                                                <!-- <td>
                                                    <?php if ($enableEditing): ?>
                                                        <button class="btn btn-sm btn-outline-primary select-score-btn"
                                                                data-user-id="<?php echo htmlspecialchars($record['user_id']); ?>"
                                                                data-toggle="modal" data-target="#updateUserIdModal">
                                                            <i class="fas fa-edit"></i> Update
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> No Update Needed</span>
                                                    <?php endif; ?>
                                                </td> -->
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-circle"></i> No record found for processing records for this student.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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

    <!-- Update User ID Modal -->
    <div class="modal fade" id="updateUserIdModal" tabindex="-1" role="dialog" aria-labelledby="updateUserIdModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateUserIdModalLabel">Update User ID</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="update-user-form">
                    <div class="modal-body">
                        <input type="hidden" name="matric" value="<?php echo htmlspecialchars($matricNumber); ?>">

                        <div class="form-group">
                            <label for="source_user_id">Current User ID (in studentRecord table):</label>
                            <input type="text" name="source_user_id" id="source_user_id" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="target_user_id">New User ID (from payment record):</label>
                            <input type="text" name="target_user_id" id="target_user_id" class="form-control" readonly>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This will update the user_id for all records with the current user ID.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_user_id" class="btn btn-primary">Update User ID</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle select user ID button click
            $('.select-user-btn').click(function() {
                var userId = $(this).data('user-id');
                $('#target_user_id').val(userId);
                this.textContent = 'Selected!';
                this.style.backgroundColor = '#28a745';
                this.disabled = true;
                this.style.color = '#fff';
            });

            // Handle select score button click
            $('.select-score-btn').click(function() {
                var userId = $(this).data('user-id');
                $('#source_user_id').val(userId);
            });

            // Auto-hide toast after 5 seconds
            setTimeout(function() {
                $('.toast').toast('hide');
            }, 5000);

            // Enable Bootstrap tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Confirm before submitting update form
            $('#update-user-form').submit(function(e) {
                var sourceId = $('#source_user_id').val();
                var targetId = $('#target_user_id').val();

                if (!sourceId || !targetId) {
                    e.preventDefault();
                    alert('Both current and new User IDs are required.');
                    return false;
                }

                if (sourceId === targetId) {
                    e.preventDefault();
                    alert('Current and new User IDs are the same. No update needed.');
                    return false;
                }

                if (!confirm('Are you sure you want to update the User ID from ' + sourceId + ' to ' + targetId + '?')) {
                    e.preventDefault();
                    return false;
                }

                return true;
            });
        });
    </script>

</body>

</html>