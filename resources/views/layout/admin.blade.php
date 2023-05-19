<ul class="nav flex-column shadow d-flex sidebar mobile-hid">
  <li class="nav-item logo-holder">
      <div class="text-center text-white logo py-4 mx-4"><img class="img-fluid" src="{{ asset('storage/assets/dnsc-logo.png') }}" width="55" height="50"><a id="title" class="text-decoration-none" href="#"><strong>DNSC</strong></a><a class="float-end text-white" id="sidebarToggleHolder" href="#"><i class="fas fa-bars" id="sidebarToggle"></i></a></div>
  </li>


  <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('admin-dashboard-page') ? 'active' : '' }}" href="{{ route('admin-dashboard-page') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
  <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('admin-area-page') ? 'active' : '' }}" href="{{ route('admin-area-page') }}"><i class="fas fa-building mx-3"></i><span class="text-nowrap mx-2">Areas</span></a></li>
  <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('admin-surveys-list') ? 'active' : '' }}" href="{{ route('admin-surveys-list') }}"><i class="fas fa-chart-bar mx-3"></i><span class="text-nowrap mx-2">Surveys</span></a></li>
  <li class="nav-item dropdown {{ request()->routeIs('admin-user-list') || request()->routeIs('admin-pending-users-page') || request()->routeIs('admin-rejected-users-page') || request()->routeIs('list-dcc-po') ? 'show' : '' }}">
    <a data-bs-auto-close="false" class="dropdown-toggle nav-link text-start py-1 px-0 position-relative {{ request()->routeIs('admin-user-list') || request()->routeIs('admin-pending-users-page') || request()->routeIs('admin-rejected-users-page') || request()->routeIs('list-dcc-po') || request()->routeIs('admin-role-page') ? 'active' : '' }}" aria-expanded="true" data-bs-toggle="dropdown" href="#"><i class="fas fa-user-alt mx-3"></i><span class="text-nowrap mx-2">Users</span><i class="fas fa-caret-down float-none float-lg-end me-3"></i></a>
        <div class="dropdown-menu drop-menu border-0 animated fadeIn {{ request()->routeIs('admin-user-list') || request()->routeIs('admin-pending-users-page') || request()->routeIs('admin-rejected-users-page') || request()->routeIs('list-dcc-po') || request()->routeIs('admin-role-page') ? 'show' : '' }}" data-bs-popper="none">
            <a class="dropdown-item {{ request()->routeIs('admin-pending-users-page') ? 'active' : '' }}" href="{{ route('admin-pending-users-page') }}"><span>Pending</span></a>
            <a class="dropdown-item {{ request()->routeIs('admin-rejected-users-page') ? 'active' : '' }}" href="{{ route('admin-rejected-users-page') }}"><span>Rejected</span></a>
            <a class="dropdown-item {{ request()->routeIs('admin-assign-users') ? 'active' : '' }}" href="{{ route('admin-assign-users') }}"><span>Assign Area</span></a>
            <a class="dropdown-item {{ request()->routeIs('admin-user-list') ? 'active' : '' }}" href="{{ route('admin-user-list') }}"><span>User List</span></a>
        </div>
  </li>
  <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-page') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>
  <!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0" href="#"><i class="fas fa-chart-bar mx-3"></i><span class="text-nowrap mx-2">Statistics</span></a></li> -->
</ul>

