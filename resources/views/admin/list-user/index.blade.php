@extends('layouts.admin')

@section('content')
<script type="text/javascript">
  var table;
  var tableLog;

  function refresh_page(){
    table.destroy();
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-user/load-user') ?>",
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
                  responsive : true,
                  destroy: true,
                  "order": [],
                });
      }
    });
  }

  function add_user(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-user/add-user') ?>",
      data : $('#formUser').serialize(),
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

          $('#pesan').html(data.message);
          $('#pesan').addClass('alert-success');
          $('#pesan').removeClass('alert-warning');
          $('#pesan').show();
        } else {
          $('#pesan').html(data.message);
          $('#pesan').removeClass('alert-success');
          $('#pesan').addClass('alert-warning');
          $('#pesan').show();
        }
      }
    });
  }

  function edit_user(){
    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-user/edit-user') ?>",
      data : $('#formUser').serialize(),
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

          $('#pesan').html(data.message);
          $('#pesan').addClass('alert-success');
          $('#pesan').removeClass('alert-warning');
          $('#pesan').show();
        } else {
          $('#pesan').html(data.message);
          $('#pesan').removeClass('alert-success');
          $('#pesan').addClass('alert-warning');
          $('#pesan').show();
        }
      }
    });
  }

  function get_log(){
    tableLog.destroy();

    $.ajax({
      type : 'GET',
      url : "<?php echo url('/list-user/view-log') ?>",
      data : { id : $('#idlog').val() },
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
        $('#content-log').html(data.view);

        tableLog = $('#tableLog').DataTable({
                      destroy: true,
                      "order": [],
                  });
      }
    });
  }
</script>

<section id="tabs" class="col-md-10 offset-md-1 col-12 pl-0 pr-0 project-tab" style="margin-top:30px;margin-bottom: 120px;">
  <div class="container body-content-mobile main-cont">
    <div class="row">

    <div class="col-md-11">

      <h2><b>Users</b></h2>  
      
      <h5>
        Show you all users
      </h5>
      

      <div id="pesan" class="alert"></div>

      <br>  

      <div class="form-group">
        <div id="user-charts" style="height: 300px; width: 100%;"></div>
      </div>

      <br>

      <div class="form-group">
        <button class="btn btn-primary btn-add mb-4" data-toggle="modal" data-target="#add-user">
          Add User
        </button>
      
        <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#modal-add-user">
          Add User (Excel)
        </button>
      </div>

      <form>
        <table class="table" id="myTable">
          <thead align="center">
            <th>
              Name
            </th>
            <th>
              Email
            </th>
            <th>
              Username
            </th>
            <th>
              Status
            </th>
            <th>
              Membership
            </th>
            <th>
              Day left
            </th>
            <th>
              Created
            </th>
            <th>
              Action
            </th>
          </thead>
          <tbody id="content">
          </tbody>
        </table>

        <div id="pager"></div>    
      </form>
    </div>
  </div>
</div>

<!-- Modal View Log -->
<div class="modal fade" id="view-log" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Log
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table" id="tableLog">

          <input type="hidden" name="idlog" id="idlog">

          <thead align="center">
            <th>Type</th>
            <th>Value</th>
            <th>Keterangan</th>
            <th>Created_at</th>
          </thead>
          <tbody id="content-log"></tbody>
        </table>
      </div>
    </div>
      
  </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="add-user" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Add User
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="formUser">
          @csrf

          <input type="hidden" name="id_edit" id="id_edit">

          <div class="form-group row">
            <label class="col-md-3 col-12">
              <b>Full Name</b> 
            </label>
            <div class="col-md-9 col-12">
              <input type="text" class="form-control" name="name" id="name">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-12">
              <b>Email</b> 
            </label>
            <div class="col-md-9 col-12">
              <input type="text" class="form-control" name="email" id="email">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-12">
              <b>Status</b> 
            </label>
            <div class="col-md-9 col-12">
              <select class="form-control" name="is_admin" id="is_admin">
                <option value="1">Admin</option>
                <option value="0">User</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-12">
              <b>Membership</b> 
            </label>
            <div class="col-md-9 col-12">
              <select class="form-control" name="membership" id="membership">
                <option value="free">Free</option>
                <option value="pro">Pro</option>
                <option value="popular">Popular</option>
                <option value="elite">Elite</option>
                <option value="super">Super</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-12 password-field">
              <b>Password</b> 
            </label>
            <div class="col-md-9 col-12">
              <input type="password" class="form-control password-field" name="password" id="password">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 col-12 password-field">
              <b>Confirm Password</b> 
            </label>
            <div class="col-md-9 col-12">
              <input id="password-confirm" type="password" class="form-control password-field" name="password_confirmation"  required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" id="foot">
        <button class="btn btn-primary" id="btn-add-user" data-dismiss="modal">
          Add
        </button>
        <button class="btn" data-dismiss="modal">
          Cancel
        </button>
      </div>
    </div>
      
  </div>
</div>


<!-- Modal Add User Free Trial Excel-->
<div class="modal fade" id="modal-add-user" role="dialog">
  <div class="modal-dialog">
    
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modaltitle">
          Add User
        </h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data"id="form-add-user">
          {{csrf_field()}}
          <div class="form-group">
            <label class="control-label col-md-5">Attach File Excel</label>
            <div class="col-md-5">
              <label class="btn btn-default btn-file">
                <input type="file" name="import_file" >
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-5" for="Day">Trial Day</label>
            <div class="col-md-5">
              <input type="number" name="time_d" class="form-control" placeholder="active time">
            </div>
          </div>
        </form>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Cancel
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-primary" id="btn-add-user-free-trial">
          Add
        </button>
      </div>
    </div>
  </div>
</div>
</section>

<script type="text/javascript">

  window.onload = function () {

    var users = [];
    $.each(<?php echo json_encode($users);?>, function( i, item ) {
        users.push({'x': new Date(i), 'y': item});
    });

    var chart = new CanvasJS.Chart("user-charts", {
      animationEnabled: true,
      theme: "light2",
      title:{
        text: "Users Statistics",
        fontFamily: "Nunito,sans-serif"
      },
      axisY: {
          titleFontFamily: "Nunito,sans-serif",
          titleFontSize : 14,
          title : "Total registered users",
          titleFontColor: "#b7b7b7",
          includeZero: false
      },
      data: [{        
        type: "line",       
        dataPoints: users
      }]
    });
    chart.render();
    //{x : new Date('2019-12-04'), y: 520, indexLabel: "highest",markerColor: "red", markerType: "triangle" },
  }

  $( "body" ).on( "click", "#btn-add-user-free-trial", function() {
    var uf = $('#form-add-user');
    var fd = new FormData(uf[0]);
    $.ajax({
      url: "{{url('import-excel-user')}}",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'post',
      data : fd,
      processData:false,
      contentType: false,
      beforeSend: function(result) {
        $("#div-loading").show();
      },
      dataType: 'text',
      success: function(result)
      {
        var data = jQuery.parseJSON(result);
        /*if(data.status=='error'){
          $('#pesan').html('<div class="alert alert-warning"><strong>Warning!</strong> '+data.message+'</div>');
        } else {
          $('#pesan').html('<div class="alert alert-success"><strong>Success!</strong> '+data.message+'</div>');
        }*/
        $("#div-loading").hide();
        alert(data.message);
      }        
    });
  });
  
  $( "body" ).on( "click", ".btn-edit", function() {
    $('#modaltitle').html('Edit User');

    $('#name').val($(this).attr('data-name'));
    $('#email').val($(this).attr('data-email'));
    $('#username').val($(this).attr('data-username'));
    $('#is_admin').val($(this).attr('data-is_admin'));
    $('#membership').val($(this).attr('data-membership'));

    
    $('.password-field').hide();
    
    $('#id_edit').val($(this).attr('data-id'));

    $('#add-user').modal('show');
  });

  $( "body" ).on( "click", ".btn-add", function() 
  {
    $('#modaltitle').html('Add User');
    
    $('#name').val('');
    $('#email').val('');
    $('#username').val('');
    $('#is_admin').val('Admin');
    $('#membership').val('Free');
    $('.password-field').show();
    $('#password').val('');
    $('#password-confirm').val('');

    $('#id_edit').val('');

    $('#add-user').modal('show');
  });

  $( "body" ).on( "click", "#btn-add-user", function() {
    if($('#id_edit').val()==''){
      add_user();
    } else {
      edit_user();
    }
  });


  $( "body" ).on( "click", ".btn-log", function() {
    $('#idlog').val($(this).attr('data-id'));
    get_log();
  });

  $(document).ready(function() {
    table = $('#myTable').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });

    tableLog = $('#tableLog').DataTable({
                responsive : true,
                destroy: true,
                "order": [],
            });
            
    // $.fn.dataTable.moment( 'ddd, DD MMM YYYY' );
    moment( 'ddd, DD MMM YYYY' );

    refresh_page();

    // $('.formatted-date').datepicker({
      // dateFormat: 'yy/mm/dd',
    // });
  });

  
</script>
@endsection