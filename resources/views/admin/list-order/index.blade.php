@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-order/load-order') ?>",
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

  function delete_order(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-order/delete') ?>",
      data: {
        id : $('#id_delete').val(),
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

        if(data.status=='success'){
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

  function confirm_order(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-order/confirm') ?>",
      data: { id:$('#id_confirm').val() },
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

      <h2><b>Orders</b></h2>  
      
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
              No Order
            </th>
            <th data-priority="2" action="email all">
              Email
            </th>
            <th action="package none">
              Package
            </th>
            <th action="total all">
              Harga
            </th>
            <th action="discount all">
              Discount
            </th>
            <th action="grand_total all">
              Total
            </th>
            <th action="created_at none">
              Date
            </th>
            <th class="all">
              Bukti Bayar
            </th>
            <th action="keterangan none">
              Keterangan
            </th>
            <th data-priority="3" action="status all">
              Status
            </th>
          </thead>
          <tbody id="content"></tbody>
        </table>

        <div id="pager"></div>    
      </form>
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

<!-- Modal Confirm Order -->
<div class="modal fade" id="confirm-order" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Confirm Order
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Confirm this order? 

        <input type="hidden" name="id_confirm" id="id_confirm">
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" id="btn-confirm-ok" data-dismiss="modal">
          Confirm
        </button>
        <button class="btn" data-dismiss="modal">
          Cancel
        </button>
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
  });

  $( "body" ).on( "click", "#btn-confirm-ok", function() 
  {
    confirm_order();
  });

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

  $(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    currentPage = $(this).attr('href');
    refresh_page();
  });
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