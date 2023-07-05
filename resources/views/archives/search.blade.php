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
                <div class="mb-3 col-8">
                    <label for="keyword" class="form-label">File or Directory Name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control py-2" name="keyword" id="keyword" value="{{ $keyword ?? '' }}" placeholder="Enter File Name" required>
                        <div class="input-group-append">&nbsp;                         
                            <button type="submit" class="btn btn-success px-4 py-2"><i class="fa fa-search"></i> Search</button>
                            <a href="{{ route('archives-page') }}" class="btn btn-warning px-4 py-2"><i class="fa fa-refresh"></i> Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
       

        @if(count($directories) == 0 && count($files) == 0)
            <h4>Result: No Result Found on keyword <strong>{{ $keyword ?? '' }}</strong></h4>
        @endif

        @if(count($directories) > 0)
            <div class="mt-4 mb-4 row">
                <h4>Directory Result: Found {{ count($directories) }} on keyword <strong>{{ $keyword ?? '' }}</strong></h4>
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
    @include('archives.common.js')
@endsection