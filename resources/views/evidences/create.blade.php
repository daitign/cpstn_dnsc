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
                        <label for="directory" class="form-label">Folder (If empty, coordinate with DCC):</label>
                        <select id="directory" name="directory" class="form-control" required>
                            <option value="">Select Folder</option>
                            @foreach($directories as $directory)
                                <option value="{{ $directory->id }}">
                                    @if(in_array(auth()->user()->role->role_name, ['Process Owner', 'Internal Auditor']))
                                        {{ sprintf('%s%s%s', $directory->parent->parent->name ? $directory->parent->parent->name.' > ' : '', $directory->parent->name ? $directory->parent->name.' > ' : '', $directory->name ?? '') }}
                                    @else
                                        {{ $directory->name ?? '' }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file_attachments" class="form-label">Attachment</label>
                        <input type="file" class="form-control" 
                            name="file_attachments[]" id="file_attachments" required multiple
                            accept="image/jpeg,image/png,application/pdf,application/vnd.oasis.opendocument.text,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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