@extends('layout.sidebar')
@section('title')
    <title>Profile</title>
@endsection
@section('css-page')
    <style>
        .btn-design {
            border: 1px solid #000000 !important;
            font-size: 1em !important;
        }

        .btn-design:hover{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .row .col-4 .active{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .row .col-8 .active{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .maxed{
            min-height: 16rem;
            max-height: 16rem;
        }
    </style>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>User Profile</h1>
    </div>
    <div class="container">
        <div class="g-3 bg-white mt-2" style="overflow-y: auto; height:50vh;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="form-control" alt="User Image">
                        </div>
                        <div class="col-9 row">
                            
                            <div class="col-6 mt-3">
                                <span>Username</span>
                                <input type="text" class="form-control" name="username" placeholder="username" value="{{ $user->username }}" disabled>
                            </div>
                            <div class="col-6 mt-3">
                                <span>Role</span>
                                <input type="text" class="form-control" name="username" placeholder="username" value="{{ $user->role->role_name }}" disabled>
                            </div>
                            @if(in_array($user->role->role_name, config('app.role_with_assigned_area')))
                                <div class="col-12 mt-3">
                                    <span>Assigned on</span>
                                    @php
                                        if(!empty($user->assigned_area) && $user->assigned_area->type == 'process') {
                                           $assigned_on = sprintf("%s > %s", $user->assigned_area->parent->area_name ?? '', $user->assigned_area->area_name ?? 'None');
                                        } else {
                                            $assigned_on = $user->assigned_area->area_name ?? 'None';
                                        }
                                    @endphp
                                    <input type="text" class="form-control" name="suffix" placeholder="Enter suffix" value="{{ $assigned_on }}" disabled>
                                </div>
                            @endif

                            <div class="col-6 mt-3">
                                <span>Firstname</span>
                                <input type="text" class="form-control" name="firstname" placeholder="Enter firstname" disabled value="{{ $user->firstname }}">
                            </div>
                            <div class="col-6 mt-3">
                                <span>Middlename</span>
                                <input type="text" class="form-control" name="middlename" placeholder="Enter middlename" value="{{ $user->middlename }}" disabled>
                            </div>
                            <div class="col-6 mt-3">
                                <span>Surname</span>
                                <input type="text" class="form-control" name="surname" placeholder="Enter surname" value="{{ $user->surname }}" disabled>
                            </div>
                            <div class="col-6 mt-3">
                                <span>Suffix</span>
                                <input type="text" class="form-control" name="suffix" placeholder="Enter suffix" value="{{ $user->suffix }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection