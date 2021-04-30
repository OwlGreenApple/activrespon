@extends('layouts.app')

@section('content')

<div class="table-responsive container">
  <div class="mb-3">
    <a id="user-data" class="btn btn-info btn-sm">Active Users</a>
    <a id="user-del" class="btn btn-warning btn-sm">Inactive Users</a>
  </div>
  <div id="content"><!-- display data --></div>
</div>

<script type="text/javascript">
  $(document).ready(function()
  {
      switch_account();
      display_data_user(0);
      delete_device();
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
  function display_data_user(is_del)
  {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
      type : 'GET',
      url : '{{ url("reseller-data") }}',
      data : {"is_del":is_del},
      dataType : 'html',
      success : function(data)
      {
        $("#content").html(data);
      },
      error : function(xhr)
      {
        console.log(xhr);
      },
      complete : function(xhr,status)
      {
        if(status == 'success')
        {
          var status = '<h5 class="text-primary">Active Users Page</h5>';
          if(is_del == 1)
          {
            status = '<h5 class="text-danger">Inactive Users Page</h5>';
          }

          setTimeout(function(){
            $("#data_customer_length").append('<label class="status_page ml-2">'+status+'</label>')
          },200);
        }
      }
    });
  }

  /*DELETE DEVICE*/
  function delete_device()
  {
    $("body").on("click",".del-customer",function(){
      var id = $(this).attr('id');
      var conf = confirm('Apakah anda yakin mau menghapus?');

      if(conf == true)
      {
        exc_delete_device(id);
      }
      else
      {
        return false;
      }
      
    });
  }

  function exc_delete_device(phone_id)
  {
    $.ajax({
      type : 'GET',
      url : '{{ url("api/delete-device") }}',
      data : {"phone_id":phone_id},
      dataType : 'html',
      beforeSend : function() 
      {
        $('#loader').show();
        $('.div-loading').addClass('background-load');
      },
      success : function(data)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        display_data_user(0);
      },
      error : function(xhr)
      {
        $('#loader').hide();
        $('.div-loading').removeClass('background-load');
        alert('Sorry our server is too busy, please try again later');
        console.log(xhr);
      }
    });
  }

</script>
@endsection