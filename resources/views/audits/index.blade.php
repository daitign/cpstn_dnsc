@extends('layout.sidebar')
@section('title')
<title>Audit Plans</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Audit Plans</h1>
    </div>
    <div class="container">
        <div class="mt-2" style="text-align:right">
            <a href="{{ route('lead-auditor.audit.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Start New Audit Plan</a>
            <!-- <a href="#" class="btn btn-warning"><i class="fa fa-edit"></i> Update Previous Audit Plan</a> -->
        </div>
        @include('layout.alert')
        <div class="mb-4 row">
            <div class="row col-8">
                @foreach($audit_plans as $plan)
                    <div class="col-2 text-center">
                        <a href="{{ route('lead-auditor.audit.edit', $plan->id) }}" class="btn align-items-center justify-content-center btn-directory">
                            <img src="{{ Storage::url('assets/folder.png') }}" alt="Folder.png" class="img-fluid">
                            <p class="text-dark" style="text-overflow: ellipsis"><small>{{ $plan->area->parent->area_name.' > '.$plan->area->area_name ?? '' }}</small></p>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="col-4 mt-2 alert alert-success">
                <h3 class="mb-2">Internal Auditors</h3>
                @foreach($auditors as $user)
                    <h4 class="mb-0">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h4>
                    <p class="mb-2 mt-0"><small>Assigned on: {{ sprintf("%s > %s", $user->assigned_area->parent->area_name ?? '', $user->assigned_area->area_name ?? 'None') }}</small></p>
                @endforeach
            </div>
        </div>
@endsection