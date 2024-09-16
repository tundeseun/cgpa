<?php 
session_start();
$code=$_SESSION["code"];
$effectivedate=$_SESSION["effectivedate"];

include_once('../function/script.php');


// $query_name = "SELECT DISTINCT name FROM users WHERE username = '$user'";
// $result_name = mysqli_query($conn, $query_name);

//     $row_name = mysqli_fetch_assoc($result_name);
//     $name_head = $row_name['name'];

// if (isset($_GET["name"])) {
//   $_SESSION["name"] = $_GET["name"];
// }

if(isset($_POST["logout"])){
  session_destroy();
  header('Location: ../');
}


  
$dept=$_SESSION["dept_new"];
//$sec=$_SESSION["sec"];
$name=$_SESSION["name"];
include_once('../function/script.php');
if(!isset($_SESSION["dept_new"]) && !isset($_SESSION["name"])){
  session_destroy();
  header('Location: ../');
}
$admin = $_SESSION["name"];


            if (isset($_POST['update'])) {
                $sections = $_POST['sections'];

                foreach ($sections as $section) {
                    $matric = $section['matric'];
                    $score = $section['scoreHide'];

                    if ($score != '') {
  
                    $update_result = "UPDATE testscore SET score = '$score' WHERE cozid = '$code' AND matric = '$matric' AND effectivedate = '$effectivedate' AND dept = '$dept'";
                    $result_update = mysqli_query($conn, $update_result);

                    }
                    
                }

                if($result_update){
                    echo "<script>alert('Result Updated Successfully');</script>";
                } 
                //else{
                //     echo mysqli_error($conn,$result_update);
                // }

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
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .edit-btn, .delete-btn {
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
        .edit-btn:hover, .delete-btn:hover {
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
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
<!--  <link rel="stylesheet" href="../style.css">-->
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
        padding: 0 !important;
            padding-left: 0.5rem !important;
            
            margin-bottom: 0 !important;
            border-radius: 0 !important;
            margin-bottom: 0.5rem;
            width: 100%;
            outline: none;
            border: 1px solid #0a2b4f;
            
            

        }
      .btns {
        background: #dc3545;
            padding-left: 2rem  !important;
            padding-top: 1rem  !important;
            padding-bottom: 1rem  !important;
          border-radius: 0.75rem !important;
            margin-bottom: 0.5rem;
            width: 100%;
            outline: none;
            border: 1px solid #dc3545;
            color: #fff;
            transition: 0.3s ease-in-out !important;

        }
        .btns:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;
      
        }
        
        .code{
            border: none !important;
            padding: 0 !important;
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
        .link, .link:hover {
            background: #1cc88a;
            border-radius: 0.2rem;
            transition: 0.3s ease-in-out !important;
           color: #fff;
           font-weight: bolder;
           width: fit-content !important;
           padding-left: 0.5rem !important;
           padding-right: 0.5rem !important;
           

        }
  #form form .btn:hover {
            background: #0a2b4f;
            border: 1px solid #0a2b4f !important;
            color: #fff;
      
        }
        .name{
            text-align: center !important;
        }
        .fas{
            font-size: 0.75rem;
            
        }
        .s{
            width: 5rem !important;
        }
        .scoreHide,.hideCid{
            display: none !important; 
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
            <h1 class="h3 mb-0 text-gray-800"><?php if(isset($dept)){showdept($dept,$conn);}  ?></h1>
            
          </div>
          
                <div id="form" class="contain">
                    
              
                
             

                <?php
 
              
// include('function/script.php');
    $result = getStudentResultsWithCode($conn,$code,$effectivedate,$dept);

    $querycode = "SELECT DISTINCT course_code FROM course_new WHERE cgpa_id = '$code'";
    $resultcode = mysqli_query($conn, $querycode);

    $row_name = mysqli_fetch_assoc($resultcode);
    $name_std = $row_name['course_code'] ?? $code;

    $count = mysqli_num_rows($result);
    if($count <= 0) {
      echo "<script> alert('No Record Found.');</script>";
      echo "<script> window.location.replace('../dashboard.php?p=editresult');</script>";
      
    }
    
    ?>
   
    <a class="link" href="../dashboard.php?p=editresult"><i class="fas fa-fw fa-arrow-left"></i>Back</a>

    <h3 class="name"><?php echo $name_std.' Results' ?></h3>
    <form method="post" enctype="multipart/form-data">

    <table class="table cmt" id="myTable">
              <tr>
                <th class="hideCid">matric</th>
                <th>Matric</th>
                <th>Name</th>
                <th class="s"> Score</th>


              </tr>
<?php

        $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $matric = $row['matric'];
        $score = $row['score'];
        $name = $row['name'];
        
        

        // echo $name.' '.$course_code.' '.$status.' '.$unit.' '.$score.'<br><br><br>';
   
?>
  <tr>
   
                  <td class="hideCid">
                  <input type="text" id="matric"class="matric" name="sections[<?php echo $i;?>][matric]" value="<?php echo $matric;?>" readonly>
                  </td>
   
                  <td>
                    <?php echo $matric; ?>
                  </td>
                  <td>
                    <?php echo $name;?>
                  </td>
                 
                  <td>
                    
                    <input type="number" name="sections[<?php echo $i;?>][score]" class="score" value="<?php echo $score;?>">
                    <input type="number" name="sections[<?php echo $i;?>][scoreHide]" class="scoreHide" id="scoreHide" >
                    
                  </td>
                <tr>
                  
                <?php 
                $i++;

}



?>


            </table>
            <input type="submit" value="Update Result" name="update" class="btns">

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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.score').change(function(){
            // Find the closest row and get the values from specific input fields within that row
            var currentRow = $(this).closest('tr');
            var scoreValue = $(this).val();
            
            // Update the corresponding hidden input field within the same row
            currentRow.find('.scoreHide').val(scoreValue);
        });
    });
</script>
<!-- 
<script>
        
        let inputF = document.getElementById("id1");
 scoreInputs.forEach(function(scoreInput) {
        function gfg_Run() {
            inputF.setAttribute('value', 'defaultValue');
            el_down.innerHTML =
                "Value = " + "'" + inputF.value + "'";
        }
    </script> -->

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
