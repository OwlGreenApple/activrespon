@extends('layouts.app')

@section('content')

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2 class="campaign">Campaigns</h2>
  </div>

  <div class="act-tel-dashboard-right">
     <a href="{{url('create-campaign')}}" class="btn btn-custom">Create Campaign</a>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container mt-2">
  <div class="act-tel-tab">
    <div class="row">
      <div class="input-group col-lg-4">
          <input type="text" class="form-control-lg col-lg-10 search-box" placeholder="Find a campaign by a name" >
          <span class="input-group-append">
            <div class="btn search-icon">
                <span class="icon-search"></span>
            </div>
          </span>
      </div> 

      <div class="col-lg-6"></div>

      @if(getMembership(Auth()->user()->membership) > 3) 
      <div class="input-group col-lg-2">
         <select id="campaign_option" class="custom-select-campaign form-control col-lg-10 relativity">
            <option value="all">All</option>
            <option value="1">Auto Responder</option>
            <option value="2">Broadcast</option>
         </select>
         <span class="icon-triangular"></span>
      </div>
      @endif

      <div class="clearfix"></div>

    </div>
  </div>
</div>

<!-- NUMBER -->
<div class="container">
  <div class="act-tel-tab">
      <div id="display_campaign">
        @if($campaign->count() > 0)
          @include('campaign.index')
        @else
          <div class="alert bg-dashboard cardlist">
            Currently you don't have any campaign, please click : <b>Create Campaign</b>.
          </div>
        @endif
      </div>
  </div>
</div>

  <!-- Modal Duplicate Auto Responder -->
  <div class="modal fade child-modal" id="modal_duplicate_reminder" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2 act-tel-campaign">
                  <form id="duplicate_reminder">
              
                    <div class="form-group">
                      <label>Auto Responder Name</label>
                      <input type="text" class="form-control" name="campaign_name" />
                      <span class="error campaign_name_reminder"></span>
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

  <!-- Modal Duplicate Broadast -->
  <div class="modal fade child-modal" id="modal_duplicate_broadcast" role="dialog">
    <div class="modal-dialog" style="max-width : 600px">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="form-group">
                 <div class="mb-2 act-tel-campaign">
                  <form id="duplicate_broadcast">

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Broadcast Name</label>
                      <div class="col-sm-8 relativity">
                        <input type="text" class="form-control" name="campaign_name" />
                        <span class="error campaign_name"></span>
                      </div>
                    </div>

                  <!--   <div class="form-group row">
                      <label class="col-sm-3 col-form-label">Broadcast Type :</label>
                      <div class="col-sm-9 relativity">
                         <div class="broadcast-type from-control"></div>
                      </div>
                    </div> -->

                    <div class="form-group row lists">
                      <label class="col-sm-4 col-form-label">Select List :</label>
                      <div class="col-sm-8 relativity">
                         <select name="list_id" class="custom-select-campaign form-control">
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

                    <div class="box-schedule"></div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Deliver Date :</label>
                      <div class="col-sm-8 relativity">
                        <input id="datetimepicker-date" type="text" name="date_send" class="form-control custom-select-campaign" autocomplete="off" />
                        <span class="icon-calendar"></span>
                        <span class="error date_send"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label"></label>
                      <div id="duplicate_bd" class="col-sm-8 relativity">
                          <div class="form-inline mt-2 ml-1 hbd">
                            <input type="checkbox" class="form-check-input" name="birthday" value="1" />
                            <label class="mr-2">Birthday</label>
                          </div> 
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Time to send Message :</label>
                      <div class="col-sm-8 relativity">
                        <input name="hour" id="hour" type="text" class="timepicker form-control" value="00:00" />
                        <span class="error hour"></span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Image :</label>
                      <div class="col-sm-8 relativity">
                        <div class="custom-file">
                          <input type="file" name="imageWA" class="custom-file-input pictureClass form-control" accept="image/*">

                          <label class="custom-file-label" for="inputGroupFile01">
                          </label>
                        </div>
                        <small>Leave blank if you want to duplicate previous image</small>
                        <small>Maximum image size is : <b>4Mb</b></small>
                        <div><small>Image Caption Limit is 1000 characters</small></div>
                        <span class="error image"></span>
                      </div>
                    </div>

                     <!-- TARGETTING FORM -->
                    <div id="target_box_duplicate">
                      <div class="target_append"><!-- to make element move either on edit or duplicate -->
                        <div class="form-group row istarget">
                          <label class="col-sm-4 col-md-4 col-lg-4 col-form-label">Targetting :</label>
                          <div class="col-sm-8 col-md-8 col-lg-8 relativity mt-2">
                            <input type="checkbox" class="form-check-input ml-1" name="is_targetting" value="1" />
                          </div>
                        </div>

                        <!-- TARGETTING -->
                        <div class="form-group row target">
                          <label class="col-sm-4 col-md-4 col-lg-4 col-form-label"><!--  --></label>

                            <div class="col-sm-8 col-md-8 col-lg-8 relativity">
                              <div class="form-inline">
                                <label class="mr-2">Sex :</label>
                                <select name="sex" class="form-control">
                                  <option value="all" >All</option>
                                  <option value="male">Male</option>
                                  <option value="female">Female</option>
                                </select>
                              </div> 

                              <div class="form-inline mt-2">
                                <label class="mr-2">Status :</label>
                                <select name="marriage_status" class="form-control">
                                  <option value="all">All</option>
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

                              <!-- province & city -->
                              <div class="form-inline mt-2">
                                <label class="mr-2">Province :</label>
                                <input name="province" class="form-control" autocomplete="disabled" />
                              </div> 
                            
                              <div class="live-search-wrapper ml-4">
                                 <div id="display_province" class="live-search"><!-- display ajax here --></div>
                              </div>

                              <div class="form-inline mt-2">
                                <label class="mr-2">City :</label>
                                <input name="city"class="form-control" autocomplete="disabled" />
                              </div> 

                              <div class="live-search-wrapper-city ml-4">
                                <div id="display_city" class="live-search"><!-- display ajax here --></div>
                              </div>
                              <!-- end province & city -->

                              <div class="form-inline mt-2">
                                <label class="mr-2">Religion :</b></label>
                                 <select name="religion" class="form-control text-capitalize">
                                    <option value="{{ $religion[0] }}">{{ $religion[0] }}</option>
                                    <option value="{{ $religion[1] }}">{{ $religion[1] }}</option>
                                    <option value="{{ $religion[2] }}">{{ $religion[2] }}</option>
                                    <option value="{{ $religion[3] }}">{{ $religion[3] }}</option>
                                    <option value="{{ $religion[4] }}">{{ $religion[4] }}</option>
                                    <option value="{{ $religion[5] }}">{{ $religion[5] }}</option>
                                  </select>
                              </div> 

                              <div class="form-inline mt-2 hobby-cover">
                                 <label class="mr-2">Hobby :</label>
                                 <span class="form-inline" id="hobby"></span>
                              </div> 

                              <div class="form-inline mt-2">
                                 <label class="mr-2">Occupation :</label>
                                 <span class="form-inline" id="job"></span>
                              </div> 
                              <!--  -->
                          </div>
                      <!--  -->
                      </div>
                    </div>
                     <!-- end target_box_duplicate -->
                    </div>

                    <!-- calculate -->
                    <div class="form-group row calc">
                      <label class="col-sm-4 col-md-4 col-lg-4 col-form-label">&nbsp;</label>
                      <div class="col-sm-8 col-md-8 col-lg-8 relativity mt-2">
                        <div class="mb-2" id="result_calculate"></div>
                        <button id="calculate" type="button" class="btn btn-info">Calculate</button>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Message :</label>
                      <div class="col-sm-12 pad-fix">
                        <textarea name="message" id="divInput-description-post" class="form-control"></textarea>
                        <span class="error message"></span>
                      </div>
                    </div>
                 
                    <div class="form-group text-right">
                      <button type="submit" class="btn btn-custom mr-1">Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                  <!-- close target -->
                </div>
                </div>
               
            </div>
        </div>
      </div>
      
    </div>
  </div>
  <!-- End Modal -->

  <!-- Modal Edit Broadcast -->
  <div class="modal fade child-modal" id="modal_edit_broadcast" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="msg col-lg-10 mb-2"><!-- --></div>
            <div class="form-group">
                 <div class="mb-2 act-tel-campaign">
                  <form id="edit_broadcast">
                    
                    <div class="form-group">
                      <label>Campaign Name :</label>
                      <input type="text" class="form-control" name="campaign_name" />
                      <span class="error campaign_name"></span>
                    </div>

                    <div class="form-group"> 
                      <label>Date Send :</label>
                      <div class="relativity">
                        <input id="date_send" type="text" name="date_send" class="form-control custom-select-campaign" autocomplete="off" />
                        <span class="icon-calendar"></span>
                      </div>
                      <span class="error event_time"></span>
                    </div>

                    <div class="form-group"> 
                      <div id="edit_bd" class="relativity">
                        <!-- birthday input move here if user open edit -->
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Time to send Message :</label>
                      <div class="relativity"> 
                        <input name="hour" id="time_sending" type="text" class="timepicker form-control" value="00:00" />
                        <span class="error time_sending"></span>
                      </div>
                    </div>

										<div class="form-group row">
											<label class="col-sm-3 col-form-label">Image :</label>
											<div class="col-sm-9 relativity">
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

                    <div id="targeting-box"><!-- apppend to class target --></div>

                    <!-- calculate -->
                    <div class="form-group row calc">
                      <label class="col-sm-4 col-md-4 col-lg-4 col-form-label">&nbsp;</label>
                      <div class="col-sm-8 col-md-8 col-lg-8 relativity mt-2">
                        <div class="mb-2" id="result_calculate_edit"></div>
                        <button id="calculate_edit" type="button" class="btn btn-info">Calculate</button>
                      </div>
                    </div>
                    
										<div class="form-group">
                      <label>Message :</label>
                      <textarea name="edit_message" id="edit_message" class="form-control"></textarea>
                      <span class="error edit_message"></span>
                     </div>
                 
                    <div class="form-group">
											<div class="text-right">
                        <button id="publish" type="button" data="publish" class="btn btn-primary mr-1">Publish</button>
												<button id="broadcast_edit" type="button" class="btn btn-custom mr-1">Save</button>
											</div>
										</div>
										
										<div class="form-group">
											<label >Send 1 test Message
												<span class="tooltipstered" title="<div class='panel-heading'>Send 1 test Message</div><div class='panel-content'>
													Test Message will be send immediately
													</div>">
													<i class="fa fa-question-circle "></i>
												</span>
											</label>
													<input type="text" id="phone" name="phone_number" class="form-control" />
													<span class="error code_country"></span>
													<span class="error phone_number"></span>
													<button type="button" class="btn btn-test">Send Test</button>
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
  var url_province = '{{ url("provinces") }}';
  var url_city = '{{ url("cities") }}';
</script>
<script src="{{ asset('/assets/js/mix.js') }}" type="text/javascript"></script>
<script type="text/javascript">

  /* Datetimepicker */
  $(function () {
      $('#datetimepicker').datetimepicker({
        format : 'YYYY-MM-DD HH:mm',
        minDate : new Date()
      });

      var date = new Date();
      date.setHours(0,0,0,0);
      $('#datetimepicker-date, #date_send').datetimepicker({
        format : 'YYYY-MM-DD',
        minDate : date
      }); 

      $("#divInput-description-post, #edit_message").emojioneArea({
          pickerPosition: "right",
          mainPathFolder : "{{url('')}}",
      });
  });

  var global_url = "{{ url('campaign') }}";

  $(document).ready(function(){
      editBroadcast();
      saveEditBroadcast(); 
      publishDraftBroadcast();
      displayCampaign();
      delBroadcast();
      delAutoResponder();
      searchCampaign();
      duplicateResponderForm();
      duplicateResponder();
      duplicateBroadcastForm();
      draftBroadCast();
      MDTimepicker(); 
      neutralizeClock();
      sendTestMessage();
      pictureClass();
      pagination();
      display_targeting();
      calculate_duplicate();
      calculate_edit();
      display_birthday();
  });

  function clearToolTip()
  {
    $('[data-toggle="tooltip"]').tooltip('hide');
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
          var type =  $("#campaign_option").val();
          var search = $(".search-box").val();
          loadPagination(url,type,search);
      });
  }

  function loadPagination(url,type,search) {
      $.ajax({
        beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
        url: url,
        data : {'type':type,'search':search},
      }).done(function (data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          clearToolTip();
          getActiveButtonByUrl(url);
          $('#display_campaign').html(data);
      }).fail(function (xhr,attr,throwable) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $('#display_campaign').html("<div class='alert alert-warning'>Sorry, Failed to load data! please contact administrator</div>");
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

  function displayCampaign() {
    $("#campaign_option").change(function(){
        var val = $(this).val();
        loadPagination(global_url,val);
    });
  }

  function searchCampaign()
  {
    $(".search-icon").click(function(){
      var search = $(".search-box").val();
      loadPagination(global_url,null,search);
    });
  }

  function duplicateResponderForm()
  {
    $("body").on("click",".responder_duplicate",function(){
        var id = $(this).attr('id');
        $("#duplicate_reminder").attr('data',id);
        $("#modal_duplicate_reminder").modal();
    });
  }

  function duplicateResponder()
  {
    $("#duplicate_reminder").submit(function(e){
        e.preventDefault();
        var campaign_id = $(this).attr('data');
        var option_position = $("#campaign_option").val();

        var data = $(this).serializeArray();
        data.push({name : 'id', value : campaign_id});

        $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: "{{ url('reminder-duplicate') }}",
        data: data,
        dataType: 'json',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          clearToolTip();

          if(result.success == 0)
          { 
            $("#duplicate_reminder :input").val('');
            $(".error").show();
            $(".campaign_name_reminder").html(result.campaign_name);
          }
          else
          {
            $(".error").hide();
            // alert(result.message);
            $("#modal_duplicate_reminder").modal('hide');
            $("#duplicate_reminder :input").val('');
          
            if(option_position == 'all')
            {
              loadPagination(global_url,null,null);
            }
            else
            {
              loadPagination(global_url,1,null);
            }        
          }
        },
        error : function(xhr,attr,throwable){
          $(".error").hide();
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });

    });
  }

   function editBroadcast()
  {
    $("body").on("click",".edit_campaign",function(){  
        var id = $(this).attr('id');
        var name = $(this).attr('data-name');
        var date = $(this).attr('data-date');
        var time = $(this).attr('data-time');
        var message = $(this).attr('data-message');
        var published = $(this).attr('data-publish');
        var list_id = $(this).attr('list_id');
          
        $("#broadcast_edit").attr('broadcast_id',id);
        $("input[name='campaign_name']").val(name);
        $("input[name='date_send']").val(date);
        $("#time_sending").val(time);
        $("#edit_message").emojioneArea()[0].emojioneArea.setText(message);
        $("#calculate_edit").attr('list_id',list_id);

        if(published == 1)
        {
            $("#publish").hide();
        }
        else
        {
            $("#publish").show();
        }

        $(".error").hide();
        broadcast_ajax_targetting(id);
        setTimeout(function(){
          $(".target_append").appendTo("#targeting-box");
          $(".hbd").appendTo("#edit_bd");
        },200);
        $("#modal_edit_broadcast").modal();
    });
  }

  function publishDraftBroadcast() 
  {
    $("#publish").click(function(){
      var publish = $(this).attr('data');
      var broadcast_id = $("#broadcast_edit").attr('broadcast_id');
      var form = $('#edit_broadcast')[0];
      var formData = new FormData(form);
      formData.append('broadcast_id',broadcast_id);
      formData.append('is_update',1);
      formData.append('publish',publish);
      updateBroadcast(formData);
    });
  }

  function saveEditBroadcast()
  {
    $("#broadcast_edit").click(function(){
      var broadcast_id = $(this).attr('broadcast_id');
      var form = $('#edit_broadcast')[0];
      var formData = new FormData(form);
      formData.append('broadcast_id',broadcast_id);
      formData.append('is_update',1);
      updateBroadcast(formData);
    });
  }

  function updateBroadcast(formData)
  {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'POST',
      url: "{{ url('broadcast-update') }}",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        clearToolTip();
        $(".alert").show();

        if(result.success == 0)
        { 
          $(".error").show();  
          $(".campaign_name").html(result.campaign_name);
          $(".time_sending").html(result.time_sending);
          $(".edit_message").html(result.edit_message);
          $(".event_time").html(result.event_time);
          $(".msg").html('<div class="alert alert-danger">'+result.broadcast_id+'</div>')
          $(".image").html(result.image);

          if(result.msg !== undefined)
          {
            $(".msg").html('<div class="alert alert-danger">'+result.msg+'</div>')
          }
        }
        else
        {
          $(".error").hide();
          $(".msg").html('<div class="alert alert-success">'+result.msg+'</div>');

          if(result.publish == true)
          {
            $("#publish").hide();
          }
          loadPagination(global_url,null,null);
        }
        $(".alert").delay(2000).hide(3000);
      },
      error : function(xhr,attr,throwable){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $(".error").hide();
        console.log(xhr.responseText);
      }
    });
  }

  // DUPLICATE
  function duplicateBroadcastForm()
  {
    $("body").on("click",".broadcast_duplicate",function(){
        var id = $(this).attr('id');
        broadcast_ajax_targetting(id);

        $("#duplicate_broadcast").attr('data',id);
        setTimeout(function(){
          $(".target_append").appendTo("#target_box_duplicate");
          $(".hbd").appendTo("#duplicate_bd");
        },200);
        $("#modal_duplicate_broadcast").modal();
    });
  }

  function broadcast_ajax_targetting(id)
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("broadcast-check") }}',
      data : {id : id},
      dataType : "json",
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
        if(result.is_targetting == 1)
        {
          $(".target").show();
          $(".calc").show();
        }
        else
        {
          $(".target").hide();
          $(".calc").hide();
        }

        broadcastFormArrange(result);
      },
      error: function(xhr,attr,throwable)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
      }
    });
  } 

  function calculate_duplicate()
  {
    $("#calculate").click(function(){
      var data = $("#duplicate_broadcast").serialize();
      calculate_targetting(data);
    });
  }

  function calculate_edit()
  {
    $("#calculate_edit").click(function(){
      var list_id = $(this).attr('list_id');
      var data = $("#edit_broadcast").serializeArray();
      data.push({name : 'list_id', value : list_id});
      calculate_targetting(data);
    });
  }

  function calculate_targetting(data) 
  {
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
              $("#result_calculate_edit").html("Total : <b>"+result.total+"</b>");
            }
            else
            {
              $("#result_calculate").html("<span class='error'>Please fill Date Send</span>");
              $("#result_calculate_edit").html("<span class='error'>Please fill Date Send</span>");
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
  }

  function broadcastFormArrange(result)
  {
      var box = wrapper = '';
      $("input[name='campaign_name']").val(result.campaign);
      $("input[name='date_send']").val(result.day_send);
      $("input[name='hour']").val(result.hour_time);
      $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(result.message);

      // TARGETTING LOGIC
      $("select[name='sex'] option[value="+result.sex+"]").prop('selected',true);
      $("select[name='marriage_status'] option[value="+result.marriage+"]").prop('selected',true);

      if(result.birthday == 1)
      {
        $("input[name='birthday']").prop('checked',true);
        $("input[name='date_send']").val('--- Disabled ---');
        $("input[name='date_send']").prop('disabled',true);
      }
      else
      {
        $("input[name='birthday']").prop('checked',false);
        $("input[name='date_send']").prop('disabled',false);
        $("input[name='date_send']").val(result.day_send);
      }

      if(result.is_targetting == 1)
      {
        $("input[name='is_targetting']").prop('checked',true);
      }
      else
      {
        $(".target").hide();
        $("input[name='is_targetting']").prop('checked',false);
      }

     /* var hasOption = $("select[name='city'] option[value="+result.city+"]").length;
      if(hasOption == 0)
      {
        var opt = "<option value='"+result.city+"' selected>"+result.city+"</option>";
        $(opt).appendTo("select[name='city']");
      }
      else
      {
        $("select[name='city'] option[value="+result.city+"]").prop('selected',true);
      }*/

      $("input[name='city']").val(result.city);
      $("input[name='province']").val(result.province);

      $("select[name='age_start'] option[value="+result.age_start+"]").prop('selected',true);
      $("select[name='age_end'] option[value="+result.age_end+"]").prop('selected',true);
      $("select[name='religion'] option[value="+result.religion+"]").prop('selected',true);

      $.each(result.hobbies,function(index, val){
        box += '<div class="form-check form-check-inline">';
        box += '<input checked class="form-check-input" type="checkbox" name="hobby[]" value="'+val+'">';
        box += '<span class="form-check-label">'+val+'</span>';
        box += '</div>';
      });

      $("#hobby").html(box);

      $.each(result.jobs,function(i, vals){
        wrapper += '<div class="form-check form-check-inline">';
        wrapper += '<input checked class="form-check-input" type="checkbox" name="occupation[]" value="'+vals+'">';
        wrapper += '<span class="form-check-label">'+vals+'</span>';
        wrapper += '</div>';
      });
      $("#job").html(wrapper);
     
      /*
      if(result.list_id > 0){
        $(".broadcast-type").html('Schedule Broadcast');
        $(".lists").show();
        $("select[name='list_id']").prop('disabled',false);
        $("select[name='list_id'] > option[value="+result.list_id+"]").prop('selected',true);
        $(".box-schedule").html('');
      }
      else if(result.list_id == 0 && result.group_name !== null)
      {
        $(".broadcast-type").html('Schedule Group');
        $(".lists").hide();
        $("select[name='list_id']").prop('disabled',true);

        box += '<div class="form-group row">';
        box += '<label class="col-sm-3 col-form-label">Telegram Group Name :</label>';
        box += '<div class="col-sm-9 relativity">';
        box += '<input type="text" value="'+result.group_name+'" name="group_name" class="form-control" />';
        box += '<span class="error group_name"></span>';
        box += '</div>';
        box += '</div>';
        $(".box-schedule").html(box);
      }
      else if(result.list_id == 0 && result.channel !== null)
      {
        $(".broadcast-type").html('Schedule Channel');
        $(".lists").hide();
        $("select[name='list_id']").prop('disabled',true);

        box += '<div class="form-group row">';
        box += '<label class="col-sm-3 col-form-label">Telegram Channel Name :</label>';
        box += '<div class="col-sm-9 relativity">';
        box += '<input type="text" value="'+result.channel+'" name="channel_name" class="form-control" />';
        box += '<span class="error channel_name"></span>';
        box += '</div>';
        box += '</div>';
        $(".box-schedule").html(box);
      } */
  }

  function display_birthday()
  {
    $("input[name='birthday']").click(function(){
      var checked = $(this).prop("checked");
      if(checked == true)
      {
        $("input[name='date_send']").prop('disabled',true);
        $("input[name='date_send']").val('--- Disabled ---');
      }
      else
      {
        $("input[name='date_send']").prop('disabled',false);
        $("input[name='date_send']").val('');
      }
    });
  }

  function display_targeting()
  {
    $("input[name='is_targetting']").click(function(){
      var checked = $(this).prop("checked");
      if(checked == true)
      {
        $(".target").show();
        $(".calc").show();
      }
      else
      {
        $(".target").hide();
        $(".calc").hide();      }
    });
  }

  function draftBroadCast()
  {
    $("#duplicate_broadcast").submit(function(e){
      e.preventDefault();
      var reminder_id = $(this).attr('data')
      var form = $("#duplicate_broadcast")[0];

      var data = new FormData(form);
      data.append('id', reminder_id);
      data.append('draft', true);
      duplicateBroadcast(data)
    });
  }

  function duplicateBroadcast(data)
  {
      var option_position = $("#campaign_option").val();
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: "{{ url('broadcast-duplicate') }}",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          clearToolTip();

          if(result.success == 0)
          {
            $(".error").show();
            $(".campaign_name").html(result.campaign_name);
            $(".group_name").html(result.group_name);
            $(".channel_name").html(result.channel_name);
            $(".date_send").html(result.date_send);
            $(".hour").html(result.hour);
            $(".message").html(result.message);
            $(".list_id").html(result.list_id);
            $(".image").html(result.image);
          }
          else
          {
            $(".error").hide();
            $(".message").html('<div class="alert alert-success">'+result.message+"</div>");
            $("#modal_duplicate_broadcast").modal('hide');
            $("#duplicate_broadcast:input").val('');

            if(option_position == 'all')
            {
              loadPagination(global_url,null,null);
            }
            else
            {
              loadPagination(global_url,2,null);
            }
          }
        },
        error : function(xhr,attr,throwable){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
  }

  function MDTimepicker(){
    $("body").on('focus','.timepicker, #time_sending',function(){
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

  function displayBroadcast()
  {
    $.ajax({
        type : 'GET',
        url : '{{ route("broadcastlist") }}',
        data : {type : 2},
        dataType : 'html',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $("#display_campaign").html(result);          
        },
        error : function(xhr,attributes,throwable){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert(xhr.responseText);
        }
    });
  }

  function displayAutoResponder()
  {
    $.ajax({
        type : 'GET',
        url : '{{ route("reminderlist") }}',
        data : {type : 1},
        dataType : 'html',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success : function(result){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');         
          $("#display_campaign").html(result); 
        },
        error : function(xhr,attributes,throwable){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
    });
  }

  function delBroadcast()
  {
    $("body").on("click",".broadcast-del",function(){
      var id = $(this).attr('id');
      var option_position = $("#campaign_option").val();
      var conf = confirm("Are you sure to delete this broadcast?"+"\n"+"WARNING : This cannot be undone");

      if(conf == true)
      {
        $.ajax({
          type : 'GET',
          url : '{{ url("broadcast-del") }}',
          data : {id : id},
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result)
          {
            // alert(result.message);
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            clearToolTip();

            if(option_position == 'all')
            {
              loadPagination(global_url,null,null);
            }
            else
            {
              displayBroadcast();
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

  function delAutoResponder()
  {
    $("body").on("click",".responder-del",function(){
      var id = $(this).attr('id');
      var option_position = $("#campaign_option").val();
      var conf = confirm("Are you sure to delete this auto responder?"+"\n"+"WARNING : This cannot be undone");

      if(conf == true)
      {
        $.ajax({
          type : 'GET',
          url : '{{ url("campaign-del") }}',
          data : {
            id : id,
            mode : "auto_responder"
          },
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

            if(option_position == 'all')
            {
              loadPagination(global_url,null,null);
            }
            else
            {
              displayAutoResponder();
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

  function sendTestMessage(){
    $("body").on("click",".btn-test",function(){
				var form = $('#edit_broadcast')[0];
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
