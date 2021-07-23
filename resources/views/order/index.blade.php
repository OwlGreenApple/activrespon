@extends('layouts.app')

@section('content')
<link href="{{ asset('/assets/css/order.css') }}" rel="stylesheet" />
<script type="text/javascript">

  /* function tidak jadi dipake, karena pake thank you page reload page
  function confirm_payment()
  {
    var form = $('#formUpload')[0];
    var formData = new FormData(form);
    $.ajax({
      type : 'POST',
      url : "<?php echo url('/orders/confirm-payment') ?>",
      data: formData,
      dataType: 'json',
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(data) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        
        if(data.status=='success'){
          $('#pesan').html(data.message);
          $('#pesan').removeClass('alert-warning');
          $('#pesan').addClass('alert-success');
          $('#pesan').show();

          refresh_page();
        } else {
          $('#pesan').html(data.message);
          $('#pesan').removeClass('alert-success');
          $('#pesan').addClass('alert-warning');
          $('#pesan').show();
        }
      }
    });  
  }*/
</script>



<div class="container mb-5 main-cont" style="">
  <div class="row">
    <div class="col-md-12">

      <h2><b>Orders</b></h2>  
      
      <h5>
        Show you previous history orders
        <button type="button" class="btn btn-primary btn-confirm" data-toggle="modal" data-target="#transfer-information" style="font-size: 13px; padding: 5px 8px;">
          Payment / Transfer information
        </button>        
      </h5>
      
      <hr>
    </div>
      @if (session('error') )
    <div class="col-md-12 ">
      <div id="pesan" class="alert alert-danger">
          {{session('error')}}
        </div>
    </div>
      @endif

    <div class="col-md-12">
      <form class="responsive" id="content">
        @if($orders->count() > 0)
          @include('order.content')
        @else
          <div class="alert bg-dashboard cardlist">
            You don't have any order yet, please make order <a href="{{ url('pricing') }}">Here</a>
          </div>
        @endif
      </form>
    </div>

  </div>
</div>

<!-- Modal Transfer Information -->
<div class="modal fade" id="transfer-information" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Transfer Information
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

          <p class="card-text">
            Silahkan melakukan Transfer Bank ke
          </p> 
          <h2>8290-336-261</h2>
          <p class="card-text">
            BCA <b>Sugiarto Lasjim</b>
          </p>
          <p class="card-text">
            Setelah Transfer, silahkan Klik tombol confirm payment di bawah ini <br> atau Email bukti Transfer anda ke <b>info@activomni.com</b> <br>
            Admin kami akan membantu anda max 1x24 jam
          </p>

      </div>
      <div class="modal-footer" id="foot">
        <button class="btn" data-dismiss="modal">
          Ok
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Confirm Delete -->
<div class="modal fade" id="confirm-delete" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Delete Confirmation
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete?

        <input type="hidden" name="id_delete" id="id_delete">
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" id="btn-delete-ok" data-dismiss="modal">
          Yes
        </button>
        <button class="btn" data-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Confirm payment -->
<div class="modal fade" id="confirm-payment" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Confirm payment
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="formUpload" enctype="multipart/form-data" method="POST" action="{{ url('order-confirm-payment') }}">
        <div class="modal-body">
          @csrf
          <input type="hidden" name="id_confirm" id="id_confirm">

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Order No</b>
            </label>

            <span class="col-md-6 col-12" id="mod-no_order">
            </span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Package</b>
            </label>

            <span class="col-md-6 col-12" id="mod-package">
            </span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Total</b>
            </label>

            <span class="col-md-6 col-12" id="mod-total"></span>
          </div>

          <div class="form-group" id="div-discount">
            <label class="col-md-3 col-12">
              <b>Discount</b>
            </label>

            <span class="col-md-6 col-12" id="mod-discount">
            </span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Date</b> 
            </label>

            <span class="col-md-6 col-12" id="mod-date"></span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12 float-left">
              <b>Upload Image</b> 
            </label>

            <div class="col-md-6 col-12 float-left">
              <input type="file" name="buktibayar">
            </div>
          </div>
          <div class="clearfix mb-3"></div>
          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Notes</b> 
            </label>
            <div class="col-md-12 col-12">
              <textarea class="form-control" name="keterangan"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" id="foot">
          <input type="submit" class="btn btn-primary" id="btn-confirm-ok" value="Confirm">
          <button class="btn" data-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>
    </div>
      
  </div>
</div>


<script type="text/javascript">

  $(document).ready(function() {
    // refresh_page();
    pagination();
  });

  /*function refresh_page(){
    $.ajax({
      type : 'GET',
      url : "<php echo url('/order/load-order') ?>",
      dataType: 'html',
      beforeSend: function() {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success: function(result) {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        $('#content').html(result);
        // $('#pager').html(data.pager);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        console.log(xhr.responseText);
      }
    });
  }*/

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
          getActiveButtonByUrl(url);
          $('#content').html(data);
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

  $( "body" ).on( "click", ".view-details", function() {
    var id = $(this).attr('data-id');

    $('.details-'+id).toggleClass('d-none');
  });
  
  $( "body" ).on( "click", ".btn-search", function() {
    currentPage = '';
    refresh_page();
  });

  $( "body" ).on( "click", ".btn-confirm", function() {
    $('#id_confirm').val($(this).attr('data-id'));
    $('#mod-no_order').html($(this).attr('data-no-order'));
    $('#mod-package').html($(this).attr('data-package'));

    var total = parseInt($(this).attr('data-total'));
    $('#mod-total').html('Rp. ' + total.toLocaleString());
    var diskon = parseInt($(this).attr('data-discount'));
		if (diskon == 0 ) {
			$("#div-discount").hide();
		}
    $('#mod-discount').html('Rp. ' + diskon.toLocaleString());
    $('#mod-date').html($(this).attr('data-date'));

    var keterangan = '-';
   // console.log($(this).attr('data-keterangan'));
    if($(this).attr('data-keterangan')!='' || $(this).attr('data-keterangan')!=null){
      keterangan = $(this).attr('data-keterangan');
    }

    $('#mod-keterangan').html(keterangan);
  });

  // $( "body" ).on( "click", "#btn-confirm-ok", function() 
  // {
    // confirm_payment();
  // });

  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

  $( "body" ).on( "click", ".btn-delete", function() {
    $('#id_delete').val($(this).attr('data-id'));
  });

  $( "body" ).on( "click", "#btn-delete-ok", function() {
    delete_order();
  });

  $(document).on('click', '.checkAll', function (e) {
    $('input:checkbox').not(this).prop('checked', this.checked);
  });

</script>
@endsection