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
    <div class="container">
    <h1>Roles</h1>
    <div class="row g-3">
            @foreach ($data as $role)
                <div class="col-3">
                    <a href="roles/{{ $role->id }}" class="btn btn-success w-100">{{ $role->role_name }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
