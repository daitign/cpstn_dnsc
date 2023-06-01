@extends('layout.sidebar')
@section('title')
    <title>Survey Report</title>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>Survey Report</h1>
    </div>
    <div class="container pt-2">
        <div class="row g-3">
            <form action="{{ route('hr-survey-report') }}">
                <div class="input-group mb-3 col-6">
                    <input type="text" name="keyword" class="form-control" placeholder="Input Office..." aria-describedby="basic-addon2" value="{{ $keyword ?? '' }}">
                    <input type="date" name="date_from" class="form-control" value="{{ $date_from }}">
                    <input type="date" name="date_to" class="form-control" value="{{ $date_to }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </form>
            

            <div class="row">
                <div class="col-6">
                    <canvas id="pieChart" style="width:100%"></canvas>
                </div>
                <div class="col-6">
                    <canvas id="barChart" style="width:100%"></canvas>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 px-2">
                    <div class="card p-3">
                        <div class="card-body pt-2">
                            <h4>Survey</h4>
                            <div style="max-height:400px; overflow-y:scroll">
                                <table class="table datatables">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>Name</td>
                                            <td>Type</td>
                                            <td>Office</td>
                                            <td>Promptness of Service</td>
                                            <td>Quality of Engagement</td>
                                            <td>Cordiality of Personnel</td>
                                            <td>Comments/Suggestions</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($surveys as $survey)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $survey->name ?? ''}}</td>
                                                <td>{{ sprintf('%s: %s',$survey->type ?? '', $survey->type == 'Student' ? $survey->course .' '.$survey->course_year : $survey->occupation) }}</td>
                                                <td>{{ $survey->score->area->area_name ?? ''}}</td>
                                                <td>{{ $survey->score->promptness }}</td>
                                                <td>{{ $survey->score->engagement }}</td>
                                                <td>{{ $survey->score->cordiality }}</td>
                                                <td>{{ $survey->suggestions }}</td>
                                                <td>{{ $survey->created_at->format('M d, Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        var xValues = {!! json_encode($types->pluck('name')) !!};
        var yValues =  {!! json_encode($types->pluck('total')) !!};
        var barColors = {!! json_encode($types->pluck('color')) !!};

        new Chart("pieChart", {
        type: "pie",
        data: {
            labels: xValues,
            datasets: [{
            backgroundColor: barColors,
            data: yValues
            }]
        },
        options: {
            title: {
            display: true,
            text: "Survey Respondents \nTotal of {{ count($surveys)}}"
            }
        }
        });

        var xValues = {!! json_encode($areas->pluck('name')) !!};
        var yValues =  {!! json_encode($areas->pluck('total')) !!};
        var barColors = {!! json_encode($areas->pluck('color')) !!};

        new Chart("barChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
            backgroundColor: barColors,
            data: yValues
            }]
        },
        options: {
            legend: {display: false},
            title: {
            display: true,
            text: "Survey Office \nTotal of {{ count($surveys)}}"
            }
        }
        });
    </script>
@endsection
