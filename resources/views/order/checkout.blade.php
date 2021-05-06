@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('assets/css/checkout.css')}}">

<div class="container" style="margin-top:50px; margin-bottom:100px">
  <div class="row justify-content-center">
    <div class="col-md-8 col-12">
      <div class="card-custom">
        <div class="card cardpad">


          @if (session('error') )
            <div class="col-md-12 alert alert-danger">
              {{session('error')}}
            </div>
          @endif

          <?php if (Auth::check()) {?>
         
						<form method="POST" action="{{url('submit-checkout-login')}}">
            <?php } else {?>
							<form method="POST" action="{{url('submit-checkout')}}">
              <?php }?>
              {{ csrf_field() }}
              <input type="hidden" id="price" name="price">
              <input type="hidden" id="namapaket" name="namapaket">
              <input type="hidden" id="namapakettitle" name="namapakettitle">
							<input type="hidden" id="priceupgrade" name="priceupgrade">
              <input type="hidden" value="{{ $chat }}" name="chat">
              <h2 class="Daftar-Disini">Choose your package</h2>
							
              <div class="form-group">
                <div class="col-12 col-md-12">
                  <label class="text" for="formGroupExampleInput">Your package:</label>
                  <select class="form-control" name="idpaket" id="select-auto-manage">
                        <option class="" data-price="195000" data-paket="basic1" data-paket-title="Basic - 10.000 Messages" value="1" <?php if ($id==1) echo "selected" ; ?>>
                          Basic - 10.000 Messages - IDR 195.000,-/30 days
                        </option>
                        <option class="" data-price="370500" data-paket="bestseller1" data-paket-title="Best Seller - 10.000 Messages" value="2" <?php if ($id==2) echo "selected" ; ?>>
                          Best Seller - 10.000 Messages - IDR 370.500,-/60 days 
                        </option>
                        <option class="" data-price="526500" data-paket="supervalue1" data-paket-title="Super Value - 10.000 Messages" value="3" <?php if ($id==3) echo "selected" ; ?>>
                          Super Value - 10.000 Messages - IDR 526.500,-/90 days 
                        </option>

                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="295000" data-paket="basic2" data-paket-title="Basic - 17.500 Messages" value="4" <?php if ($id==4) echo "selected" ; ?>>
                          Basic - 17.500 Messages - IDR 295.000,-/30 days
                        </option>
                        <option class="" data-price="560500" data-paket="bestseller2" data-paket-title="Best Seller - 17.500 Messages" value="5" <?php if ($id==5) echo "selected" ; ?>>
                          Best Seller - 17.500 Messages - IDR 560.500,-/60 days 
                        </option>
                        <option class="" data-price="796500" data-paket="supervalue2" data-paket-title="Super Value - 17.500 Messages" value="6" <?php if ($id==6) echo "selected" ; ?>>
                          Super Value - 17.500 Messages - IDR 796.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="395000" data-paket="basic3" data-paket-title="Basic - 27.500 Messages" value="7" <?php if ($id==7) echo "selected" ; ?>>
                          Basic - 27.500 Messages - IDR 395.000,-/30 days
                        </option>
                        <option class="" data-price="750500" data-paket="bestseller3" data-paket-title="Best Seller - 27.500 Messages" value="8" <?php if ($id==8) echo "selected" ; ?>>
                          Best Seller - 27.500 Messages - IDR 750.500,-/60 days 
                        </option>
                        <option class="" data-price="1066500" data-paket="supervalue3" data-paket-title="Super Value - 27.500 Messages" value="9" <?php if ($id==9) echo "selected" ; ?>>
                          Super Value - 27.500 Messages - IDR 1.066.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="495000" data-paket="basic4" data-paket-title="Basic - 40.000 Messages" value="10" <?php if ($id==10) echo "selected" ; ?>>
                          Basic - 40.000 Messages - IDR 495.000,-/30 days
                        </option>
                        <option class="" data-price="940500" data-paket="bestseller4" data-paket-title="Best Seller - 40.000 Messages" value="11" <?php if ($id==11) echo "selected" ; ?>>
                          Best Seller - 40.000 Messages - IDR 940.500,-/60 days 
                        </option>
                        <option class="" data-price="1336500" data-paket="supervalue4" data-paket-title="Super Value - 40.000 Messages" value="12" <?php if ($id==12) echo "selected" ; ?>>
                          Super Value - 40.000 Messages - IDR 1.336.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="595000" data-paket="basic5" data-paket-title="Basic - 55.000 Messages" value="13" <?php if ($id==13) echo "selected" ; ?>>
                          Basic - 55.000 Messages - IDR 595.000,-/30 days
                        </option>
                        <option class="" data-price="1130500" data-paket="bestseller5" data-paket-title="Best Seller - 55.000 Messages" value="14" <?php if ($id==14) echo "selected" ; ?>>
                          Best Seller - 55.000 Messages - IDR 1.130.500,-/60 days 
                        </option>
                        <option class="" data-price="1606500" data-paket="supervalue5" data-paket-title="Super Value - 55.000 Messages" value="15" <?php if ($id==15) echo "selected" ; ?>>
                          Super Value - 55.000 Messages - IDR 1.606.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="695000" data-paket="basic6" data-paket-title="Basic - 72.500 Messages" value="16" <?php if ($id==16) echo "selected" ; ?>>
                          Basic - 72.500 Messages - IDR 695.000,-/30 days
                        </option>
                        <option class="" data-price="1320500" data-paket="bestseller6" data-paket-title="Best Seller - 72.500 Messages" value="17" <?php if ($id==17) echo "selected" ; ?>>
                          Best Seller - 72.500 Messages - IDR 1.320.500,-/60 days 
                        </option>
                        <option class="" data-price="1876500" data-paket="supervalue6" data-paket-title="Super Value - 72.500 Messages" value="18" <?php if ($id==18) echo "selected" ; ?>>
                          Super Value - 72.500 Messages - IDR 1.876.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="795000" data-paket="basic7" data-paket-title="Basic - 92.500 Messages" value="19" <?php if ($id==19) echo "selected" ; ?>>
                          Basic - 92.500 Messages - IDR 795.000,-/30 days
                        </option>
                        <option class="" data-price="1510500" data-paket="bestseller7" data-paket-title="Best Seller - 92.500 Messages" value="20" <?php if ($id==20) echo "selected" ; ?>>
                          Best Seller - 92.500 Messages - IDR 1.510.500,-/60 days 
                        </option>
                        <option class="" data-price="2146500" data-paket="supervalue7" data-paket-title="Super Value - 92.500 Messages" value="21" <?php if ($id==21) echo "selected" ; ?>>
                          Super Value - 92.500 Messages - IDR 2.146.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="895000" data-paket="basic8" data-paket-title="Basic - 117.500 Messages" value="22" <?php if ($id==22) echo "selected" ; ?>>
                          Basic - 117.500 Messages - IDR 895.000,-/30 days
                        </option>
                        <option class="" data-price="1700500" data-paket="bestseller8" data-paket-title="Best Seller - 117.500 Messages" value="23" <?php if ($id==23) echo "selected" ; ?>>
                          Best Seller - 117.500 Messages - IDR 1.700.500,-/60 days 
                        </option>
                        <option class="" data-price="2416500" data-paket="supervalue8" data-paket-title="Super Value - 117.500 Messages" value="24" <?php if ($id==24) echo "selected" ; ?>>
                          Super Value - 117.500 Messages - IDR 2.416.500,-/90 days 
                        </option>
												
                        <option class="">
                          -----------------------------------------------------------------------------------------------------------------
                        </option>
												
                        <option class="" data-price="995000" data-paket="basic9" data-paket-title="Basic - 147.500 Messages" value="25" <?php if ($id==25) echo "selected" ; ?>>
                          Basic - 147.500 Messages - IDR 995.000,-/30 days
                        </option>
                        <option class="" data-price="1890500" data-paket="bestseller9" data-paket-title="Best Seller - 147.500 Messages" value="26" <?php if ($id==26) echo "selected" ; ?>>
                          Best Seller - 147.500 Messages - IDR 1.890.500,-/60 days 
                        </option>
                        <option class="" data-price="2686500" data-paket="supervalue9" data-paket-title="Super Value - 147.500 Messages" value="27" <?php if ($id==27) echo "selected" ; ?>>
                          Super Value - 147.500 Messages - IDR 2.686.500,-/90 days 
                        </option>
												
                  </select>
                </div>
              </div>

              @if($is_coupon == false)
              <div class="form-group">
                <div class="col-md-12 col-12">
                  <label class="label-title-test" for="formGroupExampleInput">
                    Coupon code (optional):
                  </label>

                  <input type="text" class="form-control form-control-lg" name="kupon" id="kupon" placeholder="Kode Kupon Anda" style="width:100%">  
                  <button type="button" class="btn btn-primary btn-kupon  form-control-lg col-md-3 col-sm-12 col-xs-12 mt-3">
                    Apply
                  </button>  
                </div>
              </div>
              @endif;

              <!-- coupon messages / notification -->
              <div class="form-group mb-1">
                <div class="col-md-12 col-12">
                  <div id="pesan" class="alert"></div>
                </div>
              </div>
             
              <div class="form-group">
                <script>
                  dayleft = 0;priceupgrade=0;totalPriceUpgrade=0;
                </script>
                <?php if (Auth::check()) {?>
                  <div class="form-group mb-0 upgrade" style="display: none">
                    <div class="col-md-12 col-12 upgrade-later">
                      <label class="label-title-test" for="">
                        Remaining days (<span class="dayleft"></span>) upgrade :
                      </label>
                      <label id="label-priceupgrade"></label>
                    </div>
                    <div class="col-md-12 col-12 upgrade-later">
                      <label class="label-title-test" for="">
                        Your package upgrade :
                      </label>
                      <label id="package-upgrade"></label>
                    </div>
                    <div class="col-md-12 col-12">
                      <label class="label-title-test" for="">
                        Upgrade :
                      </label>
                       <label class="radio-inline mr-2"><input name="status_upgrade" value="1" type="radio" checked="checked" /> Now</label>
                      <label class="radio-inline"><input type="radio" name="status_upgrade" value="2" /> Later</label>
                    </div>
                  </div> 
                <?php }?>

                <div class="col-md-12 col-12">
                  <label class="label-title-test" for="formGroupExampleInput">
                    Total: 
                  </label>
                  <div class="col-md-12 pl-0">
                    <span class="total" style="font-size:18px"></span>
                  </div>
                  <label class="mt-2">
                    *Your upgrade will be activated as soon as payment confirmed
                  </label> 
                </div>
              </div>

              <div class="form-group">
                <div class="col-12 col-md-12">
                  <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required/>
                  <label for="agree-term" class="label-agree-term text">I agree to all statements in <a href="http://activrespon.com/terms-of-services/" class="term-service" target="_blank">Terms of service</a></label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-12 col-md-12">
                  <input type="submit" name="submit" id="submit" class="col-md-12 col-12 btn btn-primary bsub btn-block" value="Proceed"/>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function() {
    setPricing();
    check_kupon();
    manageSelectPackage();
    applyCoupon();
    setUpgradeOption();
  });

  function setUpgradeOption()
  {
    $("input[name='status_upgrade']").change(function(){
      var value = $(this).val();
      if(value == 2)
      {
        $(".upgrade-later").hide();
      }
      else
      {
        $(".upgrade-later").show();
      }
      check_kupon(value);
    });
  }

  function check_kupon(status_upgrade = null){

    if(status_upgrade == null)
    {
      status_upgrade = $("input[name='status_upgrade']").val();
    }

    $.ajax({
      type: 'POST',
      url: "{{url('/check-coupon')}}",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        harga : $('#price').val(),
        kupon : $('#kupon').val(),
        reseller_coupon : "{{ Request::segment(4) }}",
        idpaket : $( "#select-auto-manage" ).val(),
        status_upgrade : status_upgrade,
        chat : '{{ $chat }}',
        namapaket :  $("#select-auto-manage").find("option:selected").attr("data-paket")
      },
      dataType: 'text',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');

        var data = jQuery.parseJSON(result);

        $('#pesan').html(data.message);
        $('#pesan').show();
        if (data.message=="") {
          $('#pesan').hide();
        }
        
        if (data.status == 'success') {
          $('.total').html('IDR '+' <strike>'+formatNumber(data.price)+'</strike> '+formatNumber(data.total));
          $('#pesan').removeClass('alert-danger');
          $('#pesan').addClass('alert-success');

         if(status_upgrade == 2)
          {
            $(".upgrade").show();
            $("#package-upgrade").hide();
            $("#label-priceupgrade").hide();
            $("input[name='status_upgrade']").prop('disabled',false);
          }
          else if(data.membership == 'upgrade')
          {
            $(".upgrade").show();
            $("#package-upgrade").show();
            $("#label-priceupgrade").show();
            $("input[name='status_upgrade']").prop('disabled',false);
            $(".dayleft").html(data.dayleft);
            $("#package-upgrade").html("IDR "+formatNumber(data.upgrade_price));
            $("#label-priceupgrade").html("IDR "+formatNumber(data.packageupgrade));
          }
          else //downgrade
          {
            $("#package-upgrade").hide();
            $("#label-priceupgrade").hide();
            $("input[name='status_upgrade']").prop('disabled',true);
            $(".upgrade").hide();
          }
        } 
        /*else if (data.status == 'success-paket') {
          $('.total').html('IDR ' + formatNumber(parseInt(data.total)+parseInt(totalPriceUpgrade)));
          $('#pesan').removeClass('alert-danger');
          $('#pesan').addClass('alert-success');
          
          flagSelect = false;
          $("#select-auto-manage option").each(function() {
            console.log($(this).val());
            if ($(this).val() == data.paketid) {
              flagSelect = true;
            }
          });

          if (flagSelect == false) {
            labelPaket = data.paket;
            if (data.kodekupon=="SPECIAL12") {
              labelPaket = "Paket Special Promo 1212 - IDR 295.000";
            }
            $('#select-auto-manage').append('<option value="'+data.paketid+'" data-price="'+data.dataPrice+'" data-paket="'+data.dataPaket+'" selected="selected">'+labelPaket+'</option>');
          }
          $("#price").val(data.dataPrice);
          $("#namapaket").val(data.dataPaket);
          
          $('#select-auto-manage').val(data.paketid);
          $( "#select-auto-manage" ).change();
        }*/
        else {
          $('#pesan').removeClass('alert-success');
          $('#pesan').addClass('alert-danger');
          $('.total').html('IDR '+formatNumber(data.total));
        }
      },
      error: function(xhr,atrribute,throwable)
      {
         $('#loader').hide();
         $('.div-loading').removeClass('background-load');
         console.log(xhr.responseText);
      }
    });
  }
  
	function formatNumber(num) {

    num = parseInt(num);

    if(isNaN(num) == true)
    {
       return '';
    }
    else
    {
       return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    }
	}

  function setPricing()
  {
    var chat = '{{ $chat }}';
    var price = parseInt($("#select-auto-manage").find("option:selected").attr("data-price"));
    var namapaket = $("#select-auto-manage").find("option:selected").attr("data-paket");
    var namapakettitle = $("#select-auto-manage").find("option:selected").attr("data-paket-title");

    $("#price").val(price);
    $("#namapaket").val(namapaket);
    $("#namapakettitle").val(namapakettitle);
  }

  function applyCoupon()
  {
    $(".btn-kupon").click(function(){
      var value = $("input[name='status_upgrade']:checked").val();
      check_kupon(value);

      if(value == 2)
      {
        $(".upgrade-later").hide();
      }
      else
      {
        $(".upgrade-later").show();
      }
    });
  }

  function manageSelectPackage()
  {
    $( "#select-auto-manage" ).change(function() {
      setPricing();
      var value = $("input[name='status_upgrade']:checked").val();
      check_kupon(value);

      if(value == 2)
      {
        $(".upgrade-later").hide();
      }
      else
      {
        $(".upgrade-later").show();
      }
    })
  }

/*
  $(document).ready(function() {
    $("body").on("click", ".btn-kupon", function() {
      check_kupon();
    });

		<?php if (Auth::check()) {?>
			dayleft = <?php echo $dayleft;?>;
			priceupgrade = <?php echo $priceupgrade;?>;
		<?php }?>
		$("#priceupgrade").val(0);

		$( "#select-auto-manage" ).change(function() {
			var price = $(this).find("option:selected").attr("data-price");
			var namapaket = $(this).find("option:selected").attr("data-paket");
			var namapakettitle = $(this).find("option:selected").attr("data-paket-title");
      var wd;

			<?php if (Auth::check()) {?>
				totalPriceUpgrade = dayleft * ((price-priceupgrade)/30);
        totalPriceUpgrade = parseInt(totalPriceUpgrade);
        totalPriceUpgrade = Math.round(totalPriceUpgrade);

				// if (totalPriceUpgrade < 0 ) 
        if (totalPriceUpgrade < 0 ) 
        {
					$("#label-priceupgrade").html("Tidak dapat downgrade");
					totalPriceUpgrade = 0;
          $("input[name='status_upgrade']").prop('disabled',true);
          $(".upgrade").hide();
				}
				else 
        {
          if(dayleft > 1)
          {
            wd = 'days';
          }
          else
          {
            wd = 'day';
          }
          $(".upgrade").show();
          $("input[name='status_upgrade']").prop('disabled',false);
          $(".dayleft").html(dayleft+' '+wd);
					$("#package-upgrade").html("IDR "+formatNumber(price));
          $("#label-priceupgrade").html("IDR "+formatNumber(totalPriceUpgrade));
				}
				$("#priceupgrade").val(totalPriceUpgrade);
			<?php }?>
			
			$("#price").val(price);
			$("#namapaket").val(namapaket);
			$("#namapakettitle").val(namapakettitle);
			// $('#kupon').val("");
			check_kupon();
		});
		$( "#select-auto-manage" ).change();
		$(".btn-kupon").trigger("click");
  });*/
    
</script>


@endsection