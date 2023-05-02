@extends('layout.sidebar')
@section('title')
    <title>Pending Users</title>
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
        <h1>Pending Users</h1>
    </div>
    {{-- Transaction Messages --}}
    <div class="container">
        @if (session('success'))
            <div class="mt-3 alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-3 alert alert-danger alert-dismissible fade show">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container mt-3">
        <div class="row">
            @foreach ($data as $user)
            <div class="col-4">
                <div class="card">
                    <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="card-img-top maxed" alt="User Image">
                    <div class="card-body">
                        <h4 class="text-center">
                            {{ Str::limit($user->firstname . ' ' . ($user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '. ' : '') . $user->surname . ' ' . ($user->suffix ? $user->suffix : ''), 26, '...') }}
                        </h4>
                        <div class="text-center">
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Delete</button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#approve" class="btn btn-outline-success approve" value="{{ $user->id }}">Register</button>
                            </form>
                        </div>
                    </div>
                  </div>
            </div>
            @endforeach
            @if (count($data) == 0)
                <marquee><h1>No pending users</h1></marquee>
            @endif
        </div>
    </div>

    <!-- Modal -->
    {{-- Assign --}}
    <div class="modal fade" id="approve" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Approve User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin-approve-user') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="mb-3">
                            <span>Role</span>
                            <select name="role_id" class="form-control" required>
                                <option value="" disabled selected>Select a role</option>
                                @foreach ($data2 as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click','.approve', function () {
                $('#user_id').val($(this).val());
            });
        });
    </script>
@endsection
