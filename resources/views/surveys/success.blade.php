@extends('layout.app')
@section('title')
<title>Document Archiving and Tracking</title>
@endsection
@section('css')
    <style>
        .h-fit{
            height: fit-content
        }
        .link{
            color: black;
            text-decoration: none;
        }
        .link:hover{
            color: #198754;
        }
        .steps {
            display: none !important;
        }
        body {
            background-image: url('/storage/assets/bg.png');
            background-size: contain;
        }
    </style>
@endsection
@section('content')
    <div class="green-line align-center text-center">
        <img src="/storage/assets/dnsc-logo.png" width="200px" alt="dnsc icon" class="img-fluid">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 p-3">
                <div class="d-flex align-items-center">
                    <div class="container text-center bg-white p-3 rounded w-100 h-100">
                        <div class="mt-4 mb-5">
                            <i class="text-success fa fa-check-circle fa-4x mb-3"></i>
                            <h3 class="text-success">THANK YOU FOR FILLING UP THE SURVEY!!</h3>
                            <h4 class="mt-5 mb-2">Would you like to take another survey?</h4>
                            <a href="{{ route('surveys.create') }}" class="btn btn-success">Yes</a>
                            <a href="{{ route('login-page') }}" class="btn btn-danger">No</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@vite(['resources/js/login.js'])
@endsection