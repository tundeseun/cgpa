<?php
session_start();
include_once('function/script.php');

// Get department info from GET or SESSION
$dept_new = isset($_POST['dept_new']) ? $_POST['dept_new'] : (isset($_SESSION['dept_new']) ? $_SESSION['dept_new'] : null);
$name = isset($_POST['name']) ? $_POST['name'] : (isset($_SESSION['name']) ? $_SESSION['name'] : null);
$user = isset($_POST['user']) ? $_POST['user'] : (isset($_SESSION['user']) ? $_SESSION['user'] : null);

if (!$dept_new || !$name || !$user) {
    // Not logged in or missing info
    header('Location: ./');
    exit;
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ./');
}
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_courses'])) {
    $updates = $_POST['degree_id'] ?? [];
    foreach ($updates as $course_id => $degree_id) {
        $degree_id = trim($degree_id);
        if ($degree_id !== '') {
            $stmt = $conn->prepare("UPDATE course_new SET degree_id = ? WHERE id = ? AND dept_newids = ?");
            $stmt->bind_param("sis", $degree_id, $course_id, $dept_new);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch courses with missing degree_id
$stmt = $conn->prepare("SELECT id, course_code, course_title FROM course_new WHERE dept_newids = ? AND (degree_id IS NULL OR degree_id = '' OR degree_id = 0)");
$stmt->bind_param("s", $dept_new);
$stmt->execute();
$result = $stmt->get_result();
$courses = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// If all courses are updated, redirect to dashboard
if (empty($courses)) {
    $_SESSION['dept_new'] = $dept_new;
    $_SESSION['name'] = $name;
    $_SESSION['user'] = $user;
    header("Location: dashboard.php?p=1&dept_new=$dept_new&name=$name&user=$user");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Courses - Degree ID Required</title>
    <link rel="stylesheet" href="css/sb-admin-2.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background: #f8f9fc; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; padding: 2rem; }
        h2 { color: #e74a3b; }
        table { width: 100%; margin-top: 1rem; }
        th, td { padding: 0.5rem; text-align: left; }
        th { background: #0a2b4f; color: #fff; }
        input[type="text"] { width: 100%; border-radius: 4px; border: 1px solid #ccc; padding: 0.3rem; }
        .btn { background: #0a2b4f; color: #fff; border: none; padding: 0.5rem 1.5rem; border-radius: 4px; margin-top: 1rem; }
        .btn:disabled { background: #ccc; }
        .alert { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
        .alert-custom { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .form-control { min-width: 100px; }
         #navchecker {
            background: #fff;
            display: flex;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
    </style>
</head>
<body id="page-top">

<nav id="navchecker">
        <div class="mr">
            <img style="width:40px" src="img/ui-logo.png" class="logo">
        </div>

        <div>
            <h1 class="h3 m-0 font-weight-100 text-primary" style="">University of Ibadan,<br>The&nbsp;Postgraduate&nbsp;College</h1>

        </div>
        <div class="ml">
            <img style="width:50px" src="img/logo.png" class="logo">
        </div>





    </nav>
    <div id="wrapper">
        <!-- Sidebar/menu removed for focused UI -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Navbar removed for focused UI -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Update Degree ID for Courses</h1>
                    </div>
                    <div class="alert alert-custom mb-4">
                        <b>Note:</b> You must update the <b>Degree ID</b> for all listed courses before you can access the portal.
                    </div>
                    <form method="post" id="updateForm">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Courses Missing Degree ID</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="coursesTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S/N</th>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Unit</th>
                                                <th>Status</th>
                                                <th>Specialization</th>
                                                <th>Degree</th>
                                                <th>Archive</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <button type="submit" name="update_courses" class="btn btn-primary mt-3">Update Courses</button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy;<?= date("Y") ?>, University of Ibadan, Postgraduate College. All Rights Reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#coursesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: 'getCoursesMissingDegree.php',
                    type: 'POST',
                    data: { dept_new: '<?php echo $dept_new; ?>' },
                },
                columns: [
                    { data: 'sn' },
                    { data: 'course_code' },
                    { data: 'course_title' },
                    { data: 'unit' },
                    { data: 'status' },
                    { data: 'specialization' },
                    { data: 'degree_input', orderable: false, searchable: false },
                    { data: 'archive', orderable: false, searchable: false }
                ]
            });

            // After table draw, populate degree dropdowns and set up handlers
            table.on('draw', function() {
                // If no data, redirect to ./
                if (table.data().count() === 0) {
                    window.location.href = './';
                    return;
                }
                // Fetch degrees for the department
                $.post('getDegreesForDept.php', { dept_new: '<?php echo $dept_new; ?>' }, function(response) {
                    if (response.success) {
                        var options = '<option value="">Select Degree</option>';
                        response.degrees.forEach(function(degree) {
                            options += '<option value="' + degree.id + '">' + degree.name + '</option>';
                        });
                        // Populate all degree dropdowns
                        $('.degree-select').each(function() {
                            var current = $(this).val();
                            $(this).html(options).val(current);
                        });
                    }
                });

                // Degree change handler
                $('.degree-select').off('change').on('change', function() {
                    var courseId = $(this).data('course-id');
                    var degreeId = $(this).val();
                    var row = $(this).closest('tr');
                    var courseCode = row.find('td:eq(1)').text();
                    var courseTitle = row.find('td:eq(2)').text();
                    var degreeName = $(this).find('option:selected').text();
                    if (degreeId) {
                        Swal.fire({
                            icon: 'question',
                            title: 'Confirm Degree Assignment',
                            html: '<b>Course:</b> ' + courseCode + ' - ' + courseTitle + '<br><b>Assign Degree:</b> ' + degreeName,
                            showCancelButton: true,
                            confirmButtonText: 'Yes, assign',
                            cancelButtonText: 'Cancel'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                $.post('updateDegreeForCourse.php', { course_id: courseId, degree_id: degreeId }, function(resp) {
                                    if (resp.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated!',
                                            text: 'Degree updated successfully.',
                                            timer: 1200,
                                            showConfirmButton: false
                                        });
                                        table.ajax.reload(null, false);
                                    } else {
                                        Swal.fire('Error', resp.error || 'Could not update degree.', 'error');
                                    }
                                }, 'json');
                            } else {
                                // Optionally reset the dropdown if cancelled
                                $(row).find('.degree-select').val('');
                            }
                        });
                    }
                });

                // Archive button handler
                $('.archive-btn').off('click').on('click', function() {
                    var courseId = $(this).data('course-id');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Archive Course?',
                        text: 'Are you sure you want to archive this course? It will no longer appear in this list.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, archive it!',
                        cancelButtonText: 'Cancel'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.post('archiveCourse.php', { course_id: courseId }, function(resp) {
                                if (resp.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Archived!',
                                        text: 'Course archived successfully.',
                                        timer: 1200,
                                        showConfirmButton: false
                                    });
                                    table.ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error', resp.error || 'Could not archive course.', 'error');
                                }
                            }, 'json');
                        }
                    });
                });
            });

            // On form submit, collect all degree_id inputs and submit
            $('#updateForm').on('submit', function (e) {
                // Optionally, you can validate here
                // DataTables will keep the input names as degree_id[course_id]
            });
        });
    </script>
</body>
</html> 