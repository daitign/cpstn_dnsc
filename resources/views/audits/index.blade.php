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
    <div class=" row m-3">
        <div class="text-end m-3">
          <small><a href="{{ route('lead-auditor.audit.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> New Plan</a></small>
          @if(!empty($audit_plans) && count($audit_plans) > 0)
          <small><a href="{{ route('lead-auditor.audit.previous') }}" class="btn btn-warning "><i class="fa fa-edit"></i> Previous Plan</a></small>
          @endif
        </div>
    </div>
    <div class="m-3 bg-white py-2">
        @if(auth()->user()->role->role_name == 'Internal Lead Auditor')
        
          
        @endif
        @include('layout.alert')
        <div class="mb-4 row m-3">
            <div class="row {{ auth()->user()->role->role_name == 'Internal Lead Auditor' ? 'col-lg-8' : 'col-lg-12' }}">
                @foreach($audit_plans as $plan)
                  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <a href="{{ route(auth()->user()->role->role_name == 'Internal Lead Auditor' ? 'lead-auditor.audit.edit' : 'auditor.audit.evidence.show', $plan->id) }}" class="text-decoration-none">
                      <div class="card bg-success text-white">
                        <div class="card-body">
                          <div class="block-content block-content-full d-flex justify-content-center">
                            <i class="fas fa-book-open fa-4x text-warning"></i>
                          </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center">{{ $plan->name ?? '' }}</div>
                      </div>
                    </a>
                  </div>
                @endforeach
              </div>

                @if(auth()->user()->role->role_name == 'Internal Lead Auditor')
                <div class="col-4 mt-2 alert alert-success">
                    <h6 class="mb-2 text-center">INTERNAL AUDITORS</h6><hr>
                    @foreach($auditors as $user)
                        <h6 class="mb-0">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</h6>
                        <p class="mb-2 mt-0"><small>Assigned on: <br/>{!! implode("<br/>", $user->getAssignedAreas()) !!}</small></p>
                    @endforeach
                </div>
                @endif
        </div>
@endsection