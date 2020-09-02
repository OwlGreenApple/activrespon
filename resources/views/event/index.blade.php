@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2 class="campaign">Event List</h2>
  </div>

  <div class="act-tel-dashboard-right">
    <h5><a class="act-tel-apt-create" href="{{url('create-event')}}">SETUP EVENT</a></h5>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container mt-2">
  <div class="act-tel-tab">
    <div class="row">
      <div class="input-group col-lg-4">
          <input type="text" class="form-control-lg col-lg-10 search-box" placeholder="Find event" >
          <span class="input-group-append">
            <div class="btn search-icon">
                <span class="icon-search"></span>
            </div>
          </span>
      </div> 

      <div class="col-lg-6"></div>

      <div class="clearfix"></div>
    </div>
  </div>
  <div class="notification_err col-lg-12"></div>
</div>

<div class="container" id="display_list">
  <div class="act-tel-tab">
    @if(count($data) > 0)
      @include('event.event')
    @else
      <div class="alert bg-dashboard cardlist">
        Currently you don't have any event, please click : <b>SETUP EVENT</b>.
      </div>
    @endif
  </div>
</div>

<!-- Modal Duplicate Event -->
  <div class="modal fade child-modal" id="modal_duplicate" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2 act-tel-campaign">
                  <form id="duplicate">
                    
                    <div class="form-group">
                      <label>Event Name</label>
                      <input type="text" class="form-control" name="campaign_name" />
                      <span class="error campaign_name"></span>
                    </div>

                    <div class="form-group">
                      <label>Event Date & Time</label>
                      <div class="relativity">
                        <input id="datetimepicker" type="text" name="event_time" class="form-control custom-select-campaign" />
                        <span class="icon-calendar"></span>
                      </div>
                      <span class="error event_time"></span>
                    </div>

                    <div class="form-group">
                      <label>Select List :</label>
                      <div class="relativity">
                         <select onmousedown="if(this.options.length > 8){this.size=8;}" onchange="this.size=0;" onblur="this.size=0;" name="list_id" class="custom-select-campaign form-control">
                            @if(count($lists) > 0)
                              @foreach($lists as $row)
                                <option value="{{$row['id']}}">{{ $row['customer_count'] }} {{$row['label']}}</option>
                              @endforeach
                            @endif
                         </select>
                         <span class="icon-carret-down-circle"></span>
                         <span class="error list_id"></span>
                      </div>
                    </div>
                 
                    <div class="text-right">
                      <button type="submit" class="btn btn-custom mr-1">Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
               
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal Edit Campaign Name -->
  <div class="modal fade child-modal" id="edit-campaign" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2">
                  <form id="edit_campaign_name">
                    <label>Edit Event Name</label>
                    <div class="form-group">
                      <input type="text" class="form-control" name="campaign_name" />
                      <span class="error campaign_name"></span>
                    </div>
                    <input type="hidden" name="campaign_id" />
                    <span class="error campaign_id"></span>
                 
                    <div class="text-right">
                      <button  type="submit" class="btn btn-custom btn-sm mr-1">Save</button>
                      <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
                
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal Edit Event Date -->
  <div class="modal fade child-modal" id="edit-date" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="ev_notification"><!-- notification --></div>
            <div class="form-group">
                 <div class="mb-2">

                  <form id="edit_event_date">
                    <label>Edit Event Date</label>
                    <div class="form-group relativity">
                      <input type="text" class="form-control datetimepicker custom-select-campaign" name="event_time" autocomplete="off" />
                      <span class="icon-calendar"></span>
                      <span class="error event_time"></span>
                    </div>
                    <input type="hidden" name="campaign_id" />
                    <span class="error campaign_id"></span>
                 
                    <div class="text-right">
                      <button  type="submit" class="btn btn-custom btn-sm mr-1">Save</button>
                      <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    </div>
                  </form>

                </div>
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

<script type="text/javascript">

    /* $(function(){
        $('#tagname').on('click', function(){
            var tag = '{name}';
            var cursorPos = $('#divInput-description-post').prop('selectionStart');
            var v = $('#divInput-description-post').val();
            var textBefore = v.substring(0,  cursorPos );
            var textAfter  = v.substring( cursorPos, v.length );
            $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(textBefore+ tag +textAfter );
        });
     }); */

  $(function () {
      $('[data-toggle="tooltip"]').tooltip({
        'placement':'top'
      });       
  });

  $(document).ready(function(){
    // getText();
    emojiOne();
    pagination();
    editCampaignName();
    saveCampaignEditName();
    editEventDate();
    saveEventDateEdit();
    callSearch();
    MDTimepicker();
    duplicateEventForm();
    duplicateEvent();
    publishEvent();
    delEvent();
  });

  function clearToolTip()
  {
    $('[data-toggle="tooltip"]').tooltip('hide');
  }

  function editCampaignName()
  {
      $("body").on("click",".edit",function(){
        var id = $(this).attr('id');
        var name = $(this).attr('data-name');

        $("#edit-campaign").modal();
        $("input[name='campaign_name']").val(name);
        $("input[name='campaign_id']").val(id);
      });
  }  

  function editEventDate()
  {
      $("body").on("click",".edit_date",function(){
        var id = $(this).attr('id');
        var date = $(this).attr('data-name');

        $("#edit-date").modal();
        $("input[name='event_time']").val(date);
        $("input[name='campaign_id']").val(id);
      });
  } 

  function saveEventDateEdit()
  {
    $("#edit_event_date").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();

      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : 'POST',
        url : '{{ url("edit-event-date") }}',
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
          clearToolTip();

          if(result.success == 1)
          {
            $(".campaign_event_id-"+result.id).html(result.event_date);
            $(".error").hide();
            $(".ev_notification").html('<div class="alert alert-success">'+result.message+'</div>');
            $(".alert-success").delay(1000).hide();
            setTimeout(function(){
              $("#edit-date").modal('hide');
            },1000);
          }
          else
          {
            $(".error").show(); 
            $(".event_time").html(result.event_time);
            $(".campaign_id").html(result.campaign_id);
            if(result.message !== undefined)
            {
              $(".ev_notification").html('<div class="alert alert-success">'+result.message+'</div>');
            }
          }
          
        },
        error : function(xhr)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
      //end ajax
    });
  }

  function saveCampaignEditName()
  {
    $("#edit_campaign_name").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();

      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : 'POST',
        url : '{{ url("edit-campaign-name") }}',
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
          clearToolTip();

          if(result.success == 1)
          {
            $(".campaignid-"+result.id).html(result.campaign_name);
            $("#edit-campaign").modal('hide');
            $(".error").hide();
          }
          else
          {
            $(".error").show(); 
            $(".campaign_name").html(result.campaign_name);
            $(".campaign_id").html(result.campaign_id);
            if(result.error_server !== undefined)
            {
              alert(result.error_server);
            }
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

  function delay(callback, ms) 
  {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  } 

  function callSearch()
  {
    $(".search-box").keyup(delay(function(e){
      var val = $(this).val();
      searchEvent(val);
    },500))
  }

  function searchEvent(data)
  {
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type : 'POST',
        url : '{{ url("event-search") }}',
        data : {search : data},
        dataType : 'html',
        beforeSend : function(){
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $('#display_list').html(result); 
        },
        error : function(xhr)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
      //ajax
  }

  /* Datetimepicker */
  $(function () {
      $('#datetimepicker').datetimepicker({
        format : 'YYYY-MM-DD HH:mm',
        minDate : new Date()
      });
  });

  function MDTimepicker(){
    $("body").on('focus','.timepicker',function(){
        $(this).mdtimepicker({
          format: 'hh:mm',
        });
    });
  }

  function emojiOne(){
      $("#divInput-description-post").emojioneArea({
          pickerPosition: "right",
          mainPathFolder : "{{url('')}}",
      });
  }

   //ajax pagination
  function pagination()
  {
      $(".page-item").removeClass('active').removeAttr('aria-current');
      var mulr = window.location.href;
      getActiveButtonByUrl(mulr)
    
      $('body').on('click', '.pagination .page-link', function (e) {
          e.preventDefault();
          var url = $(this).attr('href');
          window.history.pushState("", "", url);
          loadPagination(url);
      });
  }

  function loadPagination(url) {
      $.ajax({
        beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
        url: url
      }).done(function (data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          clearToolTip();
          getActiveButtonByUrl(url);
          $('#display_list').html(data);
      }).fail(function (xhr,attr,throwable) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert("Sorry, Failed to load data! please contact administrator");
          console.log(xhr.responseText);
      });
  }

  function getActiveButtonByUrl(url)
  {
    var page = url.split('?');
    if(page[1] !== undefined)
    {
      var pagevalue = page[1].split('=');
      $(".page-link").each(function(){
         var text = $(this).text();
         if(text == pagevalue[1])
          {
            $(this).attr('href',url);
            $(this).addClass('on');
          } else {
            $(this).removeClass('on');
          }
      });
    }
    else {
        var mod_url = url+'?page=1';
        getActiveButtonByUrl(mod_url);
    }
  }

  //end ajax pagination

  function duplicateEventForm()
  {
    $("body").on("click",".event_duplicate",function(){
        var id = $(this).attr('id');
        var list_id = $(this).attr('data-list-id');
        $("#duplicate").attr('data',id);
        $("select[name='list_id'] > option[value='"+list_id+"']").prop('selected',true);
        $("#modal_duplicate").modal();
    });
  }

  function duplicateEvent()
  {
    $("#duplicate").submit(function(e){
        e.preventDefault();
        var campaign_id = $(this).attr('data');
        var option_position = $("#campaign_option").val();

        var data = $(this).serializeArray();
        data.push({name : 'id', value:campaign_id});

        $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: 'POST',
          url: "{{ url('event-duplicate') }}",
          data: data,
          dataType: 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success: function(result) 
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            clearToolTip();

            if(result.success == 0)
            { 
              $(".error").show();
              $(".campaign_name").html(result.campaign_name);
              $(".event_time").html(result.event_time);
              $(".list_id").html(result.list_id);
            }
            else
            {
              $(".error").hide();
              // alert(result.message);
              $("#modal_duplicate").modal('hide');
              $("input[name='campaign_name'],input[name='event_time']").val('');
              $("#duplicate:input").val('');
              displayEvent();
            }
          },
          error : function(xhr,attr,throwable){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            $(".error").hide();
            console.log(xhr.responseText);
          }
      });

    });
  }

  function publishEvent()
  {
    $("body").on("click",".published",function(){
      var campaign_id = $(this).attr('id');
      var warning = confirm('Are you sure to publish this event?'+"\n"+"WARNING : This cannot be undone");

      if(warning == true)
      {
        $.ajax({
          type: 'GET',
          url: "{{ url('event-publish') }}",
          data: {campaign_id : campaign_id},
          dataType: 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            clearToolTip();
            if(result.status == 'success')
            {
                $(".notification_err").html('<div class="alert alert-success">'+result.message+'</div>')
            }
            else
            {
                $(".notification_err").html('<div class="alert alert-danger">'+result.message+'</div>')
            }
            displayEvent();
          },
          error : function(xhr,attribute,throwable)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
        //end ajax
      }
      else
      {
        return false;
      }

    });
  }

  function delEvent()
  {
    $("body").on("click",".event-del",function(){
      var id = $(this).attr('id');
      var option_position = $("#campaign_option").val();
      var conf = confirm("Are you sure to delete this event?"+"\n"+"WARNING : This cannot be undone");

      if(conf == true)
      {
        $.ajax({
          type : 'GET',
          url : '{{ url("delete-event") }}',
          data : {id : id},
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.error == 0)
            {
              $(".notification_err").html('<div class="alert alert-success">'+result.msg+'</div>')
              displayEvent();
              clearToolTip();
            }
            else
            {
              $(".notification_err").html('<div class="alert alert-danger">'+result.msg+'</div>')
            }
          },
          error : function(xhr, attr, throwable)
          {
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

  function displayEvent()
  {
      $.ajax({
          type : 'GET',
          url : '{{ url("display-event") }}',
          dataType : 'html',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(data)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            $('#display_list').html(data);
          },
          error : function(xhr, attr, throwable)
          {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            console.log(xhr.responseText);
          }
        });
  }

    /*function getText(){
        $("body").on("click",".display_popup",function(){
            $("#myModal").modal();
            var id = $(this).attr('id');
            var txt = $(".get-text-"+id).text();
            $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(txt);
            $(".id_reminder").val(id);
        });
    }*/
</script>

@endsection
