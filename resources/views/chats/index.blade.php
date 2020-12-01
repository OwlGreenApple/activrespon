@extends('layouts.app')

@section('content')

<div class="container mb-5 main-cont">
  <div class="row">
    <div class="col-md-12">
      <h2><b>WA Chat page</b></h2>  
      <h5>List of your WA chat members.</h5>
      <hr>
    </div>

    <div class="col-md-12">
      <div class="error"><!-- error when unable to insert database --></div>
      <!-- <button id="add_member" class="btn btn-primary btn-sm">Add Member</button> -->
      <div class="card-body table-responsive">
        <div id="chat_members"><!-- data --></div>
      </div>
      <!-- <div class="alert bg-dashboard cardlist">
        You don't have any order yet, please make order <a href="{{ url('pricing') }}">Here</a>
      </div> -->
    </div>

    @if($error == null)

    <div class="col-md-12">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 chat-box px-0">
            <div id="chat_room_member">
              <!-- displaying chat members -->

              @if(count($chats) > 0)
                @foreach($chats AS $key=>$row)
                  <div id="{{ $row['id'] }}" total="0" class="col-md-12 mb-2 chat_room_box">
                   <div class="row chat-name">
                      <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                        <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                      </div>

                      <div class="col-lg-10 col-md-10 col-sm-10 col-10 pr-0">
                        <div class="chat-user">
                          {{$row['name']}}
                          <span class="chat-note-{{ $row['id'] }} float-right chat-notification"><!-- notification --></span>
                          <div class="clearfix"></div>
                        </div>
                        <div class="chat-text-user"><!-- Available --></div>
                      </div>

                      <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                        <div class="chat-time"></div>
                      </div>
                      <!-- -->
                    </div>
                  </div>
                @endforeach
              @else
                {{$error}}
              @endif

            </div>

          </div>

          <div id="content_chat" class="col-lg-8 col-md-8 col-sm-8 chat-box">
            <!-- displaying chat messages -->
          </div>
      </div>
    </div>

    <div class="col-lg-12 mt-2">
      <div class="row">
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-8">
          <span class="error_send"></span>
          
          <div>
            <textarea id="divInput-description-post" class="form-control"></textarea>
            <div media="video" class="btn btn-warning btn-media mt-2 float-left mr-2">Send Video</div>
            <!-- <div media="audio" class="btn btn-warning btn-media mt-2 float-left mr-2">Send Audio</div> -->
            <div class="btn btn-warning btn-media mt-2 float-left">Send Image</div>
            <button type="button" align="right" class="btn btn-success mt-2 float-right btn-send">Send</button>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
    @else
      <div class="alert alert-warning col-lg-12">{{ $error }}</div>
    @endif
  </div>
</div>

<!-- Modal Send Video -->
<div class="modal fade" id="media_video" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Send Video
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <form id="send_video">
          <span class="err_video"><!-- error --></span>
          <input type="file" name="videoWA" class="form-control mt-2"/>
          <h6 class="text-left mt-1">Please use : .mp4</h6>
          <span class="error_media error"><!-- --></span>
          <input type="text" name="vimessages" placeholder="Caption" class="form-control mt-2"/>
          <span class="error_msg error"><!-- --></span>
          <button type="submit" class="btn btn-success mt-2">Send Video</button>
        </form>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Send Audio -->
<div class="modal fade" id="media_audio" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Send Audio
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <form id="send_audio">
          <span class="err_audio"><!-- error --></span>
          <input type="file" name="audioWA" class="form-control mt-2"/>
          <h6 class="text-left mt-1">Please use : .ogg</h6>
          <span class="error_media error"><!-- --></span>
          <input type="text" name="audmessages" placeholder="Caption" class="form-control mt-2"/>
          <span class="error_msg error"><!-- --></span>
          <button type="submit" class="btn btn-success mt-2">Send Audio</button>
        </form>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Send Image -->
<div class="modal fade" id="media_image" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Send Image
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <form id="send_image">
          <img class="image_preview mb-2" />
          <span class="err_img"><!-- error --></span>
          <input id="imgInp" type="file" name="imageWA" class="form-control mt-2"/>
          <h6 class="text-left mt-1">Please use : .jpg or .png</h6>
          <span class="error_media error"><!-- --></span>
          <input type="text" name="messages" placeholder="Caption" class="form-control mt-2"/>
          <span class="error_msg error"><!-- --></span>
          <button type="submit" class="btn btn-success mt-2">Send Image</button>
        </form>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">

  var chat_err = "Please choose chat";
  var chat_err_msg = "Message or media shouldn't be empty";

  $(document).ready(function() 
  {
    
    emojiOne();
    sending_message();
    <?php if($error == null): ?>
    get_messages();
    getNotification();
    getNewMessages();
    <?php endif; ?>
    openSendMedia();
    image_preview();
    sendingImage();
    sending_video();
    
    // sending_audio(); cancelled due API not supported
  });

  function readURL(input) 
  {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        $('.image_preview').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
  }

  function image_preview()
  {
    $("#imgInp").change(function() {
      readURL(this);
    });
  }

  function emojiOne()
  {
    $("#divInput-description-post").emojioneArea({
      placeholder: "Type a message",
      pickerPosition: "bottom"
    });
  }

  function get_messages()
  {
    $(".chat_room_box").click(function(){
        var id = $(this).attr('id');
        $(".btn-send").attr('id',id);
        $("#"+id).attr('total',0);
        $(".chat-note-"+id).hide();
        load_messages(id)
        chatScroll();
    });
  }

  function sending_message()
  {
    $(".btn-send").click(function(){
      var recipient = $(this).attr('id');
      var messages = $("#divInput-description-post").emojioneArea()[0].emojioneArea.getText();

      if(recipient === undefined)
      {
        $(".error_send").html("<div class='alert alert-danger'>"+chat_err+"</div>");
        return false;
      }

      if(messages === "")
      {
        $(".error_send").html("<div class='alert alert-danger'>"+chat_err_msg+"</div>");
        return false;
      }
      
      sendMesssage(recipient,messages);
    });
  }

  function sendMesssage(recipient,messages)
  {
    var data = {
      "recipient":recipient,
      "messages":messages,
      "device_key":"{{ $device_key }}"
    };

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : 'POST',
      url : "{{ url('send_chat_message') }}",
      data : data,
      dataType: 'json',
      beforeSend: function() {
        $(".btn-send").prop('disabled',true).text('Sending...');
      },
      success: function(result) {
        $(".btn-send").text('Send').prop('disabled',false);

        if(result.response == true)
        {
          $(".error_send").html('');
          load_messages(result.to);
          $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
          chatScroll();
        }
        
        if(result.response == false)
        {
          $(".error_send").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
        }
      },
      error: function(xhr)
      {
        $(".btn-send").text('Send').prop('disabled',false);
        console.log(xhr.responseText);
      }
    });
  }

  function chatScroll()
  {
    setTimeout(function(){
      var scrolls = $("#content_chat").prop("scrollHeight");
      $("#content_chat").scrollTop(scrolls);
    },2500);
  }

  function load_messages(id)
  {
    var data = {"chat_id" : id,"device_key" : "{{ $device_key }}"};
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_messages') }}",
      data : data,
      dataType: 'html',
      success: function(result)
      {
        $("#content_chat").html(result);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function getNewMessages()
  {
    var get_messages = setInterval(function()
    {
        getNotification();
    },3000);
  } 

  function getNotification()
  {
     $.ajax({
      type : 'GET',
      url : "{{ url('get-notification') }}",
      data : {'device_id': '{{ $device_id }}'},
      dataType: 'json',
      success: function(result){
        var id = $(".btn-send").attr('id');
        if(result !== 0)
        {
          $.each( result, function( key, value ) {
            var total_notif = $("#"+key).attr('total');
            total_notif = parseInt(total_notif);
            total_notif += parseInt(value);
            if(id !== key)
            {
              $(".chat-note-"+key).html(total_notif).show();
              $("#"+key).attr('total',total_notif);
            }
          });

          if(id !== undefined)
          {
            load_messages(id);
          }
        }
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function openSendMedia()
  {
    $( "body" ).on("click", ".btn-media", function() 
    {
      var media = $(this).attr('media');
      var id = $(".btn-send").attr('id',id); //recipient id
    
      if(media == 'video')
      {
        $("#media_video").modal({backdrop: 'static', keyboard: false});
      }
      else if(media == 'audio')
      {
        $("#media_audio").modal({backdrop: 'static', keyboard: false});
      }
      else
      {
        $("#media_image").modal({backdrop: 'static', keyboard: false});
      }
    });
  }
 
  function sendingImage()
  {
    $("#send_image").submit(function(e){
      e.preventDefault();
      var recipient = $(".btn-send").attr('id');
      var messages = $("input[name='messages']").val();
      var img = $("input[name='imageWA']").val();

      if(recipient === undefined)
      {
        $(".err_img").html('<div class="alert alert-danger">'+chat_err+'</div>');
        return false;
      }

      if(messages === "" || img === "")
      {
        $(".err_img").html("<div class='alert alert-danger'>"+chat_err_msg+"</div>");
        return false;
      }

      var form = $(this)[0];
      var data = new FormData(form);
      data.append("recipient",recipient);
      data.append("device_key","{{ $device_key }}");
      data.append("type","image");
      sendMedia(recipient,data);
      // console.log(data);
    });
  }

  function sending_audio()
  {
    $("#send_audio").submit(function(e){
      e.preventDefault();
      var recipient = $(".btn-send").attr('id');
      var audio = $("input[name='audioWA']").val();
      var messages = $("input[name='audmessages']").val();

      if(recipient === undefined)
      {
        $(".err_audio").html('<div class="alert alert-danger">'+chat_err+'</div>');
        return false;
      }

      if(audio === "" )
      {
        $(".err_audio").html("<div class='alert alert-danger'>"+chat_err_msg+"</div>");
        return false;
      }

      var form = $(this)[0];
      var data = new FormData(form);
      data.append("recipient",recipient);
      data.append("device_key","{{ $device_key }}");
      data.append("type","document");
      sendMedia(recipient,data);
   });
  }

  function sending_video()
  {
    $("#send_video").submit(function(e){
      e.preventDefault();
      var recipient = $(".btn-send").attr('id');
      var video = $("input[name='videoWA']").val();
      var messages = $("input[name='vimessages']").val();

      if(recipient === undefined)
      {
        $(".err_video").html('<div class="alert alert-danger">'+chat_err+'</div>');
        return false;
      }

      if(video === "" || messages === "")
      {
        $(".err_video").html("<div class='alert alert-danger'>"+chat_err_msg+"</div>");
        return false;
      }

      var form = $(this)[0];
      var data = new FormData(form);
      data.append("recipient",recipient);
      data.append("device_key","{{ $device_key }}");
      data.append("type","video");
      sendMedia(recipient,data);
   });
  }

  function sendMedia(recipient,data)
  {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type : 'POST',
      processData : false,
      cache: false,
      contentType: false,
      url : "{{ url('send_chat_media') }}",
      data : data,
      dataType: 'json',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        if(result.response == true)
        {
          $(".error_send").html('');
          load_messages(result.to);
          $("#media_video, #media_audio, #media_image").modal('hide');
          $(".alert").delay(3000).slideDown(2000);
          chatScroll();
        }

        if(result.response == false)
        {
          $(".error").show();
          if(result.error !== false)
          {
            if(result.error.media !== null)
            {
               $(".error_media").html("<div>"+result.error.media+"</div>");
            }
            if(result.error.message !== null)
            {
               $(".error_msg").html("<div>"+result.error.message+"</div>");
            }
          }
          else
          {
             $(".error_send").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
          }
        }
        setTimeout(function(){
          $(".error").hide();
        },3500);
      },
      error: function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
    // return false;
  }

  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

</script>
@endsection