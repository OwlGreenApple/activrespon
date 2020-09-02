@extends('layouts.admin')

@section('content')


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
  });

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