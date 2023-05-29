@extends('layout.sidebar')
@section('title')
<title>Dashboard</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>
    <div class="container mt-3">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    
                    @if(in_array(auth()->user()->role->role_name, ['Quality Assurance Director', 'Administrator']))
                        <div class="col-4">
                            <a href="{{ route('admin-area-page') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-building"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Areas</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('admin-user-list') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-user"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Users</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    
                    @if(auth()->user()->role->role_name == 'Staff')
                        <div class="col-4">
                            <a href="{{ route('staff.template.index') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-newspaper"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Templates</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('staff.manual.index') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-book"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Manuals</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    
                    @if(auth()->user()->role->role_name == 'Process Owner')
                        <div class="col-4">
                            <a href="{{ route('manuals') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-book"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Manuals</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('evidences') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-receipt"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Evidence</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    
                    @if(in_array(auth()->user()->role->role_name, ['Internal Lead Auditor', 'Internal Auditor']))
                        <div class="col-4">
                            <a href="{{ route('templates') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-newspaper"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Templates</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('evidences') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-receipt"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Evidence</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->role->role_name =='Document Control Custodian')
                        <div class="col-4">
                            <a href="{{ route('manuals') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-book"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Manuals</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('evidences') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-receipt"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Evidence</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->role->role_name == 'Human Resources')
                        <div class="col-4">
                            <a href="{{ route('hr-offices-page') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-building"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Offices</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('hr-survey-page') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-chart-bar"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Surveys</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    @if(auth()->user()->role->role_name == 'College Management Team')
                        <div class="col-4">
                            <a href="{{ route('cmt.survey-reports') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-book"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Pending SR</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('cmt.consolidated-audit-reports') }}" class="text-success">
                                <div class="card p-3 text-center">
                                    <div class="card-body pt-2">
                                        <div class="block-content block-content-full ratio ratio-16x9">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div>
                                                    <i class="fa fa-4x fa-book"></i>
                                                    <div class="fs-md fw-semibold mt-3 text-uppercase">Pending CR</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                    
                    <div class="col-4">
                        <a href="{{ route('archives-page') }}" class="text-success">
                            <div class="card p-3 text-center">
                                <div class="card-body pt-2">
                                    <div class="block-content block-content-full ratio ratio-16x9">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div>
                                                <i class="fa fa-4x fa-archive"></i>
                                                <div class="fs-md fw-semibold mt-3 text-uppercase">Archives</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 px-2">
                        <div class="card p-3">
                            <div class="card-body pt-2">
                                <h4>Notifications</h4>
                                <table class="table datatables">
                                    <thead><tr><td>#</td><td>Notification</td><td>User</td><td>Date</td></tr></thead>
                                    <div style="max-height:400px; overflow-y:scroll">
                                        <tbody>
                                            @foreach($notifications as $notification)
                                                @if($notification->data['by_user_id'] !== auth()->user()->id)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $notification->data['message'] }}</td>
                                                        <td>{{ $notification->data['by'] }}</td>
                                                        <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </div>
                                </table>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="col-12">
                    <div class="row">
                        <div class="card text-center">
                            <div class="calendar"></div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="card p-3 text-center">
                            <div class="card-body">
                                <h4>{{ in_array($user_type, ['Administrators', 'Human Resources', 'Quality Assurance Director']) ? 'All Users' : $user_type }}</h4>
                                <div style="max-height:400px; overflow-y:scroll">
                                    <table class="table">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="text-center">
                                                <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" style="border-radius:50%" width="60px" height="50px" alt="User Image">
                                            </td>
                                            <td>   
                                                <strong class="px-0"><small>{{ $user->firstname }} {{ $user->surname }}</small></strong><br/>
                                                ({{ $user->username }})
                                            </td>
                                        </tr>
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(".calendar").flatpickr({
        inline: true
    });
</script>
@endsection