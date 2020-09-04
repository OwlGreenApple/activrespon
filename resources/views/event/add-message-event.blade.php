@extends('layouts.app')

@section('content')
<!-- Modal Delete Confirmation -->
<div class="modal fade" id="confirm-delete" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content content-premiumid">
      <div class="modal-header header-premiumid">
        <h5 class="modal-title" id="modaltitle">
          Confirmation Delete
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" name="id_reminder" id="id_reminder">

        <label>Are you sure want to <i>delete</i> this data ?</label>
        <br><br>
        <span class="txt-mode"></span>
        <br>
        
        <div class="col-12 mb-4" style="margin-top: 30px">
          <button class="btn btn-danger btn-block btn-delete-ok" data-dismiss="modal" id="button-delete-reminder">
            Yes, Delete Now
          </button>
        </div>
        
        <div class="col-12 text-center mb-4">
          <button class="btn  btn-block btn-delete-ok" data-dismiss="modal">
            Cancel
          </button>  
        </div>
      </div>
    </div>   
  </div>
</div>

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>ADD MESSAGE</h2>
  </div>

  <div class="clearfix"></div>
</div>

<!-- NUMBER -->
<div class="container act-tel-campaign">
  <div id="notification"><!-- notification --></div>

    <form id="save_campaign">
        <input type="hidden" name="campaign_type" value="event">
        <input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>">
        <input type="hidden" name="reminder_id" value="new">
        <div class="form-group row">
          <label class="col-6 col-sm-4 col-md-4 col-lg-3 col-form-label">Status Event :</label>
          <div class="col-6 col-sm-8 col-md-8 col-lg-9 py-2">
            <strong>@if($published == 0) Draft @else Published @endif</strong>
          </div>
        </div>

        <div class="form-group row lists">
          <label class="col-6 col-sm-4 col-md-4 col-lg-3 col-form-label">Current List :</label>
          <div class="col-6 col-sm-8 col-md-8 col-lg-9 relativity">
            <a target="_blank" href="{{ url('list-edit') }}/{{ $list_id }}">{{ $currentlist }}</a>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-7 col-sm-4 col-md-4 col-lg-3 col-form-label">Campaign Name :</label>
          <div class="col-3 col-sm-8 col-md-8 col-lg-6">
            <div>{{ $campaign_name }}</div>
          </div>
        </div>

        <div class="form-group row event-time">
          <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Event Time :</label>
          <div class="col-sm-8 col-md-8 col-lg-9">
            @if($date_event == null)
              <div id="new_event_time" class="relativity">
                <input id="datetimepicker" type="text" name="event_time" class="form-control custom-select-campaign" />
                <span class="icon-calendar"></span>
              </div>
            @else
              <b>{{ Date('Y-M-d h:i:s A',strtotime($date_event)) }}</b>
            @endif
            <div><span class="error event_time"></span></div>
          </div>
        </div>

        <div class="form-group row reminder">
          <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Select Reminder :</label>
          <div class="col-sm-8 col-md-8 col-lg-9 relativity">
             <select name="schedule" id="schedule" class="custom-select-campaign form-control">
                <option value="0">The Day</option>
                <option value="1">H-</option>
                <option value="2">H+</option>
             </select>
             <span class="icon-carret-down-circle"></span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Time to send Message :</label>
          <div class="col-sm-8 col-md-8 col-lg-9 relativity">
            <div class="inputh">
              <input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" />
            </div>
            <span class="error day"></span>
            <span class="error hour"></span>
          </div>
        </div>

        <div class="form-group row">
    			<label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Image :</label>
    			<div class="col-sm-8 col-md-8 col-lg-9 relativity">
    				<div class="custom-file">
    					<input type="file" name="imageWA" class="custom-file-input pictureClass form-control" id="input-picture" accept="image/*">

    					<label class="custom-file-label" for="inputGroupFile01">
    					</label>
              <small>Maximum image size is : <b>4Mb</b></small>
              <div><small>Image Caption Limit is 1000 characters</small></div>
              <span class="error image"></span>
    				</div>
    			</div>
        </div>

        <div class="form-group row">
          <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Message :
            <span class="tooltipstered" title="<div class='panel-heading'>Message</div><div class='panel-content'>
            You can use this as 'Personalization field' <br>
            [NAME] <br>
            [FIRSTNAME] <br>
            [PHONE] <br>
            [EMAIL] <br>
            Do NOT use : % or & character on your message<br><br>
Please use Spintax in your message<br>
example: {1|2|3} for 3 variations<br>
use min 5 spintax variations is recommended <br>
            </div>">
            <i class="fa fa-question-circle "></i>
           </span>
          </label>
          <div class="col-sm-8 col-md-8 col-lg-6">
            <textarea name="message" id="divInput-description-post" class="form-control"></textarea>
            <span class="error message"></span>
          </div>
        </div>

        <div class="text-right col-sm-12 col-md-12 col-lg-9">
          <button type="button" id="save" class="btn btn-custom">Save</button>
          <button type="button" id="btn-clear" class="btn btn-custom">Clear</button>
        </div>

    		<div class="form-group row mt-3">
    		<label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Send 1 test Message
    				<span class="tooltipstered" title="<div class='panel-heading'>Send 1 test Message</div><div class='panel-content'>
    					Test Message will be send immediately
    					</div>">
    					<i class="fa fa-question-circle "></i>
    				</span>
    		</label>
    		<div class="col-sm-8 col-md-8 col-lg-9 relativity">
          <div class="row">
            <div class="col-sm-9 col-lg-9">
      				<input type="text" id="phone" name="phone_number" class="form-control" />
      				<span class="error code_country"></span>
      				<span class="error phone_number"></span>
            </div>
            <div class="col-sm-3 col-lg-3 col-test">
              <button type="button" class="btn btn-test">Send Test</button>
            </div>
    		  </div>
        </div>
    	</div>
    </form>

</div>

<!-- Event List -->
<div class="container act-tel-campaign">
    <div id="tbody-event"></div>
</div>

<script type="text/javascript">

   /* Datetimepicker + emojione */
  $(function () {

     $('#datetimepicker').datetimepicker({
        format : 'YYYY-MM-DD HH:mm',
        minDate : new Date()
      });

    $("#divInput-description-post").emojioneArea({
        pickerPosition: "right",
    });
  });

  function saveUpdate()
  {
    $("#save").click(function()
    {
      var form = $('#save_campaign')[0];
      var formData = new FormData(form);
      var date_event = '{!! $date_event !!}';

      if(date_event == '')
      {
        var event_time = $("input[name='event_time']").val();
        formData.append('event_time',event_time);
      }
      else
      {
        formData.append('event_time','{!! $date_event !!}');
      }

      formData.append('list_id','{!! $currentlistid !!}');
      formData.append('campaign_name','{!! $campaign_name !!}');
      saveEvent(formData)
    });
  }

  function saveEvent(formData)
  {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type : 'POST',
      url : '{{url("save-campaign")}}',
      data : formData,
			cache: false,
			contentType: false,
			processData: false,
      dataType : 'json',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(result){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        loadEvent();

        if(result.err == 0)
        {
          $("#notification").html('<div class="alert alert-success">'+result.message+'</div>');
          $(".custom-file-label selected").html('');
          $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
          $("#new_event_time").html('<b>'+result.date_event+'</b>');
          $(".error").hide();
          clearForm();
        }
        else
        {
          $(".error").show();
          $(".event_time").html(result.event_time);
          $(".day").html(result.day);
          $(".hour").html(result.hour);
          $(".message").html(result.msg);
        }
      },
      error : function(xhr,attribute,throwable)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
      }
    });
    //ajax
  }

  function showReminder()
  {
      $("body").on('click','.icon-carret-down-circle',function(){
        var id = $(this).attr('id');
        $(".board-"+id).slideToggle(1000);
      });
  }
  
  function loadEvent()
  {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'GET',
      url: "<?php echo url('/load-event');?>",
      data : {
        campaign_id : "<?php echo $campaign_id; ?>"
      },
      dataType: 'text',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        var data = jQuery.parseJSON(result);
        $('#tbody-event').html(data.view);
      },
      error : function(xhr,attribute,throwable)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  $(document).ready(function(){
      displayAddDaysBtn();
      MDTimepicker();
      neutralizeClock();
      saveUpdate();
      loadEvent();
      clickButtonEdit();
      clickButtonDelete();
      clickIconDelete();
      clickButtonClear();
      $("#btn-clear").hide();
      sendTestMessage();
      pictureClass();
      showReminder();
  });

  function displayAddDaysBtn()
  {
    $(".add-day").hide();
    $("#schedule").change(function(){
      var val = $(this).val();
      var hmin = '';
      var hplus = '';

      var hday = '<input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" readonly />';

      hmin += '<select onmousedown="if(this.options.length > 8){this.size=8;}" onchange="this.size=0;" onblur="this.size=0;" name="day" class="form-control col-sm-7 float-left days delcols mr-3">';
      for(x=-1;x>=-90;x--){;
          hmin += '<option value='+x+'>'+x+' days before event</option>';
      };
      hmin += '<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />';

      hplus += '<select onmousedown="if(this.options.length > 8){this.size=8;}" onchange="this.size=0;" onblur="this.size=0;" name="day" class="form-control col-sm-7 float-left days delcols mr-3">';
      for(x=1;x<=100;x++) {
            hplus += "<option value="+x+">"+x+" days after event</option>";
      }
      hplus += '</select>';
      hplus +='<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
      ;

      if(val == 0){
        $(".inputh").html(hday);
      } else if(val == 1) {
         $(".inputh").html(hmin);
      } else {
         $(".inputh").html(hplus);
      }

    });
  }

  function MDTimepicker(){
    $("body").on('focus','.timepicker',function(){
        $(this).mdtimepicker({
          format: 'hh:mm',
        });
    });
  }

  /* prevent empty col if user click cancel on clock */
  function neutralizeClock(){
     $("body").on("click",".mdtp__button.cancel",function(){
        $(".timepicker").val('00:00');
    });
  }
  
  function clickButtonEdit(){
    $("body").on('click','.icon-edit',function(e){
      e.preventDefault();
      $('input[name="reminder_id"]').val($(this).attr("data-id"));
      // $('input[name="event_time"]').val($(this).attr("data-event_time"));
      if ( $(this).attr("data-days") == 0 ) {
        $('select[name="schedule"]').val(0).trigger('change');
        displayAddDaysBtn();
      }
      else if ( $(this).attr("data-days") > 0 ) {
        $('select[name="schedule"]').val(2).trigger('change');
        $('select[name="day"]').val($(this).attr("data-days")).trigger('change');
        displayAddDaysBtn();
      }
      else if ( $(this).attr("data-days") < 0 ) {
        $('select[name="schedule"]').val(1).trigger('change');
        $('select[name="day"]').val($(this).attr("data-days")).trigger('change');
        displayAddDaysBtn();
      }
      $('input[name="hour"]').val($(this).attr("data-hour_time"));
      $('select[name="list_id"]').val($(this).attr("data-list_id"));
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText($(this).attr("data-message"));
      $("#btn-clear").show();
       $('html, body').animate({
          scrollTop: $(".act-tel-campaign ").offset().top
      }, 1000);
    });
  }

  function clickButtonClear(){
    $("body").on('click','#btn-clear',function(e){
      clearForm();
    });
  }

  function clearForm()
  {
    $('input[name="reminder_id"]').val("new");
    // $('input[name="event_time"]').val("");
    $('select[name="list_id"]').val("");
    $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
    $("#btn-clear").hide();
  }
  
  function clickIconDelete(){
    $("body").on('click','.icon-delete',function(e){
      e.preventDefault();
      $("#id_reminder").val($(this).attr("data-id"));
    });
  }
  
  function clickButtonDelete(){
    $('#button-delete-reminder').click(function(){
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        url: "{{ url('event-del') }}",
        data: {id : $("#id_reminder").val(), campaign_id : '{!! $campaign_id !!}'},
        dataType: 'json',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(data) {
         
          if(data.status == 'success')
          {
            $("#notification_events").html('<div class="alert alert-success">'+data.message+'</div>');
            loadEvent();
          }
          else
          {
            $("#notification_events").html('<div class="alert alert-danger">'+data.message+'</div>');
          }

          if(data.remain_event == 1)
          {
              location.href='{{ url("add-message-event") }}/{!! $campaign_id !!}';
          }
          else
          {
              $('#loader').hide();
              $('.div-loading').removeClass('background-load');
              clearForm();
          }
        },
        error : function(xhr,attribute,throwable)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
    });
  }
	
  function sendTestMessage(){
    $("body").on("click",".btn-test",function(){
				var form = $('#save_campaign')[0];
				var formData = new FormData(form);
				formData.append('phone', $(".iti__selected-flag").attr('data-code')+$("#phone").val()); // added
				$.ajax({
						headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
						type : 'POST',
						url : '{{url("send-test-message")}}',
						data : formData,
						cache: false,
						contentType: false,
						processData: false,
						dataType : 'json',
						beforeSend: function()
						{
							// $('#loader').show();
							// $('.div-loading').addClass('background-load');
						},
						success : function(result){
							// $('#loader').hide();
							// $('.div-loading').removeClass('background-load');
							alert("Test Message Sent");
						},
						error : function(xhr,attribute,throwable)
						{
							// $('#loader').hide();
							// $('.div-loading').removeClass('background-load');
							console.log(xhr.responseText);
						}
				});
				//ajax
			});

  }

	function pictureClass(){
    // Add the following code if you want the name of the file appear on select
    $(document).on("change", ".custom-file-input",function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
	}
	
</script>  
<script src="{{ asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>
@endsection