@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>CREATE CAMPAIGN</h2>
  </div>

  <div class="clearfix"></div>
</div>

<!-- NUMBER -->
<div class="container act-tel-campaign">
  <form id="save_campaign">
      <input type="hidden" name="campaign_id" value="new">
      <input type="hidden" name="reminder_id" value="new">
      <div class="form-group row">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Name :</label>
        <div class="col-sm-8 col-md-8 col-lg-6">
          <input type="text" name="campaign_name" class="form-control" />
          <span class="error campaign_name"></span>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Campaign :
          <span class="tooltipstered" title="<div class='panel-heading'>Campaign type</div><div class='panel-content'>
					Broadcast <br>
					Auto Responder <br>
					Event
          </div>">
            <i class="fa fa-question-circle "></i>
          </span>
        </label>
        <div class="col-sm-8 col-md-8 col-lg-9">

          @if(getMembership(Auth()->user()->membership) > 3) 
          <div class="form-check form-check-inline">
            <label class="custom-radio">
              <input class="form-check-input" type="radio" name="campaign_type" id="inlineRadio3" value="broadcast" checked/>
              <span class="checkmark"></span>
            </label>
            <label class="form-check-label" for="inlineRadio3">Broadcast</label>
          </div>
          @endif

          <div class="form-check form-check-inline">
            <label class="custom-radio">
              <input class="form-check-input" type="radio" name="campaign_type" id="inlineRadio2" value="auto" @if(getMembership(Auth()->user()->membership) <= 3) checked @endif/>
              <span class="checkmark"></span>
            </label>
            <label class="form-check-label" for="inlineRadio2">Auto Schedule H+</label>
          </div>

        <!--   @if(getMembership(Auth()->user()->membership) > 1) 
          <div class="form-check form-check-inline">
            <label class="custom-radio">
              <input class="form-check-input" type="radio" name="campaign_type" id="inlineRadio1" value="event" />
              <span class="checkmark"></span>
            </label>
            <label class="form-check-label" for="inlineRadio1">Event</label>
          </div>
          @endif -->
          
          <!-- -->
        </div>
      </div>

      <!--
      <div class="form-group row broadcast-type">
        <label class="col-sm-3 col-form-label">Broadcast Type :</label>
        <div class="col-sm-9 relativity">
           <select name="broadcast_schedule" id="broadcast-schedule" class="custom-select-campaign form-control">
              <option value="0">Schedule Broadcast</option>
              <option value="1">Schedule Group</option>
              <option value="2">Schedule Channel</option>
           </select>
           <span class="icon-carret-down-circle"></span>
        </div>
      </div>
      -->

      <div class="form-group row lists">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Select List :</label>
        <div class="col-sm-8 col-md-8 col-lg-9 relativity">
           <select name="list_id" class="custom-select-campaign form-control">
              @if(count($lists) > 0)
                @foreach($lists as $row)
                  <option value="{{ $row['id'] }}">{{ $row['customer_count'] }} {{ $row['label'] }}</option>
                @endforeach
              @endif
           </select>
           <span class="icon-carret-down-circle"></span>
           <span class="error list_id"></span>
        </div>
      </div>

      <!-- <div class="box-schedule"></div> -->

      <div class="form-group row date-send">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Date Send :</label>
        <div class="col-sm-8 col-md-8 col-lg-9 relativity">
          <input id="datetimepicker-date" type="text" name="date_send" class="form-control custom-select-campaign" />
          <span class="icon-calendar"></span>
          <span class="error date_send"></span>
        </div>
        <div class="form-inline">
          <input type="checkbox" class="form-check-input" name="birthday" value="1" />
          <label class="ml-2">Birthday</label>
        </div> 

      </div>

      <div class="form-group row event-time">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Event Time :</label>
        <div class="col-sm-8 col-md-8 col-lg-9 relativity">
          <input id="datetimepicker" type="text" name="event_time" class="form-control custom-select-campaign" />
          <span class="icon-calendar"></span>
          <span class="error event_time"></span>
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
          <span class="inputh">
            <input name="hour" type="text" class="timepicker form-control" value="00:00" readonly />
          </span>
          <!-- <span class="inputh">
             <select name="day" class="form-control col-sm-7 float-left days delcols mr-3"> @for($x=1;$x<=100;$x++) 
                  <option value="{{ $x }}">{{ $x }} days after event</option>;
             @endfor
             </select>
            <input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />
          </span> -->
          <span class="error day"></span><br/>
          <span class="error hour"></span>
          <small>Please set your timezone on : <a target="_blank" href="{{ url('settings/?mod=1') }}">Settings</a></small>
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
          <small>Maximum image size is : <b>4Mb</b></small>
          <div><small>Image Caption Limit is 1000 characters</small></div>
          <span class="error image"></span>
				</div>
      </div>

      <!-- TARGETTING OPEN -->
      @if(getMembership(Auth()->user()->membership) > 3) 
      <div class="form-group row">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Targetting :</label>
        <div class="col-sm-8 col-md-8 col-lg-9">
          <div class="form-inline mt-2">
            <input type="checkbox" name='is_targetting' class="form-check-input" value="1" />
          </div>
        </div>
      </div>

      <!-- TARGETTING -->
      <div class="form-group row targeting">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label"><!--  --></label>
          <div class="col-sm-8 col-md-8 col-lg-9">
            <div class="form-inline">
              <label class="mr-2">Sex :</label>
              <select name="sex" class="form-control">
                <option value="all" selected>All</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div> 

            <div class="form-inline mt-2">
              <label class="mr-2">Status :</label>
              <select name="marriage_status" class="form-control">
                <option value="all" selected>All</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
              </select>
            </div> 

            <div class="form-inline mt-2">
              <label class="mr-2">Age :</label>
              <select name="age_start" class="form-control mr-2">
                <option value="all">All</option>
                @for($x=10;$x<100;$x++)
                  <option value="{{$x}}">{{$x}}</option>
                @endfor
              </select>
              <select name="age_end" class="form-control">
                <option value="all">All</option>
                @for($x=10;$x<100;$x++)
                  <option value="{{$x}}">{{$x}}</option>
                @endfor
              </select>
            </div> 

            <div class="form-inline mt-2">
              <label class="mr-2">City :</label>
              <select name="city" class="form-control">
                  <option value="all">All</option>
                  @if($utils_city->count() > 0)
                    @foreach($utils_city as $row)
                      <option class="text-capitalize" value="{{$row->category}}">{{$row->category}}</option>
                    @endforeach
                  @endif 
              </select>
            </div> 

            <div class="form-inline mt-2">
              <label class="mr-2">Religion :</b></label>
               <select name="religion" class="form-control text-capitalize">
                  <option value="{{ $religion[0] }}" selected>{{ $religion[0] }}</option>
                  <option value="{{ $religion[1] }}">{{ $religion[1] }}</option>
                  <option value="{{ $religion[2] }}">{{ $religion[2] }}</option>
                  <option value="{{ $religion[3] }}">{{ $religion[3] }}</option>
                  <option value="{{ $religion[4] }}">{{ $religion[4] }}</option>
                  <option value="{{ $religion[5] }}">{{ $religion[5] }}</option>
                </select>
            </div> 

            @if($utils_hobby->count() > 0)
            <div class="form-inline mt-2">
               <label class="mr-2">Hobby :</label>
                @foreach($utils_hobby as $row)
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="hobby[]" value="{{$row->category}}">
                  <span class="form-check-label">{{ $row->category }}</span>
                </div>
                @endforeach
            </div> 
            @endif

            @if($utils_occupation->count() > 0)
            <div class="form-inline mt-2">
               <label class="mr-2">Occupation :</label>
                @foreach($utils_occupation as $row)
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="occupation[]" value="{{$row->category}}">
                  <span class="form-check-label">{{ $row->category }}</span>
                </div>
                @endforeach
            </div> 
            @endif

            <div class="form-group mt-3">
              <div class="mb-2" id="result_calculate"><!-- Total : 10 --></div>
              <button id="calculate" type="button" class="btn btn-info">Calculate</button>
            </div> 
            <!--  -->
        </div>
      </div>
       <!-- END TARGETTING -->
        @endif

      <div class="form-group row">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Message :
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
        <div class="col-sm-8 col-md-8 col-lg-6">
          <textarea name="message" id="divInput-description-post" class="form-control"></textarea>
          <span class="error msg"></span>
        </div>
      </div>

			<div class="form-group row">

				<div class="text-right col-sm-12 col-md-12 col-lg-9">
					<button type="submit" class="btn btn-custom">Create</button>
				</div>
			</div>
			
      <div class="form-group row">
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

<script type="text/javascript">
    var date = new Date();
    date.setHours(0,0,0,0);

		/* Datetimepicker */
		$(function () {
        $('#datetimepicker').datetimepicker({
          format : 'YYYY-MM-DD HH:mm',
          minDate: new Date()
        }); 

        $('#datetimepicker-date').datetimepicker({
          format : 'YYYY-MM-DD',
          minDate: date
        });

        $("#divInput-description-post").emojioneArea({
            pickerPosition: "right",
            mainPathFolder : "{{url('')}}",
        });
    });

  $(document).ready(function()
  {
    openingPageType();
    displayOption();
    displayAddDaysBtn();
    MDTimepicker();
    neutralizeClock();
    saveCampaign();
    sendTestMessage();
    pictureClass();
    checkbox_value();
    calculate_targetting();
    display_targeting();
    targeting_start();
  });

  function targeting_start()
  {
    var checked = $("input[name='is_targetting']").prop("checked");
    targetting(checked);
  }

  function display_targeting()
  {
    $("input[name='is_targetting']").click(function(){
      var checked = $(this).prop("checked");
      targetting(checked)
    });
  }

  function targetting(checked)
  {
    if(checked == true)
    {
      $(".targeting").show();
    }
    else
    {
      $(".targeting").hide();
    }
  }

  function calculate_targetting() 
  {
    $("#calculate").click(function(){
      var data = $("#save_campaign").serialize();

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("calculate-user")}}',
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            
            if(result.status == 1)
            {
              $("#result_calculate").html("Total : <b>"+result.total+"</b>");
            }
            else
            {
              $("#result_calculate").html("<span class='error'>Please fill Date Send</span>");
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

  function checkbox_value()
  {
    $("input[name='birthday']").click(function(){
      var clicked = $(this).prop('checked');
      if(clicked == true)
      {
        $("input[name='date_send']").val('--- Disabled ---');
        $("input[name='date_send']").prop('disabled',true);
      }
      else
      {
        $("input[name='date_send']").val('');
        $("input[name='date_send']").prop('disabled',false);
      }
    });
  }

  function saveCampaign()
  {
    $("#save_campaign").submit(function(e){
      e.preventDefault();
      // var data = $(this).serialize();
				var form = $('#save_campaign')[0];
				var formData = new FormData(form);
				
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
						if(result.err == 'imgerr')
            {  
							  $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                $(".error").show();
                $(".image").html('Image width or image height can not be more than 2000px');
						}
          /*  else if(result.err == 'ev_err')
            {  
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                $(".error").show();
                $(".campaign_name").html(result.campaign_name);
                $(".list_id").html(result.list_id);
                $(".event_time").html(result.event_time);
                $(".day").html(result.day);
                $(".hour").html(result.hour);
                $(".msg").html(result.msg);
                $(".image").html(result.image);
            }*/
            else if(result.err == 'responder_err')
            {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                $(".error").show();
                $(".campaign_name").html(result.campaign_name);
                $(".list_id").html(result.list_id);
                $(".day").html(result.day);
                $(".hour").html(result.hour);
                $(".msg").html(result.msg);
								$(".image").html(result.image);
            }
            else if(result.err == 'broadcast_err')
            {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                $(".error").show();
                $(".campaign_name").html(result.campaign_name);
                $(".list_id").html(result.list_id);
                $(".group_name").html(result.group_name);
                $(".channel_name").html(result.channel_name);
                $(".date_send").html(result.date_send);
                $(".hour").html(result.hour);
                $(".msg").html(result.msg);
								$(".image").html(result.image);
            }
            else
            {
                $(".error").hide();
                $("input[name='campaign_name']").val('');
                $("input[name='group_name']").val('');
                $("input[name='group_name']").val('');
                $("input[name='channel_name']").val('');
                $("input[name='date_send']").val('');
                $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
                // alert(result.message);
                location.href='{{ url("campaign") }}';
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
    // BOLD LABEL
    $("label").css("font-weight","bold");
    var radio_option = $("input[name='campaign_type']:checked").val();
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
				var hplus = '';
        var option = '';

        for(x=1;x<=100;x++) {
          if(x == 1)
          {
            option += "<option value="+x+">"+x+" day after registered</option>";
          }
          else
          {
            option += "<option value="+x+">"+x+" days after registered</option>";
          }
        }

        hplus += '<select onmousedown="if(this.options.length > 8){this.size=8;}" onchange="this.size=0;" onblur="this.size=0;" name="day" class="form-control col-sm-7 float-left days delcols mr-3">';
        hplus += option;
        hplus += '</select>';
        hplus +='<input name="hour" type="text" class="timepicker form-control col-sm-4 delcols" value="00:00" readonly />'
        ;

        $("input[name=event_time]").prop('disabled',true);
        $(".event-time").hide();
        $("input[name=day_reminder]").prop('disabled',false);
        $(".reminder").hide();
        $(".inputh").html(hplus);
        //$(".broadcast-type").hide();
        $(".date-send").hide();
        $(".targeting").hide();
      }
      else {
        $("input[name=event_time]").prop('disabled',true);
        $("input[name=day_reminder]").prop('disabled',true);
        $(".event-time").hide();
        $(".reminder").hide();
        $(".date-send").show();
        $(".inputh").html(hday);
        $(".targeting").show();
        //$(".broadcast-type").show();
      }
  }

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

  function MDTimepicker()
  {
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
  /*function broadcastSchedule()
  {
      $(".broadcast-type").hide();

      $("#broadcast-schedule").change(function(){
        var val = $(this).val();
        var box = '';

        if(val == 0)
        {
            $(".lists").show();
            $("select[name='list_id']").prop('disabled',false);
            $(".box-schedule").html('');
        }
        else if(val == 1)
        {
            $(".lists").hide();
            $("select[name='list_id']").prop('disabled',true);

            box += '<div class="form-group row">';
            box += '<label class="col-sm-3 col-form-label">Telegram Group Name :</label>';
            box += '<div class="col-sm-9 relativity">';
            box += '<input type="text" name="group_name" class="form-control" />';
            box += '<span class="error group_name"></span>';
            box += '</div>';
            box += '</div>';
            $(".box-schedule").html(box);
        }
        else {
            $(".lists").hide();
            $("select[name='list_id']").prop('disabled',true);

            box += '<div class="form-group row">';
            box += '<label class="col-sm-3 col-form-label">Telegram Channel Name :</label>';
            box += '<div class="col-sm-9 relativity">';
            box += '<input type="text" name="channel_name" class="form-control" />';
            box += '<span class="error channel_name"></span>';
            box += '</div>';
            box += '</div>';
            $(".box-schedule").html(box);
        }
      });
  }*/
</script>
<script src="{{ asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>
@endsection
