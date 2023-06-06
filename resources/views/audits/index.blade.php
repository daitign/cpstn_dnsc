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
                    <div class="col-3">
                        <a href="{{ route('lead-auditor.audit.edit', $plan->id) }}">
                            <div class="card bg-success text-white">
                                <div class="card-body ">
                                    <div class="block-content block-content-full ratio ratio-16x9">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <div class="fs-md fw-semibold mt-3 text-uppercase">{{ $plan->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">{{ $plan->date ? \Carbon\Carbon::parse($plan->date)->format('F d, Y') : ''}}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="col-4 mt-2 alert alert-success">
                <h3 class="mb-2">Internal Auditors</h3>
                @foreach($auditors as $user)
                    <h4 class="mb-0">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h4>
                    <p class="mb-2 mt-0"><small>Assigned on: <br/>{!! implode("<br/>", $user->getAssignedAreas()) !!}</small></p>
                @endforeach
            </div>
        </div>
@endsection