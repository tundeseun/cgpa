<?php session_start();
$dept = $_SESSION["dept_new"];
$name = $_SESSION["name"];
//$sec=$_SESSION["sec"];
//echo $sec."\n";
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

//when submit button is clicked
if (isset($_POST['submit'])) {

  $allowed_image_extension = array(
    "png",
    "jpg",
    "jpeg",
    "PNG",
    "JPG",
    "JPGE"
  );

  $file_extension = pathinfo($_FILES["signature"]["name"], PATHINFO_EXTENSION);
  // Validate file input to check if is not empty
  if (!file_exists($_FILES["signature"]["tmp_name"])) {
    $title = $_POST['title'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $initial = $_POST['initial'];

    $filename = '';
    addSubdean($conn, $title, $lname, $fname, $initial, $dept, $filename, $admin);
  }
  // Validate file input to check if is with valid extension
  else if (!in_array($file_extension, $allowed_image_extension)) {
    echo "<script>alert('Error: Please Upload valid Signature. Only PNG and JPEG are allowed.')</script>";
  } else {

    $title = $_POST['title'];
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $initial = $_POST['initial'];

    $filename = $_FILES["signature"]["name"];
    $tempname = $_FILES["signature"]["tmp_name"];
    $folder = "../img/" . $filename;

    addSubdeanWithSignature($conn, $title, $lname, $fname, $initial, $dept, $filename, $tempname, $folder, $admin);
  }
}


//when submit button in edit modal is clicked
if (isset($_POST['edit'])) {

  $allowed_image_extension = array(
    "png",
    "jpg",
    "jpeg",
    "PNG",
    "JPG",
    "JPGE"
  );

  $file_extension = pathinfo($_FILES["signature"]["name"], PATHINFO_EXTENSION);
  // Validate file input to check if is not empty
  if (!file_exists($_FILES["signature"]["tmp_name"])) {
    $user = $_POST['Eid'];
    $title = $_POST['Etitle'];
    $lname = $_POST['Elname'];
    $fname = $_POST['Efname'];
    $initial = $_POST['Einitial'];



    updateSubdean($conn, $title, $lname, $fname, $initial, $user, $admin);
  }
  // Validate file input to check if is with valid extension
  else if (!in_array($file_extension, $allowed_image_extension)) {
    echo "<script>alert('Error: Please Upload valid Signature. Only PNG and JPEG are allowed.')</script>";
  } else {

    $user = $_POST['Eid'];
    $title = $_POST['Etitle'];
    $lname = $_POST['Elname'];
    $fname = $_POST['Efname'];
    $initial = $_POST['Einitial'];

    $filename = $_FILES["signature"]["name"];
    $tempname = $_FILES["signature"]["tmp_name"];
    $folder = "../img/" . $filename;

    updateSubdeanWithSignature($conn, $title, $lname, $fname, $initial, $filename, $tempname, $folder, $user, $admin);
  }
}



if (isset($_GET['status'])) {
  $user_id = $_GET['user_id'];
  $status = $_GET['status'];
  statusSubDean($conn, $user_id, $status, $admin);
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


    .contain {
      margin: auto;
      max-width: 800px;
      padding: 1rem;
      overflow: auto;


    }

    /* #addHod form .btn {
            background: #dc3545;
            border: 1px solid #dc3545 !important;
            transition: 0.3s ease-in-out !important;
           color: #fff;
           font-weight: bolder;
        }
  #addHod form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;
      
        } */

    .forms {


      padding: 3rem;
      display: flex;
      flex-direction: column;
      padding-top: 0;
      padding-bottom: 0;
    }

    .forms label {
      color: #0a2b4f;
      display: block;
      font-weight: bold;
    }

    .modal-body {
      padding-bottom: 0 !important;
    }

    .forms .modal-body select,
    .forms .modal-body input {
      padding-left: 2rem !important;
      padding-top: 0.5rem !important;
      padding-bottom: 0.5rem !important;
      border-radius: 0.4rem;
      margin-bottom: 0.5rem;
      width: 100%;
      outline: none;
      border: 1px solid #0a2b4f;



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

    table a {
      color: #fff !important;
    }

    a:hover {
      text-decoration: none;
    }

    .note {
      font-size: 0.75rem;
      color: #dc3545;
    }

    .modal-body img {
      width: 10rem;
    }

    .modal-body .img {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      height: 100%;

    }
  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('../menu/menu.php');  ?>
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
            <h1 class="h3 mb-0 text-gray-800">Create Subdean</h1>

            <!-- Button trigger modal -->
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addHod">
              <i class="fas fa-plus fa-sm text-white-50"></i> Add Sub-Dean
            </button>

            <!-- Modal -->
            <div class="modal fade" id="addHod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Sub-Dean</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form action="" method="post" class="forms" enctype="multipart/form-data">

                    <div class="modal-body">
                      <div class="form-group">
                        <label for="title">Select Title:</label>
                        <select name="title" id="title">
                          <?php
                          $queryTitle = getTitle($conn);
                          while ($rowTitle = mysqli_fetch_assoc($queryTitle)) {


                          ?>
                            <option value="<?php echo $rowTitle['id']; ?>">
                              <?php echo $rowTitle['title']; ?>
                            </option>
                          <?php } ?>
                        </select>

                      </div>
                      <div class="form-group">
                        <label for="lname">Enter Last Name:</label>
                        <input type="text" name="lname" id="lname">

                      </div>
                      <div class="form-group">
                        <label for="fname">Enter First Name:</label>
                        <input type="text" name="fname" id="fname">

                      </div>
                      <div class="form-group">
                        <label for="initial">Enter Initial:</label>
                        <input type="text" name="initial" id="initial">

                      </div>

                      <div class="form-group">

                        <label for="file">Upload Signature:</label>
                        <input type="file" name="signature" id="file">

                      </div>

                    </div>



                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <input type="submit" value="Submit" class="btn btn-primary" name="submit">

                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>



          <div class="contain">
            <table class="table cmt" id="myTable">
              <tr>
                <th>S/N</th>
                <th> Subdean Name</th>
                <th>View Signature</th>
                <th> Edit</th>
                <th> Enable/Disable</th>


              </tr>
              <?php
              $queryDisplay = displaySubdean($conn, $dept);
              $i = 0;
              while ($rowDisplay = mysqli_fetch_assoc($queryDisplay)) {
                $i++;



              ?>
                <tr>
                  <td>
                    <?php echo $i; ?>
                  </td>
                  <td>
                    <?php echo $rowDisplay['title'] . " " . $rowDisplay['lname'] . " " . $rowDisplay['fname'] . " " . $rowDisplay['initial'] ?>
                  </td>

                  <td>
                    <!-- Button trigger modal -->

                    <button type="button" class="success" id="modalSign" data-toggle="modal" data-target="#viewSign" data-sign="<?php $link = $rowDisplay['signature'];
                                                                                                                                echo "$link"; ?>">

                      <i class="fas fa-eye fa-sm text-white-50"></i> View


                    </button>


                    <!-- Modal -->
                    <div class="modal fade" id="viewSign" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">View Signature</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body">

                            <div class="img">
                              <img id="mySign" alt="No Signature for this Examiner, Please Edit and Upload Signature">

                            </div>



                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>


                          </div>

                        </div>
                      </div>
                    </div>
                  </td>

                  <td>
                    <!-- Button trigger modal -->

                    <button type="button" class="success" id="modalB" data-toggle="modal" data-target="#editHod" data-lname="<?php echo $rowDisplay['lname']; ?>" data-fname="<?php echo $rowDisplay['fname']; ?>" data-init="<?php echo $rowDisplay['initial']; ?>" data-title="<?php echo $rowDisplay['title']; ?>" data-user="<?php echo $rowDisplay['id']; ?>">

                      Edit


                    </button>


                    <!-- Modal -->
                    <div class="modal fade" id="editHod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Hod</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form action="" method="post" class="forms" enctype="multipart/form-data">
                            <div class="modal-body">
                              <div class="form-group">
                                <input type="hidden" name="Eid" id="modalUser">
                              </div>
                              <div class="form-group">
                                <label for="title">Select Title:&nbsp;<span class="note">(please confirm title)</span></label>

                                <select id="title" name="Etitle">


                                  <?php
                                  $queryTitleEdit = getTitle($conn);
                                  while ($rowTitleEdit = mysqli_fetch_assoc($queryTitleEdit)) {


                                  ?>
                                    <option value="<?php echo $rowTitleEdit['id']; ?>">
                                      <?php echo $rowTitleEdit['title']; ?>
                                    </option>
                                  <?php } ?>
                                </select>

                              </div>
                              <div class="form-group">
                                <label for="lname">Enter Last Name:</label>
                                <input type="text" name="Elname" id="modalLname">

                              </div>
                              <div class="form-group">
                                <label for="fname">Enter First Name:</label>
                                <input type="text" name="Efname" id="modalFname">

                              </div>
                              <div class="form-group">
                                <label for="initial">Enter Initial:</label>
                                <input type="text" name="Einitial" id="modalInitial">

                              </div>

                              <div class="form-group">

                                <label for="file">Upload Signature:</label>
                                <input type="file" name="signature" id="file">

                              </div>

                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                              <input type="submit" value="Submit" class="btn btn-primary" name="edit">

                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>

                    <?php
                    // $user_id = $rowDisplay['id'];
                    // $status = $rowDisplay['status'];
                    // if ($status == 0) {

                    //   echo "<a href='subdean.php?user_id=$user_id&status=$status' class='danger'>Disable</a>";
                    // } elseif ($status == 1) {
                    //   echo "<a href='subdean.php?user_id=$user_id&status=$status' class='success'>Enable</a>";
                    // }

                    ?>

                    <?php
                    $user_id = $rowDisplay['id'];
                    $status = $rowDisplay['status'];
                    if ($status == 0) {

                      echo "<a href='../function/script.php?user_id=$user_id&admin=$admin&status=$status&subdean=subdean&admin=$admin&dept=$dept' class='danger'>Disable</a>";
                    } elseif ($status == 1) {
                      echo "<a href='../function/script.php?user_id=$user_id&admin=$admin&status=$status&subdean=subdean&admin=$admin&dept=$dept' class='success'>Enable</a>";
                    }

                    ?>



                  </td>


                </tr>

              <?php } ?>

            </table>



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
    document.querySelectorAll('#modalB').forEach(function(button) {
      button.addEventListener('click', function() {

        var lname = this.getAttribute('data-lname');
        document.getElementById('modalLname').value = lname;

        var fname = this.getAttribute('data-fname');
        document.getElementById('modalFname').value = fname;

        var init = this.getAttribute('data-init');
        document.getElementById('modalInitial').value = init;

        var id = this.getAttribute('data-user');
        document.getElementById('modalUser').value = id;

        var title = this.getAttribute('data-title');
        document.getElementById('modalTitle').value = title;
      });
    });

    document.querySelectorAll('#modalSign').forEach(function(button) {
      button.addEventListener('click', function() {

        var imageName = this.getAttribute('data-sign');
        var pathToImage = '../img/' + imageName;

        // Set the src attribute of the image element
        document.getElementById("mySign").src = pathToImage;
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

</body>

</html>