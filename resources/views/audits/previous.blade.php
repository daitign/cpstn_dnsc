</div><!DOCTYPE html>
<html lang="en">
<head>
  <!-- Include necessary Bootstrap CSS and other dependencies -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  
</head>

<body>
@extends('layout.sidebar')
@section('title')
<title>Previous Audit Plan</title>
@endsection

@section('page')
    <div class="page-header">
        <h2>Previous Audit Plan</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 p-3">
                <div class="m-3 bg-white py-2">
     <div class="px-3 py-2">
                        @include('layout.alert')
                        
                            <form id="auditPlanForm" method="POST" action="{{ route('lead-auditor.audit.save') }}">
                                @csrf
                                <div>
                                    <div class="mb-3">
                                        <label for="process" class="form-label">Name</label>
                                        <input type="text" value="{{ $audit_plan->name ?? '' }}" class="form-control shadow-none" id="name" name="name" placeholder="Enter name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="process" class="form-label">Description</label>
                                        <textarea class="form-control shadow-none" rows="3" id="description" name="description" placeholder="Enter description">{{ $audit_plan->description ?? '' }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="process" class="form-label">Date</label>
                                        <input type="date" value="{{ $audit_plan->date ?? '' }}" class="form-control shadow-none" id="date" name="date" placeholder="Enter date" required>
                                     </div><br>
                                    <div class="mt-2">
                                        <h4>Process and Auditors</h4>
                                        <button class="btn btn-success" style="float:right" type="button" data-bs-toggle="modal" data-bs-target="#addProcessModal"><i class="fa fa-plus"></i> Add Process</button><br><br>
                                        <table class="table text-black table-process">
                                            <thead>
                                                <tr>
                                                    <td>PROCESS</td>
                                                    <td>AUDITORS</td>
                                                    <td><i class="fas fa-cogs"></i></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($audit_plan->plan_areas as $plan_area)
                                                <tr>
                                                    <td>{{ $plan_area->area->getAreaFullName() }}</td>
                                                    <td>
                                                        @foreach($plan_area->users as $user)
                                                        {{ $user->first_name }} {{ $user->surname }}
                                                        @if($loop->index < count($plan_area->users) - 1)
                                                        ,
                                                        @endif
                                                        @endforeach
                                                    </td>
                                                    <td><button class="btn btn-danger btn-remove" type="button"><i class="fa fa-times"></i></button></td>
                                                    <input type="hidden" name="process[]" value="{{ $plan_area->area->id }}">
                                                    <input type="hidden" name="auditors[]" value="{{ implode(',', $plan_area->users->pluck('id')->toArray()) }}">
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                     </div>
                                </div>
                                <div style="text-align: right" class="pb-5">
                                    <button type="submit" class="btn btn-success btn-save px-3 py-2"><i class="fa fa-save"></i> Save Audit Plan</button>
                                </div>
                            </form>
                        
                    </div>
                </div>
            </div>
    
            <div class="col-lg-3 p-3">
                <div class="m-3 bg-white py-2">
     <button class="btn btn text-success" type="button" data-toggle="collapse" data-target="#internal-auditors" aria-expanded="true" aria-controls="internal-auditors" style="border: none; box-shadow: none;">
                        <i class="fas fa-bars"></i>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;INTERNAL AUDITORS
     </button>
    
                    <div class="collapse show m-3" id="internal-auditors" style="flex-direction: row-reverse;">
                        @if(auth()->user()->role->role_name == 'Internal Lead Auditor')
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                @foreach($auditors as $user)
                                <div class="media align-items-center mb-4">
                                    <img src="{{ Storage::url($user->img) }}" alt="Avatar" class="rounded-circle mr-3" alt="Profile Image" width="50">
    
                                    <div class="media-body">
                                        <h6 class="mt-0 text-primary">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h6>
                                        <p class="mb-0 text-success small">Assigned on:</p>
                                        <ul class="list-unstyled mb-0 text-muted small">
                                            @foreach($user->getAssignedAreas() as $assignedArea)
                                            <li class="mb-1">{{ $assignedArea }}</li>
                                            @endforeach
                                        </ul>
     </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    
        <div class="modal fade" id="addProcessModal" tabindex="-1" aria-labelledby="addProcessModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
     <div class="modal-header">
                        <h5 class="modal-title">Add Process And Auditors</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Select Process</label>
                            <input type="hidden" class="process" id="process">
                            <input type="hidden" class="process_name" id="process_name">
                            <div class="tree"></div>
                        </div>
                        <div class="mb-3 auditors-panel">
                            <label for="name" class="form-label">Auditors</label>
                            <select id="auditors" class="form-control select2" multiple required data-placeholder="Choose Auditors">
                                @foreach($auditors as $user)
                                <option value="{{ $user->id }}">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
     <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-add-process"><i class="fa fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-close-modal btn-secondary" data-bs-dismiss="modal">Close</button>
     </div>
                </div>
            </div>
        </div>
    </div>
    

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
@endsection


@section('js')
<script src="{{ asset('packages/bootstrap-treeview-1.2.0/src/js/bootstrap-treeview.js') }}"></script>
<script>
    
    var areas = {!! json_encode($tree_areas) !!};

    var tree = $('.tree').treeview({
        data: areas,
        levels: 1,
        collapseIcon: "fa fa-minus",
        expandIcon: "fa fa-plus",
        onNodeSelected: function(event, data) {
            $('.process').val(data.id);
            $('.process_name').val(data.text);
        }
    });

    $('.select2').select2({
        'width': '100%',
        dropdownParent: $('.auditors-panel')
    });

    $('.btn-save').on('click', function(e){
        e.preventDefault();
        if($('.table-process tbody > tr').length == 0) {
            Swal.fire({
                text: 'Please Add Process...',
                icon: 'warning',
            });
        }else{
            $('#auditPlanForm').submit();
        }
    });

    $('.btn-add-process').on('click', function(){
        var process_name = $('.process_name').val();
        var process_id = $('.process').val();
        
        var auditors_name = '';
        var auditors_id = '';
        $('#auditors option:selected').each(function(i, val){
            auditors_name += val.text;
            auditors_id  += val.value;
            if(i <  ($('#auditors option:selected').length -1)) {
                auditors_name += ', ';
                auditors_id += ',';
            }
        });
        $('.table-process tbody').append(`<tr>
                    <td>` + process_name + `</td>
                    <td>` + auditors_name + `</td>
                    <td>
                        <button class="btn btn-danger btn-remove" type="button"><i class="fa fa-times"></i></button>
                        <input type="hidden" name="process[]" value="` + process_id + `">
                        <input type="hidden" name="auditors[]" value="` + auditors_id + `">
                    </td>
            </tr>`);

        $('.btn-close-modal').trigger('click');
    });

    $(document).on('click','.btn-remove', function(){
        $(this).parents('tr').remove();
    });
</script>
@endsection