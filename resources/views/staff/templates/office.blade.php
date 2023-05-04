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

        }
    </style>
    <div class="container">
        <h1>Evidence</h1>
        {{-- <div class="text-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add">
                <span class="mdi mdi-plus"></span> Add
            </button>
        </div> --}}
    </div>
    {{-- Transaction Messages --}}
    <div class="container">
        
        {{-- List --}}
        <div class="row">
            @foreach ($programs as $item)
            <div class="col-2">
                {{-- {{ $item }} --}}
                <a class="folder btn d-flex align-items-center justify-content-center" href="{{ route('dcc-show-evidence').'/program/'.$item->program_name }}">
                    {{ $item->program_name }}
                </a>
            </div>
            @endforeach
            @foreach ($offices as $item)
            <div class="col-2">
                {{-- {{ $item }} --}}
                <a class="folder btn d-flex align-items-center justify-content-center" href="{{ route('dcc-show-evidence').'/office/'.$item->office_name }}">
                    {{ $item->office_name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Evidence</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('dcc-add-folder-evidence') }}" id="area" method="POST">
                        @csrf
                        <input type="hidden" id="whitelist" value='{!! $programs !!}'>
                        <input type="hidden" name="parent" value="{{ Route::current()->parameter('parent') }}">
                        <div class="mb-2">
                            <span>Folder Name</span>
                            <input type="text" class="form-control" placeholder="Folder" name="folder" required>
                        </div>
                        <div class="mb-2">
                            <span>Area Access</span>
                            <input type="text" class="form-control" placeholder="Area" name="area" id="area_input" required>
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
    </div> --}}
@endsection
