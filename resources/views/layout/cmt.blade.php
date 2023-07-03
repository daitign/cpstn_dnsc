<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('templates') ? 'active' : '' }}" href="{{ route('templates') }}"><i class="fas fa-newspaper mx-3"></i><span class="text-nowrap mx-2">Templates</span></a></li>
<li class="nav-item">
    <a class="nav-link text-start py-1 px-0 {{ request()->routeIs('cmt.survey-reports') ? 'active' : '' }}" href="{{ route('cmt.survey-reports') }}">
        <i class="fas fa-book mx-3 mx-3 mx-3"></i><span class="text-nowrap mx-2">Pending SR</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link text-start py-1 px-0 {{ request()->routeIs('cmt.consolidated-audit-reports') ? 'active' : '' }}" href="{{ route('cmt.consolidated-audit-reports') }}">
        <i class="fas fa-receipt mx-3 mx-3 mx-3"></i><span class="text-nowrap mx-2">Pending CR</span>
    </a>
</li>
<!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-shared') ? 'active' : '' }}" href="{{ route('archives-shared') }}"><i class="fas fa-share mx-3 mx-3"></i><span class="text-nowrap mx-2">Shared with me</span></a></li> -->
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-page') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>

