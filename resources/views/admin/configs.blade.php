@extends('layouts.admin')

@section('content')

<div class="container">
  <div class="col-lg-8 mx-auto">
    <div id="msg"><!--  --></div>
  </div>
</div>

<div class="container mt-2">
  <div class="col-lg-8 mx-auto">
    <form id="set_delay">
      <div class="form-group">
        <label>Delay setelah mengirim :</label>
        <input type="number" class="form-control" name="msg_delay" value="{{ $cf->msg }}" /> message
      </div> 
      <div class="form-group">
        <label>Waktu delay (dalam detik)</label>
        <input value="{{ $cf->time }}" type="number" class="form-control" name="time_delay" />
      </div>
      <button class="btn btn-primary dly">Simpan</button>
    </form>
  </div>
</div>

<div class="container mt-5">
  <div class="col-lg-8" style="margin-left : auto; margin-right: auto;">
    <table class="table" id="config" style="width : 100%">
      <thead>
        <th>No</th>
        <th>Config Name</th>
        <th>Value</th>
        <th>Action</th>
      </thead>
      <tbody id="display_config"></tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    changeServerStatus();
    displayConfig();
    table();
    save_delay();
  });

  function save_delay()
  {
    $("#set_delay").submit(function(e){
      e.preventDefault();
      var data = $(this).serialize();
      save_delay_act(data);
    });
  }

  function save_delay_act(data)
  {
    $.ajax({
        type : "GET",
        url : "{{ url('save-delay') }}",
        data : data,
        dataType : 'json',
        beforeSend: function()
        {
          $(".dly").prop('disabled',true).html('Loading....');
        },
        success : function(result){
          if(result.err == 0)
          {
            $("#msg").html('<div class="alert alert-success">'+result.msg+'</div>');
          }
          else
          {
            $("#msg").html('<div class="alert alert-danger">'+result.msg+'</div>');
          }
          $(".dly").prop('disabled',false).html('Simpan');
        },
        error : function(xhr)
        {
          $(".dly").prop('disabled',true).html('Error');
          console.log(xhr.responseText);
        }
    });
  }

  function changeServerStatus()
  {
    $("body").on("click",".btn-status",function(){
      var status = $(this).attr('data-status');
      var id = $(this).attr('id');
      var data = {
        'id':id,
        'status':status,
      };

     /* $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });*/
      $.ajax({
          type : "GET",
          url : "{{ url('status-server') }}",
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $(".btn-status").prop('disabled',true).html('Loading....');
          },
          success : function(result){
            if(result.err == 0)
            {
              $("#msg").html('<div class="alert alert-success">'+result.msg+'</div>');
            }
            else
            {
              $("#msg").html('<div class="alert alert-danger">'+result.msg+'</div>');
            }
            $(".btn-status").prop('disabled',false).html('Change');
            displayConfig();
          },
          error : function(xhr)
          {
            $(".btn-status").prop('disabled',true).html('Error');
            console.log(xhr.responseText);
          }
      });
    });
  }

  function displayConfig(){ 
    $.ajax({
      type : "GET",
      url : "{{ url('config-show') }}",
      data : {'superadmin':0},
      dataType : 'html',
      success: function(result)
      {
        $("#display_config").html(result);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function table(){
      $("#config").dataTable();
  }

  function clearForm()
  {
      $("input").val('');
      $("#submit").removeAttr('update');
      $("#submit").html('Insert Config');
      $(".cancel").hide();
  }

</script>
@endsection