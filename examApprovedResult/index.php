<?php session_start();

$user = $_SESSION["user"];

include_once('../function/script.php');

  
$query_name = "SELECT DISTINCT name FROM users_cgpa_new WHERE username = '$user'";
$result_name = mysqli_query($conn, $query_name);

$query_dept = "SELECT DISTINCT testscore.dept as dept_id,dept_new.department as dept FROM testscore inner join dept_new on dept_new.id=testscore.dept";
$result_dept = mysqli_query($conn, $query_dept);

$row_name = mysqli_fetch_assoc($result_name);
$name_head = $row_name['name'];


if (!($user)) {
  session_destroy();
  header('Location: ../');
}

if (isset($_POST["logout"])) {
  session_destroy();
  header('Location: ../');
}

$admin = $name_head;
$boardDate = date("m/d/Y");


?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Result Processing - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <!--  <link rel="stylesheet" href="style.css">-->
  <style>
    .folder-structure {
        margin: 20px;
        font-family: Arial, sans-serif;
    }

    .folder {
        margin: 5px 0;
        padding: 5px;
        cursor: pointer;
    }

    .folder-icon {
        margin-right: 10px;
    }

    .folder-content {
        margin-left: 20px;
        display: none;
    }

    .folder.active > .folder-content {
        display: block;
    }

    .faculty-folder {
        background-color: #f8f9fc;
        border-left: 3px solid #4e73df;
    }

    .department-folder {
        background-color: #ffffff;
        border-left: 3px solid #1cc88a;
    }

    .specialization-folder {
        background-color: #ffffff;
        border-left: 3px solid #f6c23e;
    }

    .result-item {
        padding: 10px;
        margin: 5px 0;
        background-color: #ffffff;
        border-left: 3px solid #e74a3b;
    }

    .folder-header {
        display: flex;
        align-items: center;
        padding: 8px;
        transition: background-color 0.2s;
    }

    .folder-header:hover {
        background-color: rgba(0,0,0,0.05);
    }

    .folder-name {
        margin-left: 10px;
        font-weight: bold;
    }

    .result-header {
        margin-bottom: 10px;
    }

    .result-actions {
        display: flex;
        gap: 10px;
        margin-top: 5px;
    }

    .result-item form {
        display: inline-block;
    }

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
      max-width: 1100px;
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

    .table {
      font-size: 0.75rem !important;
    }

    #pagination {
      display: flex;
      justify-content: center;
    }

    .pagination_link {
      border: 1px solid #0a2b4f;
      border-radius: 0.5rem;
      padding-left: 0.5rem;
      padding-right: 0.5rem;
      padding-top: 0.2rem;
      padding-bottom: 0.2rem;
      margin-right: 1rem !important;
      text-decoration: none;
      color: #0a2b4f;
    }

    .pagination_link:hover {
      background: #0a2b4f;
      color: #fff;
    }

    .pagination_link.active {
      background: #0a2b4f;
      color: #fff;
      pointer-events: none;

    }

    .success {
      padding: 0.2rem 1rem;
      background: #28a745;
      border: none;
      border-radius: 5px;
      color: #fff !important;
    }

    .danger {
      padding: 0.2rem 1rem;
      background: #dc3545;
      border: none;
      border-radius: 5px;
      color: #fff !important;
    }

    .head {
      display: flex;
      justify-content: space-between;
    }

    .head a:hover {
      text-decoration: none;
    }
      .disabled-link {
        padding: 0.2rem 1rem;
      background: #f6c23e;
      border: none;
      border-radius: 5px;
      color: #0a2b4f !important;
      cursor: not-allowed;
      text-decoration: none;
    }

 /* Clean Folder Structure Styles with #0a2b4f Theme */
.folder-structure {
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(10, 43, 79, 0.08);
}

.folder {
  margin-bottom: 4px;
}

.folder-header {
  padding: 12px 16px;
  background: #f8f9fc;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  transition: all 0.2s ease;
  border: 1px solid #e3e6f0;
}

.folder-header:hover {
  background: #f1f3f8;
  border-color: #d1d3e2;
}

.folder-content {
  display: none;
  margin-left: 20px;
  padding-left: 16px;
  border-left: 2px solid #e3e6f0;
  margin-top: 4px;
}

.folder.active > .folder-content {
  display: block;
}

/* Faculty Folders - Primary Level */
.faculty-folder > .folder-header {
  background: #0a2b4f;
  color: white;
  border-color: #0a2b4f;
}

.faculty-folder > .folder-header:hover {
  background: #083248;
  border-color: #083248;
}

.faculty-folder .folder-name,
.faculty-folder .folder-icon {
  color: white;
}

/* Department Folders - Secondary Level */
.department-folder > .folder-header {
  background: white;
  color: #0a2b4f !important;
  border-left: 4px solid #0a2b4f;
  border-color: #d1d3e2;
}

.department-folder .folder-name,
.department-folder .folder-icon {
  color: #0a2b4f !important;
}

.department-folder > .folder-header:hover {
  background: #f8f9fc;
  border-color: #0a2b4f;
}

/* Specialization Folders - Tertiary Level */
.specialization-folder > .folder-header {
  background: white;
  color: #5a5c69;
  border-left: 3px solid #858796;
  border-color: #e3e6f0;
}
.specialization-folder .folder-name,
.specialization-folder .folder-icon {
  color: #5a5c69;
}

.specialization-folder > .folder-header:hover {
  background: #f8f9fc;
  border-left-color: #0a2b4f;
  color: #0a2b4f;
}

/* Result Items */
.result-item {
  margin: 8px 0;
  padding: 16px;
  background: white;
  border: 1px solid #e3e6f0;
  border-radius: 6px;
  border-left: 4px solid #0a2b4f;
}

.result-item:hover {
  box-shadow: 0 2px 8px rgba(10, 43, 79, 0.08);
}

.result-header {
  margin-bottom: 12px;
  color: #0a2b4f;
  font-weight: 500;
}

.result-actions {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}

.folder-icon {
  margin-right: 8px;
  width: 16px;
  text-align: center;
}

.folder-name {
  font-weight: 500;
  flex: 1;
}

/* Clean Button Styles */
.btn-success {
  background-color: #0a2b4f;
  border-color: #0a2b4f;
  color: white;
  font-size: 0.85rem;
  padding: 6px 12px;
}

.btn-success:hover {
  background-color: #083248;
  border-color: #083248;
  color: white;
}

.danger {
  background-color: #dc3545;
  border: 1px solid #dc3545;
  color: white;
  padding: 6px 12px;
  border-radius: 4px;
  text-decoration: none;
  display: inline-block;
  font-size: 0.85rem;
}

.danger:hover {
  background-color: #c82333;
  border-color: #bd2130;
  color: white;
  text-decoration: none;
}

.disabled-link {
  padding: 6px 12px;
  background-color: #6c757d;
  border: 1px solid #6c757d;
  border-radius: 4px;
  color: white !important;
  cursor: not-allowed;
  text-decoration: none;
  display: inline-block;
  font-size: 0.85rem;
  opacity: 0.7;
}

/* Clean Badge Styles */
.badge {
  font-size: 0.75rem;
  padding: 4px 8px;
  border-radius: 12px;
  font-weight: 500;
}

.badge-primary {
  background-color: #0a2b4f;
  color: white;
}

.badge-success {
  background-color: #28a745;
  color: white;
}

.badge-warning {
  background-color: #ffc107;
  color: #212529;
}

.badge-secondary {
  background-color: #6c757d;
  color: white;
}

/* Alert Styles */
.alert-success {
  background-color: #f8fffe;
  border: 1px solid #0a2b4f;
  color: #0a2b4f;
  border-radius: 6px;
}

.alert-info {
  background-color: #f8f9fc;
  border: 1px solid #5a5c69;
  color: #5a5c69;
  border-radius: 6px;
}

.alert-danger {
  background-color: #fdf2f2;
  border: 1px solid #dc3545;
  color: #721c24;
  border-radius: 6px;
}

/* Loading States */
.text-center {
  color: #5a5c69;
}

.fa-spinner {
  color: #0a2b4f;
}

/* Responsive Design */
@media (max-width: 768px) {
  .folder-structure {
    padding: 15px;
  }
  
  .folder-header {
    padding: 10px 12px;
    font-size: 14px;
  }
  
  .folder-content {
    margin-left: 16px;
    padding-left: 12px;
  }
  
  .result-item {
    padding: 12px;
  }
  
  .result-actions {
    flex-direction: column;
    gap: 6px;
  }
  
  .btn-success, 
  .danger, 
  .disabled-link {
    width: 100%;
    text-align: center;
    font-size: 0.9rem;
  }
  
  .badge {
    font-size: 0.7rem;
    padding: 3px 6px;
  }
}
  </style>

</head>

<body id="page-top">


  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->

    <!-- Sidebar --> <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.html">
        <div class="sidebar-brand-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?php echo $name_head;; ?></div>
      </a>


      <!-- Nav Item - Dashboard -->

      <li class="nav-item ">
        <a class="nav-link" href="../examView">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View Results</span></a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="http://pguicgpa.ui.edu.ng/nor" target="_blank">
          <i class="fas fa-file"></i>
          <span>NOR</span></a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="http://192.168.0.15/Notification/" target="_blank">
          <i class="fas fa-file"></i>
          <span>Old NOR</span></a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="http://pguicgpa.ui.edu.ng/cmdnor" target="_blank">
          <i class="fas fa-file"></i>
          <span>CMD NOR</span></a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="http://pguicgpa.ui.edu.ng/rendition" target="_blank">
          <i class="fas fa-file"></i>
          <span>Rendition</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="../examApprovedResult">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View Approved Result</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../examGradList">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View Graduating List</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../examRegStatus">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View Registration Status</span></a>
      </li>
      
      <li class="nav-item ">
        <a class="nav-link" href="../examSenateList">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View M.Sc Senate List</span></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="../examSenateListPhd">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View PhD Senate List</span></a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="../examCertList">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View M.Sc Cert. List</span></a>
      </li>
      <!-- <li class="nav-item ">
        <a class="nav-link" href="../examCertListPhd">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>View PhD Cert. List</span></a>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="../examOrder">
          <i class="fas fa-download"></i>
          <span>Order Of Proceedings</span></a>
      </li>
  
      <!-- Divider -->
      <hr class="sidebar-divider">


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
            <img style="width: 30px;" src="../img/ui-logo.png" class="logo">
            <div class="topbar-divider d-none d-sm-block"></div>
            <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="../img/logo.png" class="logo">
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
            <h1 class="h3 mb-0 text-gray-800"><?php if (isset($dept)) {
                                                showdept($dept, $conn);
                                              }  ?></h1>

          </div>



          <!-- <div id="form" class="contain">

            <form action="../boardresult.php" method="POST">
              <h4> View BroadSheet </h4>
              <div class="form-group mb">
                <label for="dept">Select Department:</label>

                <select name="department" id="dept" onchange="showUser(this.value)">
                  <?php


                  echo "<option value='' disabled selected>";
                  while ($row_dept = mysqli_fetch_assoc($result_dept)) {
                    echo "<option value=" . $row_dept['dept_id'];
                    echo  " > " . strtoupper($row_dept['dept']) . "</option>";
                  }
                  ?>

                </select>
              </div>



              <div align="left"><span id="txtHint"></span>


                
                <input type='submit' value='Submit' name='send' class='btn'>


            </form>

          </div> -->


          <div class="contain">
            <div class="head">
              <h4> Approved Result </h4>
              <!-- <p>
                <a href="../create-zip-file.php"><i class="fas fa-download"></i> Download</a>
              </p> -->
            </div>

            <div class="folder-structure" id="folderStructure">
              <!-- Folders will be populated here -->
            </div>
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
$(document).ready(function() {
    console.log('Document ready, starting lazy loading version...');
    
    const folderStructure = $('#folderStructure');
    
    // Initialize by loading faculties
    loadFaculties();
    
    function loadFaculties() {
        console.log('=== LOADING FACULTIES ===');
        
        if (folderStructure.length === 0) {
            console.error('ERROR: #folderStructure element not found!');
            return;
        }
        
        folderStructure.html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading faculties...</div>');
        
        $.ajax({
            url: '../fetch_exam_locked_result.php',
            method: 'GET',
            data: { level: 'faculties' },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                console.log('Faculties response:', response);
                
                if (response.error) {
                    showError('Faculty Loading Error', response.error, response.debug_info);
                    return;
                }
                
                if (!response.data || response.data.length === 0) {
                    folderStructure.html('<div class="alert alert-info">No Record Found</div>');
                    return;
                }
                
                folderStructure.empty();
                
                // Add header
                folderStructure.append(`
                    <div class="alert alert-success mb-3">
                        <small>Found ${response.data.length} faculties</small>
                    </div>
                `);
                
                // Create faculty folders
                response.data.forEach(faculty => {
                    const facultyDiv = createFacultyFolder(faculty);
                    folderStructure.append(facultyDiv);
                });
                
                attachFacultyClickHandlers();
                console.log('=== FACULTIES LOADED SUCCESSFULLY ===');
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Faculty loading error:', textStatus, errorThrown);
                showAjaxError('Loading Faculties', jqXHR, textStatus, errorThrown);
            }
        });
    }
    
    function createFacultyFolder(faculty) {
        return $(`
            <div class="folder faculty-folder" data-faculty-id="${faculty.id}" data-level="faculty">
                <div class="folder-header">
                    <i class="fas fa-folder folder-icon"></i>
                    <span class="folder-name">${escapeHtml(faculty.name)}</span>
                    <span class="ml-auto">
                        <span class="badge badge-primary mr-1">${faculty.dept_count} dept(s)</span>
                    </span>
                </div>
                <div class="folder-content"></div>
            </div>
        `);
    }
    
    function attachFacultyClickHandlers() {
        $('.faculty-folder .folder-header').off('click').on('click', function(e) {
            e.stopPropagation();
            const folder = $(this).closest('.folder');
            const facultyId = folder.data('faculty-id');
            const content = folder.find('.folder-content');
            
            if (folder.hasClass('active')) {
                // Collapse
                folder.removeClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder-open').addClass('fa-folder');
                content.slideUp();
            } else {
                // Expand
                folder.addClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
                
                if (content.is(':empty') || content.find('.departments-container').length === 0) {
                    loadDepartments(facultyId, content);
                } else {
                    content.slideDown();
                }
            }
        });
    }
    
    function loadDepartments(facultyId, contentContainer) {
        console.log('=== LOADING DEPARTMENTS FOR FACULTY:', facultyId, '===');
        
        contentContainer.html('<div class="text-center p-2"><i class="fas fa-spinner fa-spin"></i> Loading departments...</div>').slideDown();
        
        $.ajax({
            url: '../fetch_exam_locked_result.php',
            method: 'GET',
            data: { 
                level: 'departments', 
                faculty_id: facultyId 
            },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                console.log('Departments response:', response);
                
                if (response.error) {
                    contentContainer.html(`<div class="alert alert-danger p-2">Error: ${response.error}</div>`);
                    return;
                }
                
                if (!response.data || response.data.length === 0) {
                    contentContainer.html('<div class="alert alert-info p-2">No departments found</div>');
                    return;
                }
                
                const departmentsContainer = $('<div class="departments-container"></div>');
                
                response.data.forEach(department => {
                    const deptDiv = createDepartmentFolder(department, facultyId);
                    departmentsContainer.append(deptDiv);
                });
                
                contentContainer.html(departmentsContainer);
                attachDepartmentClickHandlers(facultyId);
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Department loading error:', textStatus, errorThrown);
                contentContainer.html(`<div class="alert alert-danger p-2">Failed to load departments: ${textStatus}</div>`);
            }
        });
    }
    
    function createDepartmentFolder(department, facultyId) {
        return $(`
            <div class="folder department-folder" data-dept-id="${department.id}" data-faculty-id="${facultyId}" data-level="department">
                <div class="folder-header">
                    <i class="fas fa-folder folder-icon"></i>
                    <span class="folder-name">${escapeHtml(department.name)}</span>
                    <span class="ml-auto">
                        <span class="badge badge-success mr-1">${department.specialization_count} spec(s)</span>
                    </span>
                </div>
                <div class="folder-content"></div>
            </div>
        `);
    }
    
    function attachDepartmentClickHandlers(facultyId) {
        $(`.department-folder[data-faculty-id="${facultyId}"] .folder-header`).off('click').on('click', function(e) {
            e.stopPropagation();
            const folder = $(this).closest('.folder');
            const deptId = folder.data('dept-id');
            const content = folder.find('.folder-content');
            
            if (folder.hasClass('active')) {
                // Collapse
                folder.removeClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder-open').addClass('fa-folder');
                content.slideUp();
            } else {
                // Expand
                folder.addClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
                
                if (content.is(':empty') || content.find('.specializations-container').length === 0) {
                    loadSpecializations(facultyId, deptId, content);
                } else {
                    content.slideDown();
                }
            }
        });
    }
    
    function loadSpecializations(facultyId, deptId, contentContainer) {
        console.log('=== LOADING SPECIALIZATIONS FOR DEPT:', deptId, '===');
        
        contentContainer.html('<div class="text-center p-2"><i class="fas fa-spinner fa-spin"></i> Loading specializations...</div>').slideDown();
        
        $.ajax({
            url: '../fetch_exam_locked_result.php',
            method: 'GET',
            data: { 
                level: 'specializations', 
                faculty_id: facultyId,
                dept_id: deptId
            },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                console.log('Specializations response:', response);
                
                if (response.error) {
                    contentContainer.html(`<div class="alert alert-danger p-2">Error: ${response.error}</div>`);
                    return;
                }
                
                if (!response.data || response.data.length === 0) {
                    contentContainer.html('<div class="alert alert-info p-2">No specializations found</div>');
                    return;
                }
                
                const specializationsContainer = $('<div class="specializations-container"></div>');
                
                response.data.forEach(specialization => {
                    const specDiv = createSpecializationFolder(specialization, facultyId, deptId);
                    specializationsContainer.append(specDiv);
                });
                
                contentContainer.html(specializationsContainer);
                attachSpecializationClickHandlers(facultyId, deptId);
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Specialization loading error:', textStatus, errorThrown);
                contentContainer.html(`<div class="alert alert-danger p-2">Failed to load specializations: ${textStatus}</div>`);
            }
        });
    }
    
    function createSpecializationFolder(specialization, facultyId, deptId) {
        return $(`
            <div class="folder specialization-folder" data-field-id="${specialization.id}" data-dept-id="${deptId}" data-faculty-id="${facultyId}" data-level="specialization">
                <div class="folder-header">
                    <i class="fas fa-folder folder-icon"></i>
                    <span class="folder-name">${escapeHtml(specialization.name)}</span>
                    <span class="ml-auto">
                    </span>
                </div>
                <div class="folder-content"></div>
            </div>
        `);
    }
    
    function attachSpecializationClickHandlers(facultyId, deptId) {
        $(`.specialization-folder[data-faculty-id="${facultyId}"][data-dept-id="${deptId}"] .folder-header`).off('click').on('click', function(e) {
            e.stopPropagation();
            const folder = $(this).closest('.folder');
            const fieldId = folder.data('field-id');
            const content = folder.find('.folder-content');
            
            if (folder.hasClass('active')) {
                // Collapse
                folder.removeClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder-open').addClass('fa-folder');
                content.slideUp();
            } else {
                // Expand
                folder.addClass('active');
                $(this).find('.folder-icon').removeClass('fa-folder').addClass('fa-folder-open');
                
                if (content.is(':empty') || content.find('.results-container').length === 0) {
                    loadResults(facultyId, deptId, fieldId, content);
                } else {
                    content.slideDown();
                }
            }
        });
    }
    
    function loadResults(facultyId, deptId, fieldId, contentContainer) {
        console.log('=== LOADING RESULTS FOR FIELD:', fieldId, '===');
        
        contentContainer.html('<div class="text-center p-2"><i class="fas fa-spinner fa-spin"></i> Loading results...</div>').slideDown();
        
        $.ajax({
            url: '../fetch_exam_locked_result.php',
            method: 'GET',
            data: { 
                level: 'results', 
                faculty_id: facultyId,
                dept_id: deptId,
                field_id: fieldId
            },
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                console.log('Results response:', response);
                
                if (response.error) {
                    contentContainer.html(`<div class="alert alert-danger p-2">Error: ${response.error}</div>`);
                    return;
                }
                
                if (!response.data || response.data.length === 0) {
                    contentContainer.html('<div class="alert alert-info p-2">No results found</div>');
                    return;
                }
                
                const resultsContainer = $('<div class="results-container"></div>');
                
                response.data.forEach(result => {
                    const resultDiv = createResultItem(result, {
                        facultyId: facultyId,
                        departmentId: deptId,
                        specializationId: fieldId
                    });
                    resultsContainer.append(resultDiv);
                });
                
                contentContainer.html(resultsContainer);
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Results loading error:', textStatus, errorThrown);
                contentContainer.html(`<div class="alert alert-danger p-2">Failed to load results: ${textStatus}</div>`);
            }
        });
    }
    
    function createResultItem(result, metadata) {
        const lockLink = (result.status == 1) 
            ? `<span class='disabled-link'><i class="fas fa-lock fa-sm"></i> Locked</span>`
            : `<a href='index.php?department=${encodeURIComponent(metadata.departmentId)}&degree=${encodeURIComponent(result.degree_id)}&field=${encodeURIComponent(metadata.specializationId)}&effectivedate=${encodeURIComponent(result.effectivedate)}&mode=${encodeURIComponent(result.smode)}&external=${encodeURIComponent(result.external)}&resulttype=${encodeURIComponent(result.resulttype)}&sec=${encodeURIComponent(result.sec)}&fac_approve=fac_approve' class='danger' onclick='return confirmAction(event)'><i class="fas fa-lock fa-sm"></i> Approve & Lock</a>`;
        
        return $(`
            <div class="result-item">
                <div class="result-header">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span class="font-weight-bold">${escapeHtml(result.type || 'Unknown')} (${escapeHtml(result.mode || 'Unknown')})</span>
                    <small class="text-muted ml-2">Session: ${escapeHtml(result.sec || 'N/A')}</small>
                    <small class="text-muted ml-2">Effective Date: ${escapeHtml(result.effectivedate || 'N/A')}</small>
                    <small class="text-muted ml-2">External: ${escapeHtml(result.external || 'N/A')}</small>
                </div>
                <div class="result-actions">
                    <form action="../genboardresult.php" method="POST" target="_blank" class="d-inline-block mr-2">
                        <input type="hidden" name="department" value="${escapeHtml(metadata.departmentId)}">
                        <input type="hidden" name="degree" value="${escapeHtml(result.degree_id)}">
                        <input type="hidden" name="field" value="${escapeHtml(metadata.specializationId)}">
                        <input type="hidden" name="effectivedate" value="${escapeHtml(result.effectivedate)}">
                        <input type="hidden" name="sec" value="${escapeHtml(result.sec)}">
                        <input type="hidden" name="resulttype" value="${escapeHtml(result.resulttype)}">
                        <input type="hidden" name="external" value="${escapeHtml(result.externalId)}">
                        <input type="hidden" name="mode" value="${escapeHtml(result.smode)}">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fas fa-eye fa-sm"></i> View Result
                        </button>
                    </form>
                    ${lockLink}
                </div>
            </div>
        `);
    }
    
    function showError(title, message, debugInfo) {
        let errorHtml = `
            <div class="alert alert-danger">
                <h5>${title}</h5>
                <p>${message}</p>
        `;
        
        if (debugInfo) {
            errorHtml += `<details><summary>Debug Info</summary><pre>${JSON.stringify(debugInfo, null, 2)}</pre></details>`;
        }
        
        errorHtml += `
                <button class="btn btn-sm btn-primary mt-2" onclick="location.reload()">Reload Page</button>
            </div>
        `;
        
        folderStructure.html(errorHtml);
    }
    
    function showAjaxError(title, jqXHR, textStatus, errorThrown) {
        let errorHtml = `
            <div class="alert alert-danger">
                <h5>${title} Failed</h5>
                <p><strong>Status:</strong> ${jqXHR.status} - ${textStatus}</p>
                <p><strong>Error:</strong> ${errorThrown}</p>
        `;
        
        if (jqXHR.responseText) {
            errorHtml += `
                <details>
                    <summary>Server Response</summary>
                    <pre>${jqXHR.responseText.substring(0, 1000)}${jqXHR.responseText.length > 1000 ? '...' : ''}</pre>
                </details>
            `;
        }
        
        errorHtml += `
                <button class="btn btn-sm btn-primary mt-2" onclick="location.reload()">Reload Page</button>
            </div>
        `;
        
        folderStructure.html(errorHtml);
    }
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }
    
    // Make function available globally for retry buttons
    window.loadFaculties = loadFaculties;
});

function confirmAction(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        html: "You want to Lock result?<br><br>" +
              "<b>Note:</b><br>" +
              "• This action is irreversible!<br>" +
              "• Notification of result will be available to students immediately.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0a2b4f',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = event.target.href;
        }
    });
    return false;
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
  <script type="text/javascript" src="../selectnarationexamview.js"></script>

</body>

</html>