@extends('layout.app')

@section('css')
<style>
    .container {
        margin-left: 0px !important;
    }

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
        position: fixed;
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
@endsection
@section('content')
    @if (auth()->user()->role->role_name == 'Administrator')
        @include('layout.admin')
    @elseif (auth()->user()->role->role_name == 'Document Control Custodian')
        @include('layout.dcc')
    @elseif (auth()->user()->role->role_name == 'Process Owner')
        @include('layout.po')
    @elseif (auth()->user()->role->role_name == 'Staff')
        @include('layout.staff')
    @elseif (auth()->user()->role->role_name == 'Human Resources')
        @include('layout.hr')
    @endif
    <nav class="navbar navbar-light navbar-expand-md" style="background-color: #37a87f;">
        <div class="container-fluid"><button data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span
                    class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <p class="navbar-text text-white ms-5" style="margin-bottom: 0;">Office of the Director for Quality
                    Assurance ({{ auth()->user()->role->role_name }})</p>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2"><a class="nav-link active" href="#"><i
                                class="fas fa-comment-alt text-warning" title="10"></i><span class="text-warning"
                                style="font-size: 10px;margin: 0px;margin-top: 0px;position: absolute;">10</span></a></li>
                    <li class="nav-item me-2"><a class="nav-link" href="#"><i class="fas fa-bell text-white"></i></a>
                    </li>
                    <li class="nav-item me-2"><a class="nav-link" href="#"><i class="fas fa-user text-white"></i></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}"><i class="fas fa-power-off text-white"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('page')
    {{-- <div class="d-flex h-100 sidebar-h">
        <div class="sidebar">
            @if (auth()->user()->role->role_name == 'Administrator')
                @include('layout.admin')
            @endif
        </div>
        <div class="overflow-auto w-100">
            <nav class="navbar navbar-expand-lg border-bottom border-dark ac" style="max-width:100%;height:4rem">
                <div class="container-fluid">
                    <span class="navbar-brand text-white">Office of the Director for Quality Assurance
                        ({{ auth()->user()->role->role_name }})</span>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown dropstart">
                                <button class="nav-link btn mt-2 remove-design me-1" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    @if (auth()->user()->img)
                                        <img src="{{ asset('/storage/profiles/' . auth()->user()->img) }}"
                                            class="rounded-circle avatar" alt="your image">
                                    @else
                                        <span class="mdi mdi-account rounded-circle avatar"></span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li>
                            <li class="nav-item pt-1">
                                <a href="{{ route('logout') }}" class="nav-link"><span class="mdi mdi-power text-white" style="font-size: 1.5rem;"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('page')
        </div>
    </div> --}}
    @vite(['resources/js/sidebar.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function($) {
            let win = $(window);
            let w = win.width();

            let body = $('body');
            let btn = $('#sidebarToggle');
            let sidebar = $('.sidebar');

            // Collapse on load

            if (win.width() < 992) {
                sidebar.addClass('collapsed');
            }

            sidebar.removeClass('mobile-hid');

            // Events

            btn.click(toggleSidebar);

            win.resize(function() {

                if (w == win.width()) {
                    return;
                }

                w = win.width();

                if (w < 992 && !sidebar.hasClass('collapsed')) {
                    toggleSidebar();
                } else if (w > 992 && sidebar.hasClass('collapsed')) {
                    toggleSidebar();
                }
            });

            function toggleSidebar() {

                if (win.width() < 992 || !sidebar.hasClass('collapsed')) {
                    body.animate({
                        'padding-left': '0'
                    }, 100);
                } else if (win.width() > 992 && sidebar.hasClass('collapsed')) {
                    body.animate({
                        'padding-left': '14rem'
                    }, 100);
                }

                if (!sidebar.hasClass('collapsed')) {
                    sidebar.fadeOut(100, function() {
                        btn.hide();
                        sidebar.addClass('collapsed');
                        btn.fadeIn(100);
                    });
                } else {
                    sidebar.removeClass('collapsed');
                    sidebar.fadeIn(100);
                }

            }
            })(jQuery) 
        });
    </script>
@endsection
