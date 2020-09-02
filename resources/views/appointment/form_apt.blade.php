@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->

<div class="container act-tel-apt wrapper">
      <h3 class="title">CREATE APPOINTMENT</h3>
      <h5 class="title">List : {{ $list_label }}</h5>

      <div class="col-md-12 relativ row">
        <input id="display_phone" type="text" class="form-control custom-select-apt" placeholder="Search by Name or Phone number, example : 628xxxx">
        <span class="icon-search"></span>

        <div id="display_data" class="search-result col-lg-12">
          <!-- ajax live serach result -->
        </div>
      </div>

      <form id="appt_form">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Name</label>
              <label class="col-sm-1 col-form-label">:</label>
              <div class="col-sm-7 text-left row">
                <input name="customer_name" class="form-control" readonly />
                <span class="error customer_name"></span>
              </div>
            </div> 

            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Phone Number</label>
              <label class="col-sm-1 col-form-label">:</label>
              <div class="col-sm-7 text-left row">
                <input name="phone_number" class="form-control" readonly />
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

            <div class="text-left error db_error"><!-- internal error --></div>

            <div class="text-left mt-4">
              <button id="submit" type="submit" class="btn btn-custom px-4">Submit</button>
            </div>

            <div class="text-left marketing">
              <div>Marketing by</div>
              <div><img src="{{asset('assets/img/marketing-logo.png')}}"/></div>
            </div>
      </form>
<!-- end container -->    
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <!-- <div class="modal-header">
        <h4 class="modal-title">Thank You</h4>
      </div> -->
      <div class="modal-body text-center">
          <span class="popup_modal_message"></span>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

  $(function () {
      $('#datetimepicker').datetimepicker({
        format : 'YYYY-MM-DD HH:mm',
        minDate: new Date(),
        // debug : true
      }); 
      callAjax();
      fillPhoneToForm();
      saveAppointment();
  });

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

  function callAjax()
  {
      $("#appt_form").hide();
      $("#display_phone").keyup(delay(function (e) {
        var val = $(this).val();
        $("#display_data").html('').hide();
        
        if(val.length !== 0)
        {
          $(".search-result").show();
          displayPhoneSearch(val);
        } 
        else
        {
          return false;
        } 
        //console.log('Time elapsed!', this.value);
      }, 800))
  }

  function displayPhoneSearch(val)
  {
      $.ajax({
        type : 'GET',
        url : '{{ url("display-customer-phone") }}',
        data : {value : val, list_id : {!! $list_id !!} },
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

  function fillPhoneToForm()
  {
    $("body").on("click",".adding-number",function(){
      var phone = $(this).attr('phone');
      var customer_name = $(this).attr('cname');
      var customer_id = $(this).attr('id');

      $("input[name='customer_name']").val(customer_name);
      $("input[name='phone_number']").val(phone);
      $("#submit").attr('data-id',customer_id);
      $("#appt_form").show();
      $("#display_data").html('').hide();
    });
  }

  function saveAppointment()
  {
    $("#appt_form").submit(function(e){
      e.preventDefault();
      var customer_id =  $("#submit").attr('data-id');
      var data = $(this).serializeArray();
      data.push({name:'list_id', value:{!! $list_id !!}}, {name:'campaign_id',value: {!! $id !!}},{name:'customer_id', value : customer_id} );

      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : 'POST',
        url : '{{ url("save-appt-time") }}',
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
          $(".search-result").hide();

          if(result.success == 1)
          {
            $(".popup_modal_message").html(result.message);
            $("#myModal").modal();
            
            clearForm();
            $(".error").hide();
            $("#appt_form").hide();
            setTimeout(function(){
              $("#myModal").modal('hide');
            },1500);
          }
          else
          {
            $(".error").show();
            $(".customer_name").html(result.customer_name);
            $(".phone_number").html(result.phone_number);
            $(".date_send").html(result.date_send);
            $(".db_error").html(result.customer_id);
            $(".db_error").html(result.message);
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

  function clearForm()
  {
      $("input").val('');
      $("input[name='delivery_time']").val('00:00');
  }

</script>

@endsection
