<?php session_start();

$dept = $_SESSION["dept_new"];
//$sec=$_SESSION["sec"];
$name = $_SESSION["name"];
include_once('../function/script.php');

$admin = $_SESSION["name"];
if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: ../');
}

if (!isset($_SESSION["dept_new"]) && !isset($_SESSION["name"])) {
  session_destroy();
  header('Location: ../');
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

  <title>Result Processing - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <!--  <link rel="stylesheet" href="style.css">-->
  <style>


    table {
      border-collapse: collapse;
      width: 100%;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .edit-btn,
    .delete-btn {
      padding: 5px 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 3px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
    }

    .edit-btn:hover,
    .delete-btn:hover {
      background-color: #45a049;
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

    .score {
      padding: 0 !important;
      padding-left: 0.5rem !important;
      margin-bottom: 0 !important;
      border-radius: 0 !important;
    }

    .code {
      border: none !important;
      padding: 0 !important;
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

    .link,
    .link:hover {
      background: #1cc88a;
      border-radius: 0.2rem;
      color: #fff !important;
      transition: 0.3s ease-in-out !important;
      color: #fff;
      font-weight: bolder;
      width: fit-content !important;
      padding: 0.6rem !important;



    }

    #form form .btn:hover {
      background: #0a2b4f;
      border: 1px solid #0a2b4f !important;
      color: #fff;

    }

    .name {
      text-align: center !important;
    }

    .fas {
      font-size: 0.75rem;

    }

    .s {
      width: 2rem !important;
    }
    .hiddenBtn{
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-end;
    }

    .formtohide{
      display: none;
    }
    .formtoshow{
      display: block;
    }

    .form-container {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    padding: 2rem;
}

.nav-tabs-custom {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 10px;
}

.nav-tab-btn {
    background: #f8f9fc;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    color: #858796;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.nav-tab-btn.active {
    background: #0a2b4f;
    color: white;
}

.nav-tab-btn:hover:not(.active) {
    background: #eaecf4;
}

.form-title {
    color: #2c3338;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    padding-bottom: 10px;
    border-bottom: 2px solid #e3e6f0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #0a2b4f;
    font-weight: 600;
    font-size: 0.9rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d3e2;
    border-radius: 5px;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #0a2b4f;
    outline: none;
}

.btn-delete {
    background: #e74a3b;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-delete:hover {
    background: #d52a1a;
    transform: translateY(-1px);
}

.alert-info {
    background:rgb(243, 231, 230);
    border-left: 4px solid rgb(209, 78, 66);
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 5px;
    color: #d52a1a;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%230a2b4f' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}

  .select2-container--default .select2-selection--single {
      height: 45px !important;
      padding: 8px 12px !important;
      border: 1px solid #d1d3e2 !important;
      border-radius: 5px !important;
      background-color: #fff !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #6e707e !important;
      line-height: 28px !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 43px !important;
      right: 8px !important;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #0a2b4f !important;
  }

  .select2-results__option {
      padding: 8px 12px !important;
      color: #6e707e !important;
  }

  .select2-container--default .select2-search--dropdown .select2-search__field {
      border: 1px solid #d1d3e2 !important;
      padding: 8px !important;
  }

  .select2-dropdown {
      border: 1px solid #d1d3e2 !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
  }

  .select2-container--default .select2-results__option[aria-selected=true] {
      background-color: #0a2b4f !important;
      color: #fff !important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #0a2b4f !important;
      line-height: 28px !important;
  }

  .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #0a2b4f !important;
      color: #fff !important;
}
  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <?php
    include('../menu/menu.php');

    ?>
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

          <div id="form" class="form-container">
    <div class="nav-tabs-custom">
        <button class="nav-tab-btn active" onclick="toggleForms('matric')">
            <i class="fas fa-user-graduate"></i> By Matric Number
        </button>
        <button class="nav-tab-btn" onclick="toggleForms('course')">
            <i class="fas fa-book"></i> By Course Code
        </button>
        <button class="nav-tab-btn" onclick="toggleForms('date')">
            <i class="fas fa-calendar-alt"></i> By Effective Date
        </button>
    </div>

    <!-- Matric Number Form -->
    <form class="formtoshow" id="matricForm">
        <h4 class="form-title">Delete Results By Matric Number</h4>
        <div class="alert-info">
            <i class="fas fa-exclamation-triangle"></i> Warning:  This will delete all results for the specified matric number and effective date.
        </div>
        
        <div class="form-group">
            <label for="matric">Matric Number</label>
            <select id="matric" class="form-control" data-placeholder="Search Matric Number">
                <option></option>
                <?php
                $query_matric = "SELECT DISTINCT matric 
                                FROM testscore 
                                WHERE dept = '$dept' 
                                ORDER BY matric ASC";
                $result_matric = mysqli_query($conn, $query_matric);
                while ($row_matric = mysqli_fetch_assoc($result_matric)) {
                    echo "<option value='{$row_matric['matric']}'>{$row_matric['matric']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="effectivedate">Effective Date</label>
            <select id="effectivedate" class="form-control" data-placeholder="Select Effective Date">
                <option></option>
                <?php
                $query = "SELECT DISTINCT effectivedate FROM testscore WHERE dept = '$dept' ORDER BY effectivedate DESC";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['effectivedate']}'>{$row['effectivedate']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="button" class="btn-delete" onclick="confirmDeleteByMatric()">
            <i class="fas fa-trash-alt"></i> Delete Results
        </button>
    </form>

    <!-- Course Code Form -->
    <form class="formtohide" id="courseForm">
        <h4 class="form-title">Delete Results By Course Code</h4>
        <div class="alert-info">
            <i class="fas fa-exclamation-triangle"></i> Warning:  This will delete all results for the selected course and effective date.
        </div>

        <div class="form-group">
    <label for="code">Course Code</label>
    <select id="code" class="form-control" data-placeholder="Search Course Code">
        <option></option>
        <?php
$query_course = "SELECT DISTINCT testscore.cozid, course_new.course_code, testscore.cstatus, testscore.cunit, field_new.field_title 
          FROM testscore 
          INNER JOIN course_new ON course_new.id = testscore.cozid 
          INNER JOIN field_new ON field_new.id = testscore.field
          WHERE testscore.dept = '$dept' 
          AND testscore.status = 0 
          AND course_new.status2 = 0
          ORDER BY course_new.course_code";
$result_course = mysqli_query($conn, $query_course);
while ($row_course = mysqli_fetch_assoc($result_course)) {
echo "<option value='{$row_course['cozid']}'>{$row_course['course_code']} - {$row_course['cstatus']} - {$row_course['cunit']}  - {$row_course['field_title']}</option>";
}
        ?>
    </select>
</div>

        <div class="form-group">
            <label for="effectivedate2">Effective Date</label>
            <select id="effectivedate2" class="form-control" data-placeholder="Select Effective Date">
                <option></option>
                <?php
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['effectivedate']}'>{$row['effectivedate']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="button" class="btn-delete" onclick="confirmDeleteByCourse()">
            <i class="fas fa-trash-alt"></i> Delete Results
        </button>
    </form>

    <!-- Effective Date Form -->
    <form class="formtohide" id="dateForm">
        <h4 class="form-title">Delete Results By Effective Date</h4>
        <div class="alert-info">
            <i class="fas fa-exclamation-triangle"></i> Warning: This will delete ALL results for the selected effective date.
        </div>

        <div class="form-group">
            <label for="effectivedate3">Effective Date</label>
            <select id="effectivedate3" class="form-control" data-placeholder="Select Effective Date">
                <option></option>
                <?php
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['effectivedate']}'>{$row['effectivedate']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="button" class="btn-delete" onclick="confirmDeleteByDate()">
            <i class="fas fa-trash-alt"></i> Delete All Results
        </button>
    </form>
</div>

        </div>




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
function toggleForms(formType) {
    // Update tab buttons
    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.closest('.nav-tab-btn').classList.add('active');

    // Hide all forms
    document.querySelectorAll('form').forEach(form => {
        form.classList.add('formtohide');
        form.classList.remove('formtoshow');
    });

    // Show selected form
    const formToShow = document.getElementById(formType + 'Form');
    formToShow.classList.remove('formtohide');
  formToShow.classList.add('formtoshow');
}
</script>

<script>
$(document).ready(function() {
    $('#matric').select2({
        placeholder: "Search Matric Number",
        allowClear: true,
        width: '100%',
        theme: "classic",
        minimumInputLength: 1,
        dropdownParent: $('#matricForm')
    });

    // Reinitialize Select2 when switching tabs
    $('.nav-tab-btn').on('click', function() {
        setTimeout(function() {
            $('#matric').select2({
                placeholder: "Search Matric Number",
                allowClear: true,
                width: '100%',
                theme: "classic",
                minimumInputLength: 1,
                dropdownParent: $('#matricForm')
            });
        }, 100);
    });
});
</script>

<!-- Add SweetAlert2 after jQuery and before your other scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Add these delete functions -->
<script>
function confirmDeleteByMatric() {
    const matric = $('#matric').val();
    const effectivedate = $('#effectivedate').val();

    if (!matric || !effectivedate) {
        Swal.fire({
            icon: 'error',
            title: 'Required Fields',
            text: 'Please select both Matric Number and Effective Date'
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: `Delete all results for ${matric}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_handler.php',
                type: 'POST',
                data: {
                    type: 'matric',
                    matric: matric,
                    effectivedate: effectivedate
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire(
                            'Deleted!',
                            'Results have been deleted successfully.',
                            'success'
                        ).then(() => {
                           location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete results.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}

function confirmDeleteByCourse() {
    const code = $('#code').val();
    const effectivedate = $('#effectivedate2').val();

    if (!code || !effectivedate) {
        Swal.fire({
            icon: 'error',
            title: 'Required Fields',
            text: 'Please select both Course Code and Effective Date'
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: `Delete all results for this course?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'delete_handler.php',
                type: 'POST',
                data: {
                    type: 'course',
                    code: code,
                    effectivedate: effectivedate
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire(
                            'Deleted!',
                            'Results have been deleted successfully.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete results.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}

function confirmDeleteByDate() {
    const effectivedate = $('#effectivedate3').val();

    if (!effectivedate) {
        Swal.fire({
            icon: 'error',
            title: 'Required Field',
            text: 'Please select an Effective Date'
        });
        return;
    }

    Swal.fire({
        title: 'Delete All Results?',
        text: `This will delete ALL results for ${effectivedate}. This action cannot be undone!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete all!',
        cancelButtonText: 'Cancel',
        footer: '<strong>Warning: This is a bulk delete operation!</strong>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Double confirmation for bulk deletion
            Swal.fire({
                title: 'Final Confirmation',
                text: 'Are you absolutely sure? This will delete ALL results for the selected date!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, proceed!'
            }).then((finalResult) => {
                if (finalResult.isConfirmed) {
                    $.ajax({
                        url: 'delete_handler.php',
                        type: 'POST',
                        data: {
                            type: 'date',
                            effectivedate: effectivedate
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'All results have been deleted successfully.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message || 'Failed to delete results.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    });
}
</script>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Initialize Select2 -->
  <script>
  $(document).ready(function() {
      // Initialize all select boxes with Select2
      $('.form-control').select2({
          width: '100%',
          
          placeholder: function(){
              return $(this).data('placeholder');
          },
          allowClear: true
      });

      // Reinitialize Select2 when switching tabs
      $('.nav-tab-btn').on('click', function() {
          setTimeout(function() {
              $('.form-control').select2({
                  width: '100%',
                  
                  placeholder: function(){
                      return $(this).data('placeholder');
                  },
                  allowClear: true
              });
          }, 100);
      });
  });
  </script>

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