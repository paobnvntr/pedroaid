<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
 
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
    <!-- <div class="sidebar-brand-icon">
      <img src="../images/Logo.png" alt="">
    </div> -->
    <div class="sidebar-brand-text mx-3">PedroAID</div>
  </a>
 
  <!-- Divider -->
  <hr class="sidebar-divider my-0">
 
  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-chart-bar"></i>
      <span>Dashboard</span></a>
  </li>

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('ordinance') }}">
        <i class="fas fa-fw fa-city"></i>
        <span>City Ordinance</span></a>
    </li>
  @endif

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('committee') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Committee</span></a>
    </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Appointment')
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAppointment"
          aria-expanded="true" aria-controls="collapseAppointment">
          <i class="fas fa-fw fa-calendar-check"></i>
          <span>Appointment</span>
      </a>
      <div id="collapseAppointment" class="collapse" aria-labelledby="headingAppointment" data-parent="#accordionSidebar">
          <div class="py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('appointment.pendingAppointment') }}">Pending & Declined</a>
              <a class="collapse-item" href="{{ route('appointment') }}">Appointment List</a>
              <a class="collapse-item" href="{{ route('appointment.finishedAppointment') }}">Finished</a>
              <a class="collapse-item" href="{{ route('appointment.appointmentFeedback') }}">Feedback</a>
          </div>
      </div>
    </li>
  @endif

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Inquiry')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('inquiry') }}">
        <i class="fas fa-fw fa-comment"></i>
        <span>Inquiry</span></a>
    </li>
  @endif

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Document Request')
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDocument"
          aria-expanded="true" aria-controls="collapseDocument">
          <i class="fas fa-fw fa-folder-open"></i>
          <span>Document Request</span>
      </a>
      <div id="collapseDocument" class="collapse" aria-labelledby="headingDocument" data-parent="#accordionSidebar">
          <div class="py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('document-request.pendingDocumentRequest') }}">Pending & Declined</a>
              <a class="collapse-item" href="{{ route('document-request') }}">Request List</a>
              <a class="collapse-item" href="{{ route('document-request.finishedDocumentRequest') }}">Claimed & Unclaimed</a>
              <a class="collapse-item" href="{{ route('document-request.documentRequestFeedback') }}">Feedback</a>
          </div>
      </div>
    </li>
  @endif

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUser"
          aria-expanded="true" aria-controls="collapseUser">
          <i class="fas fa-fw fa-user"></i>
          <span>User Management</span>
      </a>
      <div id="collapseUser" class="collapse" aria-labelledby="headingUser" data-parent="#accordionSidebar">
          <div class="py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('staff') }}">Staff</a>

              @if (auth()->user()->level == 'Super Admin')
                <a class="collapse-item" href="{{ route('admin') }}">Admin</a>
                <a class="collapse-item" href="{{ route('super-admin') }}">Super Admin</a>
              @endif
          </div>
      </div>
    </li>
  @endif

  @if (auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('logs') }}">
        <i class="fas fa-fw fa-file-alt"></i>
        <span>Logs</span></a>
    </li>
  @endif

  <!-- <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-cog"></i>
      <span>Settings and Profile</span></a>
  </li> -->
 
  <!-- Sidebar Toggler (Sidebar) -->
  <!-- <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>  -->
</ul>