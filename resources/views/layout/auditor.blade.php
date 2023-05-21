<ul class="nav flex-column shadow d-flex sidebar mobile-hid">
    <li class="nav-item logo-holder">
        <div class="text-center text-white logo py-4 mx-4"><img class="img-fluid"
                src="{{ asset('storage/assets/dnsc-logo.png') }}" width="55" height="50"><a id="title"
                class="text-decoration-none" href="#"><strong>DNSC</strong></a><a class="float-end text-white"
                id="sidebarToggleHolder" href="#"><i class="fas fa-bars" id="sidebarToggle"></i></a></div>
    </li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('auditor.audit.index') ? 'active' : '' }}" href="{{ route('auditor.template.index') }}"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Templates</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('auditor.audit.index') ? 'active' : '' }}" href="{{ route('auditor.evidence.index') }}"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Evidences</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-shared') ? 'active' : '' }}" href="{{ route('archives-shared') }}"><i class="fas fa-share mx-3 mx-3"></i><span class="text-nowrap mx-2">Shared with me</span></a></li>
    <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('archives') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>
</ul>