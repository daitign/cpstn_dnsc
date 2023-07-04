@extends('layout.sidebar')
@section('title')
<title>Unassigned</title>
@endsection
@section('page')
    <div class="2">
        <h2>Unassigned</h2>
    </div>
    {{-- <div class="container"> --}}
    <div class="m-3">
        
        <div class="px-4 text-center">
            <i class="text-danger fa fa-times fa-3x mb-3 mt-3"></i>
            <h2>Unable to process request</h2>
            <h3>You didn't have assigned area yet!<br/> Please contact administrator</h3>
        </div>

    </div>
@endsection
@section('js')
@endsection