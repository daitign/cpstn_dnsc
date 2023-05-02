@extends('layout.sidebar')
@section('title')
<title>Archives</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Archives File Search</h1>
    </div>
    <div class="container">
        @include('layout.alert')
        <form method="POST" action="{{ route('archives-search') }}" id="searchModalForm">
            @csrf
            <div class="row mt-3">
                <div class="mb-3 col-6">
                    <label for="fileSearch" class="form-label">File Name</label>
                    <input type="text" class="form-control" name="fileSearch" id="fileSearch" value="{{ $fileSearch ?? '' }}" placeholder="Enter File Name" required>
                </div>
                @if(!empty($users) && in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                    <div class="mb-3 col-6">    
                        <label for="fileSearch" class="form-label">User</label>
                        <select class="form-control userSelection">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $current_user == $user->id ? 'selected' : ''}}>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
            <a href="{{ route('archives-page') }}" class="btn btn-warning"><i class="fa fa-clear"></i> Clear</a>
        </form>
       
        <div class="mt-3 row">
            @if(empty($files))
                <h4>Result: No Result Found on keyword <strong>{{ $fileSearch ?? '' }}</strong></h4>
            @else
                <h4>Result: Found {{ count($files) }} on keyword <strong>{{ $fileSearch ?? '' }}</strong></h4>
            @endif
            @foreach($files as $file)
                <div class="col-2 text-center">
                    <button class="btn align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false" data-route="{{ route('archives-page') }}?directory={{ $file->id }}">
                        <img src="{{ Storage::url('assets/file.png') }}" alt="file.png" class="img-fluid w-75">
                        <p class="text-dark" style="text-overflow: ellipsis"><small>{{ $file->file_name ?? '' }}</small></p>
                    </button>
                    <ul class="dropdown-menu text-center">
                        <li><a href="{{ route('archives-download-file', $file->id) }}" class="text-decoration-none">Download</a></li>
                        <li>
                            <a href="#" class="text-decoration-none btn-property"
                                data-bs-toggle="modal" data-bs-target="#propertyModal"
                                data-name="{{ $file->file_name }}"
                                data-type="{{ $file->file_mime }}"
                                data-created-by="{{ $file->user->username }}"
                                data-created-at="{{ $file->created_at->format('M d, Y h:i A') }}"
                                data-updated-at="{{ $file->created_at->format('M d, Y h:i A') }}"
                            >Properties</a>
                        </li>
                        @if($file->user_id == Auth::user()->id || in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                        <li>
                            <a href="#" class="text-decoration-none btn-confirm" data-target="#delete_file_{{ $file->id }}">Delete</button>
                                <form id="delete_file_{{ $file->id }}" action="{{ route('archives-delete-file', $file->id) }}" class="d-none" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Properties</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr><td><strong>Name:</strong><td><td id="propertyName"></td></tr>
                            <tr><td><strong>Type:</strong><td><td id="propertyType"></td></tr>
                            <tr><td><strong>Created By:</strong><td><td id="propertyCreatedBy"></td></tr>
                            <tr><td><strong>Created:</strong><td><td id="propertyCreated"></td></tr>
                            <tr><td><strong>Updated:</strong><td><td id="propertyUpdated"></td></tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $('.btn-property').on('click', function(){
        $('#propertyName').html($(this).data('name'));
        $('#propertyType').html($(this).data('type'));
        $('#propertyCreatedBy').html($(this).data('created-by'));
        $('#propertyCreated').html($(this).data('created-at'));
        $('#propertyUpdated').html($(this).data('updated-at'));
    });
</script>
@endsection