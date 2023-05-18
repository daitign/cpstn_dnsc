@extends('layout.sidebar')
@section('title')
<title>Add Evidence</title>
@endsection
@section('page')
    <div class="page-header">
        <h1>Add Evidence</h1>
    </div>
    <div class="container">
        <div class="row mt-3 px-2">
            @include('layout.alert')
            <form method="POST" action="{{ route('po.evidence.store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Evidence Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Evidence Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="search" class="form-label">Description:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" class="form-control" name="date" max="{{ date('Y-m-d') }}"/>
                    </div>
                    <div class="mb-3">
                        <label for="directory" class="form-label">Directory (If empty, coordinate with DCC):</label>
                        <select id="directory" name="directory" class="form-control" required>
                            <option value="">Select Directory</option>
                            @foreach($directories as $directory)
                                <option value="{{ $directory->id }}">{{ $directory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file_attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="file_attachment" id="file_attachment" required accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                    </div>
                </div>
                <div style="text-align: right" class="pb-3">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script>
    $("#date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "{{ date('Y-m-d') }}"
    });
</script>
@endsection