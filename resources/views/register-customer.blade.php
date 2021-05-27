<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title></title>
		<!-- Icon -->
		<link rel='shortcut icon' type='image/png' href="{{ asset('assets/img/favicon.png') }}">
    <!-- Scripts -->
    <script src="{{ asset('/assets/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('/assets/js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('/assets/css/nunito.css') }}" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('/assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/css/main.css') }}" rel="stylesheet" />
    <link href="{{ asset('/assets/css/subscribe.css') }}" rel="stylesheet" />

     <!-- Intl Dialing Code -->
    <link href="{{ asset('/assets/intl-tel-input/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/assets/intl-tel-input/js/intlTelInput.js') }}"></script> 

    <!-- Icomoon -->
    <link href="{{ asset('/assets/icomoon/icomoon.css') }}" rel="stylesheet" />

    {!! $pixel !!}

    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>"></script>
    
    <?php if ($listname=="q6y3juoz"){ ?>    
      <!-- Pixel Code for https://activproof.com/package/ -->
      <script async src="https://activproof.com/package/pixel/9jq97p9rzcukhh6z3p5cf7ro1kewcz9s"></script>
      <!-- END Pixel Code -->
    <?php } ?>    

    <!-- DOB -->
    <script type="text/javascript" src="{{ asset('assets/DOB-Picker/dobpicker.min.js') }}"></script>
    
</head>

<body class="act-tel-subscribe-page">

<!--Loading Bar-->
<div class="div-loading">
  <div id="loader" style="display: none;"></div>  
</div> 

<div id="app">
  <!--<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}">
             <!-- config('app.name', 'Laravel') 
          </a>
      </div>
  </nav>-->

  <main class="p-5">

    @if($status > 0)
    <div class="container">
        <div class="row justify-content-center">
          
           <div class="act-tel-subscribe col-lg-9">
              <div class="wrapper act-tel-subscribe-img">
                  {!! $content !!}
              </div>

              <div class="wrapper">
                <div id="message_id">
                <span class="error main"></span>

                <form class="add-contact" id="addcustomer">
                    <div class="form-group">
                      <label>Name*</label>
                      <input type="text" name="subscribername" class="form-control" />
                     <!--  <input type="hidden" id="city" name="city" /> -->
                      <span class="error name"></span>
                    </div>

                    @if($checkbox_lastname > 0)
                    <div class="form-group">
                      <label>{{ $label_last_name }}*</label>
                      <input type="text" name="last_name" class="form-control" />
                      <span class="error last_name"></span>
                    </div> 
                    @endif

                    <div class="prep1">
                      <div class="form-group">
                          <label>{{ $label_phone }}*</label>
                          <div class="col-sm-12 row">
                            <input class="form-control" id="phone" name="phone_number" type="tel">
                            <span class="error code_country"></span>
                            <span class="error phone"></span>
                          </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label>Email*</label>
                      <input type="email" name="email" class="form-control" />
                      <span class="error email"></span>
                    </div> 

                    <div class="form-group">
                      <label>Birthday @if($lists->is_validate_dob == 1)*@endif</label>
                      <div class="form-inline">
                          <select name="day" class="form-control mr-2" id="dobday"></select>
                          <select name="month" class="form-control mr-2" id="dobmonth"></select>
                          <select name="year" class="form-control" id="dobyear"></select>
                      </div>
                      <span class="error day"></span>
                      <span class="error month"></span>
                      <span class="error year"></span>
                    </div> 

                    <div class="form-group">
                      <label>Sex*</label>
                      <select name="sex" class="form-control">
                        <option value="male" selected>Male</option>
                        <option value="female">Female</option>
                      </select>
                      <span class="error sex"></span>
                    </div> 

                    @if($utils_city->count() > 0)
                    <div class="form-group">
                      <label>City @if($lists->is_validate_city == 1)*@endif</label>
                      <select name="city" class="form-control">
                        @foreach($utils_city as $row)
                          <option value="{{$row->category}}">{{$row->category}}</option>
                        @endforeach
                      </select>
                      <span class="error city"></span>
                    </div> 
                    @endif

                    <div class="form-group">
                      <label>Status*</label>
                      <select name="marriage_status" class="form-control">
                        <option value="single" selected>Single</option>
                        <option value="married">Married</option>
                      </select>
                      <span class="error marriage_status"></span>
                    </div> 

                    @if($utils_hobby->count() > 0)
                    <div class="form-group">
                      <label>Hobby @if($lists->is_validate_hobby == 1)*@endif</label><br/>
                      
                      @foreach($utils_hobby as $row)
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="hobby[]" value="{{$row->category}}">
                        <label class="form-check-label">{{ $row->category }}</label>
                      </div>
                      @endforeach
                      <div class="error hobby"></div>
                    </div> 
                    @endif

                    @if($utils_occupation->count() > 0)
                    <div class="form-group">
                      <label>Occupation @if($lists->is_validate_job == 1)*@endif</label><br/>

                      @foreach($utils_occupation as $row)
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="occupation[]" value="{{$row->category}}">
                        <label class="form-check-label">{{ $row->category }}</label>
                      </div>
                      @endforeach
                      
                      <div class="error occupation"></div>
                    </div> 
                    @endif

                    <div class="form-group">
                      <label>Religion*</label>
                      <select name="religion" class="form-control">
                        <option value="{{ $religion[0] }}" selected>{{ $religion[0] }}</option>
                        <option value="{{ $religion[1] }}">{{ $religion[1] }}</option>
                        <option value="{{ $religion[2] }}">{{ $religion[2] }}</option>
                        <option value="{{ $religion[3] }}">{{ $religion[3] }}</option>
                        <option value="{{ $religion[4] }}">{{ $religion[4] }}</option>
                        <option value="{{ $religion[5] }}">{{ $religion[5] }}</option>
                      </select>
                      <span class="error religion"></span>
                    </div> 


                    @if(count($additional) > 0)
                      @foreach($additional as $is_optional=>$row)
                          @foreach($row as $name=>$val)
                          <div class="form-group">
                              @if($is_optional > 0)
                                <label>{{$name}}*</label>
                              @else
                                <label>{{$name}}</label>
                              @endif
                           
                            @foreach($val as $key=>$col)
                                @if($key == 0)
                                   <input type="text" class="form-control" name="data[{{$name}}]" />
                                @else
                                  <select class="form-control" name="data[{{$name}}]">
                                      @foreach($col as $opt)
                                          <option value="{{$opt}}">{{$opt}}</option>
                                      @endforeach
                                  </select>
                                @endif
                            @endforeach
                             <span class="error {{$name}}"></span>
                            </div>
                        @endforeach
                        <!-- -->
                      @endforeach
                    @endif

                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                
                    <div class="text-left">
                      <button type="submit" class="btn btn-custom btn-lg">{{ $btn_message }}</button>
                    </div>

                    <span class="error captcha"></span>
                </form>
              <!-- END MESSAGE_ID -->
              </div>

              <div id="button_add_appointment"><a class="btn btn-custom" href="{{ $link_add_customer }}">Register Another</a></div>

              <div class="text-left marketing">
                <a href="https://activrespon.com" target="_blank">
                  <div>Marketing by</div>
                  <div><img src="{{asset('assets/img/marketing-logo.png')}}"/></div>
                </a>
              </div>
            </div>
            <!-- end wrapper -->
          </div>

        </div>
    </div>
    @else
       <div class="container">
        <div class="row justify-content-center">Please contact at : <a href="mailto:activrespon@gmail.com">activrespon@gmail.com</a></div>
       </div>
    @endif

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <!-- <div class="modal-header">
            <h4 class="modal-title">Thank You</h4>
          </div> -->
          <div class="modal-body text-center">
            Your data has stored!
          </div>
        </div>

      </div>
    </div>

  </main>
 </div>

<script src="{{ asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  $(document).ready(function() {
     /* $.get("https://api.ipdata.co?api-key=test", function(response) {
          // console.log(response.country_name);
          $("#city").val(response.city);
      }, "jsonp");    */
    
      //choose();
      grecaptcha.ready(function() {
        grecaptcha.execute("<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>", {action: 'contact_form'}).then(function(token) {
            $('#recaptchaResponse').val(token);
            // console.log(token);
        });
      });
      saveSubscriber();
      /*//codeCountry()
      putCallCode();*/
      fixWidthPhoneInput();
			<?php if(session('message')) { ?>
			alert("<?php echo session('message'); ?>");
			<?php }?>
      dob_picker();
  });

  function fixWidthPhoneInput()
  {
    $(".iti").addClass('w-100');
  }

  function dob_picker()
  {
    $.dobPicker({
      daySelector: '#dobday', /* Required */
      monthSelector: '#dobmonth', /* Required */
      yearSelector: '#dobyear', /* Required */
      dayDefault: 'Day', /* Optional */
      monthDefault: 'Month', /* Optional */
      yearDefault: 'Year', /* Optional */
      minimumAge: 12, /* Optional */
      maximumAge: 65 /* Optional */
    });
  }

    // Display Country

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

  function codeCountry()
  { 
    $("input[name='code_country']").click(function(){$("input[name='code_country']").val('');});

    $("body").on('keyup focusin',"input[name='code_country']",delay(function(e){
        $("input[name='code_country']").removeAttr('update');
        var search = $(this).val();
        $.ajax({
          type : 'GET',
          url : '{{ url("countries") }}',
          data : {'search':search},
          dataType : 'html',
          success : function(result)
          {
            $("#display_countries").show();
            $("#display_countries").html(result);
          },
          error : function(xhr)
          {
            console.log(xhr.responseText);
          }
        });
    },500));

     $("input[name='code_country']").on('focusout',delay(function(e){
        var update = $(this).attr('update');
        if(update == undefined)
        {
          $("input[name='code_country']").val('+62');
          $("#display_countries").hide();
        }
        
     },200));
  }

  function putCallCode()
  {
    $("body").on("click",".calling_code",function(){
      var code = $(this).attr('data-call');
      $("input[name='code_country']").attr('update',1);
      $("input[name='code_country']").val(code);
      $("#display_countries").hide();
    });
  }
  // End Display Country

  function saveSubscriber(){
      $("#button_add_appointment").hide();
      $("#addcustomer").submit(function(e){
          e.preventDefault();
          
          var code_country = $(".iti__selected-flag").attr('data-code');
          var data_country = $(".iti__selected-flag").attr('data-country');
          var data = $(this).serializeArray();
      
          data.push(
            {name:'code_country', value:code_country},
            {name:'data_country',value:data_country},
            {name:'listname',value:'{{ $listname }}'},
            {name:'listid',value:'{{ $id }}'},
            // {name:'city',value:$("#city").val()},
          );

          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
              type : "POST",
              url : "{{ route('savesubscriber') }}",
              data : data,
              beforeSend: function()
              {
                $('#loader').show();
                $('.div-loading').addClass('background-load');
              },
              success : function(result){
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');

                if(result.success == true){
                  $("#message_id").html(result.message);
                  if(result.is_appointment == 1)
                  {
                    $("#button_add_appointment").show();
                  }
                  /*  $(".modal-body > p").text(result.message);
                    $("#myModal").modal();*/
                    // setTimeout(function(){$("#myModal").modal('hide')} , 1500);   
                    // clearField();
                } else {
                    $(".error").html('');
                    $(".error").fadeIn('slow');
                    $(".name").text(result.name);
                    $(".last_name").text(result.last_name);
                    $(".email").text(result.email);
                    $(".phone").text(result.phone);
                    $(".code_country").text(result.code_country);
                    $(".captcha").text(result.captcha);
                    $(".error_list").text(result.list);
                    $(".error_list").text(result.list);
                    $(".main").html(result.main);
  
                    $(".year").html(result.year);
                    $(".month").html(result.month);
                    $(".day").html(result.day);
                    $(".sex").html(result.sex);
                    $(".city").html(result.city);
                    $(".marriage_status").html(result.marriage_status);
                    $(".religion").html(result.religion);
                    $(".hobby").html(result.hobby);
                    $(".occupation").html(result.occupation);

                    if(result.message !== undefined){
                         $(".error_message").html('<div class="alert alert-danger text-center">'+result.message+'</div>');
                    }
                    $.each(result.data, function(key, value) {
                        $("."+key).text(value);
                    })

                    $(".error").delay(5000).fadeOut(5000);
                }
              },
              error : function(xhr)
              {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                console.log(xhr.responseText);
              }
          });
          /*end ajax*/
      });
  }

  /* Clear / Empty fields after ajax reach success */
  function clearField(){
      $("input:not([name='listid'],[name='listname'])").val('');
      $(".error").html('');
  }
  
  /*function choose(){
    $("input[name=usertel]").prop('disabled',true);
    $(".ctel").hide();

    $(".dropdown-item").click(function(){
       var val = $(this).attr('id');

       if(val == 'ph')
        {
          $("input[name=phone]").prop('disabled',false);
          $("input[name=usertel]").prop('disabled',true);
          $(".cphone").show();
          $(".ctel").hide();
          $("#selectType").val("ph");
        }
        else {
          $("input[name=phone]").prop('disabled',true);
          $("input[name=usertel]").prop('disabled',false);
          $(".cphone").hide();
          $(".ctel").show();
          $("#selectType").val("tl");
        }
    });
  }*/
</script>

</body>
</html>
