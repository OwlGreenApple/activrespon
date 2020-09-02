@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('assets/css/summary.css')}}">

<script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>"></script>
<script>
  grecaptcha.ready(function() {
    grecaptcha.execute("<?php echo env('GOOGLE_RECAPTCHA_SITE_KEY');?>", {action: 'contact_form'}).then(function(token) {
        $('#recaptchaResponse').val(token);
    });
  });
</script>

<?php 
	$is_login = false;
	if (Auth::check()) {
		$is_login = true;
	}
?>
<section>
    <div class="container">
      <div class="row sumo-split-layout">
        

<div class="col-md-6 sumo-col-left pb-0 pb-md-50">
  <div class="mt-50 my-md-50 mr-lg-50 pr-md-30">
    <div class="pb-20">
      <h1 class="mb-0">Checkout</h1>
    </div>
    <a class="d-inline-block d-md-none pb-30" data-toggle="modal" data-target="#summaryModal">
      <span class="d-flex align-items-center">
        <img class="mr-10" src="https://appsumo2.b-cdn.net/static/images/svg/baseline-shopping_cart-24px.svg" width="auto" height="24">
        <span class="sumo-psuedo-link">View cart summary</span>
      </span>
    </a>
    <div id="checkoutSteps">
      <!-- Card 1 -->
      <div class="checkout-step-container mb-30">
        <div class="card checkout-card card-step-1 filled" id="cardStep1">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h2 class="h3" id="header-step1">1. <?php if ($is_login) { ?>Account verification<?php } else { ?>Create an account <?php } ?></h2>
          </div>
        </div>
        <!-- End Card Header -->
        <div class="card-body">
          <!-- Card Data Summary -->
          <div class="card-data-summary show" id="step-1">
						<?php if ($is_login) { ?>
            <p>Your order confirmation will be emailed to:</p>
            <span class="sumo-psuedo-link">{{Auth::user()->email}}</span>
						<?php } else { ?>

							<div id="div-register">
                <form class="add-contact" id="form-register">
                    <div class="form-group">
                      <label>Name*</label>
                      <input type="text" name="username" class="form-control" placeholder="Input Your Name" required />
                      <span class="error username" role="alert"></span>                             
                    </div>

                    <div class="form-group">
                      <label>Email*</label>
                       <input id="email" type="email" class="form-control" name="email" required autocomplete="email" placeholder="Input Your Email">
                       <span class="error email"></span>
                    </div>

                    <div class="form-group">
                      <label>Handphone* <span class="tooltipstered" title="<div class='panel-heading'>Message</div><div class='panel-content'>
                            Fill with your phone number without 0 or country code<br/>
                            For example : 8123456789, (201)5555555
                          </div>">
                          <i class="fa fa-question-circle "></i>
                        </span>
                      </label>
                      <input type="text" id="phone" name="phone" class="form-control" required/>
                      <span class="error phone"></span>

                      <input id="hidden_country_code" type="hidden" class="form-control" name="code_country" />
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
                            <input class="form-check-input" type="radio" name="gender" id="radio-female" value="female">
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
                        <label class="checkbox-left" for="check-terms"><sb>I Agree with <a href="http://activrespon.com/terms-of-services/" target="_blank" style="text-decoration: underline;">Terms and Condition</a></sb></label>
                    </div>

                    <div class="text-left">
                      <button id="btn-register" type="button" class="btn btn-custom btn-lg">REGISTER</button>
                    </div>
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse" readonly="readonly"/>
                </form>

                <hr class="mt-5" />

                <div class="mt-4 mb-3"><sb>Already Have An Account? <a href="" id="link-to-login">Log in Here</a></sb></div>
							</div>
							<div id="div-login" style="display:none;">
								<form class="add-contact" method="POST" id="form-login">
										@csrf
										 <div class="form-group">
												<label>Email*</label>
												 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }} {{ Cookie::get('email') }}" required autocomplete="email" placeholder="Input Your Email">

													@error('email')
															<span class="invalid-feedback" role="alert">
																	<strong>{{ $message }}</strong>
															</span>
													@enderror
											</div>

											<div class="form-group">
												<label>Password *</label>
												 <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="{{ Cookie::get('password') }}" placeholder="Input Your Password">
												
												 @error('password')
															<span class="invalid-feedback" role="alert">
																	<strong>{{ $message }}</strong>
															</span>
												 @enderror
											</div>

											<div class="form-group">
												<label class="custom-checkbox">
														<input type="checkbox" name="remember"/ id="remember-login">
														<span class="checkmark-check"></span>
												</label>
												<label class="checkbox-left" for="remember-login"><sb>Remember Me</sb></label>
											</div>


											<div class="text-left">
												<button type="button" id="button-login" class="btn btn-custom btn-lg">LOG IN</button>
											</div>
								</form>

								<hr class="mt-5" />

								<div class="mt-4 mb-3"><sb>Need an Activrespon account? <a href="" id="link-to-register">Register Here</a></sb></div>
							</div>
						<?php } ?>
          </div>
        </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="checkout-step-container">
        <div class="card checkout-card" id="cardStep2">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <h2 class="h3">2. Order Review</h2>
          </div>
        </div>
        <!-- End Card Header -->
        <div class="card-body">
          <!-- Card Data Entry -->
          <div class="card-data-entry step-2" <?php if (!$is_login) { ?> style="display:none;"<?php } ?>> <!-- style="display: none;" -->
            <p>
              <b>
                While we're sure you got everything right, please review your order summary,
                email address, and payment method before placing your order.
              </b>
            </p>
            <!-- Mobile Checkout Summary  -->
            <div id="cart-checkout-summary-mobile" class="d-block d-md-none">

<!-- Table -->
<table class="table sumo-purchases-table">
  <tbody>
    
      <tr>
        <td class="sumo-td-name">
          <div class="sumo-title">
            <b><?php echo session('order')['namapakettitle'] ?></b>
            
          </div>
        </td>
				<!--
        <td class="sumo-td-price text-right sumo-checkout-item cart-item">
					Rp. <?php echo number_format(session('order')['price'], 0, '', '.'); ?>
        </td>
				-->
      </tr>
    
  </tbody>
</table>
<!-- End Table -->
<div>
    <div class="as-checkout-entry" id="checkout-total" data-total="79.00">
      <strong class="as-checkout-total">Total</strong>
      <strong class="as-checkout-total-price" id="totalprice_sidebar totalprice_mobile">
				Rp. <?php echo number_format(session('order')['price'], 0, '', '.'); ?>
			</strong>
    </div>
</div>


            </div>
            <!-- Checkout Button Container -->
            <div class="checkout-button-container mt-30 step-2" id="checkout-buttons-2" <?php if (!$is_login) { ?> style="display:none;"<?php } ?>>
							
              <div class="sumo-product-note light mt-20">
                By clicking the "Place Order" button, you confirm that you have read, understand,
                and accept our <a href="http://activrespon.com/terms-of-services/" target="_blank">Terms and Conditions</a>, and <a href="http://activrespon.com/privacy-policy/" target="_blank" style="text-decoration: underline;">Privacy Policy</a>.
              </div>
            </div>
            <!-- End Mobile Checkout Summary -->
            <hr class="my-30">
            <h4 class="mb-10"><b>Need Help?</b></h4>
            <p>
              Our support team is only one click away! Send us any questions you may have.
            </p>
            <a href="whatsapp://send/?phone=+62817318368" target="_blank" class="btn btn-more full-width-mobile waves-effect waves-light">Find Help</a>
          </div>
          <!-- End Card Data Entry -->
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

        
        <!-- Right Column -->
        <div class="col-md-6 sumo-col-right pb-50">
          <div class="mt-md-50 mb-50 ml-lg-50 pl-md-30">
            <div class="pb-20 d-md-block">
              <div class="placeholder"></div>
            </div>
            <!-- Summary -->
            <div class="card checkout-card checkout-summary dark mb-30 d-md-flex">
              <div class="card-header">
                <div class="d-flex align-items-center">
                  <h2 class="h3">Summary</h2>
                  <a class="edit-link ml-auto" href="<?php echo url()->previous(); ?>">Edit</a>
                </div>
              </div>
              <!-- End Card Header -->
              <div class="card-body pt-20">

<div id="cart-checkout-summary">
  <!-- Desktop Table -->
  <!-- Table -->
  <table class="table sumo-purchases-table">
    <tbody>
      
        <tr>
          <td class="sumo-td-name">
            <div class="sumo-title">
              <b><?php echo session('order')['namapakettitle'] ?></b>
              
            </div>
          </td>
					<!--
          <td class="sumo-td-price text-right sumo-checkout-item" data-item-id="2101">
						Rp. <?php echo number_format(session('order')['price'], 0, '', '.'); ?>
          </td>
					-->
        </tr>
      
    </tbody>
  </table>
  <!-- End Table -->
  <div>

  <form method="POST" action="{{url('submit-summary')}}">
    <!--
    <div class="col-md-12 col-12 upgrade" <php if (!$is_login) { ?> style="display:none;"<php } ?>>
      <label>Upgrade : </label>
      <div>
        <div class="form-check form-check-inline">
          <label class="custom-radio">
            <input class="form-check-input" type="radio" name="status_upgrade" value="1" checked disabled>
            <span class="checkmark"></span>
          </label>
          <label class="form-check-label" for="radio-male">Now</label>
        </div>

        <div class="form-check form-check-inline">
          <label class="custom-radio">
            <input class="form-check-input" type="radio" name="status_upgrade" value="2">
            <span class="checkmark"></span>
          </label>
          <label class="form-check-label" for="radio-female">Later</label>
        </div>

      </div>
       
    </div>-->

    <div class="as-checkout-entry" id="checkout-total">
    
      <div class="col-md-12 col-12">
        <strong class="as-checkout-total">Total : </strong>
        <strong class="as-checkout-total-price total_price" id="totalprice_sidebar totalprice_mobile">
        Rp. 
        @if(session('order')['diskon'] > 0 || session('order')['upgrade'] <> null)
          <strike>{!! number_format(session('order')['price'], 0, '', '.') !!}</strike>
        @endif
  			<?php echo number_format(session('order')['total'], 0, '', '.'); ?>
  			</strong>
      </div>
    </div>
</div>
  
  
  

</div>
									
										{{ csrf_field() }}
                    <div class="checkout-button-container mt-30 step-2" id="checkout-buttons-1" <?php if (!$is_login) { ?> style="display:none;"<?php } ?>>

												<input type="submit" name="submit" id="submit" class="col-md-12 col-12 btn btn-primary bsub btn-block" value="Order Now"/>
                      <div class="sumo-product-note light mt-20">
                        By clicking the "Place Order" button, you confirm that you have read, understand,
                        and accept our <a href="http://activrespon.com/terms-of-services/" target="_blank">Terms and Conditions</a>, and <a href="http://activrespon.com/privacy-policy/" target="_blank">Privacy Policy</a>.
                      </div>
                    </div>
									</form>
              <!-- Close Desktop Table -->
              </div>
            </div>
            <!-- Supplementary Info -->
            <div class="sumo-cart-supplement mt-30">
              <p class="sumo-cart-supplement-header">Hustle with Confidence</p>
              <ul class="list-inline mt-20">
                
                <li class="d-flex">
                  <img class="sumo-icon" width="auto" height="20px" src="https://appsumo2.b-cdn.net/static/images/svg/calendar.svg">
                  <span style="font-style: italic;">Award winning developer, use our applications with ease in mind. We spent countless hours working hard to develop the best software & we committed to make it better each day.</span>
                </li>
                <li class="d-flex">
                  <img class="sumo-icon" width="auto" height="20px" src="https://appsumo2.b-cdn.net/static/images/svg/lifebuoy.svg">
                  <span style="font-style: italic;">Preferred customer support. We take pride in going above and beyond to solve issues and keep our customers happy.<br>Email or simply chat with our Customer support.</span>
                </li>
                <li class="d-flex">
                  <img class="sumo-icon" width="auto" height="20px" src="https://appsumo2.b-cdn.net/static/images/svg/message-text.svg">
                  <span style="font-style: italic;">We give best deal and savings on pricing packages everytime, on top of that make sure you checked our Coupon page every now and then, to get more discounts & promotions.</span>
                </li>
              </ul>
            </div>
            <!-- End Supplemetary Info -->
          </div>
        </div>
      </div>
    </div>
  </section>

<script type="text/javascript">

  function getUpgrade()
  {
    $("input[name='status_upgrade']").change(function(){
      var val = $(this).val();

      $.ajax({
        type: 'GET',
        url: "{{ url('get-status-upgrade') }}",
        data: {
          'status_upgrade':val,
        },
        dataType: 'json',
        beforeSend: function() 
        {
          $('#loader').show();
          $('.div-loading').addClass('background-load');
        },
        success: function(data) {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          $(".total_price").html('Rp '+'<strike>'+formatNumber(data.price)+'</strike> '+formatNumber(data.total))
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

	function formatNumber(num) {
    if(isNaN(num) == false)
    {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
    }
		else
    {
      return '';
    }
	}

	/*
	REGISTER
	*/
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

  function rememberMe(){
    $("input[name=remember]").click(function(){
      var val = $(this).val();

      if(val == 1){
        $(this).val('on');
      }
      else {
        $(this).val(1);
      }

    });
  }

	function loginAjax(){
    $(".upgrade").hide();
    $("body").on("click", "#button-login", function() {
			$.ajax({
				type: 'POST',
				url: "{{ url('loginajax') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: $("#form-login").serializeArray(),
				dataType: 'text',
				beforeSend: function() {
					$('#loader').show();
					$('.div-loading').addClass('background-load');
				},
				success: function(result) {
					$('#loader').hide();
					$('.div-loading').removeClass('background-load');

					var data = jQuery.parseJSON(result);
					
					if (data.success == '1') {
						$(".step-2").show();
            $(".bsub").show();
						$("#step-1").html('<p>Your order confirmation will be emailed to:</p><span class="sumo-psuedo-link">'+data.email+'</span>');

            if(data.status_upgrade == 1) //false which mean upgrade
            {
              $("input[name='status_upgrade']").prop('disabled',false);
              // $(".upgrade").show();
              $(".total_price").html('Rp '+'<strike>'+formatNumber(data.price)+'</strike> '+data.total);
              // $("input[name='status_upgrade']").prop('disabled',false);
            }
           /* else
            {
               $(".upgrade").hide(); 
            }*/
					} 
					else {
						alert(data.message);
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

	function registerAjax(){
    $("#btn-register").click(function(){
      var val= $("input[name=agreement]").val();

      if(val == 'on'){
        alert('Please Check Agreement Box');
				return false;
      }

			$.ajax({
				type: 'POST',
				url: "{{url('register')}}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: $("#form-register").serializeArray(),
				dataType: 'text',
				beforeSend: function() {
					$('#loader').show();
					$('.div-loading').addClass('background-load');
				},
				success: function(result) {
					$('#loader').hide();
					$('.div-loading').removeClass('background-load');

					var data = jQuery.parseJSON(result);

					if (data.success == 1) 
          {
            $(".error").hide();
						$(".step-2").show();
						$("#step-1").html('<p>Your order confirmation will be emailed to:</p><span class="sumo-psuedo-link">'+data.email+'</span>');
					} 
					else {
						 $(".error").show();
             $(".username").html(data.username);
             $(".email").html(data.email);
             $(".code_country").html(data.code_country);
             $(".phone").html(data.phone);
					}
				},
        error: function(xhr,attr,throwable)
        {
          $('#loader').hide();
          $('.div-loading').removeClass('background-load');
          console.log(xhr.responseText);
        }
			});
			
			
    });
	}
	
	function initButton(){
    $("body").on("click", "#link-to-login", function(e) {
			e.preventDefault();
      $("#div-login").show();
      $("#div-register").hide();
      $("#header-step1").html("1. Login");
			$('html, body').animate({scrollTop: '0px'}, 300);
    });
    $("body").on("click", "#link-to-register", function(e) {
			e.preventDefault();
      $("#div-login").hide();
      $("#div-register").show();
      $("#header-step1").html("1. Create an account");
			$('html, body').animate({scrollTop: '0px'}, 300);
    });
	}
	
	function checkField(){
		if ($("#email").val()!="" && $("#phone").val()!="" && $("#username").val()!="" && $("#check-terms").val()==1) {
			$("#btn-register").addClass("register-active");
		}
	}

	function onChangeRegister(){
		$("#email,#phone,#username,#check-terms").change(function(){
			checkField();
		});
	}

  $(document).ready(function() {
		agreement();
		fixLayoutInputPhoneCountry();
		getDataFromCountry();
		countryChange();
		rememberMe();
		
		loginAjax();
		registerAjax();
    // getUpgrade();

		initButton();
		onChangeRegister();
  });
    
</script>

@if(!$is_login)
  <script src="{{ asset('/assets/intl-tel-input/callback.js') }}" type="text/javascript"></script>
@endif
@endsection