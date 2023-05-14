@extends('layout.sidebar')
@section('title')
    <title>User - Assign Area</title>
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
        <h1>Assign Area</h1>
        
        <div class="row">
            <div class="col-8 px-3">
                @php $roles = ['Document Control Custodian', 'Process Owner'] @endphp
                @foreach($roles as $role)
                    <button type="button" class="btn btn-design btn-role me-2 {{ $loop->index == 0 ? 'active' : ''}}" data-role="{{ $role }}"><span class="mdi mdi-domain"></span> {{ $role }}</button>
                @endforeach
            </div>
        </div>
    </div>
    {{-- Transaction Messages --}}
    <div class="container">
        @if (session('success'))
            <div class="alert mt-2 alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert mt-2 alert-danger alert-dismissible fade show">
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
            <div class="col-3 user-item" data-user-role="{{ $user->role->role_name }}">
                <div class="card">
                    <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="card-img-top form-control" alt="User Image">
                    <div class="card-body text-center">
                        <h5>
                            {{ Str::limit($user->firstname . ' ' . ($user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '. ' : '') . $user->surname . ' ' . ($user->suffix ? $user->suffix : ''), 26, '...') }}
                        </h5>
                        <h6><Strong>{{ $user->role_name ?? ''}}</strong></h6>
                        <h6><small>
                            Assigned on: 
                            @if(!empty($user->assigned_area) && $user->assigned_area->type == 'process')
                                {{ sprintf("%s > %s", $user->assigned_area->parent->area_name ?? '', $user->assigned_area->area_name ?? 'None') }}
                            @else
                                {{ $user->assigned_area->area_name ?? 'None' }}
                            @endif
                            </small>
                        </h6>
                        
                        <hr>
                        <div class="text-center">
                            <button type="button" data-user-id="{{ $user->id }}" data-type="{{ $user->role->role_name }}" data-bs-toggle="modal" data-bs-target="#assign_modal" class="btn btn-outline-success btn-assign" value="{{ $user->id }}">Assign</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if (count($data) == 0)
                <h1>No DCC/PO users</h1>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="assign_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin-assign-user') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="user_type" id="user_type">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="select-container"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var areas = {!! json_encode($areas) !!};
            var main_areas = {!! json_encode($main_areas) !!};

            $('.btn-assign').on('click',function () {
                var user_type = $(this).data('type');
                $('#user_id').val($(this).data('user-id'));
                $('#user_type').val(user_type);

                $('#area').val('');
                $('#sub_area_container').addClass('d-none');

                $('.select-container').html('');
                if(user_type == 'Document Control Custodian') {
                    $('.select-container').html(`<div class="mb-1">
                            <label for="area">Area</label>
                        <select id="area" name="area" required class="form-control"><option value="">Select Area</option></select></div>`);
                    
                    main_areas.forEach(function(main){
                        var options = `<optgroup label="` + main.area_name + `">`;
                        var child = areas.filter(c => c.parent_area == main.id);
                        child.forEach(function(c){
                            options += `<option value="` + c.id + `">` + c.area_name +`</option>`;
                        });
                        $('#area').append(options);
                    });
                }else{
                    $('.select-container').html(`<div class="mb-1">
                            <label for="area">Area</label>
                        <select id="main_area" required class="form-control"><option value="">Select Area</option></select></div>`);
                    
                    main_areas.forEach(function(main) {
                        var options = `<option value="` + main.id + `">` + main.area_name +`</option>`;
                        $('#main_area').append(options);
                    });
                    
                    $('.select-container').append('<div class="sub-select-container"></div>');
                }
            });

            function displayUser(user_role){
                $('.user-item').addClass('d-none');
                $('.user-item').each(function() {
                    if($(this).data('user-role') == user_role) {
                        $(this).removeClass('d-none');
                    }
                });
            }

            displayUser('Document Control Custodian');
            $('.btn-role').on('click', function(){
                $('.btn-role').removeClass('active');
                $(this).addClass('active');
                displayUser($(this).data('role'));
            });

            $('.select-container').on('change', '#main_area', function(){
                area_id = parseInt($(this).val());
                var area = areas.find(item => item.id === area_id);
                $('.sub-select-container').html('');
                var label = area.area_name == 'Administration' ? 'Office' : 'Institute';

                if(label == 'Office') {
                    $('.sub-select-container').append(`<div class="mt-3">
                        <label for="office" class="form-label">Select ` + label + `</label>
                        <select class="form-control sub-select" id="office" name="office" required><option value=''>Select Office</option></select>
                    </div><div class="mt-3 process-select">`);
                } else {
                    $('.sub-select-container').append(`<div class="mt-3">
                        <label for="institute" class="form-label">Select ` + label + `</label>
                        <select class="form-control sub-select" id="institute" name="institute" required><option value=''>Select Institute</option></select>
                    </div><div class="mt-3 program-select">`);
                }

                var parent_areas = areas.filter(i => i.parent_area == area.id);
                parent_areas.forEach(function(i){
                    $('.sub-select-container .sub-select').append(`<option value="` + i.id + `">` + i.area_name + `</option`);
                });
            });

            $('.select-container').on('change', '#institute', function(){
                area_id = parseInt($(this).val());
                var area = areas.find(item => item.id === area_id);
                $('.program-select').html('');

                $('.program-select').append(`<div class="mb-3">
                    <label for="program_area" class="form-label">Select Program</label>
                    <select class="form-control" id="program_area" name="program_area" required><option value=''>Select Program</option></select>
                </div><div class="mt-3 process-select">`);

                var parent_areas = areas.filter(i => i.parent_area == area.id);
                parent_areas.forEach(function(i){
                    $('#program_area').append(`<option value="` + i.id + `">` + i.area_name + `</option`);
                });
            });

            $('.select-container').on('change', '#program_area', function(){
                area_id = parseInt($(this).val());
                var area = areas.find(item => item.id === area_id);
                $('.process-select').html('');

                $('.process-select').append(`<div class="mb-3">
                    <label for="assign_area" class="form-label">Select Process</label>
                    <select class="form-control" id="assign_area" name="assign_area" required><option value=''>Select Process</option></select>
                </div>`);

                var parent_areas = areas.filter(i => i.parent_area == area.id);
                parent_areas.forEach(function(i){
                    $('#assign_area').append(`<option value="` + i.id + `">` + i.area_name + `</option`);
                });
            });

            $('.select-container').on('change', '#office', function(){
                area_id = parseInt($(this).val());
                var area = areas.find(item => item.id === area_id);
                $('.process-select').html('');

                $('.process-select').append(`<div class="mb-3">
                    <label for="assign_area" class="form-label">Select Process</label>
                    <select class="form-control" id="assign_area" name="assign_area" required><option value=''>Select Process</option></select>
                </div>`);

                var parent_areas = areas.filter(i => i.parent_area == area.id);
                parent_areas.forEach(function(i){
                    $('#assign_area').append(`<option value="` + i.id + `">` + i.area_name + `</option`);
                });
            });
        });
    </script>
@endsection