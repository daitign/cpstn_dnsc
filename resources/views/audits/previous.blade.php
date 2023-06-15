@extends('layout.sidebar')
@section('title')
<title>Use Previous Audit Plan</title>
@endsection

@section('page')
    <div class="page-header">
        <h1>Use Previous Audit Plan</h1>
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
                            <input type="text" value="{{ $audit_plan->name ?? '' }}" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Select Process</label>
                            <input type="hidden" name="areas" id="areas" value="{{ $audit_plan->areas->pluck('area_id') }}">
                            <div id="tree"></div>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter description">{{ $audit_plan->description ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Date</label>
                            <input type="date" value="{{ $audit_plan->date ?? '' }}" class="form-control" id="date" name="date" placeholder="Enter date" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Auditors</label>
                            <select class="form-control select2" name="auditors[]" multiple required data-placeholder="Choose Auditors">
                                @foreach($auditors as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, $selected_users) ? 'selected' : '' }}>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="text-align: right" class="pb-5">
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
@endsection

@section('js')
<script src="{{ asset('packages/bootstrap-treeview-1.2.0/src/js/bootstrap-treeview.js') }}"></script>
<script>
    
    var areas = {!! json_encode($tree_areas) !!};

    var tree = $('#tree').treeview({
        data: areas,
        levels: 1,
        multiSelect: true,
        collapseIcon: "fa fa-minus",
        expandIcon: "fa fa-plus",
    });

    $('.select2').select2();

    $('.btn-save').on('click', function(){
        var selected = tree.treeview('getSelected');
        var selectedAreas = [];
        selected.forEach(function(area){
            selectedAreas.push(area.id)
        });
        $('#areas').val(selectedAreas);
    });
</script>
@endsection