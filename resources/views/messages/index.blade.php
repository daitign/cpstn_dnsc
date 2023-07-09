@extends('layout.sidebar')
@section('title')
<title>Messages</title>
@endsection
@section('css-page')
  <link href="{{ asset('css/chatbox.css') }}" rel="stylesheet" />
@endsection
@section('page')
<div class="m-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title ">Messages</h4>
          </div>
          <div class="card-body row">
            <div class="mesgs col-4 animated">
                <div class="msgbox">
                    <ul class="list-group">
                    </ul>
                </div>
            </div>
            
            <div class="messaging col-12">
                <div class="inbox_msg">
                    <div class="mesgs col-md-12">
                        <div class="msg_history" style="overflow: auto;height: 50vh;"></div>
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <style>
                                        .input-group .form-control {
                                          border-top-right-radius: 0;
                                          border-bottom-right-radius: 0;
                                        }
                                      
                                        .input-group-append .btn {
                                          border-top-left-radius: 0;
                                          border-bottom-left-radius: 0;
                                        }
                                      
                                        .gap {
                                          margin-left: 10px;
                                        }
                                      </style>
                                      
                                      <form id="chat_form" action="{{ route('messages.send') }}" method="POST" class="text-right">
                                        @csrf
                                        <div class="input-group">
                                          <textarea class="form-control" name="message" placeholder="Type your message..." rows="1"></textarea>
                                          <div class="input-group-append">
                                            <button class="btn btn-primary gap" type="submit"><i class="fas fa-paper-plane"></i></button>
                                          </div>
                                        </div><br>
                                      </form>
                                      
                                      
                                      
                                      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
    var messageInterval = null;
    var timestamp = '';
    var ajax_done = true;
    var elem = $('.msg_history');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $(document).ready(function(){
        const myInterval = window.setInterval(function(){
            if(ajax_done) {
                loadMessages();
            }            
        }, 1000);
    });
    
    $('#chat_form').on('submit', function(event){
        event.preventDefault();
        var post_url = $(this).attr("action");
        var request_method = $(this).attr("method");
        var form_data = $(this).serializeArray();

        $.ajax({
            url : post_url,
            type: request_method,
            data : form_data,
            success: function(data) {
                $(".msg_history").scrollTop($(".msg_history")[0].scrollHeight);
            }
        });
        $(".write_msg").val('');
    });

    function loadMessages() {
        ajax_done = false;
        $.ajax({
            url: '{{ route("messages") }}',
            method: 'GET',
            data: { 
                "timestamp": timestamp
            },
            success: function (data) {
                ajax_done = true;
                console.log(data);
                if(timestamp == '') {
                    elem.html('');
                }
                if(data.messages.length > 0 ) {
                    data.messages.forEach(function(msg) {
                        if(msg.user_id == {{ auth()->user()->id }}){
                            elem.append('<div class="outgoing_msg"><div class="sent_msg"><div class="received_withd_msg"><p>'
                                + msg.message + '</p><div class="seen"></div></div><div class="received_withd_msg"><span class="sentby pull-right"><small>'
                                + msg.created_at_formatted + '</small></span></div></div></div>');
                        }else{
                            elem.append(`<div class="incoming_msg"><img src="{{ asset('storage/profiles') }}/` + msg.user.img + `" onerror="this.src='/storage/assets/dnsc-logo.png'" width="50px"/><div class="received_msg"><div class="received_withd_msg"><p>`
                                + msg.message + `</p><span class="sentby show"><small>`
                                + msg.sender + `</small></span><span class="sentby pull-right"><small>`
                                + msg.created_at_formatted + `</small></span></div></div></div>`);
                        }
                    });
                }
                timestamp = data.timestamp;
            }
        });
    }
</script>
@endsection