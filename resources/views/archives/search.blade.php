@extends('layout.sidebar')
@section('title')
<title>Search {{ ucwords($page_title) }}</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Search {{ ucwords($page_title) }}</h1>
    </div>
    <div class="container">
        @include('layout.alert')
        <form method="GET" action="{{ route('search', $page_title) }}" id="searchModalForm">
            <div class="row mt-3">
                <div class="mb-3 col-8 row">
                    <div class="col-12 mb-3">
                        <label for="keyword" class="form-label">File Name</label>
                        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Enter File Name" value="{{ $keyword ?? '' }}" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="keyword" class="form-label">Date From</label>
                        <input type="date" name="date_from" class="date-from form-control" value="{{ $date_from ?? ''}}">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="keyword" class="form-label">Date To</label>
                        <input type="date" name="date_to" class="date-to form-control" value="{{ $date_to ?? ''}}">
                    </div>      
                    <div class="col-12 mb-3">                  
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa fa-search"></i> Search</button>
                        <a href="{{ route('archives-page') }}" class="btn btn-warning px-4 py-2"><i class="fa fa-refresh"></i> Clear</a>
                    </div>
                </div>
            </div>
        </form>
       

        @if(count($directories) == 0 && count($files) == 0)
            <h4>Result: No Result Found on keyword <strong>{{ $keyword ?? '' }}</strong></h4>
        @endif
        
        @if(count($directories) > 0)
            <div class="mt-4 mb-4 row">
                <h4>Folder Result: Found {{ count($directories) }} on keyword <strong>{{ $keyword ?? '' }}</strong></h4>
                @foreach($directories as $directory)
                   @include('archives.common.directory')
                @endforeach
            </div>
        @endif
       

        @if(count($files) > 0)
        <div class="mt-3 row">
            <h4>File Result: Found {{ count($files) }} on keyword <strong>{{ $keyword ?? '' }}</strong></h4>
            @foreach($files as $file)
               @include('archives.common.file')
            @endforeach
        </div>
        @endif
    </div>
    
    @include('archives.common.modals')
@endsection

@section('js')
    <script>
        $('.date-from').flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ $date_from ?? Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}",
            maxDate: "{{ date('Y-m-d') }}"
        });

        $('.date-to').flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ $date_to ?? 'today' }}",
            maxDate: "{{ date('Y-m-d') }}"
        });
    </script>
    @include('archives.common.js')
@endsection