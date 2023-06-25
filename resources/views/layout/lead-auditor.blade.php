<ul class="nav flex-column shadow d-flex sidebar mobile-hid">
    <li class="nav-item logo-holder">
        <div class="text-center text-white logo py-4 mx-4"><img class="img-fluid"
                src="{{ asset('storage/assets/dnsc-logo.png') }}" width="55" height="50"><a id="title"
                class="text-decoration-none" href="#"><strong>DNSC</strong></a><a class="float-end text-white"
                id="sidebarToggleHolder" href="#"><i class="fas fa-bars" id="sidebarToggle"></i></a></div>
    </li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
    <li class="nav-item dropdown {{ request()->is('lead-auditor/templates*') ? 'show' : '' }}">
        <a data-bs-auto-close="false" class="dropdown-toggle nav-link text-start py-1 px-0 position-relative {{request()->is('template*') ? 'active' : '' }}" aria-expanded="true" data-bs-toggle="dropdown" href="#"><i class="fas fa-newspaper mx-3"></i><span class="text-nowrap mx-2">Templates</span><i class="fas fa-caret-down float-none float-lg-end me-3"></i></a>
        <div class="dropdown-menu drop-menu border-0 animated fadeIn {{request()->is('lead-auditor/templates*') ? 'show' : '' }}" data-bs-popper="none">
            <a class="dropdown-item {{request()->routeIs('lead-auditor.template.index') ? 'active' : '' }}" href="{{ route('lead-auditor.template.index') }}"><span>Template List</span></a>
            <a class="dropdown-item {{request()->routeIs('lead-auditor.template.create') ? 'active' : '' }}" href="{{ route('lead-auditor.template.create') }}"><span>Add Template</span></a>
        </div>
    </li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('evidences') ? 'active' : '' }}" href="{{ route('evidences') }}"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Evidences</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('lead-auditor.audit.index') || request()->is('lead-auditor/audit-plan*') ? 'active' : '' }}" href="{{ route('lead-auditor.audit.index') }}"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Audit Plan</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('lead-auditor/audit-reports*') || request()->is('audit-reports*')  ? 'active' : '' }}" href="{{ route('lead-auditor.audit-reports.index') }}"><i class="fas fa-receipt mx-3"></i><span class="text-nowrap mx-2">Audit Reports</span></a></li>
    <!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-shared') ? 'active' : '' }}" href="{{ route('archives-shared') }}"><i class="fas fa-share mx-3 mx-3"></i><span class="text-nowrap mx-2">Shared with me</span></a></li> -->
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('archives') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>
</ul>