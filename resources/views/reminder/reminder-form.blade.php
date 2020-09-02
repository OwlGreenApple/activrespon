@extends('layouts.app')

@section('content')

<!-- navbar -->
<div class="container mb-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a href="{{route('home')}}" class="nav-link">Back Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('reminder')}}" class="nav-link">Back Reminder</a>
                </li>
              </ul>
            </nav>
        </div>
    </div>
</div>
<!-- end navbar -->

<div class="container">
    <!-- add list-->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Create Reminder Schedule</b></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form id="get_template">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Choose Template</label>
                            <div class="col-md-6">
                                <select class="form-control" name="template_list" id="display-template">
                                  <option>Choose</option>
                                  @if($templates->count() > 0)
                                    @foreach($templates as $row)
                                      <option value="{{$row->id}}">{{$row->name}}</option>
                                    @endforeach
                                  @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning btn-sm">Add</button>
                            </div>
                        </div> 
                    </form>

                     <form method="POST" action="{{ route('reminderadd') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Lists Option</label>
                            <div class="col-md-6">

                                <select class="form-control display-package" name="list_id" id="display-template">
                                  @if($data->count() > 0)
                                    @foreach($data as $row)
                                      <option value="{{$row->id}}">{{$row->label}}</option>
                                    @endforeach
                                  @endif
                                </select>

                                 @if (session('error'))
                                    <div class="error">{{ session('error')->first('list_id') }}</div>
                                 @endif
                            </div>
                        </div> 

                        <div class="form-group row package">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Package <span class="package-name"></span></label>
                            <div class="col-md-6">
                                <select class="form-control" name="package" id="package">
                                    <!-- display option from javascript here -->
                                </select>
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Message</label>
                            <div class="col-md-6">
                                <textarea id="divInput-description-post" class="form-control" name="message"></textarea>
                                 @if (session('error'))
                                    <div class="error">{{ session('error')->first('message') }}</div>
                                 @endif
                             </div>
                             <div class="col-md-2"><input class="btn btn-default btn-sm" type="button" id="tagname" value="Add Name" /></div>
                        </div>   

                         <div class="form-group row mail_omni">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Mail Subject</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="mailsubject" />
                            </div>
                        </div> 

                        <div class="form-group row mail_omni">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Mail Message</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="mailmessage"></textarea>
                                 @if (session('error'))
                                    <div class="error">{{ session('error')->first('mailmessage') }}</div>
                                 @endif
                             </div>
                        </div> 

                         <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Set Event</label>
                            <div class="col-md-6">
                                <select class="form-control" name="is_event">
                                  <option value="0">Reminder</option>
                                  <option value="1">Event</option>
                                </select>
                            </div>
                        </div> 

                        <div class="form-group row event-counter">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Calendar</label>
                            <div class="col-md-6">
                              <input name="eventdate" class="form-control datepicker" />
                            </div>
                            <label for="name" class="col-md-4 col-form-label text-md-right">Set days before event</label>
                            <div class="col-md-6">
                                <select class="form-control" name="eventday">
                                  @php
                                  for($x=1;$x<=120;$x++){
                                   @endphp
                                    <option value="{{$x}}">-{{$x}}</option>
                                  @php  
                                  }
                                  @endphp
                                </select>
                                 @if (session('error'))
                                    <div class="error">{{ session('error')->first('eventday') }}</div>
                                 @endif
                            </div>
                        </div>

                        <div class="form-group row reminder-counter">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Add Day</label>
                            <div class="col-md-6">
                                <select class="form-control" name="day">
                                  @php
                                  for($x=1;$x<=120;$x++){
                                   @endphp
                                    <option value="{{$x}}">+{{$x}}</option>
                                  @php  
                                  }
                                  @endphp
                                </select>
                                 @if (session('error'))
                                    <div class="error">{{ session('error')->first('days') }}</div>
                                 @endif
                            </div>
                        </div>

                        <!-- submit button -->
                         <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Set Reminder
                                </button>
                            </div>
                        </div>
                     </form>
                     <!-- end form -->
                     
                </div>
            </div>
        </div>
    </div>
<!-- end container -->   
</div>


<!-- give emoji -->
 <script type="text/javascript">
    $("#divInput-description-post").emojioneArea({
        pickerPosition: "right",
        mainPathFolder : "{{url('')}}",
    });

     $(function(){
        $('#tagname').on('click', function(){
            var tag = '{name}';
            var cursorPos = $('#divInput-description-post').prop('selectionStart');
            var v = $('#divInput-description-post').val();
            var textBefore = v.substring(0,  cursorPos );
            var textAfter  = v.substring( cursorPos, v.length );
            $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(textBefore+ tag +textAfter );
        });
     }); 
</script>

<script type="text/javascript">
    $(document).ready(function(){
        displayTemplate();
        setEvent();
        datepicker();
        displayPackage();
    });

    function displayPackage()
    {
      var list_id = $(".display-package > option:selected").val();
      $(".display-package").change(function()
      {
        list_id = $(".display-package > option:selected").val();
        if(list_id == 17 || list_id == 18)
        {
          $(".package").show();
          $(".mail_omni").show();
        }
        else
        {
          $(".package").hide();
          $(".mail_omni").hide();
        }

        if(list_id == 17 )
        {
          $('#package').find('option').remove().end();
          $(".package-name").html('Omnilinkz');
          var elite2 = new Option("Beli 195rb Dapat 2 Bulan Elite", "package-elite-2", true, true);
          var elite3 = new Option("Beli 295rb Dapat 3 Bulan Elite", "package-elite-3", false, false);
          var elite5 = new Option("Beli 395rb Dapat 5 Bulan Elite", "package-elite-5", false, false);
          var elite7 = new Option("Beli 495rb Dapat 7 Bulan Elite", "package-elite-7", false, false);
          var sel = document.getElementById('package');
          sel.add(elite2,0);
          sel.add(elite3,1);
          sel.add(elite5,2);
          sel.add(elite7,3);
        }
        else if(list_id == 18)
        {
          $('#package').find('option').remove().end();
          $(".package-name").html('Omnifluencer');
          var premium1 = new Option("Beli 197rb Dapat 1 Bulan Premium", "package-premium-1", true, true);
          var premium2 = new Option("Beli 297rb Dapat 2 Bulan Premium", "package-premium-2", false, false);
          var premium4 = new Option("Beli 397rb Dapat 4 Bulan Premium", "package-premium-4", false, false);
          var premium6 = new Option("Beli 497rb Dapat 6 Bulan Premium", "package-premium-6", false, false);
          var sel = document.getElementById('package');
          sel.add(premium1,0);
          sel.add(premium2,1);
          sel.add(premium4,2);
          sel.add(premium6,3);
        }
        else
        {
          $('#package').find('option').remove().end();
          $(".package-name").html('');
        }
      });
      //APPLY WHEN BROWSER SUCCESSFULLY LOADED
        if(list_id == 17 || list_id == 18)
        {
          $(".package").show();
          $(".mail_omni").show();
        }
        else
        {
          $(".package").hide();
          $(".mail_omni").hide();
        }

        if(list_id == 17 )
        {
          $(".package-name").html('Omnilinkz');
        }
        else if(list_id == 18)
        {
          $(".package-name").html('Omnifluencer');
        }
        else
        {
          $(".package-name").html('');
        }
    }

    function datepicker(){
      $(".datepicker").datetimepicker();
    }

    /* Set option for event and reminder */

    function setEvent(){
      $(".event-counter").hide();
      $("select[name='is_event']").change(function(){
          var vals = $(this).val();
          if(vals == 0){
            $(".event-counter").hide();
            $(".reminder-counter").show();
          } else {
            $(".event-counter").show();
            $(".reminder-counter").hide();
          }
      });
    }

    /* Attach broadcast template into textarea message */
    function displayTemplate(){
        $("body").on('submit','#get_template',function(e){
            e.preventDefault();
            var id = document.getElementsByName("template_list")[0].value;
            $.ajax({
                type : 'GET',
                url : '{{route("displaytemplate")}}',
                data : {'id':id},
                dataType : "text",
                success : function(txt){
                    $("#divInput-description-post").emojioneArea()[0].emojioneArea.setText(txt);
                }
            });
        });
    }
</script>
@endsection
