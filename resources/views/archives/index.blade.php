@extends('layout.sidebar')
@section('title')
<title>Archives</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Archives</h1>
        <h5 class="text-decoration-none">
            <a href="{{ route('archives-page') }}">Root</a>
            @if(!empty($parents))
                @foreach($parents as $parent) 
                    >
                    <a href="{{ route('archives-page') }}?directory={{ $parent->id }}&user={{ $current_user->id }}">{{ $parent->name }}</a>
                @endforeach
            @endif
        </h5>
    </div>
    <div class="container">
        <div style="text-align:right">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i> Search</button>
            @if(!empty($parents) || in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                <button class="btn btn-success" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> New</button>
                <ul class="dropdown-menu text-left">
                    @if(!empty($parents))
                        <li><button class="btn" data-bs-toggle="modal" data-bs-target="#fileModal">File</button></li>
                    @endif
                    <li>
                        <button class="btn toggleDirectoryModal"
                            data-route="{{ route('archives-store-directory') }}" 
                            data-bs-toggle="modal" data-bs-target="#directoryModal">
                                Directory
                        </button>
                    </li>
                </ul>
            @endif
        </div>
        @include('layout.alert')
        @if(!empty($users) && in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
            <h5>User:</h5>
            <select class="form-control userSelection">
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $current_user->id == $user->id ? 'selected' : ''}}>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                @endforeach
            </select>
        @endif
        <div class="mb-4 row">
            @foreach($directories as $directory)
                <div class="col-2 text-center">
                    <button class="btn align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false" data-route="{{ route('archives-page') }}?directory={{ $directory->id }}">
                        <img src="{{ Storage::url('assets/folder.png') }}" alt="Folder.png" class="img-fluid w-75">
                        <p class="text-dark" style="text-overflow: ellipsis"><small>{{ $directory->name ?? '' }}</small></p>
                    </button>
                    <ul class="dropdown-menu text-center">
                        <li><a href="{{ route('archives-page') }}?directory={{ $directory->id }}&user={{ $current_user->id }}" class="text-decoration-none">Open Directory</a></li>
                        <li><a href="#" class="text-decoration-none btn-property"
                            data-bs-toggle="modal" data-bs-target="#propertyModal"
                            data-name="{{ $directory->name }}"
                            data-type="Directory"
                            data-created-by="{{ $directory->user->username ?? 'Admin' }}"
                            data-created-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
                            data-updated-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
                        >Properties</a></li>
                        
                        @if($directory->user_id == Auth::user()->id || in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                        <li>
                            <a href="#" class="text-decoration-none toggleDirectoryModal"
                                data-name="{{ $directory->name }}" 
                                data-route="{{ route('archives-update-directory', $directory->id) }}" 
                                data-bs-toggle="modal" data-bs-target="#directoryModal">
                                    Rename
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-decoration-none btn-confirm" data-target="#delete_directory_{{ $directory->id }}">Delete</button>
                                <form id="delete_directory_{{ $directory->id }}" action="{{ route('archives-delete-directory', $directory->id) }}" class="d-none" method="POST">
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

        <div class="mt-3 row">
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


    <div class="modal fade" id="directoryModal" tabindex="-1" aria-labelledby="directoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="directoryModalLabel">Add Directory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('archives-store-directory') }}" id="directoryModalForm">
                    @csrf
                    <input type="hidden" value="{{ $current_directory->id ?? '' }}" name="parent_directory">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="directory" class="form-label">Name</label>
                            <input type="text" class="form-control" name="directory" id="directory" placeholder="Enter Directory Name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">Upload File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('archives-store-file') }}" enctype="multipart/form-data" id="fileModalForm">
                    @csrf
                    <input type="hidden" value="{{ $current_directory->id ?? '' }}" name="parent_directory">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file_name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="file_name" id="file_name" placeholder="Enter Filename" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_attachment" class="form-label">Attachment</label>
                            <input type="file" class="form-control" name="file_attachment" id="file_attachment" required accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('archives-search') }}" id="searchModalForm">
                    @csrf
                    <input type="hidden" value="{{ $current_search->id ?? '' }}" name="parent_search">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileSearch" class="form-label">File Name</label>
                            <input type="text" class="form-control" name="fileSearch" id="fileSearch" placeholder="Enter File Name" required>
                        </div>
                        @if(!empty($users) && in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                            <div class="mb-3">
                                <label for="search" class="form-label">User</label>
                                <select class="form-control" name="userSearch">
                                    <option value="">All Users</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
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

    $('.toggleDirectoryModal').on('click', function(){
        $('#directoryModalForm').attr('action', $(this).data('route'));
        $('#directory').val($(this).data('name'));
    });

    $('.btn-confirm').on('click', function(){
        var form = $(this).data('target');
        Swal.fire({
            title: "Are you sure you wan't to delete?",
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(form).submit();
                }
        });
    });

    $('.userSelection').on('change', function(){
        var userID = $(this).val();
        if(userID == '') {
            location.href = "{{ route('archives-page') }}";
        }else{
            location.href = "{{ route('archives-page') }}?user=" + userID;
        }
    });

    $('.btn-property').on('click', function(){
        $('#propertyName').html($(this).data('name'));
        $('#propertyType').html($(this).data('type'));
        $('#propertyCreatedBy').html($(this).data('created-by'));
        $('#propertyCreated').html($(this).data('created-at'));
        $('#propertyUpdated').html($(this).data('updated-at'));
    });
</script>
@endsection