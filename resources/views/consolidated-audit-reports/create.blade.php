@extends('layout.sidebar')
@section('title')
<title>Add Consolidated Audit Report</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Add Consolidated Audit Report</h1>
    </div>
    <div class="m-3 bg-white py-4">
        <div class="m-3">
            @include('layout.alert')
            <form method="POST" action="{{ route('lead-auditor.consolidated-audit-reports.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label><span class="text-danger"> *</span>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Consolidated Audit Report Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label">Date</label><span class="text-danger"> *</span>
                        <input type="date" id="date" class="form-control" name="date" max="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="search" class="form-label">Description</label><span class="text-danger"> *</span>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="audit_plan" class="form-label">Audit Plan</label><span class="text-danger"> *</span>
                        <select id="audit_plan" name="audit_plan" class="form-control" required>
                            <option value="">Select Audit Plan</option>
                            @foreach($audit_plans as $audit_plan)
                            <option value="{{ $audit_plan->id }}">{{ $audit_plan->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="file_attachments" class="form-label">Attachment</label><span class="text-danger"> *</span>
                        <input type="file" class="form-control" name="file_attachments[]" id="file_attachments" multiple required accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-end mb-3">
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
    
@endsection

@section('js')
<script>
    $("#date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "{{ date('Y-m-d') }}"
    });
</script>
@endsection