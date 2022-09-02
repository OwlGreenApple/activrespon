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
        <input type="hidden" name="id_phone_number" id="id_phone_number">

        <label><h4>Apakah anda yakin untuk <i>menghapus</i> no telepon ini ?</h4></label>
        <br><br>
        <span class="txt-mode"></span>
        <br>

        <div class="col-12 mb-4" style="margin-top: 30px">
          <button class="btn btn-danger btn-block btn-delete-ok" data-dismiss="modal" id="button-delete-phone">
            Ya, Hapus Sekarang
          </button>
        </div>

        <div class="col-12 text-center mb-4">
          <button class="btn  btn-block btn-delete-ok" data-dismiss="modal">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Start to connect-->
<div class="modal fade" id="modal-start-connect" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content content-premiumid">
      <div class="modal-header header-premiumid">
        <h5 class="modal-title" id="modaltitle">
          Connect Whatsapp Anda
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        <input type="hidden" name="id_phone_number" id="id_phone_number">

        <label><h4><b style="color:#e3342f;">Perhatian</b> <br>Sebelum connect ke server kami,<br>
        Anda harus memiliki <b>"Profile Image"</b> pada setting Whatsapp
        </h4></label>
        <br><br>
        <!--<span class="txt-mode"></span>-->
        <img src="{{url('assets/img/hint-setting.png')}}" class="img img-fluid">
        <br>

        <span>
          <h4><b style="color:#e3342f;">PERINGATAN DARI WHATSAPP!</b></h4>
          <p><h5>
          Aktifitas spam, broadcast yang berlebihan ataupun memberikan pesan yang tidak diinginkan penerima dapat mengakibatkan akun di banned.

          Segala akibat yang timbul adalah tanggung jawab masing-masing user.

          Keputusan banned adalah sepenuhnya hak prerogratif dari Whatsapp dan tidak dapat diganggu gugat.
        </h5>
        </p>
        </span>

        <div class="form-check">
          <input class="form-check-input" id="flexCheckDefault" type="checkbox" name="agreement"/>
          <label class="form-check-label" for="flexCheckDefault">
           <h5> Dengan mencentang box ini berarti anda telah: <b>membaca</b>, <b>mengerti</b> dan <b>setuju</b> dengan ketentuan di atas.</h5>
          </label>
        </div>

        <div id="display_agreement" class="col-12 mb-4" style="margin-top: 30px">
          <!-- display start -->
        </div>

        <div class="col-12 text-center mb-4">
          <a href="" class="" data-dismiss="modal">
            Batal
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOP SECTION -->
<div class="container act-tel-dashboard">
  <div class="act-tel-dashboard-left">
    <h2>SETTINGS</h2>
  </div>
  <div class="clearfix"></div>
</div>

<div class="container">
  <ul id="tabs" class="row">
      <li class="col-lg-4"><a id="tab1">Whatsapp Settings</a></li>
      <li class="col-lg-4"><a id="tab2">Account Settings</a></li>
  </ul>

  <!-- TABS CONTAINER -->
  <div class="tabs-content">
    <!-- TABS 1 -->
    <div class="tabs-container" id="tab1C">
      <div class="row">
      <div class="act-tel-settings col-lg-9 col-md-6">
        <div class="form-control message col-lg-9">
          <!-- logic here -->
        </div>

        <div class="row col-fix">
            <div class="col-lg-12 col-md-12 col-sm-12 account_status">
                <div class="form-inline">
                    <div>
                        @if($is_registered == 0)
                            <button id="cdevice" type="button" class="btn btn-success">Buat Device</button>
                        @else
                            <button id="button-connect" type="button" class="btn btn-custom">Hubungkan Device</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- timer -->
        <div id="waiting" class="wrapper verification d-none text-center">
            <div class="mx-auto">
              <h5 class="text-dark">Tunggu Hingga QRCODE keluar</h5>
              <h5>
                  <span class="font-weight-bold text-primary" id="min"></span>
                  <span class="font-weight-bold text-primary ml-1 mr-1" id="min">:</span>
                  <span class="font-weight-bold text-primary" id="secs"></span>
              </h5>
            </div>

            <div class="mt-4" id="qr-code"><!-- qrcode --></div> 
        </div>

        <!-- <div class="wrapper verification" id="div-verify">
            <div class="form-group"><label class="col-sm-12 col-form-label">Scan this QR code from your <strong>Whatsapp Phone</strong></label></div>
            <div class="form-group row col-fix">
              <div class="col-lg-6"><div id="qr-code"></div></div>
              <div class="col-lg-6"><div id="timer"></div></h3></div>
            </div>

            <div class="text-right">
              <button type="button" id="button-verify" class="btn btn-custom">Submit</button>
            </div> 
        </div> -->

        <div class="wrapper add-contact table-responsive" id="phone-table">
            <table class="table table-bordered mt-4">
              <thead class="bg-dashboard">
                <tr>
                  <th class="text-center">No</th>
                  <th class="text-center">Phone Whatsapp</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Delete</th>
                </tr>
              </thead>

              <tbody id="table-phone"></tbody>
            </table>
        </div>
      <!-- end act-tel -->
      </div>

      <div class="col-lg-3 col-md-12 col-sm-12 plan account_status">
        @if($user->status > 0)
          <div>Current plan : <b>{{ $user->membership }}</b></div>
          <div>Phone Status : <b>{!! $phone_status !!}</b></div>
          <div>Server Status : <b>{!! $server_status !!}</b></div>
          <div>Valid Until : <b>{{ $expired }}</b></div>
          <div>MESSAGES Quota : <b>{{ $quota }}</b></div>
          <div>CHATS Quota : <b>{{ $chat_quota }}</b></div>
          <div><a class="text-success font-weight-bold" href="{{ url('pricing') }}"><i>Buy More</i></a></div>
        @endif
      </div>
    <!-- end row -->
    </div>
    <!-- end tabs 1 -->
    </div>

    <!-- TABS 2 -->
    <div class="tabs-container" id="tab2C">
      <div class="act-tel-settings">

      <div class="form-control message-settings col-lg-9 mb-4"><!-- --></div>

      <form id="user_contact" class="form-contact">
        <div class="wrapper account mb-5">
          <h5>Edit Your Personal Data</h5>
              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">Email</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                  <div class="form-control">
                    {{$user->email}}
                  </div>
                </div>
              </div>

              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">Full Name</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                  <input name="user_name" type="text" class="form-control" value="{{$user->name}}" />
                  <span class="error user_name"></span>
                </div>
              </div>

              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">Phone Number</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div id="move_tab2" class="col-sm-7 text-left">
                <!--   <input name="user_phone" type="text" class="form-control settings_phone" value="{{$user->phone_number}}" /> -->
                  <span class="error user_phone"></span>
                </div>
              </div>

              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">TimeZone</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                   <select class="js-example-basic-single form-control" id="timezone"  name="timezone" required>
                      @foreach($timezone as $time)
                          <option value="{{$time['zone']}}"> ({{$time['GMT_difference']. ' ) '.$time['zone']}}</option>
                      @endforeach
                   </select>
                   <span class="error timezone"></span>
                </div>
              </div>
        </div>

        <div class="wrapper account">
          <h5>Edit Your Password</h5>
              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">Old Password</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                  <input type="password" name="oldpass" class="form-control" />
                  <span class="error oldpass"></span>
                </div>
              </div>

              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">New Password</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                  <input type="password" name="newpass" class="form-control" />
                  <span class="error newpass"></span>
                </div>
              </div>

              <div class="form-group row col-fix">
                <label class="col-sm-4 col-form-label">Confirm New Password</label>
                <label class="col-sm-1 double-dot col-form-label">:</label>
                <div class="col-sm-7 text-left">
                  <input type="password" name="confpass" class="form-control" />
                  <span class="error confpass"></span>
                </div>
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-custom">Update Account</button>
              </div>
        </div>
        <!-- GENERATE API -->
        <div class="wrapper account mt-3">
            <div class="form-group row col-fix">
              <label class="col-sm-4 col-form-label">API KEY LIST</label>
              <label class="col-sm-1 double-dot col-form-label">:</label>
              <div class="col-sm-7 text-left">
                <input value="{{ $user->api_key_list }}" type="text" name="api_key_list" class="form-control" maxlength="12" />
                <span class="error api_key_list"></span>
              </div>
            </div>

            <div class="text-right">
              <button id="generate_api" type="button" class="btn btn-custom">Generate API</button>
            </div>
        </div>
        </form>
        <!-- end wrapper -->

      </div>
    <!-- end tabs -->
    </div>

  </div>
<!-- end container -->
</div>

<!-- Modal Edit Phone -->
  <div class="modal fade child-modal" id="edit-phone" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <div class="alert alert-danger"><!-- error --></div>
            <div class="form-group">
                 <div class="mb-2">
                  <form id="edit_phone_number">
                    <label>Edit Phone Number</label>
                    <div class="form-group">
                      <input type="text" class="form-control" name="edit_phone" />
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

  <!-- Modal Buy Now -->
  <div class="modal fade child-modal" id="leadsettings" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header text-center">
          <div class="modal-title">
            <span id="auth_message"></span>
          </div>
        </div>

        <div class="modal-body text-center">
            <a href="{{url('pricing')}}" class="btn btn-primary btn-lg">Buy Now</a>
        </div>

      </div>

    </div>
  </div>
  <!-- End Modal -->

<!-- <script src=" asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script> -->
<script type="text/javascript">
    var qrscan = 0;

    $(document).ready(function()
    {
        $('#div-verify').hide();
        create_device();
        buttonQr();
    });

    function create_device()
    {
        $("#cdevice").click(function(){
            ajax_device_create();
        });
    }

    function ajax_device_create()
    {
        var resp = 0;
        $.ajax({
            method : 'GET',
            url : '{{ url("create-device") }}',
            dataType : 'json',
            beforeSend : function()
            {
                $('#loader').show();
                $('.div-loading').addClass('background-load');
            },
            success : function(res)
            {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                if(res.success === 1)
                {
                    resp = 1;
                }
            },
            complete : function()
            {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');

                if(resp === 1)
                {
                    location.href="{{ url('settings') }}"
                }
            },
            error : function(xhr)
            {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
            }
        });
    }

    function buttonQr()
    {
        $("body").on("click","#button-qr",function()
        {
            wawebScan();
        });
    }

    function wawebScan()
    {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            url: "{{ url('scan') }}",
            dataType: 'json',
            beforeSend: function()
            {
                $('#div-verify').show();
                $("#modal-start-connect").modal('hide');
                timeQR();
            },
            success: function(result) {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');

                location.href="{{ url('settings') }}";
            },
            error : function(xhr,attr,throwable){
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                console.log(xhr.responseText);
                alert('Sorry, unable to display QR-CODE, there is something wrong with our server, please try again later')
            }
        });
    }

  function retQR()
	{
		$.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            url: "{{ url('qrcode') }}",
            dataType: 'html',
            beforeSend: function()
            {
                $('#loader').show();
                $('.div-loading').addClass('background-load');
            },
            success: function(result) {
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');

                if(result == 'error')
                {
                    $("#qr-code").html('<div class="alert alert-danger">{{ Lang::get("custom.db") }}</div>')
                }
                else if(result === null)
                {
                    $("#qr-code").html('Loading...')
                }
                else
                {
                    $("#qr-code").html('')
                    $("#qr-code").qrcode({
                      size : 180,
                      fill : '#000',
                      background : null,
                      content : result
                    })
                    qrscan = 1;
                }
            },
            error : function(xhr,attr,throwable){
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                console.log(xhr.responseText);
                alert('Sorry, unable to display QR-CODE, there is something wrong with our server, please try again later')
            }
        });
	}

  function deviceStatus()
	{
		$.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            url: "{{ url('phone-status') }}",
            dataType: 'json',
            beforeSend: function()
            {
                $('#loader').show();
                $('.div-loading').addClass('background-load');
            },
            success: function(result)
            {
                if(result.isConnected == 1)
                {
                    location.href="{{ url('settings') }}";
                }
                else
                {
                    $('#loader').hide();
                    $('.div-loading').removeClass('background-load');
                }
            },
            error : function(xhr,attr,throwable){
                $('#loader').hide();
                $('.div-loading').removeClass('background-load');
                console.log(xhr.responseText);
                alert('Sorry, unable to display QR-CODE, there is something wrong with our server, please try again later')
            }
        });
	}

  function timeQR()
	{
		    var scd = 0;
        var sc = 0;
        var min = qrscan = 0;

        $("#scan").html('Loading...');
        $("#waiting").removeClass('d-none');

        tm = setInterval(function(){
            $("#secs").html(sc);
            $("#min").html('0'+min);

            if(sc < 10)
            {
                $("#secs").html('0'+sc);
            }

            if(sc == 60){
                min = min + 1;
                $("#min").html('0'+min);
                sc = 0;
                $("#secs").html('0'+sc);
            }

            if(qrscan == 0)
            {
                if(sc % 12 == 0)
                {
                  retQR();
                }
            }

            sc++;
            scd++;
        },1000);
	}

</script>

<script type="text/javascript">

  var code_country = '{{ $user->code_country }}';

  if(code_country == null)
  {
      code_country = 'us';
  }

  function intialCountry()
  {
      var filename = window.location.href;
      var url = filename.split('/');
      var path;

      if(url[2] == 'localhost')
      {
          path = 'https://localhost/'+url[3]+'/assets';
      }
      else
      {
          path = 'https://'+url[2]+'/'+url[3]+'/assets';
      }

      var input = document.querySelector("#phone");
      window.intlTelInput(input, {
        customPlaceholder : function(selectedCountryPlaceholder, selectedCountryData){
           if(selectedCountryPlaceholder.substring(0,1) == '(')
           {
              return selectedCountryPlaceholder.replace(/\-+/g,'');
           }
           else
           {
              var placeholder = selectedCountryPlaceholder.replace(/^0| +|\-+/g,'');
              return placeholder;
           }
        },
        dropdownContainer: document.body,
        pageHiddenInput : url[4],
        initialCountry: code_country,
        onlyCountries: ['us', 'gb', 'sg', 'au', 'id','my'],
        placeholderNumberType: "MOBILE",
        utilsScript: path+"/intl-tel-input/js/utils.js",
        });
  }

  // Jquery Tabs
  function tabs() {
      $('#tabs li a:not(:first)').addClass('inactive');
      $('.tabs-container').hide();
      $('.tabs-container:first').show();

      $('#tabs li a').click(function(){
        var t = $(this).attr('id');

        if($(this).hasClass('inactive')){ //this is the start of our condition
          $('#tabs li a').addClass('inactive');
          $(this).removeClass('inactive');

          $('.tabs-container').hide();
          moveInputPhone(t);
          $('#'+ t + 'C').fadeIn('slow');
        }
      });
  }

  function moveInputPhone(tab)
  {
      var data_code = $(".iti__selected-flag").attr('data-code');
      var phone_number = '{{$user->phone_number}}';
      var move = $("#move_"+tab);
      $(".iti").appendTo(move);

      if(tab == 'tab1')
      {
        $("#phone").val('');
      }
      else
      {
        var regx = new RegExp("^\\"+data_code,"g");
        phone_number = phone_number.replace(regx,'');
        $("#phone").val(phone_number);
      }
  }

  function loadPhoneNumber(){
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'GET',
      url: "<?php echo url('/load-phone-number');?>",
      dataType: 'html',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $('#phone-table').show();
        $('#table-phone').html(result);
      },
      error: function(xhr,attr,throwable){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $('#table-phone').html('Maaf server kami terlalu sibuk, silahkan coba lagi nanti');
      }
    });
  }

  function triggerButtonMod()
  {
      var mod = "{{ $mod }}";
      if(mod == 1)
      {
        setTimeout(function(){
          $("#tab2").trigger('click');
        },100);
      }
  }

  function checkPhone() {
      $.ajax({
        type : 'GET',
        url : '{{url("checkphone")}}',
        success : function(result){

          if(result.status == 'buy'){
            $("#leadsettings").modal({
              show: true,
              keyboard: false,
              backdrop: 'static'
            });
            $("#auth_message").html('Please make order here :');
          }
          else if(result.status == 'exp')
          {
            $("#leadsettings").modal({
              show: true,
              keyboard: false,
              backdrop: 'static'
            });
            $("#auth_message").html('Your membership has expired please buy more to continue');
          }
        }
      });
  }


   /* function selJs()
    {
      $('.js-example-basic-single').select2({
          width: '100%',
          theme: 'bootstrap4'
      });
    }*/
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

  function checkPhoneOTP(){
    var data = {
      'phone_number':$("input[name='phone_number']").val(),
      'code_country':$(".iti__selected-flag").attr('data-code'),
    };

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: 'POST',
      url: "{{ url('check-otp') }}",
      data : data,
      dataType: 'json',
      beforeSend: function()
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        if(result.status == 1)
        {
          $(".error").hide();
          $('.message').show();
          $('.message').html('Please check OTP code on your WA account');
          $("#phone").prop('readonly',true);
          $("#otp").show();
          $("#btn-check").replaceWith('<button type="button" id="submit-otp" class="btn btn-custom">Submit OTP</button>');
        }
        else
        {
          $(".error").show();
          $(".code_country").html(result.code_country);
          $(".phone_number").html(result.phone_number);
        }
      },
      error: function(xhr,attr,throwable){
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        alert('Sorry cannot load phone list, please call administrator');
      }
    });
  }

  function checkOTP()
  {
    $("body").on('click','#btn-check',function(){
      checkPhoneOTP();
    });
  }

  function submitOTP()
  {
    $("body").on("click","#submit-otp",function()
    {
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'POST',
        url: "{{ url('submit-otp') }}",
        data : {'otp': $("input[name='otp']").val()},
        dataType: 'json',
        beforeSend: function()
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');

          if(result.status == 'success')
          {
            $('.message').hide();
            $("#otp").hide();
            $("#submit-otp").replaceWith(result.button);
          }
          else
          {
            $('.message').show();
            $('.message').html(result.message);
          }

        },
        error: function(xhr,attr,throwable){
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          alert('Sorry cannot load phone list, please call administrator');
        }
      });
      // end ajax
    });
  }

	$(document).ready(function() {
    checkPhone();
    tabs();
    loadPhoneNumber();
    editPhoneNumber();
    openEditModal();
    settingUser();
    triggerButtonMod();
    // intialCountry();
    // checkOTP();
    // submitOTP();
    agreement();
    checked_agreement();
    // selJs();

    $(".iti").addClass('w-100');
    $('.message').hide();
    buttonStartConnect();
		buttonConnect();

    $("select[name='timezone'] > option[value='{{ $user_timezone }}']").prop("selected", true);



		// Display Country
    codeCountry();
    putCallCode();

		// End Display Country

		initButton();
    generate_api_key();
  });

  // generate api-key
  function generate_api_key()
  {
    $("#generate_api").click(function(){
      var input = $("input[name='api_key_list']");

      $.ajax({
        method:'GET',
        url:'{{ url("generate_api_list") }}',
        dataType:'json',
        beforeSend : function()
        {
          $("#loader").show();
          $('.div-loading').addClass('background-load');
        },
        success: function(res){
          input.val(res.txt);
        },
        error : function()
        {
          $(".error .api_key_list").html('{{ Lang::get("custom.db") }}');
        },
        complete : function()
        {
          $("#loader").hide();
          $('.div-loading').removeClass('background-load');
        }
      });
    });
  }

  function agreement()
  {
    var checked = $("input[name='agreement']").prop('checked');
    if(checked == true)
    {
    //   $("#display_agreement").html('<button class="btn btn-secondary btn-block" id="button-start-connect">Start</button>')
      $("#display_agreement").html('<button class="btn btn-secondary btn-block" id="button-qr">Start</button>')
    }
    else
    {
      $("#display_agreement").html('');
    }
  }

  function checked_agreement()
  {
    $("input[name='agreement']").click(function(){
      agreement();
    });
  }

  function settingUser(){
    $(".message-settings").hide();
    $("#user_contact").submit(function(e){
      e.preventDefault();
      var initial_country = $(".iti__selected-flag").attr('data-country');
      var code_country = $(".iti__selected-flag").attr('data-code');
      var data = $(this).serializeArray();
      data.push({name : 'data_country', value : initial_country},{name : 'code_country', value : code_country})

      $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("save-settings")}}',
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            if(result.status == 'success'){
              $(".error").hide();
              $('.message-settings').show();
              $('.message-settings').html(result.message);
            }
            else if(result.status == 'error')
            {
              $(".error").show();
              $(".user_name").html(result.user_name);
              $(".user_phone").html(result.user_phone);
              $(".oldpass").html(result.oldpass);
              $(".confpass").html(result.confpass);
              $(".newpass").html(result.newpass);
              $(".timezone").html(result.timezone);
            }
            else {
              $('.message-settings').show();
              $('.message-settings').html(result.message);
            }
          },error: function(xhr,attribute,throwable){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
          }
        });
     });
  }

  function openEditModal(){
    $("body").on("click",".btn-edit",function(){
      var number = $(this).attr('data-number');
      $("input[name='edit_phone']").val(number);
      $("#edit-phone").modal();
    });
  }

  function editPhoneNumber()
  {
     $(".alert").hide();
     $("#edit_phone_number").submit(function(e){
        e.preventDefault();
        var values = $(this).serialize();

        $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type : 'POST',
          url : '{{url("edit-phone")}}',
          data : values,
          dataType : 'json',
          beforeSend: function()
          {
            $('#loader').show();
            $('.div-loading').addClass('background-load');
          },
          success : function(result){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');

            $('.message').show();
            $('.message').html(result.message);

            if(result.error == 'true'){
              $(".alert").show();
              $(".alert").html(result.message);
            }

            if(result.status == "success") {
              $('#div-verify').show();
              loadPhoneNumber();

              $("#phone").val(result.phone);
              $("#edit-phone").modal('hide');
              $(".alert").hide();
            }
          },error: function(xhr,attribute,throwable){
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
          }
        });
     });
  }
	function buttonConnect(){
        $('body').on('click','#button-connect',function(){
            $("#modal-start-connect").modal();
        });
	}
	function buttonStartConnect()
    {
    $('body').on('click','#button-start-connect',function()
    {
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        url: "{{ url('connect-phone') }}",
        data: dataphone,
        dataType: 'text',
        beforeSend: function()
        {
          $("#modal-start-connect").modal('hide');
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(result) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');

          var data = jQuery.parseJSON(result);

          if(data.status == "success") {
            $('.message').show();
            $('.message').html(data.message);
            $("#button-connect").prop('disabled',true);

            // new system loadPhoneNumber();
            waitingTime();
            $(".error").hide();
          }

          if(data.status == "error") {
              $(".error").show();
              $(".phone_number").html(data.phone_number);
              $('.code_country').html(data.code_country);
          }

          if(data.message !== undefined){
              $('.message').show();
              $('.message').html(data.message);
          }

        },
        error: function(xhr,attr,throwable)
        {
            $('#loader').hide();
            $('.div-loading').removeClass('background-load');
            alert(xhr.responseText);
        }
      });
    });
	}


  var tm,flagtm;
  function waitingTime()
  {
      var scd = 0;
      var sc = 0;
      var min = 0;
      flagtm = false;
      tm = setInterval(function(){
          $("#secs").html(sc);
          $("#min").html('0'+min);

          if(sc < 10)
          {
            $("#secs").html('0'+sc);
          }

          if(sc === 12 || sc === 15 || sc === 25)
          {
            retQR();
          }

          if(sc == 60){
            min = min + 1;
            $("#min").html('0'+min);
            sc = 0;
            $("#secs").html('0'+sc);
          }

					<?php if (session('mode')==0 || session('mode')==2) { ?>
          if( (scd == 33) || (scd == 60) || (scd == 90) || (scd == 120) || (scd == 180) || (scd == 233) || (scd == 287) || (scd == 329) || (scd == 359) ) {
					<?php } ?>
					<?php if (session('mode')==1) { ?>
          if( (scd == 180) || (scd == 233) || (scd == 287) || (scd == 329) || (scd == 359) || (scd == 389) || (scd == 419) || (scd == 449) || (scd == 489) || (scd == 519) || (scd == 559) || (scd == 589) || (scd == 619) || (scd == 659) || (scd == 689) ) {
					<?php } ?>
            // console.log("new system");
            if (flagtm == false ) {
              flagtm = true;
            //   getQRCode($(".iti__selected-flag").attr('data-code')+$("#phone").val());
            }
          }

          if(min == 10)
          {
              $("#secs").html('0'+0);
              clearInterval(tm);
          }

          sc++;
          scd++;
      },1000);
  };

	function getQRCode(phone_number)
	{
		$.ajax({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
				type: 'GET',
				url: "{{ url('verify-phone') }}",
				data: {
					phone_number : phone_number,
				},
				dataType: 'json',
				beforeSend: function()
				{
					$('#loader').show();
					$('.div-loading').addClass('background-load');
				},
				success: function(result) {
					$('#loader').hide();
					$('.div-loading').removeClass('background-load');

					if(result.status == 'error'){
						/* new system $('.message').show();
						$('.message').append(result.phone_number);*/
						// getQRCode($(".iti__selected-flag").attr('data-code')+$("#phone").val());
						console.log(result);
					}
					else
					if(result.status == 'success'){
						$('#div-verify').show();
						$("#qr-code").html(result.data);
						$(window).scrollTop(700);
						clearInterval(tm);
						countDownTimer(phone_number);
					}
          else if(result.status == 'login'){
            $('.message').show();
            $('#div-verify').hide();
            $("#timer, #qr-code").html('');
            $(".message").html(result.data);
            $('#phone-table').show();
            loadPhoneNumber();
            clearInterval(tm);
          }
					flagtm = false;
					// new system loadPhoneNumber();
				},
				error : function(xhr,attr,throwable){
					$('#loader').hide();
					$('.div-loading').removeClass('background-load');
					console.log(xhr.responseText);
					alert('Sorry, unable to display QR-CODE, there is something wrong with our server, please try again later')
				}
			});

	}

	var timerCheckQrCode,flagTimerCheckQrCode;
	function countDownTimer(phone_number)
	{
		var sec = 25; //countdown timer
		var word = '<h3>Please scan qr-code before time\'s up :</h3>';
		flagTimerCheckQrCode=false;
		timerCheckQrCode = setInterval( function(){

				if( (sec == 20) || (sec == 15) || (sec == 10) || (sec == 1) || (sec == -10) || (sec == -20) || (sec == -30) || (sec == -40) || (sec == -50) || (sec == -59) ) {
					if (flagTimerCheckQrCode == false ) {
						flagTimerCheckQrCode = true;
						checkQRcode(phone_number);
					}
				}

				// if(sec < 1){
				if(sec < -60){
					clearInterval(timerCheckQrCode);
				}

				if(sec < 10 && sec >= 0 ){
					$("#timer").html(word+'<h4><b>0'+sec+'</b></h4>');
				}
				else if(sec > 10)
				{
					$("#timer").html(word+'<h4><b>'+sec+'</b></h4>');
				}
				sec--;
		},1000);
	}

	function checkQRcode(phone_number)
	{
		$.ajax({
			type: 'GET',
			url: "{{ url('check-qr') }}",
			data: {
				no_wa : phone_number,
			},
			dataType: 'json',
			beforeSend: function()
			{
				/* new system $('#loader').show();
				$('.div-loading').addClass('background-load');*/
			},
			success: function(result) {
				/* new system $('#loader').hide();
				$('.div-loading').removeClass('background-load');

				$('#div-verify').hide();
				$("#timer, #qr-code").html('');*/

				if (result.status!="none"){
					$('.message').show();
					$('.message').html(result.status);
          <?php if (session('mode')==2) { ?>
            if (result.data!="") {
              $("#qr-code").html(result.data);
            }
          <?php } ?>
				}
				if (result.status=="Congratulations, your phone is connected"){
					$('#div-verify').hide();
					$("#timer, #qr-code").html('');
					$('#phone-table').show();
					loadPhoneNumber();
					clearInterval(timerCheckQrCode);
				}
				flagTimerCheckQrCode=false;
				/* new system loadPhoneNumber();*/
			},
			error : function(xhr){
				/* new system $('#loader').hide();
				$('.div-loading').removeClass('background-load');
				$('#div-verify').hide();
				$("#timer, #qr-code").html('');*/

				// alert('Sorry, unable to check if your phone verified, please try again later');
				console.log(xhr.responseText);
			}
		});
	}

	function initButton(){

    $('#button-delete-phone').click(function(){
      $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        url: "<?php echo url('/delete-phone');?>",
        data: {
          id : $("#id_phone_number").val(),
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
          $('.message').show();
          $('.message').html(data.message);
          $("#phone").prop('disabled',false);
          $("#code_country").prop('disabled',false);
          $("#button-connect").prop('disabled',false);
          $("#phone").val("");
          $("#phone-table").hide();
          $("#display_button_after_delete_phone").html(data.check_button);
          loadPhoneNumber();
          // new system loadPhoneNumber();
        },
        error: function(xhr)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
      });
    });


    $("body").on("click", ".icon-delete", function() {
      $('#id_phone_number').val($(this).attr('data-id'));
      $('#confirm-delete').modal('show');
    });

    $("body").on("click", ".link-verify", function() {
      var phone_number = $(this).attr('data-phone');
      $("#phone").val(phone_number);
      getQRCode(phone_number);
    });
	}
</script>

@endsection
