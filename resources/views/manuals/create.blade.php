@extends('layout.sidebar')
@section('title')
<title>Add Manual</title>
@endsection
@section('page')
    <div class="page-header">
        <h2>Add Manual</h2>
    </div>
    <div class="container">
        <div class="row mt-3 px-2">
            @include('layout.alert')
            <form method="POST" action="{{ route('po.manual.store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Manual Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Manual Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" class="form-control" name="date" max="{{ date('Y-m-d') }}"/>
                    </div>
                    <div class="mb-3">
                        <label for="search" class="form-label">Description:</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    @if(Auth::user()->role->role_name == 'Process Owner')
                        <div class="mb-3">
                            <label for="directory" class="form-label">Directory (If empty, coordinate with DCC):</label>
                            <select id="directory" name="directory" class="form-control" required>
                                <option value="">Select Directory</option>
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
                    @endif
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