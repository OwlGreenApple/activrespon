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
                  <div id="{{ $row['id'] }}" class="col-md-12 mb-2 chat_room_box">
                   <div class="row chat-name">
                      <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                        <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                      </div>

                      <div class="col-lg-10 col-md-10 col-sm-10 col-10 pr-0">
                        <div class="chat-user">{{$row['name']}}</div>
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
            <div class="btn btn-warning btn-image mt-2 float-left">Send Image</div>
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

<!-- Modal Add Member -->
<div class="modal fade" id="add_member_form" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Add Member Chat
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="add_member_chat">
        <div class="modal-body">
            <span class="error_save"></span>
            <div class="form-group">
              <label><b>Email Member</b></label>
              <input type="email" class="form-control" name="email" />
            </div>
           <!--  <div class="form-group">
              <label><b>Phone Number</b></label>
              <input type="text" class="form-control" name="phone_number" />
            </div> -->
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Save</button>
          <button class="btn" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
      
  </div>
</div>

<!-- Modal Chat -->
<div class="modal fade" id="chat_room" role="dialog">
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
          <input type="text" name="messages" placeholder="Caption" class="form-control mt-2"/>
          <button type="submit" class="btn btn-success mt-2">Send Image</button>
        </form>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">

  var chat_err = "Please choose chat";
  var chat_err_msg = "Message or image shouldn't be empty";

  $(document).ready(function() 
  {
    emojiOne();
    sending_message();
    get_messages();
    // getNewMessages();
    openSendImage();
    image_preview();
    sendingImage();
    /*add_member_form();
    add_member();
    loadMember();
    delete_member();
    openChatBox();
    responseInvitation();
    delChat();*/
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
        load_messages(id)
        // getNewMessages();
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
          chatScroll();
          $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
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
    },1000);
  }

  function load_messages(id)
  {
    var data = {"chat_id" : id,"device_key" : "{{ $device_key }}"};
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_messages') }}",
      data : data,
      dataType: 'html',
      success: function(result){
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
       var id = $(".btn-send").attr('id');
       // console.log(id);
       if(id == undefined)
       {
          clearInterval(get_messages);
       }
       else
       {
          load_messages(id);
       }
       
    },3500);
  } 

  function openSendImage()
  {
    $( "body" ).on("click", ".btn-image", function() 
    {
      var id = $(".btn-send").attr('id',id); //recipient id
      $("#chat_room").modal({backdrop: 'static', keyboard: false});
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
      sendImage(recipient,data);
      // console.log(data);
    });
  }

  function sendImage(recipient,data)
  {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type : 'POST',
      url : "{{ url('send_chat_image') }}",
      data : data,
      processData : false,
      cache: false,
      contentType: false,
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
          $("#chat_room").modal('hide');
          $(".alert").delay(3000).slideDown(2000);
          chatScroll();
        }
        
        if(result.response == false)
        {
          $(".error_send").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
        }
      },
      error: function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  /*function getNotification()
  {
    var get_messages = setInterval(function(){
      $(".chat_room_box").each(function(i){
        var id = $(".chat_room_box").eq(i).attr('id');
        setTimeout(function(){
          load_messages(id);
        },500);
      });
    },2500);
  }*/

  /************/

  function delChat()
  {
    $("#del_chat").click(function()
    {
      var recipient_id = $(".btn-chat").attr('id');
      var warn = confirm("Are you sure to delete these chats? \n WARNIG : this cannot be undone");

      if(warn == false)
      {
        return false;
      }
      else
      {
        $.ajax({
          type : 'GET',
          url : '{{ url("delete_chat") }}',
          data : {'recipient_id':recipient_id},
          dataType : 'json',
          beforeSend: function() {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.response == 1)
            {
              load_messages(recipient_id);
              $(".error_send").html("<div class='alert alert-success'>Your data has been cleared up");
            }
            else
            {
              load_messages(recipient_id);
              $(".error_send").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
            }

            $(".alert").delay(2000).fadeOut(3000);
          },
          error : function(xhr)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
      }
      
    });
  }

  function responseInvitation()
  {
     $("body").on("click",".response",function(){
        var data = {
          'id_invited':$(this).attr('id'),
          'invitor':$(this).attr('data-invited'),
          'response':$(this).attr('data-status')
        };

        $.ajax({
          type : 'GET',
          url : '{{ url("response-invitation") }}',
          data : data,
          dataType : 'json',
          beforeSend: function() {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.response == 1)
            {
              loadMember();
              $(".error").html("<div class='alert alert-success'>Your data has been changed");
            }
            else
            {
              $(".error").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
            }
          },
          error : function(xhr)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
     });
  }

  function add_member_form()
  {
    $("#add_member").click(function(){
      $("#add_member_form").modal();
    });
  }

  function add_member()
  {
      $("#add_member_chat").submit(function(e){
        var data = $(this).serialize();
        add_member_save(data);
        e.preventDefault();
      });
  }

  function add_member_save(data){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
      type : 'POST',
      url : "{{ url('member_save') }}",
      data : data,
      dataType: 'json',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        if(result.response == true){
          loadMember();
          $("input").val('');
        }
        else if(result.response === 0){
          $(".error_save").html("<div class='alert alert-danger'>Invalid email</div>");
        }
        else if(result.response == "available"){
          $(".error_save").html("<div class='alert alert-danger'>Email has registered</div>");
        } 
        else if(result.response == "empty"){
          $(".error_save").html("<div class='alert alert-danger'>Email shouldn\'t be empty</div>");
        }
        else{
          $(".error").html("<div class='alert alert-danger'>Sorry, our server is too busy, please try again later.</div>");
          $("#add_member_form").modal('hide');
        }
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function loadMember(chat) {
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_member') }}",
      data : {'chat_room':chat},
      dataType: 'html',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        if(chat == 1)
        {
          $("#chat_room_member").html(result);
        }
        else
        {
          $("#chat_members").html(result);
        }
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function delete_member()
  {
    $( "body" ).on("click", ".delete-member", function() {
      var id = $(this).attr('id');
      var del_warning = confirm('Are you sure to delete this member?');

      if(del_warning == true)
      {
        $.ajax({
          type : "GET",
          url : "{{ url('delete-chat-member') }}",
          data : {"id":id},
          dataType : "json",
          beforeSend: function() 
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.response == true)
            {
              $(".error").html("<div class='alert alert-success'>Member has been deleted</div>");
              loadMember();
            }
            else
            {
              $(".error").html("<div class='alert alert-danger'>Sorry our server is too busy, please try again later.</div>");
            }
          },
          error : function(xhr){
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');
              console.log(xhr.responseText);
          }
        });
      }
      else
      {
          return false;
      }
      
    });
  }

  function openChatBox()
  {
    $( "body" ).on("click", ".chat-user", function() 
    {
      var id = $(this).attr('id'); //user id
      load_messages(id);
    });
  }

  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

</script>
@endsection