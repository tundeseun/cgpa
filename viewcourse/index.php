<?php
    session_start();
    $dept = $_SESSION["dept_new"];
    $name = $_SESSION["name"];
    include_once '../function/script.php';

    if (! ($dept && $name)) {
        session_destroy();
        header('Location: ../');
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: ../');
    }

    $query  = "SELECT course_new.id AS id,course_code,course_title,unit,status,status2,field_new.field_title AS specialization FROM course_new INNER JOIN field_new ON field_new.id = course_new.specialization WHERE course_new.dept_newids = '$dept' ORDER BY course_new.id DESC";
    $result = mysqli_query($conn, $query);

    $query_field  = "SELECT DISTINCT field_new.field_title, field_new.id FROM fieldofinterest5 INNER JOIN field_new ON field_new.id = fieldofinterest5.field WHERE dept = '$dept'";
    $result_field = mysqli_query($conn, $query_field);

    if (! $result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (isset($_POST['submit'])) {
        // Extract data from the form
        $sections = $_POST['sections'];

        // Loop through each section and insert data into the database
        foreach ($sections as $section) {
            $courseCode = $section['text'] . ' ' . $section['number'];

            $courseTitle = $section['title'];
            $unit        = $section['unit'];
            $status      = $section['status'];
            $field       = $section['field'];
            $degree_id   = $section['degree'];

            if ($status == 'C') {
                $order = 1;
            } else if ($status == 'R') {
                $order = 2;
            } else if ($status == 'E') {
                $order = 3;
            }

            // Check if the course already exists in the database
            $checkQuery = "SELECT * FROM course_new WHERE course_code = ? AND course_title = ? AND unit = ? AND status = ? AND specialization = ? AND dept_newids = ? AND degree_id = ?";
            $checkStmt  = mysqli_prepare($conn, $checkQuery);
            mysqli_stmt_bind_param($checkStmt, "ssssssi", $courseCode, $courseTitle, $unit, $status, $field, $dept, $degree_id);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            // If the course already exists, skip insertion
            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                echo "<script>alert('Course with code $courseCode and title $courseTitle already exists.');</script>";
                continue;
            } else {
                // Perform database insertion using prepared statements
                $query = "INSERT INTO course_new (course_code, course_title, unit, status, dept_newids, specialization, corder, degree_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt  = mysqli_prepare($conn, $query);

                mysqli_stmt_bind_param($stmt, "sssssssi", $courseCode, $courseTitle, $unit, $status, $dept, $field, $order, $degree_id);
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

    <title>View Course(s) - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
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
            max-width: 1000px;
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

        .mr-2 {
            margin-right: 2.5rem !important;
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


/* Enhanced action button styles */
.action-btn-group {
    display: flex !important;
    gap: 0.3rem !important;
    justify-content: center !important;
    align-items: center !important;
    flex-wrap: nowrap !important;
    white-space: nowrap !important;
}

.action-btn-group .btn {
    white-space: nowrap !important;
    font-size: 0.7rem !important;
    padding: 0.25rem 0.4rem !important;
    min-width: auto !important;
    flex-shrink: 0 !important;
    transition: all 0.3s ease !important;
    position: relative !important;
}

/* Disabled state styling */
.action-btn-group .btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
}

/* Loading animation for spinner */
.action-btn-group .btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Success state styling */
.action-btn-group .btn.btn-success {
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

/* Error state styling */
.action-btn-group .btn.btn-danger {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

/* Processing state styling */
.action-btn-group .btn.processing {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

/* Make sure the table cell doesn't break the layout */
#myTable td:nth-child(8) {
    white-space: nowrap !important;
    text-align: center !important;
    vertical-align: middle !important;
    min-width: 200px !important;
}

/* Status text styling */
#myTable td:nth-child(9) {
    font-weight: bold !important;
    text-align: center !important;
}

/* Status text colors */
#myTable td:nth-child(9):contains('Enabled') {
    color: #28a745 !important;
}

#myTable td:nth-child(9):contains('Disabled') {
    color: #dc3545 !important;
}

#myTable td:nth-child(9):contains('Archived') {
    color: #6c757d !important;
}

/* Button hover effects */
.action-btn-group .btn:not(:disabled):hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .action-btn-group .btn {
        font-size: 0.6rem !important;
        padding: 0.2rem 0.3rem !important;
    }

    #myTable td:nth-child(8) {
        min-width: 180px !important;
    }
}

/* Success flash animation */
@keyframes successFlash {
    0% { background-color: #28a745; }
    50% { background-color: #34ce57; }
    100% { background-color: #28a745; }
}

.action-btn-group .btn.success-flash {
    animation: successFlash 0.5s ease-in-out;
}

/* Error flash animation */
@keyframes errorFlash {
    0% { background-color: #dc3545; }
    50% { background-color: #e74c3c; }
    100% { background-color: #dc3545; }
}

.action-btn-group .btn.error-flash {
    animation: errorFlash 0.5s ease-in-out;
}
    </style>


</head>

<body id="page-top">


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->

        <?php include '../menu/menu.php'; ?>

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include '../navbar/nav.php'; ?>


                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">View Course(s)</h1>

                        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                            data-toggle="modal" data-target="#addCourse">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Add Course
                        </button>

                        <div class="modal fade" id="addCourse" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                            <button type="button" class="btn btn-success mr mb" id="addInputSection">Add
                                                More</button>

                                            <button type="button"
                                                class="btn btn-danger mb removeInputSection">Remove</button>
                                            <div class="form-group">

                                                <div class="course">
                                                    <div class="group">

                                                        <label for="let">Letters:</label>
                                                        <input id="let" class="input" type="text"
                                                            name="sections[0][text]"
                                                            onkeyup="this.value = this.value.toUpperCase();"
                                                            placeholder="CSC" maxlength="3" required>

                                                    </div>
                                                    <div class="group">

                                                        <label for="num">Number:</label>
                                                        <input id="num" class="input" type="number"
                                                            name="sections[0][number]" placeholder="101"
                                                            pattern="/^-?\d+\.?\d*$/"
                                                            onKeyPress="if(this.value.length==3) return false;"
                                                            required>

                                                    </div>
                                                    <div class="group">

                                                        <label for="unit">Unit:</label>
                                                        <input id="unit" class="input" type="number"
                                                            name="sections[0][unit]" placeholder="3"
                                                            pattern="/^-?\d+\.?\d*$/"
                                                            onKeyPress="if(this.value.length==1) return false;"
                                                            required>
                                                    </div>

                                                    <div class="group mr">

                                                        <label for="status">Status:</label>
                                                        <select id="status" class="input st" name="sections[0][status]"
                                                            required>
                                                            <option value="R"> R </option>
                                                            <option value="C">C</option>
                                                            <option value="E">E</option>
                                                        </select>
                                                        <!-- <input id="status" class="input" type="text" name="sections[0][status]" onkeyup="this.value = this.value.toUpperCase();" placeholder="C" maxlength="2"> -->
                                                    </div>
                                                    <div class="group mr-2">

                                                        <label for="field">Specialization:</label>
                                                        <select id="field" class="sfield" name="sections[0][field]"
                                                            required>
                                                            <option value="">Select Specialization</option>
                                                            <!-- Options will be populated dynamically using PHP -->
                                                            <?php
                                                                while ($row_field = mysqli_fetch_assoc($result_field)) {

                                                                    echo '<option value="' . $row_field['id'] . '">' . $row_field['field_title'] . '</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="group">
                                                        <label for="degree">Degree:</label>
                                                        <select id="degree" class="sfield" name="sections[0][degree]" required>
                                                            <option value="">Select Degree</option>
                                                            <?php
                                                                // Fetch degrees for the department
                                                                $degree_query = mysqli_query($conn, "SELECT DISTINCT degree_new.id, degree_new.degree FROM fieldofinterest5 INNER JOIN degree_new ON degree_new.id = fieldofinterest5.degree WHERE fieldofinterest5.dept = '$dept' ORDER BY degree_new.degree");
                                                                mysqli_data_seek($degree_query, 0); // Reset pointer in case used before
                                                                while ($deg_row = mysqli_fetch_assoc($degree_query)) {
                                                                    echo '<option value="' . $deg_row['id'] . '">' . $deg_row['degree'] . '</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <input id="title" class="inputTitle" type="text"
                                                        name="sections[0][title]"
                                                        onkeyup="this.value = this.value.toUpperCase();"
                                                        placeholder="Introduction to Programming" required>


                                                </div>
                                                <div id="additionalInputSections"></div>



                                            </div>




                                        </div>




                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                            <input type="submit" value="Submit" class="btn btn-primary" name="submit">

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div id="" class="container">

                        <!-- Table -->
                        <table id="myTable" class="table table-bordered" style="font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Degree</th>
                                    <th>Specialization</th>
                                    <th>Enable/Disable</th>
                                    <th>Current Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- <button id="bulk-enable" class="btn btn-success">Enable Selected</button>
                        <button id="bulk-disable" class="btn btn-danger">Disable Selected</button> -->
                        <script>
                           $('#myTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '../getCourses.php',
        type: 'POST',
        dataSrc: function(json) {
            if (!json.data) {
                console.error("Invalid response structure:", json);
                return [];
            }
            return json.data;
        },
        error: function(xhr, error, code) {
            console.log("Error: " + code + " | " + error);
            console.log(xhr.responseText); // Log the server response
        }
    },
    columns: [{
            data: 'sn'
        },
        {
            data: 'course_code'
        },
        {
            data: 'course_title'
        },
        {
            data: 'unit'
        },
        {
            data: 'status'
        },
        {
            data: 'degree'
        },
        {
            data: 'specialization'
        },
        {
            data: 'action'
        },
        {
            data: 'current_status'
        }
    ]
});

// Button handlers with enhanced UI feedback
$('#myTable').on('click', '.enable-btn', function() {
    const courseId = $(this).data('id');
    const button = $(this);
    const row = button.closest('tr');
    updateCourseStatus(courseId, 0, button, row, 'Enabled');
});

$('#myTable').on('click', '.disable-btn', function() {
    const courseId = $(this).data('id');
    const button = $(this);
    const row = button.closest('tr');
    updateCourseStatus(courseId, 1, button, row, 'Disabled');
});

$('#myTable').on('click', '.archive-btn', function() {
    const courseId = $(this).data('id');
    const button = $(this);
    const row = button.closest('tr');
    updateCourseStatus(courseId, 3, button, row, 'Archived');
});

function updateCourseStatus(courseId, status, clickedButton, row, statusText) {
    // Store original button text and disable all action buttons in the row
    const originalText = clickedButton.text();
    const actionButtons = row.find('.action-btn-group .btn');

    // Disable all buttons in the row and show loading state
    actionButtons.prop('disabled', true);
    clickedButton.html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    $.ajax({
        url: '../function/script.php',
        type: 'GET',
        data: {
            id: courseId,
            status: status,
            course: 'course',
            dept: '<?php echo $dept; ?>',
        },
        success: function(response) {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }
            if (response.success) {
                // Update the status text in the current status column (8th column)
                const statusCell = row.find('td:nth-child(9)');
                statusCell.text(statusText);

                // Update the action buttons based on the new status
                updateActionButtons(row, status);

                // Show success feedback
                clickedButton.removeClass('btn-primary btn-danger btn-warning')
                    .addClass('btn-success')
                    .html('<i class="fas fa-check"></i> Success');

                // Reset button after 2 seconds
                setTimeout(function() {
                    updateActionButtons(row, status);
                }, 2000);

            } else {
                // Show error state
                clickedButton.removeClass('btn-primary btn-danger btn-warning')
                    .addClass('btn-danger')
                    .html('<i class="fas fa-times"></i> Error');

                // Re-enable buttons and restore original text after 3 seconds
                setTimeout(function() {
                    actionButtons.prop('disabled', false);
                    clickedButton.text(originalText);
                    restoreOriginalButtonClasses(clickedButton);
                }, 3000);

                alert('Failed to update status: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('An error occurred: ' + error);
            console.log('Response Text:', xhr.responseText);

            // Show error state
            clickedButton.removeClass('btn-primary btn-danger btn-warning')
                .addClass('btn-danger')
                .html('<i class="fas fa-times"></i> Error');

            // Re-enable buttons and restore original text after 3 seconds
            setTimeout(function() {
                actionButtons.prop('disabled', false);
                clickedButton.text(originalText);
                restoreOriginalButtonClasses(clickedButton);
            }, 3000);

            alert('An error occurred while updating the status. Please try again.');
        }
    });
}

function updateActionButtons(row, newStatus) {
    const actionCell = row.find('td:nth-child(8)');
    let newButtonsHtml = '';

    // Generate new buttons based on status
    // Assuming: 0 = Enabled, 1 = Disabled, 3 = Archived
    if (newStatus == 0) { // Enabled
        newButtonsHtml = `
            <div class="action-btn-group">
                <button class="btn btn-warning btn-sm disable-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-ban"></i> Disable
                </button>
                <button class="btn btn-secondary btn-sm archive-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-archive"></i> Archive
                </button>
            </div>
        `;
    } else if (newStatus == 1) { // Disabled
        newButtonsHtml = `
            <div class="action-btn-group">
                <button class="btn btn-success btn-sm enable-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-check"></i> Enable
                </button>
                <button class="btn btn-secondary btn-sm archive-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-archive"></i> Archive
                </button>
            </div>
        `;
    } else if (newStatus == 3) { // Archived
        newButtonsHtml = `
            <div class="action-btn-group">
                <button class="btn btn-success btn-sm enable-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-check"></i> Enable
                </button>
                <button class="btn btn-warning btn-sm disable-btn" data-id="${row.find('.btn').first().data('id')}">
                    <i class="fas fa-ban"></i> Disable
                </button>
            </div>
        `;
    }

    actionCell.html(newButtonsHtml);
}

function restoreOriginalButtonClasses(button) {
    // Restore original button classes based on button text/type
    const buttonText = button.text().toLowerCase();
    button.removeClass('btn-success btn-danger');

    if (buttonText.includes('enable')) {
        button.addClass('btn-success');
    } else if (buttonText.includes('disable')) {
        button.addClass('btn-warning');
    } else if (buttonText.includes('archive')) {
        button.addClass('btn-secondary');
    }
}
                        </script>





                    </div>





                </div>




                <?php

                ?>

            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <p>Copyright &copy;<?php echo date("Y"); ?>, University of Ibadan, Postgraduate College. All
                            Rights Reserved.</p>
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
    <!-- <script>
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
    </script> -->
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
                    // Also update the name for the degree dropdown if present
                    newInputSection.find('select[name^="sections[0][degree]"]').each(function() {
                        var originalName = $(this).attr('name');
                        var newName = originalName.replace(/\[0\]/, '[' + currentSectionIndex + ']');
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