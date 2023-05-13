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

        .maxed{
            min-height: 16rem;
            max-height: 16rem;
        }
    </style>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>Assign Area</h1>
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
            <div class="col-3">
                <div class="card">
                    <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="card-img-top maxed" alt="User Image">
                    <div class="card-body text-center">
                        <h5>
                            {{ Str::limit($user->firstname . ' ' . ($user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '. ' : '') . $user->surname . ' ' . ($user->suffix ? $user->suffix : ''), 26, '...') }}
                        </h5>
                        <h6><Strong>{{ $user->role_name ?? ''}}</strong></h6>
                        <h6><small>Assigned on: {{ $user->assigned_area->area_name ?? 'None'}}</small></h6>
                        <hr>
                        <div class="text-center">
                            <button type="button" data-user-id="{{ $user->id }}" data-type="{{ $user->role->role_name }}" data-bs-toggle="modal" data-bs-target="#assign_modal" class="btn btn-outline-success btn-assign" value="{{ $user->id }}">Assign</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if (count($data) == 0)
                <marquee><h1>No DCC/PO users</h1></marquee>
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
                        <div class="mb-1">
                            <label for="area">Area</label>
                            <select id="area" name="area" required class="form-control">
                                <option value=''>Select Area</option>
                                @foreach($areas as $area)
                                    <optgroup label="{{ $area->area_name }}">
                                        @foreach($area->children as $child)
                                            <option value="{{ $child->id }}">{{ $child->area_name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3 d-none" id="sub_area_container">
                            <label for="sub_area" id="sub_label">Process</label>
                            <select id="sub_area" name="sub_area" required class="form-control">
                                <option value=''>Select Option</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success" disabled="true">Save</button>
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
            var areas = {!! json_encode($all_areas) !!};
            $('.btn-assign').on('click',function () {
                $('#user_id').val($(this).data('user-id'));
                $('#user_type').val($(this).data('type'));

                $('#area').val('');
                $('#btn-submit').prop('disabled', true);
                $('#sub_area_container').addClass('d-none');
            });

            $('#area').on('change', function(){
                var area_id = parseInt($(this).val());
                $('#sub_area_container').addClass('d-none');
                $('#btn-submit').prop('disabled', true);
                if($('#user_type').val() == 'Document Control Custodian') {
                    var area = areas.find(item => item.id === area_id);
                    var child_areas = areas.filter(item => item.parent_area == area_id);

                    if(child_areas.length > 0) {
                        $('#sub_area').html("<option value=''>Select Option</option>");
                        child_areas.forEach(function(i){
                            $('#sub_area').append("<option value='" + i.id + "'>" + i.area_name + "</option>");
                        });
                        $('#sub_area_container').removeClass('d-none');
                        $('#sub_label').html(area.type == 'institution' ? 'Program' : 'Process');
                    }
                }
                $('#btn-submit').prop('disabled', false);
            });
        });
    </script>
@endsection