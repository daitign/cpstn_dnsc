<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}"><i class="fas fa-tachometer-alt mx-3"></i><span class="text-nowrap mx-2">Dashboard</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('templates*') || request()->routeIs('staff.template.index') ? 'active' : '' }}" href="{{ route('staff.template.index') }}"><i class="fas fa-newspaper mx-3"></i><span class="text-nowrap mx-2">Templates</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('manuals*') || request()->routeIs('staff.manual.index') ? 'active' : '' }}" href="{{ route('staff.manual.index') }}"><i class="fas fa-book mx-3"></i><span class="text-nowrap mx-2">Manuals</span></a></li>
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('messages') ? 'active' : '' }}" href="{{ route('messages') }}"><i class="fa fa-envelope mx-3"></i><span class="text-nowrap mx-2">Messages</span></a></li>
<!-- <li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->routeIs('archives-shared') ? 'active' : '' }}" href="{{ route('archives-shared') }}"><i class="fas fa-share mx-3 mx-3"></i><span class="text-nowrap mx-2">Shared with me</span></a></li> -->
<li class="nav-item"><a class="nav-link text-start py-1 px-0 {{ request()->is('archives*') ? 'active' : '' }}" href="{{ route('archives-page') }}"><i class="fas fa-archive mx-3"></i><span class="text-nowrap mx-2">Archive</span></a></li>