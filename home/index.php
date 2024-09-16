<?php session_start();
$dept = $_SESSION["dept_new"];
$name = $_SESSION["name"];
$username = $_SESSION["user"];


include "../function/script.php";

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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    .head{
      display: flex;
      justify-content: space-between;
    }

    .head a:hover{
      text-decoration: none;
    }

  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('../menu/menu.php'); ?>

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

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Number of Registered Student</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><span id="count"></span></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Number of Programs</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800" ><span id="count_programme"></span></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="container-fluid">



            <!-- Pie Chart -->
            <div class="col-xl-12 col-lg-11">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">of Course</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Course1
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Course2
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Course3
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-danger"></i> Course3
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div id="form" class="contain">
            <h4> Processed Result </h4>

<table class="table cmt" id="myTable">

  <thead>

    <tr>
      <th>S/N</th>
      <th> Specialization</th>
      <th> Result Type</th>
      <th> Stage</th>
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
            $.ajax({
                url: '../get_counts.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#count_programme').text(response.count_programme);
                    $('#count').text(response.count);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + error);
                }
            });
        });
    </script>

<script>
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
              rows += `<tr>
                <td>${index + 1 + (page - 1) * 10}</td>
                <td>${result.specialization}</td>
                <td>${result.resultT}</td>
                <td>${result.resultT}</td>
                <td>
                <a target ='_blank' href='../ProcessedBoardResult/${result.faculty}/${result.department}/${result.specialization}/${result.resultT}/BroadSheet.pdf' class='success'><i class="fas fa-eye fa-sm text-white-50"></i> View</a>

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

</body>

</html>