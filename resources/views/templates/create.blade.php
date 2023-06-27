@extends('layout.sidebar')
@section('title')
<title>Add Template</title>
@endsection

@section('page')
    <div class="page-header">
        <h1>Add Template</h1>
    </div>
    <div class="container">
        <div class="row mt-3 px-2 pb-3">
            @include('layout.alert')
            <form method="POST" action="{{ route('staff.template.store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Template Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Role</label>
                        <select name="role" class="form-control select-role" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name ?? ''}}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <span class="text-danger error_type">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 institute-container d-none">
                        <label for="name" class="form-label">Institute/Office</label>
                        <select name="institutes[]" id="institute" class="select2 form-control" multiple>
                            @foreach($institutes as $institute)
                                <option value="{{ $institute->id }}">{{ $institute->area_name ?? ''}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 process-container d-none">
                        <label for="name" class="form-label">Process</label>
                        <input type="hidden" name="process" id="process">
                        <div id="process_tree"></div>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" id="date" class="form-control" name="date" max="{{ date('Y-m-d') }}"/>
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
                <div style="text-align: right" class="pb-5">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('packages/bootstrap-treeview-1.2.0/src/js/bootstrap-treeview.js') }}"></script>
<script>
    $("#date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "{{ date('Y-m-d') }}"
    });
    
    var tree_process = {!! json_encode($tree_process) !!};
    var process_tree = $('#process_tree').treeview({
        data: tree_process,
        levels: 1,
        collapseIcon: "fa fa-minus",
        expandIcon: "fa fa-plus",
        multiSelect: true,
        onNodeSelected: function(event, data) {
            var processes = process_tree.treeview('getSelected');
            var selected_process = [];
            $(processes).each(function(i, val){
                selected_process.push(val.id);
            });
            $('#process').val(selected_process);
        }
    });
    $('.select2').select2({
        'width' : '100%'
    });

    $('.select-role').on('change', function(){
        var role_name = $(this).find('option:selected').text();
        
        $('#process').prop('required', false);
        $('#institute').prop('required', false);
        $('.process-container').addClass('d-none');
        $('.institute-container').addClass('d-none');

        if(role_name == 'Process Owner') {
        $('#process').prop('required', true);
            $('.process-container').removeClass('d-none');
        }else if(role_name == 'Document Control Custodian'){
            $('#institute').prop('required', true);
            $('.institute-container').removeClass('d-none');
        }
    });
</script>
@endsection