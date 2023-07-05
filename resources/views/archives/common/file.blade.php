<div class="col-2 text-center">
    @if($file->type == 'audit_reports'
        && !empty($file->audit_report)
        && !empty($file->audit_report->cars))
            <a href="{{ route('archives-download-file', $file->audit_report->cars->file->id) }}" class="cars"><img src="{{ asset('media/info.png') }}" width="40px"></a>
    @endif
    <button 
        data-toggle="tooltip" title="{{ $file->directory->fullPath() ?? '' }} > {{ $file->name ?? '' }}" 
        class="btn align-items-center justify-content-center pb-0" data-bs-toggle="dropdown" 
        aria-expanded="false">
            <img src="{{ Storage::url('assets/file.png') }}" alt="file.png" class="img-fluid">
            <p class="text-whiteeee mb-0" style="text-overflow: ellipsis"><small>{{ $file->file_name ?? '' }}</small></p>
    </button>

    @if(in_array($file->type, ['evidences', 'templates', 'manuals', 'audit_reports', 'consolidated_audit_reports', 'survey_reports']))
        <button class="btn btn-remarks
            {{ !empty($file->remarks) ? 'btn-success' : 'btn-secondary' }}" data-bs-toggle="modal" data-bs-target="#remarksModal"
            data-file-id="{{ $file->id }}"
            {{ (in_array(Auth::user()->role->role_name, ['Internal Auditor', 'Internal Lead Auditor', 'Staff', 'Document Control Custodian', 'College Management Team', 'Quality Assurance Director']))
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
            && empty($file->audit_report->cars))
            <a href="#" class="text-decoration-none upload-cars" data-audit-report="{{ $file->audit_report->id ?? '' }}" data-bs-toggle="modal" data-bs-target="#consolAuditReportModal"><i class="fa fa-book"></i> Upload CARS</a>
        @endif
        <!-- @if($file->user_id == Auth::user()->id)
        <li>
            <a href="#" class="text-decoration-none btn-share" data-bs-toggle="modal" data-bs-target="#shareModal" data-users="{{ $file->shared_users }}" data-route="{{ route('archives-share-file', $file->id) }}"><i class="fa fa-share"></i> Share</button>
                <form id="unshare_file_{{ $file->id }}" action="{{ route('archives-unshare-file', $file->id) }}" class="d-none" method="POST">
                    @csrf
                </form>
            </a>
        </li>
        @endif -->
        @if($file->user_id == Auth::user()->id)
            <li>
                <a href="#" class="text-decoration-none btn-edit-file"
                    data-bs-toggle="modal" data-bs-target="#editFileModal"
                    data-route="{{ route('archives-update-file', $file->id) }}"
                    data-name="{{ $file->file_name }}"
                    data-description="{{ $file->description ?? ''}}"
                ><i class="fa fa-edit"></i> Edit</a>
            </li>
        @endif
        <li>
            <a href="#" class="text-decoration-none btn-history"
                data-file-id="{{ $file->id }}"
                data-bs-toggle="modal" data-bs-target="#historyModal"
            ><i class="fa fa-history"></i> History</a>
        </li>
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