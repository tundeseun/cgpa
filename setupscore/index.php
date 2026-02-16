<?php session_start();
$dept=$_SESSION["dept_new"];
$name=$_SESSION["name"];
include_once('../function/script.php');

if(!isset($_SESSION["dept_new"]) && !isset($_SESSION["name"])){
  session_destroy();
  header('Location: ../');
}
$admin = $_SESSION["name"];
if(isset($_POST['logout'])){
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
<!--  <link rel="stylesheet" href="style.css">-->
  <style>
      .trash i{
          margin-top: 0.3rem;
      }
      #nav1{
          display: flex;
          justify-content: space-evenly;
          align-items: center;
          text-align: center;
          background: #fff;
          
      }
      .container{
    margin: auto;
    max-width: 1100px;
    padding: 1rem;
    overflow: auto;
    
}
      .logout{
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
          }}
      @media (max-width:850px) {
    #nav1 h1 {
        font-size: 1.2rem;
          }}

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
      select, input {
            padding-left: 2rem  !important;
            padding-top: 1rem  !important;
            padding-bottom: 1rem  !important;
          border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            width: 100%;
            outline: none;
            border: 1px solid #0a2b4f;
            
            

        }
      .contain{
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
            <h1 class="h3 mb-0 text-gray-800"><?php if(isset($dept)){showdept($dept,$conn);}  ?></h1>
            
          </div>
          
                <div id="form" class="contain">
                    
                     <form action="" method="post">
                        <div class="form-group">
                            <label for="programme">Select Session Examined:</label>
                            <select name="sec" >
                              <?php 
                              include_once("function/connect.php");
                              showsessionexamined($conn)
                             ?>
                            </select>
                            
                        </div>
                        <!-- <div class="form-group">
                            <label for="specialization">Select Specialization:</label>
                            <select name="" id="specialization">
                               <option value=""></option>
                                <option value="">
                                    Special1
                                </option><option value="" >
                                    Special2
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="session"> Select Session Examined:</label>
                            <select name="" id="session">
                               <option value=""></option>
                                <option value="">
                                    session1
                                </option><option value="" >
                                    session2
                                </option>
                            </select>
                        </div> -->

                        <input type='submit' value='Submit' name='send' class='btn'>

                        <?php

if(isset($_POST['send']))
{
  $sec=$_POST['sec'];
  header('Location:../dashboard.php?p=score&sec='.$sec);
}


?>
                    </form>
                    
                </div>
          
         

         

        </div>
       
        

       
      </div>
         <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <p>Copyright &copy;<?php echo date("Y");?>, University of Ibadan, Postgraduate College. All Rights Reserved.</p>
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
