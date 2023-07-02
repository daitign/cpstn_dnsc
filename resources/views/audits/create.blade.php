@extends('layout.sidebar')
@section('title')
<title>Add Audit Plan</title>
@endsection

@section('page')
    <div class="page-header">
        <h1>Add Audit Plan</h1>
    </div>
    <div class="container">
        <div class="row mt-3 px-2 pb-3">
            @include('layout.alert')
            <div class="col-8">
                <form method="POST" action="{{ route('lead-auditor.audit.save') }}">
                    @csrf
                    <div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Enter date" required>
                        </div>

                        <div class="mt-2">
                            <h3>Process and Auditors</h3>
                            <button class="btn btn-success" style="float:right" type="button" data-bs-toggle="modal" data-bs-target="#addProcessModal"><i class="fa fa-plus"></i> Add Process</button>
                            <table class="table text-white">
                                <thead><tr><td>Process</td><td>Auditors</td><td>-</td></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div style="text-align: right" class="pb-5 mt-5">
                        <button type="submit" class="btn btn-success btn-save px-3 py-2">Save Audit Plan</button>
                    </div>
                </form>
            </div>
            <div class="col-4 mt-2 alert alert-success">
                <h3 class="mb-2">Internal Auditors</h3>
                @foreach($auditors as $user)
                    <h4 class="mb-0">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h4>
                    <p class="mb-2 mt-0"><small>Assigned on: {{ sprintf("%s > %s", $user->assigned_area->parent->area_name ?? '', $user->assigned_area->area_name ?? 'None') }}</small></p>
                @endforeach
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
                            <input type="hidden" class="process" name="process[]" id="process">
                            <div class="tree"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Auditors</label>
                            <select class="form-control select2" name="auditors[]" multiple required data-placeholder="Choose Auditors">
                                @foreach($auditors as $user)
                                    <option value="{{ $user->id }}">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-add-process"><i class="fa fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
        </div>
    </div>
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
        }
    });

    $('.select2').select2({
        'width': '100%',
        dropdownParent: $('#addProcessModal')
    });

    $('.btn-add-process').on('click', function(){
        
    });
</script>
@endsection