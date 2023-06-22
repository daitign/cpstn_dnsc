@extends('layout.sidebar')
@section('title')
    <title>Announcements</title>
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
    </style>
@endsection
@section('page')
<div class="page-header pb-2">
    <h1>Announcements</h1>
</div>
<div class="container">
    <div class="row mt-2">
        <div class="col-12 px-2">
            <div class="card p-3">
                <div class="card-body pt-2">
                    <h4>Announcements</h4>
                    <table class="table datatables">
                        <thead><tr><td>#</td><td>Name</td><td>Description</td><td>Date</td><td>Action</td></tr></thead>
                        <div style="max-height:400px; overflow-y:scroll">
                            <tbody>
                                @foreach($announcements as $announcement)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $announcement->name }}</td>
                                        <td>{{ $announcement->description }}</td>
                                        <td>{{ $announcement->date ? Carbon\Carbon::parse($announcement->date)->format('M d, Y') : '' }}</td>
                                        <td>
                                            <a href="{{ route('admin-announcement-edit', $announcement->id) }}" class="text-info"><i class="fa fa-pen"></i> Edit</a>

                                            <a href="#"
                                                class="text-danger btn-confirm" data-target="#delete-announcement-{{ $announcement->id }}" data-message="Are you sure you wan't to delete this annoucement?"><i class="fa fa-trash"></i> Delete</a>
                                            <form action="{{ route('admin-announcement-delete', $announcement->id) }}" method="POST" id="delete-announcement-{{ $announcement->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>

@endsection

