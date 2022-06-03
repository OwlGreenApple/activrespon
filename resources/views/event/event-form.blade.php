@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>SETUP EVENT</h2>
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

      <div class="form-group row event-time">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Event Time :</label>
        <div class="col-sm-8 col-md-8 col-lg-9 relativity">
          <input id="datetimepicker" type="text" name="event_time" class="form-control custom-select-campaign" autocomplete="off" />
          <span class="icon-calendar"></span>
          <span class="error event_time"></span>
        </div>
      </div>

      <div class="form-group row reminder">
        <label class="col-sm-4 col-md-4 col-lg-3 col-form-label">Select Day :</label>
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
          <span class="error image"></span><br/>
          <div><small>{!! Lang::get('custom.phone_package') !!}</small></div>
          <small>Ukuran Maksimal : <b>4Mb</b></small>
          <div><small>Batas Caption gambar 1000 karakter</small></div>
        </div>
      </div>

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

<!-- give emoji -->
 <script type="text/javascript">
    $("#divInput-description-post").emojioneArea({
        pickerPosition: "right"
    });
</script>

<script type="text/javascript">

    /* Datetimepicker */
     $(function () {
          $('#datetimepicker').datetimepicker({
            format : 'YYYY-MM-DD HH:mm',
            minDate : new Date()
          });
      });

    $(document).ready(function(){
        displayAddDaysBtn();
        MDTimepicker();
        neutralizeClock();
        saveCampaign();
        pictureClass();
        sendTestMessage();
    });

    function sendTestMessage()
    {
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

    function saveCampaign()
    {
      $("#save_campaign").submit(function(e){
        e.preventDefault();
          var form = $('#save_campaign')[0];
          var formData = new FormData(form);
          formData.append('campaign_type','event');
          formData.append('campaign_id','new');
          formData.append('reminder_id','new');
          
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
              else if(result.err == 'ev_err')
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
              }
              else
              {
                  /*$('#loader').hide();
                  $('.div-loading').removeClass('background-load');*/
                  $(".error").hide();
                  $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
                  // alert(result.message);
                  location.href='{{ url("event") }}';
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
