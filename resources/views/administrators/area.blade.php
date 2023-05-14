@extends('layout.sidebar')
@section('title')
    <title>Area</title>
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
    </style>
@endsection
@section('page')
    <div class="page-header pb-2 px-3">
        <h1>Areas</h1>
        <div class="row">
            <div class="col-8">
                @foreach ($main_areas as $row)
                    <button type="button" class="btn btn-design btn-main-area me-2 {{ $loop->index == 0 ? 'active' : ''}}" data-value="{{ $row->id }}"><span class="mdi mdi-domain"></span> {{ $row->area_name }}</button>
                @endforeach
            </div>
            <div class="col-4 d-flex align-items-center justify-content-end">
                <div class="dropdown">
                    <button type="button" class="btn btn-success" data-bs-toggle="dropdown" aria-expanded="false"><span
                            class="mdi mdi-plus"></span> Add</button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item text-success btn-add" data-type="office" data-bs-toggle="modal" data-bs-target="#areaModal"><span class="mdi mdi-home-account text-success"></span>
                                Office</button></li>
                        <li><button class="dropdown-item text-success btn-add" data-type="institute" data-bs-toggle="modal" data-bs-target="#areaModal"><span
                                    class="mdi mdi-domain text-success"></span>
                                Institute</button></li>
                        <li><button class="dropdown-item text-success btn-add" data-type="program" data-bs-toggle="modal" data-bs-target="#areaModal"><span class="mdi mdi-office-building text-success"></span>
                                Program</button></li>
                        <li><button class="dropdown-item text-success btn-add" data-type="process" data-bs-toggle="modal" data-bs-target="#areaModal"><span class="mdi mdi-folder-table text-success"></span>
                                Process</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Messages --}}
    <div class="bg-white" style="min-height: 20vh">
        <div class="container row px-3 mt-3">
            @include('layout.alert')
            <div class="col-8 mt-3 row area-container">
                
            </div>

            <div class="col-8 mt-3 row sub-area-container">

            </div>
        </div>
    </div>

    <!-- Office Modal -->
    <div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="areaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize" id="areaModalLabel">Add Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="areaForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="area_id" id="area_id">
                        <input type="hidden" name="area_type" id="area_type">
                        <div class="select-container">

                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Name</label>
                            <input type="text" class="form-control" id="area_name" name="area_name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Description</label>
                            <input type="text" class="form-control" id="area_description" name="area_description" placeholder="Enter description" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var areas = {!! json_encode($areas) !!};
        var main_areas = {!! json_encode($main_areas) !!};

        document.addEventListener('DOMContentLoaded', function() {
            var area_container = $('.area-container');
            var sub_area_container = $('.sub-area-container');

            function loadArea(area_id) {
                area_id = parseInt(area_id);
                var area = main_areas.find(item => item.id === area_id);
                area_container.html('');
                sub_area_container.html('');

                var child_areas = areas.filter(i => i.parent_area == area_id);
                if(child_areas.length > 0) {
                    var type = area.area_name == 'Administration' ? 'Offices' : 'Institute';
                    area_container.html('<h2 class="my-3">' + type + '</h2>');

                    child_areas.forEach(function(i){
                        area_container.append(`<div class="col-2 text-center">
                            <button class="form-control pt-3 btn align-items-center justify-content-center btn-sub-area" data-area-id="` + i.id + `" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-building fa-2x"></i>
                                <p style="text-overflow: ellipsis"><small>` +  i.area_name + `</small></p>
                            </button>
                            <ul class="dropdown-menu text-left">
                                <li><button type="button" class="dropdown-item btn-edit" data-type="` + i.type + `" data-area-id="` + i.id + `" data-bs-toggle="modal" data-bs-target="#areaModal">Edit</button></li>
                            </ul>
                        </div>`);
                    });
                }
            }
            loadArea(main_areas[0].id);

            $('.btn-main-area').on('click', function(){
                $('.btn-main-area').removeClass('active');
                $(this).addClass('active');
                loadArea($(this).data('value'));
            });

            function loadSubArea(area_id) {
                area_id = parseInt(area_id);
                var area = areas.find(item => item.id === area_id);
                sub_area_container.html('');
                
                var child_areas = areas.filter(i => i.parent_area == area_id);
                if(child_areas.length > 0) {
                    var type = area.area_name == 'office' ? 'Process' : 'Program';
                    sub_area_container.html('<h2 class="my-3">' + type + '</h2>');

                    child_areas.forEach(function(i){
                        sub_area_container.append(`<div class="col-2 text-center">
                            <button class="pt-3 btn align-items-center justify-content-center btn-sub-area" data-area-id="` + i.id + `" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa fa-book fa-2x"></i>
                                <p style="text-overflow: ellipsis"><small>` + i.area_name + `</small></p>
                            </button>
                            <ul class="dropdown-menu text-left">
                                <li><button type="button" class="dropdown-item btn-edit" data-area-id="` + i.id + `" data-type="` + i.type + `" data-bs-toggle="modal" data-bs-target="#areaModal">Edit</button></li>
                            </ul>
                        </div>`);
                    });
                }
            }
            $('.area-container').on('click', '.btn-sub-area', function(){
                $('.btn-sub-area').removeClass('active');
                $(this).addClass('active');
                loadSubArea($(this).data('area-id'));
            });

            $('.area-container').on('click', '.btn-sub-area', function(){
                $('.btn-sub-area').removeClass('active');
                $(this).addClass('active');
                loadSubArea($(this).data('area-id'));
            });

            function initModal(type, area = null)
            {
                $('#area_id').val('');
                $('#area_type').val(type);
                $('#areaModalLabel').html('Add ' + type);
                $('.select-container').html('');
                $('#areaModal .form-control').val('');
                var parent_area = '';

                if(area) {
                    $('#areaModalLabel').html('Edit ' + type);
                    $('#area_id').val(area.id);
                    $('#area_name').val(area.area_name);
                    $('#area_description').val(area.area_description);
                }else{
                    if(type == 'process') {
                        $('.select-container').append(`<div class="mb-3">
                            <label for="parent_area" class="form-label">Office</label>
                            <select class="form-control area-select" id="parent_area" name="parent_area" required></select>
                        </div>`);
                        var parent_areas = areas.filter(i => i.type == 'office');
                        parent_areas.forEach(function(i){
                            var selected = parent_area == i.id ? ' selected' : '';
                            $('.area-select').append(`<option value="` + i.id + `"`+ selected +`>` + i.area_name + `</option`);
                        });
                    }

                    if(type == 'program') {
                        $('.select-container').append(`<div class="mb-3">
                            <label for="parent_area" class="form-label">Institute</label>
                            <select class="form-control area-select" name="parent_area" required></select>
                        </div>`);
                        var parent_areas = areas.filter(i => i.type == 'institute');
                        parent_areas.forEach(function(i){
                            var selected = parent_area == i.id ? ' selected' : '';
                            $('.area-select').append(`<option value="` + i.id + `"`+ selected +`>` + i.area_name + `</option`);
                        });
                    }
                }
            }
            $('.btn-add').on('click', function(){
                var type = $(this).data('type');
                $('#areaForm').prop('action', "{{ route('admin-area-store') }}");
                initModal(type);
            });

            $('.container').on('click', '.btn-edit', function(){
                var area_id = $(this).data('area-id');
                var type = $(this).data('type');
                
                area_id = parseInt(area_id);
                var area = areas.find(item => item.id === area_id);
                $('#areaForm').prop('action', "{{ route('admin-area-update') }}");
                initModal(type, area);
            });
        });
    </script>
@endsection
