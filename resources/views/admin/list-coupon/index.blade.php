@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;

  $(document).ready(function() {
    table = $('#myTable').DataTable({
      destroy: true,
      "order": [],
    });
    // $.fn.dataTable.moment( 'ddd, DD MMM YYYY' );

    refresh_page();
  });

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-coupon/load-coupon') ?>",
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
        $('#content').html(data.view);
        
        table = $('#myTable').DataTable({
                destroy: true,
                "order": [],
            });

      }
    });
  }

  function delete_kupon(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-coupon/delete') ?>",
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

  function add_kupon(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-coupon/add') ?>",
      data: $('#formKupon').serialize(),
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

  function edit_kupon(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-coupon/edit') ?>",
      data: $('#formKupon').serialize(),
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

      <h2><b>Coupons</b></h2>  
      
      <h5>
        Show you all coupons
      </h5>
      
      <hr>

      <div id="pesan" class="alert"></div>

      <br>  

      <form>

        <button type="button" class="btn btn-primary btn-add mb-3" data-toggle="modal" data-target="#add-coupon">
          <i class="fas fa-plus"></i> Add Coupons
        </button>

        <table class="table" id="myTable">
          <thead align="center">
            <th>
              Kode Kupon
            </th>
            <th>
              Diskon (Nominal)
            </th>
            <th>
              Diskon (Persen)
            </th>
            <th>
              Jenis Kupon
            </th>
            <th>
              Valid Until
            </th>
            <th>
              Valid To
            </th>
            <th>
              Keterangan 
            </th>
            <th>
              Paket
            </th>
            <th>
              Action
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

<!-- Modal Add Coupon -->
<div class="modal fade" id="add-coupon" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title-coupon">
          Tambah Kupon
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formKupon">
          @csrf
          <input type="hidden" name="id_edit" id="id_edit">
          
          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Kode Kupon</b>
            </label>

            <div class="col-md-8 col-12">
              <input type="text" name="kodekupon" id="kodekupon" class="form-control">
            </div>
          </div> 

          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Jenis Kupon</b>
            </label>

            <div class="col-md-8 col-12">
                  <label class="radio-inline mr-2"><input name="jenis_kupon" value="1" type="radio"checked />Kupon Normal</label>
                  <label class="radio-inline"><input type="radio" name="jenis_kupon" value="2" /> Kupon Upgrade</label>
            </div>
          </div>

          <div class="normal_discount">
            <div class="form-group row">
              <label class="col-md-4 col-12">
                <b>Diskon (Nominal)</b>
              </label>

              <div class="col-md-8 col-12">
                <input type="text" name="diskon_value" id="diskon_value" class="form-control" value="0">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-md-4 col-12">
                <b>Diskon (Persen)</b>
              </label>

              <div class="col-md-8 col-12">
                <input type="text" name="diskon_percent" id="diskon_percent" class="form-control" value="0">
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Valid Until</b>
            </label>

            <div class="col-md-8 col-12">
              <input type="text" name="valid_until" id="valid_until" class="form-control formatted-date">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Valid To</b> 
            </label>

            <div class="col-md-8 col-12">
              <select class="form-control" name="valid_to" id="valid_to">
                <option value="all">All</option>
               <!--  <option value="new">New</option>
                <option value="extend">Extend</option> -->
                <!--
                <option value="package-elite-2">Package Elite 2</option>
                <option value="package-elite-3">Package Elite 3</option>
                <option value="package-elite-5">Package Elite 5</option>
                <option value="package-elite-7">Package Elite 7</option>
                -->
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Paket</b> 
            </label>

            <div class="col-md-8 col-12">
              <select class="form-control" name="package_id" id="package_id">
                <option value="0">All</option>
                @foreach($price as $id=>$row)
                  <option value="{{ $id }}">{{ $row['package'] }} -- {{ number_format($row['price']) }}</option>
                @endforeach
              </select>
            </div>
          </div>  

          <div class="form-group row">
            <label class="col-md-4 col-12">
              <b>Keterangan</b> 
            </label>
            
            <div class="col-md-12 col-12">
              <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" id="btn-add-ok" data-dismiss="modal">
          Add
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
  $(function(){
    $('.formatted-date').datetimepicker({
      format : 'YYYY/MM/DD',
      minDate: new Date()
    }); 

    discountType();
    dismissPaket();
  });

  function dismissPaket()
  {
    $("select[name='package_id'] > option[value='1'], select[name='package_id'] > option[value='2'], select[name='package_id'] > option[value='3']").remove();
  }

  function discountType()
  {
    $("input[name='jenis_kupon']").click(function(){
      var val = $(this).val();

      if(val == 2)
      {
          $(".normal_discount").hide();
          $("input[name='diskon_value'], input[name='diskon_percent']").val(0);
      }
      else
      {
          $(".normal_discount").show();
      }

    });
  }

  $( "body" ).on( "click", ".btn-edit", function() {
    $('#title-coupon').html('Edit Kupon');
    $('#kodekupon').val($(this).attr('data-kodekupon'));
    $('#diskon_value').val($(this).attr('data-nominal'));
    $('#diskon_percent').val($(this).attr('data-persen'));
    $('#valid_until').val($(this).attr('data-validuntil'));
    $('#valid_to').val($(this).attr('data-validto'));
    $('#keterangan').val($(this).attr('data-keterangan'));
    $('#package_id').val($(this).attr('data-paket'));
    
    $('#id_edit').val($(this).attr('data-id'));

    if($(this).attr('data-type') == 1)
    {
      $("input[name='jenis_kupon'][value='1']").prop('checked',true);
      $("input[name='jenis_kupon'][value='2']").prop('checked',false);
      $(".normal_discount").show();
    }
    else
    {
      $("input[name='jenis_kupon'][value='1']").prop('checked',false);
      $("input[name='jenis_kupon'][value='2']").prop('checked',true);
      $(".normal_discount").hide();
      $("input[name='diskon_value'], input[name='diskon_percent']").val(0);
    }

    $('#add-coupon').modal('show');
  });

  $( "body" ).on( "click", ".btn-add", function() 
  {
    $('#title-coupon').html('Tambah Kupon');
    
    $('#kodekupon').val('');
    $('#diskon_value').val(0);
    $('#diskon_percent').val(0);
    $('#valid_until').val('');
    $('#valid_to').val('all');
    $('#keterangan').val('');
    $('#package_id').val(0);

    $('#id_edit').val('');

    $('#add-coupon').modal('show');
  });

  $( "body" ).on( "click", "#btn-add-ok", function() 
  {
    if($('#id_edit').val()==''){
      add_kupon();
    } else {
      edit_kupon();
    }
  });

  $( "body" ).on( "click", ".btn-delete", function() {
    $('#id_delete').val($(this).attr('data-id'));
  });

  $( "body" ).on( "click", "#btn-delete-ok", function() {
    delete_kupon();
  });

</script>
@endsection