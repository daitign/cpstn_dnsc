<script>
    var files = {!! json_encode($files) !!};
    var user_id = parseInt("{{ Auth::user()->id }}");

    var userShare = $('#userShare').select2({
        dropdownParent: $('#shareModal'),
        width: '100%'
    });

    $('.toggleDirectoryModal').on('click', function(){
        $('#directoryModalForm').attr('action', $(this).data('route'));
        $('#directory').val($(this).data('name'));
    });

    $('.btn-confirm').on('click', function(){
        var form = $(this).data('target');
        var message = $(this).data('message') ?? "Are you sure you wan't to delete?";
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

    $('.userSelection').on('change', function(){
        var userID = $(this).val();
        if(userID == '') {
            location.href = "{{ route('archives-page') }}";
        }else{
            location.href = "{{ route('archives-page') }}";
        }
    });

    $('.btn-property').on('click', function(){
        
        $('#propertyName').html($(this).data('name'));
        $('#propertyType').html($(this).data('type'));
        $('#propertyCreatedBy').html($(this).data('created-by'));
        $('#propertyCreated').html($(this).data('created-at'));
        $('#propertyUpdated').html($(this).data('updated-at'));
        $('#propertyDescription').html($(this).data('description'));

        
    });

    $('.btn-history').on('click', function(){
        var file_id = parseInt($(this).data('file-id'));
        if(file_id !== '') {
            var file = files.find(item => 
                item.id === file_id
            );

            $('.file-history-table tbody').html('');
            if(file.histories.length > 0) {
                file.histories.forEach(function(i){
                    $('.file-history-table tbody').append(`
                        <tr>
                            <td>` + i.file_name + `</td>
                            <td>` + i.description + `</td>
                            <td>` + i.created_at_format + `</td>
                            <td><a href="{{ url('/') }}/archives/file-history/` + i.id + `" target="_blank" class="text-decoration-none"><i class="fa fa-download"></i> Download</a></td>
                        </tr>
                    `);
                });
            }
        }
    });

    $('.btn-tracking').on('click', function(){
       $('#trackingModal').find('.modal-body').html($(this).parents('li').find('.file-tracking-info').html());
    });

    $('.btn-share').on('click', function(){
        var users = "" + $(this).data('users') + "";
        $('#shareModalForm').prop('action', $(this).data('route'));
        $("#userShare option:selected").removeAttr("selected");

        if(users != '') {
            users = users.includes(', ') ? users.split(', ') : [users];
            
            userShare.val(users).trigger('change');
        }
    });

    $('.btn-directory').on('dblclick', function(){
        location.href = $(this).data('route')
    });

    $('.btn-remarks').on('click', function(){
        var file_id = parseInt($(this).data('file-id'));
        
        $('.btn-submit-remarks').hide();
        $('.remarksDetailForm').hide();

        if( $(this).data('route')) {
            $('#remarksForm').prop('action', $(this).data('route'));
            $('.btn-submit-remarks').show();
            $('.remarksDetailForm').show();
        }
        
        
        var file = files.find(item => 
            item.id === file_id
        );

        $('.recent-remarks-table').html('');
        if(file.remarks.length > 0) {
            var remark = file.remarks.find(item => item.user_id === user_id);
            if(remark) {
                $('#remarks-' + remark.type).prop('checked', true);
                $('#remarks-comments').html(remark.comments);
            }
            file.remarks.forEach(function(i){
                $('.recent-remarks-table').append(`
                    <tr>
                        <td class="text-center">
                            <i class="fa fa-user text-` + i.type + ` fa-2x"></i><br/>
                            <small class="badge bg-secondary" data-bs-toggle="tooltip" title="` + i.created_at_formatted + `">` + i.created_at_for_humans + `</small>
                        </td>
                        <td><strong class="px-0">` + i.user.firstname + ` ` + i.user.surname + `</strong><br/>` +
                            `(` + i.user.role.role_name + `)<br/>` + 
                            i.comments + `
                        </td>
                    </tr>
                `);
            });
        }
        
    });

    $("#date").flatpickr({
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        maxDate: "{{ date('Y-m-d') }}"
    });

    $('.upload-cars').on('click', function(){
        $('#audit_report_id').val($(this).data('audit-report'));
    });

    $('.btn-edit-file').on('click', function(){
        $('#updateFileModalForm').prop('action', $(this).data('route'));
        $('#edit_file_name').val($(this).data('name'));
        $('#file_description').html($(this).data('description'));
    });
</script>