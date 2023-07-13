@extends('layout.sidebar')
@section('title')
<title>View Audit Plan</title>
@endsection

@section('page')
    <div class="2">
        <h2>View Audit Plan</h2>
    </div>
    <div class="container">
        <div class="row mt-3 px-2 pb-3">
            @include('layout.alert')
            <div class="col-12">
                <button class="btn btn-danger btn-confirm px-2" style="float:right" data-message="Are you sure you wan't to delete this audit plan?" data-target="#delete_audit_plan"><i class="fa fa-trash"></i>  Delete Audit Plan</button>
                    <form id="delete_audit_plan" action="{{ route('lead-auditor.audit.delete', $audit_plan->id) }}" class="d-none" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>
                </button>
            </div>
            <div class="col-12">
                <!-- <form method="POST" action="{{ route('lead-auditor.audit.update', $audit_plan->id) }}">
                    @csrf -->
                    <div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Name</label>
                            <input type="text" value="{{ $audit_plan->name ?? '' }}" class="form-control" id="name" name="name" placeholder="Enter name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter description" readonly>{{ $audit_plan->description ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Date</label>
                            <input type="date" value="{{ $audit_plan->date ?? '' }}" class="form-control" id="date" name="date" placeholder="Enter date" readonly>
                        </div>
                        <div class="mt-2">
                            <h3>Process and Auditors</h3>
                            <table class="table text-white table-process">
                                <thead><tr><td>Process</td><td>Auditors</td></tr></thead>
                                <tbody>
                                    @foreach($audit_plan->plan_areas as $plan_area)
                                        <tr>
                                            <td>{{ $plan_area->area->getAreaFullName() }}{{ $plan_area->area->area_name ?? '' }}</td>
                                            <td>
                                                @foreach($plan_area->users as $user)
                                                    {{ $user->firstname ?? '' }} {{ $user->surname ?? ''}}
                                                    @if($loop->index < count($plan_area->users) - 1)
                                                    , 
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="col-12 mt-2">
                <h3 class="mb-2">Checklist</h3>
                <table class="table text-white table-process">
                    <thead><tr><td>Auditor</td><td>Process</td><td>File Type</td><td>Submitted</td><td>Time Submitted</td><td>Action</td></tr></thead>
                    <tbody>
                        @foreach($auditors as $user)
                            @foreach($user->audit_plan_area_user as $area_user)
                                <tr>
                                    <td>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</td>
                                    <td>{{ sprintf("%s > %s", $area_user->audit_plan_area->area->parent->area_name ?? '', $area_user->audit_plan_area->area->area_name ?? 'None') }}</td>
                                    <td>
                                        {{ !empty($area_user->audit_report) && $area_user->audit_report->file->created_at != $area_user->audit_report->file->updated_at ? 'Revision AR' : 'Audit Report' }}
                                    </td>
                                    <td>{{ !empty($area_user->audit_report) ? 'YES' : 'Not Yet'}}</td>
                                    <td>{{ !empty($area_user->audit_report) ? $area_user->audit_report->updated_at->format('F d, Y h:i A') : '' }}</td>
                                    <td>
                                        @if(!empty($area_user->audit_report))
                                            <a href="{{ route('archives-show-file', $area_user->audit_report->file_id) }}" class="text-warning" target="_blank"><i class="fa fa-eye"> View</i></a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</td>
                                    <td>{{ sprintf("%s > %s", $area_user->audit_plan_area->area->parent->area_name ?? '', $area_user->audit_plan_area->area->area_name ?? 'None') }}</td>
                                    <td>CARS</td>
                                    <td>{{ !empty($area_user->cars) ? 'YES' : 'Not Yet'}}</td>
                                    <td> {{ !empty($area_user->cars) ? $area_user->cars->created_at->format('F d, Y h:i A') : '' }}</td>
                                    <td>
                                        @if(!empty($area_user->cars))
                                            <a href="{{ route('archives-show-file', $area_user->cars->file_id) }}" class="text-warning" target="_blank"><i class="fa fa-eye"> View</i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                <table>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection