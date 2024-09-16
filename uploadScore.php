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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Welcome&nbsp;back</div>
      </a>

   
      <!-- Nav Item - Dashboard -->
      <li class="nav-item ">
        <a class="nav-link" href="index.html">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSet" aria-expanded="true" aria-controls="collapseSet">
          <i class="fas fa-fw fa-cog"></i>
          <span>Set Up</span>
        </a>
        <div id="collapseSet" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item" href="#">Create HOD</a>
            <a class="collapse-item" href="#">Create Sub-Dean</a>
            <a class="collapse-item" href="#">Create External Examiner</a>
            <a class="collapse-item" href="#">External Examiner Signature</a>
          </div>
        </div>
      </li>


      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>View Course</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-table"></i>
          <span>Upload Score</span></a>
      </li>

      
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
          <i class="fas fa-fw fa-cog"></i>
          <span>Report</span>
        </a>
        <div id="collapseReport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item" href="#">Result</a>
            <a class="collapse-item" href="#">Graduating List (PDF)</a>
            <a class="collapse-item" href="#">Graduating List (WORD)</a>
          </div>
        </div>
      </li>
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
            <img style="width: 30px;" src="img/ui-logo.png" class="logo">
              <div class="topbar-divider d-none d-sm-block"></div>
              <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="img/logo.png" class="logo">
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

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Upload Score</h1>
            
          </div>
          
                <div id="form" class="contain">
                    
                     <form action="">
                        <div class="form-group">
                            <label for="programme">Select Programme:</label>
                            <select name="" id="programme">
                               <option value=""></option>
                                <option value="" >
                                    Program1
                                </option><option value="" >
                                    Program2
                                </option>
                            </select>
                            
                        </div>
                        <div class="form-group">
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
                        </div>

                        <input type='submit' value='Submit' name='send' class='btn'>
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
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
