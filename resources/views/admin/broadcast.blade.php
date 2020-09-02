@extends('layouts.admin')

@section('content')

<!-- NUMBER -->
<div class="container col-lg-6">
  <form id="broadcast_user">
    <div class="form-group">
      <label>To :</label>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="customRadio1" name="receiver" value="all" checked="checked">
        <label class="custom-control-label" for="customRadio1">All</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="customRadio2" name="receiver" value="active">
        <label class="custom-control-label" for="customRadio2">Active</label>
      </div>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" class="custom-control-input" id="customRadio3" name="receiver" value="inactive">
        <label class="custom-control-label" for="customRadio3">InActive</label>
      </div>
    </div>

    <div class="form-group">
      <label>Message :
        <span class="tooltipstered" title="<div class='panel-heading'>Message</div><div class='panel-content'>
						You can use this as 'Personalization field' <br>
						[NAME] <br>
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
      <div>
        <textarea name="message" id="divInput-description-post" class="form-control"></textarea>
        <span class="error msg"></span>
      </div>
    </div>

    <div class="form-group">
      <div id="loading" class="text-left">
        <button type="submit" class="btn btn-primary">Send Broadcast</button>
      </div>
    </div>
      <!-- -->
 </form>
</div>
			
      <!-- <div class="form-group row">
        <label class="col-sm-3 col-form-label">Send 1 test Message
					<span class="tooltipstered" title="<div class='panel-heading'>Send 1 test Message</div><div class='panel-content'>
						Test Message will be send immediately
						</div>">
						<i class="fa fa-question-circle "></i>
					</span>
				</label>
        <div class="col-sm-9 relativity">
						<input type="text" id="phone" name="phone_number" class="form-control" />
						<span class="error code_country"></span>
						<span class="error phone_number"></span>
						<button type="button" class="btn btn-test">Send Test</button>
        </div>
      </div> -->

<script type="text/javascript">

		/* Datetimepicker */
		$(function () {
       /* $('#datetimepicker').datetimepicker({
          format : 'YYYY-MM-DD HH:mm',
          minDate: new Date()
        }); 

        $('#datetimepicker-date').datetimepicker({
          format : 'YYYY-MM-DD',
          minDate: new Date()
        });*/

        $("#divInput-description-post").emojioneArea({
            pickerPosition: "bottom",
        });
    });

  $(document).ready(function(){
    sendBroadcast();
   /* displayOption();
    openingPageType();
    displayAddDaysBtn();
    MDTimepicker();
    neutralizeClock();*/
    /*sendTestMessage();
    pictureClass();*/
  });

  function sendBroadcast()
  {
    $("#broadcast_user").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("broadcast-user")}}',
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loading').html('<h5>Loading....</h5>');
          },
          success : function(result){
            $('#loading').html('<button type="submit" class="btn btn-primary">Send Broadcast</button>');
						 
             if(result.success == 1)
             {
                alert(result.message);
             }
             else
             {
                alert(result.receiver+"\n"+result.message);
             }
          },
          error : function(xhr,attribute,throwable)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
      });
      //ajax
    });
  }

  function displayOption(){
    $("input[name='campaign_type']").on('change',function(){
        var val = $(this).val();
        displayFormCampaign(val);
    });
  }

  function openingPageType()
  {
    var radio_option = $("input[name='campaign_type'] checked").val();
    displayFormCampaign(radio_option);
  }

  function displayFormCampaign(val)
  {
      var hday = '<input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" readonly />';


      if(val == 'event')
      {
				var hplus = '<select name="day" class="form-control col-sm-7 float-left days delcols mr-3"><?php for($x=1;$x<=30;$x++) {
              echo "<option value=".$x.">$x days after event</option>";
        }?></select>'+
        '<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
        ;

        $('select[name="schedule"] > option[value="0"]').prop('selected',true);
        $("input[name=event_time]").prop('disabled',false);
        $("input[name=day_reminder]").prop('disabled',false);
        $(".event-time").show();
        $(".reminder").show();
        $(".inputh").html(hday);
        //$(".broadcast-type").hide();
        $(".date-send").hide();
      }
      else if(val == 'auto'){
				var hplus = '<select name="day" class="form-control col-sm-7 float-left days delcols mr-3"><?php for($x=1;$x<=30;$x++) {
              echo "<option value=".$x.">$x days after subscribed</option>";
        }?></select>'+
        '<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
        ;

        $("input[name=event_time]").prop('disabled',true);
        $(".event-time").hide();
        $("input[name=day_reminder]").prop('disabled',false);
        $(".reminder").hide();
        $(".inputh").html(hplus);
        //$(".broadcast-type").hide();
        $(".date-send").hide();
      }
      else {
        $("input[name=event_time]").prop('disabled',true);
        $("input[name=day_reminder]").prop('disabled',true);
        $(".event-time").hide();
        $(".reminder").hide();
        $(".date-send").show();
        $(".inputh").html(hday);
        //$(".broadcast-type").show();
      }
  }

  function displayAddDaysBtn()
  {
    $(".add-day").hide();
    $("#schedule").change(function(){
      var val = $(this).val();

      var hday = '<input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" readonly />';

      var hmin = '<select name="day" class="form-control col-sm-7 float-left days delcols mr-3"><?php for($x=-90;$x<=-1;$x++) {
            echo "<option value=".$x.">$x days before event</option>";
      }?></select>'+
      '<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
      ;

      var hplus = '<select name="day" class="form-control col-sm-7 float-left days delcols mr-3"><?php for($x=1;$x<=100;$x++) {
            echo "<option value=".$x.">$x days after event</option>";
      }?></select>'+
      '<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
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
							if (result.status=="success"){
								alert("Test Message Sent");
							}
							if (result.status=="error"){
								if (result.phone!=""){
									alert("phone required");
								}
								if (result.msg!=""){
									alert("message required");
								}
								if (result.image!=""){
									alert("message required");
								}
							}
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
@endsection
