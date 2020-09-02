@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-invoice/load') ?>",
      dataType: 'text',
    //   beforeSend: function()
    //   {
    //     $('#loader').show();
    //     $('.div-loading').addClass('background-load');
    //   },
      success: function(result) {
        // $('#loader').hide();
        // $('.div-loading').removeClass('background-load');

        var data = jQuery.parseJSON(result);
        $('#content').html(data.view);
        
        table = $('#myTable').DataTable({
                  responsive : true,
                  destroy: true,
                  "order": [],
                });

      }
    });
  }

  function confirm_invoice()
  {
    var form = $('#formUpload')[0];
    var formData = new FormData(form);
    $.ajax({
      type : 'POST',
      url : "<?php echo url('/list-invoice/confirm'); ?>",
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
  }	
 </script>

<section id="tabs" class="col-md-10 offset-md-1 col-12 pl-0 pr-0 project-tab" style="margin-top:30px;margin-bottom: 120px;">
  <div class="container body-content-mobile main-cont">
    <div class="row">
    <div class="col-md-11">

      <h2><b>WooWA</b></h2>  
      
      <h5>
        Show you previous history orders
      </h5>
      
      <hr>

      <div id="pesan" class="alert"></div>

      <br>  

      <form>
        <table class="table" id="myTable">
          <thead align="center">
            <th data-priority="1" action="no_order all">
              No Invoice
            </th>
            <th action="grand_total all">
              Total
            </th>
            <th action="created_at">
              Created
            </th>
            <th class="all">
              Bukti bayar
            </th>
            <th class="all">
              Keterangan
            </th>
            <th class="all">
            </th>
          </thead>
          <tbody id="content"></tbody>
        </table>

        <div id="pager"></div>    
      </form>
    </div>
  </div>
</div>

<!-- Modal Confirm Invoice -->
<!-- Modal Confirm payment -->
<div class="modal fade" id="confirm-invoice" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Confirm invoice
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form id="formUpload" enctype="multipart/form-data" method="POST" action="{{ url('order-confirm-payment') }}">
        <div class="modal-body">
          @csrf
          <input type="hidden" name="id_confirm" id="id_confirm">

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>No Invoice</b>
            </label>

            <span class="col-md-6 col-12" id="mod-no_invoice">
            </span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Total</b>
            </label>

            <span class="col-md-6 col-12" id="mod-total"></span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Date</b> 
            </label>

            <span class="col-md-6 col-12" id="mod-date"></span>
          </div>

          <div class="form-group">
            <label class="col-md-3 col-12 float-left">
              <b>Bukti Bayar</b> 
            </label>

            <div class="col-md-6 col-12 float-left">
              <input type="file" name="buktibayar">
            </div>
          </div>
          <div class="clearfix mb-3"></div>
          <div class="form-group">
            <label class="col-md-3 col-12">
              <b>Keterangan</b> 
            </label>
            <div class="col-md-12 col-12">
              <textarea class="form-control" name="keterangan"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" id="foot">
          <input type="button" class="btn btn-primary" id="btn-confirm-ok" value="Confirm" data-dismiss="modal">
          <button class="btn" data-dismiss="modal">
            Cancel
          </button>
        </div>
      </form>
    </div>
      
  </div>
</div>

<!-- Modal View Details -->
<div class="modal fade" id="view-details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4>Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <input type="hidden" name="id-detail" id="id-detail">

        <table class="table">
          <thead align="center">
            <th>
              No
            </th>
            <th>
              No WA
            </th>
            <th>No Order</th>
            <!--<th>Total</th>-->
            <th>Month</th>
            <th>Tagihan</th>
            <th>Created order</th>
          </thead>

          <tbody id="content-details"></tbody>

        </table>

        <div id="pager-details"></div>

      </div>

      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

</section>

<script type="text/javascript">
  $( "body" ).on( "click", ".btn-search", function() {
    currentPage = '';
    refresh_page();
  });

  $( "body" ).on( "click", ".btn-confirm", function() {
    $('#id_confirm').val($(this).attr('data-id'));
    $('#mod-no_invoice').html($(this).attr('data-no-invoice'));

    var total = parseInt($(this).attr('data-total'));
    $('#mod-total').html('Rp. ' + total.toLocaleString());
    $('#mod-date').html($(this).attr('data-date'));

    var keterangan = '-';
   // console.log($(this).attr('data-keterangan'));
    if($(this).attr('data-keterangan')!='' || $(this).attr('data-keterangan')!=null){
      keterangan = $(this).attr('data-keterangan');
    }

    $('#mod-keterangan').html(keterangan);
		
  });

  $( "body" ).on( "click", "#btn-confirm-ok", function() 
  {
    confirm_invoice();
  });


  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    currentPage = $(this).attr('href');
    refresh_page();
  });
	
  $( "body" ).on( "click", ".popup-newWindow", function()
  {
    event.preventDefault();
    window.open($(this).attr("href"), "popupWindow", "width=600,height=600,scrollbars=yes");
  });

	//show detail
  $( "body" ).on( "click", ".btn-show", function() {
    var id = $(this).attr('data-id');
    $('#id-detail').val(id);
    refresh_detail();
  
    //$('.list-'+id).toggle();
  });
	
  function refresh_detail(){
    $.ajax({
      url: "<?php echo url('/list-invoice/load-invoice-order'); ?>",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'get',
      data: {
        id : $('#id-detail').val(),
      },
      beforeSend: function(result) {
        $('#loader').css("display","block");
        $('div.overlay').addClass('background-load');
      },
      dataType: 'text',
      success: function(result)
      {
        var data = jQuery.parseJSON(result);
        $('#content-details').html(data.view);
        $('#pager-details').html(data.page);

        $('#loader').css("display","none");
        $('div.overlay').removeClass('background-load');
      }        
    });
  }	
	
  $(document).ready(function() {
    table = $('#myTable').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });
    // $.fn.dataTable.moment( 'ddd, DD MMM YYYY' );

    refresh_page();

    // $('.formatted-date').datepicker({
    //   dateFormat: 'yy/mm/dd',
    // });
  });

	
</script>
@endsection