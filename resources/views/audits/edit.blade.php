@extends('layout.sidebar')
@section('title')
<title>Edit Audit Plan</title>
@endsection

@section('page')
    <div class="page-header">
        <h1>Edit Audit Plan</h1>
    </div>
    <div class="container">
        <div class="row mt-3 px-2 pb-3">
            @include('layout.alert')
            <div class="col-8">
                <form method="POST" action="{{ route('lead-auditor.audit.save') }}">
                    @csrf
                    <div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Process</label>
                            <input type="text" class="form-control" value="{{ $audit_plan->area->parent->area_name.' > '. $audit_plan->area->area_name }}" readonly>
                            <input type="hidden" name="area" id="area" value="{{ $audit_plan->area_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Auditors</label>
                            <select class="form-control select2" name="auditors[]" multiple required data-placeholder="Choose Auditors">
                                @foreach($auditors as $user)
                                    <option value="{{ $user->id }}"
                                    {{ $user->assigned_area->id == $audit_plan->area_id ? 'selected' : '' }}
                                    >{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="text-align: right" class="pb-5">
                        <button type="submit" class="btn btn-success px-3 py-2">Save Audit Plan</button>
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
<script>

    $('.select2').select2();
</script>
@endsection