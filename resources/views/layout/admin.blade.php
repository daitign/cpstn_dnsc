<style>

    @media (min-width: 992px) {
        body {
            padding-left: 14rem;
        }
    }

    .drop-menu .active{
        background-color: #ffffff !important;
    }

    .drop-menu .active span{
        color: #005b40 !important;
    }

    /* Sidebar Styles */

    .sidebar {
        max-width: 14rem !important;
        width: 100%;
        min-height: 100vh;
        background-color: #005b40;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1;
    }

    .sidebar .logo {
        font-size: 1.6rem;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #ffffff;
        opacity: 1;
        color: #005b40;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active span {
        background-color: #ffffff;
        opacity: 1;
        color: #005b40;
    }

    .sidebar .nav-link span {
        font-size: 1rem;
        color: #ffffff;
    }

    .sidebar .nav-link:hover span {
        font-size: 1rem;
        color: #005b40;
    }

    .sidebar .dropdown-toggle:after {
        display: none;
    }

    .sidebar .dropdown-menu {
        position: relative !important;
        padding: 0;
        margin: 0;
        width: 100%;
        overflow: hidden;
        transform: unset !important;
        top: unset !important;
        left: unset !important;
        min-width: unset !important;
        background-color: #005b40;
        border-radius: 0 !important;
    }

    .sidebar .dropdown-item {
        padding: 0.8rem 0 0.8rem 1.5rem;
        margin: 0;
    }

    .sidebar .dropdown-item:hover,
    .sidebar .dropdown-item:active,
    .dropdown-item:focus {
        background-color: #005b40;
    }

    .sidebar .nav-link {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        font-size: 1rem;
        position: relative;
        opacity: 0.9;
        color: #ffffff;
    }

    .sidebar .fas.fa-caret-down.float-none.float-lg-right.fa-sm {
        position: absolute;
        top: 50%;
        right: 5%;
        transform: translate(-50%, -50%);
    }

    .sidebar.collapsed .nav-item:not(.logo-holder) {
        display: none !important;
    }

    @media (max-width: 991px) {
        .sidebar.mobile-hid .nav-item {
            display: none !important;
        }
    }

    .sidebar.collapsed #sidebarToggleHolder {
        position: absolute !important;
        color: #ffffff !important;
        left: 0;
        top: 0;
        padding: 10px;
        z-index: 999;
        margin-top: 3px;
    }

    @media (max-width: 991px) {
        .sidebar.mobile-hid #sidebarToggleHolder {
            position: absolute !important;
            color: #858791 !important;
            left: 0;
            top: 0;
            margin: 10px;
            z-index: 999;
        }
    }

    .sidebar.collapsed .logo #title {
        display: none;
    }

    @media (max-width: 991px) {
        .sidebar.mobile-hid .logo #title {
            display: none;
        }
    }

    .sidebar.collapsed #sidebarToggleHolder {
        float: none !important;
    }

    @media (max-width: 991px) {
        .sidebar.mobile-hid #sidebarToggleHolder {
            float: none !important;
        }
    }

    .sidebar.collapsed {
        width: 0 !important;
    }

    @media (max-width: 991px) {
        .sidebar.mobile-hid {
            width: 0 !important;
        }
    }

    .sidebar #sidebarToggleHolder {
        font-size: 20px !important;
        margin: 7px 5px;
    }

    .dropdown-item span {
        color: #ffffff;
    }

    .dropdown-item:hover span {
        color: #005b40;
    }

    .dropdown-item:hover {
        background-color: #ffffff !important;
        /*color: #ffffff;*/
    }

    .dropdown-menu .dropdown-item .active {
        background-color: #ffffff !important;
    }

    #title {
        color: #ffffff;
    }
</style>
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
            <a class="dropdown-item {{ request()->routeIs('list-dcc-po') ? 'active' : '' }}" href="{{ route('list-dcc-po') }}"><span>Assign Area</span></a>
            <a class="dropdown-item {{ request()->routeIs('admin-user-list') ? 'active' : '' }}" href="{{ route('admin-user-list') }}"><span>User List</span></a>
        </div>
  </li>
  <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-page') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>
  <!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0" href="#"><i class="fas fa-chart-bar mx-3"></i><span class="text-nowrap mx-2">Statistics</span></a></li> -->
</ul>

