@extends('layouts.app')

@section('content')

  <!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>List Users</h2>
    <h4>Appointment Name : {{ $campaign_name }}</h4>
    <h5>List : <a target="_blank" href="{{ url('list-edit') }}/{{ $list_id }}">{{ $label }}</a></h5>
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
      <div class="row col-lg-5">

        <div class="col-lg-3 pad-fix"><a href="{{ url('list-apt') }}/{!! $campaign_id !!}/1" @if($active == true)class="act-tel-apt-create" @endif>QUEUE</a></div>

        <div class="col-lg-3 pad-fix"><a href="{{ url('list-apt') }}/{!! $campaign_id !!}/0" @if($active == false)class="act-tel-apt-create" @endif>DELIVERED</a></div>

      </div>

      <div class="mt-4" id="display_data">
       <!-- display table -->
      </div>

    </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="edit_appt" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Edit Appointment
        </h5>
      </div>

      <div class="modal-body col-lg-12">
        <form id="appt_form">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Name</label>
              <label class="col-sm-1 col-form-label">:</label>
              <div class="col-sm-7 text-left row">
                <input name="customer_name" class="form-control" />
                <span class="error customer_name"></span>
              </div>
            </div> 

            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Phone Number</label>
              <label class="col-sm-1 col-form-label">:</label>
              <div class="col-sm-7 text-left row">
                <input name="phone_number" class="form-control" />
                <span class="error phone_number"></span>
              </div>
            </div> 

            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Choose Appointment Time :</label>
              <label class="col-sm-1 col-form-label">:</label>
              <div class="col-sm-7 relativity text-left row">
                <input autocomplete="off" id="datetimepicker" type="text" name="date_send" class="form-control custom-select-campaign" />
                <span class="icon-calendar"></span>
                <span class="error date_send"></span>
              </div>
            </div>

            <input type="hidden" name="campaign_id" />
            <span class="error campaign_id"></span>
            <span class="error db_error"><!-- internal error --></span>

            <div class="text-right">
              <button id="button" type="submit" class="btn btn-warning mr-1">Save</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </form>

      </div>

    </div>   
  </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="modal_notif" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
        <span class="modal_notif"></span>
      </div>
    </div>  
    <!-- End Modal content-->
  </div>
</div>

<!-- Modal resend -->
<div class="modal fade" id="resend_popup" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="message_resend"></div>
      <div class="modal-header">
        <h5>Are you sure to resend appointment message?</h5>
      </div>
      <div class="modal-body">
         <button id="resend_message" class="btn btn-primary">Resend</button>
         <button class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    display_data();
    openEditForm();
    editContactAppointment();
    deleteAppointment();
  });

  function display_data()
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-table-apt") }}',
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
        $("#display_data").html(result);
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

  function openEditForm()
  {
    $("body").on('click','.icon-edit',function(){
      var id = $(this).attr('id');
      var date = $(this).attr('data-ev');
      var customer = $(this).attr('data-name');
      var phone = $(this).attr('data-phone');
      var customer_id = $(this).attr('data-customer-id');

      $("input[name='customer_name']").val(customer);
      $("input[name='phone_number']").val(phone);
      $("input[name='date_send']").val(date);
      $("input[name='campaign_id']").val(id);
      $("#button").attr('csid',customer_id);
      $("#button").attr('oldtime',date);
      $(".error").hide();
      $("#edit_appt").modal();
    });
  }

  function editContactAppointment()
  {
      $("#appt_form").submit(function(e){
          e.preventDefault();
          var data = $(this).serializeArray();
          data.push(
            {name:'customer_id', value:$("#button").attr('csid')},
            {name:'oldtime', value:$("#button").attr('oldtime')},
          )
          saveEditForm(data);
      });
  }

  function saveEditForm(data)
  {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type : 'POST',
      url : '{{ url("list-edit-apt") }}',
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

        if(result.success == 1)
        {
            $("#edit_appt").modal('hide');
            $(".error").hide();
            $(".modal_notif").html(result.message);
            $("#modal_notif").modal();
            display_data();
        }
        else
        {
            $(".error").show();
            $(".customer_name").html(result.customer_name);
            $(".phone_number").html(result.phone_number);
            $(".date_send").html(result.date_send);
            $(".campaign_id").html(result.campaign_id);
            $(".db_error").html(result.customer_id);
            $(".db_error").html(result.oldtime);
            if(result.message !== undefined)
            {
                $(".modal_notif").html(result.message);
                $("#modal_notif").modal();
            }
        }
        setTimeout(function(){
          $("#modal_notif").modal('hide');
        },2000);
        
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function deleteAppointment()
  {
    $("body").on("click",".icon-cancel",function(){
      /*var campaign_id = $(this).attr('id'); 
      var customer_id = $(this).attr('data-ev'); 
      var oldtime = $(this).attr('data-tm'); 
      var data = {campaign_id : campaign_id, customer_id : customer_id, oldtime : oldtime};*/
      var reminder_customer_id = $(this).attr('id');
      var data = {'reminder_customer_id' : reminder_customer_id}
      var warning = confirm('Are you sure to delete this list appointment?'+'\n'+'WARNING : This cannot be undone');

      if(warning == true)
      {
          exDeleteAppointment(data);
      }
      else
      {
          return false;
      }

    });
  }

  function exDeleteAppointment(data)
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("list-delete-apt") }}',
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
        if(result.success == 1)
        {
            $(".modal_notif").html(result.message);
            $("#modal_notif").modal();
            display_data();
        }
        else
        {
            $(".modal_notif").html(result.message);
            $("#modal_notif").modal();
        }
        setTimeout(function(){
          $("#modal_notif").modal('hide');
        },2000);
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