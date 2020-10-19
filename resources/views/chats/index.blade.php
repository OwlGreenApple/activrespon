@extends('layouts.app')

@section('content')

<div class="container mb-5 main-cont">
  <div class="row">
    <div class="col-md-12">
      <h2><b>Orders</b></h2>  
      <h5>List of your WA chat members.</h5>
      <hr>
    </div>

    <div class="col-md-12">
      <button id="add_member" class="btn btn-primary">Add Member</button>
      <div class="error"><!-- error when unable to insert database --></div>
      <div class="card-body table-responsive">
        <div id="chat_members"><!-- data --></div>
      </div>
      <!-- <div class="alert bg-dashboard cardlist">
        You don't have any order yet, please make order <a href="{{ url('pricing') }}">Here</a>
      </div> -->
    </div>

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
            <div class="form-group">
              <label><b>Member Name</b></label>
              <input type="text" class="form-control" name="member_name" />
            </div>
            <div class="form-group">
              <label><b>Phone Number</b></label>
              <input type="text" class="form-control" name="phone_number" />
            </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Save</button>
          <button class="btn" data-dismiss="modal">Cancel</button>
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
          Chat 
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 chat-box px-0">
              <div class="col-md-12 mb-2">

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div> 

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div>

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div> 

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div>

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div> 

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div>

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div> 

                <div class="row chat-name">
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 pad-fix">
                    <img class="rounded-circle chat-image" alt="100x100" src="https://placehold.it/100x100" data-holder-rendered="true"/>
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-8 col-8 pr-0">
                    <div class="chat-user">Name</div>
                    <div class="chat-text-user">Available</div>
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-right pl-0">
                    <div class="chat-time">09:00</div>
                  </div>
                </div>

              </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 chat-box">
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div> 
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div>
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div> 
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div>
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div> 
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div>
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div> 
                <div class="alert alert-primary col-md-12 chat-text">You : aaaaaaaa</div>
                <div class="alert alert-success col-md-12 chat-text">Friend : cccccccc</div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 mt-2">
          <div class="row">
            <div class="col-lg-4">&nbsp;</div>
            <div class="col-lg-8">
              <form id="send-message">
                <textarea class="form-control"></textarea>
                <button align="right" class="btn btn-success btn-sm mt-2 float-right">Send</button>
                <div class="clearfix"></div>
              </form>
            </div>
          </div>
        </div>
      <!-- end modal body -->
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    add_member_form();
    add_member();
    loadMember();
    delete_member();
    openChatRoom();
  });

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
        else{
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
  }

  function loadMember() {
    $.ajax({
      type : 'GET',
      url : "{{ url('get_chat_member') }}",
      dataType: 'html',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#chat_members").html(result);
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
    $( "body" ).on( "click", ".btn-chat", function() {
     $("#chat_room").modal();
    });
  }


  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

</script>
@endsection