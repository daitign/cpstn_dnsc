@extends('layout.sidebar')
@section('title')
<title>Add Template</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Add Template</h1>
    </div>
    <div class="container">
        <div class="row mt-3 px-2">
            @include('layout.alert')
            <form method="POST" action="{{ route('staff.template.store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Template Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="search" class="form-label">Description:</label>
                        <textarea name="description" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="file_attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="file_attachment" id="file_attachment" required accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    </div>
                </div>
                <div style="text-align: right">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection