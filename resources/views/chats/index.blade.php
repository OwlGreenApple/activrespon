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
                  <div class="col-md-12 mb-2 chat_room_box">
                   <div class="row chat-name">
                      <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                        <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                      </div>

                      <div class="col-lg-10 col-md-10 col-sm-10 col-10 pr-0">
                        <div id="{{ $row['id'] }}" class="chat-user">{{$row['name']}}</div>
                        <div class="chat-text-user"><!-- Available --></div>
                      </div>

                      <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                        <div class="chat-time"></div>
                      </div>
                      <!-- -->
                    </div>
                  </div>
                @endforeach
              @endif

            </div>

          </div>

          <div id="content_chat" class="col-lg-8 col-md-8 col-sm-8 chat-box">
            <!-- displaying chat messages -->
            <div class="col-md-6"><div class="alert alert-primary chat-text">chats</div></div>
            <div class="col-md-6 ml-auto text-right"><div class="alert alert-success chat-text">chats2 test test</div></div>
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
            <button type="button" align="right" class="btn btn-success btn-sm mt-2 float-right btn-send">Send</button>

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
  <div class="modal-dialog chat-size">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Chat Box
        </h5>

      </div>
      <div class="modal-body">
        
      <!-- end modal body -->
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    add_member_form();
    add_member();
    // loadMember();
    delete_member();
    openChatRoom();
    openChatBox();
    emojiOne();
    sending_message();
    responseInvitation();
    delChat();
  });

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

  function emojiOne()
  {
    $("#divInput-description-post").emojioneArea({
      placeholder: "Type a message",
      pickerPosition: "bottom"
    });
  }

  function sending_message()
  {
    $(".btn-send").click(function(){
      var recipient = $(this).attr('id');
      var messages = $("#divInput-description-post").emojioneArea()[0].emojioneArea.getText();
      sendMesssage(recipient,messages);
    });
  }

  function sendMesssage(recipient,messages)
  {
    var data = {
      "recipient":recipient,
      "messages":messages,
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
          load_messages(result.recipient);
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

  function load_messages(user_recipient)
  {
    var data = {"user_recipient" : user_recipient};
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_messages') }}",
      data : data,
      dataType: 'html',
      success: function(result){
        $("#content_chat").html(result);
        var scrolls = $("#content_chat").prop("scrollHeight");
        $("#content_chat").scrollTop(scrolls);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
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

  function openChatRoom()
  {
    $( "body" ).on("click", ".btn-chat", function() 
    {
      var id = $(this).attr('id'); //recipient id
      $("#chat_room").modal({backdrop: 'static', keyboard: false});
      $(".btn-send").attr('id',id);
      loadMember(1);
      load_messages(id);

      setTimeout(function(){
        getNewMessages(id);
      },300);
      
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

   function getNewMessages(recipient_id)
  {
      var get_messages = setInterval(function(){
        load_messages(recipient_id);
      },2500);

      $("#close_chat").click(function(){
          $("#chat_room").modal('hide');
          clearInterval(get_messages);
      });
  }

  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

</script>
@endsection