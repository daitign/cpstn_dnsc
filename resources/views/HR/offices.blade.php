@extends('layout.sidebar')
@section('title')
    <title>All Office</title>
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
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>Office List</h1>
    </div>
    <div class="container pt-4">
        <div style="text-align:right">
            <button class="btn btn-success mb-5 btn-office-modal" 
                data-action="create" data-bs-toggle="modal" 
                data-bs-target="#officeModal" data-route="{{ route('hr-offices-create') }}">
                    Add Office
            </button>
        </div>
        <div class="row g-3">
            <div class="row" style="overflow-y: auto;height:60vh;">
            @if(count($offices) == 0)
                <h3 class="text-center mt-4">No Office Yet</h3>
            @endif
            @foreach ($offices as $office)
                <div class="col-3">
                    <div class="card p-3 text-center">
                        <div class="card-body pt-2">
                            <i class="fa fa-building fa-2x"></i>
                            <h4>{{ $office->office_name ?? '' }}</h4>
                            <p>{{ $office->office_description ?? '' }}</p>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-office-modal" 
                                data-bs-toggle="modal" data-bs-target="#officeModal"
                                data-route="{{ route('hr-offices-update', $office->id) }}" 
                                data-name="{{ $office->office_name ?? '' }}" 
                                data-description="{{ $office->office_description ?? '' }}">
                                    Update
                            </button>
                            
                            <button class="btn btn-danger btn-confirm" data-target="#delete_office_{{ $office->id }}">Delete</button>
                            <form id="delete_office_{{ $office->id }}" action="{{ route('hr-offices-delete', $office->id) }}" class="d-none" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="officeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('hr-offices-create') }}" id="officeModalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="office" class="form-label">Office</label>
                            <input type="text" class="form-control" name="office_name" id="office_name" placeholder="Enter office name" required>
                        </div>
                        <div class="mb-3">
                            <label for="office" class="form-label">Office Full name</label>
                            <input type="text" class="form-control" name="office_description" id="office_description" placeholder="Enter full name" required>
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
        $(document).ready(function(){
            $('.btn-confirm').on('click', function(){
                var form = $(this).data('target');
                Swal.fire({
                    title: "Are you sure you wan't to delete this office?",
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

            $('.btn-office-modal').on('click', function(){
                $('#exampleModalLabel').html($(this).data('action') == 'create' ? 'Add Office' : 'Edit Office');
                $('#officeModalForm').attr('action', $(this).data('route'));
                $('#office_name').val($(this).data('name'));
                $('#office_description').val($(this).data('description'));

            });
        });
    </script>
@endsection