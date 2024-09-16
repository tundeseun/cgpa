<?php
session_start();
include_once('../function/script.php');

$user = $_SESSION["user"];
if (!$user) {
  session_destroy();
  header('Location: ../');
  exit();
}

$query_name = "SELECT DISTINCT name,faculty_id FROM fac_users WHERE username = '$user'";
$result_name = mysqli_query($conn, $query_name);

$row_name = mysqli_fetch_assoc($result_name);
$name_head = $row_name['name'];
$facId = $row_name['faculty_id'];

if (!($user)) {
  session_destroy();
  header('Location: ../');
  exit();
}

if (isset($_POST["logout"])) {
  session_destroy();
  header('Location: ../');
  exit();
}

$admin = $name_head;

if (isset($_GET['department'], $_GET['degree'], $_GET['mode'], $_GET['field'], $_GET['effectivedate'], $_GET['fac_approve'])) {
  // Validate and sanitize inputs
  $dept_id = mysqli_real_escape_string($conn, $_GET['department']);
  $degree_id = mysqli_real_escape_string($conn, $_GET['degree']);
  $field_id = mysqli_real_escape_string($conn, $_GET['field']);
  $effectivedate = mysqli_real_escape_string($conn, $_GET['effectivedate']);
  $mode = mysqli_real_escape_string($conn, $_GET['mode']);

  // Use prepared statements to prevent SQL injection
  $sql = "UPDATE testscore SET stage = 2 WHERE effectivedate = ? AND dept = ? AND degree = ? AND field = ? AND mode = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param('sssss', $effectivedate, $dept_id, $degree_id, $field_id, $mode);

    if ($stmt->execute()) {
      $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Success',
        'message' => 'Result Successfully Approved',
        'redirect' => '../dashboard.php?p=deen&faculty_id=' . $facId . '&user=' . $user
      ];
    } else {
      $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error',
        'message' => 'Unable to Approve Result: ' . $stmt->error
      ];
    }

    $stmt->close();
  } else {
    $_SESSION['alert'] = [
      'type' => 'error',
      'title' => 'Error',
      'message' => 'Failed to prepare the SQL statement: ' . $conn->error
    ];
  }

  header('Location: index.php');
  exit();
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
      max-width: 1100px;
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

    .danger {
      padding: 0.2rem 1rem;
      background: #dc3545;
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

    <!-- Sidebar --> <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.html">
        <div class="sidebar-brand-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php echo $name_head; ?></div>
      </a>


      <!-- Nav Item - Dashboard -->

      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Today's Results</span></a>
      </li>


      <li class="nav-item">
        <a class="nav-link" href="../facultyAllView">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>All Results</span></a>
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



          <div class="contain">
            <div class="head">
              <h4> Processed Result </h4>
              <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addCourse">
                <i class="fas fa-calendar fa-sm text-white-50"></i> Set Faculty Meeting Date
              </button>

              <div class="modal fade" id="addCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalCenterTitle">Set Faculty Meeting Date</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <form method="post" class="forms">

                      <div class="modal-body" id="formfield">
                        <div class="form-group">

                          <div class="course">
                            <div class="group">
                              <div class="form-group">
                                <label for="effective">Effective Date:</label>
                                <select id="effective" name="effective">
                                </select>

                              </div>
                            </div>
                            <div class="group">

                              <label for="facDate">Faculty Meeting Date:</label>
                              <input type="date" name="facDate" id="facDate">
                            </div>


                          </div>



                        </div>




                      </div>




                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        <!-- <input type="submit" value="Submit" class="d-none d-sm-inline-block btn btn-sm btn-primary" name="submit"> -->

                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <p>
                <a href="../create-zip-file.php"><i class="fas fa-download"></i> Download</a>
              </p>

            </div>

            <table class="table cmt" id="myTable">

              <thead>

                <tr>
                  <th> S/N</th>
                  <th> Specialization</th>
                  <th> Result Type</th>
                  <th> Stage </th>
                  <th> Faculty Meeting </th>
                  <th> View Result</th>
                  <th> Action</th>



                </tr>
              </thead>

              <tbody id="userTableBody">

              </tbody>

            </table>

            <div id="pagination">
              <!-- Pagination links will be loaded here -->
            </div>



          </div>



          <?php if (isset($_SESSION['alert'])): ?>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                  icon: '<?php echo $_SESSION['alert']['type']; ?>',
                  title: '<?php echo $_SESSION['alert']['title']; ?>',
                  text: '<?php echo $_SESSION['alert']['message']; ?>',
                  timer: 2000,
                  showConfirmButton: false
                }).then(function() {
                  <?php if (isset($_SESSION['alert']['redirect'])): ?>
                    window.location.href = '<?php echo $_SESSION['alert']['redirect']; ?>';
                  <?php endif; ?>
                });
              });
            </script>
            <?php unset($_SESSION['alert']); ?>
          <?php endif; ?>


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
    $(document).ready(function() {
      function loadUsers(page) {
        $.ajax({
          url: '../fetch_faculty_result.php',
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
                                      <a target ='_blank' href='../ProcessedBoardResult/${result.faculty}/${result.department}/${result.specialization}/${result.resultT}/${result.mode}/BroadSheet.pdf' class='success'>
                                        <i class="fas fa-eye fa-sm text-white-50"></i> View
                                      </a>
                                    </td>
                                    <td>
                                    <a  href='index.php?department=${result.dept_id}&degree=${result.degree_id}&field=${result.field_id}&effectivedate=${result.effectivedate}&mode=${result.smode}&fac_approve=fac_approve' class='danger'>
                                        <i class="fas fa-check fa-sm text-white-50"></i> Approve
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
  <script type="text/javascript" src="../selectnarationexamview.js"></script>

</body>

</html>