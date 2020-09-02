@extends('layouts.admin')

@section('content')

<div class="container mb-2">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="msg"><!-- message --></div>
            <form id="save_config">
               <div class="form-group">
                  <label>Insert Config*</label>
                  <div class="col-sm-12 row">
                    <div class="col-lg-3 row">
                      <input name="config_name" class="form-control" autocomplete="off" />
                      <span class="error config_name"></span>
                    </div>

                    <div class="col-sm-7">
                      <input type="text" name="config_value" class="form-control" />
                      <span class="error config_value"></span>
                    </div>
                  </div>
                </div>

              <button id="submit" type="submit" class="btn btn-primary mb-2">Insert Config</button>
              <button type="button" class="btn btn-warning mb-2 cancel">Cancel</button>
            </form>
        </div>

    </div>
<!-- end container -->
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
    saveConfig();
    displayConfig();
    table();
    editCountry();
    delCountry();
    cancelUpdate();
  });

  function saveConfig()
  {
    $("#save_config").submit(function(e){
      e.preventDefault();
      var update = $("#submit").attr('update');
      var data = $(this).serializeArray();
      data.push({name:'update', value : update});

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          type : "POST",
          url : "{{ url('save-config') }}",
          data : data,
          dataType : 'json',
          beforeSend: function()
          {
            $("#submit").prop('disabled',true).html('Loading....');
          },
          success : function(result){
            
            if(result.status == 'error')
            {
              $(".error").show();
              $(".config_name").html(result.config_name); 
              $(".config_value").html(result.config_value);
              $("#submit").prop('disabled',false).html('Insert Config'); 
            }
            else if(result.status == 'errupdate') 
            {
              $(".error").show();
              $(".config_name").html(result.config_name); 
              $(".config_value").html(result.config_value);
              $("#submit").prop('disabled',false).html('Update');
            }
            else
            {
              $(".error").hide();
              clearForm();
              displayConfig();
              $("#submit").prop('disabled',false).html('Insert Config');
            }

            if(result.success == 1)
            {
              $("#msg").html('<div class="alert alert-success">'+result.msg+'</div>');
            }
            else
            {
              $("#msg").html('<div class="alert alert-danger">'+result.msg+'</div>');
            }
            $(".alert").delay(5000).fadeOut(2000);
          },
          error : function(xhr)
          {
            $("#submit").prop('disabled',true).html('Loading....');
            console.log(xhr.responseText);
          }
      });
    });
  }

  function displayConfig(){ 
    $.ajax({
      type : "GET",
      url : "{{ url('config-show') }}",
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

  function cancelUpdate()
  {
      $("body").on("click",".cancel",function(){
          clearForm();
      });
  }

  function clearForm()
  {
      $("input").val('');
      $("#submit").removeAttr('update');
      $("#submit").html('Insert Config');
      $(".cancel").hide();
  }

  function editCountry() {
    $(".cancel").hide();

    $("body").on("click",".cedit",function(){
      var id = $(this).attr('id');
      var name = $(this).attr('data-name');
      var code = $(this).attr('data-code');
       
      $("input[name='config_name']").val(name);
      $("input[name='config_value']").val(code);
      $("#submit").html('Update');
      $("#submit").attr('update',id);
      $(".cancel").show();

      $('html, body').animate({
          scrollTop: $("#save_config").offset().top
      }, 500);
    });
  }

  function delCountry() {
    $("body").on("click",".cdel",function(){
      var conf = confirm('Are you sure to delete this country?');
      var id = $(this).attr('id');

      if(conf == true)
      {
        $.ajax({
          type : "GET",
          url : "{{ url('country-del') }}",
          data : {id : id},
          dataType : 'json',
          success: function(result)
          {
            alert(result.msg);
            displayCountry();
          },
          error : function(xhr)
          {
            console.log(xhr.responseText);
          }
        });
      }
      else
      {
        return false;
      }
        
    });
  }

</script>
@endsection