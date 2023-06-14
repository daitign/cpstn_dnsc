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
                            <label for="process" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ $audit_plan->name ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Process</label>
                            <input type="text" class="form-control" value="{{ sprintf('%s%s', !empty($audit_plan->area->parent->area_name) ? $audit_plan->area->parent->area_name.' > ' : '' , $audit_plan->area->area_name ?? '') }}" readonly>
                            <input type="hidden" name="area" id="area" value="{{ $audit_plan->area_id }}">
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Description</label>
                            <textarea class="form-control" row="3" id="description" name="description" placeholder="Enter description">{{ $audit_plan->name ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Enter date" value="{{ $audit_plan->date ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Auditors</label>
                            <select class="form-control select2" name="auditors[]" multiple required data-placeholder="Choose Auditors">
                                @foreach($auditors as $user)
                                    <option value="{{ $user->id }}"
                                    {{ in_array($audit_plan->area_id, $user->assigned_areas->pluck('id')->toArray()) ? 'selected' : '' }}
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
                    <p class="mb-2 mt-0"><small>Assigned on: <br/>{!! implode("<br/>", $user->getAssignedAreas()) !!}</small></p>
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