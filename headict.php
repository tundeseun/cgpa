<?php 

session_start();
$user=$_SESSION["user"];

include_once('function/script.php');


$query_name = "SELECT DISTINCT name FROM users_cgpa_new WHERE username = '$user'";
$result_name = mysqli_query($conn, $query_name);

    $row_name = mysqli_fetch_assoc($result_name);
    $name_head = $row_name['name'];

// if (isset($_GET["name"])) {
//   $_SESSION["name"] = $_GET["name"];
// }
if(!($user)){
  session_destroy();
  header('Location: ../');
}

if(isset($_POST["logout"])){
  session_destroy();
  header('Location: ../');
}


//when submit button is clicked
if (isset($_POST['submit'])) {
 
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
  

addUser($conn, $name, $username, $password, $department, $user);

} 
  




//when submit button in edit modal is clicked
if (isset($_POST['edit'])) {
 
    $name = $_POST['Ename'];
    $username = $_POST['Eusername'];
    $password = $_POST['Epassword'];
    $department = $_POST['Edepartment'];
    $id = $_POST['Eid'];
    updateUser($conn, $name, $username, $password, $department,$id,$user);
    

  }




if (isset($_GET['status'])) {
  $user_id = $_GET['user_id'];
  $status = $_GET['status'];
  statusUsers($conn, $user_id, $status,$user);
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

    .modal-body{
      padding-bottom: 0!important;
    }
    .modal-body img{
        width: 10rem;
    }
  
    .modal-body .img{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 100%;
        
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
    .note{
      font-size: 0.75rem;
      color: #dc3545;
    }
  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.html">
        <div class="sidebar-brand-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php echo $name_head;
; ?></div>
      </a>


      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="#">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">


      <li class="nav-item ">
        <a class="nav-link" href="../dashboard.php?p=headsec&user=<?php echo $user ?>">
          <i class="fas fa-fw fa-plus"></i>
          <span>Add Section</span></a>
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

                <img style="width: 30px;" src="../img/ui-logo.png" class="logo">

            </div>
-->
            <img style="width: 30px;" src="../img/ui-logo.png" class="logo">
            <div class="topbar-divider d-none d-sm-block"></div>
            <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="../img/logo.png" class="logo">
            <h1 class="h3 m-0 font-weight-100 text-primary" style="">Result Processing Application</h1>
            <!--
            <div class="top-logo">

                <img style="width: 50px;" src="../img/logo.png" class="logo">

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
            <h1 class="h3 mb-0 text-gray-800">Create User (PG Coordinator)</h1>

            <!-- Button trigger modal -->
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addHod">
              <i class="fas fa-plus fa-sm text-white-50"></i> Add User
            </button>

            <!-- Modal -->
            <div class="modal fade" id="addHod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <form action="" method="post" class="forms" enctype="multipart/form-data">

                    <div class="modal-body">
                     
                      <div class="form-group">
                        <label for="lname">Name:</label>
                        <input type="text" name="name" id="lname">

                      </div>
                      <div class="form-group">
                        <label for="fname">Username:</label>
                        <input type="text" name="username" id="fname">

                      </div>
                      <div class="form-group">
                        <label for="initial">Password:</label>
                        <input type="text" name="password" id="initial">

                      </div>
                      
                      <div class="form-group">
                                <label for="department">Select Department</label>
                                
                                <select id="department" name="department">
                                  

                                  <?php
                                                    $queryDept = getDepartment($conn);
                                                    while ($rowDept = mysqli_fetch_assoc($queryDept)) {


                                                    ?>
                            <option value="<?php echo $rowDept['id']; ?>">
                              <?php echo $rowDept['department']; ?>
                            </option>
                          <?php } ?>
                                                    </select>

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
                <th> Name</th>
                <th> Department</th>
                <th> Edit</th>
                <th> Enable/Disable</th>


              </tr>
              <?php
              $queryDisplay = displayUsers($conn);
              $i = 0;
              while ($rowDisplay = mysqli_fetch_assoc($queryDisplay)) {
                $i++;



              ?>
                <tr>
                  <td>
                    <?php echo $i; ?>
                  </td>
                  <td>
                    <?php echo $rowDisplay['name'];?>
                  </td>
                  <td>
                    <?php $dept_id = $rowDisplay['dept_new'];
                    $department = getUsersDepartment($conn, $dept_id);
                    echo $department;
                    ?>
                  </td>
               
               
                  
                  <td>
                    <!-- Button trigger modal -->

                    <button type="button" class="success" id="modalB" data-toggle="modal" data-target="#editHod" data-name="<?php echo $rowDisplay['name']; ?>" data-id="<?php echo $rowDisplay['id']; ?>" data-username="<?php echo $rowDisplay['username']; ?>" data-password="<?php echo $rowDisplay['password']; ?>" data-dept="<?php echo $department; ?>">

                      Edit


                    </button>


                    <!-- Modal -->
                    <div class="modal fade" id="editHod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit User</h5>
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
                        <label for="lname">Name:</label>
                        <input type="text" name="Ename" id="name">

                      </div>
                      <div class="form-group">
                        <label for="fname">Username:</label>
                        <input type="text" name="Eusername" id="username">

                      </div>
                      <div class="form-group">
                        <label for="initial">Password:</label>
                        <input type="text" name="Epassword" id="pass">

                      </div>
                      <div class="form-group">
                        <label for="initial">Currently Saved Department:</label>
                        <input type="text" name="" id="dept" readonly>

                      </div>
                              <div class="form-group">
                                <label for="title">Select Department Again:&nbsp;<span class="note">(please confirm department)</span></label>
                                
                                <select id="title" name="Edepartment">
                                  

                                <?php
                                                    $queryDept = getDepartment($conn);
                                                    while ($rowDept = mysqli_fetch_assoc($queryDept)) {


                                                    ?>
                            <option value="<?php echo $rowDept['id']; ?>">
                              <?php echo $rowDept['department']; ?>
                            </option>
                          <?php } ?>           </select>

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
                    $user_id = $rowDisplay['id'];
                    $status = $rowDisplay['status'];
                    if ($status == 0) {

                      echo "<a href='../headict.php?user=$user&user_id=$user_id&status=$status' class='danger'>Disable</a>";
                    } elseif ($status == 1) {
                      echo "<a href='../headict.php?user=$user&user_id=$user_id&status=$status' class='success'>Enable</a>";
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
       
        var name = this.getAttribute('data-name');
        document.getElementById('name').value = name;

        var username = this.getAttribute('data-username');
        document.getElementById('username').value = username;

        var pass = this.getAttribute('data-password');
        document.getElementById('pass').value = pass;

        var id = this.getAttribute('data-id');
        document.getElementById('modalUser').value = id;
        
        var dept = this.getAttribute('data-dept');
        document.getElementById('dept').value = dept;
        
        
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