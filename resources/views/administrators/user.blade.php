@extends('layout.sidebar')
@section('title')
    <title>All Roles</title>
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
        <h2>User List</h2>
    </div>
    {{-- <div class="container"> --}}
        <div class="m-3">

        <div class="row mt-3">
            <div class="col-3 mb-2">
                <a href="{{ route('admin-user-list') }}" class="btn w-100 {{ $request_role == '' ? 'btn-success' : 'btn-primary' }}">All</a>
            </div>
            @foreach ($roles as $role)
                <div class="col-3 mb-2">
                    <a href="{{ route('admin-user-list') }}?role={{ $role->role_name ?? '' }}" class="btn w-100 {{ $request_role == $role->role_name ? 'btn-success' : 'btn-primary'}}">{{ $role->role_name }}</a>
                </div>
            @endforeach
        </div>
        <div class="row g-3 bg-transparent mt-2" style="overflow-y: auto; height:50vh;">
            @if(empty($users))
                <h3 class="text-center mt-4">No User Available Yet</h3>
            @endif
            @foreach ($users as $user)
            <div class="col-md-6 col-lg-2">
                <div class="card"><br>
                    <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="card-img-top rounded-circle mx-auto d-block" alt="User Image" style="width: 80px; height: 80px;">
                    <div class="card-body text-center">
                        <small class="card-title">
                            {{ Str::limit($user->firstname . ' ' . ($user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '. ' : '') . $user->surname . ' ' . ($user->suffix ? $user->suffix : ''), 26, '...') }}
                        </small>
                        <h6><strong>{{ $user->role_name ?? ''}}</strong></h6>
                        <hr>
                        <div class="text-center">
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size: smaller;"><small>Disable</small></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            
            @endforeach
        </div>
    </div>
@endsection