@extends('layout.sidebar')
@section('title')
<title>Evidence</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Evidence</h1>
    </div>
    <div class="container">
        @include('layout.alert')
        <div class="mb-4 row">
            <div class="row col-12 mt-4">
                @foreach(auth()->user()->assigned_areas as $area)
                    <div class="col-3">
                        <a href="#">
                            <div class="card bg-success text-white">
                                <div class="card-body ">
                                    <div class="block-content block-content-full">
                                        <div class="justify-content-center py-3">
                                            <div class="fs-md fw-semibold text-uppercase">{{ $area->parent->area_name.' > '.$area->area_name ?? '' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
@endsection