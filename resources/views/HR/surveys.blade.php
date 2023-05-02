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
    </style>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>Survey List</h1>
    </div>
    <div class="container pt-2">
        <div class="row g-3">
            <form action="{{ route('hr-survey-page') }}">
                <div class="input-group mb-3 col-6">
                    <input type="text" name="keyword" class="form-control" placeholder="Input Office..." aria-describedby="basic-addon2" value="{{ $keyword ?? '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
            <div style="overflow-y: auto;height:60vh;">
            @if(count($surveys) == 0)
                <h3 class="text-center mt-4">No Survey Submitted Yet</h3>
            @endif
            @foreach ($surveys as $survey)
                <div class="card p-3">
                    <div class="row">
                        <div class="col-6">
                            <h4>{{ $survey->name ?? ''}}</h4>
                            <h6>{{ sprintf('%s: %s',$survey->type ?? '', $survey->type == 'Student' ? $survey->course_year : $survey->occupation) }}<h6>
                            <h5>Office: {{ $survey->score->office->office_name ?? ''}}<h5>
                            <h5>Ratings:<h5>
                            <h6>Promptness of Service: <input class="input-grade" type="text" value="{{ $survey->score->promptness }}" disabled><h6>
                            <h6>Quality of Engagement: <input class="input-grade" type="text" value="{{ $survey->score->engagement }}" disabled><h6>
                            <h6>Cordiality of Personnel: <input class="input-grade" type="text" value="{{ $survey->score->cordiality }}" disabled><h6>
                        </div>
                        
                        <div class="col-6">
                            <h5>Comments:</h5>
                            <textarea class="form-control" rows="7" disabled>{{ $survey->suggestions ?? ''}}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection
