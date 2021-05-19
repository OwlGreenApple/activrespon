@extends('layouts.app')

@section('content')

<div class="table-responsive container">
  <div id="content"><!-- display data --></div>
</div>

<!-- Modal Preview Payment Proof -->
<div class="modal fade" id="payment_proof" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Bukti Pembayaran
        </h5>
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
      </div>
      <div class="modal-body">
        <img class="w-100" id="bukti_bayar" />
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn" data-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
      
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
      // switch_account();
      display_data_user();
      view_bukti_pembayaran();
  });

  function switch_account()
  {
    $("#user-data").click(function(){
      display_data_user(0);
    });

    $("#user-del").click(function(){
      display_data_user(1)
    });
  }

  /*DISPLAY AVAILABLE / ACTIVE USER*/
  function display_data_user()
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("reseller-data") }}',
      dataType : 'html',
      beforeSend : function(){
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(data)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $("#content").html(data);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }

  function view_bukti_pembayaran()
  {
    $("body").on("click",".preview_payment",function(){
      var img_url = $(this).attr('data-img');
      $("#payment_proof").modal();
      $("#bukti_bayar").attr('src',img_url);
    });
  }

</script>
@endsection