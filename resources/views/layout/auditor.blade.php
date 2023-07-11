<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('templates') ? 'active' : '' }}" href="{{ route('templates') }}"><i class="fas fa-newspaper mx-3"></i><span class="text-nowrap mx-2">Templates</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('auditor/audit-evidence*')  || request()->is('evidences*') ? 'active' : '' }}" href="{{ route('auditor.audit.evidence.index') }}"><i class="fas fa-receipt mx-3"></i><span class="text-nowrap mx-2">Audit Evidences</span></a></li>
<li class="nav-item dropdown {{ request()->is('audit-reports*') || request()->is('auditor/audit-reports*') ? 'active' : '' }}">
    <a data-bs-auto-close="false" class="dropdown-toggle nav-link text-start py-1 px-0 position-relative" aria-expanded="true" data-bs-toggle="dropdown" href="#"><i class="fas fa-receipt mx-3"></i><span class="text-nowrap mx-2">Audit Reports</span><i class="fas fa-caret-down float-none float-lg-end me-3"></i></a>
    <div class="dropdown-menu drop-menu border-0 animated fadeIn {{ request()->is('audit-reports*') || request()->is('auditor/audit-reports*') ? 'show' : '' }}" data-bs-popper="none">
        <a class="dropdown-item {{ request()->is('audit-reports*') || request()->routeIs('auditor.audit-reports.index') ? 'active' : '' }}" href="{{ route('auditor.audit-reports.index') }}"><span>Audit Report List</span></a>
        <a class="dropdown-item {{request()->routeIs('auditor.audit-reports.create') ? 'active' : '' }}" href="{{ route('auditor.audit-reports.create') }}"><span>Add Audit Report</span></a>
    </div>
</li>
<!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-shared') ? 'active' : '' }}" href="{{ route('archives-shared') }}"><i class="fas fa-share mx-3 mx-3"></i><span class="text-nowrap mx-2">Shared with me</span></a></li> -->
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('archives*') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>