@extends('layouts.app')

@section('content')

  <!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>List Users</h2>
    <h5>Campaign Name : {{ $campaign_name }}</h5>
    <h5>List Name : <a target="_blank" href="{{ url('list-edit') }}/{{ $list_id }}">{{ $list_name }}</a></h5>
  </div>
  <div class="clearfix"></div>
</div>
<!-- 
<div class="container act-tel-tab">
  <div class="input-group col-lg-4">
      <input type="text" class="form-control-lg col-lg-10 search-box" placeholder="Find customer by name" >
      <span class="input-group-append">
        <div class="btn search-icon">
            <span class="icon-search"></span>
        </div>
      </span>
  </div> 
</div> -->

<div class="container act-tel-apt">
    <div class="col-lg-12">
      <div class="message_campaign"><!-- messages --></div>
      <div class="row col-lg-5">

        <div class="col-lg-3 pad-fix"><a href="{{ url('list-campaign') }}/{!! $campaign_id !!}/{{ $is_event }}/1" @if($active == true)class="act-tel-apt-create" @endif>QUEUE</a></div>

        <div class="col-lg-3 pad-fix"><a href="{{ url('list-campaign') }}/{!! $campaign_id !!}/{{ $is_event }}/0" @if($active == false)class="act-tel-apt-create" @endif>DELIVERED</a></div>

      </div>

      <div class="mt-4">
        @if($is_event == 'broadcast')
          <div id="broadcast_table"></div>
        @elseif($is_event == 1)
          <div id="event_table"></div>
        @elseif($is_event == 0)
          <div id="responder_table"></div>
        @else
          <div class="alert bg-dashboard cardlist">
            No Data Available
          </div>
        @endif
      </div>
     

    </div>
</div>

<!-- Modal open message -->
  <div class="modal fade child-modal" id="campaign_message" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header"><h3>Message :</h3></div>
        <div class="modal-body">
           <div id="display_message"></div>
        </div>
        <div class="modal-footer" id="foot">
          <button class="btn btn-primary" data-dismiss="modal">
            Close
          </button>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

<script type="text/javascript">
  $(document).ready(function(){
    $('#datetimepicker').datetimepicker({
        format : 'YYYY-MM-DD HH:mm',
        minDate : new Date()
    }); 
    display_broadcast_data();
    data_event();
    data_auto_schedule();
    deleteCampaign();
    openCampaignMessage();
  });

  function openCampaignMessage()
  {
    $("body").on("click",".open_message",function(){
      var message = $(this).attr('data-message')
      $("#display_message").html(message);
      $("#campaign_message").modal();
    });
  }

  // FOR EVENT AND AUTO SCHEDULE
  function data_auto_schedule()
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-datatable-campaign") }}',
      data : {campaign_id : {!! $campaign_id !!}, active : {!! $active !!} },
      dataType : 'html',
      beforeSend : function(){
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#responder_table").html(result);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function data_event()
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-event-campaign") }}',
      data : {campaign_id : {!! $campaign_id !!}, active : {!! $active !!} },
      dataType : 'html',
      beforeSend : function(){
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#event_table").html(result);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function display_broadcast_data()
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-broadcast-campaign") }}',
      data : {campaign_id : {!! $campaign_id !!}, active : {!! $active !!} },
      dataType : 'html',
      beforeSend : function(){
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#broadcast_table").html(result);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
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

  function deleteCampaign()
  {
    $("body").on("click",".icon-cancel",function(){
      var is_broadcast = $(this).attr('data-broadcast');
      var data;

      if(is_broadcast == 1)
      {
        var broadcast_customer_id = $(this).attr('id');
        data = {'broadcast_customer_id' : broadcast_customer_id, 'is_broadcast' : 1};
      }
      else
      {
        var reminder_customer_id = $(this).attr('id');
        data = {'reminder_customer_id' : reminder_customer_id,'is_event' : '{!! $is_event !!}'};
      }
     
      var warning = confirm('Are you sure to cancel this user?'+'\n'+'WARNING : This cannot be undone');

      if(warning == true)
      {
          exDeleteCampaign(data);
      }
      else
      {
          return false;
      }

    });
  }

  function exDeleteCampaign(data)
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-delete-campaign") }}',
      data : data,
      dataType : 'json',
      beforeSend : function(){
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        var table = $("#list_campaign").DataTable();

        if(result.success == 1)
        {
            if(result.broadcast == 1)
            {
              display_broadcast_data();
            }
            else if(result.campaign == 1)
            {
              data_event();
            }
            else
            {
              data_auto_schedule();
            }
            $(".message_campaign").html('');
        }
        else
        {
            $(".message_campaign").html('<div class="alert alert-danger">Unable to cancel your campaign, sorry our server is too busy.</div>');
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

</script>
@endsection