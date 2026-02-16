<?php
    session_start();
    $matric        = $_SESSION["matric"];
    $effectivedate = $_SESSION["effectivedate"];

    include_once '../function/script.php';

    $dept = $_SESSION["dept_new"];
    $name = $_SESSION["name"];

    if (! isset($_SESSION["dept_new"]) && ! isset($_SESSION["name"])) {
    session_destroy();
    header('Location: ../');
    }

    if(isset($_POST["logout"])){
  session_destroy();
  header('Location: ../');
}


    $admin = $_SESSION["name"];

    // Handle form submission
    if (isset($_POST["update_cozid"])) {
    $updates = $_POST['updates'];

    foreach ($updates as $update) {
        $id        = $update['id'];
        $new_cozid = $update['new_cozid'];

        if ($new_cozid != '') {
            $update_query  = "UPDATE testscore SET cozid = '$new_cozid' WHERE id = '$id' AND matric = '$matric' AND effectivedate = '$effectivedate'";
            $result_update = mysqli_query($conn, $update_query);

            if ($result_update) {
                $sqlAudit = "INSERT INTO audit (name,action) VALUES ('$admin','Update cozid for testscore ID: $id, matric: $matric')";
                mysqli_query($conn, $sqlAudit);
            } else {
                echo "<script>alert('Error updating record: " . mysqli_error($conn) . "');</script>";
            }
        }
    }

    if ($result_update) {
        echo "<script>alert('Course Updated Successfully');</script>";
    }
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

  <title>Update Course COZID - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
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
        .cozidHide{
            display: none !important;
        }
        .course-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #0a2b4f;
        }
        .course-info p {
            margin: 0.5rem 0;
            color: #0a2b4f;
        }
        .course-info strong {
            color: #dc3545;
        }
    </style>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

  <?php
      include '../menu/menu.php';

  ?>

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include '../navbar/nav.php'; ?>


            <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><?php if (isset($dept)) {showdept($dept, $conn);}?></h1>

          </div>

                <div id="form" class="contain">





                <?php

                    $result = getStudentResults($conn, $matric, $effectivedate, $dept);

                    $query_name  = "SELECT DISTINCT name FROM studentrecord WHERE matric = '$matric'";
                    $result_name = mysqli_query($conn, $query_name);

                    $row_name = mysqli_fetch_assoc($result_name);
                    $name_std = $row_name['name'];

                    $count = mysqli_num_rows($result);
                    if ($count <= 0) {
                        echo "<script> alert('No Student Record Found.');</script>";
                        echo "<script> window.location.replace('../dashboard.php?p=editresult');</script>";

                    }

                ?>

    <a class="link" href="./"><i class="fas fa-fw fa-arrow-left"></i>Back</a>

    <h3 class="name"><?php echo $name_std . ' - Update Course COZID' ?></h3>
    <form method="post" enctype="multipart/form-data">

    <table class="table cmt" id="myTable">
              <tr>
                <th>Course Code</th>
                <th>Current Status</th>
                <th>Unit</th>
                <th>Degree & Specialization</th>
                <th>Select Alternative Course</th>
                <th>Action</th>
              </tr>
<?php

    $i = 0;
    while ($row = mysqli_fetch_assoc($result)) {
    $course_code = $row['course_code'];
    $status      = $row['cstatus'];
    $unit        = $row['cunit'];
    $cozid       = $row['cozid'];
    $id          = $row['id'];
    $stud_degree = $row['degree'];
    $stud_specialization = $row['specialization'];

    // Get the current course's degree and specialization
    $current_course_query  = "SELECT degree_new.degree AS degree_name, field_new.field_title AS specialization, course_new.specialization AS field_id, course_new.degree_id AS degree_id FROM course_new INNER JOIN degree_new ON course_new.degree_id = degree_new.id INNER JOIN field_new ON course_new.specialization = field_new.id WHERE course_new.id = '$cozid'";
    $current_course_result = mysqli_query($conn, $current_course_query);
    $current_course        = mysqli_fetch_assoc($current_course_result);

    $degree_name    = '';
    $degree_id      = '';
    $field_name = '';
    $field_id      = '';

    if ($current_course) {
        $degree_id      = $current_course['degree_id'] ?? '';
        $field_id = $current_course['field_id'] ?? '';
        $degree_name = $current_course['degree_name'] ?? '';
        $field_name = $current_course['specialization'] ?? '';


    }

    // Get all alternative courses for this degree and specialization
    $courses_query  = "SELECT id, course_code FROM course_new WHERE degree_id = '$stud_degree' AND specialization = '$stud_specialization' AND status2 = 0 ORDER BY course_code";
    $courses_result = mysqli_query($conn, $courses_query);

    ?>
  <tr>
                  <td>
                        <input type="text" id="code" class="code" name="updates[<?php echo $i; ?>][code]" value="<?php echo $course_code; ?>" readonly>
                    </td>
                  <td>
                    <?php echo $status; ?>
                  </td>
                  <td>
                    <?php echo $unit; ?>
                  </td>
                  <td>
                    <div class="course-info">
                        <p><strong>Degree:</strong> <?php echo $degree_name ?: 'Not Set'; ?></p>
                        <p><strong>Specialization:</strong> <?php echo $field_name ?: 'Not Set'; ?></p>
                    </div>
                  </td>
                  <td>
                    <input type="hidden" name="updates[<?php echo $i; ?>][id]" value="<?php echo $id; ?>">
                    <select name="updates[<?php echo $i; ?>][new_cozid]" class="form-control">
                        <option value="">-- Select a course --</option>
                        <?php
                            if ($courses_result && mysqli_num_rows($courses_result) > 0) {
                                    while ($alt_course = mysqli_fetch_assoc($courses_result)) {
                                        echo '<option value="' . $alt_course['id'] . '">' . $alt_course['course_code'] . '</option>';
                                    }
                                } else {
                                    echo '<option disabled>No alternative courses available</option>';
                                }
                            ?>
                    </select>
                  </td>
                  <td>
                    <button type="button" class="delete-btn btn btn-danger btn-sm"
                            onclick="confirmDelete('<?php echo $id; ?>', '<?php echo $matric; ?>', '<?php echo $effectivedate; ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                  </td>
                <tr>

                <?php
                    $i++;

                    }

                ?>


            </table>
            <input type="submit" value="Update Course" name="update_cozid" class="btns">

            </form>



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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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

  <script>
  function confirmDelete(id, matric, effectivedate) {
      // Store the button and row reference
      const button = event.currentTarget;
      const row = $(button).closest('tr');

      Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
          if (result.isConfirmed) {
              $.ajax({
                  url: 'delete_result.php',
                  type: 'POST',
                  data: {
                      id: id,
                      matric: matric,
                      effectivedate: effectivedate
                  },
                  dataType: 'json',
                  success: function(response) {
                      if(response.success) {
                          // Remove the row using jQuery
                          row.fadeOut(400, function() {
                              $(this).remove();
                          });

                          Swal.fire(
                              'Deleted!',
                              'Record has been deleted.',
                              'success'
                          );
                      } else {
                          Swal.fire(
                              'Error!',
                              'Failed to delete record.',
                              'error'
                          );
                      }
                  },
                  error: function() {
                      Swal.fire(
                          'Error!',
                          'Something went wrong.',
                          'error'
                      );
                  }
              });
          }
      });
  }


</script>

</body>

</html>
