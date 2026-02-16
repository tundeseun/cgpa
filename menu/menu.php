

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="sidebar-brand-text mx-1"><?php echo "Welcome HOD"; ?></div>
      </a>

   
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="../dashboard.php?p=1">
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
            
            <a class="collapse-item" href="../dashboard.php?p=hod">Create HOD</a>
            <a class="collapse-item" href="../dashboard.php?p=sd">Create Sub-Dean</a>
            <a class="collapse-item" href="../dashboard.php?p=ex">Create External Examiner</a>
            <!-- <a class="collapse-item" href="#">External Examiner Signature</a> -->
          </div>
        </div>
      </li>


      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=viewcourse">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>View Course(s)</span></a>
      </li>

       <!-- Nav Item - Charts -->
       <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=viewstudent">
          <i class="fas fa-book-reader"></i>
          <span>View Registered Students</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <!-- <a class="nav-link" href="../dashboard.php?p=setupscore"> -->
        <a class="nav-link" href="../dashboard.php?p=chkstudrec">
          <i class="fas fa-fw fa-table"></i>
          <span>Upload Score</span></a>
      </li>

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=assign">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Assign Examiner &   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective date</span></a>
      </li>
      
      <li class="nav-item">
        <!-- <a class="nav-link" href="../dashboard.php?p=setupscore"> -->
        <a class="nav-link" href="../dashboard.php?p=editresult">
          <i class="fas fa-fw fa-table"></i>
          <span>Edit Result</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=deleteresult">
          <i class="fas fa-fw fa-table"></i>
          <span>Delete Result (Bulk)</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=setupresolve">
          <i class="fas fa-chalkboard-teacher"></i>
          <span>Resolve Specialisation</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=processresult">
          <i class="fas fa-cog"></i>
          <span>Process Result</span></a>
      </li>
      

      
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReport" aria-expanded="true" aria-controls="collapseReport">
          <i class="fas fa-fw fa-cog"></i>
          <span>Report</span>
        </a>
        <div id="collapseReport" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <a class="collapse-item" href="../dashboard.php?p=setupresult">Result</a>
            <a class="collapse-item" href="../dashboard.php?p=setupdraft">Draft Result</a>
            <a class="collapse-item" href="../dashboard.php?p=graduating">Graduating List (PDF)</a>
            <a class="collapse-item" href="../dashboard.php?p=regstatus">Registration Status (PDF)</a>
            
            <hr>
            <!-- <a class="collapse-item" href="../dashboard.php?p=graduating-word">Graduating List (WORD)</a> -->
            <!-- <a class="collapse-item" href="../dashboard.php?p=regstatus-word">Registration Status (WORD)</a> -->
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php?p=pass">
          <i class="fas fa-key"></i>
          <span>Change Password</span></a>
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