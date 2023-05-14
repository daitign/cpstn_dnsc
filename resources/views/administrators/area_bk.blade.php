@extends('layout.sidebar')
@section('title')
    <title>Area</title>
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
    </style>
@endsection
@section('page')
    {{-- Adding Modal --}}
    <div class="page-header pb-2">
        <h1>Areas</h1>
        
        <div class="row">
            <div class="col-8">
                @foreach ($data as $item)
                    <button type="button" class="btn btn-design area me-2 {{ $loop->index == 0 ? 'active' : ''}}" value="{{$item['id']}}"><span class="mdi mdi-domain"></span> {{$item['area_name']}}</button>
                @endforeach
            </div>
            <div class="col-4 d-flex align-items-center justify-content-end">
                <div class="dropdown">
                    <button type="button" class="btn btn-success" data-bs-toggle="dropdown" aria-expanded="false"><span
                            class="mdi mdi-plus"></span> Add</button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#office"><span class="mdi mdi-home-account text-success"></span>
                                Office</button></li>
                        <li><button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#exampleModal"><span
                                    class="mdi mdi-domain text-success"></span>
                                Institute</button></li>
                        <li><button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#program"><span class="mdi mdi-office-building text-success"></span>
                                Program</button></li>
                        <li><button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#process"><span class="mdi mdi-folder-table text-success"></span>
                                Process</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction Messages --}}
    <div class="bg-white" style="min-height: 80vh">
        <div class="container">
            @include('layout.alert')
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="office-buttons d-none row pt-3 ps-3 pe-3">

        </div>

        <div class="institute-buttons row pt-3 ps-3 pe-3">

        </div>

        <div class="program-buttons row pt-3 ps-3 pe-3">

        </div>

        <div class="process-buttons row pt-3 ps-3 pe-3">

        </div>
    </div>

    <!-- Modal -->
    {{-- Institute --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Institute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add-institute') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="institute" class="form-label">Institute</label>
                            <input type="text" class="form-control" name="institute_name" placeholder="Enter institute name" required>
                        </div>
                        <div class="mb-3">
                            <label for="institute" class="form-label">Institute Full name</label>
                            <input type="text" class="form-control" name="institute_description" placeholder="Enter full name" required>
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
    <div class="modal fade" id="editInstitute" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Institute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('edit-institute') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                            <input type="hidden" name="institute_id" id="institute_id">
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute</label>
                                <input type="text" class="form-control" name="institute_name" id="institute_name" placeholder="Enter institute name" required>
                            </div>
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute Full name</label>
                                <input type="text" class="form-control" name="institute_description" id="institute_description" placeholder="Enter full name" required>
                            </div>
                            <div class="text-center">
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
    {{-- Program --}}
    <div class="modal fade" id="program" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add-program') }}">
                    @csrf
                    <div class="modal-body">
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute</label>
                                <select name="institute_id" class="form-control" required>
                                    <option value="" disabled selected>Select an Institute</option>
                                    @foreach ($data[1]['institutes'] as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['institute_name'] }} - {{ $item['institute_description'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="institute" class="form-label">Program</label>
                                <input type="text" class="form-control" name="program_name" placeholder="Enter program name" required>
                            </div>
                            <div class="mb-3">
                                <label for="institute" class="form-label">Program Full name</label>
                                <input type="text" class="form-control" name="program_description" placeholder="Enter full name" required>
                            </div>
                            <div class="text-center">
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
    <div class="modal fade" id="editProgram" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('edit-program') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="program_id" id="program_id">
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <input type="text" class="form-control" name="program_name" placeholder="Enter program name" required>
                        </div>
                        <div class="mb-3">
                            <label for="program" class="form-label">Program Full name</label>
                            <input type="text" class="form-control" name="program_description" placeholder="Enter full name" required>
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
    {{-- Process --}}
    <div class="modal fade" id="process" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Process</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add-process') }}">
                    @csrf
                    <div class="modal-body" id="addProcess">
                        <div class="mb-3">
                            <label for="process_type" class="form-label">Process Belong</label>
                            <select name="process_type" id="process_type" class="form-control" required>
                                <option value="" disabled selected>Select an option</option>
                                <option value="office">Office</option>
                                <option value="program">Program</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editProcess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Process</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('edit-process') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="process_id" id="process_id">
                        <div class="mb-3">
                            <label for="process" class="form-label">Process</label>
                            <input type="text" class="form-control" name="process_name" placeholder="Enter process name" required>
                        </div>
                        <div class="mb-3">
                            <label for="process" class="form-label">Process Full name</label>
                            <input type="text" class="form-control" name="process_description" placeholder="Enter full name" required>
                        </div>
                        <div class="text-center">
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
    {{-- Office --}}
    <div class="modal fade" id="office" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add-office') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="Office" class="form-label">Office</label>
                            <input type="text" class="form-control" name="office_name" placeholder="Enter office name" required>
                        </div>
                        <div class="mb-3">
                            <label for="institute" class="form-label">Office Full name</label>
                            <input type="text" class="form-control" name="office_description" placeholder="Enter full name" required>
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
    <div class="modal fade" id="editOffice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Office</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('edit-office') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="office_id" id="office_id">
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

    <script>
        const data = {!! json_encode($data) !!};
        var p;
        console.log(data);
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click','.area', function (event) {
                event.preventDefault();
                $('.program-buttons').empty();
                $('.process-buttons').empty();
                $('.area').removeClass('active');
                $(this).addClass('active');
                let buttonContainer = $('.institute-buttons');
                const institute = data.find(s => s.id === parseInt($(this).val()));
                $('.institute-buttons').html('');
                if (institute.id != 1) {
                    $('.institute-buttons').html('<h3>Institutes</h3>');
                    for (const key in institute.institutes) {
                        var text = `<div class="card-body pt-2">
                                        <i class="fa fa-building fa-2x"></i>
                                        <h4>` + institute.institutes[key].institute_name + `</h4>
                                        <p>` + institute.institutes[key].institute_description + `</p>
                                    </div>`;
                        var newButton = $('<button>', {
                            'class': 'btn btn-design institute w-100 editable',
                            'html': text,
                            'value': institute.institutes[key].id
                        });
                        var ul = $('<ul>', {
                            'class': 'dropdown-menu edit'
                        });
                        var li = $('<li>');
                        var menu = $('<button>', {
                            'class': 'dropdown-item editInstitute',
                            'text':'Edit',
                            'type':'button',
                            'data-bs-toggle':'modal',
                            'data-bs-target':'#editInstitute',
                            'value': institute.institutes[key].id
                        });                        
                        var newCol = $('<div>', {
                            'class': 'col-4 pb-2 item'
                        });
                        li.append(menu);
                        ul.append(li);
                        newCol.append(newButton);
                        newCol.append(ul);
                        buttonContainer.append(newCol);
                    }
                }
                else{
                    $('.program-buttons').html('');
                    $('.institute-buttons').html('');
                    $('.institute-buttons').html('<h3>Offices</h3>');
                    for (const key in institute.offices) {
                        var text = `<div class="card-body pt-2">
                                        <i class="fa fa-building fa-2x"></i>
                                        <h4>` + institute.offices[key].office_name + `</h4>
                                        <p>` + institute.offices[key].office_description + `</p>
                                    </div>`;

                        var newButton = $('<button>', {
                            'class': 'btn btn-design office w-100 editable',
                            'html': text,
                            'value': institute.offices[key].id,
                        });
                        var ul = $('<ul>', {
                            'class': 'dropdown-menu edit'
                        });
                        var li = $('<li>');
                        var menu = $('<button>', {
                            'class': 'dropdown-item editOffice',
                            'text':'Edit',
                            'type':'button',
                            'data-bs-toggle':'modal',
                            'data-bs-target':'#editOffice',
                            'value': institute.offices[key].id
                        });                        
                        var newCol = $('<div>', {
                            'class': 'col-4 pb-2 item'
                        });
                        li.append(menu);
                        ul.append(li);
                        newCol.append(newButton);
                        newCol.append(ul);
                        buttonContainer.append(newCol);
                    }
                }
            });
            $(document).on('click','.institute', function () {
                $('.process-buttons').empty();
                $('.institute').removeClass('active');
                $(this).addClass('active');
                let buttonContainer = $('.program-buttons');
                const institute = data[1];
                const program = institute.institutes.find(s => s.id === parseInt($(this).val()));
                p = parseInt($(this).val());
                $('.program-buttons').html('<h3>Programs</h3>');
                for (const key in program.programs) {
                    
                    var newButton = $('<button>', {
                        'class': 'btn btn-design program w-100 editable',
                        'text': program.programs[key].program_name,
                        'value': program.programs[key].id,
                    });
                    
                    var ul = $('<ul>', {
                        'class': 'dropdown-menu edit'
                    });
                    var li = $('<li>');
                    var menu = $('<button>', {
                        'class': 'dropdown-item editProgram',
                        'text':'Edit',
                        'type':'button',
                        'data-bs-toggle':'modal',
                        'data-bs-target':'#editProgram',
                        'data-institute': p,
                        'value': program.programs[key].id,
                    });                        
                    var newCol = $('<div>', {
                        'class': 'col-4 pb-2 item'
                    });
                    li.append(menu);
                    ul.append(li);
                    newCol.append(newButton);
                    newCol.append(ul);
                    buttonContainer.append(newCol);
                }
            });
            $(document).on('click','.program', function () {
                $('.program').removeClass('active');
                $('.process-buttons').empty();
                $(this).addClass('active');
                let buttonContainer = $('.process-buttons');
                const institute = data[1];
                var program = institute.institutes.find(s => s.id === p);
                program = program.programs.find(s => s.id === parseInt($(this).val()));
                $('.process-buttons').html('<h3>Process</h3>');
                for (const key in program.processes) {
                    var newButton = $('<button>', {
                        'class': 'btn btn-design process w-100 editable',
                        'text': program.processes[key].process_name,
                        'value': program.processes[key].id,
                    });
                    var ul = $('<ul>', {
                        'class': 'dropdown-menu edit'
                    });
                    var li = $('<li>');
                    var menu = $('<button>', {
                        'class': 'dropdown-item editProcess',
                        'text':'Edit',
                        'type':'button',
                        'data-bs-toggle':'modal',
                        'data-bs-target':'#editProcess',
                        'value': program.processes[key].id,
                    });                        
                    var newCol = $('<div>', {
                        'class': 'col-4 pb-2 item'
                    });
                    li.append(menu);
                    ul.append(li);
                    newCol.append(newButton);
                    newCol.append(ul);
                    buttonContainer.append(newCol);
                }
            });

            $(document).on('click','.office', function () {
                $('.office').removeClass('active');
                $('.process-buttons').empty();
                $(this).addClass('active');
                let buttonContainer = $('.process-buttons');
                const institute = data[0];
                var program = institute.offices.find(s => s.id === parseInt($(this).val()));
                $('.process-buttons').html('<h3>Process</h3>');
                for (const key in program.processes) {
                    var newButton = $('<button>', {
                        'class': 'btn btn-design process w-100 editable',
                        'text': program.processes[key].process_name,
                        'value': program.processes[key].id,
                    });
                    var ul = $('<ul>', {
                        'class': 'dropdown-menu edit'
                    });
                    var li = $('<li>');
                    var menu = $('<button>', {
                        'class': 'dropdown-item editProcess',
                        'text':'Edit',
                        'type':'button',
                        'data-bs-toggle':'modal',
                        'data-bs-target':'#editProcess',
                        'value': program.processes[key].id,
                    });                        
                    var newCol = $('<div>', {
                        'class': 'col-4 pb-2 item'
                    });
                    li.append(menu);
                    ul.append(li);
                    newCol.append(newButton);
                    newCol.append(ul);
                    buttonContainer.append(newCol);
                }
            });

            $(document).on('change','#process_type', function () {
                let process_type =  $('#process_type').val();
                $('#addProcess').children().not(':first-child').remove();
                
                if (process_type === 'program') {
                    // Add institute
                    var newDiv = $('<div>',{
                        class:'mb-3'
                    });
                    var newLabel = $('<label>',{
                        for:'institute',
                        class:'form-label',
                        text:'Institute'
                    });
                    newDiv.append(newLabel);
                    var newSelect = $('<select>',{
                        class:'form-control',
                        name:'institute_id',
                        id:'institute_id',
                        required:true
                    });
                    var defOption = $('<option>',{
                        value:"",
                        text:"Select an Institute",
                        disabled:true,
                        selected:true
                    });
                    newSelect.append(defOption);
                    for (const key in data[1]['institutes']) {
                        var newOption = $('<option>',{
                            value:data[1]['institutes'][key]['id'],
                            text:data[1]['institutes'][key]['institute_name']+' - '+data[1]['institutes'][key]['institute_description'],
                        });
                        newSelect.append(newOption);
                    }
                    newDiv.append(newSelect);
                    $('#addProcess').append(newDiv);
                    // End adding institute

                    // Add Program
                    var newProgramDiv = $('<div>',{
                        class:'mb-3'
                    });
                    var newProgramLabel = $('<label>',{
                        for:'Program',
                        class:'form-label',
                        text:'Program'
                    });
                    newProgramDiv.append(newProgramLabel);
                    var newProgramSelect = $('<select>',{
                        class:'form-control',
                        name:'program_id',
                        id:'programs_id',
                        required:true
                    });
                    var defProgramOption = $('<option>',{
                        value:"",
                        text:"Select a Program",
                        disabled:true,
                        selected:true
                    });
                    newProgramSelect.append(defProgramOption);
                    newProgramDiv.append(newProgramSelect);
                    $('#addProcess').append(newProgramDiv);
                    // End Add Program

                    // Test
                    var processDiv1 = $('<div>', {
                        class: 'mb-3'
                    });

                    var processLabel1 = $('<label>', {
                        for: 'institute',
                        class: 'form-label',
                        text: 'Process'
                    });

                    var processInput1 = $('<input>', {
                        type: 'text',
                        class: 'form-control',
                        name: 'process_name',
                        placeholder: 'Enter process name',
                        required: true
                    });

                    processDiv1.append(processLabel1, processInput1);

                    

                    var processDiv2 = $('<div>', {
                        class: 'mb-3'
                    });

                    var processLabel2 = $('<label>', {
                        for: 'institute',
                        class: 'form-label',
                        text: 'Process Full name'
                    });

                    var processInput2 = $('<input>', {
                        type: 'text',
                        class: 'form-control',
                        name: 'process_description',
                        placeholder: 'Enter full name',
                        required: true
                    });

                    processDiv2.append(processLabel2, processInput2);

                    var submitButton = $('<button>', {
                        type: 'submit',
                        class: 'btn btn-success',
                        text: 'Save changes'
                    });

                    $('#addProcess').siblings('.modal-footer').prepend(submitButton);

                    $('#addProcess').append(processDiv1, processDiv2, centerDiv);

                }
                else{
                    var newDiv = $('<div>', {
                        class: 'mb-3'
                    });

                    var newLabel = $('<label>', {
                        for: 'office',
                        class: 'form-label',
                        text: 'Office'
                    });

                    newDiv.append(newLabel);

                    var newSelect = $('<select>', {
                        class: 'form-control',
                        name: 'office_id',
                        id: 'office_id',
                        required: true
                    });

                    var defOption = $('<option>', {
                        value: "",
                        text: "Select an Office",
                        disabled: true,
                        selected: true
                    });

                    newSelect.append(defOption);

                    for (const key in data[0]['offices']) {
                        var newOption = $('<option>', {
                            value: data[0]['offices'][key]['id'],
                            text: data[0]['offices'][key]['office_name'] + ' - ' + data[0]['offices'][key]['office_description'],
                        });
                        newSelect.append(newOption);
                    }

                    newDiv.append(newSelect);

                    var processDiv1 = $('<div>', {
                        class: 'mb-3'
                    });

                    var processLabel1 = $('<label>', {
                        for: 'institute',
                        class: 'form-label',
                        text: 'Process'
                    });

                    var processInput1 = $('<input>', {
                        type: 'text',
                        class: 'form-control',
                        name: 'process_name',
                        placeholder: 'Enter process name',
                        required: true
                    });

                    processDiv1.append(processLabel1, processInput1);

                    var processDiv2 = $('<div>', {
                        class: 'mb-3'
                    });

                    var processLabel2 = $('<label>', {
                        for: 'institute',
                        class: 'form-label',
                        text: 'Process Full name'
                    });

                    var processInput2 = $('<input>', {
                        type: 'text',
                        class: 'form-control',
                        name: 'process_description',
                        placeholder: 'Enter full name',
                        required: true
                    });

                    processDiv2.append(processLabel2, processInput2);

                    var submitButton = $('<button>', {
                        type: 'submit',
                        class: 'btn btn-success',
                        text: 'Save changes'
                    });

                    var centerDiv = $('<div>', {
                        class: 'text-center'
                    });

                    $('#addProcess').siblings('.modal-footer').prepend(submitButton);

                    $('#addProcess').append(newDiv,processDiv1,processDiv2,centerDiv);

                }
            });
            $(document).on('change','#institute_id', function () {
                $('#program_id').empty();
                const institute = data[1];
                const i = institute.institutes.find(s => s.id === parseInt($(this).val()));
                const program = i.programs;
                var newOption = $('<option>',{
                    'text':'Select a Program',
                    'value':'',
                    'disabled':'true',
                    'selected':'true'
                });
                $('#programs_id').append(newOption);
                for (const key in program) {
                    var newOption = $('<option>',{
                        'text':program[key].program_name+' - '+program[key].program_description,
                        'value':program[key].id
                    });
                    $('#programs_id').append(newOption);
                }
            });

            $(document).on('click','.editInstitute', function () {
                let institute_id = $(this).val();
                const institute = data[1];
                const i = institute.institutes.find(s => s.id === parseInt($(this).val()));
                $('#institute_name').val(i.institute_name);
                $('#institute_description').val(i.institute_description);
                $('#institute_id').val(institute_id);
            });

            $(document).on('click','.editOffice', function () {
                let office_id = $(this).val();
                const institute = data[0];
                const i = institute.offices.find(s => s.id === parseInt($(this).val()));
                $('#office_name').val(i.office_name);
                $('#office_description').val(i.office_description);
                $('#office_id').val(office_id);
            });

            $(document).on('click','.editProgram', function () {
                let program_id = $(this).val();
                $('#program_id').val(program_id);
            });

            $(document).on('click','.editProcess', function () {
                let process_id = $(this).val();
                $('#process_id').val(process_id);
            });

            $(document).on('click', function () {
                $('.edit').removeClass('show');
            });


            $(document).on('contextmenu','.editable', function (event) {
                event.preventDefault();
                $('.dropdown-menu').removeClass('show');
                $(this).next().addClass('show');
            });

            $('.area').first().trigger('click');
        });
    </script>
@endsection
