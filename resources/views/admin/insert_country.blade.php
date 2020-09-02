@extends('layouts.admin')

@section('content')

<div class="container mb-2">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="msg"><!-- message --></div>
            <form id="save_country">
               <div class="form-group">
                  <label>Insert Country Calling Code*</label>
                  <div class="col-sm-12 row">
                    <div class="col-lg-3 row">
                      <input name="code_country" class="form-control" autocomplete="off" />
                      <span class="error code_country"></span>
                    </div>

                    <div class="col-sm-7">
                      <input type="text" name="country_name" class="form-control" />
                      <span class="error country_name"></span>
                    </div>
                  </div>
                </div>

              <button id="submit" type="submit" class="btn btn-primary mb-2">Insert Country</button>
              <button type="button" class="btn btn-warning mb-2 cancel">Cancel</button>
            </form>
        </div>

    </div>
<!-- end container -->
</div>  

<div class="container mt-5">
  <div class="col-lg-8" style="margin-left : auto; margin-right: auto;">
    <table class="table" id="country" style="width : 100%">
      <thead>
        <th>No</th>
        <th>Name</th>
        <th>Code</th>
        <th colspan="2">Action</th>
      </thead>
      <tbody id="display_country"></tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    saveCallingCode();
    displayCountry();
    table();
    editCountry();
    delCountry();
    cancelUpdate();
  });

  function saveCallingCode()
  {
    $("#save_country").submit(function(e){
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
          url : "{{ url('save-country') }}",
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
              $(".country_name").html(result.country_name); 
              $(".code_country").html(result.code_country);
              $("#submit").prop('disabled',false).html('Insert Country'); 
            }
            else if(result.status == 'errupdate') 
            {
              $(".error").show();
              $(".country_name").html(result.country_name); 
              $(".code_country").html(result.code_country);
              $("#submit").prop('disabled',false).html('Update');
            }
            else
            {
              $(".error").hide();
              clearForm();
              displayCountry();
              $("#submit").prop('disabled',false).html('Insert Country');
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

  function displayCountry(){ 
    $.ajax({
      type : "GET",
      url : "{{ url('country-show') }}",
      dataType : 'html',
      success: function(result)
      {
        $("#display_country").html(result);
      },
      error : function(xhr)
      {
        console.log(xhr.responseText);
      }
    });
  }

  function table(){
      $("#country").dataTable();
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
      $("#submit").html('Insert Country');
      $(".cancel").hide();
  }

  function editCountry() {
    $(".cancel").hide();

    $("body").on("click",".cedit",function(){
      var id = $(this).attr('id');
      var name = $(this).attr('data-name');
      var code = $(this).attr('data-code');
       
      $("input[name='country_name']").val(name);
      $("input[name='code_country']").val(code);
      $("#submit").html('Update');
      $("#submit").attr('update',id);
      $(".cancel").show();

      $('html, body').animate({
          scrollTop: $("#save_country").offset().top
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