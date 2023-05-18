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
            <form method="POST" action="{{ route('lead-auditor.audit.save') }}">
                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Select Process</label>
                        <input type="hidden" name="area" id="area">
                        <div id="tree"></div>
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
                <div style="text-align: right" class="pb-5">
                    <button type="submit" class="btn btn-success px-3 py-2">Save Audit Plan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('packages/bootstrap-treeview-1.2.0/src/js/bootstrap-treeview.js') }}"></script>
<script>
    
    var areas = {!! json_encode($tree_areas) !!};

    $('#tree').treeview({
        data: areas,
        levels: 1,
        collapseIcon: "fa fa-minus",
        expandIcon: "fa fa-plus",
        onNodeSelected: function(event, data) {
            $('#area').val(data.id);
        }
    });

    $('.select2').select2();
</script>
@endsection