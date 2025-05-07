<!-- Main Sidebar Container -->
<aside class="main-sidebar main-sidebar-custom sidebar-light-primary elevation-4">
  <!-- Brand Logo -->
  <a style="background-color: #57A8FF;" href="#" class="brand-link">
    <img src="Assets/assets/images/uratex-logo.png" alt="ddd" class="brand-image" style="opacity: .8">&nbsp;
    <span style="font-size: 15px;" class="brand-text font-weight-bold" id="titlespan"><strong>sss</strong></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item" id="li-home">
          <a href="home" class="nav-link" id="lia-home" onclick="menuclick(this.id)">
            <i class="fas fa-home nav-icon" aria-hidden="true"></i>
            <p>
              Home
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-dashboard">
          <a href="modulecontrol?Dashboard&Home" class="nav-link" id="lia-dashboard" onclick="menuclick(this.id)">
            <i class="fas fa-tachometer-alt nav-icon" aria-hidden="true"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-request">
          <a href="#" class="nav-link" id="lia-request" onclick="menuclick(this.id)">
            <i class="fa fa-list nav-icon" aria-hidden="true"></i>
            <p>
              Transactions
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview" style="border: 1px solid black; padding: -5px; border-radius: 5px;">
            <li class="nav-item">
              <a href="modulecontrol?Transaction&Transactions" class="nav-link" onclick="menuclick(this.id)" id="a-request-1">
                <i class="fa fa-file nav-icon" aria-hidden="true"></i>
                <p>Recently Uploaded</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="modulecontrol?Archive&Transactions" class="nav-link" onclick="menuclick(this.id)" id="a-request-2">
                <i class="fa fa-user nav-icon" aria-hidden="true"></i>
                <p>Archive</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="modulecontrol?Extract&Transactions" class="nav-link" onclick="menuclick(this.id)" id="a-request-3">
                <i class="fa fa-download nav-icon" aria-hidden="true"></i>
                Data
              </a>
            </li>

          </ul>
        </li>




        <li class="nav-item" id="li-dashboard">
          <a href="modulecontrol?NewUpload&Upload" class="nav-link" id="lia-new" onclick="menuclick(this.id)">
            <i class="fas fa-upload nav-icon" aria-hidden="true"></i>
            <p>
              New Upload
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-dashboard">
          <a href="modulecontrol?SoConfirmation&Upload" class="nav-link" id="lia-new" onclick="menuclick(this.id)">
            <i class="fas fa-upload nav-icon" aria-hidden="true"></i>
            <p>
              Upload Confirmation
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-dashboard">
          <a href="modulecontrol?PrintSO&Printing" class="nav-link" id="lia-print" onclick="menuclick(this.id)">
            <i class="fas fa-print nav-icon" aria-hidden="true"></i>
            <p>
              SO Printing
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-dashboard">
          <a href="modulecontrol?Report&Report" class="nav-link" id="lia-report" onclick="menuclick(this.id)">
            <i class="fas fa-list nav-icon" aria-hidden="true"></i>
            <p>
              Report
            </p>
          </a>
        </li>

        <li class="nav-item" id="li-dashboard">
          <a href="logOut" class="nav-link" id="lia-report" onclick="menuclick(this.id)">
            <i class="fas fa-power-off"></i>
            <p>
              Logout
            </p>
          </a>
        </li>






      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->

  <div class="sidebar-custom col-sm-12">
    <!-- <a href="#" class="btn btn-link"><i class="fas fa-cogs"></i></a> -->
    <!--<a href="#" class="btn btn-secondary hide-on-collapse pos-right">Help</a>-->
    <!-- <button type="button" class="btn btn-primary hide-on-collapse pos-right" data-toggle="modal" data-target="#modal-default">
      	Help
      </button> -->
    <button type="button" class="col-12 btn btn-info" data-toggle="modal" data-target="#modal-default">
      Help
    </button>
  </div>
  <!-- /.sidebar-custom -->
</aside>