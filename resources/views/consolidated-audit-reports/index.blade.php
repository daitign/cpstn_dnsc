@extends('layout.sidebar')
@section('title')
<title>Consolidated Audit Reports</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Consolidated Audit Reports</h1>
    </div>
    <div class="container">
        @include('layout.alert')
        <div class="mb-4 row">
            <div class="row mt-4 col-12">
                @foreach($consolidated_audit_reports as $report)
                    <div class="col-2 text-center">
                        <div class="btn align-items-center justify-content-center btn-directory" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Storage::url('assets/file.png') }}" alt="Folder.png" class="img-fluid">
                            <p class="text-whitee" style="text-overflow: ellipsis"><small>{{ $report->name ?? '' }}</small></p>
                            
                            <a href="#" class="btn btn-success btn-confirm" data-message="Are you sure you want to approve?" data-target="#approve_report_{{ $report->id }}">Approve</button>
                                <form id="approve_report_{{ $report->id }}" action="{{ route(auth()->user()->role->role_name == 'College Management Team' ? 'cmt.consolidated-audit-reports.approve' : 'admin-consolidated-audit-reports.approve', $report->id) }}" class="d-none" method="POST">@csrf</form>
                            </a>
                            <a href="#" class="btn btn-warning btn-confirm" data-message="Are you sure you want to reject?" data-target="#approve_report_{{ $report->id }}">Reject</button>
                                <form id="approve_report_{{ $report->id }}" action="{{ route(auth()->user()->role->role_name == 'College Management Team' ? 'cmt.consolidated-audit-reports.approve' : 'admin-consolidated-audit-reports.reject', $report->id) }}" class="d-none" method="POST">@csrf</form>
                            </a>
                        </div>
                        <ul class="dropdown-menu text-center">
                            <li><a href="{{ route('archives-download-file', $report->file_id) }}" target="_blank" class="text-decoration-none"><i class="fa fa-download"></i> Download</a></li>
                            <li><a href="#" class="text-decoration-none btn-property"
                                data-bs-toggle="modal" data-bs-target="#propertyModal"
                                data-name="{{ $report->name }}"
                                data-created-by="{{ $report->user->username ?? 'Admin' }}"
                                data-created-at="{{ $report->created_at ? $report->created_at->format('M d, Y h:i A') : '' }}"
                                data-updated-at="{{ $report->created_at ? $report->created_at->format('M d, Y h:i A') : '' }}"
                                data-description="{{ $report->description ?? ''}}"
                            ><i class="fa fa-cog"></i> Properties</a></li>
                        </ul>
                    </div>
                @endforeach
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
@endsection

@section('js')
<script>
    $('.btn-property').on('click', function(){
        $('#propertyName').html($(this).data('name'));
        $('#propertyType').html($(this).data('type'));
        $('#propertyCreatedBy').html($(this).data('created-by'));
        $('#propertyCreated').html($(this).data('created-at'));
        $('#propertyUpdated').html($(this).data('updated-at'));
        $('#propertyDescription').html($(this).data('description'));
    });

    $('.btn-confirm').on('click', function(){
        var form = $(this).data('target');
        var message = $(this).data('message') ?? "Are you sure you wan't save changes?";
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
</script>
@endsection