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

        .maxed{
            min-height: 16rem;
            max-height: 16rem;
        }
    </style>
@endsection
@section('page')
    <div class="page-header pb-2">
        <h1>Assign Area</h1>
    </div>
    {{-- Transaction Messages --}}
    <div class="container">
        @if (session('success'))
            <div class="alert mt-2 alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert mt-2 alert-danger alert-dismissible fade show">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container mt-3">
        <div class="row">
            @foreach ($data as $user)
            <div class="col-3">
                <div class="card">
                    <img src="{{ Storage::url($user->img) }}" onerror="this.src='/storage/assets/dnsc-logo.png'" class="card-img-top maxed" alt="User Image">
                    <div class="card-body text-center">
                        <h5>
                            {{ Str::limit($user->firstname . ' ' . ($user->middlename ? strtoupper(substr($user->middlename, 0, 1)) . '. ' : '') . $user->surname . ' ' . ($user->suffix ? $user->suffix : ''), 26, '...') }}
                        </h5>
                        <h6><Strong>{{ $user->role_name ?? ''}}</strong></h6>
                        <hr>
                        <div class="text-center">
                            <button type="button" value="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="{{ $user->role_id == 10 ? '#dcc':'#po' }}" class="btn btn-outline-success approve" value="{{ $user->id }}">Assign</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if (count($data) == 0)
                <marquee><h1>No DCC/PO users</h1></marquee>
            @endif
        </div>
    </div>

    <!-- Modal -->
    {{-- dcc --}}
    <div class="modal fade" id="dcc" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-dcc">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="mb-1">
                            <span>Category</span>
                            <select id="dcc_category" required class="form-control">
                                <option value="" disabled selected>Select a category</option>
                                <option value="0">Administration</option>
                                <option value="1">Academics</option>
                            </select>
                        </div>
                        <div class="mb-1" id="dcc_office">

                        </div>
                        <div class="mb-1" id="dcc_institute">
                            
                        </div>
                        <div class="mb-1" id="dcc_program">
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- po --}}
    <div class="modal fade" id="po" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-po">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id_po">
                        <div class="mb-1">
                            <span>Category</span>
                            <select id="po_category" required class="form-control">
                                <option value="" disabled selected>Select a category</option>
                                <option value="0">Administration</option>
                                <option value="1">Academics</option>
                            </select>
                        </div>
                        <div class="mb-1" id="po_office">

                        </div>
                        <div class="mb-1" id="po_institute">
                            
                        </div>
                        <div class="mb-1" id="po_program">
                            
                        </div>
                        <div class="mb-1" id="po_process">
                            
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var data = {!! json_encode($areas) !!};
        var dcc_office_link = "{{ route('add-office-user') }}";
        var po_office_link = "{{ route('add-office-user') }}";
        var dcc_program_link = "{{ route('add-program-user') }}";
        var po_process_link = "{{ route('add-process-user') }}";
        console.log(data);
        document.addEventListener('DOMContentLoaded', function() {
            $(document).on('click','.approve', function () {
                $('#user_id').val($(this).val());
                $('#user_id_po').val($(this).val());
            });
            $(document).on('change','#dcc_category', function () {
                if ($(this).val() == 1) {
                    $('#form-dcc').attr('action', dcc_program_link);
                    $('#dcc_institute').empty();
                    $('#dcc_office').empty();
                    $('#dcc_program').empty();
                    var institute_label = $('<span>', {
                        'text':'Institute'
                    });
                    $('#dcc_institute').append(institute_label);
                    var institute_list = $('<select>', {
                        'class':'form-control',
                        'id':'dcc_institute_list'
                    });
                    var dcc_institute_option = $('<option>', {
                        'value':'',
                        'text':'Select an institute',
                        'disabled':true,
                        'selected':true
                    });
                    institute_list.append(dcc_institute_option);
                    for (const key in data[1].institutes) {
                        var dcc_institute_option = $('<option>', {
                            'value':data[1].institutes[key].id,
                            'text':data[1].institutes[key].institute_name,
                        });
                        institute_list.append(dcc_institute_option);
                    }
                    $('#dcc_institute').append(institute_list);
                }
                else{
                    $('#form-dcc').attr('action', dcc_office_link);
                    $('#dcc_institute').empty();
                    $('#dcc_program').empty();
                    $('#dcc_office').empty();
                    var office_label = $('<span>', {
                        'text':'Office'
                    });
                    $('#dcc_office').append(office_label);
                    var office_list = $('<select>', {
                        'class':'form-control',
                        'id':'dcc_office_list',
                        'required':true,
                        'name':'office_id'
                    });
                    var dcc_office_option = $('<option>', {
                        'value':'',
                        'text':'Select an office',
                        'disabled':true,
                        'selected':true
                    });
                    office_list.append(dcc_office_option);
                    for (const key in data[0].offices) {
                        var dcc_office_option = $('<option>', {
                            'value':data[0].offices[key].id,
                            'text':data[0].offices[key].office_name,
                        });
                        office_list.append(dcc_office_option);
                    }
                    $('#dcc_office').append(office_list);
                    var container = $('<div>', {
                        'class':'text-center mt-2',
                    });
                    var dcc_submit = $('<button>', {
                        'type':'submit',
                        'class':'btn btn-success',
                        'text':'Save Changes'
                    });
                    container.append(dcc_submit)
                    $('#dcc_office').append(container);

                }
            });

            $(document).on('change','#dcc_institute_list', function () {
                $('#dcc_program').empty();
                var program_label = $('<span>', {
                    'text':'Program'
                });
                $('#dcc_program').append(program_label);
                var program_list = $('<select>', {
                    'class':'form-control',
                    'id':'dcc_program_list',
                    'required':true,
                    'name':'program_id'
                });
                var dcc_program_option = $('<option>', {
                    'value':'',
                    'text':'Select an program',
                    'disabled':true,
                    'selected':true
                });
                program_list.append(dcc_program_option);
                var index = data[1].institutes.findIndex(item => item.id === parseInt($(this).val()));
                for (const key in data[1].institutes[index].programs) {
                    var dcc_program_option = $('<option>', {
                        'value':data[1].institutes[index].programs[key].id,
                        'text':data[1].institutes[index].programs[key].program_name,
                    });
                    program_list.append(dcc_program_option);
                }
                $('#dcc_program').append(program_list);
                var container = $('<div>', {
                    'class':'text-center mt-2',
                });
                var dcc_submit = $('<button>', {
                    'type':'submit',
                    'class':'btn btn-success',
                    'text':'Save Changes'
                });
                
                container.append(dcc_submit)
                $('#dcc_program').append(container);
            });

            $(document).on('change','#po_category', function () {
                $('#po_process').empty();
                if ($(this).val() == 1) {
                    $('#form-po').attr('action', po_process_link);
                    $('#po_institute').empty();
                    $('#po_office').empty();
                    $('#po_program').empty();
                    var institute_label = $('<span>', {
                        'text':'Institute'
                    });
                    $('#po_institute').append(institute_label);
                    var institute_list = $('<select>', {
                        'class':'form-control',
                        'id':'po_institute_list'
                    });
                    var po_institute_option = $('<option>', {
                        'value':'',
                        'text':'Select an institute',
                        'disabled':true,
                        'selected':true
                    });
                    institute_list.append(po_institute_option);
                    for (const key in data[1].institutes) {
                        var po_institute_option = $('<option>', {
                            'value':data[1].institutes[key].id,
                            'text':data[1].institutes[key].institute_name,
                        });
                        institute_list.append(po_institute_option);
                    }
                    $('#po_institute').append(institute_list);
                }
                else{
                    $('#form-po').attr('action', po_process_link);
                    $('#po_institute').empty();
                    $('#po_program').empty();
                    $('#po_office').empty();
                    var office_label = $('<span>', {
                        'text':'Office'
                    });
                    $('#po_office').append(office_label);
                    var office_list = $('<select>', {
                        'class':'form-control',
                        'id':'po_office_list',
                        'required':true,
                        'name':'office_id'
                    });
                    var po_office_option = $('<option>', {
                        'value':'',
                        'text':'Select an office',
                        'disabled':true,
                        'selected':true
                    });
                    office_list.append(po_office_option);
                    for (const key in data[0].offices) {
                        var po_office_option = $('<option>', {
                            'value':data[0].offices[key].id,
                            'text':data[0].offices[key].office_name,
                        });
                        office_list.append(po_office_option);
                    }
                    $('#po_office').append(office_list);
                    

                }
            });
            $(document).on('change','#po_office_list', function () {
                let sel = $(this).val();
                sel = data[0].offices.findIndex(item=>item.id === parseInt(sel));
                console.log(sel);
                var office_label = $('<span>', {
                    'text':'Process'
                });
                $('#po_office').append(office_label);
                var process_list = $('<select>', {
                    'class':'form-control',
                    'id':'po_office_process_list',
                    'required':true,
                    'name':'process_id'
                });
                var po_office_process_option = $('<option>', {
                    'value':'',
                    'text':'Select a Process',
                    'disabled':true,
                    'selected':true
                });
                process_list.append(po_office_process_option);
                for (const key in data[0].offices[sel].processes) {
                    var po_process_option = $('<option>', {
                        'value':data[0].offices[sel].processes[key].id,
                        'text':data[0].offices[sel].processes[key].process_name,
                    });
                    process_list.append(po_process_option);
                }
                var container = $('<div>', {
                    'class':'text-center mt-2',
                });
                var po_submit = $('<button>', {
                    'type':'submit',
                    'class':'btn btn-success',
                    'text':'Save Changes'
                });
                container.append(po_submit)
                $('#po_office').append(process_list,container);
            });
            var i;
            $(document).on('change','#po_institute_list', function () {
                $('#po_program').empty();
                $('#po_process').empty();
                var program_label = $('<span>', {
                    'text':'Program'
                });
                $('#po_program').append(program_label);
                var program_list = $('<select>', {
                    'class':'form-control',
                    'id':'po_program_list',
                    'required':true,
                    'name':'program_id'
                });
                var po_program_option = $('<option>', {
                    'value':'',
                    'text':'Select an program',
                    'disabled':true,
                    'selected':true
                });
                program_list.append(po_program_option);
                var index = data[1].institutes.findIndex(item => item.id === parseInt($(this).val()));
                i = index;
                for (const key in data[1].institutes[index].programs) {
                    var po_program_option = $('<option>', {
                        'value':data[1].institutes[index].programs[key].id,
                        'text':data[1].institutes[index].programs[key].program_name,
                    });
                    program_list.append(po_program_option);
                }
                $('#po_program').append(program_list);
            });

            $(document).on('change','#po_program_list', function () {
                $('#po_process').empty();
                var process_label = $('<span>', {
                    'text':'Process'
                });
                $('#po_process').append(process_label);
                var process_list = $('<select>', {
                    'class':'form-control',
                    'id':'po_process_list',
                    'required':true,
                    'name':'process_id'
                });
                var po_process_option = $('<option>', {
                    'value':'',
                    'text':'Select a process',
                    'disabled':true,
                    'selected':true
                });
                process_list.append(po_process_option);
                var index = data[1].institutes[i].programs.findIndex(item => item.id === parseInt($(this).val()));
                for (const key in data[1].institutes[i].programs[index].processes) {
                    var po_process_option = $('<option>', {
                        'value':data[1].institutes[i].programs[index].processes[key].id,
                        'text':data[1].institutes[i].programs[index].processes[key].process_name,
                    });
                    process_list.append(po_process_option);
                }
                $('#po_process').append(process_list);

                var container = $('<div>', {
                    'class':'text-center mt-2',
                });
                var po_submit = $('<button>', {
                    'type':'submit',
                    'class':'btn btn-success',
                    'text':'Save Changes'
                });
                
                container.append(po_submit)
                $('#po_process').append(container);
            });
        });
    </script>
@endsection
