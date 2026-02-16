<?php session_start();
error_reporting(0);
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

// $stringVariable="CSE 701";
// $result = str_replace(' ', '&nbsp;', $stringVariable);
// $result2 = nl2br($result);

// echo $result."\n";
?>

           

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Assign Examiner and Date - Dashboard</title>

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
            max-width: 800px;
            padding: 1rem;
            overflow: auto;

        }

        #form .btn {
            background: #dc3545;
            border: 1px solid #dc3545 !important;
            transition: 0.3s ease-in-out !important;
            color: #fff;
            font-weight: bolder;
        }

        #form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;

        }

        .danger {
            background: #dc3545;
            color: #fff!important;
            font-weight: bolder;

        }
        .danger a{
            color: #fff;
            text-decoration: none;
        }
        .danger:hover{
            background: #0a2b4f;
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
            <?php include('../navbar/nav.php'); ?>

                <div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Assign External Examiner and Effective Date</h1>
    <!--  -->
</div>

<div class="contain">

    <form id="form" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="external">External Examiner<span style="color: red;">*</span></label>
            <input type="hidden" value="" name="external">
            <select name="external" class="form control" id="">
                <option value="" selected disabled></option>
                <?php
                include('conn.php');
                $query = "SELECT id, lname, fname, initial FROM external_cgpa WHERE dept_new = '$dept' AND status = '0'";
                if ($result = $conn->query($query)) {
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {


                ?>
                        <option value="<?php echo $row['id'] ?>"><?php echo $row['lname']. ' ' .$row['fname']?></option>
                    <?php } ?>
                <?php } ?>
            </select>


            <label for="effective_date">Enter Effective Date<span style="color: red;">*</span></label>
            <input type="date" value="" name="effective_date">


            <label for="approval">Enter Approval Date<span style="color: red;">*</span></label>
            <input type="date" value="" name="approval">



            <label for="upload_matric">Upload Matric(The Excel file should contain only the matric numbers, without a heading.)<span style="color: red;">*</span></label>
            <input type="file" value="" name="upload_matric">




            <input type='submit' value='Assign' name='assign' class='btn' id='submitBtn'>
    </form>

</div>


    <?php
    if (isset($_POST["assign"])) {
        $excelFile = $_FILES["upload_matric"];
        $effective = $_POST["effective_date"];
        $external = $_POST["external"];
        $approval = $_POST["approval"];

            assignExternalAndEffectiveDate($conn, $excelFile, $effective,$approval, $external, $admin);


        // if(isset ($effective)){

            
        //             $query_lock = "SELECT DISTINCT status FROM testscore WHERE effectivedate = '$effective'";
        //             $result_lock = mysqli_query($conn, $query_lock);
        //             $row_lock = mysqli_fetch_assoc($result_lock);
        //             $lock_status = $row_lock['status'];
                    
        //             if($lock_status == 1){
                    
        //               echo "<script>alert('Students Result With Selected Effective Date Has Been Locked');</script>";
                    
                    
        //             } elseif($lock_status == 0){
                    
        //               assignExternalAndEffectiveDate($conn, $excelFile, $effective,$approval, $external, $admin);
        //             }
        // }
    }
    ?>

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
