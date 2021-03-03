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
    </div>

    @if($error == null)

    <div class="col-md-12">
      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 px-0">

            <div class="col-lg-12 chat-roof row mx-0">
              <div class="col-lg-2 px-0"><img class="chat-wa-logo" src="{{ asset('assets/wachat/walogo.png') }}"/></div>
              <div class="col-lg-8">
                <h5><b>{{ $username }}</b></h5>
                <h6>{{ $phone }}</h6>
              </div>
              <div class="col-lg-2">
                <!-- <div class="chat-refresh"><a class="icon-spinner11"></a></div> -->
              </div>
            </div>

            <div class="chat-box" id="chat_room_member">
              <!-- search chat members -->
              <input placeholder="search name" type="text" class="form-control mb-3" id="search-user" />
              <!-- displaying chat members -->
              <div id="chat-members">@include('chats.members')</div>
            </div>

          </div>

          <div class="col-lg-8 col-md-8 col-sm-8 px-0 chat-bg">

            <div class="col-lg-12 chat-roof-right row mx-0">
              <div class="col-md-12 mb-2 chat_room_border">
                 <div class="row chat-name">
                    
                    <div class="col-lg-1 col-md-1 col-sm-1 col-1 pad-fix ml-3">
                      <img class="rounded-circle chat-roof-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                      <h5 id="chat_user"><!-- Display user name --></h5>
                    </div>

                    <!-- -->
                  </div>
                </div>
            </div>

            <div id="content_chat" class="chat-box">
              <div class="alert alert-success col-lg-6 text-center mx-auto">Please choose contact</div>
            </div>
          </div>
      </div>
    </div>

    <div class="col-lg-12 mt-2">
      <div class="row">
        <div class="col-lg-4">&nbsp;</div>
        <div class="col-lg-8 px-0">
          <span class="error_send"></span>
          
          <div>
            <textarea id="divInput-description-post" class="form-control"></textarea>
            <!-- <div media="video" class="btn btn-warning btn-media mt-2 float-left mr-2">Send Video</div> -->
            <!-- <div media="audio" class="btn btn-warning btn-media mt-2 float-left mr-2">Send Audio</div> -->
           <!--  <div class="btn btn-warning btn-media mt-2 float-left">Send Image</div> -->
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

 <script type="text/javascript" src="{{ asset('assets/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
 <!-- FOR PHP VARIABLE -->
 <script type="text/javascript">
   var notification_page = "{{ url('get-notification') }}";
   var device_id = "{{ $device_id }}";
   var device_key = "{{ $device_key }}";
 </script>
  <!-- //RUN SCRIPT AFTER PAGE LOADED COMPLETELY -->
 <script defer type="text/javascript" src="{{ asset('assets/js/chat-load-member.js') }}"></script>

<script type="text/javascript">

  var chat_err = "Please choose chat";
  var chat_err_msg = "Message or media shouldn't be empty";

  $(document).ready(function() 
  {
    emojiOne();
    sending_message();
    <?php if($error == null): ?>
    get_messages();
    getChatMembers();
    <?php endif; ?>
    openSendMedia();
    image_preview();
    // sendingImage();
    // sending_video();
    // sending_audio(); cancelled due API not supported
  });

  /* custom scrollbar */
 /* 
  ---DISABLED DUE CAUSE SCROLL DOWN SLOW---
 (function($){
      $(window).on("load",function(){
        
        $("#chat_room_member").mCustomScrollbar({
          autoHideScrollbar:true,
          theme:"minimal-dark"
        }); 
      
      });
    })(jQuery);*/

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
      pickerPosition: "bottom",
      events: {
        keypress: (editor, event) => {
          if (event.originalEvent.code === "Enter") {
            if (event.originalEvent.shiftKey === true) {
              // do nothing enter already inserts break line in caret position
            } else {
              // enter key without ctrl
              event.preventDefault(); // ignore line break
              // insert your code here
              trigger_message();
            }
          }
        },
      }
    });
  }

  // TO GET MESSAGES ACCORDING ON USER
  function get_messages()
  {
    $("body").on("click",".chat_room_box",function(){
        var id = $(this).attr('id');
        $(".btn-send").attr('id',id);
        $(".chat-note-"+id).hide();

        $(".chat-roof-image").css('visibility', 'visible');
        var get_name = $("#"+id+" .chat-user").text();
        $("#chat_user").html(get_name+'&nbsp;'+'('+id+')');

        var img = $("#"+id+" .chat-image").attr('src');
        $(".chat-roof-image").attr('src',img);

        $(".chat_room_box").removeClass('waselected');
        setTimeout(function(){
          $("#"+id).addClass('waselected');
        },100);

        var total_notif = parseInt($("#"+id).attr('total'));
        if(total_notif > 0)
        {
          removeNotification(id);
        }
        else
        {
          load_messages(id);
        }
    });
  }

  // TO REMOVE NOTIFICATION WHEN OWNER READ CHAT
  function removeNotification(sender)
  {
    $.ajax({
      async: false,
      type : 'GET',
      url : "{{ url('rm-notification') }}",
      data : {'device_id': '{{ $device_id }}',"sender" : sender},
      success: function(result)
      {
        $("#"+sender).attr('total',0);
      },
      complete: function()
      {
        load_messages(sender);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  // TO SEND MESSAGE
  function sending_message()
  {
    $(".btn-send").click(function(){
     trigger_message();
    });
  }

  // TO EXECUTE SEND MESSAGE
  function trigger_message()
  {
    var recipient = $(".btn-send").attr('id');
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
  }

  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

  /* GET CHAT MEMBERS */
  function getChatMembers()
  {
    $("#search-user").keyup(delay(function(e)
    {
      var name = $(this).val();
        searchChat(name);
    },750));
  }

  function searchChat(name)
  {
    $.ajax({
      type : 'GET',
      url : "{{ url('chat-members') }}",
      data : {'member':name},
      dataType: 'html',
      beforeSend: function() {
        if(name !== undefined)
        {
          if(name.length > 0){
            $("#chat-members").html('<div class="alert alert-warning col-lg-6 mx-auto">Loading...</div>');
          }
        }
      },
      success: function(result, textStatus, xhr) 
      {
        $("#chat-members").html(result);
      },
      error: function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
    //end ajax
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
        //get function get all messages
        if(result.response == true)
        {
          $(".error_send").html('');
          load_messages(result.to, null);
          $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
          chatScroll();
          searchChat();
          /*setTimeout(function(){
            searchChat();
          },500);*/
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
    var scrolls = $("#content_chat").prop("scrollHeight");
    $("#content_chat").scrollTop(scrolls);
  }

  function load_messages(id,load)
  {
    var data = {"chat_id" : id,"device_id":"{{ $device_id }}"};
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_messages') }}",
      cache: false,
      data : data,
      dataType: 'html',
      beforeSend: function() {
        //give loading text if not chatting
        if(load === undefined)
        {
          $("#content_chat").html('<div class="alert alert-warning col-lg-6 mx-auto">Loading...</div>');
        }
      },
      success: function(result)
      {
        $("#content_chat").html(result);
      },
      complete : function(xhr,status)
      {
        chatScroll();
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
        alert('Sorry, our server is too busy, please reload your browser');
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