@extends('layout.sidebar')
@section('title')
<title>Unassigned</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Unassigned</h1>
    </div>
    <div class="container">
        
        <div class="px-4 text-center">
            <i class="text-danger fa fa-times fa-3x mb-3 mt-3"></i>
            <h2>Unable to process request</h2>
            <h3>You didn't have assigned area yet!<br/> Please contact administrator</h3>
        </div>

    </div>
@endsection
@section('js')
@endsection