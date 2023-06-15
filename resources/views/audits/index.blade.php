@extends('layout.sidebar')
@section('title')

@php
    $title = auth()->user()->role->role_name == 'Internal Lead Auditor' ? 'Audit Plans' : 'Audit Evidence';
@endphp
<title>{{ $title }}</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>{{ $title }}</h1>
    </div>
    <div class="container pt-2">
        @if(auth()->user()->role->role_name == 'Internal Lead Auditor')
        <div style="text-align:right">
            <a href="{{ route('lead-auditor.audit.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Start New Audit Plan</a>
            <a href="{{ route('lead-auditor.audit.previous') }}" class="btn btn-warning"><i class="fa fa-edit"></i> Use Previous Audit Plan</a>
        </div>
        @endif
        @include('layout.alert')
        <div class="mb-4 row">
            <div class="row {{ auth()->user()->role->role_name == 'Internal Lead Auditor' ? 'col-8' : 'col-12' }}">
                @foreach($audit_plans as $plan)
                    <div class="col-3">
                        <a href="{{ route(auth()->user()->role->role_name == 'Internal Lead Auditor' ? 'lead-auditor.audit.edit' : 'auditor.audit.evidence.show', $plan->id) }}">
                            <div class="card bg-success text-white">
                                <div class="card-body ">
                                    <div class="block-content block-content-full">
                                        <div class="d-flex justify-content-center">
                                            <div class="text-center">
                                                <h5 class="fs-md fw-semibold mt-3 text-uppercase">{{ $plan->name ?? '' }}</h5>
                                                <p>{{ $plan->description ?? '' }}</p>
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

            @if(auth()->user()->role->role_name == 'Internal Lead Auditor')
            <div class="col-4 mt-2 alert alert-success">
                <h3 class="mb-2">Internal Auditors</h3>
                @foreach($auditors as $user)
                    <h4 class="mb-0">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h4>
                    <p class="mb-2 mt-0"><small>Assigned on: <br/>{!! implode("<br/>", $user->getAssignedAreas()) !!}</small></p>
                @endforeach
            </div>
            @endif
        </div>
@endsection