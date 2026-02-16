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
  </style>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Result Processing - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

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

    .hiddenBtn {
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-end;
    }

    .formtohide {
      display: none;
    }

    .formtoshow {
      display: block;
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
            } ?></h1>

          </div>

          <div id="form" class="contain">


            <form class="formtoshow" method="post" enctype="multipart/form-data">
              <div class="hiddenBtn">

                <a class="link" type="button" onclick="toggleForms(event)">Edit With Course Code</a>
              </div>
              <!-- <div class="form-group">
                <label for="programme">Select Course:</label>
                <select name="coz">
                  <?php //showcourse2($conn,$dept);
                  ?>
                            </select>
                            
                          </div> -->

              <h4> Edit Student Result With Matric Number</h4>
              <div class="form-group">

                <label for="matric">Enter Matric Number:</label>
                <input type="text" name="matric" id="matric" placeholder="Matric Number">

              </div>

              <div class="form-group">

                <label for="effectivedate">Select Effective Date:</label>
                <select id="effectivedate" name="effectivedate">
                  <option value="">Select Effective Date</option>
                  <?php

                  $query = "SELECT DISTINCT effectivedate FROM testscore WHERE dept = '$dept'";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {


                    ?>

                    <option value="<?php echo $row['effectivedate']; ?>"><?php echo $row['effectivedate']; ?></option>
                  <?php } ?>
                </select>

              </div>

              <input type="submit" value="Submit Matric Number" class="btn" name="submitMatric">

              <!-- <input type='submit' value='Submit' name='send' class='btn'> -->
            </form>
            <form class="formtohide" method="post" enctype="multipart/form-data">
              <div class="hiddenBtn">

                <a class="link" type="button" onclick="toggleForms(event)">Edit With Matric Number</a>
              </div>
              <!-- <div class="form-group">
                <label for="programme">Select Course:</label>
                <select name="coz">
                  <?php //showcourse2($conn,$dept);
                  ?>
                            </select>
                            
                          </div> -->

              <h4> Edit Student Result With Course Code</h4>
              <div class="form-group">

                <label for="code">Enter Course Code:</label>
                <select id="code" name="code">
                  <option value="">Select Course Code</option>
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

                <label for="effectivedate">Select Effective Date:</label>
                <select id="effectivedate" name="effectivedate">
                  <option value="">Select Effective Date</option>
                  <?php

                  $query = "SELECT DISTINCT effectivedate FROM testscore WHERE dept = '$dept'";
                  $result = mysqli_query($conn, $query);
                  while ($row = mysqli_fetch_assoc($result)) {


                    ?>

                    <option value="<?php echo $row['effectivedate']; ?>"><?php echo $row['effectivedate']; ?></option>
                  <?php } ?>
                </select>

              </div>

              <input type="submit" value="Submit Course Code" class="btn" name="submitCode">

              <!-- <input type='submit' value='Submit' name='send' class='btn'> -->
            </form>




            <?php

            // include('function/script.php');
            if (isset($_POST['submitMatric'])) {
              $matric = $_POST['matric'];
              $effectivedate = $_POST['effectivedate'];

              $query_lock = "SELECT DISTINCT status FROM testscore WHERE matric = '$matric' AND effectivedate = '$effectivedate'";
              $result_lock = mysqli_query($conn, $query_lock);
              $row_lock = mysqli_fetch_assoc($result_lock);
              $lock_status = $row_lock['status'];

              if ($lock_status == 1) {

                echo "<script>alert('Student Result Has Been Locked');</script>";
              } elseif ($lock_status == 0) {

                header('location: ../dashboard.php?p=stud&matric=' . $matric . '&effectivedate=' . $effectivedate);
              }
            }


            if (isset($_POST['submitCode'])) {

              $code = $_POST['code'];
              $effectivedate = $_POST['effectivedate'];

              $query_lock = "SELECT DISTINCT status FROM testscore WHERE cozid = '$code' AND effectivedate = '$effectivedate'";
              $result_lock = mysqli_query($conn, $query_lock);
              $row_lock = mysqli_fetch_assoc($result_lock);
              $lock_status = $row_lock['status'];

              if ($lock_status == 1) {

                echo "<script>alert('Student Result Has Been Locked');</script>";
              } elseif ($lock_status == 0) {

                header('location: ../dashboard.php?p=couserCode&code=' . $code . '&effectivedate=' . $effectivedate);
              }



            }
            ?>

          </div>

        </div>




      </div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <p>Copyright &copy;<?php echo date("Y"); ?>, University of Ibadan, Postgraduate College. All Rights
              Reserved.</p>
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
    function toggleForms() {
      var formToShow = document.querySelector('.formtohide');
      var formToHide = document.querySelector('.formtoshow');

      formToHide.classList.add('formtohide');
      formToHide.classList.remove('formtoshow');

      formToShow.classList.add('formtoshow');
      formToShow.classList.remove('formtohide');
    }
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