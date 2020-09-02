@extends('layouts.app')

@section('content')
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>"></script>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute("<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>", {action: 'contact_form'}).then(function(token) {
        $('#recaptchaResponse').val(token);
    });
  });
</script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
          
           <div class="act-tel-register bg-dashboard wrapper">

                @if(session('error_phone'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error_phone') }}
                    </div>
                @endif

                <form class="add-contact" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group text-center">
                      <big>Hello</big>
                    </div>

                    <div class="form-group">
                      <label>Name*</label>
                      <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Input Your Name" required />
                      @error('username')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label>Email*</label>
                       <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Input Your Email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                      <label>Handphone* <span class="tooltipstered" title="<div class='panel-heading'>Message</div><div class='panel-content'>
                            Fill with your phone number without 0 or country code<br/>
                            For example : 8123456789, (201)5555555
                          </div>">
                          <i class="fa fa-question-circle "></i>
                        </span>
                      </label>
                      <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required/>
                      @error('phone')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <input id="hidden_country_code" type="hidden" class="form-control @error('code_country') is-invalid @enderror" name="code_country" />
                      @error('code_country')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                     <input name="data_country" type="hidden" /> 
                    </div>

                    <div class="form-group">
                      <label>Gender*</label>
                      <div>
                        <div class="form-check form-check-inline">
                          <label class="custom-radio">
                            <input class="form-check-input" type="radio" name="gender" value="male" id="radio-male" checked>
                            <span class="checkmark"></span>
                          </label>
                          <label class="form-check-label" for="radio-male">Male</label>
                        </div>

                        <div class="form-check form-check-inline">
                          <label class="custom-radio">
                            <input class="form-check-input" type="radio" name="gender" value="female" id="radio-female">
                            <span class="checkmark"></span>
                          </label>
                          <label class="form-check-label" for="radio-female">Female</label>
                        </div>

                      </div>
                      <!-- -->
                    </div>

                    <div class="form-group">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="agreement" required id="check-terms"/>
                            <span class="checkmark-check"></span>
                        </label>
                        <label class="checkbox-left" for="check-terms">I Agree with <a>Terms and Condition</a></label>
                    </div>

                    <div class="text-left">
                      <button id="btn-register" type="submit" class="btn btn-custom btn-lg">REGISTER</button>
                    </div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" readonly="readonly"/>
                </form>

                <hr class="mt-5" />

                <div class="mt-4 mb-3"><sb>Already Have An Account? <a href="{{route('login')}}">Log in Here</a></sb></div>
            <!-- end wrapper -->
           </div>

        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
      agreement();
      checkCheckBox();
      fixLayoutInputPhoneCountry();
      getDataFromCountry();
      countryChange();
  });

  function getDataFromCountry()
  {
     var data_country = $(".iti__selected-flag").attr('data-country');
     $("input[name='data_country']").val(data_country);
  }

  function countryChange()
  {
     jQuery("#phone").on('countrychange', function(e, countryData){
        var data_country = $(".iti__selected-flag").attr('data-country');
        $("input[name='data_country']").val(data_country);
    })
  } 

  function fixLayoutInputPhoneCountry()
  {
      $(".iti").addClass('w-100');
  }

  function agreement(){
    $("input[name=agreement]").click(function(){
      var val = $(this).val();

      if(val == 1){
        $(this).val('on');
      }
      else {
        $(this).val(1);
      }

    });
  }

  function checkCheckBox()
  {
    $("#btn-register").click(function(){
      var val= $("input[name=agreement]").val();

      if(val == 'on'){
        alert('Please Check Agreement Box');
      }
    });
  }
</script>
<script src="{{ asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>
@endsection
