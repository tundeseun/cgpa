<?php session_start();
$dept=$_SESSION["dept_new"];
$name=$_SESSION["name"];
include_once('../function/script.php');

$admin = $_SESSION["name"];
if(isset($_POST['logout'])){
  session_destroy();
  header('Location: ../');
}

if(!($dept && $admin && $name)){
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

  <title>View Registered Students - Dashboard</title>

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
      max-width: 100%;
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
  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('../menu/menu.php'); ?>
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
            <h1 class="h3 mb-0 text-gray-800">View Registered Students</h1>
            <!--  -->
          </div>

          <div id="form" class="contain">
            <?php
           
          //  getStudentCourseCode($re, $conn);

                             
            
            ?>

            <form action="" method="Post">
              <div class="form-group">
                <label for="session">Select Session:</label>
                <input type="hidden" value="" name="sessioned">
                <select name="sessioned" class="form control" id="">
                  <option value="" selected disabled></option>
                  <?php
                  //include 'conn.php';
                  $query = "SELECT sec FROM sec_examined GROUP BY sec ORDER by sec desc";
                  if ($result = $conn->query($query)) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {


                  ?>
                      <option value="<?php echo $row['sec'] ?>"><?php echo $row['sec'] ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>

              <input type='submit' value='Submit' name='submit' class='btn'>
            </form>

            <?php
            if (isset($_POST["submit"])) {
              $sessioned = $_POST["sessioned"];
              $querygen = "SELECT new.numeration, new.Surname, new.Other_names, new.Telephone, new.email, zmain_app.department, reginvoice.appno, reginvoice.sessioned FROM new INNER JOIN zmain_app ON zmain_app.user_id=new.id INNER JOIN reginvoice ON reginvoice.appno=new.numeration WHERE zmain_app.department='$dept' and reginvoice.sessioned='$sessioned' AND reginvoice.amount_paid=reginvoice.amount_charge AND reginvoice.amount_paid>0 AND zmain_app.degree <> '3' AND zmain_app.degree <> '4' AND zmain_app.degree <> '5'";
              if ($resultgen = $conn->query($querygen)) {



            ?>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>S/N</th>
                        <th>Application No</th>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th>Session Paid</th>
                        <!-- <th>Courses Registered</th> -->
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      $sn = 0;
                      while ($rowgen = $resultgen->fetch_assoc()) { ?>
                        <tr>
                          <td><?php echo ++$sn ?></td>
                          <td><?php echo $rowgen['appno']  ?></td>
                          <td><?php echo $rowgen['Surname'] . ' ' . $rowgen['Other_names'] ?></td>
                          <td><?php echo $rowgen['Telephone']  ?></td>
                          <td><?php echo $rowgen['email'] ?></td>
                          <td><?php echo $rowgen['sessioned'] ?></td>
                          <!-- <td> -->
                            <?php //$appno=$rowgen ['appno']; 
                          
                          //$tt = getStudentCourseID($appno, $conn);
                          //if($tt->num_rows > 0)
                          //{
                          //while($rowid = $tt->fetch_array(MYSQLI_ASSOC)){
                          //  $regid = $rowid['coz_id'];
                        
                          //  echo getStudentCourseCode($regid, $conn);

                          
                          //}
                        //}
                       // else{
                         // echo"Paid but no courses Registered";
                        }
                              // $re = getStudentCourseID($appno, $conn);
                              
                              //   getStudentCourseCode($re, $conn);
                              
                              // while($rowtitle = $ccode->fetch_array(MYSQLI_ASSOC)){
                              //   $coursecode= $rowtitle['course_code'];
                              //   echo $coursecode;
                              // }
                              
                          ?>
                          <!-- </td> -->


                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
            <?php }
            //} ?>
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