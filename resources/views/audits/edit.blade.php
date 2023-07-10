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
            <div class="col-8">
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
@endsection