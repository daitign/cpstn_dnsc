@extends('layout.sidebar')
@section('title')
<title>Dashboard</title>
@endsection
@section('page')
    <div class="container">
        <h1>Dashboard</h1>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-3">
                <div class="calendar"></div>
            </div>
            <div class="col-3">
                <div class="card p-3 text-center">
                    <div class="card-body pt-2">
                        <i class="fa fa-file fa-2x"></i>
                        <h4>Files</h4>
                        <p>{{ $data->files }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(".calendar").flatpickr({
        inline: true
    });
</script>
@endsection