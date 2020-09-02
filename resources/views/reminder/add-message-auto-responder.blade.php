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
      <input type="hidden" name="campaign_type" value="auto">
      <input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>">
      <input type="hidden" name="reminder_id" value="new">
      <div class="form-group row">
        <label class="col-6 col-sm-4 col-md-4 col-lg-3 col-form-label">Type Campaign :</label>
        <div class="col-6 col-sm-8 col-md-8 col-lg-9 py-2">
          <strong>Auto Schedule H+</strong>
        </div>
      </div>

      <div class="form-group row lists">
        <label class="col-6 col-sm-4 col-md-4 col-lg-3 col-form-label">Current List :</label>
        <div class="col-6 col-sm-8 col-md-8 col-lg-9 relativity">
          <a target="_blank" href="{{ url('list-edit') }}/{{ $currentlistid }}">{{ $currentlist }}</a>
           <!-- <select name="list_id" class="custom-select-campaign form-control">
              @if($lists->count() > 0)
                @foreach($lists as $row)
                  <option value="{{$row->id}}">{{$row->label}}</option>
                @endforeach
              @endif
           </select>
           <span class="icon-carret-down-circle"></span> -->
        </div>
      </div>

      <div class="form-group row">
        <label class="col-6 col-sm-4 col-md-4 col-lg-3 col-form-label">Campaign Name :</label>
        <div class="col-4 col-sm-8 col-md-8 col-lg-6">
          <div>{{ $campaign_name }}</div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Time to send Message :</label>
        <div class="col-sm-8 col-md-8 col-lg-9 relativity inputh">
          <div class="row">
            <select name="day" class="form-control col-8 col-sm-7 ml-3 mr-2">
              @for($x=1; $x<=100 ;$x++)
                @if($x == 1)
                  <option value="{{ $x }}">{{ $x }} day after registered</option>
                @else
                  <option value="{{ $x }}">{{ $x }} days after registered</option>
                @endif      
              @endfor
            </select>
            <input name="hour" type="text" class="timepicker form-control col-3 col-sm-3" value="00:00" readonly />
            <div class="error day"></div>
            <div class="error hour"></div>
          </div>
        </div>
      </div>

      <div class="form-group row">
				<label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Image :</label>
				<div class="col-sm-8 col-md-8 col-lg-9 relativity">
					<div class="custom-file">
						<input type="file" name="imageWA" class="custom-file-input pictureClass form-control" id="input-picture" accept="image/*">

						<label class="custom-file-label" for="inputGroupFile01">
						</label>
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
use min 5 spintax variations is recommended	<br>
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
        <button type="submit" class="btn btn-custom">Save</button>
        <button type="button" id="btn-clear" class="btn btn-custom">Clear</button>
      </div>

      <div class="form-group mt-2">
        <div class="row">
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
      <!-- end row -->
      </div>
      </div>
  </form>
</div>

<!-- Table -->
<div class="container act-tel-campaign table-responsive">
    <table class="table table-bordered mt-4">
      <thead class="bg-dashboard">
        <tr>
          <th class="text-center" style="width : 100px">Reminder Time</th>
          <th class="text-center" style="width : 100px">Time Sending</th>
          <th class="text-center">Reminder Messages</th>
          <th class="text-center" style="width : 60px">Edit</th>
          <th class="text-center" style="width : 60px">Delete</th>
        </tr>
      </thead>

      <tbody id="tbody-event">
      </tbody>
    </table>
</div>

<script type="text/javascript">
  function saveAutoResponder()
  {
    $("#save_campaign").submit(function(e){
      e.preventDefault();
      // var data = $(this).serializeArray();
			var form = $('#save_campaign')[0];
			var formData = new FormData(form);
        // formData.push({name:'list_id',value:'{!! $currentlistid !!}'},{ name:'campaign_name', value:'<php echo $campaign_name;?>'});
        formData.append('list_id','{!! $currentlistid !!}');
        formData.append('campaign_name',  '{!! $campaign_name !!}');

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("save-campaign")}}',
					cache: false,
					contentType: false,
					processData: false,
          data : formData,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            
            if(result.err == 0)
            {
               $("#notification").html('<div class="alert alert-success">'+result.message+'</div>');
               $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
               loadAutoResponder();
               $(".error").hide();
               $('html, body').animate({
                    scrollTop: $(".act-tel-campaign ").offset().top
               }, 1000);
            }
            else
            {
              $(".error").show();
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
    });
  }

  $(function () {
      $("#divInput-description-post").emojioneArea({
          pickerPosition: "right",
         // mainPathFolder : "{{url('/sxax')}}",
      });
  });

  function loadAutoResponder()
  {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'GET',
      url: "<?php echo url('/load-auto-responder');?>",
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
      }
    });
  }

  $(document).ready(function(){
    MDTimepicker();
    neutralizeClock();
    loadAutoResponder();
    clickButtonDelete();
    clickIconDelete();
    saveAutoResponder();
    clickButtonEdit();
    clickButtonClear();
    $("#btn-clear").hide();
    sendTestMessage();
    pictureClass();
  });

  function clickButtonEdit(){
    $("body").on('click','.icon-edit',function(e){
      e.preventDefault();
      $('input[name="reminder_id"]').val($(this).attr("data-id"));
      $('input[name="event_time"]').val($(this).attr("data-event_time"));
      if ( $(this).attr("data-days") == 0 ) {
        $('select[name="schedule"]').val(0).trigger('change');
      }
      else if ( $(this).attr("data-days") > 0 ) {
        $('select[name="schedule"]').val(2).trigger('change');
        $('select[name="day"]').val($(this).attr("data-days")).trigger('change');
      }
      else if ( $(this).attr("data-days") < 0 ) {
        $('select[name="schedule"]').val(1).trigger('change');
        $('select[name="day"]').val($(this).attr("data-days")).trigger('change');
      }
      $('input[name="hour"]').val($(this).attr("data-hour_time"));
      $('select[name="list_id"]').val($(this).attr("data-list_id"));
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText($(this).attr("data-message"));
      // $('textarea[name="message"]').val($(this).attr("data-message"));
      $("#btn-clear").show();
      $('html, body').animate({
          scrollTop: $(".act-tel-campaign ").offset().top
      }, 1000);
    });
  }

  function clickButtonClear(){
    $("body").on('click','#btn-clear',function(e){
      clearEdit();
    });
  }

  function clearEdit()
  {
    $('input[name="reminder_id"]').val("new");
    $('select[name="day"]').val($('select[name="day"]').prop("selectedIndex", 0).val());
    $('input[name="hour"]').val("00:00");
    $('select[name="list_id"]').val($('select[name="list_id"]').prop("selectedIndex", 0).val());
    $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
    $("#btn-clear").hide();
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
        url: "<?php echo url('/delete-auto-responder');?>",
        data: {
          id : $("#id_reminder").val(),
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
          if(data.status == 'success')
          {
            $("#notification").html('<div class="alert alert-success">Your schedule delete successfully</div>');
          }
          else
          {
            $("#notification").html('<div class="alert alert-danger">Sorry, currently our system is too busy, please try again later</div>');
          }
          clearEdit();
          $('html, body').animate({
              scrollTop: $(".act-tel-campaign ").offset().top
          }, 1000);
          loadAutoResponder();
        },
        error : function(xhr){
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
