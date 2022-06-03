@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h3 class="campaign">Setup template messages</h3>
    <h5>Appointment name : <color>{{ $campaign_name }}</color></h5>
  </div>

  <div class="clearfix"></div>
</div>

<!-- NUMBER -->
<div class="container act-tel-apt">
  <div id="status_db"><!-- display status --></div>
  <div class="create bg-dashboard">

    <form id="save_template_apt" class="aptform">
     <div class="form-group row reminder">
        <label class="col-md-4 col-lg-3 col-form-label">Send message on :</label>
        <div class="col-md-8 col-lg-9 radio-schedule">
           <!-- <select name="schedule" id="schedule" class="custom-select-campaign form-control">
              <option value="0">The Day</option>
              <option value="1">H-</option>
           </select>
           <span class="icon-carret-down-circle"></span> -->

          <div class="form-check form-check-inline">
            <label class="custom-radio">
              <input class="form-check-input" type="radio" name="schedule" value="0" checked />
              <span class="checkmark"></span>
            </label>
            <label class="form-check-label">Appointment day</label>
          </div>

          <div class="form-check form-check-inline">
            <label class="custom-radio">
              <input class="form-check-input" type="radio" name="schedule" value="1" />
              <span class="checkmark"></span>
            </label>
            <label class="form-check-label">x Days before appointment</label>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-4 col-lg-3 col-form-label">Message will be send at :</label>
        <div class="col-md-8 col-lg-9 relativity">
          <span class="inputh">
            <input name="hour" type="text" class="timepicker form-control" value="00:00" readonly />
          </span>
          <span class="error day"></span><br/>
          <span class="error hour"></span>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Image :</label>
        <div class="col-sm-9 relativity text-left">
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
        <label class="col-sm-3 col-form-label">Message :  <span class="tooltipstered" title="<div class='panel-heading'>Info personalization field</div><div class='panel-content'>
                    [NAME] <br>
                    [FIRSTNAME] <br>
                    [PHONE] <br>
                    [EMAIL] <br/>
                    ADD new personalization for date & time appointment : <br/>
                    [DATE-APT] <br/>
                    [TIME-APT] 
                    </div>">
                    <i class="fa fa-question-circle "></i>
                    </span>
        </label>
        <div class="col-sm-9">
          <textarea name="message" id="divInput-description-post" class="form-control text-left"></textarea>
          <span class="error msg"></span>
        </div>
      </div>

      <div class="text-right col-sm-12">
        <button type="submit" id="btn-submit" class="btn btn-custom">Save</button>
        <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
      </div>
    </form>

    <div id="display_reminder_apt"><!-- display reminder --></div>

  </div>
</div>

<script type="text/javascript">

   /* Datetimepicker */
   $(function () {
        $('#datetimepicker').datetimepicker({
          format : 'YYYY-MM-DD HH:mm',
          minDate : new Date()
        }); 

        $('#datetimepicker-date').datetimepicker({
          format : 'YYYY-MM-DD',
        });

        $("#divInput-description-post").emojioneArea({
            pickerPosition: "top",
        });
    });

  $(document).ready(function(){
    displayAppointment();
    saveAppointment();
    showReminder();
    deleteAppointment();
    displayEditTemplateAppt();
    displayAddDaysBtn();
    MDTimepicker();
    neutralizeClock();
    cancelUpdate();
    pictureClass();
  });

  function cancelUpdate()
  {
      $("#cancel").hide();
      $("#cancel").click(function(){
        $('#btn-submit').html('Save').removeAttr('data-update');
        $('#btn-submit').removeAttr('data-oldtime');
        $('#btn-submit').removeAttr('data-oldday');
        $("input[name='schedule'][value='0']").prop('checked',true);
        buttonTime(0);
        $("input[name='hour']").val('00:00');
        $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
        $(this).hide();
      });
  }

  function displayEditTemplateAppt()
  {
      $("body").on("click",".icon-edit",function(){
        var id = $(this).attr('id'); 

        $.ajax({
          type : 'GET',
          url : '{{ url("edit-appt-template") }}',
          data : {id : id},
          dataType : 'json',
         /* beforeSend : function(){
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },*/
          success : function(result)
          {
           /* $('#loader').hide();
            $('.div-loading').removeClass('background-load');*/
            $('#btn-submit').html('Update Reminder').attr('data-update',id);
            $("#cancel").show();

            if(result.day < 0)
            {
                $("input[name='schedule'][value='1']").prop('checked',true);
                buttonTime(1,result.day);
                $('#btn-submit').attr('data-oldday',result.day);
            }
            else
            {
                $("input[name='schedule'][value='0']").prop('checked',true);
                buttonTime(0);
                $('#btn-submit').attr('data-oldday',0);
            }

            $('#btn-submit').attr('data-oldtime',result.time_send);
            $("input[name='hour']").val(result.time_send);
            $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(result.msg);
            $('html, body').animate({
                scrollTop: $(".create ").offset().top
            }, 1000);
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

  function deleteAppointment()
  {
      $("body").on('click','.icon-delete',function(){
          var id = $(this).attr('id');
          var notif = confirm('Are you sure want to delete this appointment reminder?');

          if(notif == true)
          {
              executeDeleteTemplateAppt(id)
          }
          else
          {
              return false;
          }
      });
  }

  function executeDeleteTemplateAppt(id)
  {
      $.ajax({
        type : 'GET',
        url : '{{ url("delete-appt-template") }}',
        data : {id : id},
        dataType : 'json',
        beforeSend : function(){
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $("#status_db").html('<div class="alert alert-success">'+result.message+'</div>');
          $("#cancel").trigger("click");
          if(result.success == 1)
          {
              displayAppointment();
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

  function saveAppointment()
  {
    $("#save_template_apt").submit(function(e){
      e.preventDefault();
      var is_update = $("#btn-submit").attr('data-update'); 
      var oldtime = $("#btn-submit").attr('data-oldtime');
      var oldday = $("#btn-submit").attr('data-oldday');

      var form = $("#save_template_apt")[0];
      var data = new FormData(form);

      data.append('campaign_id','{!! $id !!}');
      data.append('is_update',is_update);
      data.append('old_day',oldday);
      data.append('oldtime',oldtime);

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("save-template-appoinments")}}',
          data : data,
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

            if(result.success == 0)
            {  
                $(".error").show();
                $(".day").html(result.day);
                $(".hour").html(result.hour);
                $(".msg").html(result.msg);
                $("#status_db").html(result.message);
                $("#status_db").hide();
            }
            else
            {
                $(".error").hide();
                $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText('');
                $("#status_db").attr('class','status');
                $("#status_db").html(result.message);
                $(".custom-file-label").html('');
                $("#cancel").trigger("click");
                displayAppointment();
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

  function displayAppointment()
  {
      $.ajax({
        type : 'GET',
        url : '{{ url("display-template-apt") }}',
        data : {id : {!! $id !!}},
        dataType : 'html',
        beforeSend : function(){
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $("#display_reminder_apt").html(result);
        },
        error : function(xhr)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
  }

  function showReminder()
  {
      $("body").on('click','.icon-carret-down-circle',function(){
        var id = $(this).attr('id');
        $(".board-"+id).slideToggle(1000);
      });
  }

  function displayAddDaysBtn()
   {
      $(".add-day").hide();
      $("input[name='schedule']").change(function(){
          var val = $(this).val();
          buttonTime(val);
      });
   }

  function pictureClass(){
    // Add the following code if you want the name of the file appear on select
    $(document).on("change", ".custom-file-input",function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
  }

  function buttonTime(val,day)
  {
        var hmin = '';
        var hday = '<input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" readonly />';
        var options = '';

        for(x=-1;x>=-30;x--){
          if(x == day)
          {
            options += '<option value='+x+' selected>'+x+' days before appointment</option>';
          }
          else
          {
            options += '<option value='+x+'>'+x+' days before appointment</option>';
          }
        }

       hmin += '<select name="day" class="form-control col-sm-7 float-left days mr-3">';
       hmin += options;
       hmin +='</select>';
       hmin += '<input name="hour" type="text" class="timepicker form-control col-sm-4" value="00:00" readonly />';

        if(val == 0){
          $(".inputh").html(hday);
        } else if(val == 1) {
           $(".inputh").html(hmin);
        }
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

</script>
@endsection
