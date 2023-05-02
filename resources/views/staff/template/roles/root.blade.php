@extends('layout.sidebar')
@section('title')
    <title>Evidence</title>
@endsection
@section('page')
    <style>
        .folder{
            width: 140px;
            height: 123px;
            background-repeat: no-repeat;
            background-position: center;
            background-image: url("{{ Storage::url('assets/folder.png') }}");
            color: #ffffff;
        }
        .folder:hover{
            border: 2px #d1d1d1 solid;
            color: #ffffff;
        }
        .folder:focus{
            border: 2px #d1d1d1 solid !important;
            color: #ffffff !important;
        }
        .folder:active{
            border: 2px #d1d1d1 solid !important;
            color: #ffffff !important;
        }
        .file{
            width: 140px;
            height: 123px;
            background-repeat: no-repeat;
            background-position: center;
            color: #ffffff;
        }
        .file:hover{
            border: 2px #d1d1d1 solid;
            color: #ffffff;
        }
        .file:focus{
            border: 2px #d1d1d1 solid !important;
            color: #ffffff !important;
        }
        .file:active{
            border: 2px #d1d1d1 solid !important;
            color: #ffffff !important;
        }
        .dropdown-menu li .dropdown-item:hover{
            background-color: #498f49 !important;
            color: #ffffff !important;
        }
        /* .dropdown-menu li .dropdown-item:hover {
            background-color: #fa0303 !important;
            color: #ffffff !important;
        } */
    </style>

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
            
        @if (session('err'))
        <div class="mt-3 alert alert-danger alert-dismissible fade show">
            {{ session('err') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>

    <div class="container mt-3">
        <div class="row">
            <div class="col-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('staff.template.index') }}">Template</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('staff.template.roles') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $role->role_name }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-4 text-end">
                <div class="dropdown">
                    <button class="btn btn-success" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mdi mdi-plus"></span> Add
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add">Folder</button>
                        </li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addfile">File</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div class="row">
            @if (count($folders) > 0)
            <div class="col-12">
                <h3>Folders</h3>
            </div>
            @endif
            @foreach ($folders as $folder)
                <div class="col-2">
                    <div class="dropdown">
                        <a class="folder btn d-flex align-items-center justify-content-center" href="">
                            {{ $folder->folder_name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item fold" type="button" data-bs-toggle="modal" data-bs-target="#rename{{ $folder->id }}">Rename</button></li>
                            <li><button class="dropdown-item fold" type="button" data-bs-toggle="modal" data-bs-target="#remove{{ $folder->id }}">Delete</button></li>
                        </ul>
                    </div>
                    <div class="modal fade" id="rename{{ $folder->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Rename Folder Template</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('staff.template.folder.rename') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $folder->id }}">
                                        <div class="mb-2">
                                            <span>Folder Name</span>
                                            <input type="text" class="form-control" placeholder="Folder" name="folder" value="{{ $folder->folder_name }}" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="remove{{$folder->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Remove Folder Template</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('staff.template.folder.remove') }}" method="POST">
                                        @csrf
                                        {{-- <input type="hidden" id="whitelist" value='{!! $programs !!}'> --}}
                                        <input type="hidden" name="id" value="{{ $folder->id }}">
                                        <div class="mb-2">
                                            <h3 class="text-center">Are you sure you want to remove {{ $folder->folder_name }} folder?</h3>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger">Remove</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            @endforeach
            @if (count($files) > 0)
            <div class="col-12">
                <h3>Files</h3>
            </div>
            @endif
            @foreach ($files as $file)
                <div class="col-2">
                    <div class="dropdown">
                        <button class="folder btn d-flex align-items-center justify-content-center">
                            {{ $file->directory->filename }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div> 
            @endforeach
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Folder Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('staff.template.folder.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="belong" value="role">
                        <input type="hidden" name="type_id" value="{{ Route::current()->parameter('role') }}">
                        <div class="mb-2">
                            <span>Folder Name</span>
                            <input type="text" class="form-control" placeholder="Folder" name="folder" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Template</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('upload-evidence') }}" id="area" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="belong" value="role">
                        <input type="hidden" name="type_id" value="{{ Route::current()->parameter('role') }}">
                        <div class="mb-2">
                            <span>Filename</span>
                            <input type="text" class="form-control" placeholder="Filename" name="filename" required>
                        </div>
                        <div class="mb-2">
                            <span>File</span>
                            <input type="file" class="form-control" placeholder="File" name="file" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">Save</button>
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
            $(document).on('contextmenu','.folder', function (event) {
                event.preventDefault();
                $(this).siblings('.dropdown-menu').toggle();
            });
            $('.dropdown-item .fold').on('click', function() {
                $(this).parent().parent().toggle();
            });
            $(document).on('click', function(e) {
                if (!$('.dropdown-toggle').is(e.target) && $('.dropdown-toggle').has(e.target).length === 0 && $('.show').has(e.target).length === 0) {
                    $('.folder').siblings('.dropdown-menu').hide();
                }
            });
            $('#download').on('click', function () {
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
        });
    </script>
@endsection
