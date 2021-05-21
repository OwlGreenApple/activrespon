@extends('layouts.app')

@section('content')
<link href="{{ asset('/assets/css/pricing.css') }}" rel="stylesheet" />
 
<!--Plugin CSS file with desired skin-->
<link rel="stylesheet" href="{{ asset('/assets/ion.rangeSlider-master/css/ion.rangeSlider.min.css') }}"/>
<!--Plugin JavaScript file-->
<script src="{{ asset('/assets/ion.rangeSlider-master/js/ion.rangeSlider.min.js') }}"></script>

<!-- TOP SECTION -->
<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col-12">
      <!--
        <h1 class="">Omnilinkz <strong>Pricing Plans</strong></h1>
        -->
      </div>
    </div>
  </div>
</section>

<div class="container main-pricing">
  <div class="row h-100">
      <!--
      <div class="card card-block w-25 mx-auto">I am Groot.</div>
			<i class="fa fa-check"></i>
      -->
      <h1 class="col-12">
        Choose Your Package
      </h1>
      <div class="col-1 ">
      </div>
      <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 price-content">
				<div class="row">
        <h3>
          How many messages you need to send per month ? 
        </h3>
        <div class="col-10 div-range-slider">
          <input type="text" class="js-range-slider" name="my_range" value="" />
        </div>
        <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12 ml-0 mr-0 box-pricing p-2 " data-attr="1">
          <div class="box-in">
            <div class="div-header w-100 p-1">
              <h5>Basic</h5>
            </div>
            <div class="div-content w-100 w-100 p-3">
              <span><i class="correct"></i> Monthly </span>
              <span>&nbsp</span>
              <span id="monthly-price" class="content-price">IDR 195.000</span>
              <span id="description-contact-3" class="description-contact">10.000 Messages</span>
            </div>
          </div>            
        </div>    
        <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12 ml-0 mr-0 box-pricing p-2 selected" data-attr="2">
          <div class="box-in">
            <div class="div-header w-100 p-1">
              <i class=""></i> 
              <h5 class="pl-3">Best Seller</h5>
            </div>
            <div class="div-content w-100 w-100 p-3">
              <span><i class="star"></i> 2 Months </span>
              <span>&nbsp</span>
              <span id="2months-price" class="content-price">IDR 370.500</span>
              <span id="description-contact-3" class="description-contact">10.000 Messages</span>
            </div>
          </div>            
        </div>    
        <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12 ml-0 mr-0 box-pricing p-2" data-attr="3">
          <div class="box-in">
            <div class="div-header w-100 p-1">
              <h5>Super Value</h5>
            </div>
            <div class="div-content w-100 w-100 p-3">
              <span><i class="correct"></i> 3 Months </span>
              <span>&nbsp</span>
              <span id="3months-price" class="content-price">IDR 526.500</span>
              <span id="description-contact-3" class="description-contact">10.000 Messages</span>
            </div>
          </div>            
        </div>            
        </div>
        <!-- Chat Feature 
         <h5 class="text-left mt-2"><input type="checkbox" class="package-chat" /> Add webchat IDR 100.000 per-month</h5>-->
      </div>
			<!--
      <div class="col-1 ">
      </div>
			-->
      <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12 detail-description">
        <h3 id="description-contact-header">Best Seller 3 Months<br>10.000 Messages/month</h3>
        <button type="button" class="btn btn-lg btn-success col-12" id="choose-price">IDR 370.500</button>
        <ul>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span><strong>Unlimited Contacts</strong></span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span id="message-per-month">500 Messages / day</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Event Reminder</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Appointment Reminder</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Message Automation</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Auto reply / responder</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Subscription Form</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Statistic</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Download Contacts List</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>FB Pixel / Google Retargetting</span>
          </li>
          <li>
            <i class="fa fa-check" aria-hidden="true"></i> 
            <span>Google form integration</span>
          </li>
          <li>
            <i class="not-10000 fa fa-times" aria-hidden="true"></i> 
            <span>Broadcast Schedule</span>
          </li>
          <li>
            <i class="not-10000 fa fa-times" aria-hidden="true"></i> 
            <span>Broadcast Now</span>
          </li>
        </ul>
				<a id="link-checkout">
        <button class="btn btn-lg btn-success button-bottom-buynow col-12">Buy Now <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
				</a>
      </div>
  </div>
</div>

<script type="text/javascript">
  var modePrice,messagePer30day;
  $(document).ready(function(){
		$("#link-checkout").attr("href","<?php echo url('checkout/2'); ?>");
		modePrice = 2;
    box_pricing_click();
    // set_price();
		check_package();
    slider_init();
    chatPricing();
  });

	function check_package(){

    /*var is_checked = $(".package-chat").prop('checked');
    var chat;
    if(is_checked == true)
    {
      chat = 1;
    }
    else
    {
      chat = 0;
    }*/

		if (modePrice==1) {
			if (messagePer30day == 10000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/1'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>10.000 Messages/month");
			}
			if (messagePer30day == 17500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/4'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>17.500 Messages/month");
			}
			if (messagePer30day == 27500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/7'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>27.500 Messages/month");
			}
			if (messagePer30day == 40000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/10'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>40.000 Messages/month");
			}
			if (messagePer30day == 55000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/13'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>55.000 Messages/month");
			}
			if (messagePer30day == 72500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/16'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>72.500 Messages/month");
			}
			if (messagePer30day == 92500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/19'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>92.500 Messages/month");
			}
			if (messagePer30day == 117500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/22'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>117.500 Messages/month");
			}
			if (messagePer30day == 147500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/25'); ?>");
				$("#description-contact-header").html("Basic 1 Month<br>147.500 Messages/month");
			}
		}
		if (modePrice==2) {
			if (messagePer30day == 10000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/2'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>10.000 Messages/month");
			}
			if (messagePer30day == 17500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/5'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>17.500 Messages/month");
			}
			if (messagePer30day == 27500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/8'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>27.500 Messages/month");
			}
			if (messagePer30day == 40000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/11'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>40.000 Messages/month");
			}
			if (messagePer30day == 55000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/14'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>55.000 Messages/month");
			}
			if (messagePer30day == 72500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/17'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>72.500 Messages/month");
			}
			if (messagePer30day == 92500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/20'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>92.500 Messages/month");
			}
			if (messagePer30day == 117500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/23'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>117.500 Messages/month");
			}
			if (messagePer30day == 147500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/26'); ?>");
				$("#description-contact-header").html("Best Seller 2 Months<br>147.500 Messages/month");
			}		
		}
		if (modePrice==3) {
			if (messagePer30day == 10000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/3'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>10.000 Messages/month");
			}
			if (messagePer30day == 17500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/6'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>17.500 Messages/month");
			}
			if (messagePer30day == 27500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/9'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>27.500 Messages/month");
			}
			if (messagePer30day == 40000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/12'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>40.000 Messages/month");
			}
			if (messagePer30day == 55000) {
				$("#link-checkout").attr("href","<?php echo url('checkout/15'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>55.000 Messages/month");
			}
			if (messagePer30day == 72500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/18'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>72.500 Messages/month");
			}
			if (messagePer30day == 92500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/21'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>92.500 Messages/month");
			}
			if (messagePer30day == 117500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/24'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>117.500 Messages/month");
			}
			if (messagePer30day == 147500) {
				$("#link-checkout").attr("href","<?php echo url('checkout/27'); ?>");
				$("#description-contact-header").html("Super Value 3 Months<br>147.500 Messages/month");
			}
		}
	}

  function set_price(){
      if (modePrice==1) {
        $("#choose-price").html($("#monthly-price").html());
      }
      if (modePrice==2) {
        $("#choose-price").html($("#2months-price").html());
      }
      if (modePrice==3) {
        $("#choose-price").html($("#3months-price").html());
      }

      messagePer30day = $(".js-range-slider").val();
      if (messagePer30day == 10000 || messagePer30day == 17500 || messagePer30day == 27500 ) {
        $(".not-10000").removeClass("fa-check");
        $(".not-10000").addClass("fa-times");
      }
      else {
        $(".not-10000").addClass("fa-check");
        $(".not-10000").removeClass("fa-times");
      }

  }
  
  function slider_init(){
    var custom_values = [10000, 17500, 27500, 40000, 55000, 72500, 92500, 117500, 147500];
    $(".js-range-slider").ionRangeSlider({
        grid: true,
        step:100,
        skin: "round",
        values: custom_values,
        onChange: function (data) {
          var is_checked = $(".package-chat").prop('checked');
            // fired on every range slider update
            // console.log(data);
            // console.log($(".js-range-slider").val());
          messagePer30day = $(".js-range-slider").val();
          if (messagePer30day == 10000) {
            $("#monthly-price").html("IDR 195.000");
            $("#2months-price").html("IDR 370.500");
            $("#3months-price").html("IDR 526.500");
            $("#message-per-month").html("500 Messages / day ");
            $(".description-contact").html("10.000 Messages");
          }
          if (messagePer30day == 17500) {
            $("#monthly-price").html("IDR 295.000");
            $("#2months-price").html("IDR 560.500");
            $("#3months-price").html("IDR 796.500");
            $("#message-per-month").html("1.000 Messages / day ");
            $(".description-contact").html("17.500 Messages");
          }
          if (messagePer30day == 27500) {
            $("#monthly-price").html("IDR 395.000");
            $("#2months-price").html("IDR 750.500");
            $("#3months-price").html("IDR 1.066.500");
            $("#message-per-month").html("1.500 Messages / day ");
            $(".description-contact").html("27.500 Messages");
          }
          if (messagePer30day == 40000) {
            $("#monthly-price").html("IDR 495.000");
            $("#2months-price").html("IDR 940.500");
            $("#3months-price").html("IDR 1.336.500");
            $("#message-per-month").html("1.500 Messages / day ");
            $(".description-contact").html("40.000 Messages");
          }
          if (messagePer30day == 55000) {
            $("#monthly-price").html("IDR 595.000");
            $("#2months-price").html("IDR 1.130.500");
            $("#3months-price").html("IDR 1.606.500");
            $("#message-per-month").html("2.000 Messages / day ");
            $(".description-contact").html("55.000 Messages");
          }
          if (messagePer30day == 72500) {
            $("#monthly-price").html("IDR 695.000");
            $("#2months-price").html("IDR 1.320.500");
            $("#3months-price").html("IDR 1.876.500");
            $("#message-per-month").html("2.500 Messages / day ");
            $(".description-contact").html("72.500 Messages");
          }
          if (messagePer30day == 92500) {
            $("#monthly-price").html("IDR 795.000");
            $("#2months-price").html("IDR 1.510.500");
            $("#3months-price").html("IDR 2.146.500");
            $("#message-per-month").html("3.000 Messages / day ");
            $(".description-contact").html("92.500 Messages");
          }
          if (messagePer30day == 117500) {
            $("#monthly-price").html("IDR 895.000");
            $("#2months-price").html("IDR 1.700.500");
            $("#3months-price").html("IDR 2.416.500");
            $("#message-per-month").html("4.000 Messages / day ");
            $(".description-contact").html("117.500 Messages");
          }
          if (messagePer30day == 147500) {
            $("#monthly-price").html("IDR 995.000");
            $("#2months-price").html("IDR 1.890.500");
            $("#3months-price").html("IDR 2.686.500");
            $("#message-per-month").html("5.000 Messages / day ");
            $(".description-contact").html("147.500 Messages");
          }

          if(is_checked == true)
          {
            change_chat_pricing();
          }
          else
          {
            set_price();
          }
          
					check_package();
        }
    });
  }
  
  function box_pricing_click(){
    $('body').on('click', '.box-pricing', function (e) {
      $(".box-pricing").removeClass("selected");
      $(this).addClass("selected");
      modePrice = $(this).attr("data-attr");
      set_price();
			check_package();
    });
  }

  function chatPricing()
  {
    $(".package-chat").click(function(){
      change_chat_pricing();
      check_package();
    });
  }

  function change_chat_pricing()
  {
    var is_checked = $(".package-chat").prop('checked');
    month_price = get_filter_price($("#monthly-price"));
    month_2_price = get_filter_price($("#2months-price"));
    month_3_price = get_filter_price($("#3months-price"));

    if(is_checked == true)
    {
      month_price += 100000;
      month_2_price += 200000;
      month_3_price += 300000;
    }
    else
    {
      month_price -= 100000;
      month_2_price -= 200000;
      month_3_price -= 300000;
    } 

    $("#monthly-price").html(format2(month_price,'IDR '));
    $("#2months-price").html(format2(month_2_price,'IDR '));
    $("#3months-price").html(format2(month_3_price,'IDR '));
    set_price();
  }

  //http://jsfiddle.net/hAfMM/9571/
  /* to format number */
  function format2(n, currency) {
    currency = currency + n.toFixed(1).replace(/(\d)(?=(\d{3})+\.)/g, '$1.');
    var len = currency.length -2;
    currency = currency.slice(0,len);
    return currency;
  }

  function get_filter_price(selector)
  {
    selector = selector.text().split(" ")[1]; //remove idr
    selector = parseInt(selector.replace(/\./g, '')); //convert string to integer
    return selector;
  }

</script>
@endsection
