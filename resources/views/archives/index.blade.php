@extends('layout.sidebar')
@section('title')
<title>{{ $page_title ?? 'Archives' }}</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>{{ $page_title ?? 'Archives' }}</h1>
        <h5 class="text-decoration-none">
            @if(empty($page_title))
                <a href="{{ route('archives-page') }}">Archives</a> >
            @endif
            @if(!empty($parents))
                @foreach($parents as $parent) 
                    {{ $parent->name }}
                    @if(!$loop->last) > @endif
                @endforeach
            @endif
        </h5>
    </div>
    <div class="container">
        <div style="text-align:right">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fa fa-search"></i> Search</button>
            @if(Auth::user()->role->role_name == 'Document Control Custodian' && !empty($current_directory->area) && $current_directory->area->type == 'process')
                <button class="btn btn-success" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-plus"></i> New</button>
                <ul class="dropdown-menu text-left">
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
                <option value="">All Users</option>
                @php $roles = $users->groupBy('role.role_name'); @endphp
                @foreach($roles as $role => $users)
                    <optgroup label="{{ $role }}">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $current_user->id == $user->id ? 'selected' : ''}}>{{ sprintf("%s %s", $user->firstname ?? '', $user->surname ?? '') }}</option>
                    @endforeach
                    </optgroup>
                @endforeach
            </select>
        @endif
        <div class="mb-4 row">
            @foreach($directories as $directory)
                <div class="col-2 text-center">
                    <button class="btn align-items-center justify-content-center btn-directory" data-bs-toggle="dropdown" aria-expanded="false" data-route="{{ route($route ?? 'archives-page') }}?directory={{ $directory->id }}&user={{ $current_user->id }}">
                        <img src="{{ Storage::url('assets/folder.png') }}" alt="Folder.png" class="img-fluid">
                        <p class="text-dark" style="text-overflow: ellipsis"><small>
                            @if(in_array($current_user->role->role_name, ['Process Owner', 'Internal Auditor']))
                                {{ sprintf('%s%s%s', !empty($directory->parent->parent->name) ? $directory->parent->parent->name.' > ' : '', !empty($directory->parent->name) ? $directory->parent->name.' > ' : '', $directory->name ?? '') }}
                            @else
                                {{ $directory->name ?? '' }}
                            @endif
                        </small></p>
                    </button>
                    <ul class="dropdown-menu text-center">
                        <li><a href="{{ route($route ?? 'archives-page') }}?directory={{ $directory->id }}&user={{ $current_user->id }}" class="text-decoration-none">Open Directory</a></li>
                        <li><a href="#" class="text-decoration-none btn-property"
                            data-bs-toggle="modal" data-bs-target="#propertyModal"
                            data-name="{{ $directory->name }}"
                            data-type="Directory"
                            data-created-by="{{ $directory->user->username ?? 'Admin' }}"
                            data-created-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
                            data-updated-at="{{ $directory->created_at ? $directory->created_at->format('M d, Y h:i A') : '' }}"
                        >Properties</a></li>
                        
                        @if(Auth::user()->role->role_name == 'Document Control Custodian' && !empty($current_directory->area) && $current_directory->area->type == 'process')
                        <li>
                            <a href="#" class="text-decoration-none toggleDirectoryModal"
                                data-name="{{ $directory->name }}" 
                                data-route="{{ route('archives-update-directory', $directory->id) }}" 
                                data-bs-toggle="modal" data-bs-target="#directoryModal">
                                    Rename
                            </a>
                        </li>
                        <!-- <li>
                            <a href="#" class="text-decoration-none btn-confirm" data-target="#delete_directory_{{ $directory->id }}">Delete</button>
                                <form id="delete_directory_{{ $directory->id }}" action="{{ route('archives-delete-directory', $directory->id) }}" class="d-none" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </a>
                        </li> -->
                        @endif
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-3 row">
            @foreach($files as $file)
                <div class="col-2 text-center">
                    @if($file->type == 'audit_reports'
                        && !empty($file->audit_report)
                        && !empty($file->audit_report->consolidated_report))
                            <a href="{{ route('archives-download-file', $file->audit_report->consolidated_report->file->id) }}" style="float:right"><img src="{{ asset('media/info.png') }}" width="40px"></a>
                    @endif
                    <button class="btn align-items-center justify-content-center pb-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Storage::url('assets/file.png') }}" alt="file.png" class="img-fluid">
                        <p class="text-dark mb-0" style="text-overflow: ellipsis"><small>{{ $file->file_name ?? '' }}</small></p>
                    </button>

                        @if(in_array($file->type, ['evidences', 'templates', 'manuals', 'audit_reports']))
                            <button class="btn btn-remarks
                                {{ !empty($file->remarks) ? 'btn-success' : 'btn-secondary' }}" data-bs-toggle="modal" data-bs-target="#remarksModal"
                                data-file-id="{{ $file->id }}"
                                {{ (in_array(Auth::user()->role->role_name, ['Internal Auditor', 'Internal Lead Auditor', 'Staff', 'Document Control Custodian']))
                                ? 'data-route='.route('save-remarks', $file->id) : '' }}>
                                        <i class="fa fa-email"></i> Remarks
                            </button>
                        @endif
                    <ul class="dropdown-menu text-left px-3">
                        <li><a href="{{ route('archives-download-file', $file->id) }}" target="_blank" class="text-decoration-none"><i class="fa fa-download"></i> Download</a></li>
                        <li>
                            <a href="#" class="text-decoration-none btn-property"
                                data-bs-toggle="modal" data-bs-target="#propertyModal"
                                data-name="{{ $file->file_name }}"
                                data-type="{{ $file->file_mime }}"
                                data-created-by="{{ $file->user->username }}"
                                data-created-at="{{ $file->created_at->format('M d, Y h:i A') }}"
                                data-updated-at="{{ $file->created_at->format('M d, Y h:i A') }}"
                                data-description="{{ $file->description ?? ''}}"
                            ><i class="fa fa-cog"></i> Properties</a>
                        </li>
                        @if(!empty($file->trackings()))
                        <li>
                            <a href="#" class="text-decoration-none btn-tracking"
                            data-bs-toggle="modal" data-bs-target="#trackingModal"
                            ><i class="fa fa-search"></i> Track</a>
                            <div class="d-none file-tracking-info">
                                <div class="tracking-container">
                                    @foreach($file->trackings() as $track)
                                        <div class="tracking-item">
                                            <span><strong>{{ $track['name'] ?? '' }}</strong></span><br/>
                                            <div class="pt-2 item-box text-white {{ $track['color'] ?? 'bg-secondary' }}">
                                                <i class="fa fa-user"></i>
                                            </div>
                                           <small>&nbsp;{{ !empty($track['user']) ? "By: ". $track['user'] : ' ' }}</small><br/>
                                            <small>&nbsp;{{ !empty($track['date']) ? "Date: ".$track['date'] : ' ' }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                        @endif
                        @if(Auth::user()->role->role_name == 'Internal Auditor' 
                            && $file->user_id == Auth::user()->id
                            && $file->type == 'audit_reports'
                            && !empty($file->audit_report)
                            && empty($file->audit_report->consolidated_report))
                            <a href="#" class="text-decoration-none upload-consolidated-report" data-audit-report="{{ $file->audit_report->id ?? '' }}" data-bs-toggle="modal" data-bs-target="#consolAuditReportModal"><i class="fa fa-book"></i> Consolidated Report</a>
                        @endif
                        @if($file->user_id == Auth::user()->id)
                        <li>
                            <a href="#" class="text-decoration-none btn-share" data-bs-toggle="modal" data-bs-target="#shareModal" data-users="{{ $file->shared_users }}" data-route="{{ route('archives-share-file', $file->id) }}"><i class="fa fa-share"></i> Share</button>
                                <form id="unshare_file_{{ $file->id }}" action="{{ route('archives-unshare-file', $file->id) }}" class="d-none" method="POST">
                                    @csrf
                                </form>
                            </a>
                        </li>
                        @endif
                        @if($file->user_id == Auth::user()->id || in_array(Auth::user()->role->role_name, Config::get('app.manage_archive')))
                        <!-- <li>
                            <a href="#" class="text-decoration-none btn-confirm" data-target="#delete_file_{{ $file->id }}"><i class="fa fa-trash"></i>Delete</button>
                                <form id="delete_file_{{ $file->id }}" action="{{ route('archives-delete-file', $file->id) }}" class="d-none" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </a>
                        </li> -->
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
                                        <option value="{{ $user->id }}">{{ sprintf("%s %s - %s", $user->firstname ?? '', $user->surname ?? '', $user->role->role_name ?? '') }}</option>
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
                            <tr><td><strong>Name:</strong></td><td id="propertyName"></td></tr>
                            <tr><td><strong>Type:</strong></td><td id="propertyType"></td></tr>
                            <tr><td><strong>Created By:</strong></td><td id="propertyCreatedBy"></td></tr>
                            <tr><td><strong>Created:</strong></td><td id="propertyCreated"></td></tr>
                            <tr><td><strong>Updated:</strong></td><td id="propertyUpdated"></td></tr>
                            <tr><td colspan="2"><strong>Description:</strong></td></tr>
                            <tr>
                                <td colspan="2">
                                    <textarea class="form-control" row="5" readonly id="propertyDescription"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tracking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Share File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="#" id="shareModalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="search" class="form-label">Share File With:</label>
                            <select class="form-control" name="userShare[]" id="userShare" multiple>
                                @foreach($users as $user)
                                    @if($user->id !== $current_user->id)
                                        <option value="{{ $user->id }}">{{ sprintf("%s %s - %s", $user->firstname ?? '', $user->surname ?? '', $user->role->role_name ?? '') }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fa fa-share"></i> Share</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="remarksForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="remarksDetailForm">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Choose Remarks:</label><br/>
                                    <input type="radio" class="btn-check" name="type" id="remarks-success" value="success" autocomplete="off" checked>
                                    <label class="btn btn-outline-success p-2 px-4" for="remarks-success"></label>

                                    <input type="radio" class="btn-check" name="type" id="remarks-danger" value="danger" autocomplete="off">
                                    <label class="btn btn-outline-danger p-2 px-4" for="remarks-danger"></label>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="comments">Comments:</label>
                                    <textarea class="form-control" rows="3" name="comments" id="remarks-comments"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="comments">Recent Remarks:</label>
                                <table class="table recent-remarks-table"></table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-submit-remarks">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="consolAuditReportModal" tabindex="-1" aria-labelledby="consolAuditReportModalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consolAuditReportModalModalLabel">Upload Consolidated Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('auditor.consolidated-audit-reports.store') }}" enctype="multipart/form-data" id="fileModalForm">
                    @csrf
                    <input type="hidden" value="" id="audit_report_id" name="audit_report_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Filename" required>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date:</label>
                            <input type="date" id="date" class="form-control" name="date" max="{{ date('Y-m-d') }}"/>
                        </div>
                        <div class="mb-3">
                            <label for="file_attachment" class="form-label">Attachment</label>
                            <input type="file" class="form-control" name="file_attachment" id="file_attachment" required accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                        </div>
                        <div class="mb-3">
                            <label for="search" class="form-label">Description:</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
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
@endsection

@section('js')
<script>
    var files = {!! json_encode($files) !!};
    var user_id = parseInt("{{ Auth::user()->id }}");

    var userShare = $('#userShare').select2({
        dropdownParent: $('#shareModal'),
        width: '100%'
    });

    $('.toggleDirectoryModal').on('click', function(){
        $('#directoryModalForm').attr('action', $(this).data('route'));
        $('#directory').val($(this).data('name'));
    });

    $('.btn-confirm').on('click', function(){
        var form = $(this).data('target');
        var message = $(this).data('message') ?? "Are you sure you wan't to delete?";
        Swal.fire({
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
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
        $('#propertyDescription').html($(this).data('description'));
    });

    $('.btn-tracking').on('click', function(){
       $('#trackingModal').find('.modal-body').html($(this).parents('li').find('.file-tracking-info').html());
    });

    $('.btn-share').on('click', function(){
        var users = "" + $(this).data('users') + "";
        $('#shareModalForm').prop('action', $(this).data('route'));
        $("#userShare option:selected").removeAttr("selected");

        if(users != '') {
            users = users.includes(', ') ? users.split(', ') : [users];
            
            userShare.val(users).trigger('change');
        }
    });

    $('.btn-directory').on('dblclick', function(){
        location.href = $(this).data('route')
    });

    $('.btn-remarks').on('click', function(){
        var file_id = parseInt($(this).data('file-id'));
        
        $('.btn-submit-remarks').hide();
        $('.remarksDetailForm').hide();

        if( $(this).data('route')) {
            $('#remarksForm').prop('action', $(this).data('route'));
            $('.btn-submit-remarks').show();
            $('.remarksDetailForm').show();
        }
        
        
        var file = files.find(item => 
            item.id === file_id
        );

        $('.recent-remarks-table').html('');
        if(file.remarks.length > 0) {
            var remark = file.remarks.find(item => item.user_id === user_id);
            if(remark) {
                $('#remarks-' + remark.type).prop('checked', true);
                $('#remarks-comments').html(remark.comments);
            }
            file.remarks.forEach(function(i){
                $('.recent-remarks-table').append(`
                    <tr>
                        <td class="text-center">
                            <i class="fa fa-user text-` + i.type + ` fa-2x"></i><br/>
                            <small class="badge bg-secondary" data-bs-toggle="tooltip" title="` + i.created_at_formatted + `">` + i.created_at_for_humans + `</small>
                        </td>
                        <td><strong class="px-0">` + i.user.firstname + ` ` + i.user.surname + `</strong><br/>` +
                            `(` + i.user.role.role_name + `)<br/>` + 
                            i.comments + `
                        </td>
                    </tr>
                `);
            });
        }
        
    });

    $("#date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "{{ date('Y-m-d') }}"
    });

    $('.upload-consolidated-report').on('click', function(){
        $('#audit_report_id').val($(this).data('audit-report'));
    });
</script>
@endsection