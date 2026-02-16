<?php session_start();

if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: ../');
}
//   require_once 'vendor/autoload.php'; // Adjust the path if necessary

//   use Ramsey\Uuid\Uuid;

//   // Your code here
//   $uniqueNumber = Uuid::uuid4();
//   $token= $uniqueNumber->toString();
$token = $_SESSION["token"];
$dept = $_SESSION["dept_new"];
//$sec=$_SESSION["sec"];
//echo $dept."<br>";
$name = $_SESSION["name"];
include_once('../function/script.php');
//echo $token."\n";
$admin = $_SESSION["name"];
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
      max-width: 1000px;
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

    .sinput {
      padding-left: 3rem !important;
      padding-top: 1rem !important;
    }

    .sbtn {
      padding: 0.5rem !important;
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
                                              }  ?></h1>
            
          </div>

          <div id="form" class="contain">

            <form method="post" enctype="multipart/form-data">
              <?php
              $rec = mysqli_query($conn, "select studentrecord.matric,degree_new.degree AS degree,studentrecord.name,field_new.field_title AS field,studentrecord.status,studentrecord.specialization from studentrecord LEFT JOIN field_new ON field_new.id=studentrecord.specialization LEFT JOIN zmain_app ON zmain_app.user_id=studentrecord.user_id LEFT JOIN degree_new ON zmain_app.degree=degree_new.id where studentrecord.code='$token' GROUP BY studentrecord.user_id") or die(mysqli_error($con));
              echo " <table  class='table cmt' id='myTable'>
                                        
                                                     <tr>
                                                         <th>Matric</th>
                                                         
                                                         <th>Name</th>

                                                         <th>Degree</th>

                                                        <th >Select New Degree</th>
                                                         
                                                         <th>Specialization</th>
                                                         
                                                         <th colspan=2>Select New Specialisation (if needs)</th>
                                                     </tr>
                                                 ";
              //foreach ($insertedData as $record){

              while ($insertedData = mysqli_fetch_array($rec))
              //print_r($record);
              {
                echo "<tr class='data'>
                                             <td>" . $insertedData['matric'] . "</td>
                                             <td>" . $insertedData['name'] . "</td>
                                             <td id='responseDiv2' class='responseDiv2'>" . $insertedData['degree'] . "</td>
                                                                     <td > ";

                    $degreevalue = mysqli_query($conn, "select DISTINCT degree_new.degree AS degree,fieldofinterest5.degree AS degreeId from fieldofinterest5 INNER JOIN degree_new on fieldofinterest5.degree=degree_new.id where fieldofinterest5.dept='$dept' ") or die(mysqli_error($con));
                    echo "<select name='degree' id='degree' class='sinput degree'>
                                                <option value= selected>Select</option>";

                    while ($seldegree = mysqli_fetch_array($degreevalue))
                    //  //print_r($record);
                    {
                        echo " <option value=" . $seldegree['degreeId'] . ">" . $seldegree['degree'] . "</option>";
                    }
                    echo " </select>
                                                </td>
                                             <td id='responseDiv' class='responseDiv'>" . $insertedData['field'] . "</td>
                                             <td > ";

                $fieldvalue = mysqli_query($conn, "select DISTINCT field_new.field_title,fieldofinterest5.field from fieldofinterest5 INNER JOIN field_new on fieldofinterest5.field=field_new.id where fieldofinterest5.dept='$dept' ") or die(mysqli_error($con));
                echo  "<select name='field' id='field' class='sinput field'>
                                                <option value= selected>Select</option>";

                while ($selfield = mysqli_fetch_array($fieldvalue))
                //  //print_r($record);
                {
                  echo " <option value=" . $selfield['field'] . ">" . $selfield['field_title'] . "</option>";
                }
                echo " </select>
                                                </td>";

                echo "<td><input type='hidden' name='matric' class='matric' id='matric' value=" . $insertedData['matric'] . " /></td>";


                echo "</tr>";
              }


              echo "</table>";


              ?>

              <input type="submit" name="proceed" value="Upload and Process Excel" class="btn">
              <!-- <input type='submit' value='Submit' name='send' class='btn'> -->
            </form>
            <?php
            if (isset($_POST['proceed'])) {
              header("Location:../dashboard.php?p=setupscore");
            }
            ?>
          </div>


          <?php

          // include('function/script.php');
          // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          //     //$coz=$_POST['coz'];
          //     chkstudentrecord($_FILES['excelFile'],$conn,$admin,$token);
          // }

          ?>



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
// Loop through each set of select elements
var selectElements = document.querySelectorAll('.data');
selectElements.forEach(function(selectElement) {
    // Find the elements within the current set
    var fieldSelect = selectElement.querySelector('.field');
    var degreeSelect = selectElement.querySelector('.degree');
    var matricInput = selectElement.querySelector('.matric');
    var responseDiv = selectElement.querySelector('.responseDiv');
    var responseDiv2 = selectElement.querySelector('.responseDiv2');

    // Add change event listener to the field select element
    fieldSelect.addEventListener('change', function() {
        var field = this.value;
        
        var matric = matricInput.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Response from PHP script
                var successMessage = this.responseText;
                responseDiv.innerHTML = successMessage;
            }
        };
        xhttp.open("GET", "../resolvefield.php?field=" + field + "&matric=" + matric, true);
        xhttp.send();
    });
    // Add change event listener to the degree select element
    degreeSelect.addEventListener('change', function() {
        var degree = this.value;
        
        var matric = matricInput.value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Response from PHP script
                var successMessage = this.responseText;
                responseDiv2.innerHTML = successMessage;
            }
        };
        xhttp.open("GET", "../resolvefield.php?degree=" + degree + "&matric=" + matric, true);
        xhttp.send();
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