@extends('layout.sidebar')
@section('title')
    <title>All Surveys</title>
@endsection
@section('css-page')
    <style>
        .btn-design {
            border: 1px solid #000000 !important;
            font-size: 1em !important;
        }

        .btn-design:hover{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .row .col-4 .active{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .row .col-8 .active{
            color: #ffffff !important;
            background-color: #005b40 !important;
        }

        .maxed{
            min-height: 16rem;
            max-height: 16rem;
        }
        .input-grade {
            width: 30px;
            text-align: center;
        }
        .card-container {
        overflow-y: auto;
        height: 60vh;
    }

    .card-container::-webkit-scrollbar {
        width: 8px;
    }

    .card-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .card-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .card-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h2>Survey List</h2>
    </div>
    <form action="{{ route(auth()->user()->role->role_name == 'Human Resources' ? 'hr-survey-page' : 'admin-surveys-list') }}">
        @csrf
        <div class="input-group mb-3 col-6">
            <input type="text" name="keyword" class="form-control" placeholder="Input Office..." aria-describedby="basic-addon2" value="{{ $keyword ?? '' }}">
            <input type="date" name="date_from" class="form-control">
            <input type="date" name="date_to" class="form-control">
            <div class="input-group-append">
                <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
            </div>
        </div>
    </form> 
    <div class="m-3">
        <div class="row g-3">
            <div style="overflow-y: auto;height:60vh;">
                @if(count($surveys) == 0)
                    <h3 class="text-center mt-4">No Survey Submitted Yet</h3>
                @endif
                <div class="card-container">
                    @foreach ($surveys as $survey)
                    <div class="card p-3 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-user text-success"></i> : <small>{{ $survey->name ?? ''}}</small></h6>
                                <h6><i class="fas fa-address-card text-success"></i> : <small>{{ sprintf('%s: %s',$survey->type ?? '', $survey->type == 'Student' ? $survey->course .' '.$survey->course_year : $survey->occupation) }}</small></h6>
                                <h6><i class="fas fa-envelope text-success"></i> : <small>{{ $survey->email ?? ''}}</small></h6>
                                <h6><i class="fas fa-phone text-success"></i> : {{ $survey->contact_number ?? ''}}</h6>
                                <h6><i class="fas fa-building text-success"></i> : <small>{{ $survey->facility->name ?? ''}}</small></h6>
                                <hr>
                                <h6 class="text-success">RATINGS ⭐</h6>
                                
                                <h6><small>&nbsp&nbsp&nbsp&nbsp&nbspPromptness of Service: &nbsp&nbsp<input class="input-grade round-circle text-success" type="text" value="{{ $survey->promptness }}" disabled style="border-radius: 100%"></small></h6>
                                <h6><small>&nbsp&nbsp&nbsp&nbsp&nbspQuality of Engagement: &nbsp<input class="input-grade text-success" type="text" value="{{ $survey->engagement }}" disabled style="border-radius: 100%"></small></h6>
                                <h6><small>&nbsp&nbsp&nbsp&nbsp&nbspCordiality of Personnel: &nbsp&nbsp<input class="input-grade text-success" type="text" value="{{ $survey->cordiality }}" disabled style="border-radius: 100%"></small></h6>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="text-success">Comments:</h5>
                                <textarea class="form-control" rows="7" disabled>{{ $survey->suggestions ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
